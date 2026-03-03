<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $colocation = $this->route('colocation') ?? auth()->user()->colocations()->wherePivot('left_at', null)->first();

        if (!$colocation) {
            return false;
        }

        $isMember = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->exists();

        if (!$isMember) {
            return false;
        }

        return true;
    }

   
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'category_id'  => ['required', 'exists:categories,id'],
            'payer_id'     => ['required', 'exists:users,id'],
        ];
    }
}
