<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * Atribut yang dapat diisi secara massal.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'description'
    ];

    protected $primaryKey = 'id_term';

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'slug' => 'string',
            'name' => 'string',
            'description' => 'string',
        ];
    }
}
