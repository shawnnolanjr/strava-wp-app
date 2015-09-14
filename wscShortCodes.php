<?php

function userDetailsShortCode() {
    $wscClass = new \WscStravaApi\StravaConnectApi();
    $userDetails = $wscClass->getUserDetails();
    if($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}
add_shortcode('wscUserDetails', 'userDetailsShortCode');

function themeWidgetShortCode() {
    $wscClass = new \WscStravaApi\StravaConnectApi();
    $userDetails = $wscClass->getUserDetails();
    if($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}
add_shortcode('wscWidgetShortCode', 'themeWidgetShortCode');