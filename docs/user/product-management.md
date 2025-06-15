# Product Management in WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This guide explains how to configure and use the product management features in WP Plugin Starter.

## 📋 Table of Contents

- [Product Types Control](#-product-types-control)
- [Image Restrictions](#-image-restrictions)
- [Product Fields Customization](#-product-fields-customization)
- [Product Options](#-product-options)
- [Advanced Settings](#-advanced-settings)
- [Bulk Product Management](#-bulk-product-management)
- [Product Templates](#-product-templates)
- [Product Approval System](#-product-approval-system)

## 🏷️ Product Types Control

WP Plugin Starter allows marketplace administrators to control which product types vendors can create in their dashboards. This helps simplify the vendor experience and ensures products remain consistent with your marketplace vision.

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Product > Product Types**
2. Toggle options to enable/disable specific product types:

    | Product Type                | Description                                              |
    | --------------------------- | -------------------------------------------------------- |
    | Simple Products             | Basic products with no options                           |
    | Variable Products           | Products with variations (size, color, etc.)             |
    | Digital Products            | Downloadable files                                       |
    | Virtual Products            | Non-physical products or services                        |
    | Subscription Products       | Recurring payment products (requires Dokan Subscription) |
    | External/Affiliate Products | Links to products on other sites                         |

3. Click "Save Changes" to apply your settings

### Administrator Experience

- Control which product types appear in the vendor dashboard
- Set default product type for new vendor products
- Apply product type restrictions by vendor level (if using vendor tiers)

### Vendor Experience

- Only enabled product types will appear in the "Add New Product" dropdown
- Simplified interface showing only relevant fields for the selected product type
- Clear guidance on product type capabilities and limitations

## 📸 Image Restrictions

WP Plugin Starter enhances product image management with additional controls for quality, dimensions, and counts.

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Product > Images**
2. Configure the following options:

    | Setting                      | Description                                         |
    | ---------------------------- | --------------------------------------------------- |
    | Maximum Product Images       | Total number of images allowed per product          |
    | Minimum Image Dimensions     | Required width/height in pixels                     |
    | Maximum Image Dimensions     | Maximum width/height in pixels                      |
    | Maximum File Size            | Size limit in MB for uploads                        |
    | Required Image Formats       | Allow only specific file types (JPG, PNG, etc.)     |
    | Image Optimization           | Automatically optimize uploaded images              |
    | Watermarking                 | Apply automatic watermarks to product images        |

3. Click "Save Changes" to apply your settings

### Image Validation

When a vendor uploads product images, WP Plugin Starter validates them against your configured restrictions:

- Size validation occurs before upload to save bandwidth
- Dimension validation ensures professional presentation
- Format validation prevents unsupported file types
- Count validation ensures vendors don't exceed limits

### Administrator Benefits

- Maintain consistent image quality across your marketplace
- Reduce storage requirements with size limitations
- Prevent poor-quality images from affecting marketplace appearance
- Simplify image management for vendors

## 🔧 Product Fields Customization

WP Plugin Starter allows administrators to customize product submission forms by adding, removing, or making fields required.

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Product > Fields**
2. Customize product fields:

    | Action                     | Description                                           |
    | -------------------------- | ----------------------------------------------------- |
    | Enable/Disable Fields      | Show or hide specific fields from vendors              |
    | Mark Fields as Required    | Force vendors to complete specific information         |
    | Add Custom Fields          | Create new fields for vendor product submissions       |
    | Field Ordering             | Change display order of fields in submission form      |
    | Field Groups               | Organize related fields into collapsible sections      |

3. For custom fields, specify:
   - Field type (text, select, checkbox, date, etc.)
   - Validation rules
   - Default values
   - Help text for vendors

### Common Field Customizations

- **Making SKU Required**: Ensure inventory tracking
- **Adding Brand Field**: Standardize brand information
- **Custom Specifications**: Add industry-specific attributes
- **Hiding Unnecessary Fields**: Simplify form for your business model

## 🔖 Product Options

Configure additional product options and behaviors to enhance your marketplace functionality.

### Available Settings

1. Navigate to **WP Plugin Starter > Settings > Product > Options**
2. Configure the following options:

    | Setting                      | Description                                         |
    | ---------------------------- | --------------------------------------------------- |
    | Product Status               | Default status for new vendor products              |
    | Product Visibility           | Default catalog visibility settings                 |
    | Inventory Management         | Enable/disable stock management for vendors         |
    | Shipping Configuration       | Default shipping settings for new products          |
    | Product Reviews              | Review moderation and display settings              |
    | Featured Products            | Allow vendors to mark products as featured          |
    | Cross-Sell/Upsell           | Enable vendor cross-sell and upsell capabilities    |

### Product Visibility Options

- **Catalog & Search**: Products visible everywhere (default)
- **Catalog Only**: Products visible in category pages but not search
- **Search Only**: Products visible in search but not category pages
- **Hidden**: Products only visible on direct link

## 📊 Bulk Product Management

WP Plugin Starter enhances bulk product operations for both administrators and vendors.

### Administrator Features

1. Navigate to **Dokan > Products** in the WordPress admin
2. Use the bulk actions dropdown for operations like:
   - Change product status (draft, published, pending)
   - Update pricing (fixed amount or percentage)
   - Update stock status and quantity
   - Assign or remove categories
   - Enable/disable specific features (reviews, shipping, etc.)

### Vendor Features

Vendors can access similar bulk operations from their dashboard:

1. Navigate to **Vendor Dashboard > Products**
2. Select multiple products using checkboxes
3. Access bulk actions from the dropdown menu
4. Apply changes with a single click

## 🧩 Product Templates

WP Plugin Starter allows administrators to create product templates that vendors can use as starting points.

### Creating Templates

1. Navigate to **WP Plugin Starter > Product Templates**
2. Click "Add New Template"
3. Configure template settings:
   - Name and description
   - Pre-filled product fields
   - Required fields
   - Available categories
   - Default images (optional)
4. Save the template

### Assigning Templates

- Assign templates to specific vendor levels
- Make templates available to all vendors
- Set default templates by product category

### Vendor Experience

When a vendor adds a new product, they can:

1. Start from scratch
2. Select from available templates
3. Have key fields pre-populated
4. Customize as needed

## ✅ Product Approval System

WP Plugin Starter enhances Dokan's product approval system with additional controls and workflows.

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Product > Approval**
2. Configure approval settings:
   - Enable/disable automatic approvals
   - Set approval requirements by vendor level
   - Configure email notifications
   - Set up approval checklists

### Approval Workflow

1. Vendor submits a product
2. Product enters "Pending" status
3. Administrator receives notification
4. Administrator reviews product details
5. Product is approved or rejected with feedback
6. Vendor receives notification of decision

### Approval Rules

Create automatic approval rules based on:
- Vendor level/reputation
- Product category
- Price range
- Product type
- Submission completeness

## 🔍 Best Practices

1. **Start Simple**: Begin by enabling only essential product types
2. **Use Templates**: Create templates for common product types in your marketplace
3. **Create Field Groups**: Organize related fields into logical sections
4. **Document Requirements**: Provide vendors with clear guidelines
5. **Monitor Performance**: Adjust settings based on vendor feedback and marketplace needs

## ❓ Troubleshooting

### Common Issues

- **Missing Product Types**: Check that product types are enabled in settings
- **Image Upload Failures**: Verify image restriction settings aren't too strict
- **Field Visibility Issues**: Ensure fields are properly enabled in customization
- **Template Availability**: Check vendor level assignments for templates
- **Approval Delays**: Review approval workflow settings and notification emails
