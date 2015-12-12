	<?php
global $data, $social_position;
if ($data['social_media'] == 1) {
    $class = "";
    if ($social_position[$data['social_position']] == 'right')
        $class = " fixed-right";
    else if ($social_position[$data['social_position']] == 'left')
        $class = " fixed-left";

    echo "<div class='tt-share-widget clearfix $class' id='sharing-1'>";
        if ($data['social_twitter'] == 1)
            echo "<span class='st_twitter_hcount' displayText='Tweet'></span>";
        if ($data['social_googlePlus'] == 1)
            echo "<span class='st_plusone_hcount' displayText='Google +1'></span>";
        if ($data['social_linkedin'] == 1)
            echo "<span class='st_linkedin_hcount' displayText='LinkedIn'></span>";
        if ($data['social_pinterest'] == 1)
            echo "<span class='st_pinterest_hcount' displayText='Pinterest'></span>";
        if ($data['social_facebook'] == 1)
            echo "<span class='st_facebook_hcount' displayText='Facebook'></span>";
        if ($data['social_email'] == 1)
            echo "<span class='st_email_hcount' displayText='Email'></span>​";
        if ($data['social_stumbleupon'] == 1)
            echo "<span class='st_stumbleupon_hcount' displayText='StumbleUpon'></span>​";

    echo "</div>";
}
?>
