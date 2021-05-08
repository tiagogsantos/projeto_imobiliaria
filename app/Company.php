<?php

namespace LaraDev;

use LaraDev\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user',
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secondary',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city'
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }

    public function setDocumentCompanyAttribute($value)
    {
        $this->attributes['document_company'] = $this->clearField($value);
    }

    // Retornando o CPF com as pontuações
    public function getDocumentCompanyAttribute($value)
    {
        return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) . '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
    }

    private function clearField (?string $param)
    {
        // Na verificação abaixo estou convertendo de nulo para vazio
        if (empty($param)) {
            return '';
        }

        // Removendo pontuação de CPF e convertendo apenas para numeros no banco de dados
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }

}
