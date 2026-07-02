<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentsOverview extends Component
{
    use WithPagination;

    public function render()
    {
        $payments = Payment::with(['user', 'auction'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_payments' => Payment::count(),
            'payments_today' => Payment::whereDate('created_at', today())->count(),
        ];

        return view('livewire.admin.payments-overview', [
            'payments' => $payments,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
