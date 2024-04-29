<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function girlgroup()
    {
        return $this->belongsTo(GirlGroup::class, 'group');
    }
}
