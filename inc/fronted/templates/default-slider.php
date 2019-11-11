<?php
/**
 * Slider Edit Template.
 *
 * @package wp-before-after-slider\frontend\Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$slider_id = strtolower( $this->_slider_name );

$slider_settings_slug = WPBAS_NAME_SPACE.$this->_slider_name;

$bxslider_cls = ( count( $this->_slide_fields_data ) >= 2 ) ? 'wpbas-bxslider' : 'no-wpbas-bxslider';
?>

<script>
    jQuery(window).load(function(){

        // Only apply the Bxslider if multiple slides added 
        <?php if( count( $this->_slide_fields_data ) >= 2 ): ?>
            jQuery('.wpbas-bxslider-<?php esc_attr_e( $slider_id ); ?>').bxSlider({
                adaptiveHeight      : true,
                infiniteLoop        : true,
                controls            : <?php esc_attr_e( $this->_s_slider_nex_prev ); ?>,
                pager               : <?php esc_attr_e( $this->_s_slider_paginations ); ?>,
                slideWidth          : <?php esc_attr_e( $this->_s_slider_width ); ?>,
                onSliderLoad: function(currentIndex){
                    //do your stuff
                }
            });
        <?php endif; ?>
      	

        // If slider type is jQuery animated
        <?php if( $this->_s_slider_type == 1 ): ?>
            jQuery(".wpbas-twenty20-<?php esc_attr_e( $slider_id ); ?>").twentytwenty({
                default_offset_pct     : <?php esc_attr_e( $this->_s_default_offset_pct ); ?>,
                orientation            : '<?php esc_attr_e( $this->_s_orientation ); ?>',
                no_overlay             : <?php esc_attr_e( $this->_s_no_overlay ); ?>,
                move_slider_on_hover   : <?php esc_attr_e( $this->_s_move_slider_on_hover ); ?>,
                move_with_handle_only  : <?php esc_attr_e( $this->_s_move_with_handle_only ); ?>,
                click_to_move          : <?php esc_attr_e( $this->_s_move_with_handle_only ); ?>
            });
        <?php endif; ?>
		
    });
</script>


<div class="wpbas-slider-wrap" id="<?php esc_attr_e( $slider_id ); ?>" style="width: <?php esc_attr_e( $this->_s_slider_width ); ?>">

    <ul class="wpbas-bxslider-<?php esc_attr_e( $slider_id ); ?> <?php esc_attr_e( $bxslider_cls );  ?>">
        <?php $count = 0; ?>
        <?php foreach( $this->_slide_fields_data as $key => $slide ): ?>
            
            <?php 
                $count++;
                $slide_before_img   = $slide['slide_before_img'];
                $slide_after_img    = $slide['slide_after_img'];
                $caption_before     = $slide['caption_before'];
                $caption_after      = $slide['caption_after'];
                $title              = $slide['title'];
            ?>
            <li class="bxitem-<?php esc_attr_e( $count ); ?>">
                
                <?php if( $this->_s_show_slider_title == 'true' ): ?>
                    <h4 class="wpbas-title">
                        <span><?php echo esc_html__( $title, 'wp-before-after-slider' );  ?></span>
                    </h4>
                <?php endif; ?>

                <?php if( $this->_s_slider_type == 1 ): //Animated B/A Slider ?>
                    <div class="wpbas-twenty20-<?php esc_attr_e( $slider_id ); ?>">
                        <img src="<?php echo esc_url( $slide_before_img ); ?>" title="<?php esc_attr_e( $caption_before, 'wp-before-after-slider' ); ?>" />
                        <?php if( $this->_s_no_overlay != 'true' ): ?>
                            <span class="wpbas-t-caption before-caption">
                                <?php echo esc_html__( $caption_before, 'wp-before-after-slider' ); ?>
                            </span>
                        <?php endif; ?>

                        <img src="<?php echo esc_url( $slide_after_img ); ?>" title="<?php esc_attr_e( $caption_after, 'wp-before-after-slider' ); ?>" />
                        <?php if( $this->_s_no_overlay != 'true' ): ?>
                            <span class="wpbas-t-caption after-caption">
                                <?php echo esc_html__( $caption_after, 'wp-before-after-slider' ); ?>
                            </span>
                        <?php endif; ?>
                        
                    </div>
                <?php else: //Traditional Slider, without jQuery animation ?>
                    
                    <div class="wpbas-traditional wpbas-traditional-<?php esc_attr_e( $slider_id ); ?>">
                        <div class="wpbas-t-left-column wpbas-before">
                            <img src="<?php echo esc_url( $slide_before_img ); ?>" title="<?php esc_attr_e( $caption_before, 'wp-before-after-slider' ); ?>" />
                            <span class="wpbas-t-caption after-caption">
                                <?php echo esc_html__( $caption_before, 'wp-before-after-slider' ); ?>
                            </span>
                        </div>
                        <div class="wpbas-t-right-column wpbas-after">
                            <img src="<?php echo esc_url( $slide_after_img ); ?>" title="<?php esc_attr_e( $caption_after, 'wp-before-after-slider' ); ?>" />
                            <span class="wpbas-t-caption before-caption">
                                <?php echo esc_html__( $caption_after, 'wp-before-after-slider' ); ?>
                            </span>
                        </div>
                    </div>

                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

</div>