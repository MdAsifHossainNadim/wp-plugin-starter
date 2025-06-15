# Shipping in WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

This guide covers all shipping features available in WP Plugin Starter, including configuration options for both lite and pro shipping methods.

## 📋 Table of Contents

- [Shipping Overview](#-shipping-overview)
- [Lite Shipping](#-lite-shipping)
- [Pro Shipping](#-pro-shipping)
- [Shipping Zones](#-shipping-zones)
- [Shipping Classes](#-shipping-classes)
- [Multi-vendor Shipping](#-multi-vendor-shipping)
- [Shipping Labels](#-shipping-labels)
- [Shipping Tracking](#-shipping-tracking)
- [Fulfillment Integration](#-fulfillment-integration)
- [International Shipping](#-international-shipping)
- [Troubleshooting](#-troubleshooting)

## 🚚 Shipping Overview

WP Plugin Starter enhances the shipping capabilities of your multi-vendor marketplace, allowing both marketplace-wide and vendor-specific shipping configurations. The features are divided into Lite and Pro shipping options.

### Shipping Architecture

WP Plugin Starter shipping works alongside Dokan and WooCommerce's shipping system:

1. **Marketplace Level**: Global shipping options set by the admin
2. **Vendor Level**: Vendor-specific shipping options (when permitted)
3. **Product Level**: Product-specific shipping settings

### Shipping Feature Categories

| Feature Category | Description |
|-----------------|-------------|
| Basic Shipping | Simple flat rate and free shipping options |
| Advanced Shipping | Distance-based, table rates, and dynamic pricing |
| Vendor Controls | Customize vendor shipping permissions |
| Labels & Tracking | Streamline the fulfillment process |
| Carrier Integration | Connect with major shipping carriers |

## 📦 Lite Shipping

Lite shipping features are available in the base WP Plugin Starter plugin and provide essential shipping functionality.

### Available Methods

1. **Flat Rate Shipping**
   - Fixed price per order
   - Optional per-item cost
   - Configurable by vendor or admin

2. **Free Shipping**
   - Based on order total
   - Available for specific products
   - Configurable free shipping threshold

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > Basic**
2. Configure global shipping settings:
   - Default shipping method
   - Default costs
   - Free shipping thresholds
   - Shipping calculation method (per order or per item)

### Vendor Configuration

When enabled, vendors can configure lite shipping methods:

1. Vendor navigates to **Vendor Dashboard > Settings > Shipping**
2. Configures flat rate and free shipping options
3. Sets product-specific shipping options when adding/editing products

## 🚢 Pro Shipping

Pro shipping features provide advanced shipping options for more complex marketplace needs.

### Advanced Methods

1. **Table Rate Shipping**
   - Price based on weight ranges
   - Price based on price ranges
   - Price based on item count
   - Combined conditions

2. **Distance-Based Shipping**
   - Calculate shipping based on distance
   - Support for multiple distance units
   - Integration with Google Maps for distance calculation

3. **Time-Based Delivery**
   - Offer delivery time slots
   - Different rates for different delivery times
   - Processing time configuration

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > Advanced**
2. Configure global advanced shipping settings:
   - Enable/disable advanced methods
   - Set default configurations
   - Configure calculation priorities

### Vendor Configuration

When enabled, vendors can configure advanced shipping:

1. Vendor navigates to **Vendor Dashboard > Settings > Shipping > Advanced**
2. Creates shipping rules based on weight, price, or distance
3. Sets up delivery time options
4. Configures shipping restrictions

## 🌎 Shipping Zones

Shipping zones allow you to define different shipping methods and rates based on geographical regions.

### Zone Management

1. Navigate to **WP Plugin Starter > Settings > Shipping > Zones**
2. Configure shipping zones:
   - Create zones by country, state, or postcode
   - Assign specific shipping methods to each zone
   - Set zone-specific pricing

### Zone Hierarchy

Zones follow this priority order:
1. Postcode-specific zones
2. State/province-specific zones
3. Country-specific zones
4. Global (rest of the world) zone

### Vendor Zone Management

When enabled, vendors can manage their own shipping zones:

1. Vendor navigates to **Vendor Dashboard > Settings > Shipping > Zones**
2. Creates or modifies shipping zones
3. Configures shipping methods for each zone
4. Sets zone-specific pricing

## 📇 Shipping Classes

Shipping classes allow you to group products with similar shipping requirements.

### Class Management

1. Navigate to **WooCommerce > Settings > Shipping > Shipping Classes**
2. Create shipping classes relevant to your marketplace:
   - Standard products
   - Heavy items
   - Fragile products
   - Oversized items
   - Hazardous materials

### Class Configuration

For each shipping class, configure:
- Class name and description
- Default handling fees
- Special shipping requirements
- Carrier restrictions

### Vendor Class Assignment

When enabled, vendors can assign shipping classes to products:

1. Vendor navigates to **Product Edit Page**
2. Selects appropriate shipping class from dropdown
3. Optionally overrides default shipping class settings

## 👥 Multi-vendor Shipping

WP Plugin Starter provides several options for handling shipping in multi-vendor orders.

### Split Shipping Options

1. Navigate to **WP Plugin Starter > Settings > Shipping > Multi-vendor**
2. Choose from these shipping calculation methods:

   | Method | Description |
   |--------|-------------|
   | Split by Vendor | Each vendor's shipping calculated separately |
   | Combined Flat Rate | Single shipping fee for entire order |
   | Highest Cost | Apply the highest vendor shipping cost only |
   | Priority Vendor | Use shipping from vendor with most items |

### Cart Display Options

Configure how shipping options appear to customers:
- Show all vendor shipping options separately
- Show combined shipping only
- Show shipping breakdown during checkout
- Display estimated delivery dates by vendor

### Split Shipping User Experience

With split shipping enabled:
1. Customer adds products from multiple vendors
2. Shipping is calculated for each vendor separately
3. All shipping costs are shown during checkout
4. Customer can select different shipping methods per vendor

## 🏷️ Shipping Labels

WP Plugin Starter enhances the fulfillment process with integrated shipping label generation.

### Label Features

- One-click label generation
- Bulk label printing
- Label customization options
- Integration with major carriers

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > Labels**
2. Configure label settings:
   - Default label size and format
   - Carrier account connections
   - Label printer settings
   - Default package sizes

### Vendor Label Generation

When enabled, vendors can generate labels:

1. Vendor navigates to **Vendor Dashboard > Orders > Order Details**
2. Clicks "Generate Shipping Label"
3. Confirms or adjusts package details
4. Purchases and prints label

## 📍 Shipping Tracking

Keep customers informed with enhanced shipping tracking features.

### Tracking Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > Tracking**
2. Configure tracking settings:
   - Automatic tracking emails
   - Tracking page customization
   - SMS notifications (if enabled)
   - Tracking update frequency

### Carrier Integration

WP Plugin Starter integrates with major carriers:
- USPS, UPS, FedEx, DHL
- Local and regional carriers
- Custom carrier definitions
- API-based tracking updates

### Vendor Tracking Management

When enabled, vendors can manage tracking:

1. Vendor navigates to **Vendor Dashboard > Orders > Order Details**
2. Adds tracking number and carrier information
3. Optionally adds custom tracking notes
4. Shipping status updates automatically

## 🔄 Fulfillment Integration

Connect your marketplace with third-party fulfillment services.

### Available Integrations

- Amazon Fulfillment
- ShipStation
- Shippo
- Custom fulfillment services

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > Fulfillment**
2. Set up integration credentials
3. Configure order sync settings
4. Define fulfillment rules

### Vendor Fulfillment Options

When enabled, vendors can use fulfillment services:

1. Vendor connects their fulfillment account
2. Configures product fulfillment settings
3. Orders automatically route to fulfillment service
4. Tracking information syncs back to the marketplace

## 🌏 International Shipping

Enhanced features for marketplaces with international vendors and customers.

### International Features

- Customs documentation generation
- HS code management
- Duty and tax calculation
- International address validation

### Configuration

1. Navigate to **WP Plugin Starter > Settings > Shipping > International**
2. Configure international shipping settings:
   - Default customs information
   - Restricted countries/regions
   - Currency conversion for shipping costs
   - International return policies

### Vendor International Settings

When enabled, vendors can configure:
- Country-specific shipping rules
- Product-level customs information
- International shipping restrictions
- Export documentation settings

## ❓ Troubleshooting

Common shipping issues and solutions.

### Shipping Rates Not Showing

- **Problem**: Shipping rates don't appear at checkout
- **Solution**: Verify shipping zones are configured correctly and products have weight/dimensions

### Multiple Shipping Charges

- **Problem**: Customers being charged shipping multiple times
- **Solution**: Check multi-vendor shipping settings and consider using combined shipping

### Incorrect Shipping Calculations

- **Problem**: Shipping costs calculate incorrectly
- **Solution**: Verify product weights, dimensions, and shipping class assignments

### Missing Shipping Options

- **Problem**: Vendors can't see shipping configuration options
- **Solution**: Check vendor shipping capabilities in vendor settings

### Label Generation Errors

- **Problem**: Shipping labels fail to generate
- **Solution**: Verify carrier account credentials and address information

### Tracking Not Updating

- **Problem**: Tracking information not updating automatically
- **Solution**: Check carrier API connection and tracking update settings
