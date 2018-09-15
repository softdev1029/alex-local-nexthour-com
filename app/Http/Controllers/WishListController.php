<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Season;
use App\Wishlist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishListController extends Controller
{

	public function showWishListTvShows()
	{
		$auth = Auth::user();
		$all_shows = collect();

		$all_seasons = DB::table('wishlists')->where([
			['user_id', '=', $auth->id],
			['item_type', '=', 'S']
		])->get();

		foreach ($all_seasons as $season)
		{
			$item = Season::find($season->item_id);
			if (isset($item)) {
				$all_shows->push($item);
			}
		}
		return view('watchlist', compact('all_shows'));
	}

	public function showWishListMovies()
	{
		$auth = Auth::user();
		$all_movies = collect();

		$movies = DB::table('wishlists')->where([
			['user_id', '=', $auth->id],
			['item_type', '=', 'M']
		])->get();

          // return $movies;

		foreach ($movies as $movie)
		{            
			$item2 = Movie::find($movie->item_id);
			if (isset($item2)) { 
				$all_movies->push($item2);
			}
		}

		return view('watchlist', compact('all_movies'));
	}

	public function addWishList(Request $request)
	{
		$auth = Auth::user();
		
		$id = intval($request->input('id'));
		$type = $request->input('type');

		$result = '';
		if ( $auth->addedWishlist($id, $type) ) {
			Wishlist::where('item_id', $id)
					->where('item_type', $type)
					->delete();
			
			$result = 'added';
		} else {
			$wishlist = new Wishlist;
			$wishlist->user_id = $auth->id;
			$wishlist->item_id = $id;
			$wishlist->item_type = $type;
			$wishlist->save();
			
			$result = 'removed';
		}

		return response()->json(['result' => $result]);
	}

	public function showdestroy($id)
	{
		$show = Wishlist::where('item_id', $id)->where('item_type', 'S')->first();
		$show->delete();
		return back();
	}

	public function moviedestroy($id)
	{
		$movie = Wishlist::where('item_id', $id)->where('item_type', 'M')->first();
		$movie->delete();
		return back();
	}

	public function tvseriesdestroy($id)
	{
		$movie = Wishlist::where('tvseries_id', $id)->where('item_type', 'S')->first();
		$movie->delete();
		return back();
	}
}
