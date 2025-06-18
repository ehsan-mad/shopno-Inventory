<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * Create a new product
     */
    public function productCreate(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'price'          => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status'         => 'boolean',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $imagePath = null;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $imageName = 'product_' . $userId . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            }

            // Create product
            $product = Product::create([
                'user_id'        => $userId,
                'name'           => $request->name,
                'description'    => $request->description,
                'category_id'    => $request->category_id,
                'price'          => $request->price,
                'selling_price'  => $request->selling_price,
                'stock_quantity' => $request->stock_quantity,
                'image'          => $imagePath,
                'status'         => $request->status ?? true,
            ]);

            // Create initial stock movement record
            if ($request->stock_quantity > 0) {
                StockMovement::create([
                    'product_id'     => $product->id,
                    'type'           => 'in',
                    'quantity'       => $request->stock_quantity,
                    'previous_stock' => 0,
                    'current_stock'  => $request->stock_quantity,
                    'user_id'        => $userId,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully.',
                'product' => $product->load('category:id,name'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded image if product creation fails
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create product.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all products with filters and search
     */
    public function productList(Request $request)
    {
        try {
            $userId = $request->header('user_id');

            if (!$userId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User ID is required in header.',
                ], 401);
            }

            $products = Product::where('user_id', $userId)
                ->with('category:id,name')
                ->select(
                    'id', 'name', 'description', 'category_id', 'price',
                    'selling_price', 'stock_quantity', 'image', 'status', 'created_at'
                )
                ->orderBy('id', 'desc')
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'status'     => 'success',
                'products'   => $products,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'total_pages'  => $products->lastPage(),
                    'per_page'     => $products->perPage(),
                    'total_items'  => $products->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch products.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update product
     */
    public function productUpdate(Request $request)
    {
        $userId = $request->header('user_id');

        $product = Product::find($request->id);

        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'name'           => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'nullable|exists:categories,id',
            'price'          => 'nullable|numeric|min:0',
            'selling_price'  => 'nullable|numeric|min:0',

            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status'         => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        try {
            DB::beginTransaction();
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $imageName = 'product_' . $userId . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            }
            }

            // Update product data
            $updateData = [
                'name'          => $request->name ?? $product->name,
                'description'   => $request->description ?? $product->description,
                'category_id'   => $request->category_id ?? $product->category_id,
                'price'         => $request->price ?? $product->price,
                'selling_price' => $request->selling_price ?? $product->selling_price,
                'status'        => $request->status ?? $product->status,
                'user_id'       => $userId,
                'stock_quantity' => $request->stock_quantity ?? $product->stock_quantity,
                'image'         => isset($imagePath) ? $imagePath : $product->image,
            ];

            $product->update($updateData);
            if ($request->stock_quantity > 0) {
                StockMovement::create([
                    'product_id'     => $product->id,
                    'type'           => 'in',
                    'quantity'       => $request->stock_quantity ?? $product->stock_quantity,
                    'previous_stock' => 0,
                    'current_stock'  => $request->stock_quantity,
                    'user_id'        => $userId,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully.',
                'product' => $product->fresh()->load('category:id,name'),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // $imagePath = isset($imagePath) ? $imagePath : $product->image;
            // // Delete uploaded image if product creation fails
            // if ($imagePath) {
            //     Storage::disk('public')->delete($imagePath);
            // }

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create product.',
                'error'   => $e->getMessage(),
            ], 500);
           
        }
    }

    /**
     * Delete product
     */
    public function productDelete(Request $request)
    {
        $product = Product::find($request->id);

        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        try {
            DB::beginTransaction();
            DB::table('stock_movements')->where('product_id', $product->id)->delete();

            // Delete image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Handle any errors during deletion

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete product.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show single product with stock history
     */
    public function productShow(Request $request)
    {

        $product =  Product::find($request->id);


        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        // Calculate product statistics
        // $totalSold    = $product->saleItems()->sum('quantity');
        // $totalRevenue = $product->saleItems()->sum('total_price');
        // $profitMargin = $product->selling_price - $product->purchase_price;

        // $productData               = $product->toArray();
        // $productData['statistics'] = [
        //     'total_sold'        => $totalSold,
        //     'total_revenue'     => $totalRevenue,
        //     'profit_margin'     => $profitMargin,
        //     'profit_percentage' => $product->purchase_price > 0 ?
        //     (($profitMargin / $product->purchase_price) * 100) : 0,
        //     'is_low_stock'      => $product->stock_quantity <= $product->min_stock_level,
        // ];

        return response()->json([
            'status'  => 'success',
            'product' => $product,
        ], 200);
    }

    /**
     * Get products for dropdown/select options
     */
    public function productOptions()
    {
        try {
            $products = Product::where('status', true)
                ->where('stock_quantity', '>', 0)
                ->select('id', 'name',  'price', 'stock_quantity',)
                ->orderBy('id')
                ->get();

            return response()->json([
                'status'   => 'success',
                'products' => $products,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch product options.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = User::find($request->headers->get('user_id'));
        return view('products.index', compact('user'));
    }

}
