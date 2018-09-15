<?php

namespace App\Http\Controllers;

use App\AudioLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AudioLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $a_lans = AudioLanguage::all();
        return view('admin.audio_language.index', compact('a_lans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.audio_language.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'language' => 'required'
        ]);
        $input = $request->all();
        AudioLanguage::create($input);
        return back()->with('added', 'language has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $a_lan = AudioLanguage::findOrFail($id);
        return view('admin.audio_language.edit', compact('a_lan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
           'language' => 'required'
        ]);
        $a_lan = AudioLanguage::findOrFail($id);
        $input = $request->all();
        $a_lan->update($input);
        return redirect('/admin/audio_language')->with('updated', 'Language has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a_lan = AudioLanguage::findOrFail($id);
        $a_lan->delete();
        return back()->with('deleted', 'Language has been deleted');
    }

    public function bulk_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', 'Please select one of them to delete');
        }

        foreach ($request->checked as $checked) {
            AudioLanguage::destroy($checked);
        }

        return back()->with('deleted', 'Audio Languages has been deleted');   
    }
}
