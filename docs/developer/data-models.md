# Data Models in WP Plugin Starter

This document provides a detailed overview of the data modeling system in WP Plugin Starter, including architecture, implementation patterns, extension points, and best practices.

## Table of Contents

- [Introduction](#introduction)
- [Architecture](#architecture)
- [Core Components](#core-components)
  - [Base Model Class](#base-model-class)
  - [Data Store Interface](#data-store-interface)
- [Sample Implementations](#sample-implementations)
  - [Product Model](#product-model)
  - [Product Data Store](#product-data-store)
  - [Settings Model](#settings-model)
- [Integration with WordPress & WooCommerce](#integration-with-wordpress--woocommerce)
- [Extension Points](#extension-points)
  - [Hooks & Filters](#hooks--filters)
  - [Subclassing](#subclassing)
- [Best Practices](#best-practices)
- [Example Use Cases](#example-use-cases)

## Introduction

The WP Plugin Starter data modeling system provides a robust, object-oriented approach to data management in WordPress plugins. Built on the principles established by WooCommerce's data layer, it adds enhanced type safety, comprehensive hooks for extensibility, and integration patterns for modern WordPress development.

This system allows you to:

1. Create structured data models with defined properties and types
2. Handle data persistence with clean separation of concerns
3. Extend existing WordPress and WooCommerce data with custom attributes
4. Implement proper validation and error handling
5. Support caching for performance optimization

## Architecture

The data model system follows a layered architecture:

```
┌───────────────────┐
│   Feature Layer   │  Product features, UI, etc.
└────────┬──────────┘
         │ Uses
┌────────▼──────────┐
│    Model Layer    │  Product_Model, Settings_Model, etc.
└────────┬──────────┘
         │ Uses
┌────────▼──────────┐
│  Data Store Layer │  Product_Data_Store, Settings_Data_Store, etc.
└────────┬──────────┘
         │ Uses
┌────────▼──────────┐
│   Database Layer  │  WordPress database via wpdb
└───────────────────┘
```

This separation provides several benefits:

- **Decoupling**: Business logic is separated from data persistence
- **Testability**: Each layer can be tested in isolation
- **Extensibility**: Each layer can be extended independently
- **Reusability**: Models can be reused across different features

## Core Components

### Base Model Class

The foundation of all data models is the `Model` class, which extends WooCommerce's `WC_Data`:

```php
namespace WP_Plugin_Starter\Core\Data;

use WC_Data;

abstract class Model extends WC_Data {
    // Base implementation
}
```

Key characteristics of the base model:

- Provides property getter/setter pattern
- Handles data change tracking
- Manages object states (create, read, update)
- Integrates with data stores for persistence
- Supports serialization and caching

### Data Store Interface

Data stores handle the persistence of model data, adhering to the `WC_Object_Data_Store_Interface`:

```php
interface WC_Object_Data_Store_Interface {
    public function create( &$data );
    public function read( &$data );
    public function update( &$data );
    public function delete( &$data, $args = array() );
    public function read_meta( &$data );
    public function update_meta( &$data );
    public function delete_meta( &$data, $meta );
    public function add_meta( &$data, $meta );
}
```

Our implementation pattern includes:

- Table creation and schema management
- CRUD operations for model data
- Query methods for retrieving collections
- Caching mechanisms for performance
- Proper error handling and validation

## Sample Implementations

### Product Model

The `Product_Model` extends the base `Model` class and demonstrates how to implement a custom data model:

```php
namespace WP_Plugin_Starter\Core\Data\Models;

use WP_Plugin_Starter\Core\Data\Model;

class Product_Model extends Model {
    // Implementation
}
```

Key features demonstrated:

- **Custom Properties**: Additional product attributes beyond standard WooCommerce product data
- **Type-Safe Methods**: Proper type declarations for PHP 7.4+
- **Relations**: Integration with WooCommerce product system
- **Validation**: Proper data validation and sanitation
- **Extension Points**: Hooks and filters for customization

### Product Data Store

The `Product_Data_Store` class handles the persistence of `Product_Model` data:

```php
namespace WP_Plugin_Starter\Core\Data\Stores;

use WC_Data_Store_WP;
use WC_Object_Data_Store_Interface;

class Product_Data_Store extends WC_Data_Store_WP implements WC_Object_Data_Store_Interface {
    // Implementation
}
```

Key features demonstrated:

- **Custom Database Tables**: Creating and managing plugin-specific tables
- **Efficient Queries**: Building optimized database queries
- **Caching**: Implementation of WordPress caching mechanisms
- **Error Handling**: Proper exception handling and logging
- **Extension Points**: Hooks and filters for customization

### Settings Model

The `Settings` model demonstrates a different approach to data modeling, focused on key-value storage:

```php
namespace WP_Plugin_Starter\Core\Data\Models;

use WP_Plugin_Starter\Core\Data\Model;

class Settings extends Model {
    // Implementation
}
```

This model showcases:

- **Group-Based Organization**: Organizing settings by functional groups
- **Serialization**: Handling complex data types in storage
- **Default Values**: Managing fallbacks and defaults
- **Validation**: Type-specific validation rules
- **Integration**: Connecting with WordPress options system

## Integration with WordPress & WooCommerce

The data model system integrates seamlessly with WordPress and WooCommerce:

1. **WordPress Hooks**: Models and data stores use WordPress actions and filters
2. **WooCommerce Extensions**: Models can extend WooCommerce data
3. **Database Integration**: Uses wpdb for database operations
4. **Caching**: Leverages WordPress object caching
5. **Internationalization**: All user-facing strings are translatable

## Extension Points

### Hooks & Filters

The data model system provides numerous hooks and filters for extension:

#### Product Model Hooks

```php
// Before saving product model
do_action( 'wp_plugin_starter_before_product_model_save', $product, $product_id );

// After saving product model
do_action( 'wp_plugin_starter_after_product_model_save', $product, $product_id );

// Before/after deleting product model
do_action( 'wp_plugin_starter_before_product_model_delete', $id, $product );
do_action( 'wp_plugin_starter_after_product_model_delete', $id, $result );

// Filter product visibility
apply_filters( 'wp_plugin_starter_product_is_visible', $is_visible, $product );

// Filter product marketplace statuses
apply_filters( 'wp_plugin_starter_product_marketplace_statuses', $statuses, $status, $product );
```

#### Data Store Hooks

```php
// After creating product model
do_action( 'wp_plugin_starter_product_model_created', $product->get_id(), $product, $data );

// After reading product model
do_action( 'wp_plugin_starter_product_model_read', $product, $data );

// After updating product model
do_action( 'wp_plugin_starter_product_model_updated', $product->get_id(), $product, $data );

// Filter product data before database save
apply_filters( 'wp_plugin_starter_product_data_for_db', $data, $product );

// When product model cache is cleared
do_action( 'wp_plugin_starter_product_model_cache_cleared', $product->get_id(), $product );
```

### Subclassing

Both models and data stores are designed to be extended through subclassing:

```php
// Extending a model
class Custom_Product_Model extends Product_Model {
    // Custom implementation
}

// Extending a data store
class Custom_Product_Data_Store extends Product_Data_Store {
    // Custom implementation
}
```

This allows for:

- Adding custom properties and methods
- Overriding default behaviors
- Implementing specialized validation
- Adding business logic
- Creating domain-specific models

## Best Practices

When working with the data model system, follow these best practices:

1. **Use Type Hints**: Always use proper PHP type hints for parameters and return types
2. **Document Everything**: Add PHPDoc comments to all methods and properties
3. **Validate Input**: Always validate and sanitize input data
4. **Use Transactions**: For operations that modify multiple records
5. **Handle Errors**: Implement proper error handling and logging
6. **Leverage Caching**: Use the built-in caching mechanisms
7. **Follow Naming Conventions**: Use consistent naming for models, data stores, and properties
8. **Provide Hooks**: Add hooks for extensibility in your custom implementations
9. **Unit Test**: Write tests for your models and data stores

## Example Use Cases

### Retrieving a Product Model

```php
// By model ID
$product_model = new Product_Model( $model_id );

// By WooCommerce product ID
$data_store = new Product_Data_Store();
$model_id = $data_store->find_by_product_id( $wc_product_id );
if ( $model_id ) {
    $product_model = new Product_Model( $model_id );
}
```

### Creating a New Product Model

```php
$product_model = new Product_Model();
$product_model->set_product_id( $wc_product_id );
$product_model->set_custom_attributes( [
    'color' => 'blue',
    'size' => 'large',
] );
$product_model->set_marketplace_status( 'pending' );
$product_model->save();
```

### Updating a Product Model

```php
$product_model = new Product_Model( $model_id );
$product_model->set_visibility( false );
$product_model->add_custom_attribute( 'material', 'cotton' );
$product_model->save();
```

### Querying Product Models

```php
$data_store = new Product_Data_Store();
$products = $data_store->get_all( [
    'limit' => 10,
    'offset' => 0,
    'orderby' => 'date_created',
    'order' => 'DESC',
    'visibility' => true,
    'status' => 'approved',
] );

foreach ( $products as $product ) {
    // Work with each product model
}
```

### Deleting a Product Model

```php
$product_model = new Product_Model( $model_id );
$product_model->delete();
```

By following the patterns and practices outlined in this documentation, you can create robust, maintainable, and extensible data models for your WordPress plugin.
