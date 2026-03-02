<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) return false;

        $colocation = $this->route('colocation');
        
        if (!$colocation) return false;

        return auth()->user()->memberships()
            ->where('colocation_id', $colocation->id)
            ->whereNull('left_at')
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_user_id' => ['required', 'exists:users,id'],
            'to_user_id'   => ['required', 'exists:users,id', 'different:from_user_id'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
