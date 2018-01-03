<?php
namespace App\Model\Table;
use Cake\ORM\Table;

Class GymProgramAssignmentTable extends Table{
	public function initialize(array $config)
	{
	    $this->addBehavior("Timestamp");
	    $this->belongsTo("GymProgram",["foreignKey"=>"program_id"]);
	    $this->belongsTo("GymMember",["foreignKey"=>"member_id"]);
		
	}
}