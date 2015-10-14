<?php
/*
Plugin Name: Discontinued Product for WooCommerce
Plugin URI: http://vu-tran.com/
Description: Adds the ability to flag a product as discontinued to WooCommerce
Version: 0.1.0
Author: Vu Tran
Author URI: http://vu-tran.com
Copyright: © 2015 Vu Tran
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

add_action( 'init', 'discontinued_product_for_woocommerce_init' );
add_action( 'woocommerce_product_options_general_product_data', 'discontinued_product_for_woocommerce_add_custom_fields' );
add_action( 'woocommerce_process_product_meta', 'discontinued_product_for_woocommerce_save_custom_fields' );

/**
 * Initializes the plugin
 *
 * @access public
 * @return void
 */
function discontinued_product_for_woocommerce_init()
{
    register_post_status( 'wc-discontinued', array(
        'label' => __( 'Discontinued', 'discontinued_product_for_woocommerce' ),
        'public' => true,
        'exclude_from_search' => true,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Discontinued <span class="count">(%s)</span>', 'Discontinued <span class="count">(%s)</span>' )
    ));
}

/**
 * Adds the custom fields
 *
 * @access public
 * @return void
 */
function discontinued_product_for_woocommerce_add_custom_fields()
{
    global $woocommerce, $post;
    echo '<div class="options_group">';
    woocommerce_wp_checkbox(array(
        'id' => '_discontinued',
        'label' => __( 'Discontinued', 'discontinued_product_for_woocommerce' ),
        'name' => '_discontinued',
        'description' => __( 'Check this box to mark this product as discontinued.', 'discontinued_product_for_woocommerce' )
    ));
    echo '</div>';
}

/**
 * Saves the custom fields
 *
 * Saves the post meta "_discontinued" to "yes" or "no"
 * Updates the post_status to "wc-discontinued" or "publish"
 *
 * @access public
 * @param int $post_id          The post ID that is being edited
 * @return void
 */
function discontinued_product_for_woocommerce_save_custom_fields( $post_id )
{
    $discontinued = isset( $_POST['_discontinued'] ) ? esc_attr( $_POST['_discontinued'] ) : null;
    // Save the post meta
    update_post_meta( $post_id, '_discontinued', esc_attr( $discontinued ) );
    // Set the new post status
    $newPostStatus = $discontinued ? 'wc-discontinued' : 'publish';
    // Udpate the post status
    wp_update_post(array(
        'ID' => $post_id,
        'post_status' => $newPostStatus
    ));
}
