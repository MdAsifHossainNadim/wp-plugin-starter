# Building Extensions for WP Plugin Starter

> **Documentation Version**: This documentation is based on Dokan Kits version 3.0.0. Features and functionality may vary in other versions.

## Introduction

Dokan Kits is designed to be extended with additional functionality through extensions. This guide will walk you through the process of creating well-structured, maintainable extensions for Dokan Kits that follow best practices and integrate seamlessly with the core plugin.

## Extension Architecture

A well-structured Dokan Kits extension should follow these architectural principles:

1. **Modular Design**: Organize code into logical components
2. **Dependency Injection**: Utilize the Dokan Kits DI container
3. **Clean API**: Provide clear interfaces for other extensions
4. **Proper Namespacing**: Use appropriate namespaces to avoid conflicts
5. **Consistent Hooks**: Follow the Dokan Kits hook naming conventions

## Directory Structure

A typical Dokan Kits extension should have the following structure:

```
my-wp-plugin-starter-extension/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   ├── Admin/
│   ├── Frontend/
│   ├── REST/
│   ├── Models/
│   ├── DataStores/
│   ├── Providers/
│   └── class-my-extension.php
├── languages/
├── templates/
├── vendor/
├── my-wp-plugin-starter-extension.php
├── readme.txt
├── LICENSE
└── composer.json
```

## Extension Bootstrap

Your main plugin file (`my-wp-plugin-starter-extension.php`) should:

1. Check for Dokan Kits dependencies
2. Register hooks for activation/deactivation
3. Initialize your extension

Here's a template:

```php
<?php
/**
 * Plugin Name: My Dokan Kits Extension
 * Description: Extends Dokan Kits with additional functionality
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: my-wp-plugin-starter-extension
 * Domain Path: /languages
 * Requires at least: 6.4.2
 * Requires PHP: 7.4
 * WC requires at least: 7.9
 * WC tested up to: 9.8.3
 * Dokan requires at least: 3.9.7
 * Dokan tested up to: 4.0.1
 * Dokan Kits requires at least: 3.0.0
 * Dokan Kits tested up to: 3.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'MY_DOKAN_KITS_EXTENSION_VERSION', '1.0.0' );
define( 'MY_DOKAN_KITS_EXTENSION_FILE', __FILE__ );
define( 'MY_DOKAN_KITS_EXTENSION_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_DOKAN_KITS_EXTENSION_URL', plugins_url( '', __FILE__ ) );
define( 'MY_DOKAN_KITS_EXTENSION_ASSETS_URL', MY_DOKAN_KITS_EXTENSION_URL . '/assets' );
define( 'MY_DOKAN_KITS_EXTENSION_TEMPLATE_PATH', MY_DOKAN_KITS_EXTENSION_PATH . 'templates/' );

/**
 * Class My_Dokan_Kits_Extension
 */
class My_Dokan_Kits_Extension {
    /**
     * The single instance of the class.
     *
     * @var My_Dokan_Kits_Extension
     */
    protected static $_instance = null;

    /**
     * Main instance
     *
     * @return My_Dokan_Kits_Extension
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        // Check dependencies
        add_action( 'plugins_loaded', array( $this, 'check_dependencies' ) );
        
        // Register activation/deactivation hooks
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        
        // Initialize after Dokan Kits is loaded
        add_action( 'dokan_kits_loaded', array( $this, 'init' ) );
    }

    /**
     * Check if Dokan Kits is active and the correct version
     */
    public function check_dependencies() {
        // Check if Dokan Kits is active
        if ( ! class_exists( 'Dokan_Kits' ) ) {
            add_action( 'admin_notices', function() {
                echo '<div class="error"><p>';
                echo esc_html__( 'My Dokan Kits Extension requires Dokan Kits to be installed and active.', 'my-wp-plugin-starter-extension' );
                echo '</p></div>';
            } );
            return;
        }
        
        // Check for the minimum version
        if ( version_compare( DOKAN_KITS_VERSION, '3.0.0', '<' ) ) {
            add_action( 'admin_notices', function() {
                echo '<div class="error"><p>';
                echo esc_html__( 'My Dokan Kits Extension requires Dokan Kits version 3.0.0 or higher.', 'my-wp-plugin-starter-extension' );
                echo '</p></div>';
            } );
            return;
        }
        
        // Load text domain
        load_plugin_textdomain( 'my-wp-plugin-starter-extension', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Initialize the extension
     */
    public function init() {
        // Include files
        $this->includes();
        
        // Register provider with Dokan Kits container
        $this->register_provider();
        
        /**
         * Action fired when extension is initialized
         */
        do_action( 'my_dokan_kits_extension_initialized', $this );
    }

    /**
     * Include required files
     */
    private function includes() {
        // Main extension class
        require_once MY_DOKAN_KITS_EXTENSION_PATH . 'includes/class-my-extension.php';
        
        // Service provider
        require_once MY_DOKAN_KITS_EXTENSION_PATH . 'includes/Providers/class-service-provider.php';
    }

    /**
     * Register service provider with Dokan Kits container
     */
    private function register_provider() {
        $container = dokan_kits_get_container();
        $container->addServiceProvider( new \MyDokan_KitsExtension\Providers\ServiceProvider() );
    }

    /**
     * Activation hook
     */
    public function activate() {
        // Create necessary database tables, etc.
    }

    /**
     * Deactivation hook
     */
    public function deactivate() {
        // Clean up if necessary
    }
}

// Initialize the extension
function my_dokan_kits_extension() {
    return My_Dokan_Kits_Extension::instance();
}

// Global function for accessing the extension
my_dokan_kits_extension();
```

## Service Provider

The Service Provider is a key component that registers your extension's services with the Dokan Kits DI container:

```php
<?php

namespace MyDokan_KitsExtension\Providers;

use Dokan_Kits\Core\DI\Providers\ServiceProviderInterface;
use Dokan_Kits\Core\DI\Container;
use MyDokan_KitsExtension\Admin\AdminController;
use MyDokan_KitsExtension\Frontend\FrontendController;
use MyDokan_KitsExtension\REST\APIController;
use MyDokan_KitsExtension\DataStores\CustomModelDataStore;
use MyDokan_KitsExtension\Models\CustomModel;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface {
    /**
     * Register services with the container.
     *
     * @param Container $container The DI container.
     *
     * @return void
     */
    public function register( Container $container ): void {
        // Register data stores
        $container->addShared( 'my_extension.data_store', CustomModelDataStore::class );
        
        // Register models
        $container->addShared( 'my_extension.model', CustomModel::class );
        
        // Register controllers
        $container->addShared( 'my_extension.admin', AdminController::class )
            ->addArgument( 'my_extension.model' );
            
        $container->addShared( 'my_extension.frontend', FrontendController::class )
            ->addArgument( 'my_extension.model' );
            
        // Register REST API controller
        $container->add( 'my_extension.api', APIController::class )
            ->addArgument( 'my_extension.model' );
            
        // Add REST API controller to Dokan Kits REST services
        $container->extend( 'rest-service', function( $services, $container ) {
            $services[] = $container->get( 'my_extension.api' );
            return $services;
        });
        
        // Register any hooks, filters, or actions
        $this->register_hooks( $container );
    }
    
    /**
     * Register hooks with WordPress
     *
     * @param Container $container The DI container.
     *
     * @return void
     */
    protected function register_hooks( Container $container ): void {
        // Admin hooks
        add_action( 'admin_enqueue_scripts', [ $container->get( 'my_extension.admin' ), 'enqueue_scripts' ] );
        
        // Frontend hooks
        add_action( 'wp_enqueue_scripts', [ $container->get( 'my_extension.frontend' ), 'enqueue_scripts' ] );
        
        // Filter to extend Dokan Kits functionality
        add_filter( 'dokan_kits_some_filter', [ $container->get( 'my_extension.model' ), 'filter_method' ] );
    }
}
```

## Creating Models & Data Stores

Extensions can define their own models and data stores that follow the Dokan Kits pattern:

```php
<?php

namespace MyDokan_KitsExtension\Models;

use Dokan_Kits\Core\Data\Model;

class CustomModel extends Model {
    /**
     * Data structure
     */
    protected $data = [
        'name' => '',
        'value' => '',
        'date_created' => null,
        'date_modified' => null,
    ];
    
    /**
     * Object type
     */
    protected $object_type = 'my_extension_custom';
    
    /**
     * Constructor
     */
    public function __construct( array $data = [], int $read = 0 ) {
        // Initialize the model
        parent::__construct( $read );
        
        // Set up properties
        $this->data_store = \WC_Data_Store::load( 'my_extension_custom' );
        
        // Set ID if provided
        if ( ! empty( $data['id'] ) ) {
            $this->set_id( $data['id'] );
        }
        
        // Set other properties
        if ( ! empty( $data['name'] ) ) {
            $this->set_name( $data['name'] );
        }
        
        // Read from database if ID is set
        if ( $read && $this->get_id() ) {
            $this->data_store->read( $this );
        }
    }
    
    // Getters and setters
    public function get_name( $context = 'view' ) {
        return $this->get_prop( 'name', $context );
    }
    
    public function set_name( $name ) {
        $this->set_prop( 'name', sanitize_text_field( $name ) );
    }
}
```

Then create a corresponding data store:

```php
<?php

namespace MyDokan_KitsExtension\DataStores;

use Dokan_Kits\Core\Data\Data_Store;
use MyDokan_KitsExtension\Models\CustomModel;

class CustomModelDataStore extends Data_Store {
    /**
     * Meta type
     */
    protected $meta_type = 'my_extension_custom';
    
    /**
     * Object type
     */
    protected $object_type = 'my_extension_custom';
    
    /**
     * Initialize the data store
     */
    public function initialize(): void {
        global $wpdb;
        
        // Create table if it doesn't exist
        $table_name = $this->table_name;
        $collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';
        
        $schema = "CREATE TABLE {$table_name} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            value TEXT NULL,
            date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            date_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id),
            KEY name (name)
        ) $collate;";
        
        // Use dbDelta to create/update the table
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $schema );
    }
    
    // Implement CRUD methods
    public function create( &$model ): void {
        // Implementation
    }
    
    public function read( &$model ): void {
        // Implementation
    }
    
    public function update( &$model ): void {
        // Implementation
    }
    
    public function delete( &$model, $args = array() ): bool {
        // Implementation
        return true;
    }
}
```

## Adding REST API Endpoints

Extensions can add their own REST API endpoints:

```php
<?php

namespace MyDokan_KitsExtension\REST;

use Dokan_Kits\REST\Controllers\Abstract_Controller;
use WP_REST_Server;
use WP_REST_Request;

class APIController extends Abstract_Controller {
    /**
     * Route base
     */
    protected $rest_base = 'my-extension';
    
    /**
     * Model reference
     */
    protected $model;
    
    /**
     * Constructor
     */
    public function __construct( $model ) {
        $this->model = $model;
    }
    
    /**
     * Register routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_item' ],
                    'permission_callback' => [ $this, 'create_item_permissions_check' ],
                ],
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_item' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'description' => __( 'Unique identifier for the object.', 'my-wp-plugin-starter-extension' ),
                            'type'        => 'integer',
                            'required'    => true,
                        ],
                    ],
                ],
            ]
        );
    }
    
    /**
     * Check permissions for getting items
     */
    public function get_items_permissions_check( $request ) {
        return true; // Adjust based on your needs
    }
    
    /**
     * Get items
     */
    public function get_items( $request ) {
        // Implementation
        return $this->success( [ 'message' => 'Items retrieved successfully' ] );
    }
    
    // Other endpoint implementations
}
```

## Adding Admin Pages

Extensions can add admin pages to the Dokan Kits admin area:

```php
<?php

namespace MyDokan_KitsExtension\Admin;

class AdminController {
    /**
     * Model reference
     */
    protected $model;
    
    /**
     * Constructor
     */
    public function __construct( $model ) {
        $this->model = $model;
        
        // Add menu page
        add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
    }
    
    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_submenu_page(
            'wp-plugin-starter',
            __( 'My Extension', 'my-wp-plugin-starter-extension' ),
            __( 'My Extension', 'my-wp-plugin-starter-extension' ),
            'manage_options',
            'my-wp-plugin-starter-extension',
            [ $this, 'render_page' ]
        );
    }
    
    /**
     * Render page
     */
    public function render_page() {
        // For React admin pages
        echo '<div id="my-wp-plugin-starter-extension-admin-root"></div>';
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
        
        if ( $screen && $screen->id === 'wp-plugin-starter_page_my-wp-plugin-starter-extension' ) {
            wp_enqueue_style(
                'my-wp-plugin-starter-extension-admin',
                MY_DOKAN_KITS_EXTENSION_ASSETS_URL . '/css/admin.css',
                [],
                MY_DOKAN_KITS_EXTENSION_VERSION
            );
            
            wp_enqueue_script(
                'my-wp-plugin-starter-extension-admin',
                MY_DOKAN_KITS_EXTENSION_ASSETS_URL . '/js/admin.js',
                [ 'react', 'react-dom', 'wp-element', 'wp-components', 'wp-api-fetch' ],
                MY_DOKAN_KITS_EXTENSION_VERSION,
                true
            );
            
            wp_localize_script(
                'my-wp-plugin-starter-extension-admin',
                'myDokan_KitsExtension',
                [
                    'apiUrl' => rest_url( 'wp-plugin-starter/v1/my-extension' ),
                    'nonce' => wp_create_nonce( 'wp_rest' ),
                ]
            );
        }
    }
}
```

## Adding React Components

Extensions can add their own React components that integrate with the Dokan Kits admin:

```jsx
// src/admin/app.js
import { render } from '@wordpress/element';
import { SlotFillProvider } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

import MyExtensionApp from './components/MyExtensionApp';

// Set up headers for API requests
apiFetch.use( apiFetch.createNonceMiddleware( myDokan_KitsExtension.nonce ) );

// Render the app
document.addEventListener( 'DOMContentLoaded', () => {
    const container = document.getElementById( 'my-wp-plugin-starter-extension-admin-root' );
    
    if ( container ) {
        render(
            <SlotFillProvider>
                <MyExtensionApp />
            </SlotFillProvider>,
            container
        );
    }
} );
```

Then create your component:

```jsx
// src/admin/components/MyExtensionApp.js
import { useState, useEffect } from '@wordpress/element';
import { Button, Card, CardHeader, CardBody } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

const MyExtensionApp = () => {
    const [data, setData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    
    useEffect(() => {
        fetchData();
    }, []);
    
    const fetchData = async () => {
        try {
            setIsLoading(true);
            const response = await apiFetch({
                path: myDokan_KitsExtension.apiUrl,
            });
            
            if (response.success) {
                setData(response.data);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setIsLoading(false);
        }
    };
    
    return (
        <div className="my-extension-app">
            <Card>
                <CardHeader>
                    <h2>{__('My Extension', 'my-wp-plugin-starter-extension')}</h2>
                </CardHeader>
                <CardBody>
                    {isLoading ? (
                        <p>{__('Loading...', 'my-wp-plugin-starter-extension')}</p>
                    ) : (
                        <div>
                            {/* Render your data */}
                            <pre>{JSON.stringify(data, null, 2)}</pre>
                            <Button isPrimary onClick={fetchData}>
                                {__('Refresh', 'my-wp-plugin-starter-extension')}
                            </Button>
                        </div>
                    )}
                </CardBody>
            </Card>
        </div>
    );
};

export default MyExtensionApp;
```

## Adding Custom Templates

Extensions can provide custom templates:

```php
// Get template from extension
function my_extension_get_template( $template_name, $args = array() ) {
    // Look in theme/child theme
    $template = locate_template(
        array(
            'wp-plugin-starter/my-extension/' . $template_name,
            'my-extension/' . $template_name,
        )
    );
    
    // If not found in theme, use plugin templates
    if ( ! $template ) {
        $template = MY_DOKAN_KITS_EXTENSION_TEMPLATE_PATH . $template_name;
    }
    
    // Allow other plugins to override the template
    $template = apply_filters( 'my_dokan_kits_extension_get_template', $template, $template_name, $args );
    
    // Extract args
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }
    
    // Include the template
    include $template;
}
```

## Extension Settings

Extensions should integrate with the Dokan Kits settings system:

```php
// Add settings tab
add_filter( 'dokan_kits_settings_tabs', function( $tabs ) {
    $tabs['my_extension'] = __( 'My Extension', 'my-wp-plugin-starter-extension' );
    return $tabs;
} );

// Add settings fields
add_filter( 'dokan_kits_settings_fields', function( $fields ) {
    $fields['my_extension'] = [
        'section_title' => [
            'title' => __( 'My Extension SettingsModel', 'my-wp-plugin-starter-extension' ),
            'type'  => 'title',
            'desc'  => __( 'Configure settings for My Extension.', 'my-wp-plugin-starter-extension' ),
        ],
        'enabled' => [
            'title'   => __( 'Enable/Disable', 'my-wp-plugin-starter-extension' ),
            'desc'    => __( 'Enable My Extension features', 'my-wp-plugin-starter-extension' ),
            'type'    => 'toggle',
            'default' => 'yes',
        ],
        'option_name' => [
            'title'   => __( 'Option Name', 'my-wp-plugin-starter-extension' ),
            'desc'    => __( 'Description of this option.', 'my-wp-plugin-starter-extension' ),
            'type'    => 'text',
            'default' => '',
        ],
        'section_end' => [
            'type' => 'sectionend',
        ],
    ];
    
    return $fields;
} );
```

## Best Practices

1. **Follow Coding Standards**: Adhere to WordPress and Dokan Kits coding standards
2. **Use Namespaces**: Properly namespace your code to avoid conflicts
3. **Leverage the DI Container**: Use the Dokan Kits DI container for managing dependencies
4. **Respect Hooks**: Use appropriate hooks and follow naming conventions
5. **Access Data Properly**: Use models and data stores instead of direct database queries
6. **Sanitize and Validate**: Always sanitize inputs and validate data
7. **Internationalization**: Make your extension translatable
8. **Documentation**: Document your code properly

## Publishing Your Extension

When your extension is ready for release:

1. **Version Carefully**: Use semantic versioning (MAJOR.MINOR.PATCH)
2. **Document Requirements**: Clearly specify Dokan Kits version requirements
3. **Write Documentation**: Provide comprehensive documentation
4. **Include Examples**: Show how to use your extension
5. **Test Thoroughly**: Test with different WordPress, WooCommerce, and Dokan configurations

## Distribution Considerations

1. **WordPress.org**: Follow WordPress.org guidelines if distributing there
2. **Commercial Extensions**: Consider a licensing system for commercial extensions
3. **Updates**: Implement a reliable update mechanism
4. **Support**: Provide clear support channels and documentation

## Testing Extensions

Properly testing your Dokan Kits extension is crucial for ensuring quality and preventing regressions. Here are recommended testing approaches:

### Unit Testing

Dokan Kits extensions should include unit tests for critical functionality:

```php
<?php
/**
 * Example unit test case for your extension
 */
class My_Extension_Test extends WP_UnitTestCase {
    public function test_something() {
        // Test your functionality
        $model = new \MyDokan_KitsExtension\Models\CustomModel();
        $model->set_name('Test Name');
        
        $this->assertEquals('Test Name', $model->get_name());
    }
}
```

PHPUnit configuration can follow this structure in your extension's phpunit.xml:

```xml
<phpunit
    bootstrap="tests/php/bootstrap.php"
    backupGlobals="false"
    colors="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true"
>
    <testsuites>
        <testsuite name="My Extension Test Suite">
            <directory suffix=".php">./tests/php</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Integration Testing

For testing integration with Dokan Kits, WooCommerce, and WordPress:

1. Set up a test environment with all dependencies installed
2. Use Codeception or the WordPress test framework
3. Create tests that verify your extension properly integrates with core functionality

### Frontend/JavaScript Testing

For React components and other JavaScript functionality:

```bash
# Jest configuration example
npm test -- --config=jest.config.js
```

## Deploying Extensions

### Building for Production

Before deploying your extension, run a production build:

```bash
# Optimize JS/CSS assets
npm run build

# Generate composer autoloader for production
composer dump-autoload -o
```

### Creating Release Packages

Package your extension for distribution:

```bash
# Example packaging script
sh ./bin/package.sh
```

A typical package should exclude development files like:
- node_modules/
- tests/
- .git/
- package.json and package-lock.json
- webpack.config.js
- phpunit.xml
- composer.json and composer.lock (unless needed)

### WordPress.org Deployment

If distributing via WordPress.org, follow their guidelines for plugin submission:

1. Include properly formatted readme.txt
2. Ensure assets are optimized
3. Follow WordPress coding standards
4. Include translation-ready strings

## Best Practices and Common Pitfalls

### Performance Optimization

- Use asset minification and bundling
- Employ selective script loading based on context
- Cache expensive database queries
- Follow WordPress performance best practices

### Security Considerations

- Always sanitize and validate user input
- Use WordPress permission checks
- Implement nonces for form submissions
- Use prepared SQL statements

### Compatibility

- Test with the latest Dokan Kits, WordPress, WooCommerce, and PHP versions
- Implement graceful degradation when certain features aren't available
- Follow semantic versioning for your extension

### Common Pitfalls

- Not checking for Dokan Kits dependency before initializing
- Using global functions or variables instead of the container
- Defining models without proper sanitization
- Not following the Dokan Kits hook naming conventions
- Loading JS/CSS on all admin pages instead of selectively

### Extension Maintenance

- Keep documentation updated
- Respond to user feedback and bug reports
- Regularly test with the latest Dokan Kits updates
- Release updates in a timely manner for compatibility

## Contributing Back to Dokan Kits

If your extension introduces functionality that might be valuable to the core plugin:

1. Consider submitting features back to the core project
2. Follow the [Contributing Guide](contributing.md) for code contributions
3. Start with an issue describing your proposed change before submitting a pull request

## Need Help?

If you need assistance with extension development:

- Consult the [Developer Forums](https://wpintegrity.com//forums)
- Join the [Slack Community](https://wpintegrity.com//slack)
- Search or submit issues on [GitHub](https://github.com/wpintegrity/wp-plugin-starter/issues) 
