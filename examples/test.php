<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:42
 */

date_default_timezone_set("PRC");

require_once __DIR__ . '/../vendor/autoload.php';

$live = new \AliLive\Task(
            '',
    '42784.livepush.myqcloud.com',
    'live.pan233.com',
    '123456789',
    '987654321',
    'a');

$live->liveChannelSetStatus(1,0);

