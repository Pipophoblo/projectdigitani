<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('threads')->latest()->paginate(10);
        return view('admin.forum.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.forum.create');
    }
    
     public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('categories', 'digitani');

    // (Optional) buat supaya bisa diakses publik dari URL
    Storage::disk('digitani')->setVisibility($imagePath, 'public');

    $validatedData['image'] = $imagePath;
}

        
        Category::create($validatedData);
        
        return redirect()->route('admin.forum.index')
            ->with('success', 'Category created successfully.');
    }
    
    public function edit(Category $category)
    {
        return view('admin.forum.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
    // Delete old image if exists (on digitani)
    if ($category->image && Storage::disk('digitani')->exists($category->image)) {
        Storage::disk('digitani')->delete($category->image);
    }

    // Upload new image to R2
    $imagePath = $request->file('image')->store('categories', 'digitani');

    // Make image publicly accessible
    Storage::disk('digitani')->setVisibility($imagePath, 'public');

    $validatedData['image'] = $imagePath;
}

        
        $category->update($validatedData);
        
        return redirect()->route('admin.forum.index')
            ->with('success', 'Category updated successfully.');
    }
    
    public function destroy(Category $category)
    {
        // Check if category has threads before deleting
        if ($category->threads()->count() > 0) {
            return redirect()->route('admin.forum.index')
                ->with('error', 'Cannot delete category with existing threads.');
        }
        
        // Delete image if exists
        if ($category->image && Storage::disk('digitani')->exists($category->image)) {
        Storage::disk('digitani')->delete($category->image);
    }
        
        $category->delete();
        
        return redirect()->route('admin.forum.index')
            ->with('success', 'Category deleted successfully.');
    }
}