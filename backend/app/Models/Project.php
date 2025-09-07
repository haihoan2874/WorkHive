<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'owner_id',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    //Quan hệ với User (owner)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    //Quan hệ với Task
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
