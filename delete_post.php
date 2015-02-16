<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 16.02.15
 * Time: 15:23
 */
include("retwis.php");

if (!isLoggedIn()) {
    header("Location:index.php");
    exit;
}

$postid = 5;
$r      = redisLink();
foreach ($_POST as $key => $value) {

    if ($value != "delete") {
        $postid = $key;
    }
}
$userID = $User['id'];

if ($postid) {
    $r->expire("post:$postid", -1);

    $followers   = $r->zrange("followers:" . $User['id'], 0, -1);
    $followers[] = $userID; /* Remove the post from our own posts too */

    foreach ($followers as $fid) {
        $r->lrem("posts:$fid", 1, $postid);
        $r->lrem("selfPosts:$fid", 1, $postid);
    }

    //remove comments
    $addedComments = $r->lrange("comments:$postid", 0, -1);

    foreach ($addedComments as $commentid) {
        $r->expire("comment:$commentid", -1);
        $r->lrem("comments:$postid", 1, $commentid);
    }
}

$refferer = $_SERVER['HTTP_REFERER'];
header("Location:" . $refferer);