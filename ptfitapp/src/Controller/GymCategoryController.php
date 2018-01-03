<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class GymCategoryController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function categoryList() {
		$session = $this->request->session()->read("User");
		if($session["role_name"]=="member") {
		    $mem_id = $this->GymCategory->GymMember->find()->where(["id"=>$session["id"]])->select(["selected_membership"])->hydrate(false)->toArray();
		}if($session["role_name"]=="staff_member") {
		    $data = $this->GymCategory->find("all")->where(["trainer_id"=>$session['id']])->hydrate(false)->toArray();
		    
		    //$data = $this->GymCategory->find()->where(["trainer_id"=>$session['id']]);
		}else{
		    $data = $this->GymCategory->find("all")->contain(["GymMember","Category"])->select($this->GymCategory)->select(["GymMember.first_name","GymMember.last_name","Category.name"])->hydrate(false)->toArray();
		}
		$this->set("data",$data);		
	}	
	
	public function addCategory() 
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Category"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {
		    //$members = $this->GymCategory->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			//$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} else if($session["role_name"] == "administrator"){
	        $members = $this->GymCategory->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"staff_member"]);
		    $members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} 
		$this->set("members",$members);		
		
		if($this->request->is("post")) { 
		    $this->loadComponent("GYMFunction");
		    $category = $this->GymCategory->newEntity();
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    $this->request->data["trainer_id"] = $uid;
		    $category = $this->GymCategory->patchEntity($category,$this->request->data);
		    if($this->GymCategory->save($category)) {
		        $this->Flash->Success(__("Success! Category Added to Trainer Successfully."));
		        return $this->redirect(["action"=>"categoryList"]);
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
	
	public function editCategory($id){
	    $this->set("title",__("Edit Category"));
	    $row1 = $this->GymCategory->get($id);
	    $row = $row1->toArray();
	    $this->set("edit",true);
	    $this->set("data",$row);
	    $this->render("addCategory");
	    if($this->request->is("post"))
	    {
	        $this->loadComponent("GYMFunction");
	        $category = $this->GymCategory->patchEntity($row1,$this->request->data);
	        if($this->GymCategory->save($category))
	        {
	            $this->Flash->success(__("Success! Record Updated Successfully"));
	            return $this->redirect(["action"=>"categoryList"]);
	        }
	    }
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		// $members_actions = ["categoryList"];
		$staff_acc_actions = ["categoryList"];
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