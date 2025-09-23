<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory;

    /**
     * Una categoría puede tener muchas publicaciones.
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
