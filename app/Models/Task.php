<?php

namespace App\Models;

use App\Days;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'expiry',
        'type',
        'status',
        'repeats',
    ];
    /** @use HasFactory<\Database\Factories\TasksFactory> */
    use HasFactory;
    protected function casts(): array
    {
        return [
            'repeats' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDaysAttribute()
    {
        $days = [];
        foreach ($this->repeats as $day) {
            $days[] = Days::from($day)->name;
        }
        return $days;
    }

    public function getIsTodayAttribute()
    {
        $today = now()->dayOfWeek();
        if (in_array($today, $this->repeats)) {
            return true;
        }
        return false;
    }
}
