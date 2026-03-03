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
        if (!auth()->check()) {
            return false;
        }

        $membership = Membership::where('user_id', auth()->id())
            ->whereNull('left_at')
            ->first();

        $category = $this->route('category');

        return $membership 
            && $membership->role === 'Owner' 
            && $category 
            && $category->colocation_id === $membership->colocation_id;
    }

  
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
