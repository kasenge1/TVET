<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set order when creating
        static::creating(function ($note) {
            if (is_null($note->order) || $note->order === 0) {
                $maxOrder = static::where('unit_id', $note->unit_id)->max('order');
                $note->order = ($maxOrder ?? 0) + 1;
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_id',
        'title',
        'content',
        'order',
        'is_published',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Get the unit that owns this note.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the user who created this note.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only published notes.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to order by the order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
