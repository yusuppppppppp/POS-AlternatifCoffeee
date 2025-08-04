<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');
        $category = request()->get('category');
        
        $query = Menu::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('price', 'like', '%' . $search . '%');
            });
        }
        
        if ($category && $category !== 'All') {
            $query->where('category', $category);
        }
        
        $menus = $query->orderBy('id', 'desc')->paginate($perPage);
        
        return view('menu-management', compact('menus', 'search', 'category'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|in:Coffee,Non Coffee,Food',
            'image' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $imagePath = $request->file('image')->store('menus', 'public');

        Menu::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'image_path' => $imagePath,
        ]);

        return response()->json(['message' => 'Menu added successfully'], 200);
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|in:Coffee,Non Coffee,Food',
            'image' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $data = $request->only(['name', 'price', 'category']);

        if ($request->hasFile('image')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image_path'] = $imagePath;
        }

        $menu->update($data);

        return response()->json(['message' => 'Menu updated successfully'], 200);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully'], 200);
    }

    public function apiIndex()
    {
        $menus = Menu::orderBy('id', 'desc')->get();
        $menus->each(function ($menu) {
            $menu->image_url = asset('storage/' . $menu->image_path);
        });

        return response()->json($menus);
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->image_url = asset('storage/' . $menu->image_path);

        return response()->json($menu);
    }
}
