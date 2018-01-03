<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class GymLevelsController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function levelList() {
		$session = $this->request->session()->read("User");
		if($session["role_name"]=="member") {
		    
		}if($session["role_name"]=="staff_member") {
		    $data = $this->GymLevels->find("all")->where(["trainer_id"=>$session['id']])->hydrate(false)->toArray();
		    //$data = $this->GymMod->find()->where(["trainer_id"=>$session['id']]);
		}else{
		    $data = $this->GymLevels->find("all")->contain(["GymMember","Category"])->select($this->GymMod)->select(["GymMember.first_name","GymMember.last_name","Category.name"])->hydrate(false)->toArray();
		}
		$this->set("data",$data);		
	}	
	
	public function addLevel() 
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Level"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator"){
	        $members = $this->GymLevels->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"staff_member"]);
		    $members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} 
		$this->set("members",$members);		
		
		if($this->request->is("post")) { 
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    
		    $this->loadComponent("GYMFunction");
		    $mod = $this->GymLevels->newEntity();
		    $this->request->data["trainer_id"] = $uid;
		    $mod = $this->GymLevels->patchEntity($mod,$this->request->data);
		    if($this->GymLevels->save($mod)) {
		        $this->Flash->Success(__("Success! Record Added Successfully."));
		        return $this->redirect(["action"=>"levelList"]);
			} else{
				if($row->errors()) {
					foreach($row->errors() as $error) {
						foreach($error as $key=>$value) {
							$this->Flash->error(__($value));
						}						
					}
				}
			}
		}
	}
	
	public function editLevel($id){
	    $this->set("title",__("Edit Level"));
	    $row1 = $this->GymLevels->get($id);
	    $row = $row1->toArray();
	    $this->set("edit",true);
	    $this->set("data",$row);
	    $this->render("addLevel");
	    if($this->request->is("post"))
	    {
	        $this->loadComponent("GYMFunction");
	        $mod = $this->GymLevels->patchEntity($row1,$this->request->data);
	        if($this->GymLevels->save($mod)) {
	            $this->Flash->success(__("Success! Record Updated Successfully"));
	            return $this->redirect(["action"=>"levelList"]);
	        }
	    }
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		// $members_actions = ["categoryList"];
		$staff_acc_actions = ["modList"];
		switch($role_name)
		{			
			// CASE "member":
				// if(in_array($curr_action,$members_actions))
				// {return true;}else{return false;}
			// break;
			
			// CASE "staff_member":
				// if(in_array($curr_action,$staff_acc_actions))
				// {return true;}else{ return false;}
			// break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}