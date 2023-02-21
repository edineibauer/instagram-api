<?php

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
    $loginResponse = $ig->login($username, $password);

    if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
        $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();

        // The "STDIN" lets you paste the code via terminal for testing.
        // You should replace this line with the logic you want.
        // The verification code will be sent by Instagram via SMS.
        $verificationCode = trim(fgets(STDIN));
        $ig->finishTwoFactorLogin($username, $password, $twoFactorIdentifier, $verificationCode);
    }
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
