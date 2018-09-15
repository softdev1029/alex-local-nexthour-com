<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FooterTranslation;

class FooterTranslationController extends Controller
{
    public function index()
    {
    	$translations = FooterTranslation::all();
    	return view('admin.translations.footer-translations', compact('translations'));
    }

    public function update(Request $request)
    {
    	$ids = $request->id;
    	$values = $request->name;

    	foreach ($ids as $key => $value) {
    		$get_key = FooterTranslation::find($value);
    		if (isset($get_key)) {	
	    		$get_key->update([
	    			'value' => $values[$key]
	    		]);
    		}
    	}
    	
    	return back()->with('updated', 'Footer translations has been updated');	

    }
}
