<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

Class ForgetPasswordController  extends AppController
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
        $this->Auth->allow(['index','getMembershipEndDate','addPaymentHistory']);
		if (in_array($this->request->action, ['getMembershipEndDate'])) {
			$this->eventManager()->off($this->Csrf);
		}
		 
    }
	
	public function index()
	{		
		$this->viewBuilder()->layout('login');
		if($this->request->is("post"))
		{
		    $member_email = $this->request->data['email_id'];
		    if($member_email) {
		        $staff = $this->GymMember->find("list",["keyField"=>"id","valueField"=>"email"])->where(["email"=>$member_email]);
				
				if(! ($this->request->data["assign_class"]))
				{
					foreach($this->request->data["assign_class"] as $class)
					{
						
					}
				}
				
				$sys_email = $this->GYMFunction->getSettings("email");
				$sys_name = $this->GYMFunction->getSettings("name");
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				$message = "<p>Hi {$this->request->data["first_name"]},</p>";
				$message .= "<p>Forget Password Recovery Email</p>";
				$message .= "<p>Your Username:{$this->request->data['username']}</p>";
				$message .= "<p>Click on link to reset the password <a href='{$this->request->base}/'>Click Here</a> ></p>";
				$message .= "<p>Thank You.</p>";
				@mail($this->request->data["email"],_("Password recovery Email"),$message,$headers);
				
				$this->Flash->success(__("Reset link has been sent successfully. Please Check email"));
				return $this->redirect(["controller"=>"users","action"=>"login"]);
				// return $this->redirect(["action"=>"regComplete"]);
			}else
			{				
				if($member->errors())
				{	
					foreach($member->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
						}						
					}
				}
			}			
		}
	}
	
}