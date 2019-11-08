<?php
/**
 * All slider list shows here.
 *
 * @package wp-before-after-slider\admin\Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>

<div class="wrap">
    
    <?php require_once( WP_BAS_INC_DIR . "/admin/templates/slider-header.php" ); ?>

    <div class="wpbas-page-wrap">

        <hr class="wp-header-end">
        <h3><?php echo __( 'All Sliders', 'wp-before-after-slider' ) ?></h3>
        <table class="widefat" cellspacing="0">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'SL No.', 'wp-before-after-slider' ); ?></th>
                    <th><?php esc_html_e( 'Name', 'wp-before-after-slider' ); ?></th>
                    <th><?php esc_html_e( 'Shortcode', 'wp-before-after-slider' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'wp-before-after-slider' ); ?></th>
                </tr>
            </thead>
            <tbody class="ui-sortable" id="wpbas-sliders-body">
                <?php $count = 0; ?>
                <?php if( !empty( $all_sliders ) ): ?>
                    
                    <?php foreach( $all_sliders as $key => $slider ): ?>
                        <?php $count++; ?>
                        <tr>
                            <td><?php esc_html_e( $count ); ?></td>
                            <td><?php esc_html_e( $slider ); ?></td>
                            <td><pre>[wpbaslider name="<?php esc_html_e( $slider ); ?>"]</pre></td>
                            <td>
                                <div class="wpbas-actions wpbas-actions-list">
                                    <a onclick="wpbas_app.updateSliderSetting( '<?php echo esc_attr( $slider ); ?>' ); return false;" class="wpbas-icon-setting wpbas-icon-btn" href="#" title="<?php esc_attr_e( 'Setting', 'wp-before-after-slider' ); ?>"></a>
                                    <a class="wpbas-icon-edit wpbas-icon-btn" href="<?php echo esc_url( admin_url( 'admin.php?page=add-wpbaslider&slider='.$slider ) ); ?>" title="<?php echo __( 'Edit', 'wp-before-after-slider' ); ?>"></a>
                                    <a onclick="wpbas_app.deleteSlidersByName( '<?php echo esc_attr( $slider ); ?>' ); return false;" class="wpbas-icon-delete wpbas-icon-btn" href="#" title="<?php esc_attr_e( 'Delete', 'wp-before-after-slider' ); ?>"></a>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <div>
                                <p style="color: #d46868;"><?php echo __( 'No results found.', 'wp-before-after-slider' ) ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                
                
            </tbody>
        </table>

    </div>
    
</div>
