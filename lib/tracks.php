<?php
include './class/mp3file.class.php';

$domain = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'];
$directory = './mp3/';
$folder_tracks = array_map('basename', glob("mp3/*.mp3"));
$track_list = array();

foreach ($folder_tracks as $value) {
  $track = $directory . $value;
  $mp3file = new MP3File($track);
  $duration2 = $mp3file->getDuration();
  $track_list[] = array(
    'filename' => $value,
    'duration' => MP3File::formatTime($duration2)
  );
}

$response = array('url' => $domain . ltrim($directory, '.'),
                  'tracks' => $track_list);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
http_response_code(200);
echo json_encode($response);
