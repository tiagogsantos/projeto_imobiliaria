<?php

namespace LaraDev;

use LaraDev\Company;
use LaraDev\Support\Cropper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'genre',
        'document',
        'document_secondary',
        'document_secondary_complement',
        'date_of_birth',
        'place_of_birth',
        'civil_status',
        'cover',
        'occupation',
        'income',
        'company_work',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
        'telephone',
        'cell',
        'type_of_communion',
        'spouse_name',
        'spouse_genre',
        'spouse_document',
        'spouse_document_secondary',
        'spouse_document_secondary_complement',
        'spouse_date_of_birth',
        'spouse_place_of_birth',
        'spouse_occupation',
        'spouse_income',
        'spouse_company_work',
        'lessor',
        'lessee',
        'admin',
        'client'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        // Fazendo relacionamento que um usuário pode ter mais de uma empresa
        return $this->hasMany(Company::class, 'user', 'id');
    }

    public function properties ()
    {
        // Fazendo relacionamento de usuario com properties
        return $this->hasMany(Property::class, 'user', 'id');
    }

    public function getUrlCoverAttribute()
    {
        if(!empty($this->cover)){
            return Storage::url(Cropper::thumb($this->cover, 500, 500));
        }

        return '';
    }

    public function scopeLessors($query) 
    {
        return $query->where('lessor', true);
    }

    public function scopeLessees($query) 
    {
        return $query->where('lessee', true);
    }

    public function setLessorAttribute($value)
    {
        // Se o campo check do formulario for preenchido ele receberá 1 (verdadeiro) no banco de dados, caso contrario será 0 (falso)
        $this->attributes['lessor'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setLesseeAttribute($value)
    {
        // Se o campo check do formulario for preenchido ele receberá 1 (verdadeiro) no banco de dados, caso contrario será 0 (falso)
        $this->attributes['lessee'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setDocumentAttribute($value)
    {
        $this->attributes['document'] = $this->clearField($value);
    }

    // Retornando o CPF com as pontuações na visão blade
    public function getDocumentAttribute($value)
    {
        return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3). '-' . substr($value, 9, 2);
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = $this->convertStringToDate($value);
    }

    // Retornando a data para o formato brasileiro
    public function getDateOfBirthAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    // Retornando o valor para real
    public function getIncomeAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function setIncomeAttribute ($value)
    {
        $this->attributes['income'] = floatval($this->convertStringToDouble($value));
    }

    public function setZipcodeAttribute ($value)
    {
        $this->attributes['zipcode'] = $this->clearField($value);
    }

    public function setTelephoneAttribute ($value)
    {
        $this->attributes['telephone'] = $this->clearField($value);
    }

    public function setCellAttribute ($value)
    {
        $this->attributes['cell'] = $this->clearField($value);
    }

    /*public function setPasswordAttribute ($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }*/

    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            unset($this->attributes['password']);
            return;
        }

        $this->attributes['password'] = bcrypt($value);
    }

    public function setSpouseDocumentAttribute($value)
    {
        $this->attributes['spouse_document'] = $this->clearField($value);
    }

    public function getSpouseDocumentAttribute($value)
    {
        return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3). '-' . substr($value, 9, 2);
    }

    public function setSpouseDateOfBirthAttribute($value)
    {
        $this->attributes['spouse_date_of_birth'] = $this->convertStringToDate($value);
    }

    public function getSpouseDateOfBirthAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setSpouseIncomeAttribute ($value)
    {
        $this->attributes['spouse_income'] = floatval($this->convertStringToDouble($value));
    }

    public function getSpouseIncomeAttribute ($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function setAdminAttribute($value)
    {
        // Se o campo check do formulario for preenchido ele receberá 1 (verdadeiro) no banco de dados, caso contrario será 0 (falso)
        $this->attributes['admin'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setClientAttribute($value)
    {
        // Se o campo check do formulario for preenchido ele receberá 1 (verdadeiro) no banco de dados, caso contrario será 0 (falso)
        $this->attributes['client'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    private function convertStringToDouble(?string $param)
    {
        // Na verificação abaixo estou convertendo de vario para nulo
        if (empty($param)) {
            return null;
        }

        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function convertStringToDate(string $param)
    {
        // Na verificação abaixo estou convertendo de vario para nulo
        if (empty($param)) {
            return null;
        }

        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-'. $month . '-' . $day))->format('Y-m-d');
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
