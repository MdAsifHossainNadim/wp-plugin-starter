# Hooks Reference

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document provides a comprehensive list of actions and filters available in the WP Plugin Starter plugin. These hooks allow developers to customize and extend the plugin's functionality without modifying core files.

## Table of Contents

- [Actions](#actions)
    - [Core Actions](#core-actions)
    - [Admin Actions](#admin-actions)
    - [Frontend Actions](#frontend-actions)
    - [Feature Actions](#feature-actions)
    - [Data Actions](#data-actions)
    - [API Actions](#api-actions)
- [Filters](#filters)
    - [Core Filters](#core-filters)
    - [Admin Filters](#admin-filters)
    - [Frontend Filters](#frontend-filters)
    - [Feature Filters](#feature-filters)
    - [Data Filters](#data-filters)
    - [REST API Filters](#rest-api-filters)

## Actions

### Core Actions

#### `wp_plugin_starter_loaded`

Fires when the WP Plugin Starter plugin is fully loaded.

```php
add_action('wp_plugin_starter_loaded', function() {
    // Plugin is fully loaded, initialize custom functionality
});
```

#### `wp_plugin_starter_before_init_hooks`

Fires during the initialization phase of WP Plugin Starter, before all services are registered.

```php
add_action('wp_plugin_starter_before_init_hooks', function() {
    // Early initialization code
});
```

#### `wp_plugin_starter_before_load_hookable_services`

Fires when services are being registered with the dependency injection container.

```php
add_action('wp_plugin_starter_before_load_hookable_services', function($container) {
    // Register custom services with the container
    $container->add('my_service', MyServiceClass::class);
});
```

#### `wp_plugin_starter_before_activate`

Fires when the plugin is activated.

```php
add_action('wp_plugin_starter_before_activate', function() {
    // Run activation code
    create_custom_tables();
});
```

#### `wp_plugin_starter_before_deactivate`

Fires when the plugin is deactivated.

```php
add_action('wp_plugin_starter_before_deactivate', function() {
    // Run deactivation code
    cleanup_temporary_data();
});
```

### Admin Actions

#### `wp_plugin_starter_before_admin_menu_hooks`

Fires before the WP Plugin Starter admin menu is registered.

```php
add_action('wp_plugin_starter_before_admin_menu_hooks', function() {
    // Add custom pre-menu initialization
});
```

#### `wp_plugin_starter_admin_menu`

Fires after the WP Plugin Starter admin menu is registered.

```php
add_action('wp_plugin_starter_admin_menu', function($capability, $menu_position) {
    // Add custom post-menu initialization
    add_submenu_page(
        'wp-plugin-starter',
        'Custom Page',
        'Custom Page',
        'manage_options',
        'wp-plugin-starter-custom',
        'my_custom_page_callback'
    );
}, 10, 2);
```

#### `wp_plugin_starter_enqueue_admin_scripts`

Fires when admin scripts and styles are being enqueued.

```php
add_action('wp_plugin_starter_enqueue_admin_scripts', function() {
    // Enqueue custom scripts and styles for the admin area
    wp_enqueue_script(
        'my-custom-script',
        plugin_dir_url(__FILE__) . 'assets/js/admin.js',
        ['jquery'],
        '1.0.0',
        true
    );
});
```

#### `wp_plugin_starter_before_dashboard_template`

Fires before the WP Plugin Starter admin dashboard content is displayed.

```php
add_action('wp_plugin_starter_before_dashboard_template', function() {
    // Output content before the dashboard
    echo '<div class="custom-dashboard-header">Welcome to WP Plugin Starter</div>';
});
```

#### `wp_plugin_starter_after_dashboard_template`

Fires after the WP Plugin Starter admin dashboard content is displayed.

```php
add_action('wp_plugin_starter_after_dashboard_template', function() {
    // Output content after the dashboard
    echo '<div class="custom-dashboard-footer">Custom footer content</div>';
});
```

### Frontend Actions

#### `wp_plugin_starter_frontend_assets_registered`

Fires when frontend assets are registered.

```php
add_action('wp_plugin_starter_frontend_assets_registered', function($assets_instance) {
    // Run code after frontend assets are registered
    wp_enqueue_script('my-frontend-script', 'path/to/script.js');
});
```

### Feature Actions

#### `wp_plugin_starter_before_bootstrap`

Fires before the plugin bootstrap process begins.

```php
add_action('wp_plugin_starter_before_bootstrap', function($bootstrap) {
    // Run code before bootstrap
    initialize_custom_features();
});
```

#### `wp_plugin_starter_bootstrap`

Fires after the plugin bootstrap process completes.

```php
add_action('wp_plugin_starter_bootstrap', function($bootstrap) {
    // Run code after bootstrap
    finalize_custom_setup();
});
```

### Data Actions

#### `wp_plugin_starter_data_stores_initialized`

Fires when data stores are initialized.

```php
add_action('wp_plugin_starter_data_stores_initialized', function($data_stores, $plugin) {
    // Run code after data stores are initialized
    setup_custom_data_store();
}, 10, 2);
```

### API Actions

#### `wp_plugin_starter_before_rest_routes_registration`

Fires before REST API routes are registered.

```php
add_action('wp_plugin_starter_before_rest_routes_registration', function($controllers, $bootstrap) {
    // Add custom REST controllers
    register_custom_rest_routes();
}, 10, 2);
```

#### `wp_plugin_starter_after_rest_routes_registration`

Fires after REST API routes are registered.

```php
add_action('wp_plugin_starter_after_rest_routes_registration', function($controllers, $bootstrap) {
    // Run code after REST routes are registered
    setup_api_authentication();
}, 10, 2);
```

## Filters

### Core Filters

#### `wp_plugin_starter_get_container`

Filters the dependency injection container before it's fully configured.

```php
add_filter('wp_plugin_starter_get_container', function($container, $plugin) {
    // Modify container configuration
    $container->add('custom_service', CustomService::class);
    return $container;
}, 10, 2);
```

#### `wp_plugin_starter_hookable_services`

Filters the hookable services before they're registered.

```php
add_filter('wp_plugin_starter_hookable_services', function($hooks, $plugin) {
    // Add custom hookable service
    $hooks[] = new CustomHookableService();
    return $hooks;
}, 10, 2);
```

#### `wp_plugin_starter_data_stores`

Filters the data stores to be loaded.

```php
add_filter('wp_plugin_starter_data_stores', function($stores, $plugin) {
    // Add custom data store
    $stores['custom_data'] = CustomDataStore::class;
    return $stores;
}, 10, 2);
```

### Admin Filters

#### `wp_plugin_starter_menu_capability`

Filters the capability required to access the WP Plugin Starter admin menu.

```php
add_filter('wp_plugin_starter_menu_capability', function($capability) {
    // Change the required capability
    return 'custom_capability';
});
```

#### `wp_plugin_starter_menu_position`

Filters the position of the admin menu.

```php
add_filter('wp_plugin_starter_menu_position', function($position) {
    // Change menu position
    return 25;
});
```

#### `wp_plugin_starter_admin_menu_icon`

Filters the admin menu icon.

```php
add_filter('wp_plugin_starter_admin_menu_icon', function($icon) {
    // Change the menu icon
    return 'dashicons-admin-tools';
});
```

#### `wp_plugin_starter_admin_script_deps`

Filters the dependencies for admin scripts.

```php
add_filter('wp_plugin_starter_admin_script_deps', function($deps) {
    // Add custom dependencies
    $deps[] = 'my-custom-lib';
    return $deps;
});
```

#### `wp_plugin_starter_admin_script_data`

Filters the data passed to admin scripts.

```php
add_filter('wp_plugin_starter_admin_script_data', function($data) {
    // Add custom data
    $data['customOption'] = get_option('my_custom_option');
    return $data;
});
```

### Frontend Filters

#### `wp_plugin_starter_load_frontend_assets`

Filters whether to load frontend assets.

```php
add_filter('wp_plugin_starter_load_frontend_assets', function($should_load, $assets) {
    // Custom logic for loading assets
    if (is_page('special-page')) {
        return true;
    }
    return $should_load;
}, 10, 2);
```

### Feature Filters

#### `wp_plugin_starter_is_pro_active`

Filters whether the Pro version is active.

```php
add_filter('wp_plugin_starter_is_pro_active', function($is_active) {
    // Custom logic for Pro detection
    return defined('WP_PLUGIN_STARTER_PRO_VERSION');
});
```

### Data Filters

#### `wp_plugin_starter_has_update`

Filters whether the plugin has updates available.

```php
add_filter('wp_plugin_starter_has_update', function($has_update) {
    // Custom update check logic
    return check_custom_update_server();
});
```

### REST API Filters

#### `wp_plugin_starter_rest_response`

Filters the response data for REST API endpoints.

```php
add_filter('wp_plugin_starter_rest_response', function($response, $request) {
    // Modify API response data
    if (isset($response['data']) && current_user_can('manage_options')) {
        $response['data']['admin_info'] = get_admin_info();
    }
    return $response;
}, 10, 2);
```

## Complete Hook List

### Actions

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `wp_plugin_starter_loaded` | Plugin is fully loaded | `$plugin` |
| `wp_plugin_starter_before_init_hooks` | Before plugin initialization | `$plugin` |
| `wp_plugin_starter_after_init_hooks` | After plugin initialization | `$plugin` |
| `wp_plugin_starter_before_load_hookable_services` | Before loading hookable services | `$plugin` |
| `wp_plugin_starter_after_load_hookable_services` | After loading hookable services | `$hooks, $plugin` |
| `wp_plugin_starter_before_activate` | Before plugin activation | `$plugin` |
| `wp_plugin_starter_after_activate` | After plugin activation | `$plugin` |
| `wp_plugin_starter_before_deactivate` | Before plugin deactivation | `$plugin` |
| `wp_plugin_starter_after_deactivate` | After plugin deactivation | `$plugin` |
| `wp_plugin_starter_before_admin_menu_hooks` | Before admin menu hooks | `$menu` |
| `wp_plugin_starter_admin_menu` | Admin menu registration | `$capability, $menu_position` |
| `wp_plugin_starter_enqueue_admin_scripts` | Admin scripts enqueuing | none |
| `wp_plugin_starter_before_dashboard_template` | Before admin dashboard | none |
| `wp_plugin_starter_after_dashboard_template` | After admin dashboard | none |
| `wp_plugin_starter_frontend_assets_registered` | Frontend assets registered | `$assets` |
| `wp_plugin_starter_before_bootstrap` | Before plugin bootstrap | `$bootstrap` |
| `wp_plugin_starter_bootstrap` | After plugin bootstrap | `$bootstrap` |
| `wp_plugin_starter_data_stores_initialized` | Data stores initialized | `$data_stores, $plugin` |
| `wp_plugin_starter_before_rest_routes_registration` | Before REST routes registration | `$controllers, $bootstrap` |
| `wp_plugin_starter_after_rest_routes_registration` | After REST routes registration | `$controllers, $bootstrap` |

### Filters

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `wp_plugin_starter_get_container` | DI container | `$container, $plugin` |
| `wp_plugin_starter_hookable_services` | Hookable services | `$hooks, $plugin` |
| `wp_plugin_starter_data_stores` | Data stores | `$stores, $plugin` |
| `wp_plugin_starter_menu_capability` | Admin menu capability | `$capability` |
| `wp_plugin_starter_menu_position` | Admin menu position | `$position` |
| `wp_plugin_starter_admin_menu_icon` | Admin menu icon | `$icon` |
| `wp_plugin_starter_admin_script_deps` | Admin script dependencies | `$deps` |
| `wp_plugin_starter_admin_script_data` | Admin script data | `$data` |
| `wp_plugin_starter_load_frontend_assets` | Load frontend assets | `$should_load, $assets` |
| `wp_plugin_starter_is_pro_active` | Pro version active | `$is_active` |
| `wp_plugin_starter_has_update` | Has plugin update | `$has_update` |
| `wp_plugin_starter_rest_response` | REST API response | `$response, $request` |

## Usage Examples

### Creating Custom Features

```php
// Register a custom feature
add_action('wp_plugin_starter_before_bootstrap', function($bootstrap) {
    $container = wp_plugin_starter_get_container();
    $container->add('my_custom_feature', MyCustomFeature::class);
});

// Add custom hookable service
add_filter('wp_plugin_starter_hookable_services', function($hooks, $plugin) {
    $hooks[] = wp_plugin_starter_get_container()->get('my_custom_feature');
    return $hooks;
}, 10, 2);
```

### Extending Admin Interface

```php
// Add custom admin page
add_action('wp_plugin_starter_admin_menu', function($capability, $menu_position) {
    add_submenu_page(
        'wp-plugin-starter',
        'Custom Tools',
        'Tools',
        $capability,
        'wp-plugin-starter-tools',
        'render_custom_tools_page'
    );
}, 10, 2);

// Add custom admin script data
add_filter('wp_plugin_starter_admin_script_data', function($data) {
    $data['customApiEndpoint'] = rest_url('wp-plugin-starter/v1/custom/');
    return $data;
});
```

### Custom Data Integration

```php
// Add custom data store
add_filter('wp_plugin_starter_data_stores', function($stores, $plugin) {
    $stores['custom_analytics'] = CustomAnalyticsDataStore::class;
    return $stores;
}, 10, 2);

// Hook into data store initialization
add_action('wp_plugin_starter_data_stores_initialized', function($data_stores, $plugin) {
    // Setup custom analytics tracking
    if (isset($data_stores['custom_analytics'])) {
        setup_analytics_hooks();
    }
}, 10, 2);
```
