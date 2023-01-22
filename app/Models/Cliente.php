<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function rules()
    {
        return [
            'nome' => 'required|min:3|max:40'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',

            'nome.min' => 'O nome deve ter 3 caracteres no minímo',
            'nome.min' => 'O nome deve ter 40 caracteres no máximo'
        ];
    }
}
