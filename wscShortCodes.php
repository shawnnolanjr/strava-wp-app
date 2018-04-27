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
        $isRideId = isset($_GET['rideId']);
        if ($isRideId) {
            $rideId = $_GET['rideId'];
            $rideIdInt = is_numeric($rideId);
            if ($rideIdInt) {
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
            }
        } else {
            //wsc_api_activities_dropdown
            $activitiesId = get_theme_mod('wsc_api_user_activities');
            $activityLink = get_permalink( $activitiesId );
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <?php
                        foreach ($userActivities as $activity) {
                            ?>
                            <ul>
                                <li><a href="<?php echo $activityLink; ?>?rideId=<?php echo $activity->id; ?>"><?php echo $activity->name; ?></a></li>
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