<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatchRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! empty($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'repository_id' => 'required',
            'user_provider_id' => 'required'
        ];
    }
}
