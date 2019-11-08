<?php
/**
 * Slider Common Header template.
 * Create new slider modal section
 * 
 * @package wp-before-after-slider\admin\Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="wpbas-header">
    <ul class="wpbas-header-nav">
        <li class="wpbas-logo">
            <span class="wpbas-name"><?php esc_html_e( 'Before After Slider', 'wp-before-after-slider' ); ?></span>
            <span class="wpbas-version"><?php echo WP_BAS_VERSION; ?></span>
        </li>
        <li class="wpbas-toolbar-item">
                <a href="#TB_inline?height=200&amp;width=600&amp;inlineId=add-new-wpbas" class="thickbox button button-primary">
                    <?php esc_html_e( 'Add New Slider', 'wp-before-after-slider' ); ?>
                </a>
        </li>
        <li class="wpbas-toolbar-item">
            <button class="button"><?php esc_html_e( 'Import Slider', 'wp-before-after-slider' ); ?></button>
        </li>
    </ul>
</div>


<?php
/**
 * Create Slider Modal section
 *
 */
?>
<div id="add-new-wpbas" style="display: none;">
    <h3 class="wpbas-modal-title">
        <?php esc_html_e( 'Add Before/After Slider', 'wp-before-after-slider' ); ?>
    </h3>

    <div class="form-table-wrap" id="new-wpbaslider-form">
        
        <div id="wpbas-admin-notice" class="wpbas-error wpbas-notice" style="display: none;">
            <div class="notice-container"></div>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    <?php esc_html_e( 'Dismiss this notice.', 'wp-before-after-slider' ); ?>
                </span>
            </button>
        </div>

        <table class="form-table">      
            <tr valign="top">
                <td>
                    <input id="wpbas-slider-name" class="regular-text" type="text" name="wpbas['slider_name']" placeholder="<?php esc_attr_e( 'Enter Slider Name', 'wp-before-after-slider' ); ?>">
                </td>
                <td>
                    <?php submit_button( __( 'Create New Slider', 'wp-before-after-slider' ), 'button-primary button-large wpbas-add-slider' ); ?>
                </td>
            </tr>
            
        </table>
            
    </div> 

</div>



<?php
/**
 * Slider Settings Modal
 *
 */
?>
<div id="wpbas-add-settings" style="display: none;">
    <h3 class="wpbas-modal-title">
        <?php esc_html_e( 'Slider Settings', 'wp-before-after-slider' ); ?>
    </h3>

    <div class="form-table-wrap" id="wpbaslider-settings-form">
        
        <div id="wpbas-admin-notice" class="wpbas-error wpbas-notice" style="display: none;">
            <div class="notice-container"></div>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    <?php esc_html_e( 'Dismiss this notice.', 'wp-before-after-slider' ); ?>
                </span>
            </button>
        </div>

        <table class="form-table">      
            <tr valign="top">
                <td>
                    <input id="wpbas-slider-name" class="regular-text" type="text" name="wpbas['slider_name']" placeholder="<?php esc_attr_e( 'Enter Slider Name', 'wp-before-after-slider' ); ?>">
                </td>
                <td>
                    <?php submit_button( __( 'Save Settings', 'wp-before-after-slider' ), 'button-primary button-large wpbas-save-settings' ); ?>
                </td>
            </tr>
            
        </table>
            
    </div> 

</div>