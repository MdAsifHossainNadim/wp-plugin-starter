# Vendor Management in WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

This guide covers all vendor management features available in WP Plugin Starter, helping you customize the vendor experience in your marketplace.

## 📋 Table of Contents

- [Vendor Registration](#-vendor-registration)
- [Vendor Capabilities](#-vendor-capabilities)
- [Account Settings](#-account-settings)
- [Store Settings](#-store-settings)
- [Commission Management](#-commission-management)
- [Vendor Verification](#-vendor-verification)
- [Vendor Levels](#-vendor-levels)
- [Performance Metrics](#-performance-metrics)
- [Vendor Communication](#-vendor-communication)
- [Vendor Compliance](#-vendor-compliance)
- [Troubleshooting](#-troubleshooting)

## 📝 Vendor Registration

Customize the vendor registration process to better suit your marketplace needs.

### Registration Options

1. Navigate to **WP Plugin Starter > Settings > Vendor > Registration**
2. Configure the following options:

    | Option                            | Description                                            | Use Case                              |
    | --------------------------------- | ------------------------------------------------------ | ------------------------------------- |
    | Remove Vendor Checkbox            | Removes "I am a vendor" option from registration       | For invitation-only marketplaces      |
    | Enable "I am a Vendor" by default | Auto-checks the vendor option during registration      | For vendor-focused marketplaces       |
    | Require Approval                  | Require admin approval before vendor accounts activate | For curated marketplaces              |
    | Custom Registration Fields        | Add additional fields to the vendor registration form  | For gathering specialized information |

### Custom Registration Fields

With WP Plugin Starter, you can add custom fields to the vendor registration form:

1. Navigate to **WP Plugin Starter > Settings > Vendor > Registration Fields**
2. Click **Add New Field**
3. Configure field options:
   - Field Type (text, select, radio, checkbox, file, etc.)
   - Label and Description
   - Required status
   - Validation rules (if applicable)
   - Display order

### Registration Form Customization

1. **Form Layout**: Adjust the registration form layout using the visual editor
2. **Form Styling**: Customize colors, borders, and spacing to match your theme
3. **Multi-step Registration**: Enable multi-step registration for complex forms
4. **Registration Email**: Customize the email sent to vendors after registration

## 🔒 Vendor Capabilities

WP Plugin Starter allows you to fine-tune vendor capabilities throughout your marketplace.

### Permission Control

1. Navigate to **WP Plugin Starter > Settings > Vendor > Capabilities**
2. Configure permissions for actions such as:

    | Capability                    | Description                                           |
    | ----------------------------- | ----------------------------------------------------- |
    | Product Management            | Control product creation, editing, and deletion       |
    | Order Management              | Configure order processing capabilities               |
    | Coupon Creation               | Allow/disallow vendors to create discount coupons     |
    | Review Management             | Permit vendors to respond to customer reviews         |
    | Withdrawal Access             | Control vendor access to withdrawal features          |
    | Report Access                 | Define which reports vendors can view                 |
    | Customer Messaging            | Allow vendors to message customers directly           |
    | Store Customization           | Control vendor store customization options            |
    | Shipping Management           | Configure vendor shipping management capabilities     |

### Default vs. Custom Capability Sets

- **Default**: Apply standard capabilities to all vendors
- **Custom**: Create capability sets that can be assigned to specific vendors or vendor levels

### Capability Assignment

Capabilities can be assigned based on:
- Individual vendor settings
- Vendor level/tier
- Vendor performance metrics
- Account age/history

## ⚙️ Account Settings

Manage vendor accounts with enhanced controls for security, access, and verification.

### Account Configuration

1. Navigate to **WP Plugin Starter > Settings > Vendor > Account**
2. Configure the following options:

    | Setting                        | Description                                            |
    | ------------------------------ | ------------------------------------------------------ |
    | Account Verification           | Require email/phone verification                       |
    | Two-Factor Authentication      | Enable enhanced security for vendor logins             |
    | Account Lockout                | Set rules for locking accounts after failed attempts   |
    | Password Requirements          | Define password strength requirements                  |
    | Account Suspension             | Configure rules for automatic suspension               |
    | Login Notifications            | Send notifications for suspicious login activities     |
    | Account Deletion               | Control vendor ability to delete their accounts        |

### Vendor Dashboard Access

1. Navigate to **WP Plugin Starter > Settings > Vendor > Dashboard**
2. Customize the vendor dashboard:
   - Available menu items
   - Default landing page
   - Dashboard widgets
   - Analytics display
   - Announcement section

## 🏪 Store Settings

Enhanced store management options give administrators more control while offering vendors more customization.

### Store Configuration

1. Navigate to **WP Plugin Starter > Settings > Vendor > Store**
2. Configure store-related settings:

    | Setting                        | Description                                            |
    | ------------------------------ | ------------------------------------------------------ |
    | Store URL Structure            | Customize vendor store URL format                      |
    | Store Header                   | Configure store header options and layout              |
    | Store Layout Templates         | Provide different layout options for vendors           |
    | Brand Consistency              | Enforce marketplace branding elements                  |
    | Store Policy Templates         | Provide templates for return/shipping policies         |
    | SEO Options                    | Control vendor SEO settings and capabilities           |
    | Store Opening Hours            | Enable/configure opening hours feature                 |
    | Vacation Mode                  | Allow vendors to temporarily close their stores        |

### Store Customization Limits

Define which elements vendors can customize:

- Banner images (dimensions, content restrictions)
- Store logo (dimensions, content restrictions)
- Color schemes (preset options or full control)
- Typography (font families, sizes, colors)
- Layout options (sidebar position, widget areas)

## 💰 Commission Management

Dokan Kits extends Dokan's commission capabilities with additional structures and automation.

### Commission Structure Options

1. Navigate to **Dokan Kits > Settings > Vendor > Commission**
2. Configure commission structures:

    | Commission Type                | Description                                            |
    | ------------------------------ | ------------------------------------------------------ |
    | Fixed                          | Set fixed commission percentage for all products       |
    | Category-based                 | Set different rates by product category                |
    | Product-specific               | Allow commission to be set per product                 |
    | Tiered                         | Commission rates change based on sales volume          |
    | Combined                       | Percentage plus fixed fee per sale                     |
    | Progressive                    | Rates increase as vendor reaches performance goals     |

### Commission Rules

Create dynamic commission rules based on:

- Sales volume (daily, monthly, yearly)
- Product categories
- Vendor performance metrics
- Customer acquisition
- Seasonal promotions

### Commission Reports

Enhanced commission reporting includes:

- Detailed earnings breakdowns
- Commission forecasting
- Tax implications
- Comparative analysis
- Export options (CSV, PDF, Excel)
- Scheduled reports via email

## ✓ Vendor Verification

Dokan Kits enhances vendor verification to build customer trust and ensure marketplace quality.

### Verification Methods

1. Navigate to **Dokan Kits > Settings > Vendor > Verification**
2. Configure verification options:

    | Verification Method            | Description                                            |
    | ------------------------------ | ------------------------------------------------------ |
    | ID Verification                | Government ID upload and verification                   |
    | Address Verification           | Physical address confirmation                          |
    | Business Documentation         | Business license, tax documents verification           |
    | Phone Verification             | SMS/call verification                                  |
    | Social Profile Verification    | Connect and verify social media accounts               |
    | External Verification          | Integration with third-party verification services     |

### Verification Process

1. Vendor submits verification materials
2. System performs automated checks (if configured)
3. Administrator reviews submissions
4. Verification status updated
5. Vendor notified of outcome

### Verification Display

Configure how verification status appears to customers:

- Verification badge design
- Badge placement on store/products
- Verification status tooltips
- Verification details visibility

## 🎖️ Vendor Levels

Dokan Kits introduces a comprehensive vendor leveling system to incentivize performance and create progression paths.

### Level Configuration

1. Navigate to **Dokan Kits > Settings > Vendor > Levels**
2. Create custom vendor levels with:
   - Level name and description
   - Visual badge/indicator
   - Qualification criteria
   - Associated benefits
   - Automatic/manual assignment rules

### Level Criteria

Set requirements for each level based on:

- Sales volume (total or periodic)
- Customer ratings/reviews
- Return rate
- Account age
- Product count
- Order fulfillment metrics
- Custom KPIs

### Level Benefits

Assign benefits to each level:

- Commission rate adjustments
- Featured placement in search/categories
- Additional product categories
- Enhanced store customization
- Priority support
- Reduced fees
- Special badges/indicators

### Level Progression

Configure how vendors move between levels:

- Automatic progression based on metrics
- Manual admin approval required
- Probation periods for new levels
- Level degradation rules for underperformance

## 📊 Performance Metrics

Dokan Kits provides enhanced analytics for monitoring vendor performance.

### Vendor Metrics Dashboard

1. Navigate to **Dokan Kits > Vendors > Performance**
2. View comprehensive metrics for all vendors:
   - Sales performance (trends, averages, goals)
   - Customer satisfaction ratings
   - Order fulfillment speed
   - Return/refund rates
   - Product quality scores
   - Response time to inquiries
   - Inventory management efficiency
   - Store traffic and conversion rates

### Performance Alerts

Configure alerts for:
- Significant performance changes
- Service level violations
- Exceptional performance for recognition
- Required administrative intervention

### Vendor Insights

Provide vendors with actionable insights:
- Performance comparisons (anonymized)
- Improvement recommendations
- Achievement recognition
- Goal tracking and projections

## 💬 Vendor Communication

Dokan Kits enhances communication between administrators and vendors.

### Communication Tools

1. Navigate to **Dokan Kits > Vendor > Communication**
2. Access communication features:
   - Announcement system for all vendors
   - Targeted announcements for specific vendors/levels
   - Private messaging system
   - Notice board for policy updates
   - Feedback collection forms
   - Scheduled emails and notifications

### Notification System

Configure automated notifications for vendors:
- New order alerts
- Inventory threshold warnings
- Customer inquiries
- Review notifications
- Performance milestone achievements
- Account status changes
- Security alerts

### Communication Templates

Create and manage templates for common communications:
- Welcome messages
- Policy updates
- Performance reviews
- Holiday announcements
- Marketplace changes
- Promotional opportunities

## 🚨 Vendor Compliance

Ensure vendors meet marketplace standards with compliance monitoring tools.

### Compliance Settings

1. Navigate to **Dokan Kits > Settings > Vendor > Compliance**
2. Configure compliance requirements:
   - Required legal documents
   - Policy acknowledgments
   - Regular review schedules
   - Compliance checklists
   - Violation consequences

### Compliance Monitoring

Automated tools to monitor:
- Policy adherence
- Required field completion
- Document expiration/renewal
- Customer complaints/issues
- Intellectual property violations
- Prohibited content/products

### Compliance Actions

Configure automated actions for non-compliance:
- Warning notifications
- Temporary suspension
- Feature limitation
- Product delisting
- Account termination
- Appeal process

## ❓ Troubleshooting

Common vendor management issues and solutions.

### Registration Issues

- **Problem**: Vendors cannot complete registration form
- **Solution**: Check for required field validation errors or form submission conflicts

### Permission Problems

- **Problem**: Vendors reporting missing features
- **Solution**: Review capability settings for the vendor's level or individual account

### Commission Calculation Errors

- **Problem**: Incorrect commission amounts
- **Solution**: Verify commission rules priority and check for rule conflicts

### Store Customization Limitations

- **Problem**: Vendors unable to customize certain elements
- **Solution**: Check store customization permissions in vendor capabilities

### Verification Delays

- **Problem**: Vendors waiting too long for verification
- **Solution**: Check verification queue and ensure notification emails are working

### Level Progression Issues

- **Problem**: Vendors not advancing to expected levels
- **Solution**: Review level criteria and check if any exclusion rules are affecting progression
