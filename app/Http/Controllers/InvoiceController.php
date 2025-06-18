<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Download invoice as PDF
     */
    public function downloadInvoice(Request $request)
    {
        $userId = $request->header('user_id');
        
        if (!$userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'sale_id' => 'required|exists:sales,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Get sale with all data needed for invoice
            $sale = Sale::where('id', $request->sale_id)
                       ->where('user_id', $userId)
                       ->with([
                           'customer',
                           'saleItems.product',
                           'user'
                       ])
                       ->first();

            if (!$sale) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sale not found or unauthorized.'
                ], 404);
            }

            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData($sale);

            // Generate PDF
            $pdf = Pdf::loadView('invoices.template', $invoiceData);
            $pdf->setPaper('A4', 'portrait');

            // Generate filename
            $filename = 'invoice_' . $sale->sale_number . '_' . date('Y-m-d') . '.pdf';

            // Return PDF download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to download invoice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview invoice (HTML view)
     */
    public function previewInvoice(Request $request)
    {
        $userId = $request->header('user_id');
        
        if (!$userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'sale_id' => 'required|exists:sales,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $sale = Sale::where('id', $request->sale_id)
                       ->where('user_id', $userId)
                       ->with([
                           'customer',
                           'saleItems.product',
                           'user'
                       ])
                       ->first();

            if (!$sale) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sale not found or unauthorized.'
                ], 404);
            }

            $invoiceData = $this->prepareInvoiceData($sale);

            return view('invoices.template', $invoiceData);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to preview invoice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare invoice data for PDF generation
     */
    private function prepareInvoiceData($sale)
    {
        return [
            'invoice' => [
                'number' => 'INV-' . $sale->sale_number,
                'date' => Carbon::now()->format('d/m/Y'),
                'due_date' => Carbon::now()->addDays(30)->format('d/m/Y'),
            ],
            'company' => [
                'name' => $sale->user->name ?? 'Your Company Name',
                'address' => 'Your Company Address',
                'city' => 'Your City, Country',
                'phone' => 'Your Phone Number',
                'email' => $sale->user->email ?? 'your-email@company.com',
            ],
            'customer' => [
                'name' => $sale->customer->name,
                'email' => $sale->customer->email,
                'phone' => $sale->customer->phone,
                'address' => $sale->customer->address ?? 'N/A',
                'city' => $sale->customer->city,
            ],
            'sale' => [
                'number' => $sale->sale_number,
                'date' => Carbon::parse($sale->sale_date)->format('d/m/Y'),
                'subtotal' => $sale->subtotal,
                'discount' => $sale->discount_amount,
                'tax' => $sale->tax_amount,
                'total' => $sale->total_amount,
                'notes' => $sale->notes,
            ],
            'items' => $sale->saleItems->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total' => $item->total,
                ];
            }),
        ];
    }
}
