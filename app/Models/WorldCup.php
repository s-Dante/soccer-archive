<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorldCup extends Model
{
    use HasFactory;


    /**
     * Un mundial puede tener muchas publicaciones.
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
