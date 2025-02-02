<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $type
 */
class PrimeUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price' => 'required',
            'name'=>'required',
            'plantype'=>'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name field is required',
            'price.required' => 'price field is required',
            'plantype.required' => 'plan type field is required',
        ];
    }

}
