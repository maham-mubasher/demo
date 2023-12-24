<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'description', 'list_group_id'];

    public function settings()
    {
        return $this->hasOne(Setting::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
