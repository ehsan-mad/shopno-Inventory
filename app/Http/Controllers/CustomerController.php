<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function customerCreate(Request $request)
    {
        $user_id = $request->header('user_id');
        if (! $user_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in the header',
            ], 400);
        } else {
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:customers,email',
                'phone'         => 'nullable|string|max:20',
                'city'          => 'nullable|string|max:100',
                'customer_type' => 'nullable|in:regular,wholesale,retail,premium',
                'status'        => 'boolean',
            ]);
            if ($validated) {
                $customer = Customer::create([
                    'user_id'       => $user_id,

                    'name'          => $request->input('name'),
                    'email'         => $request->input('email'),
                    'phone'         => $request->input('phone'),
                    'city'          => $request->input('city'),
                    'customer_type' => $request->input('customer_type', 'regular'),
                    'status'        => $request->input('status', true),
                ]);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation failed',
                ], 422);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer created successfully',
            'data'    => $customer,
        ], 201);
    }

    public function customerList(Request $request)
    {
        $user_id = $request->header('user_id');
        if (! $user_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in the header',
            ], 400);
        }

        $customers = Customer::where('user_id', $user_id)->get();
        if ($customers->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No customers found',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Customers fetched successfully',
            'data'    => $customers,
        ], 200);
    }

    public function customerShow(Request $request)
    {
        $user_id = $request->header('user_id');
        if (! $user_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in the header',
            ], 400);
        }

        $customer = Customer::where('id', $request->id)->where('user_id', $user_id)->first();
        if (! $customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer fetched successfully',
            'data'    => $customer,
        ], 200);
    }

    public function customerUpdate(Request $request)
    {
        $user_id = $request->header('user_id');
        if (! $user_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in the header',
            ], 400);
        }

        $customer = Customer::where('id', $request->id)->where('user_id', $user_id)->first();
        if (! $customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        $validated = $request->validate([
            'name'          => 'nullable|string|max:255',
            'email'         => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone'         => 'nullable|string|max:20',
            'city'          => 'nullable|string|max:100',
            'customer_type' => 'nullable|in:regular,wholesale,retail,premium',
            'status'        => 'boolean',
        ]);
        if ($validated) {
            $customer->update($request->all());
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer updated successfully',
                'data'    => $customer,
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
            ], 422);

        }
    }

    public function customerDelete(Request $request)
    {
        try {
            $user_id = $request->header('user_id');
            if (! $user_id) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User ID is required in the header',
                ], 400);
            }

            $customer = Customer::where('id', $request->id)->where('user_id', $user_id)->first();
            if (! $customer) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Customer not found',
                ], 404);
            }

            $customer->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

    }
}
