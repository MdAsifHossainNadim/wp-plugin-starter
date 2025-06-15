# Frequently Asked Questions (FAQ)

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document answers common questions about WP Plugin Starter and its features.

## 📦 General Questions

### What is WP Plugin Starter?

WP Plugin Starter is a feature-packed add-on for WordPress sites. It enhances and extends the functionality of WordPress with additional features for product management, vendor management, cart & checkout, and shipping.

### Do I need any other plugin to use WP Plugin Starter?

No, WP Plugin Starter works as a standalone plugin but can integrate with other plugins like WooCommerce and Dokan. Some advanced features may require these integrations, but the core functionality works independently.

### Will WP Plugin Starter slow down my site?

WP Plugin Starter is designed to be lightweight and efficient. Features are loaded only when needed, and you can disable any features you don't use to optimize performance. In most cases, the impact on site performance is minimal, especially with proper caching configured.

### Is WP Plugin Starter compatible with my theme?

WP Plugin Starter is designed to be compatible with most WordPress themes, especially those that are WooCommerce compatible. For best results, we recommend using themes that are fully compatible with WooCommerce and Dokan, such as Astra, Flatsome, Storefront, Avada, and OceanWP.

### Does WP Plugin Starter work with WordPress Multisite?

Yes, WP Plugin Starter is fully compatible with WordPress Multisite installations. You can use it on individual sites within your network or network-activate it for all sites.

### Is WP Plugin Starter translation-ready?

Yes, WP Plugin Starter is fully translation-ready and compatible with WPML, Polylang, and other translation plugins. All user-facing strings are properly prepared for translation.

## 🔧 Installation & Setup

### What are the minimum requirements for WP Plugin Starter?

WP Plugin Starter requires:

- WordPress 6.4.2 or higher
- PHP 7.4 or higher
- (Optional) Dokan Lite 3.9.7 or higher
- (Optional) WooCommerce 6.0 or higher

For detailed requirements, see the [System Requirements](requirements.md) document.

### Can I use WP Plugin Starter on shared hosting?

Yes, WP Plugin Starter works on most shared hosting plans as long as they meet the minimum requirements. For optimal performance, we recommend a hosting plan with at least 2GB of RAM and PHP configured with a memory limit of 128MB or higher.

### How do I install WP Plugin Starter?

Installation is simple:

1. Download the plugin ZIP file
2. Go to WordPress Dashboard > Plugins > Add New > Upload Plugin
3. Upload the ZIP file and click "Install Now"
4. Activate the plugin

For detailed installation instructions, see the [Installation Guide](installation.md).

### Do I need to configure WP Plugin Starter after installation?

While WP Plugin Starter works with default settings, we recommend reviewing the settings to customize the plugin for your specific needs. Go to WP Plugin Starter > Settings to access all configuration options.

### Can I migrate from another marketplace plugin to WP Plugin Starter?

WP Plugin Starter doesn't provide migration tools. However, if you're using Dokan or WooCommerce, you can integrate WP Plugin Starter to enhance your site.

## 🛒 Product Features

### Can I control which product types vendors can create?

Yes, WP Plugin Starter allows you to control which product types vendors can create. Go to WP Plugin Starter > Settings > Product > Product Types to enable or disable specific product types for vendors.

### How do image restrictions work?

Image restrictions allow you to enforce specific dimensions and file sizes for product images:

1. Go to WP Plugin Starter > Settings > Product > Image Restrictions
2. Configure maximum/minimum dimensions and file size
3. Enable validation messages to provide feedback to vendors

When vendors upload images that don't meet these requirements, they'll receive helpful error messages.

### Can I simplify the product form for vendors?

Yes, you can remove unnecessary fields from the product form to create a streamlined experience:

1. Go to WP Plugin Starter > Settings > Product > Product Fields
2. Toggle off fields you don't need
3. Save your settings

This helps create a more user-friendly form, especially for marketplaces with simple products.

### Does WP Plugin Starter support digital products?

Yes, WP Plugin Starter works with all product types supported by WooCommerce and Dokan, including digital and downloadable products. You can customize features for these product types just like physical products.

## 👥 Vendor Management

### Can I customize the vendor registration process?

Yes, WP Plugin Starter provides several options to customize the vendor registration process:

1. Go to WP Plugin Starter > Settings > Vendor > Registration
2. Configure options like removing the vendor checkbox or enabling it by default
3. Add custom registration fields if needed
4. Enable registration approval if you want to manually approve vendors

### How do vendor capabilities work?

Vendor capabilities allow you to control what vendors can do in their dashboard:

1. Go to WP Plugin Starter > Settings > Vendor > Capabilities
2. Toggle various capabilities on or off
3. Save your settings

This gives you fine-grained control over vendor permissions.

### Can vendors have different capabilities?

With the basic version, all vendors share the same capabilities. If you need different capability sets for different vendors, consider using Dokan Pro alongside WP Plugin Starter for vendor tiers.

### Can I customize the vendor dashboard with WP Plugin Starter?

WP Plugin Starter primarily enhances functionality rather than changing the dashboard layout. However, many features indirectly affect the dashboard by adding or removing options. For comprehensive dashboard customization, consider using Dokan Pro.

## 🛍️ Cart & Checkout

### Can I customize the "Add to Cart" button?

Yes, WP Plugin Starter allows you to customize cart buttons:

1. Go to WP Plugin Starter > Settings > Cart > Cart Buttons
2. Change button text, styling, and behavior
3. Enable the "Buy Now" feature if desired
4. Configure button positions on product pages

### What is the "Buy Now" feature?

The "Buy Now" feature adds a button that allows customers to bypass the cart and go directly to checkout with the selected product. This can streamline the purchase process and increase conversions.

### Can I customize the checkout process?

Yes, WP Plugin Starter provides options to enhance the checkout process:

1. Go to WP Plugin Starter > Settings > Cart > Order Completion
2. Configure options like thank you messages and order details display
3. Additional checkout field options are available in the advanced settings

### Does WP Plugin Starter work with third-party checkout plugins?

WP Plugin Starter is compatible with most third-party checkout plugins. However, some custom features like "Buy Now" might require additional configuration to work with certain checkout plugins.

## 🚚 Shipping

### What shipping options does WP Plugin Starter provide?

WP Plugin Starter enhances shipping capabilities with:

- Lite Shipping: Simple flat rate, free shipping, and location-based rates
- Pro Shipping: Advanced options like table rates and distance-based shipping
- Shipping Zones: Better control over vendor shipping by geographic zones

### Can vendors set their own shipping rates?

Yes, vendors can set their own shipping rates within the parameters defined in your settings. You control which shipping options are available to vendors and how they can configure them.

### Does WP Plugin Starter support multiple shipping methods?

Yes, WP Plugin Starter supports multiple shipping methods and allows you to control which ones are available to vendors. This includes all standard WooCommerce shipping methods plus Dokan-specific options.

### How does shipping work for multi-vendor orders?

WP Plugin Starter maintains Dokan's approach to multi-vendor orders, where each vendor's products can have separate shipping charges. The enhanced shipping features give you more control over how these charges are calculated and presented.

## 🔧 Technical Questions

### Can I extend WP Plugin Starter with custom features?

Yes, WP Plugin Starter is built with extensibility in mind. Developers can use the provided hooks and filters to add custom features or modify existing ones. See the [Developer Guide](../developer/developer-guide.md) for details.

### Does WP Plugin Starter create custom database tables?

Yes, WP Plugin Starter creates a few custom database tables to store feature-specific data. These tables are created during installation and properly maintained during updates.

### How is WP Plugin Starter built on the frontend?

WP Plugin Starter uses a combination of WordPress-standard templates and modern JavaScript. The frontend is designed to be lightweight and compatible with various themes.

### How is the admin interface built?

The admin interface is built with React components using the WordPress component library. This provides a modern, responsive experience that fits seamlessly into the WordPress admin.

### Can I programmatically access WP Plugin Starter features?

Yes, WP Plugin Starter provides a comprehensive API for programmatic access to all features. This includes both PHP functions and a REST API. See the [API Reference](../developer/api-reference.md) for details.

## 🆙 Updates & Maintenance

### How often is WP Plugin Starter updated?

WP Plugin Starter follows a regular update schedule with:

- Major releases: 2-3 times per year (new features, significant improvements)
- Minor releases: Monthly (bug fixes, compatibility updates, minor improvements)
- Patch releases: As needed (critical fixes)

### Will updates break my customizations?

We strive to maintain backward compatibility with all updates. If you've used the provided hooks and filters for customizations, they should continue to work after updates. However, it's always a good practice to test updates in a staging environment first.

### How do I update WP Plugin Starter?

You can update WP Plugin Starter through the WordPress admin just like any other plugin:

1. Go to WordPress Dashboard > Plugins
2. Find WP Plugin Starter in the list
3. Click "Update" if an update is available

For major updates, we recommend backing up your site first and testing the update in a staging environment.

### What should I do if an update causes issues?

If you encounter issues after an update:

1. Check if your theme and other plugins are compatible
2. Review the changelog for any breaking changes
3. Check our support resources for known issues
4. Contact our support team if needed

## 🛡️ Compliance & Security

### Is WP Plugin Starter GDPR compliant?

WP Plugin Starter follows WordPress and WooCommerce GDPR compliance best practices. It doesn't collect additional personal data beyond what WooCommerce and Dokan already collect, and it provides proper data handling for any vendor or customer information.

### How does WP Plugin Starter handle security?

WP Plugin Starter is built with security in mind:

- Input validation and sanitization for all user inputs
- Proper capability checks for all admin actions
- Secure AJAX handling
- Regular security audits and updates

### Does WP Plugin Starter affect my PCI compliance?

WP Plugin Starter doesn't directly handle payment information, so it doesn't affect your PCI compliance. Payment processing is handled by WooCommerce and your chosen payment gateways.

### How can I report a security issue?

If you discover a security issue, please do not disclose it publicly. Instead, email us at security@example.com with details so we can address it promptly.

## 🤝 Getting More Help

### Where can I find documentation?

Comprehensive documentation is available at:

- [User Guide](../user/user-guide.md) - For site administrators
- [Developer Guide](../developer/developer-guide.md) - For developers
- [API Reference](../developer/api-reference.md) - For programmatic access
- [Hooks Reference](../developer/hooks-reference.md) - For customizations

### How do I get support?

Support is available through multiple channels:

- Documentation: Check our comprehensive documentation first
- Support Forum: Visit our support forum for community help
- Support Tickets: Submit a support ticket for direct assistance
- Email Support: Contact our support team at support@example.com

### Can I request new features?

Yes, we welcome feature requests! Please submit your ideas through:

- Our feature request form on the website
- The GitHub repository issues section
- Direct email to our product team

### Do you offer custom development?

Yes, we offer custom development services for specific site needs. Contact our sales team for details and pricing.
