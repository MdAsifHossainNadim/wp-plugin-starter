<?php

namespace WPPluginStarter\REST\Controllers\V1;

use WPPluginStarter\REST\Controllers\Abstract_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Product REST Controller
 *
 * Handles product-related REST API endpoints, demonstrating proper REST implementation
 * with standardized responses, validation, and error handling.
 *
 * @since   1.0.0
 * @package WPPluginStarter\REST\Controllers\V1
 */
class Product_Controller extends Abstract_Controller {

	/**
	 * Route base for this controller
	 *
	 * @var string
	 */
	protected $rest_base = 'products';

	/**
	 * Register routes
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the product.', 'wp-plugin-starter' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		/**
		 * Action hook fired after product routes are registered.
		 *
		 * Allows plugins to register additional product-related routes.
		 *
		 * @since 3.0.0
		 *
		 * @param string           $namespace The namespace for the routes.
		 * @param string           $rest_base The base for the routes.
		 * @param Product_Controller $controller The controller instance.
		 */
		do_action( 'WPPluginStarter_product_rest_routes_registered', $this->namespace, $this->rest_base, $this );
	}

	/**
	 * Check if current user can get items
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function get_items_permissions_check( $request ) {
		/**
		 * Filter product items permission check.
		 *
		 * Controls public access to product listings in the REST API.
		 *
		 * @since 3.0.0
		 *
		 * @param bool            $public Whether this endpoint allows public access.
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool|WP_Error True for public access, WP_Error otherwise.
		 */
		$public_access = apply_filters( 'WPPluginStarter_product_items_public_access', true, $request );

		return $public_access;
	}

	/**
	 * Check if current user can get a specific item
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function get_item_permissions_check( $request ) {
		/**
		 * Filter product item permission check.
		 *
		 * Controls public access to individual products in the REST API.
		 *
		 * @since 3.0.0
		 *
		 * @param bool            $public   Whether this endpoint allows public access.
		 * @param WP_REST_Request $request  The request object.
		 * @param int             $product_id The product ID being requested.
		 *
		 * @return bool|WP_Error True for public access, WP_Error otherwise.
		 */
		$public_access = apply_filters(
			'WPPluginStarter_product_item_public_access',
			true,
			$request,
			(int) $request['id']
		);

		return $public_access;
	}

	/**
	 * Check if current user can create items
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function create_item_permissions_check( $request ) {
		return $this->check_item_permissions( $request, 'edit_products' );
	}

	/**
	 * Check if current user can update a specific item
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function update_item_permissions_check( $request ) {
		return $this->check_item_permissions( $request, 'edit_products' );
	}

	/**
	 * Check if current user can delete a specific item
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->check_item_permissions( $request, 'delete_products' );
	}

	/**
	 * Get a collection of products
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_items( $request ) {
		try {
			/**
			 * Action fired before retrieving products.
			 *
			 * @since 3.0.0
			 *
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_get_products', $request );

			// Example implementation that would typically use a data store
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => $request['per_page'],
				'paged'          => $request['page'],
				's'              => $request['search'] ?? '',
				'orderby'        => $request['orderby'],
				'order'          => $request['order'],
			);

			/**
			 * Filter the query arguments for retrieving products.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $args    The query arguments.
			 * @param WP_REST_Request $request The request object.
			 *
			 * @return array Modified query arguments.
			 */
			$args = apply_filters( 'WPPluginStarter_product_query_args', $args, $request );

			$products_query = new \WP_Query( $args );
			$products       = array();

			foreach ( $products_query->posts as $product ) {
				$data       = $this->prepare_item_for_response( $product, $request );
				$products[] = $this->prepare_response_for_collection( $data );
			}

			/**
			 * Filter products data before creating the response.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $products Array of prepared products data.
			 * @param WP_REST_Request $request  The request object.
			 *
			 * @return array Modified products data.
			 */
			$products = apply_filters( 'WPPluginStarter_rest_products_data', $products, $request );

			$response = $this->response()->success(
				$products,
				__( 'Products retrieved successfully.', 'wp-plugin-starter' )
			);

			// Add pagination headers
			$total_posts = $products_query->found_posts;
			$max_pages   = ceil( $total_posts / $request['per_page'] );

			$response = $this->add_pagination_headers( $response, $total_posts, $request['per_page'], $request['page'] );

			/**
			 * Action fired after retrieving products.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $products Array of prepared products data.
			 * @param WP_REST_Request $request  The request object.
			 * @param WP_REST_Response $response The response object.
			 */
			do_action( 'WPPluginStarter_after_get_products', $products, $request, $response );

			return $response;
		} catch ( \Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to retrieve products.', 'wp-plugin-starter' ),
				'WPPluginStarter_products_retrieval_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Get a single product
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_item( $request ) {
		try {
			/**
			 * Action fired before retrieving a product.
			 *
			 * @since 3.0.0
			 *
			 * @param int             $product_id The product ID.
			 * @param WP_REST_Request $request    The request object.
			 */
			do_action( 'WPPluginStarter_before_get_product', (int) $request['id'], $request );

			$product_id = $request['id'];
			$product    = wc_get_product( $product_id );

			if ( ! $product || $product->get_id() !== (int) $product_id ) {
				return $this->response()->error(
					__( 'Product not found.', 'wp-plugin-starter' ),
					'WPPluginStarter_product_not_found',
					404
				);
			}

			$data = $this->prepare_item_for_response( $product, $request );

			/**
			 * Filter product data before creating the response.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $data    Prepared product data.
			 * @param \WC_Product     $product The product object.
			 * @param WP_REST_Request $request The request object.
			 *
			 * @return array Modified product data.
			 */
			$data = apply_filters( 'WPPluginStarter_rest_product_data', $data, $product, $request );

			$response = $this->response()->success(
				$data,
				__( 'Product retrieved successfully.', 'wp-plugin-starter' )
			);

			/**
			 * Action fired after retrieving a product.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product  The product object.
			 * @param array           $data     Prepared product data.
			 * @param WP_REST_Request $request  The request object.
			 * @param WP_REST_Response $response The response object.
			 */
			do_action( 'WPPluginStarter_after_get_product', $product, $data, $request, $response );

			return $response;
		} catch ( \Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to retrieve product.', 'wp-plugin-starter' ),
				'WPPluginStarter_product_retrieval_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Create a single product
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		try {
			/**
			 * Action fired before creating a product.
			 *
			 * @since 3.0.0
			 *
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_create_product', $request );

			// Validate request data
			$validation = $this->validate_request(
				$request,
				array(
					'name'        => 'required|string|max:255',
					'price'       => 'required|numeric',
					'description' => 'string',
				)
			);

			if ( is_wp_error( $validation ) ) {
				return $this->response()->error(
					__( 'Validation failed.', 'wp-plugin-starter' ),
					'WPPluginStarter_validation_failed',
					400,
					array( 'validation_errors' => $validation->get_error_messages() )
				);
			}

			/**
			 * Filter product data before creation.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $product_data Product data from request.
			 * @param WP_REST_Request $request      The request object.
			 *
			 * @return array Modified product data.
			 */
			$product_data = apply_filters( 'WPPluginStarter_pre_create_product_data', $request->get_params(), $request );

			// Example implementation
			$product = new \WC_Product();
			$product->set_name( $product_data['name'] );
			$product->set_regular_price( $product_data['price'] );

			if ( isset( $product_data['description'] ) ) {
				$product->set_description( $product_data['description'] );
			}

			/**
			 * Action fired before saving a new product.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product Product object before saving.
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_save_new_product', $product, $request );

			$product_id = $product->save();

			if ( ! $product_id ) {
				return $this->response()->error(
					__( 'Could not create product.', 'wp-plugin-starter' ),
					'WPPluginStarter_product_creation_failed',
					400
				);
			}

			$product = wc_get_product( $product_id );
			$data    = $this->prepare_item_for_response( $product, $request );

			/**
			 * Action fired after creating a product.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product  The product object.
			 * @param WP_REST_Request $request  The request object.
			 * @param array           $data     Prepared product data.
			 */
			do_action( 'WPPluginStarter_after_create_product', $product, $request, $data );

			return $this->response()->success(
				$data,
				__( 'Product created successfully.', 'wp-plugin-starter' ),
				201
			);
		} catch ( \Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to create product.', 'wp-plugin-starter' ),
				'WPPluginStarter_product_creation_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Update a single product
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_item( $request ) {
		try {
			/**
			 * Action fired before updating a product.
			 *
			 * @since 3.0.0
			 *
			 * @param int             $product_id The product ID.
			 * @param WP_REST_Request $request    The request object.
			 */
			do_action( 'WPPluginStarter_before_update_product', (int) $request['id'], $request );

			$product_id = $request['id'];
			$product    = wc_get_product( $product_id );

			if ( ! $product || $product->get_id() !== (int) $product_id ) {
				return $this->response()->error(
					__( 'Product not found.', 'wp-plugin-starter' ),
					'WPPluginStarter_product_not_found',
					404
				);
			}

			/**
			 * Filter product data before update.
			 *
			 * @since 3.0.0
			 *
			 * @param array           $product_data Product data from request.
			 * @param \WC_Product     $product      Current product object.
			 * @param WP_REST_Request $request      The request object.
			 *
			 * @return array Modified product data.
			 */
			$product_data = apply_filters( 'WPPluginStarter_pre_update_product_data', $request->get_params(), $product, $request );

			// Example implementation
			if ( isset( $product_data['name'] ) ) {
				$product->set_name( $product_data['name'] );
			}

			if ( isset( $product_data['price'] ) ) {
				$product->set_regular_price( $product_data['price'] );
			}

			if ( isset( $product_data['description'] ) ) {
				$product->set_description( $product_data['description'] );
			}

			/**
			 * Action fired before saving an updated product.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product  Product object before saving changes.
			 * @param WP_REST_Request $request  The request object.
			 * @param array           $product_data The data being used for update.
			 */
			do_action( 'WPPluginStarter_before_save_updated_product', $product, $request, $product_data );

			$product->save();

			$data = $this->prepare_item_for_response( $product, $request );

			/**
			 * Action fired after updating a product.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product  The updated product object.
			 * @param WP_REST_Request $request  The request object.
			 * @param array           $data     Prepared product data.
			 */
			do_action( 'WPPluginStarter_after_update_product', $product, $request, $data );

			return $this->response()->success(
				$data,
				__( 'Product updated successfully.', 'wp-plugin-starter' )
			);
		} catch ( \Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to update product.', 'wp-plugin-starter' ),
				'WPPluginStarter_product_update_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Delete a single product
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_item( $request ) {
		try {
			/**
			 * Action fired before deleting a product.
			 *
			 * @since 3.0.0
			 *
			 * @param int             $product_id The product ID.
			 * @param WP_REST_Request $request    The request object.
			 */
			do_action( 'WPPluginStarter_before_delete_product', (int) $request['id'], $request );

			$product_id = $request['id'];
			$product    = wc_get_product( $product_id );

			if ( ! $product || $product->get_id() !== (int) $product_id ) {
				return $this->response()->error(
					__( 'Product not found.', 'wp-plugin-starter' ),
					'WPPluginStarter_product_not_found',
					404
				);
			}

			// Prepare response data before deletion
			$previous = $this->prepare_item_for_response( $product, $request );

			/**
			 * Filter force delete setting for product deletion.
			 *
			 * @since 3.0.0
			 *
			 * @param bool            $force_delete Whether to permanently delete the product.
			 * @param \WC_Product     $product      The product object.
			 * @param WP_REST_Request $request      The request object.
			 *
			 * @return bool Whether to force delete.
			 */
			$force_delete = apply_filters( 'WPPluginStarter_product_force_delete', true, $product, $request );

			/**
			 * Action fired immediately before product deletion.
			 *
			 * Last chance to access the product before it's deleted.
			 *
			 * @since 3.0.0
			 *
			 * @param \WC_Product     $product      The product object.
			 * @param bool            $force_delete Whether the product will be permanently deleted.
			 * @param WP_REST_Request $request      The request object.
			 */
			do_action( 'WPPluginStarter_product_pre_delete', $product, $force_delete, $request );

			// Force delete product
			$result = $product->delete( $force_delete );

			if ( ! $result ) {
				return $this->response()->error(
					__( 'Could not delete product.', 'wp-plugin-starter' ),
					'WPPluginStarter_product_deletion_failed',
					400
				);
			}

			/**
			 * Action fired after successful product deletion.
			 *
			 * @since 3.0.0
			 *
			 * @param int             $product_id   The ID of the deleted product.
			 * @param array           $previous     The product data before deletion.
			 * @param bool            $force_delete Whether the product was permanently deleted.
			 * @param WP_REST_Request $request      The request object.
			 */
			do_action( 'WPPluginStarter_after_delete_product', (int) $request['id'], $previous, $force_delete, $request );

			return $this->response()->success(
				array(
					'deleted'  => true,
					'previous' => $previous,
				),
				__( 'Product deleted successfully.', 'wp-plugin-starter' )
			);
		} catch ( \Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to delete product.', 'wp-plugin-starter' ),
				'WPPluginStarter_product_deletion_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Prepare a single product for API response
	 *
	 * @param \WC_Product|object $product Product object
	 * @param WP_REST_Request    $request Request object
	 *
	 * @return array Prepared data
	 */
	public function prepare_item_for_response( $product, $request ) {
		// For WC_Product objects
		if ( $product instanceof \WC_Product ) {
			$data = array(
				'id'            => $product->get_id(),
				'name'          => $product->get_name(),
				'slug'          => $product->get_slug(),
				'price'         => $product->get_regular_price(),
				'sale_price'    => $product->get_sale_price(),
				'description'   => $product->get_description(),
				'status'        => $product->get_status(),
				'images'        => $this->get_product_images( $product ),
				'date_created'  => wc_rest_prepare_date_response( $product->get_date_created() ),
				'date_modified' => wc_rest_prepare_date_response( $product->get_date_modified() ),
			);

			/**
			 * Filter product data for a WC_Product object.
			 *
			 * @since 3.0.0
			 *
			 * @param array        $data    Prepared product data.
			 * @param \WC_Product  $product The product object.
			 * @param WP_REST_Request $request The request object.
			 *
			 * @return array Modified product data.
			 */
			$data = apply_filters( 'WPPluginStarter_prepare_wc_product_response', $data, $product, $request );
		}
		// For WP_Post objects (from WP_Query)
		else {
			$product_obj = wc_get_product( $product->ID );
			$data        = array(
				'id'            => $product->ID,
				'name'          => $product->post_title,
				'slug'          => $product->post_name,
				'price'         => $product_obj ? $product_obj->get_regular_price() : '',
				'description'   => $product->post_content,
				'status'        => $product->post_status,
				'date_created'  => $product->post_date_gmt,
				'date_modified' => $product->post_modified_gmt,
			);

			/**
			 * Filter product data for a WP_Post object.
			 *
			 * @since 3.0.0
			 *
			 * @param array        $data       Prepared product data.
			 * @param \WP_Post     $product    The post object.
			 * @param \WC_Product  $product_obj The WooCommerce product object.
			 * @param WP_REST_Request $request    The request object.
			 *
			 * @return array Modified product data.
			 */
			$data = apply_filters( 'WPPluginStarter_prepare_post_product_response', $data, $product, $product_obj, $request );
		}

		// Add links for HATEOAS
		$data['_links'] = $this->prepare_links( $data['id'] );

		/**
		 * Filter the final prepared product data.
		 *
		 * @since 3.0.0
		 *
		 * @param array           $data    Prepared product data.
		 * @param mixed           $product The original product object.
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return array Modified product data.
		 */
		return apply_filters( 'WPPluginStarter_prepare_product_response', $data, $product, $request );
	}

	/**
	 * Get product images
	 *
	 * @param \WC_Product $product Product object
	 *
	 * @return array
	 */
	protected function get_product_images( $product ) {
		$images         = array();
		$attachment_ids = array();

		// Add featured image
		if ( $product->get_image_id() ) {
			$attachment_ids[] = $product->get_image_id();
		}

		// Add gallery images
		$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		/**
		 * Filter product attachment IDs for REST response.
		 *
		 * @since 3.0.0
		 *
		 * @param array       $attachment_ids Array of attachment IDs.
		 * @param \WC_Product $product       The product object.
		 *
		 * @return array Modified attachment IDs array.
		 */
		$attachment_ids = apply_filters( 'WPPluginStarter_product_attachment_ids', $attachment_ids, $product );

		// Add images data
		foreach ( $attachment_ids as $attachment_id ) {
			$attachment_post = get_post( $attachment_id );
			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'id'            => (int) $attachment_id,
				'date_created'  => wc_rest_prepare_date_response( $attachment_post->post_date_gmt ),
				'date_modified' => wc_rest_prepare_date_response( $attachment_post->post_modified_gmt ),
				'src'           => current( $attachment ),
				'name'          => get_the_title( $attachment_id ),
				'alt'           => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);
		}

		/**
		 * Filter prepared product images.
		 *
		 * @since 3.0.0
		 *
		 * @param array       $images  Prepared images data.
		 * @param \WC_Product $product The product object.
		 *
		 * @return array Modified images data.
		 */
		return apply_filters( 'WPPluginStarter_product_images', $images, $product );
	}

	/**
	 * Get the Product schema
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'product',
			'type'       => 'object',
			'properties' => array(
				'id'            => array(
					'description' => __( 'Unique identifier for the product.', 'wp-plugin-starter' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'          => array(
					'description' => __( 'Product name.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
				),
				'slug'          => array(
					'description' => __( 'Product slug.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'price'         => array(
					'description' => __( 'Product price.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
				),
				'sale_price'    => array(
					'description' => __( 'Product sale price.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'description'   => array(
					'description' => __( 'Product description.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'status'        => array(
					'description' => __( 'Product status.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'enum'        => array( 'draft', 'pending', 'private', 'publish' ),
					'context'     => array( 'view', 'edit' ),
					'default'     => 'publish',
				),
				'images'        => array(
					'description' => __( 'Product images.', 'wp-plugin-starter' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Image ID.', 'wp-plugin-starter' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'src'  => array(
								'description' => __( 'Image URL.', 'wp-plugin-starter' ),
								'type'        => 'string',
								'format'      => 'uri',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Image name.', 'wp-plugin-starter' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'alt'  => array(
								'description' => __( 'Image alternative text.', 'wp-plugin-starter' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'date_created'  => array(
					'description' => __( 'Creation date.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified' => array(
					'description' => __( 'Last modification date.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		/**
		 * Filter the product schema for the REST API.
		 *
		 * @since 3.0.0
		 *
		 * @param array $schema The product schema.
		 *
		 * @return array Modified schema.
		 */
		return apply_filters( 'WPPluginStarter_product_schema', $schema );
	}
}
