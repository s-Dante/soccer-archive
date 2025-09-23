<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Multimedia extends Model
{
    use HasFactory;

    /**
    * Un archivo multimedia pertenece a una publicación.
    */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }
}
