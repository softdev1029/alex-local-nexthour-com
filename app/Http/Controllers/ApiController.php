<?php

namespace App\Http\Controllers;

use Auth;

use App\Movie;
use App\Season;
use App\UserPlayingTime;

use Illuminate\Http\Request;

class ApiController extends Controller {

    public function get_video_data(Request $request) {

    	$user = Auth::user();

    	$id = intval($request->input('id'));
    	$type = $request->input('type');

    	$user = Auth::user();

    	if ($type == 'M') {
    	
    		$movie = Movie::find($id);

            if ( !$movie ) {
                return response()->json([]);
            }

            $poster = $movie->poster;

    		$all_link = $movie->video_link;

            $title = $movie->title;

            if ( $movie->thumbnail ) {
            	$image = config('app.url') . '/images/movies/thumbnails/' . $movie->thumbnail;
            }

            $description = $movie->detail;

            $sources = [];

            if ( $all_link ) {
                if ( $all_link->url_360 ) {
                    $sources[] = [
                        'file' => $all_link->url_360,
                        'label' => '360',
                    ];
                }

                if ( $all_link->url_480 ) {
                    $sources[] = [
                        'file' => $all_link->url_480,
                        'label' => '480',
                    ];
                }

                if ( $all_link->url_720 ) {
                    $sources[] = [
                        'file' => $all_link->url_720,
                        'label' => '720',
                    ];
                }

                if ( $all_link->url_1080 ) {
                    $sources[] = [
                        'file' => $all_link->url_1080,
                        'label' => '1080',
                    ];
                }
            }

            // Check last playing time
            $storing = UserPlayingTime::where('user_id', $user->id)
			                            ->where('type', $type)
			                            ->where('item_id', $id)
			                            ->orderBy('id', 'desc')
			                            ->first();

            return response()->json([
                'links' => $sources, 
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'poster' => $poster,
                'time' => $storing ? $storing->time : 0,
                'track_no' => $user->audio_track_no,
            ]);

    	} elseif ($type == 'S') {

    		$season = Season::find($id);

    		if ( !$season ) {
    			return response()->json([]);
    		}

            $links = [];

            // TV Series has multiple seasons
            if ( count($season->tvseries->seasons) > 1 ) {

            	foreach ( $season->tvseries->seasons as $ss ) {
		            if ( count($ss->episodes) > 0 )   {
		                foreach ($ss->episodes as $key => $episode) {

		                    $all_link = $episode->video_link;

		                    $image = asset('images/tvseries/thumbnails/'.($ss->thumbnail != null ? $ss->thumbnail : $ss->tvseries->thumbnail));

		                    $poster = asset('images/tvseries/posters/'.($ss->poster != null ? $ss->poster : $ss->tvseries->poster));
		            
		                    if ( $all_link->ready_url != null ) {
		                        $links[] = [
		                            'file' => $all_link->ready_url,
		                            'image' => $image,
		                            'title' => $episode->title,
		                            'poster' => $poster,
		                            'description' => $ss->detail,
		                        ];
		                    } else {

		                        $sources = [];

		                        if ( $all_link->url_360 != null ) {
		                            $sources[] = [
		                                'file' => $all_link->url_360,
		                                'label' => '360p',
		                            ];
		                        }

		                        if ( $all_link->url_480 != null ) {
		                            $sources[] = [
		                                'file' => $all_link->url_480,
		                                'label' => '480p',
		                            ];
		                        }

		                        if ( $all_link->url_720 != null ) {
		                            $sources[] = [
		                                'file' => $all_link->url_480,
		                                'label' => '720p',
		                            ];
		                        }

		                        if ( $all_link->url_1080 != null ) {
		                            $sources[] = [
		                                'file' => $all_link->url_1080,
		                                'label' => '1080p',
		                            ];
		                        }

		                        if ( $sources ) {
		                            $links[] = [
		                                'sources' => $sources,
		                                'image' => $image,
		                                'title' => $episode->title,
		                                'poster' => $poster,
		                                'description' => $ss->detail,
		                            ];
		                        }
		                    }
		                }
		            }
            	}

            } else {

	            if ( count($season->episodes) > 0 )   {
	                foreach ($season->episodes as $key => $episode) {

	                    $all_link = $episode->video_link;

	                    $image = asset('images/tvseries/thumbnails/'.($season->thumbnail != null ? $season->thumbnail : $season->tvseries->thumbnail));

	                    $poster = asset('images/tvseries/posters/'.($season->poster != null ? $season->poster : $season->tvseries->poster));
	            
	                    if ( $all_link->ready_url != null ) {
	                        $links[] = [
	                            'file' => $all_link->ready_url,
	                            'image' => $image,
	                            'title' => $episode->title,
	                            'poster' => $poster,
	                            'description' => $season->detail,
	                        ];
	                    } else {

	                        $sources = [];

	                        if ( $all_link->url_360 != null ) {
	                            $sources[] = [
	                                'file' => $all_link->url_360,
	                                'label' => '360p',
	                            ];
	                        }

	                        if ( $all_link->url_480 != null ) {
	                            $sources[] = [
	                                'file' => $all_link->url_480,
	                                'label' => '480p',
	                            ];
	                        }

	                        if ( $all_link->url_720 != null ) {
	                            $sources[] = [
	                                'file' => $all_link->url_480,
	                                'label' => '720p',
	                            ];
	                        }

	                        if ( $all_link->url_1080 != null ) {
	                            $sources[] = [
	                                'file' => $all_link->url_1080,
	                                'label' => '1080p',
	                            ];
	                        }

	                        if ( $sources ) {
	                            $links[] = [
	                                'sources' => $sources,
	                                'image' => $image,
	                                'title' => $episode->title,
	                                'poster' => $poster,
	                                'description' => $season->detail,
	                            ];
	                        }
	                    }
	                }
	            }
			}

            // Check last playing time
            $storing = UserPlayingTime::where('user_id', $user->id)
			                            ->where('type', $type)
			                            ->where('item_id', $id);

			if ( $request->has('index') ) {
				$storing = $storing->where('index', intval($request->input('index')));
			}

			$storing = $storing->orderBy('id', 'desc')
                                ->orderBy('index', 'desc')
								->first();

            return response()->json([
            	'links' => $links,
            	'index' => $storing ? $storing->index : 0,
            	'time' => $storing ? $storing->time : 0,
            	'track_no' => $user->audio_track_no,
            ]);

    	}

        return response()->json([]);

    }

}
