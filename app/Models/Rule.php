<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
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
