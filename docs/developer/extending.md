# Extending WP Plugin Starter

> **Documentation Version**: This documentation is based on Dokan Kits version 3.0.0. Features and functionality may vary in other versions.

This guide provides detailed information on how to extend and customize Dokan Kits for developers. It covers various extension points and customization possibilities.

## 📋 Table of Contents

- [Extension Architecture Overview](#-extension-architecture-overview)
- [Custom Features](#-custom-features)
- [Adding Custom Settings](#-adding-custom-settings)
- [Custom Field Types](#-custom-field-types)
- [Template Customization](#-template-customization)
- [REST API Extensions](#-rest-api-extensions)
- [Frontend Customization](#-frontend-customization)
- [Integration with Other Plugins](#-integration-with-other-plugins)
- [Best Practices](#-best-practices)

## 🏗️ Extension Architecture Overview

Dokan Kits is built with extensibility in mind, providing several ways to customize and extend its functionality:

1. **Service Container**: A dependency injection container for managing services
2. **Feature Registry**: A registry for plugin features with dependency management
3. **Settings Registry**: A registry for structured settings registration
4. **Event Manager**: Centralized event handling system (hooks/filters)
5. **Template System**: Overridable templates for customization
6. **REST API**: Extensible REST API endpoints
7. **React Components**: Extendable admin UI components

### Extension Points

| Extension Point   | Purpose                                         | Use Case                                                |
| ----------------- | ----------------------------------------------- | ------------------------------------------------------- |
| Service Provider  | Register new services or override existing ones | Add custom functionality that needs service integration |
| Feature Registry  | Add new marketplace features                    | Create a new feature like custom vendor commission      |
| Settings Registry | Add custom settings                             | Add configuration options for your extension            |
| Hooks/Filters     | Modify behavior without changing core code      | Change how existing features work                       |
| Template Override | Change how templates are rendered               | Customize the appearance of frontend elements           |
| REST API Routes   | Add new API endpoints                           | Provide data for custom frontend features               |
| React Components  | Customize admin UI                              | Add custom tabs or fields to the admin interface        |

## 🧩 Custom Features

Create custom features by implementing the `FeatureInterface` and registering them with the Feature Registry.

### Creating a Custom Feature

1. Create a class that implements `FeatureInterface`:

```php
<?php
namespace YourNamespace\Features;

use Dokan_Kits\Core\Container;
use Dokan_Kits\Features\Base\FeatureInterface;

class YourCustomFeature implements FeatureInterface {
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function init() {
        // Initialize your feature
        add_action('init', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Add feature features
        add_action('dokan_kits_register_settings', [$this, 'register_settings']);
    }

    public function setup() {
        // Set up your feature
        // Register hooks, add filters, etc.
    }

    public function enqueue_scripts() {
        // Enqueue any required scripts or styles
        wp_enqueue_script(
            'your-feature-script',
            plugin_dir_url(__FILE__) . 'assets/js/your-feature.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }

    public function register_settings($registry) {
        // Register feature features
        $registry->add_section('advanced', 'your_feature', __('Your Feature', 'your-text-domain'))
                ->add_field('advanced', 'your_feature', [
                    'id' => 'your_feature_enabled',
                    'type' => 'toggle',
                    'label' => __('Enable Your Feature', 'your-text-domain'),
                    'default' => false,
                ]);
    }
}
```

2. Register your feature with the Feature Registry:

```php
add_action('dokan_kits_register_features', function($registry) {
    $registry->register_feature('your_feature', YourNamespace\Features\YourCustomFeature::class);
});
```

### Feature Integration Points

Your custom feature can integrate with Dokan Kits at several points:

1. **Vendor Dashboard**: Add new vendor dashboard menu items or pages
2. **Product Management**: Extend product management capabilities
3. **Order Processing**: Add custom order processing functionality
4. **Marketplace UI**: Enhance the customer-facing marketplace UI
5. **Admin Interface**: Add administration tools and settings

### Example: Custom Vendor Badge Feature

```php
<?php
namespace YourNamespace\Features;

use Dokan_Kits\Core\Container;
use Dokan_Kits\Features\Base\FeatureInterface;

class VendorBadgeFeature implements FeatureInterface {
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function init() {
        // Add vendor badge to store pages
        add_action('dokan_store_header_info_fields', [$this, 'display_vendor_badge'], 10, 2);

        // Add badge management in admin
        add_action('dokan_kits_register_settings', [$this, 'register_settings']);

        // Add badge field to vendor profile
        add_action('dokan_store_profile_saved', [$this, 'save_badge_field'], 10, 2);
        add_action('dokan_settings_form_bottom', [$this, 'add_badge_field'], 10, 2);
    }

    public function display_vendor_badge($vendor_id, $store_info) {
        // Get badge information
        $badge = get_user_meta($vendor_id, 'vendor_badge', true);
        if (!empty($badge)) {
            echo '<div class="vendor-badge">' . esc_html($badge) . '</div>';
        }
    }

    // Other methods for badge management...
}
```

## ➕ Adding Custom Settings

Extend Dokan Kits with custom settings using the Settings Registry.

### Adding a New Settings Section

```php
add_action('dokan_kits_register_settings', function($registry) {
    // Add a new section to an existing tab
    $registry->add_section('advanced', 'my_section', __('My Custom Section', 'your-text-domain'), __('Description for my section', 'your-text-domain'))
            ->add_field('advanced', 'my_section', [
                'id' => 'my_setting',
                'type' => 'text',
                'label' => __('My Setting', 'your-text-domain'),
                'description' => __('Description for my setting', 'your-text-domain'),
                'default' => '',
            ]);
});
```

### Adding a Complete New Tab

```php
add_action('dokan_kits_register_settings', function($registry) {
    // Add a new tab
    $registry->add_group('my_tab', __('My Tab', 'your-text-domain'), 'dashicons-admin-generic', 50)
            // Add a section to the new tab
            ->add_section('my_tab', 'general', __('General', 'your-text-domain'), __('General features for my tab', 'your-text-domain'))
            // Add fields to the section
            ->add_field('my_tab', 'general', [
                'id' => 'my_tab_enabled',
                'type' => 'toggle',
                'label' => __('Enable Feature', 'your-text-domain'),
                'default' => false,
            ])
            ->add_field('my_tab', 'general', [
                'id' => 'my_tab_text',
                'type' => 'text',
                'label' => __('Text Setting', 'your-text-domain'),
                'default' => '',
            ]);
});
```

### Using Conditional Fields

Create fields that appear only when certain conditions are met:

```php
add_action('dokan_kits_register_settings', function($registry) {
    $registry->add_section('my_tab', 'general', __('General', 'your-text-domain'))
            ->add_field('my_tab', 'general', [
                'id' => 'enable_feature_x',
                'type' => 'toggle',
                'label' => __('Enable Feature X', 'your-text-domain'),
                'default' => false,
            ])
            ->add_field('my_tab', 'general', [
                'id' => 'feature_x_option',
                'type' => 'text',
                'label' => __('Feature X Option', 'your-text-domain'),
                'default' => '',
                'conditional' => [
                    'depends_on' => 'enable_feature_x',
                    'value' => true,
                ],
            ]);
});
```

### Accessing Settings Values

Retrieve settings values in your code:

```php
// Get a specific setting
$options_manager = dokan_kits()->container()->get('options_manager');
$value = $options_manager->get_option('my_setting', 'default_value');

// Check if a feature is enabled
$feature_enabled = $options_manager->get_option('enable_feature_x', false);
if ($feature_enabled) {
    // Do something when the feature is enabled
}
```

## 🔧 Custom Field Types

Extend Dokan Kits by adding custom field types for settings or forms.

### Creating a Custom Field Type

1. Create a class that extends the `AbstractField` class:

```php
<?php
namespace YourNamespace\Fields;

use Dokan_Kits\Core\Fields\AbstractField;

class ColorPickerField extends AbstractField {
    protected $type = 'color_picker';

    public function __construct($args) {
        parent::__construct($args);
    }

    public function sanitize($value) {
        // Sanitize the color value
        return sanitize_hex_color($value);
    }

    public function render($value = null) {
        // Render the field HTML
        $value = !is_null($value) ? $value : $this->default;
        ?>
        <div class="wp-plugin-starter-color-picker-field">
            <label for="<?php echo esc_attr($this->id); ?>">
                <?php echo esc_html($this->label); ?>
            </label>
            <input
                type="text"
                id="<?php echo esc_attr($this->id); ?>"
                name="<?php echo esc_attr($this->id); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="wp-plugin-starter-color-field"
            />
            <?php if (!empty($this->description)) : ?>
                <p class="description"><?php echo esc_html($this->description); ?></p>
            <?php endif; ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('.wp-plugin-starter-color-field').wpColorPicker();
            });
        </script>
        <?php
    }
}
```

2. Register the field type with the Field Registry:

```php
add_action('init', function() {
    $field_registry = dokan_kits()->container()->get('field_registry');
    $field_registry->register_field_type('color_picker', YourNamespace\Fields\ColorPickerField::class);
});
```

3. Use your custom field type in settings:

```php
add_action('dokan_kits_register_settings', function($registry) {
    $registry->add_section('display', 'colors', __('Colors', 'your-text-domain'))
            ->add_field('display', 'colors', [
                'id' => 'primary_color',
                'type' => 'color_picker',
                'label' => __('Primary Color', 'your-text-domain'),
                'default' => '#f86e01',
            ]);
});
```

### Creating a React Component for Custom Field

For the admin interface, create a corresponding React component:

```jsx
// src/admin/components/fields/color-picker/index.js
import { useState } from '@wordpress/element';
import { ColorPicker } from '@wordpress/components';

const ColorPickerField = ({ field, value, onChange }) => {
	const [color, setColor] = useState(value || field.default || '#ffffff');

	const handleChange = (newColor) => {
		setColor(newColor);
		onChange(newColor);
	};

	return (
		<div className="wp-plugin-starter-color-picker-field">
			<label className="wp-plugin-starter-field-label">{field.label}</label>
			<ColorPicker color={color} onChangeComplete={(newColor) => handleChange(newColor.hex)} disableAlpha />
			{field.description && <p className="wp-plugin-starter-field-description">{field.description}</p>}
		</div>
	);
};

export default ColorPickerField;
```

Then register it in the field component mapping:

```jsx
// src/admin/components/fields/index.js
import ColorPickerField from './color-picker';

// Existing field components...
const FIELD_COMPONENTS = {
	toggle: ToggleField,
	text: TextField,
	select: SelectField,
	// Add your custom field
	color_picker: ColorPickerField,
	// Other field types...
};
```

## 📝 Template Customization

Customize how Dokan Kits content is displayed by overriding templates.

### Template Override System

Dokan Kits uses a template system that allows themes to override plugin templates:

1. Templates are located in the `templates/` directory of the plugin
2. Templates are organized by context (admin, frontend, etc.)
3. Templates can be overridden by placing a file in your theme's `wp-plugin-starter/` directory

### Overriding a Template

To override a template:

1. Locate the template in the plugin: `wp-plugin-starter/templates/frontend/product/image-validator.php`
2. Create a directory structure in your theme: `your-theme/wp-plugin-starter/frontend/product/`
3. Copy the template file to your theme: `your-theme/wp-plugin-starter/frontend/product/image-validator.php`
4. Modify the template file as needed

### Adding a Custom Template Path

You can register additional template directories:

```php
add_action('init', function() {
    $template_manager = dokan_kits()->container()->get('template_manager');
    $template_manager->add_template_path('/path/to/your/templates', 20);
});
```

### Using Template Functions

Load templates in your code:

```php
$template_manager = dokan_kits()->container()->get('template_manager');

// Load a template with variables
$template_manager->get_template('frontend/product/image-validator.php', [
    'product' => $product,
    'features' => $settings,
]);
```

## 🌐 REST API Extensions

Extend the Dokan Kits REST API with custom endpoints.

### Creating a Custom API Controller

1. Create a controller class that extends the AbstractController:

```php
<?php
namespace YourNamespace\Rest\Controllers;

use Dokan_Kits\REST\AbstractController;
use Dokan_Kits\REST\Api_Response;
use WP_REST_Request;

class YourCustomController extends AbstractController {
    protected $namespace = 'wp-plugin-starter/v1';
    protected $rest_base = 'your-endpoint';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_items'],
                    'permission_callback' => [$this, 'permissions_check'],
                ],
                'schema' => [$this, 'get_item_schema'],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_item'],
                    'permission_callback' => [$this, 'permissions_check'],
                    'args' => [
                        'id' => [
                            'required' => true,
                            'validate_callback' => function($param) {
                                return is_numeric($param);
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    public function permissions_check($request) {
        return current_user_can('manage_options');
    }

    public function get_items($request) {
        // Fetch and return items
        $items = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ];

        return Api_Response::success($items);
    }

    public function get_item($request) {
        $id = $request['id'];

        // Fetch and return a specific item
        $item = ['id' => $id, 'name' => 'Item ' . $id];

        return Api_Response::success($item);
    }

    public function get_item_schema() {
        return [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'custom_item',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'description' => __('Unique identifier for the item', 'your-text-domain'),
                    'type' => 'integer',
                    'readonly' => true,
                ],
                'name' => [
                    'description' => __('Name of the item', 'your-text-domain'),
                    'type' => 'string',
                ],
            ],
        ];
    }
}
```

2. Register your controller with the REST API:

```php
add_action('dokan_kits_register_controllers', function($container) {
    $container->factory('rest.controllers.your_custom_controller', function($container) {
        return new YourNamespace\Rest\Controllers\YourCustomController();
    });
});
```

### Using ApiResponse Helper

Use the ApiResponse class for standardized responses:

```php
use Dokan_Kits\REST\Api_Response;

// Success response
return Api_Response::success($data, 'Operation successful', 200);

// Error response
return Api_Response::error('Something went wrong', 'error_code', 400, $error_data);
```

### Consuming the API in JavaScript

Access your API endpoint in JavaScript:

```javascript
import apiFetch from '@wordpress/api-fetch';

// GET request
const getItems = async () => {
	try {
		const response = await apiFetch({
			path: '/wp-plugin-starter/v1/your-endpoint',
		});
		return response;
	} catch (error) {
		console.error('Error fetching items:', error);
		throw error;
	}
};

// POST request
const createItem = async (itemData) => {
	try {
		const response = await apiFetch({
			path: '/wp-plugin-starter/v1/your-endpoint',
			method: 'POST',
			data: itemData,
		});
		return response;
	} catch (error) {
		console.error('Error creating item:', error);
		throw error;
	}
};
```

## 🎨 Frontend Customization

Extend and customize the Dokan Kits frontend experience.

### Adding Custom JavaScript

Add custom JavaScript to enhance the frontend:

```php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'your-custom-script',
        plugin_dir_url(__FILE__) . 'assets/js/custom-script.js',
        ['jquery', 'wp-plugin-starter-frontend'],
        '1.0.0',
        true
    );

    // Pass data to your script
    wp_localize_script('your-custom-script', 'yourScriptData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('your-custom-nonce'),
        'someOption' => 'value'
    ]);
});
```

### Adding Custom CSS

Add custom styles to modify the appearance:

```php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'your-custom-style',
        plugin_dir_url(__FILE__) . 'assets/css/custom-style.css',
        ['wp-plugin-starter-frontend'],
        '1.0.0'
    );
});
```

### Custom Frontend Hooks

Take advantage of Dokan Kits frontend hooks:

```php
// Add content before product title
add_action('dokan_kits_before_product_title', function($product) {
    if ($product->is_featured()) {
        echo '<span class="featured-badge">Featured</span>';
    }
});

// Modify vendor store info
add_filter('dokan_kits_vendor_store_info', function($store_info, $vendor_id) {
    // Add custom data to store info
    $store_info['custom_rating'] = get_user_meta($vendor_id, 'custom_rating', true);
    return $store_info;
}, 10, 2);
```

### Gutenberg Blocks

Add custom Gutenberg blocks for the frontend:

1. Register a block in JavaScript:

```javascript
// src/frontend/blocks/featured-vendors/index.js
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';

registerBlockType('wp-plugin-starter-extension/featured-vendors', {
	title: __('Featured Vendors', 'your-text-domain'),
	icon: 'store',
	category: 'dokan-blocks',
	attributes: {
		numberOfVendors: {
			type: 'number',
			default: 4,
		},
		showRating: {
			type: 'boolean',
			default: true,
		},
	},
	edit,
	save,
});
```

2. Create the edit component:

```javascript
// src/frontend/blocks/featured-vendors/edit.js
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

const Edit = ({ attributes, setAttributes }) => {
	const { numberOfVendors, showRating } = attributes;
	const [vendors, setVendors] = useState([]);
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		fetchVendors();
	}, [numberOfVendors]);

	const fetchVendors = async () => {
		setLoading(true);
		try {
			const response = await apiFetch({
				path: `/dokan/v1/stores?per_page=${numberOfVendors}&featured=true`,
			});
			setVendors(response);
		} catch (error) {
			console.error('Error fetching vendors:', error);
		} finally {
			setLoading(false);
		}
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'your-text-domain')}>
					<RangeControl
						label={__('Number of Vendors', 'your-text-domain')}
						value={numberOfVendors}
						onChange={(value) => setAttributes({ numberOfVendors: value })}
						min={1}
						max={12}
					/>
					<ToggleControl
						label={__('Show Rating', 'your-text-domain')}
						checked={showRating}
						onChange={(value) => setAttributes({ showRating: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>
				<h3>{__('Featured Vendors', 'your-text-domain')}</h3>

				{loading ? (
					<Spinner />
				) : (
					<div className="featured-vendors-grid">
						{vendors.map((vendor) => (
							<div key={vendor.id} className="vendor-card">
								<img src={vendor.banner} alt={vendor.store_name} />
								<h4>{vendor.store_name}</h4>
								{showRating && <div className="vendor-rating">Rating: {vendor.rating.rating}/5</div>}
							</div>
						))}
					</div>
				)}
			</div>
		</>
	);
};

export default Edit;
```

3. Register the block in PHP:

```php
add_action('init', function() {
    register_block_type('wp-plugin-starter-extension/featured-vendors', [
        'editor_script' => 'wp-plugin-starter-extension-blocks',
        'render_callback' => 'render_featured_vendors_block',
        'attributes' => [
            'numberOfVendors' => [
                'type' => 'number',
                'default' => 4
            ],
            'showRating' => [
                'type' => 'boolean',
                'default' => true
            ]
        ]
    ]);
});

function render_featured_vendors_block($attributes) {
    // Server-side rendering logic
    $number = isset($attributes['numberOfVendors']) ? intval($attributes['numberOfVendors']) : 4;
    $show_rating = isset($attributes['showRating']) ? (bool)$attributes['showRating'] : true;

    // Get featured vendors
    $vendors = dokan()->vendor->get_featured(
        [
            'number' => $number,
            'status' => 'approve'
        ]
    );

    // Return HTML output
    ob_start();
    ?>
    <div class="wp-block-wp-plugin-starter-extension-featured-vendors">
        <h3><?php esc_html_e('Featured Vendors', 'your-text-domain'); ?></h3>

        <div class="featured-vendors-grid">
            <?php foreach ($vendors as $vendor) : ?>
                <div class="vendor-card">
                    <img src="<?php echo esc_url($vendor->get_banner()); ?>" alt="<?php echo esc_attr($vendor->get_shop_name()); ?>" />
                    <h4><?php echo esc_html($vendor->get_shop_name()); ?></h4>
                    <?php if ($show_rating) : ?>
                        <div class="vendor-rating">
                            Rating: <?php echo esc_html($vendor->get_rating()['rating']); ?>/5
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
```

## 🔄 Integration with Other Plugins

Integrate Dokan Kits with other WordPress plugins.

### WooCommerce Integration

Extend WooCommerce functionality with Dokan Kits:

```php
// Add a custom field to WooCommerce product data tabs
add_filter('woocommerce_product_data_tabs', function($tabs) {
    $tabs['dokan_kits_custom'] = [
        'label' => __('Dokan Kits Custom', 'your-text-domain'),
        'target' => 'dokan_kits_custom_product_data',
        'class' => ['show_if_simple', 'show_if_variable'],
    ];
    return $tabs;
});

// Add content to the custom product data tab
add_action('woocommerce_product_data_panels', function() {
    ?>
    <div id="dokan_kits_custom_product_data" class="panel woocommerce_options_panel">
        <?php
        woocommerce_wp_text_input([
            'id' => '_dokan_kits_custom_field',
            'label' => __('Custom Field', 'your-text-domain'),
            'placeholder' => __('Enter value', 'your-text-domain'),
            'desc_tip' => true,
            'description' => __('This is a custom field added by your Dokan Kits extension.', 'your-text-domain'),
        ]);
        ?>
    </div>
    <?php
});

// Save the custom field value
add_action('woocommerce_process_product_meta', function($post_id) {
    $custom_field = isset($_POST['_dokan_kits_custom_field']) ? sanitize_text_field($_POST['_dokan_kits_custom_field']) : '';
    update_post_meta($post_id, '_dokan_kits_custom_field', $custom_field);
});
```

### Dokan Pro Integration

Integrate with Dokan Pro features:

```php
// Check if Dokan Pro is active
function is_dokan_pro_active() {
    return class_exists('WeDevs_Dokan_Pro');
}

// Add functionality that depends on Dokan Pro
add_action('init', function() {
    if (is_dokan_pro_active()) {
        // Add integration with Dokan Pro features
        add_filter('dokan_pro_subscription_product_types', function($types) {
            $types[] = 'your_custom_type';
            return $types;
        });

        // Add custom vendor verification field
        add_filter('dokan_verification_fields', function($fields) {
            $fields['custom_id'] = [
                'label' => __('Custom ID', 'your-text-domain'),
                'type' => 'text',
                'required' => false,
            ];
            return $fields;
        });
    }
});
```

### WPML/Polylang Integration

Make your extension multilingual-friendly:

```php
// Register strings for translation
add_action('init', function() {
    if (function_exists('icl_register_string')) {
        // WPML
        icl_register_string('your-text-domain', 'custom_label', 'Your custom label');
    } elseif (function_exists('pll_register_string')) {
        // Polylang
        pll_register_string('custom_label', 'Your custom label', 'your-text-domain');
    }
});

// Get translated strings
function get_translated_string($string, $name) {
    if (function_exists('icl_t')) {
        // WPML
        return icl_t('your-text-domain', $name, $string);
    } elseif (function_exists('pll__')) {
        // Polylang
        return pll__($string);
    }

    return $string;
}
```

## 🚩 Best Practices

Follow these best practices when extending Dokan Kits:

### Code Organization

1. **Namespace Your Code**: Use unique namespaces to avoid conflicts
2. **Follow PSR-4**: Organize your code following PSR-4 standards
3. **Modular Structure**: Keep features in separate classes
4. **Dependency Injection**: Use the container for managing dependencies

### Performance Considerations

1. **Selective Loading**: Only load code when needed
2. **Asset Optimization**: Minimize and combine CSS/JS files
3. **Database Efficiency**: Optimize database queries
4. **Caching**: Implement caching for expensive operations

### Security Best Practices

1. **Input Validation**: Always validate and sanitize input
2. **Output Escaping**: Escape output to prevent XSS
3. **Capability Checks**: Verify user capabilities before actions
4. **AJAX Nonces**: Use nonces for AJAX requests

### Compatibility

1. **WordPress Standards**: Follow WordPress coding standards
2. **Theme Compatibility**: Test with multiple themes
3. **Plugin Compatibility**: Check for conflicts with popular plugins
4. **Version Support**: Support multiple versions of Dokan

### Documentation

1. **Code Comments**: Document your code with proper comments
2. **README**: Include clear documentation with your extension
3. **Changelog**: Maintain a detailed changelog
4. **Examples**: Provide usage examples

### Testing

1. **Unit Tests**: Write tests for your functionality
2. **Integration Tests**: Test integration with Dokan Kits
3. **Cross-browser Testing**: Test in multiple browsers
4. **Responsive Testing**: Test on various screen sizes

## 🧪 Development Environment

Set up a proper development environment for Dokan Kits extensions:

1. **Local Development Server**:

    - Use Local by Flywheel, DevKinsta, or similar
    - Install WordPress, WooCommerce, and Dokan
    - Install Dokan Kits

2. **Version Control**:

    - Use Git for version control
    - Structure your repository properly

3. **Build System**:

    - Use npm or yarn for JavaScript dependencies
    - Configure webpack for asset building
    - Set up Composer for PHP dependencies

4. **Debug Tools**:

    - Enable WP_DEBUG in wp-config.php
    - Use Query Monitor for database debugging
    - Use browser developer tools for frontend debugging

5. **Code Quality Tools**:
    - Configure PHPCS for PHP code standards
    - Use ESLint for JavaScript code quality
    - Implement SCSS linting for styles

By following these guidelines and using the extension points provided by Dokan Kits, you can create powerful extensions that enhance the functionality of Dokan-powered marketplaces while maintaining compatibility and performance.
