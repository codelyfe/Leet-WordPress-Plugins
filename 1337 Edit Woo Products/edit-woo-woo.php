<?php
/*
Plugin Name: 1337 WooCommerce Product Price and Image Editor
Plugin URI: https://codelyfe.github.io
Description: A WooCommerce plugin that allows editing of product prices on an options screen in the admin panel.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
// I Provide Free Tech Support: https://support-desk.bss.design/index.html

function my_woo_plugin_init() {
	// Your plugin code here
}
add_action( 'plugins_loaded', 'my_woo_plugin_init' );

function my_woo_plugin_add_options() {
	add_options_page(
		'1337 WooCommerce Product Price and Image Editor',
		'1337 WooCommerce Product Price and Image Editor',
		'manage_options',
		'my-woo-plugin',
		'my_woo_plugin_options_page'
	);
}
add_action( 'admin_menu', 'my_woo_plugin_add_options' );

function my_woo_plugin_options_page() {
	$args = array(
		'limit' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
		'return' => 'ids',
	);
	$product_query = new WC_Product_Query( $args );
	$product_ids = $product_query->get_products();
	?>
	<div class="wrap">
		<h1><u>1337 WooCommerce Product Price and Image Editor</u></h1>
        <br/>
		<form method="post" action="options.php">
			<?php settings_fields( 'my-woo-plugin-settings' ); ?>
			<?php foreach ( $product_ids as $product_id ) : ?>
				<?php $product = wc_get_product( $product_id ); ?>
				<div>
					<label>Name:</label><br/>
					<input type="text" style="width:100%;" name="my_woo_plugin_products[<?php echo $product_id; ?>][name]" value="<?php echo $product->get_name(); ?>">
				</div>
				<div>
					<label>Price:</label><br/>
					<input type="text" style="width:100%;" name="my_woo_plugin_products[<?php echo $product_id; ?>][price]" value="<?php echo $product->get_regular_price(); ?>">
				</div>
				<div>
					<label>Image URL:</label><br/>
					<input type="text" style="width:100%;" name="my_woo_plugin_products[<?php echo $product_id; ?>][image]" value="<?php echo $product->get_image_id() ? wp_get_attachment_url( $product->get_image_id() ) : ''; ?>">
				</div>
                <br/>
                <hr style="border: 0;border-top: 8px solid #000000;border-bottom: 1px solid #060606;"/>
                <br/>
			<?php endforeach; ?>
			<?php submit_button( 'Save Changes' ); ?>
		</form>
	</div>
	<?php
}

function my_woo_plugin_register_settings() {
	register_setting(
		'my-woo-plugin-settings',
		'my_woo_plugin_products',
		array(
			'type' => 'array',
			'sanitize_callback' => 'my_woo_plugin_sanitize_options'
		)
	);
}
add_action( 'admin_init', 'my_woo_plugin_register_settings' );

function my_woo_plugin_sanitize_options( $input ) {
	$sanitized_input = array();
	foreach ( $input as $product_id => $product_data ) {
		$product = wc_get_product( $product_id );
		$sanitized_input[ $product_id ] = array(
			'name' => sanitize_text_field( $product_data['name'] ),
			'price' => sanitize_text_field( $product_data['price'] ),
			'image' => sanitize_text_field( $product_data['image'] ),
		);
		$product->set_name( $sanitized_input[ $product_id ]['name'] );
		$product->set_regular_price( $sanitized_input[ $product_id ]['price'] );
		$image_url = $sanitized_input[ $product_id ]['image'];
		if ( $image_url ) {
			$image_id = my_woo_plugin_upload_image( $image_url );
			if ( $image_id ) {
				$product->set_image_id( $image_id );
			}
		}
		$product->save();
	}
	return $sanitized_input;
}

function my_woo_plugin_upload_image( $image_url ) {
	$image_name = basename( $image_url );
	$upload_dir = wp_upload_dir();
	$image_dir = $upload_dir['path'] . '/';
	if ( ! file_exists( $image_dir ) ) {
		wp_mkdir_p( $image_dir );
	}
	$image_data = file_get_contents( $image_url );
	$image_file = $image_dir . $image_name;
	file_put_contents( $image_file, $image_data );
	$wp_filetype = wp_check_filetype( $image_name, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => sanitize_file_name( $image_name ),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attachment_id = wp_insert_attachment( $attachment, $image_file );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $image_file );
	wp_update_attachment_metadata( $attachment_id, $attachment_data );
	return $attachment_id;
}
