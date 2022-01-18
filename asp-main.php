<?php
/*
Plugin Name: A Security Plugin
Description: Add security protocols for a browser to listen to.
Version: 1.0
Author: Ryon McCamish
Author URI: http://ryonmccamish.com
Plugin URI: http://ryonmccamish.com
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Enqueue CSS file
function plugin_assests_css() {
	wp_enqueue_style ('plugin-stylesheet', plugins_url('/css/asp-styles.css', __FILE__));
}
add_action('admin_enqueue_scripts','plugin_assests_css');


// Array for CSP attributes
$attrib_array = array(
	"defaultsrc" 	=> get_option('default_src'),
	"imgsrc" 		=> get_option('image_src'),
	"fontsrc" 		=> get_option('font_src'),
	"stylesrc" 		=> get_option('style_src'),
	"scriptsrc" 	=> get_option('script_src'),
	"connectsrc" 	=> get_option('connect_src'),
	"objectsrc" 	=> get_option('object_src'),
	"mediasrc" 		=> get_option('media_src' ),
	"sandboxsrc" 	=> get_option('sandbox_src'),
	"reportuirsrc" 	=> get_option('report_uri_src'),
	"childsrc" 		=> get_option('child_src' ),
	"formactionsrc" => get_option('form_action_src'),
	"referrerpolicysrc" => get_option('referrer_policy_src')
	);

// Register settings for text fields
add_action( 'admin_init', 'my_plugin_settings' );
function my_plugin_settings() {
	register_setting('my-plugin-settings-group', 'default_src');
	register_setting('my-plugin-settings-group', 'image_src');
	register_setting('my-plugin-settings-group', 'font_src');
	register_setting('my-plugin-settings-group', 'style_src');
	register_setting('my-plugin-settings-group', 'script_src');
	register_setting('my-plugin-settings-group', 'connect_src');
	register_setting('my-plugin-settings-group', 'object_src');
	register_setting('my-plugin-settings-group', 'media_src');
	register_setting('my-plugin-settings-group', 'sandbox_src');
	register_setting('my-plugin-settings-group', 'report_uri_src');
	register_setting('my-plugin-settings-group', 'child_src');
	register_setting('my-plugin-settings-group', 'form_action_src');
	register_setting('my-plugin-settings-group', 'referrer_policy_src');

}

// Add the admin menu
add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'A Security Plugin', 'administrator', 'my-plugin-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

// Content Security Policy form fields
function my_plugin_settings_page() {
  // Settings fields
?>
<div class="wrap">
<h2>Content Security Policy Attributes</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'my-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-plugin-settings-group' ); ?>
    <table class="form-table-test">
        <tr valign="top">
        <th scope="row">Default Source</th>
		<td><input type="text" name="default_src" value="<?php echo esc_attr( get_option('default_src') ); ?>" /></td>
        <th scope="row">Image Source</th>
        <td><input type="text" name="image_src" value="<?php echo esc_attr( get_option('image_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Font Source</th>
        <td><input type="text" name="font_src" value="<?php echo esc_attr( get_option('font_src') ); ?>" /></td>
        <th scope="row">Style Source</th>
        <td><input type="text" name="style_src" value="<?php echo esc_attr( get_option('style_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Script Source</th>
        <td><input type="text" name="script_src" value="<?php echo esc_attr( get_option('script_src') ); ?>" /></td>
        <th scope="row">Connect Source</th>
        <td><input type="text" name="connect_src" value="<?php echo esc_attr( get_option('connect_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Object Source</th>
        <td><input type="text" name="object_src" value="<?php echo esc_attr( get_option('object_src') ); ?>" /></td>
        <th scope="row">Media Source</th>
        <td><input type="text" name="media_src" value="<?php echo esc_attr( get_option('media_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Sandbox</th>
        <td><input type="text" name="sandbox_src" value="<?php echo esc_attr( get_option('sandbox_src') ); ?>" /></td>
        <th scope="row">Report URI</th>
        <td><input type="text" name="report_uri_src" value="<?php echo esc_attr( get_option('report_uri_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Child Source</th>
        <td><input type="text" name="child_src" value="<?php echo esc_attr( get_option('child_src') ); ?>" /></td>
        <th scope="row">Form Action</th>
        <td><input type="text" name="form_action_src" value="<?php echo esc_attr( get_option('form_action_src') ); ?>" /></td>
        </tr>
        <tr valign="top">
			<th scope="row">Referrer Policy</th>
			<td colspan="2" align="center"><input type="text" name="referrer_policy_src" value="<?php echo esc_attr( get_option('referrer_policy_src') ); ?>" /></td>
		</tr>
    </table>
    
    <?php submit_button(); ?>
    <?php
echo '</form>
</div>';
}

// Foreach loop to check if text field is null
foreach ($attrib_array as $k => $value) {
	if (empty($value)) {
		$attrib_array[$k] = '*';
	}
}

// HTTP header callouts
header("X-Frame-Options: deny");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/font-woff2");
header("Content-Security-Policy: default-src $attrib_array[defaultsrc]; img-src $attrib_array[imgsrc]; font-src $attrib_array[fontsrc]; style-src $attrib_array[stylesrc]; script-src $attrib_array[scriptsrc]; connect-src $attrib_array[connectsrc]; object-src $attrib_array[objectsrc]; media-src $attrib_array[mediasrc]; sandbox allow-scripts allow-forms $attrib_array[sandboxsrc]; report-uri $attrib_array[reporturisrc]; child-src $attrib_array[childsrc]; form-action $attrib_array[formactionsrc]");
header("Referrer-Policy: $attrib_array[referrerpolicysrc]");

// Set base URL to domain
function base_url() {
echo "<base href=" . get_site_url() . ">";
}
add_action('wp_head','base_URL');