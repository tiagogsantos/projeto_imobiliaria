<?php

namespace LaraDev\Http\Requests\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class Company extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Se o usuário tiver logado no painel ele pode fazer o cadastro, caso o contrario o mesmo será barrado
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required',
           'social_name' => 'required|min:3|max:191',
           'alias_name' => 'required',
           'document_company' => 'required',
           'document_company_secondary' => 'required',

           // Address - Endereço do usuário
           'zipcode' => 'required|min:8|max:9',
           'street' => 'required',
           'number' => 'required',
           'neighborhood' => 'required',
           'state' => 'required',
           'city' => 'required',
        ];
    }
}
