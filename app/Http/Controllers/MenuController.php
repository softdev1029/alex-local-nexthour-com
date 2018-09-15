<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::orderBy('position', 'asc')->get();
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu.create');
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
            'name' => 'required'
        ]);

        $input = $request->all();

        $input['position'] = (Menu::count()+1);

        $input['slug'] = str_slug(strtolower($request->name), '-');

        Menu::create($input);

        return back()->with('added', 'Menu has been created');
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
        $menu = Menu::findOrFail($id);
        return view('admin.menu.edit', compact('menu'));
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
            'name' => 'required'
        ]);

        $menu = Menu::findOrFail($id);
        
        $input = $request->all();

        $menu->update($input);

        return redirect('admin/menu')->with('updated', 'Menu has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return back()->with('deleted', 'Menu has been deleted');
    }

    public function reposition(Request $request)
    {
      if($request->item != null)
      {
          $items = explode('&', $request->item);
          $all_ids = collect();
          foreach ($items as $key => $value) {
              $all_ids->push(substr($value, 7));
          }

          $i = 0;

          foreach($all_ids as $id)
          {
              $i++;
              $item = Menu::findOrFail($id);
              $item->position = $i;
              $item->save();
          }

          return response()->json(['success' => true]);

      }

      else
      {
          return response()->json(['success' => false]);
      }
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
            $menu = Menu::findOrFail($checked);
            $menu->delete();
          }
          return back()->with('deleted', 'Menus has been deleted');   
    }
}
