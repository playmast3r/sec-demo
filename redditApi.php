<?php
//using Memcached as Memcache has issue in PHP 7.0+
$mc = new Memcached();
$mc->addServer("localhost", 11211);

//check if titles are present in memcache
if($mc->get("titles") != false) {
    $titles = json_decode($mc->get("titles"));
}
//if not present then call API and put titles in memcache
else {
    $url = "https://www.reddit.com/r/security/.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    if($resp === false) {
        die("Something went wrong!");
    }
    $resp = json_decode($resp);
    $data = $resp->data->children;
    $titles = array();
    foreach($data as $post) {
        $titles[] = $post->data->title;
    }
    //put titles in memcache
    $mc->set("titles", json_encode($titles));
    // set expiration time based on how frequent you want to update data from Reddit
    // I have put 60 mins
    $mc->touch("titles", 60 * 60);
}

//print titles
foreach($titles as $title){
    echo $title . "<br>";
}
?>