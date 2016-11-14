<?php
/**
 * Register Payments admin menu
 *
 * @package Components\Payments\Admin
 */

if( is_admin() ){
	add_action( 'admin_menu', 'appthemes_admin_menu_setup', 11 );
	add_action( 'admin_print_styles', 'appthemes_payments_menu_icon' );
	add_action( 'admin_print_styles', 'appthemes_payments_icon' );
	add_action( 'init', 'appthemes_register_payments_settings', 12);
	add_action( 'parse_request', 'appthemes_admin_quick_find_post' );
}

/**
 * Get the full URL for an image
 *
 * @param string $name The basename of the image
 * @return string
 */
function appthemes_payments_image( $name ) {
	return appthemes_payments_get_args( 'images_url' ) . $name;
}

/**
 * Registers the payment settings page
 * @return void
 */
function appthemes_register_payments_settings(){
	new APP_Payments_Settings_Admin( APP_Gateway_Registry::get_options() );
}

/**
 * Adds the Orders Top Level Menu
 * @return void
 */
function appthemes_admin_menu_setup(){
	add_menu_page( __( 'Orders', APP_TD ), __( 'Payments', APP_TD ), 'edit_others_posts', 'app-payments', null, '', 4 );
}

/**
 * Adds the Payments Menu Sprite to the CSS for admin pages
 * @return void
 */
function appthemes_payments_menu_icon() {

echo <<<EOB
<style type="text/css">
#toplevel_page_app-payments .menu-icon-generic div.wp-menu-image:before {
	font-family: FontAwesome;
	content: "\\f09d";
	font-size: 18px;
	padding: 9px 0;
}
</style>
EOB;

}

/**
 * Adds the Payments Icon for certain pages
 * @return void
 */
function appthemes_payments_icon(){
	$url = appthemes_payments_image( 'payments-med.png' );
?>
<style type="text/css">
	.icon32-posts-pricing-plan,
	.icon32-posts-transaction {
		background-image: url('<?php echo $url; ?>');
		background-position: -5px -5px !important;
	}
</style>
<?php
}

function appthemes_admin_quick_find_post( $wp_query ){
	global $pagenow;

	if( 'edit.php' != $pagenow )
		return;

	if( empty( $wp_query->query_vars['s'] ) )
		return;

	$query = $wp_query->query_vars['s'];
	if( '#' != substr( $query, 0, 1 ) )
		return;

	$id = absint( substr( $query, 1 ) );
	if( ! $id ){
		$wp_query->query_vars['s'] = 'Bad ID';
	}

	$post = get_post( $id );
	if( $post ){
		$wp_query->query_vars['s'] = get_edit_post_link( $id );
		wp_redirect( 'post.php?action=edit&post=' . $id  );
		exit;
	}else{
		$wp_query->query_vars['s'] = 'Not Found';
	}


}
