<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class ForgetPasswordTable extends Table{
	
	public function initialize(array $config)
	{	
		$this->BelongsTo("GymMember");		
	}
}