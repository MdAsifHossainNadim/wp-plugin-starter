# System Requirements

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document outlines the requirements for running WP Plugin Starter successfully on your WordPress site.

## Essential Requirements

### Core Software

| Requirement       | Minimum       | Recommended   |
|-------------------|---------------|---------------|
| WordPress         | 6.4.2         | 6.8+          |
| PHP               | 7.4           | 8.0+          |
| MySQL/MariaDB     | 5.7/10.2      | 8.0+/10.5+    |
| WooCommerce       | 7.9           | 9.8+          |
| (Optional) Dokan Lite/Pro    | 3.9.7         | 4.0+          |

### PHP Configuration

| Setting             | Minimum       | Recommended   |
|---------------------|---------------|---------------|
| Memory Limit        | 128M          | 256M+         |
| Max Execution Time  | 60 seconds    | 300 seconds   |
| Post Max Size       | 32M           | 64M+          |
| Upload Max Filesize | 32M           | 64M+          |
| Max Input Vars      | 1000          | 3000+         |

### Required PHP Extensions

The following PHP extensions must be enabled on your server:

| Extension | Purpose |
|-----------|---------|
| curl      | API connections and remote data fetching |
| json      | Data formatting and processing |
| mbstring  | Multi-byte string handling |
| mysqli    | Database connectivity |
| xml       | XML processing |
| zip       | File compression and extraction |
| gd/imagick | Image processing (one required) |
| openssl   | Secure data transmission |

## Server Environment

### Recommended Web Servers

| Web Server         | Minimum Version | Recommended Version |
|-------------------|-----------------|---------------------|
| Apache            | 2.4            | 2.4.41+             |
| Nginx             | 1.16           | 1.18+               |

### Apache Configuration

If you're using Apache, ensure these modules are enabled:

- mod_rewrite
- mod_expires
- mod_headers
- mod_deflate

### Nginx Configuration

For Nginx servers, ensure your configuration includes:

- Proper FastCGI settings
- Rewrite rules for pretty permalinks
- Cache-control headers
- PHP-FPM configuration

## Browser Support

WP Plugin Starter user interface is compatible with the following browsers:

| Browser           | Minimum Version |
|-------------------|-----------------|
| Chrome            | 80+             |
| Firefox           | 78+             |
| Safari            | 13+             |
| Edge (Chromium)   | 80+             |
| Opera             | 70+             |

Mobile browsers are supported on iOS 13+ and Android 7+.

## Additional Dependencies

### Required WordPress Settings

| Setting           | Requirement                                       |
|-------------------|---------------------------------------------------|
| Permalinks        | Any structure except Plain                        |
| AJAX              | Working AJAX functionality                        |
| REST API          | Accessible and functional REST API endpoints      |
| JavaScript        | Enabled and functional in the browser            |

### Optional Integrations

For enhanced functionality, these plugins can be integrated:

| Plugin/Service    | Purpose                                          |
|-------------------|--------------------------------------------------|
| Dokan Pro         | Enhanced vendor features and modules             |
| WPML              | Multi-language marketplace support               |
| Stripe            | Direct vendor payment processing                 |
| Mailchimp         | Email marketing integration                      |
| Google Maps       | Distance-based shipping calculations             |

## Hosting Recommendations

### Shared Hosting

For smaller marketplaces (< 1,000 products):
- Minimum 2 CPU cores
- 4GB RAM
- SSD storage
- Managed WordPress hosting recommended

### VPS/Dedicated Hosting

For larger marketplaces (1,000+ products):
- Minimum 4 CPU cores
- 8GB+ RAM
- SSD storage
- Content Delivery Network (CDN) recommended
- Database optimization and caching solutions

### Managed WordPress Hosting

Recommended providers with good WP Plugin Starter compatibility:
- WP Engine
- Kinsta
- Cloudways
- SiteGround
- Nexcess

## Checking Your System

You can verify your system meets these requirements by:

1. Installing the Health Check & Troubleshooting plugin
2. Using the Site Health tool in WordPress admin
3. Running the WP Plugin Starter compatibility check during installation

## Common Compatibility Issues

| Issue | Solution |
|-------|----------|
| **Memory limit errors** | Increase PHP memory limit in wp-config.php or contact hosting provider |
| **Maximum execution time exceeded** | Increase max_execution_time in PHP settings |
| **Missing PHP extensions** | Request your hosting provider to enable required extensions |
| **WordPress cron issues** | Consider using a real cron job instead of WP-Cron |
| **REST API errors** | Check for plugin conflicts or server configuration issues blocking the REST API |
| **File permission issues** | Ensure proper permissions on wp-content directory (755 for folders, 644 for files) |

## Multisite Compatibility

WP Plugin Starter is compatible with WordPress Multisite installations, with these considerations:

- Network activation is supported
- Each site requires its own WP Plugin Starter configuration
- Some features may require network-level settings

## Performance Considerations

For optimal performance with WP Plugin Starter:

- Use PHP 8.0+ for better performance
- Implement page caching (with exclusions for dynamic pages)
- Consider object caching (Redis or Memcached)
- Use a CDN for static assets
- Optimize database regularly
- Keep plugins to the necessary minimum
