<?php
require __DIR__ . '/vendor/autoload.php';

use InstagramGrab\Model\Instagram;

$username = "tvori_so_mnoj";
$count_posts = 2;

$run=new Instagram();

$run->getFollowers("$username"); //get followers for username
$run -> getPostLikes("$username", $count_posts); //get the likes of users for the (count) news