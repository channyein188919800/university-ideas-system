<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount('ideas')
            ->latest()
            ->paginate(20);

        return view('qa-manager.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('qa-manager.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('qa-manager.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('qa-manager.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('qa-manager.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->ideas()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category that has been used.');
        }

        $category->delete();
        return redirect()->route('qa-manager.categories.index')->with('success', 'Category deleted successfully!');
    }
}
