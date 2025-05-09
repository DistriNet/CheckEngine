<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $table = 'test';

    public function category() {
        return $this->belongsTo(Category::class, 'parent');
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}
