<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'medium',
        'reach',
        'outlet',
        'covered',
        'outlet_id'
    ];
    public static function getFields() {
        return [
            'name' => ['required', 'max:50'],
            'description' => ['required', 'max:50'],
            'outlet' => ['required', 'max:50'],
            'reach' => ['required'],
            'type' => ['required'],
            'medium' => ['required']
        ];
    }
    use HasFactory;
}
