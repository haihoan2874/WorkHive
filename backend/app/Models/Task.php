<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    //quan hệ với project
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'assignee_id',
        'status',
        'due_date',
    ];

    //quan hệ với date
    protected $casts = [
        'due_date' => 'date',
    ];

    //Quan hệ với Project
    public function project(): BelongsTo
    {
        return $this->belongsTo((Project::class));
    }

    //Quan hệ với User (assignee)
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
