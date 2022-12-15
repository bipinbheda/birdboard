<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $old = [];

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

    public function recordActivity($description)
    {
        $data = [
            'description' => $description,
            'changes' => $this->activityChanges($description),
            'user_id' => auth()->id() ?? $this->owner->id
        ];
        $this->activity()->create($data);

        /*Activity::create([
            'project_id' => $this->id,
            'description' => $type,
        ]);*/
    }

    public function activityChanges($description)
    {
        if ( $description != 'updated' ) {
            return [];
        }
        return [
                'before' => \Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => \Arr::except($this->getChanges(), 'updated_at'),
            ];
    }
}
