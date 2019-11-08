<?php
/**
 * Class WP_Before_After_Slider file.
 * 
 * @package wp-before-after-slider
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if ( ! class_exists( 'WP_Before_After_Slider', false ) ) :

    /**
     * WP_Before_After_Slider Class
     */
    class WP_Before_After_Slider {

        /**
         * Member Variable
         *
         * @var object instance
         */
        private static $instance;


        /**
         * Returns the *Singleton* instance of this class.
         *
         * @return Singleton The *Singleton* instance.
         */
        public static function get_instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        /**
         * Class Constructor
         * 
         * @since  1.0.0
         * @return void
         */
        public function __construct() {

            //add_action for plugin shortcode
            add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

            //add_action for slider admin menu
            add_action( 'admin_menu', array( $this, 'wp_bas_admin_menu' ), 99 );

            //add_action for register admin JS/CSS
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

            //add_action for remove admin notice
            add_action( 'admin_head', array( __CLASS__, 'remove_notice_actions' ) );

            //add_action create slider method
            add_action( 'wp_ajax_wpbas_create_slider_ajax', array( $this, 'wpbas_create_slider_ajax' ) );

            //add_action create slider method
            add_action( 'wp_ajax_wpbas_get_all_sliders', array( $this, 'wpbas_get_all_sliders' ) );

            //add_action create slide
            add_action( 'wp_ajax_wpbas_add_slide', array( $this, 'wpbas_add_slide' ) );

            //add_action create slide
            add_action( 'wp_ajax_wpbas_get_all_slides', array( $this, 'wpbas_get_all_slides_ajax' ) );

            //add_action delete slide
            add_action( 'wp_ajax_wpbas_delete_slide_by_id', array( $this, 'wpbas_delete_slide_by_id' ) );

            //add_action delete slider 
            add_action( 'wp_ajax_wpbas_delete_slider_by_name', array( $this, 'wpbas_delete_slider_by_name' ) );

            
        }


        /**
         * Delete Slider by name
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_delete_slider_by_name() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }

            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['security'], 'wp_bas_nonce' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';

                if( ! empty( $posted_data['slider_name'] ) ) {
                    
                    $slider_name = sanitize_text_field( $posted_data['slider_name'] );

                    $all_sliders = get_option( 'wpbaslider' );

                    $all_sliders = maybe_unserialize( $all_sliders );

                    if ( ( $key = array_search( $slider_name, $all_sliders ) ) !== false ) {
                        unset( $all_sliders[$key] );
                        $all_sliders = array_values($all_sliders);
                        update_option( 'wpbaslider', $all_sliders );
                        delete_option( WPBAS_NAME_SPACE.$slider_name );
                    }
                    
                    $status         = 'success';
                    $msg            = 'Slider deleted successfully!';
                } else {
                    $status         = 'error';
                    $msg            = 'Please reload the page and try again.';
                    
                }

                $json_data = array(
                    "status"        =>  $status,
                    "msg"           =>  __( $msg, 'wp-before-after-slider' ),
                );

                wp_send_json( $json_data );

            }


        }

        /**
         * Delete Slide
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_delete_slide_by_id() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }

            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['security'], 'wp_bas_nonce' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';

                if( ! empty( $posted_data['slider_name'] ) && 
                    ( ! empty( $posted_data['slide_id'] ) || $posted_data['slide_id'] == '0' ) ) {
                    

                    $slider_name = sanitize_text_field( $posted_data['slider_name'] );
                    $slide_id = ( int ) $posted_data['slide_id'];
                    
                    $old_slides_set  = get_option( WPBAS_NAME_SPACE.$slider_name );

                    if($old_slides_set[$slide_id])
                        unset($old_slides_set[$slide_id]);

                    update_option( WPBAS_NAME_SPACE.$slider_name, $old_slides_set );

                    //do the update stuff
                    $status         = 'success';
                    $msg            = 'Slide deleted successfully!';
                } else {
                    $status         = 'error';
                    $msg            = 'Please reload the page and try again.';
                    
                }

                $json_data = array(
                    "status"        =>  $status,
                    "msg"           =>  __( $msg, 'wp-before-after-slider' ),
                );

                wp_send_json( $json_data );

            }


        }


        /**
         * Add Slide
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_add_slide() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }
            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wpbas_add_slides' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';

                if( ! empty( $posted_data['title'] ) && 
                    ! empty( $posted_data['before_thumb'] ) && 
                    ! empty( $posted_data['after_thumb'] ) ) {
                    
                    $caption_before = ( isset($posted_data['caption_before']) && $posted_data['caption_before'] != '') ? $posted_data['caption_before'] : __('Before', 'wp-before-after-slider');
                
                    $caption_after  = ( isset($posted_data['caption_after']) && $posted_data['caption_after'] != '') ? $posted_data['caption_after'] : __('After', 'wp-before-after-slider');

                    $slider_name = sanitize_text_field( $posted_data['slider_name'] );
                    
                    $slide_info = array(
                        'before_thumb'        => ( int )$posted_data['before_thumb'],
                        'after_thumb'         => ( int )$posted_data['after_thumb'],
                        'title'               => sanitize_text_field( $posted_data['title'] ),
                        'caption_before'      => sanitize_text_field( $caption_before ),
                        'caption_after'       => sanitize_text_field( $caption_after )
                    );

                    $slider_set      = array();
                    $slider_set[]    = $slide_info;
                    $old_slides_set  = get_option( WPBAS_NAME_SPACE.$slider_name );
                    $slide_update    = $posted_data['slide_update'];
                    $slide_id        = $posted_data['slide_id'];


                    //do the update stuff
                    if( $posted_data['slide_update'] == 1 ) {
                        $update_type_msg = 'updated';

                        $old_slides_set[$slide_id] = array(
                            'before_thumb'        => ( int )$posted_data['before_thumb'],
                            'after_thumb'         => ( int )$posted_data['after_thumb'],
                            'title'               => sanitize_text_field( $posted_data['title'] ),
                            'caption_before'      => sanitize_text_field( $caption_before ),
                            'caption_after'       => sanitize_text_field( $caption_after )
                        );

                        update_option( WPBAS_NAME_SPACE.$slider_name, $old_slides_set );

                    } else { //do the insert stuff
                        $update_type_msg = 'added';

                        if ( "" == $old_slides_set ) {
                            $result = add_option( WPBAS_NAME_SPACE.$slider_name, $slider_set );
                        } else {
                            array_unshift( $old_slides_set, $slide_info );
                            $result = update_option( WPBAS_NAME_SPACE.$slider_name, $old_slides_set );
                        }
                    }

                    $status         = 'success';
                    $msg            = 'Slides '. $update_type_msg .' successfully!';
                } else {
                    $status         = 'error';
                    $msg            = 'Caption and Images are required for slider.';
                    
                }

                $json_data = array(
                    "status"        =>  $status,
                    "msg"           =>  __( $msg, 'wp-before-after-slider' ),
                    "slider_name"   => $slider_name
                );

                wp_send_json( $json_data );

            }

        }



        /**
         * Get all Slides of a particular slider
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_get_all_slides_ajax() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }
            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['security'], 'wp_bas_nonce' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';

    
                $data_fetch_type = ( isset( $posted_data['slider_type'] ) && $posted_data['slider_type'] ) ?  esc_attr( $posted_data['slider_type'] ) : 'all';

               
                $slider_name = ( isset( $posted_data['slider_name'] ) && $posted_data['slider_name'] ) ?  esc_attr( $posted_data['slider_name'] ) : '';


                //Get slides data
                if( !empty( $slider_name ) ) {
                    $all_slides = get_option( WPBAS_NAME_SPACE.$slider_name );
                    $all_slides = maybe_unserialize( $all_slides );

                    $html_body_start    = '<ul class="wpbas-slides-list">';
                    $html_body_end      = '</ul>';

                    if( isset( $all_slides ) && ! empty( $all_slides ) ) {

                        $html_body = '';
                        $count = 0;
                        
                        foreach( $all_slides as $key => $slide ) {
                            
                            if( is_numeric( $slide['before_thumb'] ) ) {
                                $slide_before_img = wp_get_attachment_image_src( $slide['before_thumb'], 'full' );
                                $slide_before_img = $slide_before_img[0];
                            }

                            if( is_numeric( $slide['after_thumb'] ) ) {
                                $slide_after_img = wp_get_attachment_image_src( $slide['after_thumb'], 'full' );
                                $slide_after_img = $slide_after_img[0];
                            }

                            $caption_before     = esc_attr( $slide['caption_before'] );
                            $caption_after      = esc_attr( $slide['caption_after'] );

                            $before_thumb_id    = esc_attr( $slide['before_thumb'] );
                            $after_thumb_id     = esc_attr( $slide['after_thumb'] );

                            $slide_json_data = json_encode (array(
                                'title'              => esc_attr( $slide['title'] ),
                                'caption_before'     => $caption_before,
                                'caption_after'      => $caption_after,
                                'before_thumb_id'    => $before_thumb_id,
                                'after_thumb_id'     => $after_thumb_id,
                                'slide_before_img'   => $slide_before_img,
                                'slide_after_img'    => $slide_after_img
                            ));

                            $html_body .= '<li id="wpbas_item_'.$count.'">
                                    <textarea name="wpbas_slide_json_data" class="wpbas-slide-json-data" cols="30" rows="10" style="display: none;">'. sanitize_textarea_field( $slide_json_data ) .'</textarea>

                                    <h3 class="wpbase-slide-title">'. esc_attr( $slide['title'] ) .'</h3>
                                    <div class="wpbas-thumb-row">

                                        <div class="thumb-wrap left-column">
                                            <img src="'. esc_url( $slide_before_img) .'" alt="'. $caption_before .'"/>
                                            <span>'. $caption_before .'</span>
                                        </div>

                                        <div class="thumb-wrap right-column">
                                            <img src="'. esc_url( $slide_after_img) .'" alt="'. $caption_before .'" />
                                            <span>'. $caption_after .'</span>
                                        </div>

                                        <div class="wpbas-actions">
                                            <a onclick="wpbas_app.getSingleSlidesByID( '.$count.' ); return false;"  class="wpbas-icon-edit wpbas-icon-btn" href="#" title="' . __( "Edit", "wp-before-after-slider" ) . '"></a>
                                            <a onclick="wpbas_app.deleteSlidesByID( '.$count.' ); return false;" class="wpbas-icon-delete wpbas-icon-btn" href="#" title="' . __( "Delete", "wp-before-after-slider" ) . '"></a>
                                        </div>
                                    </div>
                                </li>';


                            $count++;
                        }
                        
                        $msg         = 'Data fetched successfully!';
                        $status      = 'success';
                        $data        = $html_body_start . $html_body . $html_body_end;

                    } else {
                        $html_body = '<tr>
                            <td colspan="4">
                                <div>
                                    <p class="wpbas-error">'. __( "No results found.", "wp-before-after-slider" ) .'</p>
                                </div>
                            </td>
                        </tr>';
                        $msg         = 'No sliders found.';
                        $status      = 'error';
                        $data        = $html_body_start . $html_body . $html_body_end;
                    }


                } else {
                    $msg         = 'Slider name is not correct.';
                    $status      = 'error';
                    $data        = '';
                }

                
                $json_data = array(
                    "status"     => $status,
                    "msg"        => __( $msg, 'wp-before-after-slider' ),
                    "data"       => $data
                );

                wp_send_json( $json_data );
                
            }

        }


        /**
         * Get all Sliders
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_get_all_sliders() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }
            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['security'], 'wp_bas_nonce' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';
                $sliders        = get_option( 'wpbaslider' );

                if( $posted_data['get_sliders'] == 'yes' && ! empty( $sliders ) ) {

                    $all_sliders = maybe_unserialize( $sliders );

                    $html_body = '';
                    if( !empty( $all_sliders ) ) {
                        $count = 0;
                        foreach( $all_sliders as $key => $slider ) {
                            $count++;
                            $html_body .= '<tr>
                                <td>' . esc_html( $count ) . '</td>
                                <td>' . esc_html( $slider ) . '</td>
                                <td><pre>[wpbaslider name="' . esc_html( $slider ) . ']</pre></td>
                                <td>
                                    <div class="wpbas-actions wpbas-actions-list">
                                        <a onclick="wpbas_app.updateSliderSetting(\' '. esc_attr( $slider ).' \' ); return false;" class="wpbas-icon-setting wpbas-icon-btn" href="#" title="'. esc_attr( 'Setting', 'wp-before-after-slider' ).'"></a>
                                        <a class="wpbas-icon-edit wpbas-icon-btn" href="'. esc_url( admin_url( 'admin.php?page=add-wpbaslider&slider='.$slider ) ).'" title="' . esc_attr( 'Edit', 'wp-before-after-slider' ). '"></a>
                                        <a onclick="wpbas_app.deleteSlidersByName(\' '. esc_attr( $slider ).' \' ); return false;" class="wpbas-icon-delete wpbas-icon-btn" href="#" title="'. esc_attr( '1 Delete', 'wp-before-after-slider' ).'"></a>
                                    </div>
                                </td>
                            </tr>';
                        }
                    }
                    
                    $msg         = 'Data fetched successfully!';
                    $status      = 'success';
                    $data        = $html_body;

                } else {
                    $html_body = '<tr>
                        <td colspan="4">
                            <div>
                                <p style="color: #d46868;">'. __( "No results found.", "wp-before-after-slider" ) .'</p>
                            </div>
                        </td>
                    </tr>';
                    $msg         = 'No data found.';
                    $status      = 'error';
                    $data        = $html_body;
                }

                $json_data = array(
                    "status"     => $status,
                    "msg"        => __( $msg, 'wp-before-after-slider' ),
                    "data"       => $data
                );

                wp_send_json( $json_data );

            }

        }


        /**
         * Create Slider
         *
         * @since  1.0.0
         * @return void
         */
        public function wpbas_create_slider_ajax() {

             // Check the WordPress Ajax request
            if ( ! wp_doing_ajax() ) {
                wp_die();
            }
            if( ! empty( $_POST ) ) {

                if ( ! wp_verify_nonce( $_POST['security'], 'wp_bas_nonce' ) ) {
                  die( 'Security check' );
                }

                $posted_data    = $_POST;
                $posted_data    = stripslashes_deep( $posted_data );
                $status         = 'error';
                $slider_name    = $posted_data['slider_name'];
                if( $slider_name ) {

                    //Replace white space to underscore and remove '_' from last 
                    $slider_name = preg_replace('/\s+/', '_', $slider_name);

                    $slider_name = rtrim( $slider_name, '_' );
                    $slider_name = ltrim( $slider_name, '_' );

                    $old_sliders = get_option( 'wpbaslider' );
                    $old_sliders = maybe_unserialize( $old_sliders );
                    $slider_name_array = array(sanitize_text_field( $slider_name ) );
                    
                    if( in_array( sanitize_text_field( $slider_name ), $old_sliders ) ) {
                        $status = 'error';
                    } else if( $old_sliders ) {
                        $udpated_sliders = array_merge( $slider_name_array, $old_sliders );
                        $udpated_sliders = maybe_serialize( $udpated_sliders );
                        $status = 'success';
                    } else {
                        $udpated_sliders = maybe_serialize( $slider_name_array );
                        $status = 'success';
                    }


                    if( $status == 'error' ) {
                        $msg            = '<strong>'.$slider_name.' </strong> &nbsp; name is already exists! Please try with some other Slider name.';
                        $status         =  'eror';
                        $slider_name    =  sanitize_text_field( $slider_name );
                    } else {
                        update_option( 'wpbaslider', $udpated_sliders );
                        $msg            = 'Slider created successfully!';
                        $status         =  'success';
                        $slider_name    =  sanitize_text_field( $slider_name );
                    }

                } else {
                    $msg            = 'Please enter the slider name.';
                    $slider_name    = '';
                }

                $json_data = array(
                    "status"        =>  $status,
                    "msg"           =>  __( $msg, 'wp-before-after-slider' ),
                    "slider_name"   =>  $slider_name
                );

                wp_send_json( $json_data );

            }

        }


        /**
         * Plugin localization
         *
         * @since  1.0.0
         * @return void
         */
        public function load_textdomain() {

            $lang_dir = HFWE_DIR . '/languages/';
            load_plugin_textdomain( 'wp-before-after-slider', false, $lang_dir );
        
        }


        /**
         * Remove hooks for admin notices 
         *
         * WP Before After slider admin does not play nice with admin notices, so we use a series of steps to remove most of them, sadly can not beat them all.
         *
         * @since 1.0.0
         * @uses "admin_head" action
         */
        public static function remove_notice_actions() {
            $screen = get_current_screen();
            if ( $screen->base == 'toplevel_page_wpbaslider' 
                || $screen->base == 'wp-ba-slider_page_add-wpbaslider' ) {
                remove_all_actions( 'admin_notices' );
                remove_all_actions( 'network_admin_notices' );
                remove_all_actions( 'user_admin_notices' );
                remove_all_actions( 'all_admin_notices' );
            } else {
                return;
            }

        }


        /**
         * Enqueue admin scripts
         *
         * @since  1.0.0
         * @return void
         */
        public function enqueue_admin_scripts( $hook ) {
            // print_r($hook); exit;
            if( $hook == 'toplevel_page_wpbaslider' 
                || $hook == 'wp-ba-slider_page_add-wpbaslider' ) {

                //add thick box
                add_thickbox();
                wp_enqueue_script('media-upload');
                
                wp_enqueue_media();

                wp_enqueue_style( 'wpbas-admin-style',  plugin_dir_url( __FILE__ ) . 'admin/assets/css/wpbas-admin.css', WP_BAS_VERSION );

                wp_enqueue_script( 'wpbas-admin-js',  plugin_dir_url( __FILE__ ) . 'admin/assets/js/wpbas-admin.js', WP_BAS_VERSION );

                // Set Ajax URL, Nonce and Loading message
                wp_localize_script( 'wpbas-admin-js', 'WPBAS', array(
                    "ajaxurl"                   => admin_url( 'admin-ajax.php' ),
                    "ajax_nonce"                => wp_create_nonce( 'wp_bas_nonce' ),
                    "slider_name_required"      =>  __( 'Please enter slider name.', 'wp-before-after-slider' ),
                    "reload_page_msg"           => __( 'Please reload the page and try again.', 'wp-before-after-slider' ),
                ));
            } else {
                return;
            }


        }


        /**
         * Create WP BAS Admin menu  
         *
         * @since  1.0.0
         * @return void
         */
        public function wp_bas_admin_menu() {

            add_menu_page(
                __( 'WP BA Slider', 'wp-before-after-slider' ),
                __( 'WP BA Slider', 'wp-before-after-slider' ),
                'manage_options', 
                'wpbaslider', 
                array( $this, 'wpbas_admin_all_slider' ),
                35
            );
            
            add_submenu_page(
                'wpbaslider', 
                __( 'Slider', 'wp-before-after-slider' ),
                __( 'All Sliders', 'wp-before-after-slider' ),
                'manage_options', 
                'wpbaslider',
                array( $this, 'wpbas_admin_all_slider' )
            );

            add_submenu_page(
                'wpbaslider', 
                __( 'Add/Edit Slider', 'wp-before-after-slider' ),
                __( 'Add/Edit Slider', 'wp-before-after-slider' ),
                'manage_options', 
                'add-wpbaslider', 
                array( $this, 'wpbas_admin_add_slider' )
            );
            // add_submenu_page(
            //     'wpbaslides', 
            //     __( 'Export/Import', 'wp-before-after-slider' ),
            //     __( 'Export/Import', 'wp-before-after-slider' ),
            //     'manage_options', 
            //     array( $this, 'wpbas_admin_export_import_page' )
            // );



        }

        

        /**
         * Slider lists template
         * 
         * @since 1.0.0
         * @return void
         */
        public function wpbas_admin_all_slider() {

            $sliders = get_option( 'wpbaslider' );
            $all_sliders = maybe_unserialize( $sliders );

            require_once( WP_BAS_INC_DIR . "/admin/templates/slider-list.php" );

        }

        /**
         * Include add slder template
         *
         * @since 1.0.0
         * @return void
         */
        public function wpbas_admin_add_slider() {

            require_once( WP_BAS_INC_DIR . "/admin/templates/slider-edit.php" );
        }


    }

    /**
     * Calling class using 'get_instance()' method
     */
    WP_Before_After_Slider::get_instance();

endif;


