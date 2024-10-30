<?php
/**
* Plugin Name: Content Between Paragraph
* Plugin URI: http://www.francescosganga.it/wordpress/plugins/content-inside-paragraph/
* Description: Insert custom content between your articles' paragraphs
* Version: 1.5
* Author: Francesco Sganga
* Author URI: http://www.francescosganga.it/
**/

function cbp_init_plugin() {
	register_setting('cbp-options', 'cbp_content', array(
		'type' => 'string', 
		'sanitize_callback' => null,
		'default' => ""
	));

	register_setting('cbp-options', 'cbp_nparagraph', array(
		'type' => 'string', 
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 4
	));
}
add_action('admin_init', 'cbp_init_plugin');

function cbp_options_panel(){
	add_menu_page('CBP', 'CBP', 'manage_options', 'cbp-options', 'cbp_options_settings');
	add_submenu_page( 'cbp-options', 'About', 'About', 'manage_options', 'cbp-option-about', 'cbp_options_about');
}
add_action('admin_menu', 'cbp_options_panel');

function cbp_options_settings(){
	?>
	<div class="wrap">
		<h1>Content Between Paragraph</h1>
		<h2>Settings Section</h2>
		<form method="post" action="options.php">
		<?php settings_fields('cbp-options'); ?>
		<?php do_settings_sections('cbp-options'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Content</th>
				<td>
	        		<textarea name="cbp_content"><?php echo get_option('cbp_content'); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Insert Content after Paragraphs</th>
				<td>
					<input type="number" name="cbp_nparagraph" value="<?php echo get_option('cbp_nparagraph'); ?>" />
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
		<hr />
	</div>
	<?php
}

function cbp_options_about(){
	?>
	<h1>About</h1>
	<h2>Under Construction</h2>
	<?php
}

function cbp_insert_post_content($content) {
	if(is_single()) {
		$cbp_content = get_option("cbp_content");
		$cbp_nparagraph = get_option("cbp_nparagraph");
		return cbp_insert_after_paragraph($cbp_content, $cbp_nparagraph, $content);
	}
     
	return $content;
}
  
// Parent Function that makes the magic happen
  
function cbp_insert_after_paragraph($insertion, $paragraph_id, $content) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {
		if (trim($paragraph)) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}
     
    return implode('', $paragraphs);
}

add_filter('the_content', 'cbp_insert_post_content');
