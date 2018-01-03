<?php 
namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\View\Helper\UrlHelper;
use Cake\Datasource\ConnectionManger;
use Cake\Mailer\Email;

Class GYMfunctionComponent extends Component
{	
	public function sanitize_string($str)
	{
		$str = urldecode ($str );
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		$str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
		return $str ;
	}
	var $helpers = array('Url'); //Loading Url Helper
	public function createurl($controller,$action)
	{
		return $this->Url->build(["controller" => $controller,"action" => $action]);		
	}
	
	public function uploadImage($file)
	{
		$new_name = "";
		$img_name = $file["name"];	
		if(!empty($img_name))
		{
			$tmp_name = $file["tmp_name"];					
			$ext = substr(strtolower(strrchr($img_name, '.')), 1); 
			$new_name = time() . "_" . rand(000000, 999999). "." . $ext;		
			move_uploaded_file($tmp_name,WWW_ROOT . "/upload/".$new_name);	
		}
		return $new_name;
	}
	
	public function getSettings($key)
	{
		$settings = TableRegistry::get("GeneralSetting");
		$row = $settings->find()->all();
		$row = $row->first()->toArray();	
		$value = "";
		switch($key)
		{
			CASE "name":
				$value =  $row[$key];
			break;
			CASE "gym_logo":
				$value = $row[$key];
			break;
			CASE "date_format":
				$value = $row[$key];
			break;
			CASE "country":
				$value = $row[$key];
			break;
			CASE "enable_rtl":
				$value = $row[$key];
			break;
			CASE "weight":
				$value = $row[$key];
			break;
			CASE "height":
				$value = $row[$key];
			break;
			CASE "chest":
				$value = $row[$key];
			break;
			CASE "waist":
				$value = $row[$key];
			break;
			CASE "thing":
				$value = $row[$key];
			break;
			CASE "arms":
				$value = $row[$key];
			break;
			CASE "fat":
				$value = $row[$key];
			break;
			CASE "waist":
				$value = $row[$key];
			break;
			CASE "member_can_view_other";
				$value = $row[$key];
			break;
			CASE "enable_message":
				$value = $row[$key];
			break;
			CASE "paypal_email":
				$value = $row[$key];
			break;
			CASE "currency":
				$value = $row[$key];
			break;
			CASE "enable_sandbox":
				$value = $row[$key];
			break;
			CASE "enable_alert":
				$value = $row[$key];
			break;
			CASE "reminder_message":
				$value = $row[$key];
			break;
			CASE "reminder_days":
				$value = $row[$key];
			break;
			CASE "email":
				$value = $row[$key];
			break;
			CASE "staff_can_view_own_member":
				$value = $row[$key];
			break;
			CASE "staff_can_view_member_group":
			    $value = $row[$key];
			break;    
			CASE "calendar_lang":
				$value = $row[$key];
			break;
			CASE "system_installed":
				$value = $row[$key];
			break;
			CASE "left_header":
				$value = $row[$key];
			break;
			CASE "footer":
				$value = $row[$key];
			break;
			CASE "datepicker_lang":
				$value = $row[$key];
			break;
			CASE "sys_language":
				@$value = $row[$key];
			break;
			CASE "system_version":
				@$value = (isset($row[$key]))?$row[$key].".0":"1.0";			
			break;
		}
		return $value;
	}
	
	public function date_format()
	{
		$settings = TableRegistry::get("GeneralSetting");
		$row = $settings->find()->all();
		$row = $row->first()->toArray();	
		$value = $row["date_format"];
		return $value;
	}
	
	public function add_membership_history($data)
	{
		$history_table = TableRegistry::get("membershipHistory");
		$history = $history_table->newEntity();
		$history = $history_table->patchEntity($history,$data);
		$history_table->save($history);
	}
	
	public function generate_chart($type,$mid)
	{		
		$report_type_array = array();
		$measurment_table = TableRegistry::get("GymMeasurement");
		$data = $measurment_table->find()->where(["user_id"=>$mid])->hydrate(false)->toArray();			
		foreach($data as $row)
		{			
			$all_data[$row["result_measurment"]][]=array('result'=>$row["result"],'date'=>$row["result_date"]->format('Y-m-d'));
		}
	
		
		switch($type)
		{
			CASE "Weight":
				$report_type_array[] = array('date','Weight	');
				if(isset($all_data['Weight']) && !empty($all_data['Weight']))
				{
					foreach($all_data['Weight'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Thigh":
				$report_type_array[] = array('date','Thigh	');
				if(isset($all_data['Weight']) && !empty($all_data['Thigh']))
				{
					foreach($all_data['Thigh'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Height":
				$report_type_array[] = array('date','Height	');
				if(isset($all_data['Height']) && !empty($all_data['Height']))
				{
					foreach($all_data['Height'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Chest":
				$report_type_array[] = array('date','Chest	');
				if(isset($all_data['Chest']) && !empty($all_data['Chest']))
				{
					foreach($all_data['Chest'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Waist":
				$report_type_array[] = array('date','Waist	');
				if(isset($all_data['Waist']) && !empty($all_data['Waist']))
				{
					foreach($all_data['Waist'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Arms":
				$report_type_array[] = array('date','Arms	');
				if(isset($all_data['Arms']) && !empty($all_data['Arms']))
				{
					foreach($all_data['Arms'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Fat":
				$report_type_array[] = array('date','Fat	');
				if(isset($all_data['Fat']) && !empty($all_data['Fat']))
				{
					foreach($all_data['Fat'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
		}
		return $report_type_array;
	}
	
	public function report_option($report_type)
	{
		$report_title = '';
		$htitle = "";
		$ytitle = "";
		if($report_type == 'Weight')
		{
			$report_title = __('Weight Report');
			$htitle = __('Day');
			$vtitle = $this->getSettings( 'weight' );
		}
		if($report_type == 'Thigh')
		{
			$report_title = __('Thigh Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'thing' );
		}
		if($report_type == 'Height')
		{
			$report_title = __('Height Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'height' );
		}
		if($report_type == 'Chest')
		{
			$report_title = __('Chest Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'chest' );
		}
		if($report_type == 'Waist')
		{
			$report_title = __('Waist Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'waist' );
		}
		if($report_type == 'Arms')
		{
			$report_title = __('Arms Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'arms' );
		}
		if($report_type == 'Fat')
		{
			$report_title = __('Fat Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'fat' );
		}
		$options = Array(
				'title' => $report_title,
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
		
		
				//'bar'  => Array('groupWidth' => '70%'),
				//'lagend' => Array('position' => 'none'),
				'hAxis' => Array(
						'title' => $htitle,
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 11),
						'maxAlternation' => 2
							
						//'annotations' =>Array('textStyle'=>Array('fontSize'=>5))
				),
				'vAxis' => Array(
						'title' => $vtitle,
						'minValue' => 0,
						'maxValue' => 5,
						'format' => '#',
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 11)
				),
				'colors' => array('#E14444')
			);
		return $options;				
	}
	
	
	public function save_member_login_details($username,$password,$role,$mid)
	{
		$login_tbl = TableRegistry::get("GymLoginDetails");
		$row = $login_tbl->newEntity();
		$data["username"] = $username;
		$data["password"] = $password;
		$data["role_name"] = $role;
		$data["member_id"] = $mid;
		$data["created_date"] = date("Y-m-d");
		$row = $login_tbl->patchEntity($row,$data);
		if($login_tbl->save($row))
		{
			return true;
		}else
		{ 		
			return false;
		}
	}
	
	public function username_check($username)
	{
		$login_tbl = TableRegistry::get("GymLoginDetails");
		$query = $login_tbl->find("all")->where(["username"=>$username]);
		$count = intval($query->count());
		if($count == 1){return false;}else{return true;}
	}
	
	public function get_membership_amount($mid)
	{ 		
		$mem_tbl = TableRegistry::get("Membership");
		$amt = $mem_tbl->get($mid)->toArray();		
		return $amt["membership_amount"];
	}
	
	public function get_membership_name($mid)
	{ 		
		$mem_tbl = TableRegistry::get("Membership");
		$amt = $mem_tbl->get($mid)->toArray();		
		return $amt["membership_label"];
	}
	
	public function get_membership_paymentstatus($mp_id)
	{
	$membership_payment_tbl = TableRegistry::get('MembershipPayment');	
	$result = $membership_payment_tbl->get($mp_id)->toArray();
	if($result['paid_amount'] >= $result['membership_amount'])
		return 'Fully Paid';		
	elseif($result['paid_amount'] == 0 )
		return __('Not Paid');
	else
		return __('Partially Paid');
	
	/*	
	$mem_table = TableRegistry::get('Membership');	
	$signup_fee = $mem_table->get($result['membership_id'])->toArray();
	$signup_fee = $signup_fee["signup_fee"];
	// var_dump($result);
	if($result['paid_amount'] >= $result['membership_amount'] + $signup_fee)
		return 'Fully Paid';		
	elseif($result['paid_amount'] == 0 )
		return 'Not Paid';
	else
		return 'Partially Paid';
	*/
	}	
	
	public function get_user_name($uid)
	{
		$mem_table = TableRegistry::get("GymMember");
		$name = $mem_table->get($uid)->toArray();
		return $name["first_name"] ." ". $name["last_name"];
	}	

	function get_currency_symbol( $currency = '' )
	{			
		$currency = $this->getSettings("currency");
			switch ( $currency ) {
			case 'AED' :
			$currency_symbol = 'د.إ';
			break;
			case 'AUD' :
			case 'CAD' :
			case 'CLP' :
			case 'COP' :
			case 'HKD' :
			case 'MXN' :
			case 'NZD' :
			case 'SGD' :
			case 'USD' :
			$currency_symbol = '&#36;';
			break;
			case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
			case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
			case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
			case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
			case 'CNY' :
			case 'JPY' :
			case 'RMB' :
			$currency_symbol = '&yen;';
			break;
			case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
			case 'DKK' :
			$currency_symbol = 'kr.';
			break;
			case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
			case 'EGP' :
			$currency_symbol = 'EGP';
			break;
			case 'EUR' :
			$currency_symbol = '&euro;';
			break;
			case 'GBP' :
			$currency_symbol = '&pound;';
			break;
			case 'HRK' :
			$currency_symbol = 'Kn';
			break;
			case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
			case 'IDR' :
			$currency_symbol = 'Rp';
			break;
			case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
			case 'INR' :
			$currency_symbol = 'Rs.';
			break;
			case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
			case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
			case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
			case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
			case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
			case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
			case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
			case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
			case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
			case 'RON' :
			$currency_symbol = 'lei';
			break;
			case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
			case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'THB' :
			$currency_symbol = '&#3647;';
			break;
			case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
			case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
			case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
			case 'VND' :
			$currency_symbol = '&#8363;';
			break;
			case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
			default :
			$currency_symbol = $currency;
			break;
		}
		return $currency_symbol;

	}
		
	public function sendAlertEmail()
	{
		$email = new Email('default');
		$check_alert_on = $this->getSettings("enable_alert");
		$sys_email = $this->getSettings("email");
		$sys_name = $this->getSettings("name");
		$reminder_days = $this->getSettings("reminder_days");		
		$reminder_message = $this->getSettings("reminder_message");
		$search = ["GYM_MEMBERNAME","GYM_MEMBERSHIP","GYM_STARTDATE","GYM_ENDDATE"];				
			
		$mem_table = TableRegistry::get("GymMember");
		$m_table = TableRegistry::get("Membership");
		$data = $mem_table->find("All")->where(function($exp){
				return $exp
						->gte("membership_valid_to",date("Y-m-d"))
						->eq("role_name","member");										
			})->hydrate(false)->toArray();
				
		$user_ids = array();
		foreach($data as $member)
		{
			if($member["alert_sent"] == 0)
			{
				/* $membership = $m_table->get($member["selected_membership"])->toArray(); */
				$membership = $m_table->find()->where(["id"=>$member["selected_membership"]])->hydrate(false)->toArray();
		
				if(!empty($membership))
				{
					$membership = $membership[0];
					$member_name = $member["first_name"]." ".$member["last_name"];
					$replace = [$member_name,$membership["membership_label"],$member["membership_valid_from"],$member["membership_valid_to"]];
					$reminder_message = str_replace($search,$replace,$reminder_message);
					$expiry_date = $member["membership_valid_to"]->format("Y-m-d");
					$mail_date = date('Y-m-d',(strtotime ( "-{$reminder_days} day" , strtotime ( $expiry_date) ) ));
					$curr_date = date("Y-m-d");
					$str_mail_date = strtotime($mail_date);
					$str_curr_date = strtotime($curr_date);
					$last_date = strtotime($expiry_date);
					/* if($curr_date == $mail_date) */
					if($curr_date > $mail_date && $curr_date <= $last_date)
					{						
						$to = $member["email"];
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						/* $email->from([$sys_email => $sys_name])
						->to($to)
						->subject( _("Membership Reminder Alert!"))
						->send($reminder_message); */
						mail($to,_("Membership Reminder Alert!"),$reminder_message,$headers);
						$user_ids[] = $member["id"];
					}
				}
			}
		}			
		if(!empty($user_ids))
		{
			$rows = $mem_table->updateAll(["alert_sent"=>1],["id IN"=>$user_ids]);
		}			
	}
	
	public function get_class_by_member($mid)
	{
		$class_table = TableRegistry::get("GymMemberClass");
		$class_sche_table = TableRegistry::get("ClassSchedule");
		$row = $class_table->find()->where(["member_id"=>$mid])->select(["assign_class"])->hydrate(false)->toArray();
		$class = array();
		foreach($row  as $data)
		{
			$class[]= $data["assign_class"];
		}
		return $class;
	}
	
	
	public function index()
	{
		$msg = "First line of text\nSecond line of text";
		$to = "pramod.yaadv@gmail.com";
		mail($to,"My subject",$msg);
		$this->autoRender = false ;
	}
	
	public function word_list_for_translation()
	{
		$months = array( __("January"),__("February"),__("March"),__("April"),
		__("May"),__("June"),__("July"),__("August"),__("September"),__("October"),__("November"),__("December"),
		__("You are not authorized to access that location."));
	}
}