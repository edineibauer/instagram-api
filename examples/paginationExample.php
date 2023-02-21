<?php

use InstagramAPI\Request\Media;

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

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}


try {
    // Get the UserPK ID for "natgeo" (National Geographic).
    $userId = INSTAGRAM_ID;

    // Starting at "null" means starting at the first page.
    $maxId = null;
        // Request the page corresponding to maxId.
        $response = $ig->timeline->getTimelineFeed($userId, $maxId);
        foreach ($response->getFeedItems() as $item) {
            var_dump($item);
        }
        die;
       
        // In this example we're simply printing the IDs of this page's items.
        foreach ($response->getItems() as $i => $item) {
            $ig->media->like($item->getId());
            die;
        }

        // Now we must update the maxId variable to the "next page".
        // This will be a null value again when we've reached the last page!
        // And we will stop looping through pages as soon as maxId becomes null.
        $maxId = $response->getNextMaxId();

        // Sleep for 5 seconds before requesting the next page. This is just an
        // example of an okay sleep time. It is very important that your scripts
        // always pause between requests that may run very rapidly, otherwise
        // Instagram will throttle you temporarily for abusing their API!
//        echo "Sleeping for 5s...\n";
//        sleep(5);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
