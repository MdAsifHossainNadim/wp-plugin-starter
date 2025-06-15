# WP Plugin Starter User Guide

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

Welcome to the comprehensive user guide for WP Plugin Starter, the feature-packed add-on designed to enhance your WordPress site or multi-vendor marketplace. This guide will walk you through all the features and settings available in WP Plugin Starter.

## 📋 Table of Contents

- [Installation](#-installation)
- [Getting Started](#-getting-started)
- [Product Management](#-product-management)
- [Vendor Management](#-vendor-management)
- [Cart & Checkout](#-cart--checkout)
- [Shipping Options](#-shipping-options)
- [Display Settings](#-display-settings)
- [Advanced Settings](#-advanced-settings)
- [Troubleshooting](#-troubleshooting)
- [FAQ](#-faq)

## 🔧 Installation

### Requirements

Before installing WP Plugin Starter, ensure your system meets the following requirements:

- WordPress 6.4.2 or higher
- PHP 7.4 or higher
- (Optional) Dokan Lite 3.9.7 or higher
- (Optional) WooCommerce 6.0 or higher

### Installation Steps

1. **Download the Plugin**:

    - Download the WP Plugin Starter ZIP file from the official website or marketplace

2. **Upload and Install**:

    - Go to WordPress Dashboard > Plugins > Add New > Upload Plugin
    - Choose the downloaded ZIP file and click "Install Now"

3. **Activate the Plugin**:

    - After installation, click on "Activate Plugin"
    - Verify that WP Plugin Starter appears in the WordPress admin menu

4. **Verify Installation**:
    - Navigate to WP Plugin Starter in your WordPress admin
    - The dashboard should display with all available features

## 🚀 Getting Started

### Dashboard Overview

After activating WP Plugin Starter, you'll find a new menu item "WP Plugin Starter" under the main WordPress menu. The dashboard provides an overview of enabled features and quick access to settings.

![Dashboard Overview](../assets/images/dashboard-overview.jpg)

### Quick Setup

For a quick start, we recommend:

1. Go to **WP Plugin Starter > Settings**
2. Review each tab and enable the features you need
3. Save your settings
4. Test your site with the new features enabled

## 📦 Product Management

WP Plugin Starter provides several features to enhance product management for vendors.

### Product Types Control

Control which product types vendors can create in their dashboard.

1. Go to **WP Plugin Starter > Settings > Product**
2. Find the "Product Types" section
3. Toggle options to enable/disable specific product types:
    - Simple Products
    - Variable Products
    - Downloadable Products
    - Virtual Products
    - Subscription Products (requires Dokan Subscription)
    - Other product types

![Product Types Settings](../assets/images/product-types-settings.jpg)

### Image Restrictions

Set restrictions for product images to maintain consistency and quality.

1. Go to **WP Plugin Starter > Settings > Product**
2. Find the "Image Restrictions" section
3. Configure the following options:
    - Maximum Width (px)
    - Maximum Height (px)
    - Maximum File Size (MB)
    - Required Minimum Width (px)
    - Required Minimum Height (px)
    - Allowed File Types

Enable "Show validation message" to provide feedback to vendors when their images don't meet requirements.

### Product Fields Customization

Simplify the product form by removing unnecessary fields.

1. Go to **WP Plugin Starter > Settings > Product**
2. Find the "Product Fields" section
3. Toggle options to remove specific fields:
    - Short Description
    - Regular Price
    - Sale Price
    - Product Tags
    - Weight & Dimensions
    - Shipping Class
    - Product Attributes
    - Other product fields

This helps create a streamlined experience for vendors by showing only necessary fields.

## 👥 Vendor Management

WP Plugin Starter provides features to enhance vendor management and registration.

### Vendor Registration

Customize the vendor registration process.

1. Go to **WP Plugin Starter > Settings > Vendor**
2. Find the "Registration" section
3. Configure the following options:
    - Remove Vendor Checkbox (removes "I am a vendor" option)
    - Enable "I am a Vendor" by default (auto-checks the vendor option)
    - Custom Vendor Registration Fields (add additional fields)
    - Registration Approval (require manual approval)

### Vendor Capabilities

Control what vendors can do in their dashboard.

1. Go to **WP Plugin Starter > Settings > Vendor**
2. Find the "Capabilities" section
3. Toggle options to enable/disable specific capabilities:
    - Product Creation
    - Order Management
    - Coupon Creation
    - Review Management
    - Withdrawal Requests
    - Store Settings
    - Other capabilities

### Account Settings

Customize vendor account settings and pages.

1. Go to **WP Plugin Starter > Settings > Vendor**
2. Find the "Account Settings" section
3. Configure options like:
    - Store Header Customization
    - Store URL Structure
    - Store Policy Options
    - Social Profile Links
    - Payment Methods

## 🛒 Cart & Checkout

Enhance the shopping experience with cart and checkout customizations.

### Cart Buttons

Customize cart buttons throughout your marketplace.

1. Go to **WP Plugin Starter > Settings > Cart**
2. Find the "Cart Buttons" section
3. Configure the following options:
    - Custom "Add to Cart" button text
    - Enable "Buy Now" button
    - Custom "Buy Now" button text
    - Button styling options (colors, size, etc.)
    - Button positions

![Cart Button Settings](../assets/images/cart-button-settings.jpg)

### Order Completion

Enhance the order completion process.

1. Go to **WP Plugin Starter > Settings > Cart**
2. Find the "Order Completion" section
3. Configure options like:
    - Custom thank you messages
    - Order details display
    - Social sharing options
    - Related products display

## 🚚 Shipping Options

WP Plugin Starter provides additional shipping options for vendors.

### Lite Shipping

Simplify shipping for vendors with lite shipping options.

1. Go to **WP Plugin Starter > Settings > Shipping**
2. Find the "Lite Shipping" section
3. Configure options like:
    - Flat rate shipping
    - Free shipping thresholds
    - Location-based rates
    - Shipping calculation methods

### Pro Shipping

Advanced shipping options for more complex needs (requires Dokan Pro).

1. Go to **WP Plugin Starter > Settings > Shipping**
2. Find the "Pro Shipping" section
3. Configure advanced options like:
    - Table rate shipping
    - Distance rate shipping
    - Vendor shipping zones
    - Shipping class support

## 🎨 Display Settings

Customize how your marketplace looks and functions.

### Product Display

Customize how products are displayed.

1. Go to **WP Plugin Starter > Settings > Display**
2. Find the "Product Display" section
3. Configure options like:
    - Product grid layout
    - Product card design
    - Featured product highlighting
    - Sale badge customization

### Store Display

Customize vendor store appearance.

1. Go to **WP Plugin Starter > Settings > Display**
2. Find the "Store Display" section
3. Configure options like:
    - Store header layout
    - Store sidebar widgets
    - Product filtering options
    - Store map display

## ⚙️ Advanced Settings

Configure advanced options for your marketplace.

### Performance

Optimize performance with caching and loading options.

1. Go to **WP Plugin Starter > Settings > Advanced**
2. Find the "Performance" section
3. Configure options like:
    - Feature-specific caching
    - Lazy loading options
    - Script optimization

### Compatibility

Ensure compatibility with other plugins and themes.

1. Go to **WP Plugin Starter > Settings > Advanced**
2. Find the "Compatibility" section
3. Configure options for compatibility with:
    - Popular themes
    - Page builders
    - Other WooCommerce extensions

### Debugging

Tools to help troubleshoot issues.

1. Go to **WP Plugin Starter > Settings > Advanced**
2. Find the "Debugging" section
3. Access options like:
    - Debug log viewer
    - System status information
    - Test tools for specific features

## 🛠️ Troubleshooting

### Common Issues

#### Feature Not Working

**Symptoms**: A feature is enabled but doesn't seem to be working

**Solutions**:

1. Verify the feature is enabled in WP Plugin Starter settings
2. Check for conflicts with other plugins (disable other plugins temporarily)
3. Check the browser console for JavaScript errors
4. Review the system status page for compatibility issues
5. Clear your cache and test again

#### Settings Not Saving

**Symptoms**: Changes to settings don't seem to persist after saving

**Solutions**:

1. Check for JavaScript errors in the browser console
2. Ensure you have adequate permissions
3. Verify WordPress nonces aren't expiring (check for caching issues)
4. Try a different browser
5. Check server error logs

#### Vendor Complaints

**Symptoms**: Vendors report missing features or confusing interface

**Solutions**:

1. Verify vendor capabilities are set correctly
2. Check if the feature is restricted to certain vendor levels
3. Create documentation for vendors explaining the features
4. Consider running a training session for vendors

### Checking System Status

The System Status page provides valuable information for troubleshooting:

1. Go to **WP Plugin Starter > Settings > Advanced**
2. Find the "System Status" section
3. Review information about:
    - WordPress environment
    - Server environment
    - Active plugins
    - Theme compatibility
    - Feature status

If you encounter issues, this information will be helpful when contacting support.

## ❓ FAQ

### General Questions

**Q: Is WP Plugin Starter compatible with my theme?**

A: WP Plugin Starter is designed to be compatible with most WordPress themes, especially those that are WooCommerce compatible. For best results, we recommend using a theme that is fully compatible with WP Plugin Starter.

**Q: Can I use WP Plugin Starter with Dokan Lite?**

A: Yes, WP Plugin Starter is fully compatible with Dokan Lite. However, some advanced features may require Dokan Pro.

**Q: Will WP Plugin Starter slow down my site?**

A: WP Plugin Starter is designed to be lightweight and efficient. Features are loaded only when needed, and you can disable any features you don't use to optimize performance.

### Product Management

**Q: Can vendors still create variable products if I disable them?**

A: No, if you disable variable products in the WP Plugin Starter settings, vendors will not see the option to create variable products in their dashboard.

**Q: What happens if a vendor uploads an image that doesn't meet the restrictions?**

A: If validation is enabled, the vendor will see an error message explaining why the image was rejected. They'll need to upload an image that meets the requirements.

### Vendor Management

**Q: If I remove the vendor checkbox, how do users become vendors?**

A: There are several options:

1. You can manually assign vendor status to users
2. Use the "Enable by default" option to make all new users vendors
3. Create a custom registration page with vendor status built-in

**Q: Can I have different capabilities for different vendors?**

A: WP Plugin Starter provides global settings that apply to all vendors. For vendor-specific capabilities, you may need to use Dokan Pro or develop a custom extension.

### Cart & Checkout

**Q: What's the difference between "Add to Cart" and "Buy Now"?**

A: "Add to Cart" adds the product to the cart and keeps the customer on the current page, while "Buy Now" adds the product to the cart and immediately redirects to checkout.

**Q: Can I customize button styles to match my theme?**

A: Yes, WP Plugin Starter provides styling options for buttons including colors, size, padding, and more to help match your theme's design.

### Technical Questions

**Q: Can I programmatically enable/disable features?**

A: Yes, you can use the WP Plugin Starter API or hooks to programmatically manage features. See the [Developer Documentation](./developer-docs.md) for details.

**Q: Is WP Plugin Starter translation-ready?**

A: Yes, WP Plugin Starter is fully translation-ready and compatible with WPML, Polylang, and other translation plugins.

## 📚 Getting Help

If you need additional help beyond this guide:

1. **Support Tickets**: Submit a support ticket from your account dashboard
2. **Documentation**: Visit our online documentation for more detailed guides
3. **Community Forum**: Join our community forum to connect with other users
4. **Contact Us**: Use the contact form on our website for pre-sales questions

We're committed to helping you make the most of WP Plugin Starter and your WordPress site!
