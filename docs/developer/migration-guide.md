# Migration Guide to WP Plugin Starter 3.0.0

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

## Introduction

WP Plugin Starter 3.0.0 represents a complete architectural overhaul from previous versions, focusing on modern development practices, improved performance, and better extensibility. This guide will help developers migrate their code, extensions, and customizations from previous versions to the new architecture.

## Major Changes Overview

- **Architecture**: Complete rewrite using object-oriented programming principles
- **Dependency Injection**: New DI container for service management
- **React-based Admin**: Modern UI built with React and Tailwind CSS
- **REST API**: All core functionality exposed through REST API endpoints
- **Data Layer**: New data models and stores system based on WooCommerce architecture
- **Namespaces**: All classes now use proper PHP namespaces
- **Type Hinting**: Extensive use of PHP 7.4+ type hinting features
- **Hook System**: Standardized hook naming and organization

## Step-by-Step Migration Process

### 1. Update PHP Code References

#### Old Way (pre-3.0.0):

```php
// Directly accessing global functions and classes
$settings = dokan_kits_get_settings();
$vendor = new Dokan_Kits_Vendor($vendor_id);
```

#### New Way (3.0.0+):

```php
// Using namespaced classes and dependency injection
use Dokan_Kits\Core\Data\Models\Settings;
use Dokan_Kits\Features\Vendor\Models\Vendor;

// Using the container to get services
$settings_store = dokan_kits_get_container()->get('settings-service');
$vendor_service = dokan_kits_get_container()->get('vendor-service');

// Or directly instantiating models
$setting = new Settings(['name' => 'setting_name'], true);
$vendor = new Vendor(['id' => $vendor_id], true);
```

### 2. Hook Name Changes

Most hooks have been renamed to follow a consistent naming convention. Here's a reference for the most common hooks:

| Old Hook (pre-3.0.0) | New Hook (3.0.0+) | Purpose |
|------------------|----------------|---------|
| `dokan_kits_before_settings_save` | `dokan_kits_before_settings_object_save` | Before saving settings |
| `dokan_kits_after_settings_save` | `dokan_kits_after_settings_object_save` | After saving settings |
| `dokan_kits_before_vendor_save` | `dokan_kits_before_vendor_object_save` | Before saving vendor data |
| `dokan_kits_after_vendor_save` | `dokan_kits_after_vendor_object_save` | After saving vendor data |
| `dokan_kits_init` | `dokan_kits_loaded` | Plugin initialization |

For a complete list of hooks, please refer to the [Hooks Reference](hooks-reference.md).

### 3. Data Storage Migration

If you've stored custom data in the previous version, you'll need to migrate it to the new data structure:

```php
// Migration example
function migrate_custom_data_to_3_0_0() {
    global $wpdb;
    
    // Get old data
    $old_data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dokan_kits_custom_data");
    
    // Migrate to new structure
    foreach ($old_data as $data) {
        $model = new CustomModel([
            'name' => $data->name,
            'value' => $data->value,
            // Add other properties as needed
        ]);
        $model->save();
    }
}
```

### 4. Template Changes

If you've customized templates, you'll need to update them for the new structure:

1. First, identify the new template location:
   - Old: `templates/old-path/template.php`
   - New: `templates/new-path/template.php`

2. Update your template overrides in your theme:
   ```
   your-theme/wp-plugin-starter/new-path/template.php
   ```

3. Update the template content to match the new data structure and variable names.

### 5. JavaScript/REST API Integration

If you were using the old AJAX endpoints, switch to the new REST API:

#### Old Way (pre-3.0.0):

```javascript
jQuery.ajax({
    url: dokan_kits.ajax_url,
    type: 'POST',
    data: {
        action: 'dokan_kits_get_vendors',
        nonce: dokan_kits.nonce
    },
    success: function(response) {
        // Handle response
    }
});
```

#### New Way (3.0.0+):

```javascript
import apiFetch from '@wordpress/api-fetch';

apiFetch({
    path: '/wp-plugin-starter/v1/vendors',
    method: 'GET',
}).then(response => {
    if (response.success) {
        // Handle response.data
    }
}).catch(error => {
    // Handle error
});
```

### 6. Settings Migration

Settings are now managed through the Settings model:

```php
// Migrate settings
function migrate_settings_to_3_0_0() {
    // Old way of getting settings
    $old_settings = get_option('dokan_kits_settings', []);
    
    // Migrate each setting to the new structure
    foreach ($old_settings as $key => $value) {
        $setting = new Dokan_Kits\Core\Data\Models\Settings([
            'name' => $key,
            'value' => $value,
            'default' => '', // Set appropriate default
        ]);
        $setting->save();
    }
}
```

## Breaking Changes

Be aware of these significant breaking changes:

1. **Minimum Requirements**: PHP 7.4+, WordPress 6.4.2+, WooCommerce 7.9+, Dokan 3.9.7+
2. **Class Names**: All classes have been renamed and namespaced
3. **Function Names**: Many utility functions have been removed or replaced
4. **Hook Names**: Most hook names have changed
5. **Data Structure**: The database schema has been updated
6. **Template Structure**: Template files have been reorganized
7. **JavaScript API**: The JS API has completely changed to use React and REST API

## Compatibility Layer

For backwards compatibility, we provide a temporary compatibility layer:

```php
// Include the compatibility file for legacy extensions
if (defined('DOKAN_KITS_COMPAT') && DOKAN_KITS_COMPAT) {
    require_once DOKAN_KITS_PLUGIN_PATH . 'includes/Compatibility/legacy-functions.php';
}
```

However, we strongly recommend updating your code to use the new architecture as the compatibility layer will be removed in a future version.

## Migration Checklist

Use this checklist to ensure you've covered all aspects of migration:

- [ ] Update all class references to use namespaces
- [ ] Update all function calls to the new API
- [ ] Update all hook names in add_action and add_filter calls
- [ ] Migrate custom data to the new data models
- [ ] Update template overrides
- [ ] Replace AJAX calls with REST API calls
- [ ] Test thoroughly in a staging environment

## Common Migration Issues

### Issue: Class not found errors

**Solution**: Add the correct namespace or use the DI container to get the service.

### Issue: Hook not firing

**Solution**: Check the [Hooks Reference](hooks-reference.md) for the new hook name.

### Issue: Settings not saving

**Solution**: Use the Settings model instead of directly modifying the options table.

### Issue: JavaScript features not working

**Solution**: Update to use the new REST API and React components.

## Getting Help

If you encounter any issues during migration, please:

1. Check the [Troubleshooting](../user/troubleshooting.md) guide
2. Review the [Developer Guide](developer-guide.md) and [API Reference](api-reference.md)
3. Contact our developer support through the official channels

## Conclusion

While migrating to WP Plugin Starter 3.0.0 requires significant changes, the benefits of the new architecture—better performance, improved extensibility, and modern development patterns—make it worthwhile. This guide should help smooth the transition to the new version. 
