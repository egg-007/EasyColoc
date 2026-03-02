<?php

namespace App\Http\Requests;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Must be authenticated and must be Owner of the active colocation
        if (!auth()->check()) {
            return false;
        }

        $membership = Membership::where('user_id', auth()->id())
            ->whereNull('left_at')
            ->first();

        // Also verify the category being updated belongs to their colocation
        $category = $this->route('category');

        return $membership 
            && $membership->role === 'Owner' 
            && $category 
            && $category->colocation_id === $membership->colocation_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
