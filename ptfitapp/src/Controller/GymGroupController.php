<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

Class GymGroupController extends AppController
{
	public function initialize()
	{
			parent::initialize();
	}
		
	public function GroupList()
	{ 
		// var_dump($this->request->session()->read("Config.username"));
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator") {
		    $data = $this->GymGroup->find("all")->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member"){
		    $uid = intval($session["id"]);
		    if($this->GYMFunction->getSettings("staff_can_view_member_group")) {
		        $data = $this->GymGroup->find("all")->where(["trainer_id"=>$uid])->hydrate(false)->toArray();
		    }else{
		        $data = $this->GymGroup->find("all")->where(["trainer_id"=>$uid])->hydrate(false)->toArray();
		    }
		}
		$this->set("data",$data);
	}
	
	public function addGroup()
	{		
		$this->set("edit",false);
		$this->set("title",__("Add Group"));	
		if($this->request->is("post"))
		{
			$this->loadComponent("GYMFunction");
			$group = $this->GymGroup->newEntity();
			$new_name = $this->GYMFunction->uploadImage($this->request->data["image"]);
			$this->request->data["image"] =  $new_name;
			$session = $this->request->session()->read("User");
			$uid = intval($session["id"]);
			$this->request->data["trainer_id"] =  $uid;
			$this->request->data["created_date"] = date("Y-m-d");			
			$group = $this->GymGroup->patchEntity($group,$this->request->data);
			
			if($this->GymGroup->save($group))
			{
				$this->Flash->Success(__("Success! Group Added to Trainer Successfully."));
				return $this->redirect(["action"=>"groupList"]);
			}			
		}
	}

	public function editGroup($id){
		$this->set("title",__("Edit Group"));	
		$row1 = $this->GymGroup->get($id);
		$row = $row1->toArray();		
		$this->set("edit",true);
		$this->set("data",$row);
		$this->render("addGroup");
		if($this->request->is("post"))
		{
			$this->loadComponent("GYMFunction");
			if(!empty($this->request->data["image"]['name']))
			{
				$new_name = $this->GYMFunction->uploadImage($this->request->data["image"]);
				$this->request->data["image"] =  $new_name;
			}else{
				$this->request->data["image"] = $row['image'];
			}
			$group = $this->GymGroup->patchEntity($row1,$this->request->data);
			if($this->GymGroup->save($group))
			{
				$this->Flash->success(__("Success! Record Updated Successfully"));
				return $this->redirect(["action"=>"groupList"]);
			}
		}
	}	
	
	public function deleteGroup($id = null)
	{
		if($id != null)
		{
			$row = $this->GymGroup->get($id);
			if($this->GymGroup->delete($row))
			{
				$this->Flash->success(__("Success! Record Deleted Successfully"));
				return $this->redirect(["action"=>"groupList"]);
			}
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["groupList"];
		$staff_acc_actions = ["groupList","editGroup","deleteGroup","addGroup"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		
		return parent::isAuthorized($user);
	}

}