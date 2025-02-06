<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customerId'         => 'required|exists:customers,id',
            'items'              => 'required|array|min:1',
            'items.*.productId'  => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unitPrice'  => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
