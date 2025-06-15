# Cart and Checkout in WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

This guide covers all the cart and checkout enhancement features available in WP Plugin Starter.

## 📋 Table of Contents

- [Cart Buttons](#-cart-buttons)
- [Buy Now Functionality](#-buy-now-functionality)
- [Order Completion](#-order-completion)
- [Checkout Fields](#-checkout-fields)
- [Multi-vendor Cart](#-multi-vendor-cart)
- [Checkout Optimization](#-checkout-optimization)
- [Payment Methods](#-payment-methods)
- [Order Notes](#-order-notes)
- [Abandoned Cart Recovery](#-abandoned-cart-recovery)
- [Checkout Design](#-checkout-design)
- [Troubleshooting](#-troubleshooting)

## 🛒 Cart Buttons

Customize cart buttons throughout your marketplace to improve the shopping experience and increase conversions.

### Button Customization

1. Navigate to **WP Plugin Starter > Settings > Cart > Cart Buttons**
2. Configure the following options:

    | Option                | Description                                        | Default                 |
    | --------------------- | -------------------------------------------------- | ----------------------- |
    | Add to Cart Text      | Customize the "Add to Cart" button text            | "Add to Cart"           |
    | Variable Product Text | Text for variable products before options selected | "Select options"        |
    | Out of Stock Text     | Text shown when product is unavailable             | "Out of stock"          |
    | Button Style          | Visual style of cart buttons                       | Theme default           |
    | Button Position       | Where the button appears on product pages          | After short description |

### AJAX Cart

Enable AJAX add-to-cart functionality to improve the shopping experience:

1. Navigate to **WP Plugin Starter > Settings > Cart > AJAX Settings**
2. Configure the following options:
   - Enable/disable AJAX add to cart
   - Show/hide cart notification popups
   - Customize popup appearance and duration
   - Configure mini cart behavior

### Quantity Controls

Enhance product quantity selection:

1. Navigate to **WP Plugin Starter > Settings > Cart > Quantity**
2. Configure the following options:
   - Quantity increment/decrement buttons
   - Minimum/maximum order quantities
   - Step values for quantity selection
   - Quantity validation messages

## 🏃 Buy Now Functionality

Bypass the cart and take customers directly to checkout with the Buy Now feature.

### Basic Configuration

1. Navigate to **WP Plugin Starter > Settings > Cart > Buy Now**
2. Configure the following options:

    | Option                  | Description                                          | Default |
    | ----------------------- | ---------------------------------------------------- | ------- |
    | Enable Buy Now          | Add a "Buy Now" button alongside "Add to Cart"       | Off     |
    | Button Text             | Customize the Buy Now button text                    | "Buy Now" |
    | Button Style            | Visual style of the Buy Now button                   | Primary  |
    | Clear Cart Before       | Clears existing cart items when Buy Now is clicked   | On      |
    | Redirect After Add      | Takes customer directly to checkout after clicking   | On      |

### Advanced Options

- **Product Types**: Choose which product types support Buy Now
- **Button Position**: Configure where the Buy Now button appears
- **Mobile Display**: Adjust button appearance on mobile devices
- **Vendor Control**: Allow vendors to enable/disable Buy Now for their products

### Buy Now Implementation

When properly configured, the Buy Now button:
1. Adds the product to the cart
2. Optionally clears other items from the cart
3. Redirects the customer immediately to the checkout page
4. Pre-fills available customer information

## 🔄 Order Completion

Customize the order completion process to improve customer experience and reduce support inquiries.

### Thank You Page

1. Navigate to **WP Plugin Starter > Settings > Checkout > Thank You**
2. Configure the following options:

    | Option                    | Description                                          | Default |
    | ------------------------- | ---------------------------------------------------- | ------- |
    | Custom Thank You Message  | Personalized message shown after order completion    | Default text |
    | Show Order Details        | Display comprehensive order information              | On      |
    | Show Vendor Information   | Display vendor details for each item                 | On      |
    | Download Links Position   | Where to display downloadable product links          | Top     |
    | Social Sharing            | Allow customers to share their purchase              | Off     |
    | Related Products          | Show related products on thank you page              | Off     |

### Order Emails

Customize the transactional emails sent after order completion:

1. Navigate to **WP Plugin Starter > Settings > Checkout > Emails**
2. Configure email templates for different order statuses
3. Add vendor-specific information to order emails
4. Customize email design and content

## 📝 Checkout Fields

Customize checkout fields to collect necessary information while maintaining a streamlined experience.

### Field Management

1. Navigate to **Dokan Kits > Settings > Checkout > Fields**
2. Manage standard checkout fields:

    | Field Section | Options |
    | ------------- | ------- |
    | Billing       | Add, remove, or modify billing fields |
    | Shipping      | Add, remove, or modify shipping fields |
    | Additional    | Add, remove, or modify order notes and custom fields |

### Field Actions

For each checkout field, you can:

- Change label text
- Update placeholder text
- Make fields required or optional
- Change field position/order
- Hide/show fields conditionally
- Add custom CSS classes

### Custom Fields

Add new custom fields to the checkout form:

1. Click "Add New Field" button
2. Configure field options:
   - Field type (text, select, checkbox, radio, etc.)
   - Label and placeholder
   - Required status
   - Validation rules
   - Display conditions
   
### Field Assignment

Determine who sees field data:

- Admin only fields
- Admin and vendor fields
- Fields visible to specific vendor levels

## 🛍️ Multi-vendor Cart

Enhance the multi-vendor shopping experience with specialized cart features.

### Cart Organization

1. Navigate to **Dokan Kits > Settings > Cart > Multi-vendor**
2. Configure how multi-vendor orders appear in the cart:

    | Option                 | Description                                          | Default |
    | ---------------------- | ---------------------------------------------------- | ------- |
    | Group by Vendor        | Organize cart items by vendor                        | On      |
    | Show Vendor Name       | Display the vendor name for each item                | On      |
    | Vendor Info Position   | Where to display vendor information                  | After item |
    | Separate Cart Totals   | Show subtotals grouped by vendor                     | Off     |

### Split Orders

Configure how multi-vendor orders are processed:

1. Navigate to **Dokan Kits > Settings > Checkout > Order Processing**
2. Choose between these options:
   - **Single Order**: Create one order containing items from all vendors
   - **Split Orders**: Create separate orders for each vendor
   - **Split Orders with Master**: Create individual vendor orders linked to a master order

### Split Payments

When using split orders, configure payment handling:

- **Single Payment**: Customer makes one payment that is later divided
- **Split Payment**: Customer makes separate payments for each vendor
- **Combined Display**: Show total amount but process as separate transactions

## 🚀 Checkout Optimization

Enhance checkout performance and usability to reduce abandonment rates.

### One-Page Checkout

1. Navigate to **Dokan Kits > Settings > Checkout > Layout**
2. Configure one-page checkout options:
   - Enable/disable one-page checkout
   - Choose layout template (Standard, Compact, Two-Column)
   - Configure section collapsing behavior
   - Set up progress indicators

### Guest Checkout

Streamline checkout for non-registered customers:

1. Navigate to **Dokan Kits > Settings > Checkout > Account**
2. Configure guest checkout options:
   - Enable/disable guest checkout
   - Optional account creation during checkout
   - Auto-generate username/password options
   - Guest order tracking features

### Express Checkout

For returning customers, offer express checkout features:

- Saved payment methods
- Address book functionality
- Order history auto-fill
- Recently ordered products quick-add

### Checkout Validation

Improve form validation for higher completion rates:

1. Navigate to **Dokan Kits > Settings > Checkout > Validation**
2. Configure validation options:
   - Real-time field validation
   - Address verification
   - Phone number format validation
   - Email verification
   - Custom validation rules

## 💳 Payment Methods

Enhance the payment experience with additional payment method controls.

### Payment Method Display

1. Navigate to **Dokan Kits > Settings > Checkout > Payments**
2. Configure payment method options:
   - Order payment methods by popularity
   - Show/hide payment method icons
   - Customize payment method descriptions
   - Set default payment method

### Vendor-Specific Payments

Allow vendors to have individual control over accepted payment methods:

1. Navigate to **Dokan Kits > Settings > Checkout > Vendor Payments**
2. Configure vendor payment options:
   - Allow vendors to enable/disable specific payment methods
   - Set commission rules by payment method
   - Configure payment processing fees by method
   - Set up payment method restrictions by vendor level

### Payment Processing

Customize payment processing behavior:

- Hold funds until vendor ships order
- Instant payment processing
- Scheduled disbursement options
- Partial payment and installment features

## 📝 Order Notes

Enhance communication during checkout with customizable order notes.

### Customer Notes

1. Navigate to **Dokan Kits > Settings > Checkout > Notes**
2. Configure customer note options:
   - Custom placeholder text
   - Character limits
   - Note categories/tags
   - Pre-defined note templates for customers

### Vendor Notes

Allow vendors to add order preparation notes:

- Delivery instructions
- Preparation preferences
- Gift messaging options
- Custom vendor note fields

### Internal Notes

Administrative notes visible only to administrators:
- Order verification flags
- Customer service notes
- Special handling instructions
- Fraud check notes

## 🔔 Abandoned Cart Recovery

Recapture lost sales with abandoned cart features.

### Cart Tracking

1. Navigate to **Dokan Kits > Settings > Cart > Abandoned Carts**
2. Configure tracking options:
   - When to consider a cart abandoned
   - What customer information to capture
   - How long to store abandoned cart data
   - Anonymous vs. logged-in tracking differences

### Recovery Emails

Set up automated abandoned cart emails:

1. Navigate to **Dokan Kits > Settings > Cart > Recovery Emails**
2. Configure recovery campaigns:
   - Email timing and sequences
   - Email content and templates
   - Recovery incentives (discounts, free shipping)
   - Performance tracking

### Recovery Links

Generate special recovery links that:
- Restore the exact abandoned cart
- Apply special recovery discounts
- Expire after configured time period
- Track recovery attribution

## 🎨 Checkout Design

Customize the visual appearance of the checkout process.

### Design Options

1. Navigate to **Dokan Kits > Settings > Checkout > Design**
2. Configure design elements:
   - Color scheme and buttons
   - Form styling and layout
   - Progress indicators
   - Mobile optimization settings

### Visual Elements

Customize additional visual elements:
- Trust badges and security icons
- Payment method logos
- Checkout header/footer
- Order summary styling

### Multi-step Design

For multi-step checkout, configure:
- Step indicators
- Animation between steps
- Step completion indicators
- Step-specific layouts

## ❓ Troubleshooting

Common cart and checkout issues and their solutions.

### Cart Issues

- **Problem**: Items not adding to cart
- **Solution**: Check for JavaScript conflicts or plugin compatibility issues

- **Problem**: Incorrect pricing in cart
- **Solution**: Verify product pricing settings and cache status

- **Problem**: AJAX cart not working
- **Solution**: Test for theme compatibility issues or JavaScript errors

### Checkout Issues

- **Problem**: Customers can't complete checkout
- **Solution**: Check required fields, payment gateway status, and form validation

- **Problem**: Multi-vendor orders not splitting correctly
- **Solution**: Verify split order settings and check for plugin conflicts

- **Problem**: Payment methods not displaying
- **Solution**: Verify payment gateway configuration and currency settings

- **Problem**: Custom fields not saving
- **Solution**: Check field configuration and database storage settings

### Order Processing Issues

- **Problem**: Thank you page not displaying correctly
- **Solution**: Check page template settings and order processing hooks

- **Problem**: Order emails not sending
- **Solution**: Verify email settings and test email functionality

- **Problem**: Order not appearing in vendor dashboard
- **Solution**: Check order assignment logic and vendor capabilities
