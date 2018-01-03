<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\View\View;

class GymProgramAssignmentController extends AppController
{     
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function programList() {
	    
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator" ) {
		    $data = $this->GymProgramAssignment->find("all")->hydrate(false)->toArray();
		    $data = $data->contain(["GymMember","GymProgram"])->select(["GymProgramAssignment.id", "GymProgram.name","GymMember.first_name", "GymMember.last_name"])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member") {
		    $data = $this->GymProgramAssignment->find("all")->where(["GymProgramAssignment.trainer_id"=> $session['id'] ]);
		    $data = $data->contain(["GymMember","GymProgram"])->select(["GymProgramAssignment.id", "GymProgram.name","GymMember.first_name", "GymMember.last_name","GymProgramAssignment.start_date"])->hydrate(false)->toArray();
		} else if($session["role_name"] == "member") {
		    $data = $this->GymProgramAssignment->find("all")->where(["GymProgramAssignment.member_id"=> $session['id'] ]);
		    $data = $data->contain(["GymMember","GymProgram"])->select(["GymProgramAssignment.id", "GymProgram.name","GymMember.first_name", "GymMember.last_name","GymProgramAssignment.start_date"])->hydrate(false)->toArray();
		} 
		$this->set("data",$data);
	}	
	
	public function assignProgram() {
	    
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Assign Program"));
		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {
			//$members = $this->GymProgramAssignment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["id"=>$session["id"]]);
			//$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		} else if($session["role_name"] == "staff_member" || $session["role_name"] == "administrator"){
		    $members =  $this->GymProgramAssignment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member", "assign_staff_mem" => $session['id'] ]);
		    $members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		    
		    $adminIds =  $this->GymProgramAssignment->GymMember->find("list",["valueField"=>"id"])->where(["role_name"=>"administrator"])->select(["id"])->hydrate(false)->toArray();
		    $adminIds[] = $session['id'];
		    $programs = $this->GymProgramAssignment->GymProgram->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id IN "=>$adminIds])->select(["GymProgram.id","GymProgram.name"])->hydrate(false)->toArray();
		    
		    $this->set("members",$members);
		    $this->set("programs",$programs);
		}
		
		if($this->request->is("post")) {
		    $session = $this->request->session()->read("User");
		    $uid = intval($session["id"]);
		    $att_date = date("Y-m-d",strtotime($this->request->data["start_date"]));
			$program_data[] = array(); 
			$program_data['program_id'] = $this->request->data["program_id"];
			$program_data['trainer_id'] = $uid;
			$program_data['member_id']  = $this->request->data["member_id"];
			$program_data['create_time'] = date("Y-m-d");
			$program_data['create_user_id'] = $uid;
			
			$programrow = $this->GymProgramAssignment->newEntity();
			$programrow = $this->GymProgramAssignment->patchEntity($programrow,$program_data);
			if($this->GymProgramAssignment->save($programrow)) {
			    $this->Flash->success(__("Success! Program Assigned Successfully."));
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
		}
	}
	
	public function customizeProgram($program_assign_id){
	    $this->set("title",__("Customize Program"));
	    $this->set("edit",true);
	    $program_assign_data = $this->GymProgramAssignment->get($program_assign_id);
	    $program_id = $program_assign_data['program_id'];
	    $program_data = $this->GymProgramAssignment->GymProgram->get($program_id);
	    if($program_assign_data['is_customized'] == 1){
	        $GymProgramAssignmentSchedule = TableRegistry::get("gym_program_assignment_schedule");
	        $program_workouts = $GymProgramAssignmentSchedule->find("all", array( 'order' => array('gym_program_assignment_schedule.day' => 'ASC', 'gym_program_assignment_schedule.seq' => 'ASC')))->where(["program_assignment_id"=> $program_assign_id]);
	        $program_workouts = $program_workouts->contain(["GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
	        ->select(["gym_program_assignment_schedule.id", "GymWorkout.workout_name", "GymCategory.name", "GymExercise.name",
	            "GymLevels.level","GymMod.name", "gym_program_assignment_schedule.seq", "gym_program_assignment_schedule.day",
	            "gym_program_assignment_schedule.category_id", "gym_program_assignment_schedule.workout_id","gym_program_assignment_schedule.exercise_id",
	            "gym_program_assignment_schedule.mod_id","gym_program_assignment_schedule.level_id", "gym_program_assignment_schedule.weight",
	            "gym_program_assignment_schedule.sets", "gym_program_assignment_schedule.reps", "gym_program_assignment_schedule.rest_time",
	            "gym_program_assignment_schedule.instructions", "gym_program_assignment_schedule.wrkout_video"
	        ])->hydrate(false)->toArray();
	    }else{
	        $GymProgramWorkoutTemplate = TableRegistry::get("gym_program_workout_template");
	        $program_workouts = $GymProgramWorkoutTemplate->find("all", array( 'order' => array('gym_program_workout_template.day' => 'ASC', 'gym_program_workout_template.seq' => 'ASC')))->where(["program_id"=> $program_id]);
	        $program_workouts = $program_workouts->contain(["GymMember","GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
	        ->select(["gym_program_workout_template.id", "GymWorkout.workout_name", "GymCategory.name", "GymExercise.name",
	            "GymLevels.level","GymMod.name", "gym_program_workout_template.seq", "gym_program_workout_template.day",
	            "gym_program_workout_template.category_id", "gym_program_workout_template.workout_id","gym_program_workout_template.exercise_id",
	            "gym_program_workout_template.mod_id","gym_program_workout_template.level_id", "gym_program_workout_template.weight",
	            "gym_program_workout_template.sets", "gym_program_workout_template.reps", "gym_program_workout_template.rest_time",
	            "gym_program_workout_template.instructions", "gym_program_workout_template.wrkout_video"
	        ])->hydrate(false)->toArray();
	    }
        	   
	    $program_data['program_assign_id'] = $program_assign_id;
	    $this->set("workouts",$program_workouts);
	    $this->set("program_data",$program_data);
	    
	    if($this->request->is("post")) {
	        $session = $this->request->session()->read("User");
	        $uid = intval($session["id"]);
	        //$program_id = $this->request->data["program-id"];
	        if($program_id) {
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
	                $workout_day_entry['program_assignment_id'] = $program_assign_id;
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
	                
	                $GymProgramAssignmentSchedule = TableRegistry::get("gym_program_assignment_schedule");
	                
	                $workoutentryrow = $GymProgramAssignmentSchedule->newEntity();
	                $workoutentryrow = $GymProgramAssignmentSchedule->patchEntity($workoutentryrow,$workout_day_entry);
	                if($GymProgramAssignmentSchedule->save($workoutentryrow)) {
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
	            
	            $program_assign_data['is_customized'] = 1;
	            if($program_assign_data = $this->GymProgramAssignment->save($program_assign_data)){
	                $this->Flash->success(__("Success! Record Saved Successfully."));
	                return $this->redirect(["action"=>"programList"]);
	            }
	            
	        }else{
	            if($row->errors())  {
	                foreach($row->errors() as $error) {
	                    foreach($error as $key=>$value) {
	                        $this->Flash->error(__($value));
	                    }
	                }
	            }
	            
	        }
	        
	        $row = $this->GymProgramAssignment->newEntity();
	        $row = $this->GymProgramAssignment->patchEntity($row,$this->request->data);
	        if($this->GymProgramAssignment->save($row)) {
	            $id = $row->id;
	            $this->Flash->success(__("Success! Record Updated Successfully."));
	            return $this->redirect(["action"=>"programList"]);
	        } else{
	            
	        }
	    }
	}
	
	public function deleteProgram($assign_id) {
	    if($this->GymProgramAssignment->deleteAll(array('GymProgramAssignment.id' => $assign_id)) )  {
	        //$this->GymProgramAssignment->GymProgram->delete($program_id);
	        $this->Flash->success(__("Success! All workouts in program deleted successfully"));
	        return $this->redirect($this->referer());
	    }else{
	        $this->Flash->success(__("Fail to deleted Record"));
	        return $this->redirect($this->referer());
	    }
	}
	
	public function viewProgram($program_assign_id)
    {
        $program_assign_data = $this->GymProgramAssignment->get($program_assign_id);
        $program_id = $program_assign_data['program_id'];
        $program_data = $this->GymProgramAssignment->GymProgram->get($program_id);
        
        if($program_assign_data['is_customized'] == 1){
            $GymProgramAssignmentSchedule = TableRegistry::get("gym_program_assignment_schedule");
            $program_workouts = $GymProgramAssignmentSchedule->find("all", array( 'order' => array('gym_program_assignment_schedule.day' => 'ASC', 'gym_program_assignment_schedule.seq' => 'ASC')))->where(["program_assignment_id"=> $program_assign_id]);
            $program_workouts = $program_workouts->contain(["GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
            ->select(["gym_program_assignment_schedule.id", "GymWorkout.workout_name", "GymCategory.name", "GymExercise.name",
                "GymLevels.level","GymMod.name", "gym_program_assignment_schedule.seq", "gym_program_assignment_schedule.day",
                "gym_program_assignment_schedule.category_id", "gym_program_assignment_schedule.workout_id","gym_program_assignment_schedule.exercise_id",
                "gym_program_assignment_schedule.mod_id","gym_program_assignment_schedule.level_id", "gym_program_assignment_schedule.weight",
                "gym_program_assignment_schedule.sets", "gym_program_assignment_schedule.reps", "gym_program_assignment_schedule.rest_time",
                "gym_program_assignment_schedule.instructions", "gym_program_assignment_schedule.wrkout_video"
            ])->hydrate(false)->toArray();
        }else{
            $GymProgramWorkoutTemplate = TableRegistry::get("gym_program_workout_template");
            $program_workouts = $GymProgramWorkoutTemplate->find("all", array( 'order' => array('gym_program_workout_template.day' => 'ASC', 'gym_program_workout_template.seq' => 'ASC')))->where(["program_id"=> $program_id]);
            $program_workouts = $program_workouts->contain(["GymMember","GymWorkout","GymCategory","GymExercise","GymLevels","GymMod"])
            ->select(["gym_program_workout_template.id", "GymWorkout.workout_name", "GymCategory.name", "GymExercise.name",
                "GymLevels.level","GymMod.name", "gym_program_workout_template.seq", "gym_program_workout_template.day",
                "gym_program_workout_template.category_id", "gym_program_workout_template.workout_id","gym_program_workout_template.exercise_id",
                "gym_program_workout_template.mod_id","gym_program_workout_template.level_id", "gym_program_workout_template.weight",
                "gym_program_workout_template.sets", "gym_program_workout_template.reps", "gym_program_workout_template.rest_time",
                "gym_program_workout_template.instructions", "gym_program_workout_template.wrkout_video"
            ])->hydrate(false)->toArray();
        }
        $this->set("workouts",$program_workouts);
        $this->set("program_data",$program_data);
        
	}
	
	public function wrkoutTemplate() {
	    $session = $this->request->session()->read("User");
	    if($session["role_name"] == "staff_member"){
	        $categories = $this->GymProgramAssignment->GymWorkout->GymCategory->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $exercises = $this->GymProgramAssignment->GymWorkout->GymExercise->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $mods = $this->GymProgramAssignment->GymWorkout->GymMod->find("list",["keyField"=>"id","valueField"=>"name"])->where(["trainer_id"=>$session['id']])->select(["id","name"])->hydrate(false)->toArray();
	        $levels = $this->GymProgramAssignment->GymWorkout->GymLevels->find("list",["keyField"=>"id","valueField"=>"level"])->where(["trainer_id"=>$session['id']])->select(["id","level"])->hydrate(false)->toArray();
	    }
	    //$this->autoRender = false;
	    $this->layout = false;
	    
	    $view = new View($this, false);
	    $view->set(compact("members",$members));
	    $view->set(compact("categories",$categories));
	    $view->set(compact("exercises",$exercises));
	    $view->set(compact("mods",$mods));
	    $view->set(compact("levels",$levels));
	    $html = $this -> render('/GymProgramAssignment/wrkout_template');
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