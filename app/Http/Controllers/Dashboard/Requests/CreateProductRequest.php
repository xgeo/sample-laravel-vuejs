<?php
namespace App\Http\Controllers\Dashboard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProduteRequest extends FormRequest 
{
    public function rules() {
        return [
            'name'                      => 'required',
            'description'               => 'required',
            'image'                     => 'required',
            'price'                     => 'required',
            'product_categories_id'     => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}