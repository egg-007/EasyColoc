<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, Colocation $colocation)
    {
        $validated = $request->validated();

        Payment::create([
            'colocation_id' => $colocation->id,
            'from_user_id'  => $validated['from_user_id'],
            'to_user_id'    => $validated['to_user_id'],
            'amount'        => $validated['amount'],
            'paid_at'       => now(),
        ]);

        return back()->with('success', 'Payment recorded.');
    }
}