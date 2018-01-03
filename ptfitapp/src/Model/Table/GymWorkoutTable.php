<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class GymWorkoutTable extends Table{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");	
		$this->belongsTo("GymCategory",["foreignKey"=>"category_id"]);
		$this->belongsTo("GymExercise",["foreignKey"=>"exercise_id"]);
		$this->belongsTo("GymMod",["foreignKey"=>"mod_id"]);
		$this->belongsTo("GymLevels",["foreignKey"=>"level_id"]);
		$this->belongsTo("GymMember",["foreignKey"=>"trainer_id"]);
	}	
}