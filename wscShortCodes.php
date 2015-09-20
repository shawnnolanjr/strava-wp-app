<?php

function userDetailsShortCode() {
    $wscClass = new \WscStravaApi\StravaUser();
    $userDetails = $wscClass->getUserDetails();
    if($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}
add_shortcode('wscUserDetails', 'userDetailsShortCode');

function userActivitiesShortCode() {
    $wscClass = new \WscStravaApi\StravaUser();
    $userActivities = $wscClass->getUserActivityStream();
    if($userActivities) {
        echo '<pre>';
        print_r($userActivities);
        echo '</pre>';
    } else {
        if(isset($_SESSION['wscOAuthResponse'])) {
            echo 'You don\'t currently have activities.';
        }
    }
}
add_shortcode('wscUserActivities', 'userActivitiesShortCode');

function themeWidgetShortCode() {
    $wscClass = new \WscStravaApi\StravaUser();
    $userDetails = $wscClass->getUserDetails();
    if($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}
add_shortcode('wscWidgetShortCode', 'themeWidgetShortCode');