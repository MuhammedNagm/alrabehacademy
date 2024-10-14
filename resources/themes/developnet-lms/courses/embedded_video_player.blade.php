<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Plyr - A simple, customizable HTML5 Video, Audio, YouTube and Vimeo player</title>
    <meta
            name="description"
            property="og:description"
            content="A simple HTML5 media player with custom controls and WebVTT captions."
    />
    <meta name="author" content="Sam Potts" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />

</head>
<body>
<div class="grid">

    <div class="plyr__video-embed js-player">
        <iframe
                src="{{$link}}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                allowfullscreen
                allowtransparency
                allow="autoplay"

        ></iframe>
    </div>


</div>

<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<script>
    const player = new Plyr('#player');
</script>

</body>
</html>