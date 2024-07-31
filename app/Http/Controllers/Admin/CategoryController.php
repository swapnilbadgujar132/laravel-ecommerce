<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    function index(): View
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }
    function create(): View
    {
        return view('admin.category.create');
    }
    function store(Request $request): RedirectResponse
    {
        $data=$request->validate([
            'name' => 'required|unique:categories',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2096',
            'meta_keyword' => 'required',
            'meta_description' => 'required',
        ]);

        $filename = '';
        if ($request->file('image')) {
            $storefilename = $request->image->store('category', 'public');
            $storefilearray=explode('/',$storefilename);
            $last =count($storefilearray);
            $filename =$storefilearray[$last-1];
        }

        $category = new Category();
        $category->image = $filename;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->meta_keyword = $request->meta_keyword;
        $category->meta_description = $request->meta_description;
        $category->serial = $request->serial;
        $category->save();
        return redirect()->route('admin.category.index')->with('success', 'Category add successfully');
    }
    function edit($id): View
    {
        $category = Category::findOrFail($id);
        return view('admin.category.update', compact('category'));
    }
    function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2096',
            'meta_keyword' => 'required',
            'meta_description' => 'required'
        ]);

        $category = Category::findOrFail($id);
        $filename = '';
        if ($request->file('image')) {
            $storefilename = $request->file('image')->store('category', 'public');
            $storefilearray=explode('/',$storefilename);
            $last =count($storefilearray);
            $filename =$storefilearray[$last-1];

            $oldImagePath = storage_path('app/public/category/' . $category->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        } else {
            $filename = $category->image;
        }
        $category->image = $filename;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->meta_keyword = $request->meta_keyword;
        $category->meta_description = $request->meta_description;
        $category->serial = $request->serial;
        $category->save();
        return redirect()->route('admin.category.index')->with('success', 'Category Update successfully');
    }
    function delete($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $path = storage_path('app/public/category/' . $category->image);
        if (File::exists($path)) {
            unlink($path);
        }
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Category Delete successfully');
    }

    function update_status($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        if ($category->status == 1) {
            $category->status = 0;
            $category->save();

            return redirect()->route('admin.category.index')->with('success', 'Category Status un-active successfully');
        } else {
            $category->status = 1;
            $category->save();

            return redirect()->route('admin.category.index')->with('success', 'Category Status active successfully');
        }
    }
}
