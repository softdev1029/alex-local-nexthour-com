<script type="text/javascript" src="/js/jwplayer-8.5.3/jwplayer.js"></script>
<script>jwplayer.key = 'MQfW+2mr0/dNGtifsJ1Ke/M/opNP0eBODJtsdYsQKos='; </script>

<script type="text/javascript">
    // Wishlist Js ( using Vuejs 2 )
    Vue.config.devtools = false;
    var app = new Vue({
        el: '#wishlistelement',
        data: {
            result: {
                id: '',
                type: '',
            },
        },
        methods: {
            addToWishList(id, type) {
                this.result.id = id;
                this.result.type = type;
                this.$http.post('<?php echo e(route('addtowishlist')); ?>', this.result).then((response) => {
                }).catch((e) => {
                    console.log(e);
                });
                this.result.item_id = '';
                this.result.item_type = '';
            }
        }
    });

    var jplayer;

    function playEpisodes(id, index) {
        var data = {
            id: id,
            type: 'S'
        };

        if ( index != undefined ) {
            data.index = index;
        }
        
        app.$http.post('<?php echo e(route("get_video_data")); ?>', data).then((response) => {
            if ( response.status == 200 ) {

                var positionTime = 0;
                var type = 'S';

                setTimeout(function () {
                    jplayer = jwplayer('my_video');
                    
                    jplayer.setup({
                        displaytitle: true,
                        playlist: response.data.links,
                        visualplaylist: true,
                        autostart: true,
                        controls: true,
                    });

                    jplayer.playlistItem(index != undefined ? index : response.data.index);
                    jplayer.setCurrentAudioTrack(response.data.track_no);
                    //jplayer.setCurrentCaptions(parseInt(response.data.track_no) + 1);
                    
                    jplayer.on('time', function(e) {
                        var currentIndex = jplayer.getPlaylistIndex();
                        var position = Math.floor(e.position);
                        if ( position > 0 && position % 30 == 0 && positionTime != parseInt(position / 30) ) {
                            app.$http.post('<?php echo e(route("store_time")); ?>', {
                                type: type,
                                id: id,
                                index: currentIndex,
                                time: position,
                                duration: jplayer.getDuration(),
                                track_no: jplayer.getCurrentAudioTrack(),
                            }).then((response) => {
                            }).catch((er) => {
                                console.log(er);
                            });

                            positionTime = parseInt(position / 30);
                        } else if ( position > 0 && position % 30 == 15 ) {
                            app.$http.post('<?php echo e(route("store_audio_track")); ?>', {
                                track_no: jplayer.getCurrentAudioTrack(),
                            }).then((response) => {
                            }).catch((er) => {
                                console.log(er);
                            });
                        }
                    });

                    jplayer.on('firstFrame', function() {
                        jplayer.setCurrentAudioTrack(response.data.track_no);

                        if ( response.data.time > 0 ) {
                            jplayer.seek(response.data.time);
                        }
                    });

                    jplayer.on('complete', function() {
                        app.$http.post('<?php echo e(route("store_time")); ?>', {
                            type: type,
                            id: id,
                            index: index,
                            end: 1,
                        }).then((response) => {
                        }).catch((er) => {
                            console.log(er);
                        });
                    });
                }.bind(this), 300);
            }
        }).catch((e) => {
            console.log(e);
        });

        $('.video-player').css({
            "visibility" : "visible",
            "z-index" : "99999",
        });

        $('body').css({
            "overflow": "hidden"
        });

        return false;
    }

    function playVideo(id, type) {
        if ( type == 'S' ) {
            playEpisodes(id, type);
            return false;
        }

    	var data = {
    		id: id,
    		type: type,
    	};

    	app.$http.post('<?php echo e(route("get_video_data")); ?>', data).then((response) => {
    		if ( response.status == 200 ) {

    			var positionTime = 0;
                var record_every_seconds = 15;

    			if ( response.data.links.length ) {

	    			setTimeout(function () {
	    				jplayer = jwplayer('my_video');
						
						jplayer.setup({
							displaytitle: true,
							image: response.data.image,
							sources: response.data.links,
							title: response.data.title,
							autostart: true,
							controls: true,
						});

                        jplayer.setCurrentAudioTrack(response.data.track_no);
                        //jplayer.setCurrentCaptions(parseInt(response.data.track_no) + 1);
						
						jplayer.on('time', function(e) {
							var position = Math.floor(e.position);
							if ( position > 0 && position % record_every_seconds == 0 && positionTime != parseInt(position / record_every_seconds) ) {
								app.$http.post('<?php echo e(route("store_time")); ?>', {
									type: type,
									id: id,
									time: position,
									duration: jplayer.getDuration(),
                                    track_no: jplayer.getCurrentAudioTrack(),
								}).then((response) => {
								}).catch((er) => {
						    		console.log(er);
						    	});

						    	positionTime = parseInt(position / record_every_seconds);
							} else if ( position > 0 && position % record_every_seconds == 0 ) {
                                app.$http.post('<?php echo e(route("store_audio_track")); ?>', {
                                    track_no: jplayer.getCurrentAudioTrack(),
                                }).then((response) => {
                                }).catch((er) => {
                                    console.log(er);
                                });
                            }
						});

						jplayer.on('firstFrame', function() {
                            jplayer.setCurrentAudioTrack(response.data.track_no);

						    if ( response.data.time > 0 ) {
						    	jplayer.seek(response.data.time);
						    }
						});

						jplayer.on('complete', function() {
							app.$http.post('<?php echo e(route("store_time")); ?>', {
								type: type,
								id: id,
								end: 1,
							}).then((response) => {
							}).catch((er) => {
					    		console.log(er);
					    	});
						});
	    			}.bind(this), 300);
	    		}
    		}
    	}).catch((e) => {
    		console.log(e);
    	});

    	$('.video-player').css({
    		"visibility" : "visible",
    		"z-index" : "99999",
    	});

    	$('body').css({
    		"overflow": "hidden"
    	});

        return false;
    }

    // Import Youtube API Library
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function YouTubeGetID(url){
        var ID = '';
        url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if (url[2] !== undefined) {
            ID = url[2].split(/[^0-9a-z_\-]/i);
            ID = ID[0];
        } else {
            ID = url;
        }
        return ID;
    }

    var yplayer = null;
    function playTrailer(url) {
        if (url.indexOf('youtu') >= 0) {
            var video_id = YouTubeGetID(url);
            var ampersandPosition = video_id.indexOf('&');
            if(ampersandPosition != -1) {
                video_id = video_id.substring(0, ampersandPosition);
            }

            yplayer = new YT.Player('my_video', {
                height: '100%',
                width: '100%',
                videoId: video_id,
                playerVars: { 'autoplay': 1, },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });

            // 4. The API will call this function when the video player is ready.
            function onPlayerReady(event) {
                event.target.playVideo();
            }

            var done = false;
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !done) {
                    // setTimeout(stopVideo, 6000);
                    // done = true;
                }
            }

            function stopVideo() {
                yplayer.stopVideo();
            }
        } else {
            setTimeout(function () {
                jplayer = jwplayer('my_video');
                
                jplayer.setup({
                    file: url,
                    controls: true,
                    autostart: true,
                });

                jplayer.on('complete', function() {
                    jplayer.remove();
                    jplayer = null;
                });
            }.bind(this), 300);
        }

    	$('.video-player').css({
    		"visibility" : "visible",
    		"z-index" : "99999",
    	});
    	$('body').css({
    		"overflow": "hidden"
    	});

    	$('#my_video').show();
    }

    function closeVideo() {
    	$('#my_video').hide();
    	
		if ( jplayer != undefined ) {
			jplayer.remove();
			jplayer = null;
		}

        if (yplayer) {
            yplayer.destroy();
            yplayer = null;
        }

    	$('.video-player').css({
    		"visibility" : "hidden",
    		"z-index" : "-99999"
    	});

    	$('body').css({
    		"overflow": "auto"
    	});
    }

    function addWish(id, type) {
    	app.addToWishList(id, type);
    	setTimeout(function() {
    		$('.addwishlistbtn'+id+type).text(function(i, text){
    			return text == "<?php echo e($popover_translations->where('key', 'add to watchlist')->first->value->value); ?>" ? "<?php echo e($popover_translations->where('key', 'remove from watchlist')->first->value->value); ?>" : "<?php echo e($popover_translations->where('key', 'add to watchlist')->first->value->value); ?>";
    		});
    	}, 100);
    }
</script>