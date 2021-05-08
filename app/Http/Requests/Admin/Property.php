<?php

namespace LaraDev\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class Property extends FormRequest
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
            'category' => 'required',
            'type' => 'required',
            'sale_price' => 'required_if:sale,on',
            'rent_price' => 'required_if:sale,on',
            'tribute' => 'required',
            'condominium' => 'required',
            'description' => 'required',
            'bedrooms' => 'required',
            'suites' => 'required',
            'bathrooms' => 'required',
            'rooms' => 'required',
            'garage' => 'required',
            'garage_covered' => 'required',
            'area_total' => 'required',
            'area_util' => 'required',

            // Address - Endereço do usuário
            'zipcode' => 'required|min:8|max:9',
            'street' => 'required',
            'number' => 'required',
            'neighborhood' => 'required',
            'state' => 'required',
            'city' => 'required'

        ];
    }
}
