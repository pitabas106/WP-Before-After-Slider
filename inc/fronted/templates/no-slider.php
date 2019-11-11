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


?>

<h2>
	<?php __( 'Slider not found! Please go to the Slider setting page to check.', 'wp-before-after-slider' ); ?>
</h2>