<?php
/**
 * Slider Edit Template.
 *
 * @package wp-before-after-slider\admin\Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$slider_name = ( isset( $_GET ) && $_GET['slider'] ) ? esc_attr( $_GET['slider'] ) : '';
$slider_name_display = str_replace('_', ' ', $slider_name );

?>

<div class="wrap">
    
    <?php require_once( WP_BAS_INC_DIR . "/admin/templates/slider-header.php" ); ?>

    <div class="wpbas-page-wrap">

        <hr class="wp-header-end">
        <h3>
        	<?php echo __( 'Edit- ', 'wp-before-after-slider' ). $slider_name_display; ?> 
        	<span class="wpbas-version"><?php echo __( 'Shortcode: ', 'wp-before-after-slider' ); ?>[wpbaslider name="<?php echo $slider_name_display; ?>"]</span>
        	<!-- <span class="wp-menu-image dashicons-before dashicons-admin-generic"><br></span> -->
            <a href="#TB_inline?height=400&amp;width=600&amp;inlineId=wpbas-add-settings" class="thickbox button button-primary"><?php echo __( 'Slider Settings', 'wp-before-after-slider' ); ?></a>
        </h3>


        <div class="wpbas-columns-2" id="poststuff">
        	<div class="wpbas-left-column">
        		<div class="postbox">
        			<h2 class="hndle ui-sortable-handle">
        				<span><?php _e( 'Add/Edit Slides', 'wp-before-after-slider'); ?></span>
        			</h2>
        			<div class="inside">
        				<form id="wpbas_upload_slide" method="post" action="">
					        <p class="wpbas-info">
					        	<?php _e( "Please make sure all images you upload in same slider are of same dimentions", "wp-before-after-slider" ); ?>
				        	</p>
				        	<input id="wpbas_slider_name" type="hidden" name="slider_name" value="<?php esc_attr_e( $slider_name ); ?>" />
						    <?php wp_nonce_field( 'wpbas_add_slides'); ?>

				        	<table class="form-table" cellspacing="0">
					            <tr>
					            	<th style="width: 90px;">
					            		<strong><?php _e( 'Slider Title', 'wp-before-after-slider' ); ?> </strong>
					            	</th>
					                <td>
					                    <input type="text" name="title" placeholder="<?php esc_attr_e( 'Enter Slider title', 'wp-before-after-slider' ); ?>" class="regular-text" />
					                </td>
								</tr>

								<tr>
					            	<td colspan="2"><hr></td>
					            </tr>
					            
					            <tr>
					            	<th style="width: 90px;">
							            <label><strong><?php esc_attr_e( "Before Image", "wp-before-after-slider" ); ?></strong></label>
									</th>
					            	<td>
					            		<input class="wpbas-upload-img-id" type="hidden" name="before_thumb" value="" />
					            		<input type="button" class="button-primary wpbas-thumb-before wpbas-upload-img" name="<?php echo esc_attr( $slider_name ); ?>_before" value="<?php esc_attr_e( 'Upload', 'wp-before-after-slider' );?>" />
					            		<div class="wpbas-upload-img-preview before-preview"></div>
					              	</td>
								</tr>

					            <tr>
					            	<th style="width: 90px;">
					            		<?php esc_attr_e( 'Before Title', 'wp-before-after-slider' ); ?>
				            		</th>
					                <td>
					                	<input type="text" name="caption_before" class="regular-text" placeholder="<?php esc_attr_e( 'Before', 'wp-before-after-slider' );?>" />
					                </td>
					            </tr>

					            <tr>
					            	<td colspan="2"><hr></td>
					            </tr>

					            <tr>
					            	<th style="width: 90px;">
							            <label>
							            	<strong><?php esc_attr_e( 'After Image', 'wp-before-after-slider'); ?> </strong>
							            </label>
					                </th>
					            	<td>
					                	<input class="wpbas-upload-img-id" type="hidden" name="after_thumb" value="" />
					                	<input type="button" class="button-primary wpbas-thumb-after wpbas-upload-img" name="<?php esc_attr_e( $slider_name ); ?>_after" value="<?php esc_attr_e( 'Upload', 'wp-before-after-slider' ); ?>" >
					                	<div class="wpbas-upload-img-preview after-preview"></div>
					                </td>
					            </tr>

					            <tr>
					            	<th style="width: 90px;">
					            		<?php esc_attr_e( 'After Title', 'wp-before-after-slider' ); ?>
				            		</th>
					                <td>
					                	<input type="text" name="caption_after" class="regular-text" placeholder="<?php esc_attr_e( 'After', 'wp-before-after-slider' ); ?>" />
					                </td>
					            </tr>

				            	<tr>
									<td colspan="2" style="text-align: right;">
						                <input type="hidden" name="slider_name" value="<?php esc_attr_e( $slider_name ); ?>" />
						                <input type="hidden" name="slide_update" value="" />
						                <input type="hidden" name="slide_id" value="" />
						            	<button id="upload_slide_btn" onclick="wpbas_app.AddSlide(); return false;" class="button-primary button">
						            		<?php esc_attr_e( 'Save Slide', 'wp-before-after-slider' ); ?>
					            		</button>
					            	</td>
					            </tr>

							</table>
					    </form>
        			</div>
        		</div>
        	</div><!--.wpbas-left-column-->

        	<div class="wpbas-right-column">
        		<div class="postbox">
        			<h2 class="hndle ui-sortable-handle"><span><?php esc_attr_e( 'All Slides', 'wp-before-after-slider' ); ?></span></h2>
        			<div class="inside">
        				<script type="text/javascript">
        					jQuery(function() {
        						wpbas_app.getAllSlides('<?php esc_attr_e( $slider_name ); ?>', 'all');
        					});
        				</script>
        				<div id="wpbas-all-slides"></div>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
    
</div>
