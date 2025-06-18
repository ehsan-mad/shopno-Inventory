<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helper\JwtToken;

class SaleController extends Controller
{
    /**
     * Create a new sale
     */
    public function saleCreate(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'customer_id'        => 'required|exists:customers,id',

            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'tax'                => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string|max:500',
            'status'             => 'nullable|in:pending,completed,cancelled',
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

            // Verify customer belongs to user
            $customer = Customer::where('id', $request->customer_id)
                ->first();

            if (! $customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found or unauthorized.',
                ], 404);
            }

            // Validate stock availability
            $stockValidation = $this->validateStockAvailability($request->items, $userId);

            if (! $stockValidation['valid']) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Insufficient stock.',
                    'errors'  => $stockValidation['errors'],
                ], 400);
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $discountAmount = $request->discount ?? 0;
            $taxAmount      = $request->tax ?? 0;
            $totalAmount    = $subtotal - $discountAmount + $taxAmount;

            // Generate sale number
            $saleNumber = $userId;

            // Create sale record
            $sale = Sale::create([
                'user_id'     => $userId,
                'customer_id' => $request->customer_id,
                'sale_number' => $saleNumber,
                'sale_date'   => $request->sale_date,
                'subtotal'    => $subtotal,
                'discount'    => $discountAmount,
                'tax'         => $taxAmount,
                'total'       => $totalAmount,
                'status'      => $request->status ?? 'pending',
                'notes'       => $request->notes,
            ]);

            // Create sale items and update stock
            foreach ($request->items as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('user_id', $userId)
                    ->first();

                if (! $product) {
                    throw new \Exception("Product not found: " . $item['product_id']);
                }

                // Create sale item
                $Items = SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $item['quantity'] * $item['price'],
                ]);

                // Update product stock
                $previousStock = $product->stock_quantity;
                $newStock      = $previousStock - $item['quantity'];

                $product->update(['stock_quantity' => $newStock]);

                // Create stock movement record
                StockMovement::create([
                    'product_id'     => $product->id,
                    'type'           => 'out',
                    'quantity'       => $item['quantity'],
                    'previous_stock' => $previousStock,
                    'current_stock'  => $newStock,
                    'reference_type' => 'sale',
                    'reference_id'   => $sale->id,
                    'notes'          => "Sale: {$saleNumber}",
                    'user_id'        => $userId,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Sale created successfully.',
                'sale'    => $sale,
                'Items'   => $Items,

            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create sale.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get sales list with filters
     */
    public function salesList(Request $request)
    {
        try {
            $user_id = $request->header('user_id');
            if (!$user_id) {
                \Log::error('Sales List Error: User ID not found in request headers');
                return response()->json([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ], 400);
            }

            $user = User::find($user_id);
            if (!$user) {
                \Log::error('Sales List Error: User not found for ID: ' . $user_id);
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            $query = Sale::with(['customer', 'saleItems.product'])
                ->where('user_id', $user_id);

            // Apply filters
            if ($request->has('date_from')) {
                $query->whereDate('sale_date', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('sale_date', '<=', $request->date_to);
            }
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Get paginated results
            $sales = $query->orderBy('created_at', 'desc')
                ->paginate(10);

            // Transform the data to ensure it's in the correct format
            $transformedSales = collect($sales->items())->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'sale_number' => $sale->sale_number,
                    'customer' => $sale->customer ? [
                        'id' => $sale->customer->id,
                        'name' => $sale->customer->name
                    ] : null,
                    'sale_date' => $sale->sale_date,
                    'total' => $sale->total,
                    'status' => $sale->status,
                    'created_at' => $sale->created_at
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $transformedSales,
                'pagination' => [
                    'current_page' => $sales->currentPage(),
                    'per_page' => $sales->perPage(),
                    'total_items' => $sales->total(),
                    'last_page' => $sales->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Sales List Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode([
                'user_id' => $request->header('user_id'),
                'email' => $request->header('email'),
                'filters' => [
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'status' => $request->status
                ]
            ]));
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching sales',
                'debug' => [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * Show single sale with details
     */
    public function saleShow(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $sale = Sale::where('id', $request->id)
            ->where('user_id', $userId)
            ->with([
                'customer',
                'saleItems.product:id,name',
                'user:id,first_name',
            ])
            ->first();

        if (! $sale) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sale not found or unauthorized.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'sale'   => $sale,
        ], 200);
    }

    /**
     * Update sale (before completion)
     */
    public function saleUpdate(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'id'          => 'required|exists:sales,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date'   => 'nullable|date',
            'discount'    => 'nullable|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'notes'       => 'nullable|string|max:500',
            'status'      => 'nullable|in:pending,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $sale = Sale::where('id', $request->id)
                ->where('user_id', $userId)
                ->first();

            if (! $sale) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sale not found or unauthorized.',
                ], 404);
            }

            if ($sale->status === 'completed' && $request->status !== 'cancelled') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Cannot update completed sale. Use cancel endpoint to cancel.',
                ], 400);
            }

            $updateData = [
                'customer_id' => $request->customer_id ?? $sale->customer_id,
                'sale_date'   => $request->sale_date ?? $sale->sale_date,
                'discount'    => $request->discount ?? $sale->discount,
                'tax'         => $request->tax ?? $sale->tax,
                'notes'       => $request->notes ?? $sale->notes,
                'status'      => $request->status ?? $sale->status,
            ];

            $sale->update($updateData);

            return response()->json([
                'status'  => 'success',
                'message' => 'Sale updated successfully.',
                'sale'    => $updateData,

            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update sale.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel/void sale
     */
    public function cancelSale(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'id'     => 'required|exists:sales,id',
            'reason' => 'nullable|string|max:500',
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

            $sale = Sale::where('id', $request->id)
                ->where('user_id', $userId)
                ->with('saleItems')
                ->first();

            if (! $sale) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sale not found or unauthorized.',
                ], 404);
            }

            if ($sale->status === 'cancelled') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sale is already cancelled.',
                ], 400);
            }

            // Restore stock for each item
            foreach ($sale->saleItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $previousStock = $product->stock_quantity;
                    $newStock      = $previousStock + $item->quantity;

                    $product->update(['stock_quantity' => $newStock]);

                    // Create stock movement record
                    StockMovement::create([
                        'product_id'     => $product->id,
                        'type'           => 'in',
                        'quantity'       => $item->quantity,
                        'previous_stock' => $previousStock,
                        'current_stock'  => $newStock,
                        'reference_type' => 'sale_cancellation',
                        'reference_id'   => $sale->id,

                        'user_id'        => $userId,
                    ]);
                }
            }

            // Update sale status
            $sale->update([
                'status' => 'cancelled',
                'notes'  => $sale->notes . "\n\nCancelled: " . ($request->reason ?? 'No reason provided'),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Sale cancelled successfully.',
                'sale'    => $sale->fresh(),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to cancel sale.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get sales summary/statistics
     */
    public function salesSummary(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        try {

            $dateFrom = date('Y-m-d', strtotime($request->date_from));
            $dateTo   = date('Y-m-d', strtotime($request->date_to));

         
            $query = Sale::where('user_id', $userId)
                ->whereDate('sale_date', '>=', $dateFrom)
                ->whereDate('sale_date', '<=', $dateTo);

            $summary = [
                'period'             => [
                    'from' => $dateFrom,
                    'to'   => $dateTo,
                ],
                'total_sales'        =>  (clone $query)->where('status', 'completed')->count(),
                'total_revenue'      => (clone $query)->where('status', 'completed')->sum('total'),
                'cancelled_sales'    => (clone $query)->where('status', 'cancelled')->count(),
                'pending_sales'      => (clone $query)->where('status', 'pending')->count(),
                'average_sale_value' => (clone $query)->where('status', 'completed')->avg('total') ?? 0,

            ];
            
            return response()->json([
                'status'  => 'success',
                'summary' => $summary,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch sales summary.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete sale (soft delete)
     */
    public function saleDelete(Request $request)
    {
        $userId = $request->header('user_id');

        if (! $userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.',
            ], 401);
        }

        $sale = Sale::where('id', $request->id)
            ->where('user_id', $userId)
            ->first();

        if (! $sale) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sale not found or unauthorized.',
            ], 404);
        }

        if ($sale->status === 'completed') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cannot delete completed sale. Use cancel endpoint instead.',
            ], 400);
        }

        try {
            $sale->delete(); // Soft delete

            return response()->json([
                'status'  => 'success',
                'message' => 'Sale deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete sale.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate stock availability for sale items
     */
    private function validateStockAvailability(array $items, int $userId): array
    {
        $errors = [];
        $valid  = true;

        foreach ($items as $index => $item) {
            $product = Product::where('id', $item['product_id'])
                ->where('user_id', $userId)
                ->first();

            if (! $product) {
                $errors["items.{$index}.product_id"] = "Product not found or unauthorized.";
                $valid                               = false;
                continue;
            }

            if ($product->stock_quantity < $item['quantity']) {
                $errors["items.{$index}.quantity"] = "Insufficient stock. Available: {$product->stock_quantity}, Requested: {$item['quantity']}";
                $valid                             = false;
            }

            if (! $product->status) {
                $errors["items.{$index}.product_id"] = "Product is inactive.";
                $valid                               = false;
            }
        }

        return ['valid' => $valid, 'errors' => $errors];
    }

    /**
     * Generate unique sale number
     */

    public function index(Request $request)
    {
        $user_id = $request->header('user_id');
        if (!$user_id) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue');
        }

        $user = User::find($user_id);
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found');
        }

        // Get the token from cookie
        $token = $request->cookie('token');
        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue');
        }

        return view('sales.index', [
            'user' => $user,
            'token' => $token
        ]);
    }
}
