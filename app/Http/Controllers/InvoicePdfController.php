<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(
        Invoice $invoice
    )
    {
        $invoice->load([
            'customer',
            'booking.services.service',
            'booking.services.employee',
            'booking.products.product',
        ]);

        $pdf = Pdf::loadView(
            'pdf.invoice',
            compact('invoice')
        );

        return $pdf->download(
            'invoice-' .
            $invoice->invoice_number .
            '.pdf'
        );
    }
}