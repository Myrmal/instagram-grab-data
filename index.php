<?php

require __DIR__ . '/vendor/autoload.php';

use InstagramGrab\Model\Instagram;

$run=new Instagram();

$run->getFollowers("tvori_so_mnoj"); //get followers for username

$run->getPostLikes("tvori_so_mnoj", 30); //get the likes of users for the (count) news