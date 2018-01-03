<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class GymWorkoutController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function workoutList()
	{
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator" ) {
		    $data = $this->GymWorkout->find("all")->hydrate(false)->toArray();
		    $trainer = $this->GymWorkout->GymMember->find("all")->where(["role_name"=>"staff_member"])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member") {
		    $data = $this->GymWorkout->find("all")->where(["GymWorkout.trainer_id"=>$session['id']]);
		    $data = $data->contain(["GymCategory","GymExercise","GymMod","GymLevels","GymMember"])
		    ->select(["GymWorkout.id","GymWorkout.workout_name","GymWorkout.weight","GymWorkout.sets","GymWorkout.reps","GymWorkout.rest_time","GymCategory.name","GymExercise.name","GymMod.name","GymLevels.level","GymMember.last_name"])->hydrate(false)->toArray();
		} else if($session["role_name"] == "member") {
			$uid = $session["id"];
			$data = $this->GymWorkout->GymMember->find("all")->where(["id"=>$uid])->hydrate(false)->toArray();
		}
		$this->set("data",$data);
	}	
	
	public function addWorkout() {
	    
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Workout"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {
			//$members = $this->GymWorkout->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			//$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member"){
		    $categories = $this->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
		    $exercises = $this->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
		    $mods = $this->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
		    $levels = $this->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id"=>$session['id']])->select(["id","level"])->hydrate(false)->toArray();
            		    
		} else{
			$members = $this->GymWorkout->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","member_type"=>"Member"]);
			$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
			$categories = $this->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
			$exercises = $this->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
			$mods = $this->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
			$levels = $this->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id"=>$session['id']])->select(["id","level"])->hydrate(false)->toArray();
			
		}
		$this->set("members",$members);
		$this->set("categories",$categories);
		$this->set("exercises",$exercises);
		$this->set("mods",$mods);
		$this->set("levels",$levels);
		
		if($this->request->is("post") && !isset($this->request->data["new_data"]) && !isset($this->request->data["edit"])) { 
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    $row = $this->GymWorkout->newEntity();
		    $this->request->data["trainer_id"] = $uid;
			$this->request->data["create_time"] = date("Y-m-d");
			$this->request->data["update_time"] = date("Y-m-d");
			$this->request->data["update_user_id"] = $session["id"];
			$this->request->data["create_user_id"] = $session["id"];
			
			$row = $this->GymWorkout->patchEntity($row,$this->request->data);
			if($this->GymWorkout->save($row)) {
				$id = $row->id;				
				$this->Flash->success(__("Success! Record Saved Successfully."));
				return $this->redirect(["action"=>"workoutList"]);
			} else{
				if($row->errors())
				{
					foreach($row->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
						}						
					}
				}
			}
		}
		else if($this->request->is("post") && isset($this->request->data["new_data"]) && !isset($this->request->data["edit"]))
		{
			$row = $this->GymWorkout->newEntity();
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["record_date"] = date("Y-m-d",strtotime($this->request->data["record_date"]));
			$this->request->data["created_by"] = $session["id"];
			$row = $this->GymWorkout->patchEntity($row,$this->request->data);
			if($this->GymWorkout->save($row))
			{
				$id = $row->id;				
				$post = $this->request->data;
				$activities = $post["activity_name"];
				// var_dump($post);die;
				foreach($activities as $activity)
				{
					$error = null;
					$data = array();
					$data["user_exersize_id"] = $id;
					$data["exersize_name"] = $activity;
					$data["sets"] = $post["sets_{$activity}"];
					$data["reps"] = $post["reps_{$activity}"];
					$data["kg"] = $post["kg_{$activity}"];
					$data["rest_time"] = $post["rest_{$activity}"];
					$row = $this->GymWorkout->GymUserWorkout->newEntity();
					$row = $this->GymWorkout->GymUserWorkout->patchEntity($row,$data);
					if($this->GymWorkout->GymUserWorkout->save($row))
					{$error = 0;}else{$error = 1;}					
				}
				if($error == 0)
				{
					// $this->Flash->success(__("Success! Record Saved Successfully."));
					// return $this->redirect(["action"=>"exersizeList"]);
				}				
			}
			else
			{
				if($row->errors())
				{
					foreach($row->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
							return $this->redirect(["action"=>"addWorkout"]);
						}						
					}
				}
			}
			
			$assign_row = $this->GymWorkout->GymAssignWorkout->newEntity();
			$assign_data["level_id"]= $this->request->data["level_id"];
			$assign_data["user_id"]= $this->request->data["member_id"];
			$assign_data["description"]= $this->request->data["note"];
			$assign_data["direct_assign"]= 1;
			$assign_data["start_date"]= $this->request->data["record_date"];
			$assign_data["end_date"]= $this->request->data["record_date"];
			$assign_data["created_date"]= date("Y-m-d");
			$assign_data["created_by"]= $session["id"];
			$assign_row = $this->GymWorkout->GymAssignWorkout->patchEntity($assign_row,$assign_data);
			if($this->GymWorkout->GymAssignWorkout->save($assign_row))
			{
				$id = $assign_row->id;				
				$post = $this->request->data;
				$activities = $post["activity_name"];
				foreach($activities as $activity)
				{
					$error = null;
					$data = array();
					$day_name = date("l",strtotime($post["record_date"]));
					$data["day_name"] = $day_name;
					$data["exersize_id"] = $id;
					$data["exersize_name"] = $activity;
					$data["sets"] = $post["sets_{$activity}"];
					$data["reps"] = $post["reps_{$activity}"];
					$data["kg"] = $post["kg_{$activity}"];
					$data["time"] = $post["rest_{$activity}"];
					$data["created_date"]= date("Y-m-d");
					$data["created_by"]= $session["id"];
					
					$row = $this->GymWorkout->GymWorkoutData->newEntity();
					$row = $this->GymWorkout->GymWorkoutData->patchEntity($row,$data);
					if($this->GymWorkout->GymWorkoutData->save($row))
					{$error = 0;}else{$error = 1;}					
				}
				if($error == 0)
				{
					$this->Flash->success(__("Success! Record Saved Successfully."));
					return $this->redirect(["action"=>"exersizeList"]);
				}
			}
						
		}
		else if($this->request->is("post") && !isset($this->request->data["new_data"]) && isset($this->request->data["edit"]) && $this->request->data["edit"] == "yes")
		{
			$post = $this->request->data;			
			foreach($post["exersizes_array"] as $wa)
			{
				$wn = $post["exersize_name_".$wa];
				$row[$wn]["sets"] = $post["sets_{$wa}"];
				$row[$wn]["reps"] = $post["reps_{$wa}"];
				$row[$wn]["kg"] = $post["kg_{$wa}"];
				$row[$wn]["rest"] = $post["rest_{$wa}"];
				
				$query = $this->GymWorkout->GymUserWorkout->query();
				$query->update()
						->set(["sets" => $post["sets_{$wa}"],"reps"=>$post["reps_{$wa}"],"kg"=>$post["kg_{$wa}"],"rest_time"=>$post["rest_{$wa}"]])
						->where(['user_exersize_id' => $post["user_exersize_id"],"exersize_name"=>$wn])
						->execute();				
			}	
			$this->Flash->success(__("Success! Record Updated Successfully."));
			return $this->redirect(["action"=>"exersizeList"]);
		}
	}
	
	public function editExercise($id){
	    $this->set("title",__("Edit Exercise"));
	    $this->set("edit",true);
	    $categories = $this->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->toArray();
	    $this->set("categories",$categories);
	    
	    $data = $this->GymWorkout->find()->where(["GymWorkout.id"=>$id])->contain(["GymCategory","GymMember"])->select($this->GymWorkout);
	    $data = $data->select(["GymMember.first_name","GymCategory.name"])->hydrate(false)->toArray();
	    //$data = $this->GymWorkout->find()->where(["GymWorkout.id"=>$id])->hydrate(false)->toArray();
	    $this->set("data",$data);
	    $this->render("addExercise");
	    if($this->request->is("post")) {
	        $this->loadComponent("GYMFunction");
	        $category = $this->GymWorkout->patchEntity($row1,$this->request->data);
	        if($this->GymWorkout->save($category)) {
	            $this->Flash->success(__("Success! Record Updated Successfully"));
	            return $this->redirect(["action"=>" exerciseList"]);
	        }
	    }
	}
	
	public function deleteActivity($did)
	{
	    $row = $this->Activity->get($did);
	    if($this->Activity->delete($row))
	    {
	        $this->Flash->success(__("Success! Record Deleted Successfully"));
	        return $this->redirect($this->referer());
	    }
	}
	
	public function viewExercise($uid)
    {		
		$member = $this->GymWorkout->GymMember->get($uid)->toArray();
		$this->set("member_name",$member["first_name"]." ".$member["last_name"]);		
		
		$session = $this->request->session()->read("User");		
		if(intval($session["id"]) != intval($uid) && $session["role_name"] == 'member')
		{
			echo $this->Flash->error("No sneaking around! ;p ");
			return $this->redirect(["action"=>"exersizeList"]);			
		}
		
		$dates = $this->GymWorkout->find()->select(["id","record_date"])->where(["member_id"=>$uid])->hydrate(false)->toArray();
		$date_array = array();
		foreach($dates as $date)
		{
			$wid = $date["id"];
			$date_array[$wid]=$date["record_date"]->format("Y-m-d");
		}
		$this->set("date_array",$date_array);
		
		if($this->request->is("post"))
		{			
			$user_exersize_id = $this->request->data["schedule_date"];
			$exersizes = $this->GymWorkout->GymUserWorkout->find()->where(["user_exersize_id"=>$user_exersize_id])->hydrate(false)->toArray();
			$this->set("exersizes",$exersizes);
		}
		
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		// $members_actions = ["exersizeList"];
		$staff_acc_actions = ["exersizeList"];
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