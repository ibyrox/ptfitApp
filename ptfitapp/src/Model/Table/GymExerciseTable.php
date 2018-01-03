<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class GymExerciseTable extends Table{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");	
		$this->belongsTo("GymCategory",["foreignKey"=>"category_id"]);
		$this->belongsTo("GymMember",["foreignKey"=>"trainer_id"]);
	}	
}