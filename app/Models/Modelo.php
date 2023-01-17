<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'
    ];

    public function rules(): array
    {
        return [
            'marca_id' => 'exists:marcas,id', // verifica se o id existe na table marcas
            'nome' => 'required|', Rule::unique('modelos')->ignore($this->id, 'id'). '|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5', // aceita numeros de 1 a 5
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean', // a validação aceita true ou false, 0 e 1, "0" e "1"
            'abs' => 'required|boolean'
        ];
    }

    public function feedback(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',

            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no minímo 3 caracteres',

            'imagem.mimes' => 'O arquivo deve ser uma imagem PNG, JPEG ou JPG',

            'numero_portas.digits_between' => 'O número de portas deve ser entre 1 e 5',
            'lugares.digits_between' => 'O número de lugares deve ser entre 1 e 20',
        ];
    }
}
