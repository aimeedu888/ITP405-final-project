<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirlGroup extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public function albums()
    {
        return $this->hasMany(Album::class,"group","id");
    }
}
