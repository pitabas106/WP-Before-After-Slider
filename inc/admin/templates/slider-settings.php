<?php
/**
 * Slider Settings Template.
 *
 * @package wp-before-after-slider\admin\Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$slider_name = ( isset( $_GET ) && $_GET['slider'] ) ? esc_attr( $_GET['slider'] ) : '';
$slider_name_display = str_replace('_', ' ', $slider_name );

$slider_settings_slug = WPBAS_NAME_SPACE.$slider_name.'_settings';

$this->get_sanitize_slider_settings( $slider_settings_slug );

?>

<div class="wrap">
    
    <?php require_once( WP_BAS_INC_DIR . "/admin/templates/slider-header.php" ); ?>

    <div class="wpbas-page-wrap">

        <hr class="wp-header-end">
        
        <h3>
        	<?php echo __( 'Settings - ', 'wp-before-after-slider' ). $slider_name_display; ?> 
        	<span class="wpbas-version"><?php echo __( 'Shortcode: ', 'wp-before-after-slider' ); ?>[wpbaslider name="<?php echo $slider_name; ?>"]</span>
            
            <a class="button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=add-wpbaslider&slider='.$slider_name ) ); ?>">
            	<?php echo __( 'Add/Edit Slider', 'wp-before-after-slider' ); ?>
        	</a>
        </h3>

        <?php do_action( 'wpbas_admin_notices' ); ?>
		<hr>
		<form id="wpbas_slider_settings" method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=wpbaslider-settings&slider='.$slider_name ) ); ?>">
			
	        <input type="hidden" name="action" value="wpbas_settings" />

        	<input type="hidden" name="slider_name" value="<?php esc_attr_e( $slider_name ); ?>" />
			<?php wp_nonce_field( 'wpbas_slider_settings_nonce' ); ?>

			<table class="form-table" cellspacing="0">
				<tr>
	            	<th>
	            		<label for="slider_type">
	            			<?php _e( 'Slider Type', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<?php _e( 'Animated', 'wp-before-after-slider' ); ?> <input type="radio" name="slider_type" value="1" <?php echo ( $this->_s_slider_type == 1 ) ? 'checked' : ''; ?>>
	                	<?php _e( 'Traditional', 'wp-before-after-slider' ); ?> <input type="radio" name="slider_type" value="2" <?php echo ( $this->_s_slider_type == 2 ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Animated: jQuery slider.<br>Traditional: Without animation, static images', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th scope="row">
	            		<label for="slider_width">
	            			<?php _e( 'Slider Width', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="number" name="slider_width" min="0" max="3000" step="any" class="regular-text" value="<?php echo ( !empty( $this->_s_slider_width ) ) ? $this->_s_slider_width : '0'; ?>" placeholder="<?php esc_attr_e( 'Enter Slider Width', 'wp-before-after-slider' ); ?>" />
	                	<p class="description"><?php _e( 'Fixed slide width.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th scope="row">
	            		<label for="slider_paginations">
	            			<?php _e( 'Show slider paginations', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="slider_paginations" value="1" <?php echo ( $this->_s_slider_paginations == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Enable Slider Pagination.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th scope="row">
	            		<label for="slider_nex_prev">
	            			<?php _e( 'Show slider Next/Prev buttons', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="slider_nex_prev" value="1" <?php echo ( $this->_s_slider_nex_prev == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Enable Slider Next/Prev buttons.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>

				<tr>
	            	<th scope="row">
	            		<label for="show_slider_title">
	            			<?php _e( 'Show slider Title', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="show_slider_title" value="1" <?php echo ( $this->_s_show_slider_title == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Show Slider Title.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>

				<tr>
	            	<th scope="row">
	            		<label for="default_offset_pct">
	            			<?php _e( 'Default Offset', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="number" name="default_offset_pct" min="0" max="1" step="any" class="regular-text" value="<?php echo ( !empty( $this->_s_default_offset_pct ) ) ? $this->_s_default_offset_pct : '0.7'; ?>" placeholder="<?php esc_attr_e( 'Enter Default Offset', 'wp-before-after-slider' ); ?>" />
	                	<p class="description"><?php _e( 'How much of the before image is visible when the page loads.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th>
	            		<label for="orientation">
	            			<?php _e( 'Orientation', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<?php _e( 'Horizontal', 'wp-before-after-slider' ); ?> <input type="radio" name="orientation" value="horizontal" <?php echo ( $this->_s_orientation == 'horizontal' ) ? 'checked' : ''; ?>>
	                	<?php _e( 'Vertical', 'wp-before-after-slider' ); ?> <input type="radio" name="orientation" value="vertical" <?php echo ( $this->_s_orientation == 'vertical' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Orientation of the before and after images (horizontal or vertical)', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th>
	            		<label for="no_overlay">
	            			<?php _e( 'Disable Before/After Overlay', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="no_overlay" value="1" <?php echo ( $this->_s_no_overlay == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Do not show the overlay with before and after.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th>
	            		<label for="move_slider_on_hover">
	            			<?php _e( 'Move slider on mouse hover?', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="move_slider_on_hover" value="1" <?php echo ( $this->_s_move_slider_on_hover == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Move slider on mouse hover.', 'wp-before-after-slider' ); ?></p>
                	</td>
				</tr>
				<tr>
	            	<th>
	            		<label for="move_with_handle_only">
	            			<?php _e( 'Move with handle only?', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="move_with_handle_only" value="1" <?php echo ( $this->_s_move_with_handle_only == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Allow a user to swipe anywhere on the image to control slider movement.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
	            	<th>
	            		<label for="click_to_move">
	            			<?php _e( 'Click to move', 'wp-before-after-slider' ); ?>
	            		</label>
	            	</th>
	                <td>
	                	<input type="checkbox" name="click_to_move" value="1" <?php echo ( $this->_s_click_to_move == 'true' ) ? 'checked' : ''; ?>>
	                	<p class="description"><?php _e( 'Allow a user to click (or tap) anywhere on the image to move the slider to that location.', 'wp-before-after-slider' ); ?></p>
	                </td>
				</tr>
				<tr>
					<td colspan="2">
						<?php submit_button( __('Save Settings', 'wp-before-after-slider') ); ?>
					</td>
				</tr>
			</table>
		</form>
    </div>
</div>
