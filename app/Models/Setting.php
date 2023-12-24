<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['field_id', 'required', 'sort_options','max_characters', 'max_characters_enabled'];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
