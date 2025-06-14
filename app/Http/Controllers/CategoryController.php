<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'      => 'boolean',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = $request->header('user_id') . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');
            // Storage::disk('public')->delete($product->img_url);
        } else {
            $imagePath = $request->image;
        }
        if ($validated) {

            $category = Category::create([
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $imagePath,
                'status'      => $request->status ?? true,
                'slug'        => $request->slug ?? "random",
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => ' create category validation failed.',
            ]);
        }
        if ($category) {
            return response()->json([
                'category' => $category,
                'status'   => 'success',

            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create category.',
            ]);
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

        $category = Category::find($request->id);
        if (! $category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found.',
            ]);
        }
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = $request->header('user_id') . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');
            Storage::disk('public')->delete($category->image);
        } else {
            $imagePath = $request->image;
        }
        if ($category) {
            $name        = $request->name ?? $category->name;
            $description = $request->description ?? $category->description;
            $image       = $imagePath ?? $category->image;
            $status      = $request->status ?? $category->status;
            $slug        = $request->slug ?? $category->slug;

    

            $category->update([
                'name'        => $name,
                'description' => $description,
                'image'       => $image,
                'status'      => $status,
                'slug'        => $slug,
            ]);
            return response()->json([
                'category' => $category,
                'status'   => 'success',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update category.',
            ]);
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

}
