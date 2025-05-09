<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'category';

    function childTests()
    {
        return $this->hasMany(Test::class, 'parent');
    }

    function childCategories()
    {
        return $this->hasMany(Category::class, 'parent');
    }

}
