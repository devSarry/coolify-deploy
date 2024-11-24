<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduledMovie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'movie_program_id',
        'movie_id',
        'scheduled_time',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movieProgram(): BelongsTo
    {
        return $this->belongsTo(MovieProgram::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(SearchableMovie::class, 'movie_id');
    }

    public function formattedScheduledTime(): string
    {
        $scheduledTime = $this->scheduled_time;

        if ($scheduledTime->isFuture()) {
            if ($scheduledTime->diffInWeeks() <= 3) {
                return $scheduledTime->diffForHumans(['parts' => 1, 'join' => true]) .
                    ' at ' . $scheduledTime->format('H:i');
            } else {
                return $scheduledTime->translatedFormat('d F \\a\\t H:i');
            }
        }

        return $scheduledTime->translatedFormat('d F \\a\\t H:i');
    }


}
