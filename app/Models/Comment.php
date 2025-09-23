<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
    * Un comentario pertenece a un usuario.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Un comentario pertenece a una publicación.
    */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }
}
