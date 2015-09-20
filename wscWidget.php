<?php
namespace wsc\widget;

use WscStravaApi\StravaUser;

class WSC_Widget_Class extends \WP_Widget {

    function __construct() {
        parent::__construct( 'baseID', 'WP Strava Widget' );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if($_SESSION['wscOAuthResponse']->access_token) {
            if ( ! empty( $instance['loggedInTitle'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['loggedInTitle'] ). $args['after_title'];
//                echo '<br/>';
//                $stravaClass = new StravaUser();
//                $stravaClass->stravaConnectLogout();
            }
        } else {
            if ( ! empty( $instance['loggedOutTitle'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['loggedOutTitle'] ). $args['after_title'];
            }
        }

        echo do_shortcode( '[wscWidgetShortCode]');

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $loggedOutTitle = ! empty( $instance['loggedOutTitle'] ) ? $instance['loggedOutTitle'] : __( 'Logged out text', 'text_domain' );
        $loggedInTitle = ! empty( $instance['loggedInTitle'] ) ? $instance['loggedInTitle'] : __( 'Logged in text', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'loggedOutTitle' ); ?>"><?php _e( 'Logged out title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'loggedOutTitle' ); ?>" name="<?php echo $this->get_field_name( 'loggedOutTitle' ); ?>" type="text" value="<?php echo esc_attr( $loggedOutTitle ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'loggedInTitle' ); ?>"><?php _e( 'Logged in title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'loggedInTitle' ); ?>" name="<?php echo $this->get_field_name( 'loggedInTitle' ); ?>" type="text" value="<?php echo esc_attr( $loggedInTitle ); ?>">
        </p>
    <?php
    }
}

add_action( 'widgets_init', function(){
    register_widget( 'wsc\widget\WSC_Widget_Class' );
});