<?php
namespace App\Controller;
use Cake\View\View;

class GymProgramWorkoutTemplateController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function programList() {
	    
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator" ) {
		    $data = $this->GymProgramWorkoutTemplate->find("all")->hydrate(false)->toArray();
		    $trainer = $this->GymProgramWorkoutTemplate->GymMember->find("all")->where(["role_name"=>"staff_member"])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member") {
		    $adminIds =  $this->GymProgramWorkoutTemplate->GymMember->find("list",["valueField"=>"id"])->where(["role_name"=>"administrator"])->select(["id"])->hydrate(false)->toArray();
		    $adminIds[] = $session['id'];
		    $data = $this->GymProgramWorkoutTemplate->GymProgram->find("all")->where(["GymProgram.trainer_id IN "=> $adminIds]);
		    $data = $data->contain(["GymMember"])->select(["GymProgram.trainer_id","GymProgram.id", "GymProgram.name","GymProgram.duration","GymProgram.duration_unit"])->hydrate(false)->toArray();
		} /* else if($session["role_name"] == "member") {
		    $data = $this->GymProgram->find("all")->where(["GymProgram.trainer_id"=>$session['id']]);
		    $data = $data->contain(["GymMember"])->select(["GymMember.name","GymProgram.id", "GymProgram.name","GymProgram.duration"])->hydrate(false)->toArray();
		} */
		$this->set("data",$data);
	}	
	
	public function addTemplate() {
	    
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Program Template"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {
			//$members = $this->GymProgramWorkoutTemplate->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			//$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member" || $session["role_name"] == "administrator"){
		    $adminIds =  $this->GymProgramWorkoutTemplate->GymMember->find("list",["valueField"=>"id"])->where(["role_name"=>"administrator"])->select(["id"])->hydrate(false)->toArray();
		    $adminIds[] = $session['id'];
		    
		    $categories = $this->GymProgramWorkoutTemplate->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id IN "=>$adminIds])->select(["id","name"])->hydrate(false)->toArray();
		    $exercises = $this->GymProgramWorkoutTemplate->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
		    $levels = $this->GymProgramWorkoutTemplate->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id IN"=>$adminIds])->select(["id","level"])->hydrate(false)->toArray();
		    $mods = $this->GymProgramWorkoutTemplate->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
            		    
		}
		$this->set("members",$members);
		$this->set("categories",$categories);
		$this->set("exercises",$exercises);
		$this->set("mods",$mods);
		$this->set("levels",$levels);
		
		if($this->request->is("post") && !isset($this->request->data["new_data"]) && !isset($this->request->data["edit"])) {
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    /* $this->request->data["trainer_id"] = $uid;
			$this->request->data["create_time"] = date("Y-m-d");
			$this->request->data["update_time"] = date("Y-m-d");
			$this->request->data["update_user_id"] = $session["id"];
			$this->request->data["create_user_id"] = $session["id"]; */
			
			$program_data[] = array(); 
			$program_data['id'] = $this->request->data["program-id"];
			$program_data['duration'] = $this->request->data["program_duration"];
			$program_data['name'] = $this->request->data["program_name"];
			$programrow = $this->GymProgramWorkoutTemplate->GymProgram->newEntity();
			$programrow = $this->GymProgramWorkoutTemplate->GymProgram->patchEntity($programrow,$program_data);
			if($this->GymProgramWorkoutTemplate->GymProgram->save($programrow)) {
			    $id = $programrow->id;
			    $persist_indexes = array();
			    
			    foreach ($this->request->data as $key => $value){
			        if(substr( $key, 0, 18 ) === "wrkout-persist-id-" ){
			            $persist_indexes[] = substr( $key, 18);
			        }
			    }
			    
			    $workouts_array = array();
			    foreach ($persist_indexes as $index) {
			        $workout_day_entry = array(); 
			        $workout_day_entry['id'] = $this->request->data['wrkout-persist-id-'.$index];
			        $workout_day_entry['trainer_id']     = $uid;
			        
			        $workout_day_entry['program_id']     = $this->request->data['program-id'];
			        $workout_day_entry['workout_id']     = $this->request->data['wrkout-mstr-id-'.$index];
			        
			        $workout_day_entry['category_id']    = $this->request->data['wrkout-category_id-'.$index];
			        $workout_day_entry['exercise_id']    = $this->request->data['wrkout-exercise_id-'.$index];
			        $workout_day_entry['mod_id']         = $this->request->data['wrkout-mod_id-'.$index];
			        $workout_day_entry['level_id']       = $this->request->data['wrkout-level_id-'.$index];
			        
			        $workout_day_entry['time_unit']      = 'DD';
			        $workout_day_entry['time_value']     = 0;
			        $workout_day_entry['seq']            = $this->request->data['wrkout-seq-'.$index];
			        $workout_day_entry['day']            = $this->request->data['wrkout-days-'.$index];
			        $workout_day_entry['weight']         = $this->request->data['wrkout-weight-'.$index];
			        $workout_day_entry['sets']           = $this->request->data['wrkout-sets-'.$index];
			        $workout_day_entry['reps']           = $this->request->data['wrkout-reps-'.$index];
			        $workout_day_entry['rest_time']      = $this->request->data['wrkout-rest-'.$index];
			        
			        $workout_day_entry['wrkout_video']   = $this->request->data['wrkout_video-'.$index];
			        $workout_day_entry['instructions']   = $this->request->data['wrkout-instructions-'.$index];
			        
			        $workout_day_entry["create_time"]    = date("Y-m-d");
			        $workout_day_entry["update_time"]    = date("Y-m-d");
			        $workout_day_entry["update_user_id"] = $session["id"];
			        $workout_day_entry["create_user_id"] = $session["id"];
			        
			        $workoutentryrow = $this->GymProgramWorkoutTemplate->newEntity();
			        $workoutentryrow = $this->GymProgramWorkoutTemplate->patchEntity($workoutentryrow,$workout_day_entry);
			        if($this->GymProgramWorkoutTemplate->save($workoutentryrow)) {
			            $id = $workoutentryrow->id;
                         			            
			        }else{
			            if($row->errors())  {
			                foreach($row->errors() as $error) {
			                    foreach($error as $key=>$value) {
			                        $this->Flash->error(__($value));
			                    }
			                }
			            }
			        }
			    }
			    $this->Flash->success(__("Success! Record Saved Successfully."));
			    return $this->redirect(["action"=>"programList"]);
			}else{
			    if($row->errors())  {
			        foreach($row->errors() as $error) {
			            foreach($error as $key=>$value) {
			                $this->Flash->error(__($value));
			            }
			        }
			    }
			    
			}
			
			$row = $this->GymProgramWorkoutTemplate->newEntity();
			$row = $this->GymProgramWorkoutTemplate->patchEntity($row,$this->request->data);
			if($this->GymProgramWorkoutTemplate->save($row)) {
				$id = $row->id;				
				$this->Flash->success(__("Success! Record Saved Successfully."));
				return $this->redirect(["action"=>"programList"]);
			} else{
			    
			}
		}
	}
	
	public function editTemplate($program_id){
	    $this->set("title",__("Edit Program Template"));
	    $this->set("edit",true);
	    $session = $this->request->session()->read("User");
	    $adminIds =  $this->GymProgramWorkoutTemplate->GymMember->find("list",["valueField"=>"id"])->where(["role_name"=>"administrator"])->select(["id"])->hydrate(false)->toArray();
	    $adminIds[] = $session['id'];
	    
	    $categories = $this->GymProgramWorkoutTemplate->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id IN "=> $adminIds])->select(["id","name"])->hydrate(false)->toArray();
	    $exercises = $this->GymProgramWorkoutTemplate->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	    $levels = $this->GymProgramWorkoutTemplate->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id IN "=>$adminIds])->select(["id","level"])->hydrate(false)->toArray();
	    $mods = $this->GymProgramWorkoutTemplate->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();

	    $this->set("members",$members);
	    $this->set("categories",$categories);
	    $this->set("exercises",$exercises);
	    $this->set("mods",$mods);
	    $this->set("levels",$levels);
	    
	    $program_data = $this->GymProgramWorkoutTemplate->GymProgram->get($program_id);
	    $program_workouts = $this->GymProgramWorkoutTemplate->find("all", array( 'order' => array('GymProgramWorkoutTemplate.day' => 'ASC', 'GymProgramWorkoutTemplate.seq' => 'ASC')))->where(["program_id"=> $program_id]);
	    $program_workouts = $program_workouts->contain(["GymMember","GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
	    ->select(["GymProgramWorkoutTemplate.id", "GymProgramWorkoutTemplate.workout_id","GymProgramWorkoutTemplate.category_id","GymProgramWorkoutTemplate.exercise_id","GymProgramWorkoutTemplate.level_id","GymProgramWorkoutTemplate.mod_id","GymProgramWorkoutTemplate.seq",
	        "GymProgramWorkoutTemplate.day","GymProgramWorkoutTemplate.weight","GymProgramWorkoutTemplate.sets","GymProgramWorkoutTemplate.reps","GymProgramWorkoutTemplate.rest_time",
	        "GymProgramWorkoutTemplate.instructions","GymProgramWorkoutTemplate.wrkout_video"
	    ])->hydrate(false)->toArray();
	    
	    $this->set("workouts",$program_workouts);
	    $this->set("program_data",$program_data);
	    
	    $days = array();
	    for ($i = 1; $i <= $program_data['duration']; $i++){
	        $days[$i]=$i;
	    }
	    $this->set("days",$days);
	    if($this->request->is("post")) {
	        $session = $this->request->session()->read("User");
	        $uid = intval($session["id"]);
	        /* $this->request->data["trainer_id"] = $uid;
	         $this->request->data["create_time"] = date("Y-m-d");
	         $this->request->data["update_time"] = date("Y-m-d");
	         $this->request->data["update_user_id"] = $session["id"];
	         $this->request->data["create_user_id"] = $session["id"]; */
	        $program_id = $this->request->data["program-id"];
	        if($program_id) {
	            $id = $programrow->id;
	            $persist_indexes = array();
	            
	            foreach ($this->request->data as $key => $value){
	                if(substr( $key, 0, 18 ) === "wrkout-persist-id-" ){
	                    $persist_indexes[] = substr( $key, 18);
	                }
	            }
	            
	            $workouts_array = array();
	            foreach ($persist_indexes as $index) {
	                $workout_day_entry = array();
	                $workout_day_entry['id'] = $this->request->data['wrkout-persist-id-'.$index];
	                $workout_day_entry['trainer_id']     = $uid;
	                
	                $workout_day_entry['program_id']     = $program_id;
	                $workout_day_entry['workout_id']     = $this->request->data['wrkout-mstr-id-'.$index];
	                
	                $workout_day_entry['category_id']    = $this->request->data['wrkout-category_id-'.$index];
	                $workout_day_entry['exercise_id']    = $this->request->data['wrkout-exercise_id-'.$index];
	                $workout_day_entry['mod_id']         = $this->request->data['wrkout-mod_id-'.$index];
	                $workout_day_entry['level_id']       = $this->request->data['wrkout-level_id-'.$index];
	                
	                $workout_day_entry['time_unit']      = 'DD';
	                $workout_day_entry['time_value']     = 0;
	                $workout_day_entry['seq']            = $this->request->data['wrkout-seq-'.$index];
	                $workout_day_entry['day']            = $this->request->data['wrkout-days-'.$index];
	                $workout_day_entry['weight']         = $this->request->data['wrkout-weight-'.$index];
	                $workout_day_entry['sets']           = $this->request->data['wrkout-sets-'.$index];
	                $workout_day_entry['reps']           = $this->request->data['wrkout-reps-'.$index];
	                $workout_day_entry['rest_time']      = $this->request->data['wrkout-rest-'.$index];
	                
	                $workout_day_entry['wrkout_video']   = $this->request->data['wrkout_video-'.$index];
	                $workout_day_entry['instructions']   = $this->request->data['wrkout-instructions-'.$index];
	                
	                $workout_day_entry["create_time"]    = date("Y-m-d");
	                $workout_day_entry["update_time"]    = date("Y-m-d");
	                $workout_day_entry["update_user_id"] = $session["id"];
	                $workout_day_entry["create_user_id"] = $session["id"];
	                
	                $workoutentryrow = $this->GymProgramWorkoutTemplate->newEntity();
	                $workoutentryrow = $this->GymProgramWorkoutTemplate->patchEntity($workoutentryrow,$workout_day_entry);
	                if($this->GymProgramWorkoutTemplate->save($workoutentryrow)) {
	                    $id = $workoutentryrow->id;
	                    
	                }else{
	                    if($row->errors())  {
	                        foreach($row->errors() as $error) {
	                            foreach($error as $key=>$value) {
	                                $this->Flash->error(__($value));
	                            }
	                        }
	                    }
	                }
	            }
	            $this->Flash->success(__("Success! Record Saved Successfully."));
	            return $this->redirect(["action"=>"programList"]);
	        }else{
	            if($row->errors())  {
	                foreach($row->errors() as $error) {
	                    foreach($error as $key=>$value) {
	                        $this->Flash->error(__($value));
	                    }
	                }
	            }
	            
	        }
	        
	        $row = $this->GymProgramWorkoutTemplate->newEntity();
	        $row = $this->GymProgramWorkoutTemplate->patchEntity($row,$this->request->data);
	        if($this->GymProgramWorkoutTemplate->save($row)) {
	            $id = $row->id;
	            $this->Flash->success(__("Success! Record Updated Successfully."));
	            return $this->redirect(["action"=>"programList"]);
	        } else{
	            
	        }
	    }
	}
	
	public function deleteProgram($program_id) {
	    if($this->GymProgramWorkoutTemplate->deleteAll(array('GymProgramWorkoutTemplate.program_id' => $program_id)) )  {
	        //$this->GymProgramWorkoutTemplate->GymProgram->delete($program_id);
	        $this->Flash->success(__("Success! All workouts in program deleted successfully"));
	        return $this->redirect($this->referer());
	    }else{
	        $this->Flash->success(__("Fail to deleted Record"));
	        return $this->redirect($this->referer());
	    }
	}
	
	public function viewProgram($program_id)
    {
        $program_data = $this->GymProgramWorkoutTemplate->GymProgram->get($program_id);
        $program_workouts = $this->GymProgramWorkoutTemplate->find("all", array( 'order' => 'GymProgramWorkoutTemplate.day ASC'))->where(["program_id"=> $program_id]);
        $program_workouts = $program_workouts->contain(["GymMember","GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
        ->select(["GymWorkout.workout_name","GymCategory.name","GymExercise.name","GymLevels.level","GymMod.name","GymProgramWorkoutTemplate.seq",
            "GymProgramWorkoutTemplate.day","GymProgramWorkoutTemplate.weight","GymProgramWorkoutTemplate.sets","GymProgramWorkoutTemplate.reps","GymProgramWorkoutTemplate.rest_time",
            "GymProgramWorkoutTemplate.instructions","GymProgramWorkoutTemplate.wrkout_video"
        ])->hydrate(false)->toArray();
        
        $this->set("workouts",$program_workouts);
        $this->set("program_data",$program_data);
        
	}
	
	public function wrkoutTemplate() {
	    $session = $this->request->session()->read("User");
	    if($session["role_name"] == "staff_member"){
	        $categories = $this->GymProgramWorkoutTemplate->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $exercises = $this->GymProgramWorkoutTemplate->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $mods = $this->GymProgramWorkoutTemplate->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $levels = $this->GymProgramWorkoutTemplate->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id"=>$session['id']])->select(["id","level"])->hydrate(false)->toArray();
	    }
	    //$this->autoRender = false;
	    $this->layout = false;
	    
	    $view = new View($this, false);
	    $view->set(compact("members",$members));
	    $view->set(compact("categories",$categories));
	    $view->set(compact("exercises",$exercises));
	    $view->set(compact("mods",$mods));
	    $view->set(compact("levels",$levels));
	    $html = $this -> render('/GymProgramWorkoutTemplate/wrkout_template');
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
	
	public function copyAdminTemplate($program_id) {
	    
	    $session = $this->request->session()->read("User");
	    $uid = intval($session["id"]);
	    if($session["role_name"] == "staff_member"){
	        $program_data = $this->GymProgramWorkoutTemplate->GymProgram->get($program_id);
	        if($program_data['trainer_id'] != $uid){
	            $program_workouts = $this->GymProgramWorkoutTemplate->find("all")->where(["program_id"=> $program_id])->hydrate(false)->toArray();
                	            
	            foreach ($program_workouts as $workout_day_entry) {
	                    $workout_day_entry['id']             = null;
	                    $workout_day_entry['trainer_id']     = $uid;
	                    $workout_day_entry["update_time"]    = date("Y-m-d");
	                    $workout_day_entry["update_user_id"] = $session["id"];
	                    $workout_day_entry["create_time"]    = date("Y-m-d");
	                    //$workout_day_entry["create_user_id"] = $session["id"];
	                    
	                    $workoutentryrow = $this->GymProgramWorkoutTemplate->newEntity();
	                    $workoutentryrow = $this->GymProgramWorkoutTemplate->patchEntity($workoutentryrow,$workout_day_entry);
	                    if($this->GymProgramWorkoutTemplate->save($workoutentryrow)) {
	                        $id = $workoutentryrow->id;
	                        
	                    }else{
	                        if($row->errors())  {
	                            foreach($row->errors() as $error) {
	                                foreach($error as $key=>$value) {
	                                    $this->Flash->error(__($value));
	                                }
	                            }
	                        }
	                    }
	                }
	                $this->Flash->success(__("Success! Template Copied Successfully."));
	                return $this->redirect(["action"=>"programList"]);
	            
	            $row = $this->GymProgramWorkoutTemplate->newEntity();
	            $row = $this->GymProgramWorkoutTemplate->patchEntity($row,$this->request->data);
	            if($this->GymProgramWorkoutTemplate->save($row)) {
	                $id = $row->id;
	                $this->Flash->success(__("Success! Record Updated Successfully."));
	                return $this->redirect(["action"=>"programList"]);
	            } else{
	                
	            }
	        }
	        
	    }
	}
	
}