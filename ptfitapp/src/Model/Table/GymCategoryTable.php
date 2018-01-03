<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class GymCategoryTable extends Table{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");	
		$this->belongsTo("GymMember",["foreignKey"=>"trainer_id"]);
	}	
}