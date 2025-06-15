# API Reference

> **Documentation Version**: This documentation is based on Dokan Kits version 3.0.0. Features and functionality may vary in other versions.

# Dokan Kits API Reference

This document provides comprehensive documentation for the REST API endpoints available in the Dokan Kits plugin.

## Base URL

All API endpoints are prefixed with:

```
/wp-json/wp-plugin-starter/v1/
```

## Authentication

API endpoints require authentication through WordPress REST API authentication methods. Most endpoints require admin or shop manager capabilities, while some may be accessible to vendors based on specific permissions.

## Response Format

All API responses follow a standardized format:

### Success Response

```json
{
	"success": true,
	"data": {
		// Response data varies by endpoint
	},
	"message": "Operation completed successfully"
}
```

### Error Response

```json
{
	"code": "error_code",
	"message": "Error message description",
	"data": {
		"status": 400
		// Additional error data
	}
}
```

## Available Endpoints

### Settings Endpoints

#### Get All Settings

Retrieves all plugin settings with their current values.

- **Endpoint:** `GET /settings`
- **Requires Authentication:** Yes (Admin)
- **Response Example:**

```json
{
	"success": true,
	"data": {
		"structure": {
			"vendor": {
				"title": "Vendor",
				"icon": "dashicons-businessman",
				"priority": 10,
				"sections": {
					"registration": {
						"id": "registration",
						"title": "Registration",
						"description": "Control vendor registration options",
						"priority": 10,
						"fields": [
							{
								"id": "remove_vendor_checkbox",
								"type": "toggle",
								"label": "Remove Vendor Registration",
								"description": "Remove \"I am a vendor\" option from the WooCommerce my account page.",
								"default": false
							},
							{
								"id": "set_default_seller_role_checkbox",
								"type": "toggle",
								"label": "Enable \"I am a Vendor\" by default",
								"description": "To enable the \"I am a Vendor\" option by default on the My Account page.",
								"default": false
							}
						]
					}
				}
			},
			"product": {
				"title": "Product",
				"icon": "dashicons-products",
				"priority": 20,
				"sections": {
					// Product sections and fields
				}
			}
			// Other setting groups
		},
		"values": {
			"remove_vendor_checkbox": false,
			"set_default_seller_role_checkbox": true
			// Other setting values
		}
	}
}
```

#### Update Settings

Updates plugin settings with new values.

- **Endpoint:** `POST /settings`
- **Requires Authentication:** Yes (Admin)
- **Request Body Example:**

```json
{
	"remove_vendor_checkbox": true,
	"set_default_seller_role_checkbox": false,
	"remove_variable_product_checkbox": true
}
```

- **Response Example:**

```json
{
	"success": true,
	"message": "SettingsModel updated successfully.",
	"values": {
		"remove_vendor_checkbox": true,
		"set_default_seller_role_checkbox": false,
		"remove_variable_product_checkbox": true
		// All updated features
	}
}
```

### Features Endpoints

#### Get All Features

Retrieves all available features and their status.

- **Endpoint:** `GET /features`
- **Requires Authentication:** Yes (Admin)
- **Response Example:**

```json
{
	"success": true,
	"data": {
		"vendor_registration": {
			"id": "vendor_registration",
			"title": "Vendor Registration",
			"description": "Controls vendor registration process",
			"enabled": true
		},
		"product_image_restrictions": {
			"id": "product_image_restrictions",
			"title": "Product Image Restrictions",
			"description": "Controls product image dimensions and size",
			"enabled": true
		}
		// Other features
	}
}
```

#### Update Feature Status

Enables or disables a specific feature.

- **Endpoint:** `POST /features/{feature_id}`
- **Requires Authentication:** Yes (Admin)
- **Request Body Example:**

```json
{
	"enabled": false
}
```

- **Response Example:**

```json
{
	"success": true,
	"message": "Feature status updated successfully.",
	"data": {
		"id": "product_image_restrictions",
		"enabled": false
	}
}
```

### Vendor Endpoints

#### Get Vendor Settings

Retrieves settings specific to a vendor.

- **Endpoint:** `GET /vendors/{vendor_id}/settings`
- **Requires Authentication:** Yes (Admin or Vendor owner)
- **Response Example:**

```json
{
	"success": true,
	"data": {
		"account_settings": {
			// Vendor-specific features
		},
		"capabilities": {
			// Vendor capabilities
		}
	}
}
```

#### Update Vendor Settings

Updates settings for a specific vendor.

- **Endpoint:** `POST /vendors/{vendor_id}/settings`
- **Requires Authentication:** Yes (Admin or Vendor owner)
- **Request Body Example:**

```json
{
	"account_settings": {
		// Updated features
	}
}
```

- **Response Example:**

```json
{
	"success": true,
	"message": "Vendor features updated successfully.",
	"data": {
		// Updated vendor features
	}
}
```

### Product Endpoints

#### Get Product Restrictions

Retrieves product restrictions configuration.

- **Endpoint:** `GET /products/restrictions`
- **Requires Authentication:** Yes (Admin)
- **Response Example:**

```json
{
	"success": true,
	"data": {
		"image_restrictions": {
			"max_width": 1920,
			"max_height": 1080,
			"max_size": 2,
			"enabled": true
		},
		"field_restrictions": {
			// Field restrictions
		},
		"type_restrictions": {
			// Product type restrictions
		}
	}
}
```

#### Update Product Restrictions

Updates product restrictions configuration.

- **Endpoint:** `POST /products/restrictions`
- **Requires Authentication:** Yes (Admin)
- **Request Body Example:**

```json
{
	"image_restrictions": {
		"max_width": 2400,
		"max_height": 1600,
		"max_size": 3,
		"enabled": true
	}
}
```

- **Response Example:**

```json
{
	"success": true,
	"message": "Product restrictions updated successfully.",
	"data": {
		// Updated restrictions
	}
}
```

#### Validate Product Image

Validates a product image against the configured restrictions.

- **Endpoint:** `POST /products/validate-image`
- **Requires Authentication:** Yes (Admin or Vendor)
- **Request Body Example:**

```json
{
	"image_id": 123
}
```

- **Response Example:**

```json
{
	"success": true,
	"data": {
		"valid": true,
		"dimensions": {
			"width": 1200,
			"height": 800
		},
		"size": 1.2,
		"format": "jpeg"
	}
}
```

Or, for invalid images:

```json
{
	"success": false,
	"code": "invalid_image",
	"message": "Image exceeds maximum dimensions of 1920x1080 pixels.",
	"data": {
		"dimensions": {
			"width": 2500,
			"height": 1800
		},
		"max_dimensions": {
			"width": 1920,
			"height": 1080
		}
	}
}
```

### Shipping Endpoints

#### Get Shipping Settings

Retrieves shipping settings configuration.

- **Endpoint:** `GET /shipping/settings`
- **Requires Authentication:** Yes (Admin)
- **Response Example:**

```json
{
	"success": true,
	"data": {
		"lite_shipping": {
			"enabled": true
			// Lite shipping features
		},
		"pro_shipping": {
			"enabled": false
			// Pro shipping features
		}
	}
}
```

#### Update Shipping Settings

Updates shipping settings configuration.

- **Endpoint:** `POST /shipping/settings`
- **Requires Authentication:** Yes (Admin)
- **Request Body Example:**

```json
{
	"lite_shipping": {
		"enabled": false
	},
	"pro_shipping": {
		"enabled": true
		// Pro shipping features
	}
}
```

- **Response Example:**

```json
{
	"success": true,
	"message": "Shipping features updated successfully.",
	"data": {
		// Updated shipping features
	}
}
```

## Error Codes

| Code                | Description                                          |
| ------------------- | ---------------------------------------------------- |
| `rest_no_route`     | The requested endpoint does not exist                |
| `rest_forbidden`    | User does not have permission to access the endpoint |
| `invalid_parameter` | One or more request parameters are invalid           |
| `invalid_image`     | The uploaded image does not meet requirements        |
| `feature_not_found` | The requested feature does not exist                 |
| `vendor_not_found`  | The requested vendor does not exist                  |
| `update_failed`     | Failed to update the requested resource              |

## Rate Limiting

API requests are subject to rate limiting to prevent abuse. By default, authenticated users can make up to 25 requests per minute. Exceeding this limit will result in a 429 Too Many Requests response.

## Versioning

The API version is specified in the URL path (/v1/). Future releases may introduce new API versions (/v2/, etc.) to maintain backward compatibility while adding new features.

## Extending the API

The Dokan Kits API can be extended using the WordPress REST API hooks. Custom endpoints can be registered, and existing endpoints can be modified using filters.

Example of registering a custom endpoint:

```php
add_action('rest_api_init', function () {
    register_rest_route('wp-plugin-starter/v1', '/custom-endpoint', [
        'methods' => 'GET',
        'callback' => 'my_custom_endpoint_callback',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);
});

function my_custom_endpoint_callback() {
    // Custom endpoint implementation
    return rest_ensure_response([
        'success' => true,
        'data' => [
            // Custom data
        ]
    ]);
}
```

Example of modifying an existing endpoint response:

```php
add_filter('dokan_kits_rest_response', function ($response, $endpoint, $request) {
    if ($endpoint === 'features') {
        // Modify features response
        $data = $response->get_data();
        $data['custom_field'] = 'custom_value';
        $response->set_data($data);
    }

    return $response;
}, 10, 3);
```
