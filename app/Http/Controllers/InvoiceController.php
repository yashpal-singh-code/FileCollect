<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST INVOICES
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Invoice::where('user_id', Auth::id());

        // 🔍 Search (invoice number)
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        // 🔍 Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW INVOICE
    |--------------------------------------------------------------------------
    */
    // public function show(Invoice $invoice)
    // {
    //     $this->authorizeInvoice($invoice);

    //     $customerCompany = CompanySetting::where('owner_id', $invoice->user_id)->first();

    //     // 👉 If ?pdf=1 → return PDF
    //     if (request()->has('pdf')) {

    //         $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
    //             ->setPaper('a4', 'portrait')
    //             ->setOptions([
    //                 'isHtml5ParserEnabled' => true,
    //                 'isRemoteEnabled' => true,
    //                 'defaultFont' => 'DejaVu Sans',
    //             ]);

    //         return $pdf->stream($invoice->invoice_number . '.pdf');
    //     }

    //     // 👉 Normal HTML view
    //     return view('invoices.show', [
    //         'invoice' => $invoice,
    //         'customerCompany' => $customerCompany
    //     ]);
    // }

    public function show(Invoice $invoice)
    {
        $customerCompany = null; // optional if you use company table

        return view('invoices.show', compact('invoice', 'customerCompany'));
    }

    public function download(Invoice $invoice)
    {
        $customerCompany = null;

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'customerCompany'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }
    /*
    |--------------------------------------------------------------------------
    | EDIT INVOICE
    |--------------------------------------------------------------------------
    */
    public function edit(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        return view('invoices.edit', compact('invoice'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE INVOICE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $validated = $request->validate([
            'status' => 'required|in:paid,pending,failed',
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE INVOICE
    |--------------------------------------------------------------------------
    */
    public function destroy(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->delete();

        return back()->with('success', 'Invoice deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */
    // public function download(Invoice $invoice)
    // {
    //     $this->authorizeInvoice($invoice);

    //     $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
    //         ->setPaper('a4', 'portrait')
    //         ->setOptions([
    //             'isHtml5ParserEnabled' => true,
    //             'isRemoteEnabled' => true,
    //             'defaultFont' => 'DejaVu Sans',
    //             'isPhpEnabled' => true,
    //         ]);

    //     return $pdf->download($invoice->invoice_number . '.pdf');
    // }

    /*
    |--------------------------------------------------------------------------
    | SECURITY CHECK
    |--------------------------------------------------------------------------
    */
    private function authorizeInvoice(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
    }
}
