<?php

namespace App\Http\Requests\ProfileSeeker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   =>  'required|string|max:255'
        ];
    }
}
