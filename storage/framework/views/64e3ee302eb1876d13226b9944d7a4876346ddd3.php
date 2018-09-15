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
                            }).then((response) => {
                            }).catch((er) => {
                                console.log(er);
                            });

                            positionTime = parseInt(position / 30);
                        }
                    });

                    jplayer.on('firstFrame', function() {
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
						
						jplayer.on('time', function(e) {
							var position = Math.floor(e.position);
							if ( position > 0 && position % 30 == 0 && positionTime != parseInt(position / 30) ) {
								app.$http.post('<?php echo e(route("store_time")); ?>', {
									type: type,
									id: id,
									time: position,
									duration: jplayer.getDuration(),
								}).then((response) => {
								}).catch((er) => {
						    		console.log(er);
						    	});

						    	positionTime = parseInt(position / 30);
							}

                            console.log(position + ':' + jplayer.getCurrentAudioTrack());
						});

						jplayer.on('firstFrame', function() {
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

    function playTrailer(url) {
		setTimeout(function () {
			jplayer = jwplayer('my_video');
			
			jplayer.setup({
				file: url,
				controls: true,
			});

			jplayer.on('complete', function() {
				jplayer.remove();
				jplayer = null;
			});
		}.bind(this), 300);

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