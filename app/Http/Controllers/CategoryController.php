<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function categoryCreate(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif',
            'status'      => 'nullable|boolean',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category validation failed.',
                'errors'  => $validated->errors()
            ], 422);
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $request->header('user_id') . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads', $imageName, 'public');
            }

            // Generate a unique slug if not provided
            $slug = $request->slug ?? strtolower(str_replace(' ', '-', $request->name)) . '-' . time();

            $category = Category::create([
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $imagePath,
                'status'      => $request->status ?? true,
                'slug'        => $slug,
            ]);

            return response()->json([
                'category' => $category,
                'status'   => 'success',
                'message'  => 'Category created successfully.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create category.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function categoryList()
    {
        $category = Category::where('status', true)->get();
        if ($category) {
            return response()->json([
                'category' => $category,
                'status'   => 'success',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch categories.',
            ]);
        }
    }

    public function categoryUpdate(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id'          => 'required|exists:categories,id',
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif',
            'status'      => 'nullable|boolean',
            'slug'        => 'nullable|string|max:255|unique:categories,slug,' . $request->id,
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category validation failed.',
                'errors'  => $validated->errors()
            ], 422);
        }

        $category = Category::find($request->id);
        if (!$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found.',
            ]);
        }

        try {
            // Handle image upload
            $imagePath = $category->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $image = $request->file('image');
                $imageName = $request->header('user_id') . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads', $imageName, 'public');
            }

            $category->update([
                'name'        => $request->name ?? $category->name,
                'description' => $request->description ?? $category->description,
                'image'       => $imagePath,
                'status'      => $request->status ?? $category->status,
                'slug'        => $request->slug ?? $category->slug,
            ]);

            return response()->json([
                'category' => $category,
                'status'   => 'success',
                'message'  => 'Category updated successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update category.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function categoryDelete(Request $request)
    {

        $category = Category::find($request->id);
        if (! $category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found.',
            ]);
        } else {
            Storage::disk('public')->delete($category->image);
            $category->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Category deleted successfully.',
            ], 200);
        }
    }

    public function categoryShow(Request $request)
    {
        $category = Category::find($request->id);
        if (! $category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found.',
            ]);
        } else {
            return response()->json([
                'category' => $category,
                'status'   => 'success',
            ], 200);
        }
    }

    public function index(Request $request)
    {
        $user = User::find($request->headers->get('user_id'));
        return view('categories.index', compact('user'));
    }

}
