<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Colocation;

class ExpenseController extends Controller
{
    public function store(StoreExpenseRequest $request, Colocation $colocation)
    {
        $user = auth()->user();

        $data = $request->validated();

        // Optional: additional rule check for payer_id and category_id in Form Request, 
        // doing a quick sanity check here for safety.
        $payerIsMember = $colocation->memberships()
            ->where('user_id', $data['payer_id'])
            ->whereNull('left_at')
            ->exists();

        abort_if(!$payerIsMember, 403, 'Le payeur sélectionné n\'appartient pas à la colocation.');

        abort_if(!\App\Models\Category::where('id', $data['category_id'])->where('colocation_id', $colocation->id)->exists(), 403, 'Catégorie invalide.');

        \App\Models\Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $data['payer_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Expense added successfully.');
    }
}
