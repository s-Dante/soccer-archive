<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use HasFactory;

    /**
     * Una publicación pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una publicación pertenece a un mundial.
     */
    public function worldCup(): BelongsTo
    {
        return $this->belongsTo(WorldCup::class);
    }

    /**
     * Una publicación pertenece a una categoría.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Una publicación puede tener muchos archivos multimedia.
     */
    public function multimedia(): HasMany
    {
        return $this->hasMany(Multimedia::class);
    }

    /**
     * Una publicación puede tener muchos comentarios.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Una publicación puede tener muchas interacciones.
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class);
    }
}
