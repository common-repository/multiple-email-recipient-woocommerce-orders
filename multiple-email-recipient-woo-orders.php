<?php
/**
 * Plugin Name: Multiple email recipient for woo orders
 * Plugin URI: 
 * Description: Provides extra email field in checkout to send customer email copy.
 * Author: net4earning
 * Author URI: https://vrwebs.in/
 * Version: 1.0
 */

/*our functions for controlling the mail sending*/

//Check if WooCommerce is active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

// Hook me in!
add_filter('woocommerce_billing_fields', 'vr_merw_extra_email');
// Create email field
function vr_merw_extra_email($fields){
    $fields['extra_email_option'] = array(
        'label' => __('Extra Email', 'woocommerce'),
        'placeholder' => _x('Extra Email....', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => false,
        'type' => 'email',
        'class' => array('my-css')
    );

    return $fields;
}

// WooCommerce core
add_action( 'woocommerce_checkout_update_order_meta', 'vr_merw_extra_email_update_order_meta' );
function vr_merw_extra_email_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['extra_email_option'] ) ) {
		update_post_meta( $order_id, 'extra_email_option', sanitize_email( $_POST['extra_email_option'] ) );
	}
}

// Add extra email address
add_filter( 'woocommerce_email_headers', 'vr_merw_extra_email_bcc', 9999, 3 );
function vr_merw_extra_email_bcc( $headers, $email_id, $order ) {
	$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    $email = get_post_meta( $order_id, 'extra_email_option', true );
    if ( 'customer_completed_order' == $email_id ) {
        $headers .= "Bcc: ".$email . "\r\n";
    }
    return $headers;
}
    
}
    else 
    
        {
            return 'WooCommerce is not active, please install and activate it first';
         }