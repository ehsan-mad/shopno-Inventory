<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
            }            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData($sale);

            // Generate PDF using PDF-optimized template
            $pdf = Pdf::loadView('invoices.pdf-template', $invoiceData);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true
            ]);

            // Generate filename
            $filename = 'invoice_' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf';

            // Return PDF download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to download invoice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }    /**
     * Preview invoice (HTML view)
     */
    public function previewInvoice(Request $request)
    {
        try {
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
            Log::error('Invoice preview error: ' . $e->getMessage(), [
                'sale_id' => $request->sale_id,
                'user_id' => $request->header('user_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to preview invoice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show invoices index page
     */
    public function index()
    {
        return view('invoices.index');
    }

    /**
     * Get all invoices for the user (API endpoint)
     */
    public function getInvoices(Request $request)
    {
        $userId = $request->header('user_id');
        
        if (!$userId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required in header.'
            ], 401);
        }

        try {
            $sales = Sale::where('user_id', $userId)
                        ->with(['customer', 'saleItems.product'])
                        ->orderBy('created_at', 'desc')
                        ->get();            $invoices = $sales->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'invoice_number' => 'INV-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'sale_number' => str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $sale->customer->name ?? 'N/A',
                    'customer_email' => $sale->customer->email ?? '',
                    'sale_date' => $sale->sale_date,
                    'total_amount' => $sale->total ?? 0,
                    'subtotal' => $sale->total ?? 0,
                    'discount_amount' => $sale->discount ?? 0,
                    'tax_amount' => $sale->tax ?? 0,
                    'notes' => $sale->notes ?? '',
                    'status' => $sale->status ?? 'completed',
                    'items_count' => $sale->saleItems->count(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $invoices,
                'total' => $invoices->count(),
                'total_amount' => $invoices->sum('total_amount')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch invoices.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }    /**
     * Prepare invoice data for PDF generation
     */
    private function prepareInvoiceData($sale)
    {
        return [
            'invoice' => [
                'number' => 'INV-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->format('d/m/Y'),
                'due_date' => Carbon::now()->addDays(30)->format('d/m/Y'),
            ],
            'company' => [
                'name' => ($sale->user->first_name ?? '') . ' ' . ($sale->user->last_name ?? ''),
                'address' => 'Your Company Address',
                'city' => 'Your City, Country',
                'phone' => 'Your Phone Number',
                'email' => $sale->user->email ?? 'info@shopno.com',
            ],
            'customer' => [
                'name' => $sale->customer->name ?? 'N/A',
                'email' => $sale->customer->email ?? 'N/A',
                'phone' => $sale->customer->phone ?? 'N/A',
                'address' => $sale->customer->address ?? 'N/A',
                'city' => $sale->customer->city ?? 'N/A',
            ],
            'sale' => [
                'number' => str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                'date' => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('d/m/Y') : Carbon::now()->format('d/m/Y'),
                'subtotal' => $sale->total ?? 0,
                'discount' => $sale->discount ?? 0,
                'tax' => $sale->tax ?? 0,
                'total' => $sale->total ?? 0,
                'notes' => $sale->notes ?? '',
            ],
            'items' => $sale->saleItems ? $sale->saleItems->map(function($item) {
                return [
                    'name' => $item->product->name ?? 'Unknown Product',
                    'sku' => $item->product->sku ?? 'PRD-' . ($item->product->id ?? 'UNK'),
                    'quantity' => $item->quantity ?? 0,
                    'unit_price' => $item->price ?? 0,
                    'total' => $item->total ?? 0,
                ];
            }) : collect(),
        ];
    }
}
