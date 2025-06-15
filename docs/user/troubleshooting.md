# Troubleshooting WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

This guide covers common issues you might encounter with WP Plugin Starter and provides step-by-step solutions to resolve them.

## 📋 Table of Contents

- [Installation & Activation Issues](#-installation--activation-issues)
- [Settings & Configuration Problems](#-settings--configuration-problems)
- [Product Management Issues](#-product-management-issues)
- [Vendor Management Issues](#-vendor-management-issues)
- [Cart & Checkout Problems](#-cart--checkout-problems)
- [Shipping Complications](#-shipping-complications)
- [Performance Concerns](#-performance-concerns)
- [Compatibility Issues](#-compatibility-issues)
- [Update & Migration Challenges](#-update--migration-challenges)
- [Advanced Troubleshooting](#-advanced-troubleshooting)

## 🔧 Installation & Activation Issues

### Plugin Won't Install

**Issue**: Error when uploading or installing the plugin

**Solutions**:

1. **Check ZIP File**: Verify the file isn't corrupted by downloading again
2. **File Size Limits**: Your server might have upload limits. Try FTP installation:
    - Extract the ZIP file on your computer
    - Upload the `wp-plugin-starter` folder to `/wp-content/plugins/` via FTP
    - Activate from the Plugins page
3. **Permissions**: Ensure your WordPress directory has correct permissions:
    - Folders: 755
    - Files: 644
4. **PHP Version**: Verify your server meets the minimum PHP 7.4 requirement

### Activation Fails

**Issue**: Error when activating the plugin

**Solutions**:

1. **Check Dependencies**: Ensure Dokan Lite (3.9.7+) and WooCommerce (6.0+) are installed and activated
2. **Memory Limit**: Increase PHP memory limit in wp-config.php:
    ```php
    define('WP_MEMORY_LIMIT', '256M');
    ```
3. **PHP Extensions**: Verify required PHP extensions are enabled:
    ```php
    // Check in PHP info or run this code
    $required = ['curl', 'json', 'mbstring', 'xml', 'zip'];
    foreach ($required as $ext) {
      echo $ext . ': ' . (extension_loaded($ext) ? 'Yes' : 'No') . "\n";
    }
    ```
4. **Plugin Conflicts**: Deactivate all other plugins, then activate WP Plugin Starter to identify conflicts

### Plugin Not Appearing in Menu

**Issue**: After activation, the WP Plugin Starter menu doesn't appear

**Solutions**:

1. **Refresh the Page**: Sometimes a simple refresh resolves the issue
2. **Clear Browser Cache**: Clear your browser cache or try a private/incognito window
3. **User Permissions**: Verify your user has administrator privileges
4. **Menu Display**: Check if the menu is possibly hidden or collapsed
5. **Dokan Core**: Confirm Dokan Lite is properly activated and functioning
6. **JavaScript Errors**: Check browser console for JavaScript errors affecting menu display

## ⚙️ Settings & Configuration Problems

### Settings Not Saving

**Issue**: Changes to settings don't save or persist

**Solutions**:

1. **Permissions Issue**: Ensure WordPress has write permissions to the database
2. **Nonce Timeout**: The form might have timed out. Refresh and try again
3. **Database Issues**: Check database connection or corruption:
    - Run a database repair using a plugin like WP-DBManager
    - Check error logs for database-related issues
4. **Plugin Conflict**: Disable other plugins to identify conflicts
5. **Browser Cache**: Try in private/incognito mode to bypass cache

### Plugin Conflict Detection

To systematically check for plugin conflicts:

1. Deactivate all plugins except Dokan and WP Plugin Starter
2. Test if the issue persists
3. If resolved, reactivate plugins one by one until the issue reappears
4. The last activated plugin is likely causing the conflict

### Missing Settings Tabs

**Issue**: Some settings tabs are missing from the interface

**Solutions**:

1. **JavaScript Issues**: Check console for JavaScript errors
2. **Feature Requirements**: Some tabs may require Dokan Pro
3. **Screen Options**: Check screen options to ensure tabs aren't hidden
4. **User Role**: Verify you have administrator role
5. **Plugin Files**: Ensure all plugin files were properly installed

### Settings Reset

**Issue**: Need to reset settings to default

**Solutions**:

1. **Reset Button**: Use the Reset button on the Advanced tab
2. **Manual Reset**: Delete the option from the database:
    ```sql
    DELETE FROM wp_options WHERE option_name = 'dokan_kits_settings';
    ```
3. **Selective Reset**: Reset only specific sections by editing the option value:
    ```php
    // Example code for selective reset
    $options = get_option('dokan_kits_settings', []);
    unset($options['vendor']);  // Reset only vendor features
    update_option('dokan_kits_settings', $options);
    ```

## 📦 Product Management Issues

### Image Restrictions Not Working

**Issue**: Product image restrictions aren't being enforced

**Solutions**:

1. **Configuration Check**: Verify restrictions are enabled and properly configured:
    - Navigate to **WP Plugin Starter > Settings > Product > Image Restrictions**
    - Ensure the feature is enabled and limits are set
2. **AJAX Issues**: Check for JavaScript errors in the browser console
3. **Theme Compatibility**: Some themes override the standard upload process
    - Test with a default WordPress theme
4. **Vendor Dashboard**: Verify restrictions work in the Dokan vendor dashboard
5. **Server Configuration**: Check if your server supports the GD or Imagick libraries
    - These are required for image manipulation

### Product Fields Not Hiding

**Issue**: Disabled product fields still appear in the vendor dashboard

**Solutions**:

1. **Caching Issues**: Clear cache and refresh the page
2. **Theme Override**: Your theme might be overriding Dokan templates
    - Check if the theme includes customized Dokan templates
3. **Plugin Conflict**: Another plugin might be restoring the fields
4. **Dokan Version**: Ensure compatibility between WP Plugin Starter and your Dokan version
5. **Template Override**: If using custom templates, they might need updating

### Product Types Control Issues

**Issue**: Disabled product types still available to vendors

**Solutions**:

1. **Settings Verification**: Double-check settings in Product Types section
2. **User Role**: Ensure vendors don't have administrator capabilities
3. **Cache**: Clear cache plugins and browser cache
4. **Dokan Pro Conflict**: If using Dokan Pro, check for conflicting settings
5. **Custom Code**: Check for custom code that might be overriding restrictions

### Product Types Troubleshooting Checklist

1. Navigate to **WP Plugin Starter > Settings > Product > Product Types**
2. Verify which product types are disabled
3. Log in as a vendor and try to create a new product
4. Check if disabled product types appear in the dropdown
5. Check browser console for JavaScript errors
6. Temporarily disable theme and other plugins to test

## 👥 Vendor Management Issues

### Vendor Registration Problems

**Issue**: Users can't register as vendors or registration form has errors

**Solutions**:

1. **Settings Check**: Verify registration settings:
    - Navigate to **WP Plugin Starter > Settings > Vendor > Registration**
    - Ensure "Remove Vendor Checkbox" is not enabled
2. **Form Fields**: Check if custom fields are properly configured
3. **User Roles**: Ensure the vendor role exists in your WordPress installation
4. **Email Verification**: Check if email verification is working properly
5. **Dokan Setup**: Verify Dokan vendor registration is properly configured

### Vendor Capabilities Not Applied

**Issue**: Vendors don't have expected capabilities or have too many permissions

**Solutions**:

1. **Capability Settings**: Review capabilities configuration:
    - Navigate to **Dokan Kits > Settings > Vendor > Capabilities**
    - Verify appropriate capabilities are enabled/disabled
2. **User Role**: Check if vendors have the correct user role
3. **Role Reset**: Try resetting the vendor's role:
    - Go to Users, edit the vendor, and reassign the vendor role
4. **Plugin Conflict**: Check for plugins that modify user capabilities
5. **Caching**: Clear role and capability caches

### Vendor Dashboard Access Issues

**Issue**: Vendors can't access parts of their dashboard

**Solutions**:

1. **Capability Check**: Verify the vendor has the required capabilities
2. **Feature Activation**: Ensure the feature is enabled in Dokan settings
3. **Page Template**: Check if dashboard page templates are properly loaded
4. **URL Structure**: Verify dashboard URLs are correctly formatted
5. **User Role**: Confirm the user has the vendor role assigned

### Store Settings Problems

**Issue**: Vendor store settings not saving or displaying correctly

**Solutions**:

1. **Form Validation**: Check for validation errors in the form
2. **Theme Compatibility**: Test with a default WordPress theme
3. **Store URL**: Verify store URL structure is properly configured
4. **Permissions**: Check if the vendor has permission to edit store settings
5. **Database**: Verify store settings are being saved to the database

## 🛒 Cart & Checkout Problems

### Cart Buttons Not Displaying

**Issue**: Custom cart buttons aren't showing or are styled incorrectly

**Solutions**:

1. **Settings Check**: Verify button settings:
    - Navigate to **Dokan Kits > Settings > Cart > Cart Buttons**
    - Ensure the feature is enabled and configured
2. **Theme Compatibility**: Test with a default WordPress theme
3. **CSS Conflicts**: Check for CSS that might override button styles
4. **Hook Priority**: The theme might use different hook priorities
5. **Template Override**: Check if the theme overrides WooCommerce button templates

### Buy Now Not Working

**Issue**: "Buy Now" functionality doesn't redirect to checkout

**Solutions**:

1. **Feature Verification**: Ensure Buy Now is enabled:
    - Navigate to **Dokan Kits > Settings > Cart > Buy Now**
2. **JavaScript Errors**: Check browser console for errors
3. **AJAX Issues**: Verify AJAX calls are completing successfully
4. **Plugin Conflict**: Disable other checkout-enhancing plugins
5. **Product Support**: Verify the product type supports Buy Now functionality

### Checkout Fields Problems

**Issue**: Custom checkout fields not appearing or saving data

**Solutions**:

1. **Field Configuration**: Verify fields are properly configured:
    - Navigate to **Dokan Kits > Settings > Cart > Checkout Fields**
2. **Conditional Logic**: If using conditional fields, check conditions
3. **Validation Errors**: Look for validation error messages
4. **Field Positioning**: Check if fields are in the correct section
5. **Form Submission**: Verify form data is being submitted correctly

### Multi-vendor Cart Issues

**Issue**: Problems with multi-vendor orders or order splitting

**Solutions**:

1. **Configuration Check**: Verify multi-vendor settings:
    - Navigate to **Dokan Kits > Settings > Cart > Multi-vendor**
2. **Order Splitting**: Check if order splitting is configured correctly
3. **Vendor Commission**: Verify commission calculations for split orders
4. **Email Notifications**: Check if separate emails are being sent
5. **Order Status**: Verify order status is correctly synchronized

## 🚚 Shipping Complications

### Shipping Methods Not Appearing

**Issue**: Configured shipping methods don't appear at checkout

**Solutions**:

1. **Zone Configuration**: Verify shipping zones include the customer's location:
    - Navigate to **Dokan Kits > Settings > Shipping > Zones**
    - Check that zones include the appropriate regions
2. **Method Setup**: Ensure shipping methods are added to zones
3. **Product Settings**: Check product shipping settings (virtual, weight, etc.)
4. **Class Assignment**: Verify shipping classes are correctly assigned
5. **Calculator Issues**: Test the shipping calculator in the cart

### Incorrect Shipping Calculations

**Issue**: Shipping costs calculated incorrectly

**Solutions**:

1. **Rate Settings**: Review shipping rate configurations
2. **Multi-vendor Settings**: Check how multi-vendor shipping is calculated
3. **Tax Inclusion**: Verify whether rates include tax
4. **Weight & Dimensions**: Ensure products have correct weight/dimensions
5. **Calculation Method**: Check the calculation method settings

### Vendor Shipping Control Issues

**Issue**: Vendors unable to set or modify shipping options

**Solutions**:

1. **Permission Check**: Verify vendor shipping control settings:
    - Navigate to **Dokan Kits > Settings > Shipping > Lite Shipping**
    - Check "Vendor Control" setting
2. **Capability**: Ensure vendors have shipping management capabilities
3. **Interface Access**: Check if the shipping interface is visible in vendor dashboard
4. **Restrictions**: Verify any vendor shipping restrictions
5. **Zone Management**: Check vendor zone management permissions

### Shipping Class Problems

**Issue**: Shipping classes not working as expected

**Solutions**:

1. **Class Assignment**: Verify products have the correct shipping class assigned
2. **Class Configuration**: Check shipping class settings:
    - Navigate to **Dokan Kits > Settings > Shipping > Classes**
    - Verify classes are properly defined
3. **Method Settings**: Ensure shipping methods have class costs configured
4. **Vendor Permissions**: Check if vendors can assign shipping classes
5. **Class Inheritance**: For variable products, check if variations inherit classes

## ⚡ Performance Concerns

### Slow Admin Dashboard

**Issue**: Dokan Kits admin pages load slowly

**Solutions**:

1. **Asset Optimization**: Optimize JavaScript and CSS:
    - Navigate to **Dokan Kits > Settings > Advanced > Performance**
    - Enable asset optimization options
2. **Database Cleanup**: Optimize the WordPress database:
    - Use a plugin like WP-Optimize to clean up the database
    - Remove unnecessary options and transients
3. **Server Resources**: Check server resources and limits:
    - Memory limit (increase if possible)
    - PHP execution time
    - Server CPU and disk I/O
4. **Plugin Conflicts**: Disable other plugins to identify performance impacts
5. **Caching**: Implement proper WordPress caching:
    - Object caching
    - Page caching (except for dynamic pages)
    - Browser caching

### Frontend Performance Issues

**Issue**: Slow loading on storefront pages

**Solutions**:

1. **Asset Loading**: Check what assets are loading on frontend:
    - Use browser developer tools to analyze loading
    - Look for render-blocking resources
2. **Image Optimization**: Ensure product images are optimized:
    - Enable image optimization features
    - Use WebP or optimized formats
3. **Query Optimization**: Check for excessive database queries:
    - Use Query Monitor plugin to identify slow queries
    - Look for duplicate or unnecessary queries
4. **CDN Usage**: Consider using a Content Delivery Network
5. **Feature Reduction**: Disable features you don't need:
    - Navigate to each feature section and disable unused features
    - Focus on essential functionality for your marketplace

### Database Optimization

To optimize the database for better performance:

1. **Regular Cleanup**:

    ```sql
    -- Clean up post revisions
    DELETE FROM wp_posts WHERE post_type = 'revision';

    -- Clean up transients
    DELETE FROM wp_options WHERE option_name LIKE '%_transient_%';

    -- Clean up orphaned postmeta
    DELETE pm FROM wp_postmeta pm LEFT JOIN wp_posts p ON p.ID = pm.post_id WHERE p.ID IS NULL;
    ```

2. **Index Important Tables**:

    ```sql
    -- Example: Add index to meta_key column
    ALTER TABLE wp_postmeta ADD INDEX meta_key_index (meta_key);
    ```

3. **Optimize Tables**:
    ```sql
    OPTIMIZE TABLE wp_posts, wp_postmeta, wp_options, wp_dokan_orders;
    ```

### Memory Usage Optimization

If experiencing memory issues:

1. **Increase Limits**: Add to wp-config.php:

    ```php
    define('WP_MEMORY_LIMIT', '256M');
    define('WP_MAX_MEMORY_LIMIT', '512M');
    ```

2. **Batch Processing**: For large operations, implement batch processing
3. **Reduce Object Size**: Minimize data stored in memory
4. **Debug Memory Usage**:
    ```php
    // Add this code temporarily to problematic pages to debug
    echo 'Current Memory Usage: ' . size_format(memory_get_usage(true));
    ```

## 🔄 Compatibility Issues

### Theme Compatibility Problems

**Issue**: Conflicts between Dokan Kits and your WordPress theme

**Solutions**:

1. **Template Override**: Check if theme overrides Dokan templates:
    - Look in theme directory for /dokan/ or /woocommerce/ folders
    - Update overridden templates to match current Dokan version
2. **CSS Conflicts**: Identify CSS conflicts:
    - Use browser inspector to find competing styles
    - Add more specific CSS rules to resolve conflicts
3. **Hook Conflicts**: Check for hook conflicts:
    - Some themes modify WooCommerce hooks used by Dokan
    - Use priority settings to ensure correct execution order
4. **JavaScript Issues**: Look for JavaScript conflicts:
    - Check browser console for errors
    - Ensure theme doesn't override jQuery or other libraries
5. **Test Default Theme**: Test with a default WordPress theme to isolate issues

### Plugin Conflicts

**Issue**: Conflicts with other WordPress plugins

**Solutions**:

1. **Systematic Testing**: Deactivate plugins one by one to identify conflicts
2. **Common Conflicts**: Check for known conflicts with:
    - Other marketplace plugins
    - Checkout enhancement plugins
    - Page builders
    - Security plugins (blocking AJAX)
    - Caching plugins (not excluding dynamic pages)
3. **Hook Priority**: Adjust hook priorities:
    ```php
    // Example of changing hook priority (add to theme functions.php)
    function adjust_dokan_kits_hook_priority() {
        remove_action('woocommerce_before_add_to_cart_button', 'dokan_kits_add_to_cart_button', 10);
        add_action('woocommerce_before_add_to_cart_button', 'dokan_kits_add_to_cart_button', 20);
    }
    add_action('init', 'adjust_dokan_kits_hook_priority');
    ```
4. **Shared Resources**: Check for plugins using same resources:
    - JavaScript libraries
    - CSS frameworks
    - Third-party APIs

### WooCommerce Compatibility

**Issue**: Incompatibility with WooCommerce version

**Solutions**:

1. **Version Check**: Verify WooCommerce version compatibility:
    - Dokan Kits requires WooCommerce 6.0+
    - Check release notes for specific version compatibility
2. **Hook Changes**: Recent WooCommerce versions may have changed hooks:
    - Review WooCommerce changelogs for hook changes
    - Update custom code to use current hooks
3. **Template Changes**: Check for template changes:
    - WooCommerce template changes might affect integration
    - Update any template overrides
4. **Deprecated Functions**: Look for deprecated function usage:
    - Replace deprecated WooCommerce functions
    - Use WooCommerce-recommended alternatives

### Dokan Compatibility

**Issue**: Incompatibility with Dokan version

**Solutions**:

1. **Version Verification**: Ensure Dokan meets minimum requirements:
    - Dokan Lite 3.9.7+ required
    - Check for specific version compatibility
2. **Dokan Pro Conflicts**: If using Dokan Pro, check for conflicts:
    - Features that overlap with Dokan Pro
    - Settings that conflict with Pro settings
3. **API Changes**: Check for Dokan API changes:
    - Dokan internal APIs might change between versions
    - Update custom integrations accordingly
4. **Template Updates**: Update any Dokan template overrides:
    - Compare with current Dokan templates
    - Update to match structure and variables

## 🔄 Update & Migration Challenges

### Update Failures

**Issue**: Problems when updating Dokan Kits to a newer version

**Solutions**:

1. **Backup First**: Always back up before updating:
    - Database backup
    - Files backup (especially if customized)
2. **Update Process**: Follow proper update procedure:
    - Deactivate plugin
    - Upload new version
    - Activate plugin
3. **File Permissions**: Check for permission issues:
    - Ensure WordPress can write to the plugins directory
    - Temporary set permissions to 755 for folders, 644 for files
4. **Manual Update**: If automatic update fails, try manual update:
    - Download the latest version
    - Deactivate and delete the current version (keep settings)
    - Upload and activate the new version
5. **Version Jump**: If updating across multiple versions:
    - Consider incremental updates for major version changes
    - Check changelogs for migration requirements

### Settings Migration Issues

**Issue**: Settings not carried over after update

**Solutions**:

1. **Database Check**: Verify settings in the database:
    ```sql
    SELECT * FROM wp_options WHERE option_name = 'dokan_kits_settings';
    ```
2. **Manual Transfer**: If needed, manually transfer settings:

    ```php
    // Code to copy features from old format to new
    $old_settings = get_option('old_dokan_kits_setting_name');
    $new_settings = get_option('dokan_kits_settings', []);

    // Map old features to new format
    // ...

    update_option('dokan_kits_settings', $new_settings);
    ```

3. **Settings Reset**: If corrupted, consider resetting settings:
    - Navigate to **Dokan Kits > Settings > Advanced**
    - Use the Reset Settings option
    - Reconfigure essential settings
4. **Version-Specific Issues**: Check changelog for specific migration notes

### Data Migration for Major Updates

For major version updates requiring data migration:

1. **Follow Documentation**: Check release-specific migration documents
2. **Run Migrations**: Use provided migration tools:
    - Navigate to **Dokan Kits > Settings > Advanced > Migrations**
    - Run appropriate migration scripts
3. **Verify Data**: After migration, verify critical data:
    - Vendor capabilities
    - Product configurations
    - Shipping settings
4. **Rollback Plan**: Have a rollback plan ready:
    - Keep backup accessible
    - Document rollback procedure
    - Test restoration process beforehand

## 🔬 Advanced Troubleshooting

### Debugging Techniques

Enable WordPress debugging for detailed error information:

1. **Debug Mode**: Add to wp-config.php:

    ```php
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', false); // Don't show errors on front-end
    ```

2. **Check Error Logs**: Review logs at wp-content/debug.log

3. **Function Debugging**: Add temporary code to debug functions:

    ```php
    // Add this before a problematic function
    error_log('Function X called with params: ' . print_r($params, true));

    // Add this after function
    error_log('Function X completed with result: ' . print_r($result, true));
    ```

4. **AJAX Debugging**: Debug AJAX requests:

    ```javascript
    // Client-side
    console.log('Sending request:', data);

    // Server-side
    error_log('AJAX request received: '.print_r($_POST, true));
    ```

### Database Troubleshooting

For database-related issues:

1. **Direct Query**: Check database content directly:

    ```sql
    -- Example: Check features
    SELECT * FROM wp_options WHERE option_name LIKE '%dokan_kits%';

    -- Example: Check product meta
    SELECT * FROM wp_postmeta WHERE meta_key LIKE '%dokan_kits%' AND post_id = 123;
    ```

2. **Reset Options**: If settings are corrupted:

    ```sql
    DELETE FROM wp_options WHERE option_name = 'dokan_kits_settings';
    ```

3. **Table Integrity**: Check table structure:
    ```sql
    CHECK TABLE wp_posts, wp_postmeta, wp_options;
    ```

### Advanced Hook Debugging

To debug WordPress action and filter hooks:

```php
// Add to functions.php temporarily
function debug_hooks() {
    global $wp_actions, $wp_filter;
    error_log('Current action: ' . current_action());

    // To debug a specific hook
    $hook_name = 'woocommerce_before_add_to_cart_form';
    if (isset($wp_filter[$hook_name])) {
        error_log('Hook ' . $hook_name . ' callbacks:');
        foreach ($wp_filter[$hook_name] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                error_log("  Priority $priority: " . (is_array($callback['function']) ?
                  (is_object($callback['function'][0]) ?
                    get_class($callback['function'][0]) : $callback['function'][0])
                  . '->' . $callback['function'][1] : $callback['function']));
            }
        }
    }
}
add_action('all', 'debug_hooks');
```

### Template Debugging

To debug template loading issues:

```php
// Add to functions.php temporarily
function debug_template_include($template) {
    error_log('Loading template: ' . $template);
    return $template;
}
add_filter('template_include', 'debug_template_include', 999);
```

### Getting Support

If you're unable to resolve an issue with the troubleshooting steps above:

1. **Gather Information**:

    - WordPress version
    - Dokan version
    - Dokan Kits version
    - Theme name and version
    - Active plugins list
    - Error messages from logs
    - Screenshots of the issue
    - Steps to reproduce the problem

2. **Contact Support**:

    - Submit a support ticket with all gathered information
    - Be specific about the issue and troubleshooting steps already tried
    - Provide temporary admin access if possible

3. **Community Resources**:
    - Check the Dokan community forum for similar issues
    - WordPress.org plugin support forum
    - Stack Overflow with appropriate tags

## 📋 Preventative Measures

To avoid future issues:

1. **Regular Backups**: Maintain regular backups of your site
2. **Staging Environment**: Test updates on a staging site first
3. **Update Schedule**: Plan regular updates for all components
4. **Monitoring**: Implement monitoring for your marketplace
5. **Documentation**: Maintain documentation of your customizations

Remember that most issues have simple solutions. By systematically working through the troubleshooting steps, you can resolve most problems without advanced technical assistance.
