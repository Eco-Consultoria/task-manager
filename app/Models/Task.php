<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'task_solution',
        'note',
        'status',
        'priority',
        'group_id',
        'created_by',
        'completed_by',
        'approved_by',
        'cancelled_by',
        'updated_by',
        'started_at',
        'cancelled_at',
        'copleted_at',
        'approved_at',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'started_at',
        'cancelled_at',
        'copleted_at',
        'approved_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finisher()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
