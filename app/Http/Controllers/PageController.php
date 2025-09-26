<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Str;

class PageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * get data from store_step2 and show form for create page
     *
     * @param $id
     */
    public function index()
    {
        $pages = Page::get();

        return view('backend.settings.pages.index', compact('pages'));
    }

    public function store(Request $request)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $page = new Page;
        $page->page_name = $request->page_name;
        $page->slug = Str::slug($request->page_name);
        if ($request->status == 1) {
            $page->status = 1;
        } else {
            $page->status = 0;
        }
        $page->save();

        $slug = Str::slug($request->page_name);

        return $this->editorjs_store_step2($page->id, $slug);
    }

    /**
     * Show the form for creating a new resource.
     * get data from store_step1 and show form for create page
     *
     * @param $id
     */
    public function editorjs_store_step2($id, $slug)
    {
        $editor = Page::find($id);

        return view('backend.settings.pages.editor', compact('editor', 'id', 'slug'));
    }

    /**
     * editorjs_store
     */
    public function editorjs_store(Request $request, $id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        // update or create
        $editor = Page::where('id', $request->id)->first();
        if ($editor) {
            $editor->page_name = $request->page_name;
            $editor->slug = Str::slug($request->page_name);
            $editor->blocks = $request->blocks;
            $editor->status = 1;
        } else {
            $editor = new Page;
            $editor->page_name = $request->page_name;
            $editor->status = 1;
        }

        smilify('success', 'New Blog Created Successfully');
        $editor->save();
    }

    /**
     * editorjs_update_step1
     */
    public function editorjs_update_step1(Request $request, $id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $editor = Page::where('id', $id)->first();
        $editor->page_name = $request->page_name;
        $editor->save();

        smilify('success', 'Blog Updated Successfully');

        return redirect()->route('projects.editorjs.store.step2', ['id' => $editor->id, 'slug' => $slug]);
    }

    /**
     * editorjs_update_step2
     */
    public function editorjs_update_step2($id)
    {
        $editor = Page::find($id);

        return view('backend.projects.editorjs.editor', compact('editor', 'id'));
    }

    /**
     * Blogs
     */
    public function blogs()
    {
        $blogs = Page::where('status', 1)->simplePaginate(6);
        $latest_blogs = Page::where('status', 1)->latest()->limit(10)->get();

        return view('frontend.pages.all_blogs', compact('blogs', 'latest_blogs'));
    }

    /**
     * frontend_index
     */
    public function frontend_index($page)
    {
        $page = Page::where('slug', $page)->first();

        return view('frontend.pages.index', compact('page'));
    }

    /**
     * destroy
     */
    public function edit($id, $slug)
    {
        $editor = Page::find($id);

        return view('backend.settings.pages.editor', compact('editor', 'id', 'slug'));
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        if (demo()) {
            smilify('warning', 'This feature is disabled in demo mode');

            return back();
        }

        $page = Page::find($id);
        $page->delete();
        smilify('success', 'Page Deleted Successfully');

        return back();
    }

    //ENDS
}
