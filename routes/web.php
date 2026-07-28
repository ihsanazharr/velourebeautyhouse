<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Owner\Dashboard as OwnerDashboard;
use App\Livewire\Employees\Index as EmployeeIndex;
use App\Livewire\Employees\Create as EmployeeCreate;
use App\Livewire\Employees\Edit as EmployeeEdit;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Products\Create as ProductCreate;
use App\Livewire\Products\Edit as ProductEdit;
use App\Livewire\Customers\Index as CustomerIndex;
use App\Livewire\Customers\Create as CustomerCreate;
use App\Livewire\Customers\Edit as CustomerEdit;
use App\Livewire\Bookings\Index as BookingIndex;
use App\Livewire\Bookings\Create as BookingCreate;
use App\Livewire\Bookings\Edit as BookingEdit;
use App\Livewire\Services\Index as ServiceIndex;
use App\Livewire\Services\Create as ServiceCreate;
use App\Livewire\Services\Edit as ServiceEdit;
use App\Livewire\Attendances\Index as AttendanceIndex;
use App\Livewire\Attendances\Create as AttendanceCreate;
use App\Livewire\Attendances\Edit as AttendanceEdit;
use App\Livewire\EmployeeAdjustments\Index as EmployeeAdjustmentIndex;
use App\Livewire\EmployeeAdjustments\Create as EmployeeAdjustmentCreate;
use App\Livewire\EmployeeAdjustments\Edit as EmployeeAdjustmentEdit;
use App\Livewire\Payrolls\Index as PayrollIndex;
use App\Livewire\Payrolls\Generate as PayrollGenerate;
use App\Livewire\Payrolls\Show as PayrollShow;
use App\Livewire\Invoices\Index as InvoiceIndex;
use App\Livewire\Invoices\Show as InvoiceShow;
use App\Livewire\PaymentProofs\Index as PaymentProofIndex;
use App\Livewire\PaymentProofs\Show as PaymentProofShow;
use App\Livewire\ServiceCategories\Index as ServiceCategoryIndex;
use App\Livewire\ServiceCategories\Create as ServiceCategoryCreate;
use App\Livewire\ServiceCategories\Edit as ServiceCategoryEdit;
use App\Livewire\Reports\Index as ReportIndex;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\PayrollPdfController;
use App\Livewire\Cashier\Dashboard as CashierDashboard;
use App\Livewire\Bookings\Calendar as BookingCalendar;
use App\Livewire\Booking\PublicSchedule as PublicSchedule;
use App\Livewire\Customer\BookingCreate as CustomerBookingCreate;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware([
    'auth',
    'role:customer'
])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get(
            '/dashboard',
            \App\Livewire\Customer\Dashboard::class
        )->name('dashboard');

        Route::get(
            '/booking',
            CustomerBookingCreate::class
        )->name('booking.create');

        Route::get(
            '/invoices/{invoice}',
            \App\Livewire\Invoices\Show::class
        )->name('invoices.show');

        Route::get(
            '/invoices/{invoice}/pdf',
            [\App\Http\Controllers\InvoicePdfController::class, 'download']
        )->name('invoices.pdf');
    });

Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

       /*
        |--------------------------------------------------------------------------
        | OWNER ONLY
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:owner'
        ])->group(function () {

            Route::get(
                '/dashboard',
                OwnerDashboard::class
            )->name('dashboard');

            Route::get(
                '/employees',
                EmployeeIndex::class
            )->name('employees.index');

            Route::get(
                '/employees/create',
                EmployeeCreate::class
            )->name('employees.create');

            Route::get(
                '/employees/{employee}/edit',
                EmployeeEdit::class
            )->name('employees.edit');

            Route::get(
                '/products',
                ProductIndex::class
            )->name('products.index');

            Route::get(
                '/products/create',
                ProductCreate::class
            )->name('products.create');

            Route::get(
                '/products/{product}/edit',
                ProductEdit::class
            )->name('products.edit');

            Route::get(
                '/adjustments',
                EmployeeAdjustmentIndex::class
            )->name('adjustments.index');

            Route::get(
                '/adjustments/create',
                EmployeeAdjustmentCreate::class
            )->name('adjustments.create');

            Route::get(
                '/adjustments/{adjustment}/edit',
                EmployeeAdjustmentEdit::class
            )->name('adjustments.edit');

            Route::get(
                '/payrolls',
                PayrollIndex::class
            )->name('payrolls.index');

            Route::get(
                '/payrolls/generate',
                PayrollGenerate::class
            )->name('payrolls.generate');

            Route::get(
                '/payrolls/{payroll}',
                PayrollShow::class
            )->name('payrolls.show');

            Route::get(
                '/payrolls/{payroll}/pdf',
                [PayrollPdfController::class, 'download']
            )->name('payrolls.pdf');

            Route::get(
                '/reports',
                ReportIndex::class
            )->name('reports.index');

            Route::get(
                '/settings',
                \App\Livewire\Owner\Settings::class
            )->name('settings.edit');
        });

        /*
        |--------------------------------------------------------------------------
        | OWNER + KASIR
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:owner|kasir'
        ])->group(function () {

            Route::get(
                '/customers',
                CustomerIndex::class
            )->name('customers.index');

            Route::get(
                '/customers/create',
                CustomerCreate::class
            )->name('customers.create');

            Route::get(
                '/customers/{customer}/edit',
                CustomerEdit::class
            )->name('customers.edit');

            Route::get(
                '/bookings',
                BookingIndex::class
            )->name('bookings.index');

            Route::get(
                '/bookings/create',
                BookingCreate::class
            )->name('bookings.create');

            Route::get(
                '/bookings/{booking}/edit',
                BookingEdit::class
            )->name('bookings.edit');

            Route::get(
                '/services',
                ServiceIndex::class
            )->name('services.index');

            Route::get(
                '/services/create',
                ServiceCreate::class
            )->name('services.create');

            Route::get(
                '/services/{service}/edit',
                ServiceEdit::class
            )->name('services.edit');

            Route::get(
                '/service-categories',
                ServiceCategoryIndex::class
            )->name('service-categories.index');

            Route::get(
                '/service-categories/create',
                ServiceCategoryCreate::class
            )->name('service-categories.create');

            Route::get(
                '/service-categories/{serviceCategory}/edit',
                ServiceCategoryEdit::class
            )->name('service-categories.edit');

            Route::get(
                '/invoices',
                InvoiceIndex::class
            )->name('invoices.index');

            Route::get(
                '/invoices/{invoice}',
                InvoiceShow::class
            )->name('invoices.show');

            Route::get(
                '/invoices/{invoice}/pdf',
                [InvoicePdfController::class, 'download']
            )->name('invoices.pdf');

            Route::get(
                '/payment-proofs',
                PaymentProofIndex::class
            )->name('payment-proofs.index');

            Route::get(
                '/payment-proofs/{paymentProof}',
                PaymentProofShow::class
            )->name('payment-proofs.show');

            // Route::get(
            //     '/bookings-calendar',
            //     BookingCalendar::class
            // )->name('bookings.calendar');

            // Route::get(
            //     '/public-schedule',
            //     PublicSchedule::class
            // )->name('public.schedule');

        });

        /*
        |--------------------------------------------------------------------------
        | SEMUA KARYAWAN
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'role:owner|kasir|hairstylist|nailist_senior|nailist_junior'
        ])->group(function () {

            Route::get(
                '/attendances',
                AttendanceIndex::class
            )->name('attendances.index');

            Route::get(
                '/attendances/create',
                AttendanceCreate::class
            )->name('attendances.create');

            Route::get(
                '/attendances/{attendance}/edit',
                AttendanceEdit::class
            )->name('attendances.edit');

        });

    });

   /*
    |--------------------------------------------------------------------------
    | CASHIER
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'auth',
        'verified',
        'role:kasir'
    ])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get(
            '/dashboard',
            CashierDashboard::class
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/customers',
            CustomerIndex::class
        )->name('customers.index');

        Route::get(
            '/customers/create',
            CustomerCreate::class
        )->name('customers.create');

        Route::get(
            '/customers/{customer}/edit',
            CustomerEdit::class
        )->name('customers.edit');

        /*
        |--------------------------------------------------------------------------
        | BOOKING
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/bookings',
            BookingIndex::class
        )->name('bookings.index');

        Route::get(
            '/bookings/create',
            BookingCreate::class
        )->name('bookings.create');

        Route::get(
            '/bookings/{booking}/edit',
            BookingEdit::class
        )->name('bookings.edit');

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE KIOSK
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/attendances',
            AttendanceIndex::class
        )->name('attendances.index');

        Route::get(
            '/attendances/create',
            AttendanceCreate::class
        )->name('attendances.create');

        Route::get(
            '/attendances/{attendance}/edit',
            AttendanceEdit::class
        )->name('attendances.edit');

        /*
        |--------------------------------------------------------------------------
        | INVOICE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/invoices',
            InvoiceIndex::class
        )->name('invoices.index');

        Route::get(
            '/invoices/{invoice}',
            InvoiceShow::class
        )->name('invoices.show');

        Route::get(
            '/invoices/{invoice}/pdf',
            [InvoicePdfController::class, 'download']
        )->name('invoices.pdf');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT PROOF
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payment-proofs',
            PaymentProofIndex::class
        )->name('payment-proofs.index');

        Route::get(
            '/payment-proofs/{paymentProof}',
            PaymentProofShow::class
        )->name('payment-proofs.show');


        Route::get(
            '/bookings-calendar',
            BookingCalendar::class
        )->name('bookings.calendar');

    });

require __DIR__.'/auth.php';
