<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$domain = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'];
$tracks_array = json_encode(array_map('basename', glob("mp3/*.mp3")));
$tracks = array('url' => $domain, 'tracks' => $tracks_array);

http_response_code(200);
echo json_encode($tracks);
