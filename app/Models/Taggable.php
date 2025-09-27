<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Taggable extends Pivot
{
    protected $table = 'taggables';

    public $incrementing = true;

    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type'
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function taggable()
    {
        return $this->morphTo();
    }
}
