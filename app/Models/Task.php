<?php

namespace App\Models;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, RecordActivity;

    protected $fillable = ['body', 'completed'];

    protected $touches = ['project'];

    protected static $recordableEvents = ['created','deleted'];

    protected $casts = [
        'completed' => 'boolean'
    ];

/*    protected static function boot()
    {
        parent::boot();

        static::created(function($task){
            $task->project->recordActivity('created_task');
        });

        static::updated(function($task){
            if (! $task->completed) return;
            // $task->project->recordActivity('completed_task');
        });

        static::deleted(function($task){
            $task->project->recordActivity('deleted_task');
        });
    }*/

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function path() {
        return "projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

}
