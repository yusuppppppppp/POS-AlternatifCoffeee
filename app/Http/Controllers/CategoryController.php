<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');
        
        $query = Category::query();
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        $categories = $query->orderBy('id', 'desc')->paginate($perPage);
        
        return view('category-management', compact('categories', 'search'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category added successfully'], 200);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category is being used by any menu
        if ($category->menus()->count() > 0) {
            return response()->json(['message' => 'Cannot delete category that is being used by menu items'], 422);
        }
        
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    public function apiIndex()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return response()->json($categories);
    }
}
