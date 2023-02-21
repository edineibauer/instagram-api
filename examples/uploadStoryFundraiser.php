<?php

use InstagramAPI\Request\Story;

set_time_limit(0);
date_default_timezone_set('UTC');

require '../../../../_config/config.php';
\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
\InstagramAPI\Utils::$ffmpegBin = 'D:/wamp64/bin/ffmpeg';
\InstagramAPI\Utils::$ffprobeBin = 'D:/wamp64/bin/ffprobe';

/////// CONFIG ///////
$username = INSTAGRAM_USER;
$password = INSTAGRAM_PASS;
$debug = false;
$truncatedDebug = false;
//////////////////////

/////// MEDIA ////////
$photoFilename = '';
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

//Get the first recommended charity.
$charityUser = $ig->story->getCharities()->getSearchedCharities()[0];

// Now create the metadata array:
$metadata = [
    'story_fundraisers' => $ig->story->createDonateSticker($charityUser),
];

try {
    // This example will upload the image via our automatic photo processing
    // class. It will ensure that the story file matches the ~9:16 (portrait)
    // aspect ratio needed by Instagram stories. You have nothing to worry
    // about, since the class uses temporary files if the input needs
    // processing, and it never overwrites your original file.
    //
    // Also note that it has lots of options, so read its class documentation!
    $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);
    $ig->story->uploadPhoto($photo->getFile(), $metadata);

    // NOTE: Providing metadata for story uploads is OPTIONAL. If you just want
    // to upload it without any tags/location/caption, simply do the following:
    // $ig->story->uploadPhoto($photo->getFile());
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
