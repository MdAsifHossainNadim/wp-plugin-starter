# REST API Documentation

This document provides a detailed overview of the REST API system in WP Plugin Starter, including architecture, implementation patterns, available endpoints, and extension points.

## Table of Contents

- [Introduction](#introduction)
- [Architecture](#architecture)
- [Core Components](#core-components)
  - [Abstract Controller](#abstract-controller)
  - [Response Handling](#response-handling)
  - [Authentication & Authorization](#authentication--authorization)
  - [Validation](#validation)
- [Sample Endpoints](#sample-endpoints)
  - [Products API](#products-api)
  - [Settings API](#settings-api)
- [Integration with WordPress REST API](#integration-with-wordpress-rest-api)
- [Extension Points](#extension-points)
  - [Hooks & Filters](#hooks--filters)
  - [Subclassing Controllers](#subclassing-controllers)
- [Best Practices](#best-practices)
- [Example Use Cases](#example-use-cases)

## Introduction

The WP Plugin Starter REST API system provides a standardized approach to building RESTful APIs in your WordPress plugin. Built on top of the WordPress REST API infrastructure, it adds:

1. Standardized response formatting
2. Robust error handling
3. Authentication and validation middleware
4. Comprehensive hooks for extensibility 
5. Type safety with proper documentation

## Architecture

The REST API system follows a layered architecture:

```
┌───────────────────┐
│    Controllers    │  Product_Controller, Settings_Controller, etc.
└────────┬──────────┘
         │ Uses
┌────────▼──────────┐
│     Middleware    │  Authentication, Validation
└────────┬──────────┘
         │ Uses
┌────────▼──────────┐
│   API Response    │  Standardized response formatting
└────────┬──────────┘
         │ Extends
┌────────▼──────────┐
│  WordPress REST   │  WP_REST_Controller, WP_REST_Response
└───────────────────┘
```

This layered approach provides several benefits:

- **Separation of concerns**: Each layer has a distinct responsibility
- **Reusability**: Common functionality is abstracted into base classes
- **Consistency**: All endpoints follow the same patterns and conventions
- **Extensibility**: Each layer can be extended independently

## Core Components

### Abstract Controller

The foundation of all REST controllers is the `Abstract_Controller` class, which extends WordPress's `WP_REST_Controller`:

```php
namespace WP_Plugin_Starter\REST\Controllers;

use WP_REST_Controller;

abstract class Abstract_Controller extends WP_REST_Controller {
    // Base implementation
}
```

Key features of the Abstract Controller:

- Standardized response formatting method
- Authentication and permission checking
- Request validation
- Error handling
- Pagination utilities
- HATEOAS link generation

### Response Handling

All API responses follow a consistent format, using the `Api_Response` class:

```php
// Success response format
{
    "success": true,
    "data": {
        // Response data here
    },
    "message": "Optional success message"
}

// Error response format
{
    "success": false,
    "error": {
        "code": "error_code",
        "message": "Error message",
        "data": {
            // Optional additional error details
        }
    }
}
```

The response system provides methods for both success and error cases:

```php
// In controller methods:
return $this->response()->success($data, $message, $status_code, $headers);
return $this->response()->error($message, $code, $status_code, $data);
```

### Authentication & Authorization

The API includes middleware for authentication and authorization:

- **Authentication Middleware**: Handles user authentication
- **Permission Checking**: Role and capability-based authorization
- **Custom Capability Mapping**: Extensible permission system

Controllers implement permission checks for different operations:

```php
public function get_items_permissions_check($request) { /* ... */ }
public function create_item_permissions_check($request) { /* ... */ }
public function update_item_permissions_check($request) { /* ... */ }
public function delete_item_permissions_check($request) { /* ... */ }
```

### Validation

Request validation is handled through the Validation middleware:

```php
protected function validate_request($request, $rules = [], $messages = []) {
    return (new Validation())->validate($request, $rules, $messages);
}
```

Validation rules can be defined for each endpoint and parameter:

```php
$validation = $this->validate_request($request, [
    'name' => 'required|string|max:255',
    'price' => 'required|numeric',
    'description' => 'string',
]);

if (is_wp_error($validation)) {
    // Handle validation error
}
```

## Sample Endpoints

### Products API

**Base Path**: `/wp-json/wp-plugin-starter/v1/products`

#### Endpoints

| Method | Endpoint         | Description          | Permission          |
|--------|------------------|----------------------|---------------------|
| GET    | /products        | List products        | Public              |
| POST   | /products        | Create product       | edit_products       |
| GET    | /products/{id}   | Get single product   | Public              |
| PUT    | /products/{id}   | Update product       | edit_products       |
| DELETE | /products/{id}   | Delete product       | delete_products     |

#### Sample Request and Response

**Request: GET /products**

```
GET /wp-json/wp-plugin-starter/v1/products?page=1&per_page=10
```

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sample Product",
      "slug": "sample-product",
      "price": "19.99",
      "sale_price": "",
      "description": "This is a sample product description.",
      "status": "publish",
      "images": [...],
      "date_created": "2023-05-15T10:00:00",
      "date_modified": "2023-05-16T15:30:00",
      "_links": {
        "self": {
          "href": "https://example.com/wp-json/wp-plugin-starter/v1/products/1"
        },
        "collection": {
          "href": "https://example.com/wp-json/wp-plugin-starter/v1/products"
        }
      }
    },
    // More products...
  ],
  "message": "Products retrieved successfully."
}
```

### Settings API

**Base Path**: `/wp-json/wp-plugin-starter/v1/settings`

#### Endpoints

| Method | Endpoint           | Description            | Permission       |
|--------|-------------------|------------------------|------------------|
| GET    | /settings         | Get all settings       | manage_options   |
| POST   | /settings         | Update multiple settings| manage_options   |
| GET    | /settings/{group} | Get settings group     | manage_options   |
| PUT    | /settings/{group} | Update settings group  | manage_options   |

#### Sample Request and Response

**Request: GET /settings/product**

```
GET /wp-json/wp-plugin-starter/v1/settings/product
```

**Response:**

```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "product",
    "value": {
      "gallery_limit": 10,
      "enable_reviews": true,
      "custom_fields_enabled": true
    },
    "last_update": "2023-05-20T14:25:30"
  },
  "message": "SettingsModel for group \"product\" retrieved successfully."
}
```

## Integration with WordPress REST API

The REST API system integrates seamlessly with the WordPress REST API:

1. **Standard Registration**: Uses `register_rest_route()` for endpoint registration
2. **Authentication Integration**: Works with WordPress authentication methods
3. **Parameter Validation**: Uses WordPress argument validation
4. **Schema Support**: Implements schema definitions for all endpoints
5. **Discovery**: Supports API discovery through OPTIONS requests

## Extension Points

### Hooks & Filters

The REST API system provides numerous hooks and filters for extension:

#### Controller Initialization

```php
// When a controller is instantiated
do_action('wp_plugin_starter_rest_controller_init', $controller);

// When routes are registered
do_action('wp_plugin_starter_product_rest_routes_registered', $namespace, $rest_base, $controller);
do_action('wp_plugin_starter_settings_rest_routes_registered', $namespace, $rest_base, $controller);
```

#### Permission Management

```php
// Filter permissions checking
apply_filters('wp_plugin_starter_rest_check_permissions', $result, $request, $rest_base);
apply_filters('wp_plugin_starter_rest_item_capability', $capability, $request, $rest_base, $item_id);

// When permission check fails
do_action('wp_plugin_starter_rest_item_permission_failed', $error, $capability, $request, $rest_base);
```

#### Request Lifecycle Hooks

```php
// Before retrieving products
do_action('wp_plugin_starter_before_get_products', $request);

// After retrieving products
do_action('wp_plugin_starter_after_get_products', $products, $request, $response);

// Before creating a product
do_action('wp_plugin_starter_before_create_product', $request);

// After creating a product
do_action('wp_plugin_starter_after_create_product', $product, $request, $data);

// Similar hooks for update and delete operations
```

#### Response Customization

```php
// Filter product data before response
apply_filters('wp_plugin_starter_rest_product_data', $data, $product, $request);

// Filter settings data before response
apply_filters('wp_plugin_starter_settings_response_data', $data, $settings, $request);

// Add pagination to response
apply_filters('wp_plugin_starter_rest_paginated_response', $response, $total, $per_page, $current, $max_pages, $rest_base);
```

### Subclassing Controllers

Both Abstract Controller and specific controllers are designed to be extended through subclassing:

```php
// Extending the abstract controller
class Custom_Controller extends Abstract_Controller {
    // Custom implementation
}

// Extending a specific controller
class Custom_Product_Controller extends Product_Controller {
    // Custom implementation
}
```

This allows for:

- Adding custom endpoints
- Modifying default behavior
- Implementing specialized validation
- Adding business logic
- Creating domain-specific controllers

## Best Practices

When working with the REST API system, follow these best practices:

1. **Use Standardized Responses**: Always use the response handling methods
2. **Validate Input**: Always validate and sanitize input data
3. **Document Endpoints**: Use schema definitions to document your endpoints
4. **Use Proper Status Codes**: Follow HTTP status code standards
5. **Implement HATEOAS**: Include links in responses for discoverability
6. **Version Your API**: Use versioning in your namespace
7. **Handle Errors Gracefully**: Provide clear error messages
8. **Follow WordPress Coding Standards**: Adhere to WordPress coding guidelines
9. **Add Hooks for Extensibility**: Include action and filter hooks
10. **Test Your API**: Write unit and integration tests for your endpoints

## Example Use Cases

### Making API Requests

```javascript
// Get all products
fetch('/wp-json/wp-plugin-starter/v1/products')
  .then(response => response.json())
  .then(data => console.log(data));

// Create a new product
fetch('/wp-json/wp-plugin-starter/v1/products', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({
    name: 'New Product',
    price: 29.99,
    description: 'This is a new product'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

### Extending with Custom Endpoints

```php
class My_Custom_Controller extends Abstract_Controller {
    protected $rest_base = 'custom';
    
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                ]
            ]
        );
    }
    
    public function get_items_permissions_check($request) {
        return true; // Public endpoint
    }
    
    public function get_items($request) {
        $data = ['custom' => 'data'];
        return $this->response()->success($data, 'Custom data retrieved successfully');
    }
}

// Register the controller
add_action('rest_api_init', function() {
    $controller = new My_Custom_Controller();
    $controller->register_routes();
});
```

By following the patterns and practices outlined in this documentation, you can create robust, maintainable, and extensible REST APIs for your WordPress plugin.
