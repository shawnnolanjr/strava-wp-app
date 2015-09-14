<?php

function userDetailsShortCode() {
    $wscClass = new \WscStravaApi\StravaConnectApi();
    $userDetails = $wscClass->getUserDetails();
    //print_r($userDetails);
    if($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}
add_shortcode('wscUserDetails', 'userDetailsShortCode');