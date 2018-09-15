<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <script src='https://content.jwplatform.com/libraries/i511f6Xb.js'></script>
    <title></title>
    <style>
    body,html{margin: 0;height: 100%;}
    #player{height: 100% !important;}
    </style>
  </head>
  <body>
      <h1>test!</h1>
      <p>test!!!</p>
    <div id="player">Loading the player...</div>
    <script>
    // Setup the player
    const player = jwplayer('player').setup({
      file: 'https://storage.googleapis.com/otrohost/peliculas/padrino-1/master.m3u8'});
  </script>
  </body>
</html>
