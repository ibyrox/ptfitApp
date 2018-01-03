<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Mailer\Transport\DebugTransport;
use Cake\Mailer\EmailConfig;

Class MemberRegistrationController  extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->loadComponent("GYMFunction");		
	}
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);       
        $this->Auth->allow(['index','getMembershipEndDate','addPaymentHistory','clientRegistration','trainerRegistration']);
		if (in_array($this->request->action, ['getMembershipEndDate'])) {
			$this->eventManager()->off($this->Csrf);
		}
		 
    }
    
    public function index()
    {
        $this->viewBuilder()->layout('login');
        $member_type = $this->request->query['t'];
        switch($member_type) {
            CASE "trn":
                return $this->redirect(["action"=>"trainerRegistration"]);
                break;
            default:
                return $this->redirect(["action"=>"clientRegistration"]);
        }
    }
    
    public function clientRegistration() {
        if($this->member_registration('MB','member')){
            return $this->redirect(["controller"=>"users","action"=>"login"]);
        }
    }
    
    public function trainerRegistration() {
        if($this->member_registration('TR','staff_member')){
            return $this->redirect(["controller"=>"users","action"=>"login"]);
        }
    }
    
    private function member_registration($member_type, $memberRole) {
        // var_dump($email);die;
        $this->viewBuilder()->layout('login');
        $lastid = $this->MemberRegistration->GymMember->find("all",["fields"=>"id"])->last();
        $lastid = ($lastid != null) ? $lastid->id + 1 : 01 ;
        
        $member = $this->MemberRegistration->GymMember->newEntity();
        $date = date("d");
        $month = date("m");
        $year = date("y");
        
        $h = date("h");
        $min = date("i");
        $sec = date("s");
        
        $prefix = $member_type.$lastid;
        $this->set("member_type",$member_type);
        
        $member_id = $prefix.$date.$month.$year.$h.$min.$sec;
        $this->set("member_id",$member_id);
        
        // YP :: Commented as it is not the part of Current Requirement like Type of classes, Groups, Interest Areas, Sources and memberships.
        //$classes = $this->MemberRegistration->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
        //$groups = $this->MemberRegistration->GymMember->GymGroup->find("list",["keyField"=>"id","valueField"=>"name"]);
        //$interest = $this->MemberRegistration->GymMember->GymInterestArea->find("list",["keyField"=>"id","valueField"=>"interest"]);
        //$source = $this->MemberRegistration->GymMember->GymSource->find("list",["keyField"=>"id","valueField"=>"source_name"]);
        //$membership = $this->MemberRegistration->GymMember->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
        
        //$this->set("classes",$classes);
        //$this->set("groups",$groups);
        //$this->set("interest",$interest);
        //$this->set("source",$source);
        //$this->set("membership",$membership);
        $this->set("edit",false);
        if($this->request->is("post")){
            $this->request->data['member_id'] = $member_id;
            $image = $this->GYMFunction->uploadImage($this->request->data['image']);
            $this->request->data['image'] = (!empty($image)) ? $image : "logo.png";
            $this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
            $this->request->data['created_date'] = date("Y-m-d");
            $this->request->data['assign_group'] = json_encode($this->request->data['assign_group']);
            $this->request->data['membership_status'] = "Prospect";
            $this->request->data["role_name"] = $memberRole;
            
            $member = $this->MemberRegistration->GymMember->patchEntity($member,$this->request->data);
            if($this->MemberRegistration->GymMember->save($member)) {
                $this->request->data['member_id'] = $member->id;
                $this->GYMFunction->add_membership_history($this->request->data);
                /* if($this->addPaymentHistory($this->request->data)) {
                    // $this->Flash->success(__("Success! Record Saved Successfully."));
                } */
                
               /* if(!empty($this->request->data["assign_class"])) {
                    foreach($this->request->data["assign_class"] as $class) {
                        $new_row = $this->MemberRegistration->GymMemberClass->newEntity();
                        $data = array();
                        $data["member_id"] = $member->id;
                        $data["assign_class"] = $class;
                        $new_row = $this->MemberRegistration->GymMemberClass->patchEntity($new_row,$data);
                        $this->MemberRegistration->GymMemberClass->save($new_row);
                    }
                } */
                
                $sys_email = $this->GYMFunction->getSettings("email");
                $sys_name = $this->GYMFunction->getSettings("name");
                $headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
                $message = "<p>Hi {$this->request->data["first_name"]},</p>";
                $message .= "<p>Thank you for registering on our system.</p>";
                $message .= "<p>Your Username: {$this->request->data['username']}</p>";
                $message .= "<p>You can login once after admin review your account and activates it.</p>";
                $message .= "<p>Thank You.</p>";
                
                Email::configTransport('mailer', EmailConfig::$ANM);
                $email = new Email(EmailConfig::$ANM);
                $email->from(["pramod.yadav@anmsoft.com" => "Pramod"])
                ->to(["iby.rox@gmail.com",$this->request->data["email"]])
                ->emailFormat ("html")
                ->subject("Member Registered")
                ->send($message);
                
                $this->Flash->success(__("Registration completed successfully. Please Check email"));
                return true;
            }else {
                if($member->errors()) {
                    foreach($member->errors() as $error) {
                        foreach($error as $key=>$value) {
                            $this->Flash->error(__($value));
                        }
                    }
                }
            }
        }
    }
	
	private function addPaymentHistory($data) {
		$row = $this->MemberRegistration->MembershipPayment->newEntity();
		$save["member_id"] = $data["member_id"];
		$save["membership_id"] = $data["selected_membership"];
		$save["membership_amount"] = $this->GYMFunction->get_membership_amount($data["selected_membership"]);
		$save["paid_amount"] = 0;
		$save["start_date"] = $data["membership_valid_from"];
		$save["end_date"] = $data["membership_valid_to"];
		/* $save["membership_status"] = $data["membership_status"]; */
		$save["payment_status"] = 0;
		$save["created_date"] = date("Y-m-d");
		/* $save["created_dby"] = 1; */
		$row = $this->MemberRegistration->MembershipPayment->patchEntity($row,$save);
		if($this->MemberRegistration->MembershipPayment->save($row)){
		    return true;
		} else {
		    return false;
		}
	}
	
	
	public function regComplete()
	{
		$this->autoRender = false;
		echo "<br><p><i><strong>Success!</strong> Registration completed successfully.</i></p>";
		echo "<p><i><a href='{$this->request->base}/Users'>Click Here</a> to Redirect on login page.</i></p>";
	}
	
	public function getMembershipEndDate()
	{
		$this->autoRender=false;
		
		if($this->request->is("ajax"))
		{
			// $format = $this->GYMFunction->date_format();
			// $format = str_ireplace(array("yyyy","yy","dd","mm"),array("y","y","d","m"),$format);
			// $format = str_replace("yy","Y",$format);
			// $format = str_replace("dd","d",$format);
			// $format = str_replace("mm","m",$format);
			$date = $this->request->data["date"];
			$date = str_replace("/","-",$date);
			$membership_id = $this->request->data["membership"];
			$date1 = date("Y-m-d",strtotime($date));
			$membership_table =  TableRegistry::get("Membership");
			$row = $membership_table->get($membership_id)->toArray();
			$period = $row["membership_length"];
			$end_date = date("Y-m-d",strtotime($date1 . " + {$period} days"));
			echo $end_date;
			// echo "Asd";
			die;
		}
	}

}