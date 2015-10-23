<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://shopitpress.com
 * @since      1.0.4
 *
 * @package    SIP_Reviews_Shortcode
 * @subpackage SIP_Reviews_Shortcode/admin/partials
 */



	/**
	 * After loding this function global page show the admin panel
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_settings_page_ui() { ?>

		<div class="sip-tab-content">
		  <?php screen_icon(); ?>
		  <h2>Custom Color Settings</h2>
		  <form id="wp-color-picker-options" action="options.php" method="post">
		    <?php color_input(); ?>
		    <?php settings_fields( 'wp_color_picker_options' ); ?>
		    <?php do_settings_sections( 'wp-color-picker-settings' ); ?>

		    <p class="submit">
		      <input id="wp-color-picker-submit" name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Color' ); ?>" />
		    </p>

		  </form>
		</div>
		
		<!-- affiliate/credit link -->
		<?php include( SIP_RSWC_DIR . 'admin/partials/ui/affiliate.php'); ?>
		<?php
	}

	/**
	 * Register settings, add a settings section, and add our color fields.
	 *
	 * @since    	1.0.0
	 */
	function sip_rswc_settings_init(){

	  register_setting(
	    'wp_color_picker_options',
	    'color_options',
	    'validate_options'
	  );
	}

function validate_options( $input ){
	  $valid 														= array();
	  $valid['star_color'] 							= sanitize_text_field( $input['star_color'] );
	  $valid['bar_color'] 							= sanitize_text_field( $input['bar_color'] );
	  $valid['review_body_text_color'] 	= sanitize_text_field( $input['review_body_text_color'] );
	  $valid['review_background_color'] = sanitize_text_field( $input['review_background_color'] );
	  $valid['review_title_color'] 			= sanitize_text_field( $input['review_title_color'] );
	  $valid['load_more_button'] 				= sanitize_text_field( $input['load_more_button'] );
	  $valid['load_more_text'] 					= sanitize_text_field( $input['load_more_text'] );

	  return $valid;
	}


	function color_input(){
	  $options 									= get_option( 'color_options' );
	  $star_color 							= ( $options['star_color'] != "" ) ? sanitize_text_field( $options['star_color'] ) : '';
	  $bar_color 								= ( $options['bar_color'] != "" ) ? sanitize_text_field( $options['bar_color'] ) : '#AD74A2';
	  $review_body_text_color 	= ( $options['review_body_text_color'] != "" ) ? sanitize_text_field( $options['review_body_text_color'] ) : '';
	  $review_background_color 	= ( $options['review_background_color'] != "" ) ? sanitize_text_field( $options['review_background_color'] ) : '';
	  $review_title_color 			= ( $options['review_title_color'] != "" ) ? sanitize_text_field( $options['review_title_color'] ) : '';
	  $load_more_button 				= ( $options['load_more_button'] != "" ) ? sanitize_text_field( $options['load_more_button'] ) : '';
	  $load_more_text 					= ( $options['load_more_text'] != "" ) ? sanitize_text_field( $options['load_more_text'] ) : '';

	 ?>
	<table>
		<tr>
			<td width="250"><strong>Review stars</strong></td>
			<td>
				<input id="star-color" name="color_options[star_color]" type="text" value="<?php echo $star_color ?>" />
	  		<div id="star-colorpicker"></div>
	  	</td>
		</tr>
		<tr>
			<td><strong>Reviews bar summary</strong></td>
			<td>
				<input id="bar-color" name="color_options[bar_color]" type="text" value="<?php echo $bar_color ?>" />
	  		<div id="bar-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review background</strong></td>
			<td>
				<input id="review-background-color" name="color_options[review_background_color]" type="text" value="<?php echo $review_background_color ?>" />
	  		<div id="review-background-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review body text</strong></td>
			<td>
				<input id="review-body-text-color" name="color_options[review_body_text_color]" type="text" value="<?php echo $review_body_text_color ?>" />
	  		<div id="review-body-text-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Review title</strong></td>
			<td>
				<input id="review-title-color" name="color_options[review_title_color]" type="text" value="<?php echo $review_title_color ?>" />
	  		<div id="review-title-colorpicker"></div>
			</td>
		</tr>
		<tr>
			<td><strong>Load more button background</strong></td>
			<td>
				<input id="load-more-button-color" name="color_options[load_more_button]" type="text" value="<?php echo $load_more_button ?>" />
	  		<div id="load-more-button-colorpicker"></div>
			</td>
		</tr>

		<tr>
			<td><strong>Load more button text</strong></td>
			<td>
				<input id="load-more-button-text-color" name="color_options[load_more_text]" type="text" value="<?php echo $load_more_text ?>" />
	  		<div id="load-more-button-text-colorpicker"></div>
			</td>
		</tr>
	</table>
	 <?php
	}
	