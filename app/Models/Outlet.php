<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
    public static function getFields() {
        return [
            'name' => ['required', 'max:50'],
            'description' => ['required', 'max:50']
        ];
    }
    use HasFactory;
}
