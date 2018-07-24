<?php

require __DIR__ . '/vendor/autoload.php';

use InstagramGrab\Model\Instagram;

$username = $argv[1];
$count_posts = $argv[2];

$run=new Instagram();

$run -> getPostLikes("$username", $count_posts); //get the likes of users for the (count) news