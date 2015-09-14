<?php
function wsc_theme_customizer( $wp_customize ) {
    /*
     * Customize Instagram info
     */
    $wp_customize->add_section( 'wsc_api_section' , array(
        'title'       => __( 'Strava API section', 'wsc' ),
        'priority'    => 20,
        'description' => 'Insert your Strava API info',
    ) );

    $wp_customize->add_setting( 'wsc_api_client_id' );
    $wp_customize->add_control('wsc_api_client_id', array(
        'label'    => __( 'Strava client ID', 'wsc' ),
        'section'  => 'wsc_api_section',
        'settings' => 'wsc_api_client_id',
    ) );

    $wp_customize->add_setting( 'wsc_api_access_token' );
    $wp_customize->add_control('wsc_api_access_token', array(
        'label'    => __( 'Strava access token', 'wsc' ),
        'section'  => 'wsc_api_section',
        'settings' => 'wsc_api_access_token',
    ) );

    $wp_customize->add_setting('wsc_api_client_secret');
    $wp_customize->add_control('wsc_api_client_secret', array(
        'label' => __('Strava secret id'),
        'section' => 'wsc_api_section',
        'settings' => 'wsc_api_client_secret'
    ));
}
add_action( 'customize_register', 'wsc_theme_customizer' );