<?php

namespace App\Livewire\PaymentProofs;

use App\Models\Invoice;
use App\Models\PaymentProof;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public PaymentProof $paymentProof;

    public function mount(
        PaymentProof $paymentProof
    )
    {
        $this->paymentProof =
            $paymentProof->load([
                'booking.customer'
            ]);
    }

    public function approve()
    {
        if (
            $this->paymentProof->status
            !== 'pending'
        ) {
            return;
        }

        DB::transaction(function () {
            $this->paymentProof->loadMissing('booking');
            $booking = $this->paymentProof->booking;

            $this->paymentProof->update([
                'status' => 'approved',
                'validated_at' => now(),
            ]);

            Invoice::updateOrCreate(
                [
                    'booking_id' => $this->paymentProof->booking_id,
                ],
                [
                    'invoice_number' => Invoice::query()
                        ->where('booking_id', $this->paymentProof->booking_id)
                        ->value('invoice_number')
                        ?? 'INV-' . now()->format('YmdHis'),
                    'customer_id' => $booking->customer_id,
                    'subtotal' => $booking->total_amount,
                    'discount' => 0,
                    'grand_total' => $booking->total_amount,
                    'payment_status' => 'paid',
                    'payment_method' => 'QRIS',
                    'cash_received' => $booking->total_amount,
                    'cash_change' => 0,
                    'invoice_sent' => false,
                ]
            );

            if ($booking->status === 'pending') {
                $booking->update([
                    'status' => 'confirmed',
                ]);
            }
        });

        $this->paymentProof->refresh()->load([
            'booking.customer'
        ]);

        session()->flash(
            'success',
            'Bukti pembayaran disetujui dan invoice ditandai paid.'
        );
    }

    public function reject()
    {
        if (
            $this->paymentProof->status
            !== 'pending'
        ) {
            return;
        }

        $this->paymentProof->update([
            'status' => 'rejected',
            'validated_at' => now(),
        ]);

        $this->paymentProof->refresh();
    }

    public function render()
    {
        return view(
            'livewire.payment-proofs.show'
        )->layout('layouts.app');
    }
}
