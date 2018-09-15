<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actors = Actor::all();
        return view('admin.actor.index', compact('actors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.actor.create');
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
          'name' => 'required',
          'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
          $name = "actor_".time().$file->getClientOriginalName();
          $file->move('images/actors', $name);
          $input['image'] = $name;
        }

        Actor::create($input);
        return back()->with('added', 'Actor has been created');
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
        $actor = Actor::findOrFail($id);
        return view('admin.actor.edit', compact('actor'));
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
        $actor = Actor::findOrFail($id);

        $request->validate([
          'name' => 'required',
          'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
          $name = "actor_".time().$file->getClientOriginalName();
          if ($actor->image != null) {
              $content = @file_get_contents(public_path().'/images/actors/'.$actor->image);
              if ($content) { 
                unlink(public_path()."/images/actors/".$actor->image);
              }
          }
          $file->move('images/actors', $name);
          $input['image'] = $name;
        }

        $actor->update($input);
        return redirect('admin/actors')->with('updated', 'Actor has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actor = Actor::findOrFail($id);

        if ($actor->image != null) {
          $content = @file_get_contents(public_path().'/images/actors/'.$actor->image);
          if ($content) { 
            unlink(public_path()."/images/actors/".$actor->image);
          }
        }

        $actor->delete();
        return redirect('admin/actors')->with('deleted', 'Actor has been deleted');
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

            $actor = Actor::findOrFail($checked);
            
            if ($actor->image != null) {
              $content = @file_get_contents(public_path().'/images/actors/'.$actor->image);
              if ($content) { 
                unlink(public_path()."/images/actors/".$actor->image);
              }
            }

            Actor::destroy($checked);
        }

        return back()->with('deleted', 'Actors has been deleted');   
    }
}
