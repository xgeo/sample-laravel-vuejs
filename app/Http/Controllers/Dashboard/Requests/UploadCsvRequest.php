<?php
namespace App\Http\Controllers\Dashboard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCsvRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => 'mimetypes:text/plain,text/csv|required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}