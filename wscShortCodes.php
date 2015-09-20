<?php

function userDetailsShortCode()
{
    $wscClass = new \WscStravaApi\StravaUser();
    $userDetails = $wscClass->getUserDetails();
    if ($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}

add_shortcode('wscUserDetails', 'userDetailsShortCode');

function userActivitiesShortCode()
{
    $wscClass = new \WscStravaApi\StravaUser();
    $userActivities = $wscClass->getUserActivityStream();
    if ($userActivities) {
        $rideId = $_GET['rideId'];
        if ($rideId) {
            $userActivity = $wscClass->getUserActivity($rideId);
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <ul>
                            <li><?php echo $userActivity->name; ?></li>
                            <ul>
                                <ol><?php echo $userActivity->distance; ?></ol>
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
        } else {
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <?php
                        foreach ($userActivities as $activity) {
                            ?>
                            <ul>
                                <li><?php echo $activity->name; ?></li>
                                <ul>
                                    <ol><?php echo $activity->distance; ?></ol>
                                </ul>
                            </ul>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        if (isset($_SESSION['wscOAuthResponse'])) {
            echo 'You don\'t currently have activities.';
        }
    }
}

add_shortcode('wscUserActivities', 'userActivitiesShortCode');

function themeWidgetShortCode()
{
    $wscClass = new \WscStravaApi\StravaUser();
    $userDetails = $wscClass->getUserDetails();
    if ($userDetails) {
        echo $userDetails->firstname . ', ' . $userDetails->lastname;
    }
}

add_shortcode('wscWidgetShortCode', 'themeWidgetShortCode');