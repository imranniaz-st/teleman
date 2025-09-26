<?php

namespace App\Http\Controllers;

use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Http\Request;

class MenuBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menus::get();

        return view('backend.settings.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.settings.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function page_store(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $pages = $request->page_id;

        foreach ($pages as $page) {
            $menu_page = new MenuItems;
            $menu_page->label = pageName($page);
            $menu_page->link = '#';
            $menu_page->page_id = $page;
            $menu_page->menu = $request->idmenu;
            $menu_page->parent = 0;
            $menu_page->depth = 0;

            if ($menu_page->save()) {
                $menu_page->sort = $menu_page->id;
                $menu_page->save();
            }
        }
        //notification alert
        smilify('success', 'New Menu Added Successfully');

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('backend.settings.menus.edit');
    }

    // ENDS HERE
}
