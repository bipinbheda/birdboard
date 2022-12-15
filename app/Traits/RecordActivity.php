<?php
namespace App\Traits;

trait RecordActivity {

	public $oldAttributes = [];

	public static function bootRecordActivity()
	{
		foreach (self::$recordableEvents as $event) {
			static::$event(function($model) use ($event) {
				$model->recordActivity($model->activityDescription($event));
			});

			if ( $event == 'updated' ) {
				static::updating(function($model) {
					$model->oldAttributes = $model->getRawOriginal();
				});
			}
		}
	}

	protected function activityDescription($event)
	{
		return $event. '_' .strtolower(class_basename($this));
		/*if ( class_basename($this) != 'Project' ) {
		}
		return $event;*/
	}

	public function recordActivity($description)
	{
		$project = class_basename($this) == 'Project' ?  $this : $this->project;

		$data = [
			'description' => $description,
			'changes' => $this->activityChanges(),
			'user_id' => auth()->id() ?? $project->id,
			'project_id' => $project->id
		];
		$this->activity()->create($data);
	}

	public function activityChanges()
	{
		if ( $this->wasChanged() ) {
			return [
				'before' => \Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
				'after' => \Arr::except($this->getChanges(), 'updated_at'),
			];
		}

		return [];
	}

	public static function recordableEvents()
	{
		if (isset(static::$recordableEvents)) {
			return static::$recordableEvents;
		}
		return ['created','updated', 'deleted'];
	}
}