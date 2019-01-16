<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>MP3 Player</title>
  <link rel='stylesheet' href='https://cdn.plyr.io/3.4.7/plyr.css'>
  <link rel="stylesheet" href="./css/main.css">
</head>
<body>
  <div class="container">
    <div class="column add-bottom">
        <div id="mainwrap">
            <div id="nowPlay">
                <span id="npAction">Paused...</span><span id="npTitle"></span>
            </div>
            <div id="audiowrap">
                <div id="audio0">
                    <audio id="audio1" preload controls>Your browser does not support HTML5 Audio!</audio>
                </div>
                <div id="tracks">
                    <a id="btnPrev">&larr;</a><a id="btnNext">&rarr;</a>
                </div>
            </div>
            <div id="plwrap">
                <ul id="plList"></ul>
            </div>
        </div>
    </div>
  </div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/html5media/1.1.8/html5media.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/plyr/3.4.7/plyr.min.js'></script>
  <script>
  jQuery(function ($) {
    'use strict';

    var mp3tracks = <?php
    echo json_encode(array_map('basename', glob("mp3/*.mp3")));
    ?>;
    var tracks = [];
    var tracks_len = mp3tracks.length;
    for (var i = 0; i < tracks_len; i++) {
      var number = i + 1;
      tracks.push({
        track: number,
        name: mp3tracks[i].slice(0, -4),
        duration: '0:00',
        file: mp3tracks[i].slice(0, -4)
      });
    }
    //console.log(tracks);

    var supportsAudio = !!document.createElement('audio').canPlayType;
    if (supportsAudio) {
        // initialize plyr
        var player = new Plyr('#audio1', {
            controls: [
            'restart',
            'play',
            'progress',
            'current-time',
            'duration',
            'mute',
            'volume'] });

        // initialize playlist and controls
        var index = 0,
        playing = false,
        mediaPath = '<?php echo $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . '/mp3/'; ?>',
        extension = '',
        //tracks = [{
        //    "track": 1,
        //    "name": "Parcels-Overnight",
        //    "duration": "3:39",
        //    "file": "Parcels-Overnight" },
        //{
        //    "track": 2,
        //    "name": "Parcels-Gamesofluck",
        //    "duration": "5:48",
        //    "file": "Parcels-Gamesofluck" }],

        buildPlaylist = $(tracks).each(function (key, value) {
            var trackNumber = value.track,
            trackName = value.name,
        //    trackDuration = value.duration;
            trackDuration = '';
            if (trackNumber.toString().length === 1) {
                trackNumber = '0' + trackNumber;
            }
            $('#plList').append('<li> \
                    <div class="plItem"> \
                        <span class="plNum">' + trackNumber + ': </span> \
                        <span class="plTitle">' + trackName + '</span> \
                        <span class="plLength">' + trackDuration + '</span> \
                    </div> \
                </li>');
        }),
        trackCount = tracks.length,
        npAction = $('#npAction'),
        npTitle = $('#npTitle'),
        audio = $('#audio1').on('play', function () {
            playing = true;
            npAction.text('Now Playing...');
        }).on('pause', function () {
            playing = false;
            npAction.text('Paused...');
        }).on('ended', function () {
            npAction.text('Paused...');
            if (index + 1 < trackCount) {
                index++;
                loadTrack(index);
                audio.play();
            } else {
                audio.pause();
                index = 0;
                loadTrack(index);
            }
        }).get(0),
        btnPrev = $('#btnPrev').on('click', function () {
            if (index - 1 > -1) {
                index--;
                loadTrack(index);
                if (playing) {
                    audio.play();
                }
            } else {
                audio.pause();
                index = 0;
                loadTrack(index);
            }
        }),
        btnNext = $('#btnNext').on('click', function () {
            if (index + 1 < trackCount) {
                index++;
                loadTrack(index);
                if (playing) {
                    audio.play();
                }
            } else {
                audio.pause();
                index = 0;
                loadTrack(index);
            }
        }),
        li = $('#plList li').on('click', function () {
            var id = parseInt($(this).index());
            if (id !== index) {
                playTrack(id);
            }
        }),
        loadTrack = function (id) {
            $('.plSel').removeClass('plSel');
            $('#plList li:eq(' + id + ')').addClass('plSel');
            npTitle.text(tracks[id].name);
            index = id;
            audio.src = mediaPath + tracks[id].file + extension;
        },
        playTrack = function (id) {
            loadTrack(id);
            audio.play();
        };
        extension = audio.canPlayType('audio/mpeg') ? '.mp3' : audio.canPlayType('audio/ogg') ? '.ogg' : '';
        loadTrack(index);
    } else {
        // boo hoo
        $('.column').addClass('hidden');
        var noSupport = $('#audio1').text();
        $('.container').append('<p class="no-support">' + noSupport + '</p>');
    }
  });
  </script>
</body>
</html>
