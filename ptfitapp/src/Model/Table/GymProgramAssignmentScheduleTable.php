<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class GymProgramAssignmentScheduleTable extends Table{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("GymProgram",["foreignKey"=>"program_id"]);
		$this->belongsTo("GymWorkout",["foreignKey"=>"workout_id"]);
		
		$this->belongsTo("GymLevels",  ["foreignKey"=>"level_id"]);
		$this->belongsTo("GymExercise",["foreignKey"=>"exercise_id"]);
		$this->belongsTo("GymCategory",["foreignKey"=>"category_id"]);
		$this->belongsTo("GymMod",     ["foreignKey"=>"mod_id"]);
		
	}	
}