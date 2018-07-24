<?php

require __DIR__ . '/vendor/autoload.php';

use InstagramGrab\Model\Instagram;

$username = $argv[1];

$run=new Instagram();

$run->getFollowers("$username"); //get followers for username