<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollPdfController extends Controller
{
    public function download(
        Payroll $payroll
    )
    {
        $payroll->load([
            'employee',
            'details',
        ]);

        $pdf = Pdf::loadView(
            'pdf.payroll',
            compact('payroll')
        );

        return $pdf->download(
            'payroll-' .
            $payroll->employee?->employee_code .
            '-' .
            $payroll->month .
            '-' .
            $payroll->year .
            '.pdf'
        );
    }
}