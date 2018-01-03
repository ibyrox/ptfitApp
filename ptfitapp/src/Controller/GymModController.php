<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class GymModController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function modList() {
		$session = $this->request->session()->read("User");
		if($session["role_name"]=="member") {
		    $mem_id = $this->GymMod->GymMember->find()->where(["id"=>$session["id"]])->select(["selected_membership"])->hydrate(false)->toArray();
		    $assigned_activity = $this->GymMod->MembershipActivity->find()->where(["membership_id"=>$mem_id[0]["selected_membership"]])->select(["activity_id"])->hydrate(false)->toArray();
		    
		    if(!empty($assigned_activity)) {
		        foreach($assigned_activity as $activity) {
		            $acivities_list[] = $activity["activity_id"];
		        }
		        $data = $this->GymMod->find()->where(["Category.id IN"=>$acivities_list]);
		        $data = $data->contain(["GymMember","Category"])->select($this->GymMod)->select(["GymMember.first_name","GymMember.last_name","Category.name"])->hydrate(false)->toArray();
		    }
		}if($session["role_name"]=="staff_member") {
		    $data = $this->GymMod->find("all")->where(["trainer_id"=>$session['id']])->hydrate(false)->toArray();
		    //$data = $this->GymMod->find()->where(["trainer_id"=>$session['id']]);
		}else{
		    $data = $this->GymMod->find("all")->contain(["GymMember","Category"])->select($this->GymMod)->select(["GymMember.first_name","GymMember.last_name","Category.name"])->hydrate(false)->toArray();
		}
		$this->set("data",$data);		
	}	
	
	public function addMod() 
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Mod"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {
		    //$members = $this->GymMod->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			//$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} else if($session["role_name"] == "administrator"){
	        $members = $this->GymMod->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"staff_member"]);
		    $members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} 
		$this->set("members",$members);		
		
		if($this->request->is("post")) { 
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    
		    $this->loadComponent("GYMFunction");
		    $mod = $this->GymMod->newEntity();
			$this->request->data["create_time"] = date("Y-m-d");
			$this->request->data["update_time"] = date("Y-m-d");
		    $this->request->data["update_user_id"] = date("Y-m-d");
		    $this->request->data["trainer_id"] = $uid;
		    $this->request->data["create_user_id"] = $uid;
		    $this->request->data["active"] = 1;
		    $mod = $this->GymMod->patchEntity($mod,$this->request->data);
		    if($this->GymMod->save($mod)) {
		        $this->Flash->Success(__("Success! Category Added to Trainer Successfully."));
		        return $this->redirect(["action"=>"modList"]);
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
	
	public function editMod($id){
	    $this->set("title",__("Edit Mod"));
	    $row1 = $this->GymMod->get($id);
	    $row = $row1->toArray();
	    $this->set("edit",true);
	    $this->set("data",$row);
	    $this->render("addMod");
	    if($this->request->is("post"))
	    {
	        $this->loadComponent("GYMFunction");
	        $mod = $this->GymMod->patchEntity($row1,$this->request->data);
	        if($this->GymMod->save($mod)) {
	            $this->Flash->success(__("Success! Record Updated Successfully"));
	            return $this->redirect(["action"=>"modList"]);
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