<?php

namespace App\Models;

use App\Models\User;
use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, RecordActivity;

    protected $guarded = [];

    protected static $recordableEvents = ['created','updated'];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function addTask($body) {
        return $this->tasks()->create($body);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

}
