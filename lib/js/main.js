jQuery(function ($) {
  'use strict';

  var json;
  var tracks = [];

  $.ajax({
    type: 'GET',
    async: false,
    url: 'tracks.php',
    success: function(data) {
      json = data;
    }
  });

  for (var i = 0; i < json.tracks.length; i++) {
    var number = i + 1;
    tracks.push({
      track: number,
      name: json.tracks[i]["filename"].slice(0, -4),
      duration: json.tracks[i]["duration"],
      file: json.tracks[i]["filename"].slice(0, -4)
    });
  }

  var supportsAudio = !!document.createElement('audio').canPlayType;
  if (supportsAudio){
      var player = new Plyr('#audio1', {
          controls: [
          'restart',
          'play',
          'progress',
          'current-time',
          'duration',
          'mute',
          'volume'
        ]
      });

      var index = 0,
      playing = false,
      mediaPath = json.url,
      extension = '',
      buildPlaylist = $(tracks).each(function(key, value) {
          var trackNumber = value.track,
          trackName = value.name,
          trackDuration = value.duration;
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
      audio = $('#audio1').on('play', function() {
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
      btnPrev = $('#btnPrev').on('click', function() {
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
      btnNext = $('#btnNext').on('click', function() {
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
      li = $('#plList li').on('click', function() {
          var id = parseInt($(this).index());
          if (id !== index) {
              playTrack(id);
          }
      }),
      loadTrack = function(id) {
          $('.plSel').removeClass('plSel');
          $('#plList li:eq(' + id + ')').addClass('plSel');
          npTitle.text(tracks[id].name);
          index = id;
          audio.src = mediaPath + tracks[id].file + extension;
      },
      playTrack = function(id) {
          loadTrack(id);
          audio.play();
      };
      extension = audio.canPlayType('audio/mpeg') ? '.mp3' : audio.canPlayType('audio/ogg') ? '.ogg' : '';
      loadTrack(index);
  } else {
      $('.column').addClass('hidden');
      var noSupport = $('#audio1').text();
      $('.container').append('<p class="no-support">' + noSupport + '</p>');
  }
});
