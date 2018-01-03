<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {	
	$('.group_list').multiselect({
		includeSelectAllOption: true	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$('.class_list').multiselect({
		includeSelectAllOption: true		
	});
	
	$(".datepick").datepicker({format: 'yyyy-mm-dd'});
	$(".mem_valid_from").datepicker({dateFormat: 'yyyy-mm-dd'}).on("changeDate",function(ev){
				// var ajaxurl = document.location + "/GymAjax/get_membership_end_date";
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				var membership = $(".membership_id option:selected").val();		
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculatind date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response)
									{
										// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
										$(".valid_to").val(response);
										// alert(response);
										// console.log(response);
									}
						});
				}else{
					$(".valid_to").val("Select Membership");
				}
			});	
	$(".content-wrapper").css("height","2600px");	
});

function validate_multiselect()
{		
		var classes = $("#class_list").val();
		if(classes == null)
		{
			alert("Please Select Class or Add class class first.");
			return false;
		}else{
			return true;
		}		
}

</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-user"></i>
				<?php echo $title;?>
				<small><?php echo __("Member");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Members List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php				
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","onsubmit"=>"return validate_multiselect()"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
						
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Member ID").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"member_id","class"=>"form-control","disabled"=>"disabled","value"=>(($edit)?$data['member_id']:$member_id)]);
			echo "</div>";	
			echo "</div>";
			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required]","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","class"=>"form-control","value"=>(($edit)?$data['middle_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required]","value"=>(($edit)?$data['last_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => __('Male')],
						['value' => 'female', 'text' => __('Female')]
					];
			echo $this->Form->radio("gender",$radio,['default'=>($edit)?$data["gender"]:"male"]);			
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Date of birth").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","class"=>"form-control dob validate[required] datepick","value"=>(($edit)?date("Y-m-d",strtotime($data['birth_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo '<label class="control-label col-md-2" for="email">'. __("Group").'</label>';
			echo '<div class="col-md-8">';			
			echo @$this->Form->select("assign_group",$groups,["default"=>json_decode($data['assign_group']),"multiple"=>"multiple","class"=>"form-control group_list"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/GymGroup/addGroup/' class='btn btn-flat btn-default'>".__("Add Group")."</a>";
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required]","value"=>(($edit)?$data['address']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required]","value"=>(($edit)?$data['city']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("state").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"state","class"=>"form-control","value"=>(($edit)?$data['state']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Zip code").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"zipcode","class"=>"form-control validate[required]","value"=>(($edit)?$data['zipcode']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile","class"=>"form-control validate[required]","value"=>(($edit)?$data['mobile']:'')]);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Phone").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"phone","class"=>"form-control","value"=>(($edit)?$data['phone']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","value"=>(($edit)?$data['email']:'')]);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
					
			echo "<fieldset><legend>". __('Physical Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Weight").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"weight","class"=>"form-control","placeholder"=>"KG","value"=>(($edit)?$data['weight']:'')]);
			echo "</div>";	
			echo "</div>";

			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Height").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"height","class"=>"form-control","placeholder"=>__("Centimeter"),"value"=>(($edit)?$data['height']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Chest").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"chest","class"=>"form-control","placeholder"=>__("Inches"),"value"=>(($edit)?$data['chest']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Waist").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"waist","class"=>"form-control","placeholder"=>__("Inches"),"value"=>(($edit)?$data['waist']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Thing").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"thing","class"=>"form-control","placeholder"=>__("Inches"),"value"=>(($edit)?$data['thing']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Arms").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"arms","class"=>"form-control","placeholder"=>__("Inches"),"value"=>(($edit)?$data['arms']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Fat").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"fat","class"=>"form-control","placeholder"=>__("Percentage"),"value"=>(($edit)?$data['fat']:'')]);
			echo "</div>";	
			echo "</div>";	
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Login Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Username").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","value"=>(($edit)?$data['username']:''),"readonly"=> (($edit)?true:false)]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Password").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control validate[required]","value"=>(($edit)?$data['password']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Display Image").'</label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "logo.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
								
			echo "<fieldset><legend>". __('More Information')."</legend>";			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Staff Member").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Staff Member"),"class"=>"form-control validate[required]"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-default'>".__("Add Staff")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Interested Area").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("intrested_area",$interest,["default"=>$data['intrested_area'],"empty"=>__("Select Interest"),"class"=>"form-control interest_list"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default interest-list' data-url='{$this->request->base}/GymAjax/interestList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Source").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("source",$source,["default"=>$data['source'],"empty"=>__("Select Source"),"class"=>"form-control source_list"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default source-list' data-url='{$this->request->base}/GymAjax/sourceList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Referred By").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("referrer_by",$referrer_by,["default"=>$data['referrer_by'],"empty"=>__("Select Staff Member"),"class"=>"form-control"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-default'>".__("Add Staff")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Inquiry Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"inquiry_date","class"=>"form-control datepick","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['inquiry_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Trial End Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"trial_end_date","class"=>"form-control datepick","value"=>(($edit)? date($this->Gym->getSettings("date_format"),strtotime($data['trial_end_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			?>
			
			<div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Member Type");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="member_type" value="Member" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Member") ? "checked":"checked";?>><?php echo __("Member");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Prospect" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Prospect") ? "checked":"";?>><?php echo __("Prospect");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Alumni" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Alumni") ? "checked":"";?>><?php echo __("Alumni");?></label>
				</div>
			</div>	
			
			<?php
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("selected_membership",$membership,["default"=>$data['selected_membership'],"empty"=>__("Select Membership"),"class"=>"form-control validate[required] membership_id"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/Membership/add/' class='btn btn-flat btn-default'>".__("Add Membership")."</a>";
			echo "</div>";	
			echo "</div>";				
					
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Class").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->select("assign_class",($edit)?$classes:"",["default"=>($edit)?$member_class:"","class"=>"class_list form-control","id"=>"class_list","multiple"=>"multiple"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/ClassSchedule/addClass/' class='btn btn-flat btn-default'>".__("Add Class")."</a>";
			echo "</div>";	
			echo "</div>";
			
			if($edit)
			{
			?>
				<div class="form-group">
					<div class="control-label col-md-2">
						<label><?php echo __("Membership Status");?></label>
					</div>
					<div class="col-md-6">
						<label class="radio-inline"><input type="radio" name="membership_status" value="Continue" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Continue") ? "checked":"";?>><?php echo __("Continue");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Expired" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Expired") ? "checked":"";?>><?php echo __("Expired");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Dropped" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Dropped") ? "checked":"";?>><?php echo __("Dropped");?></label>
					</div>
				</div>	
			<?php
			}
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership Valid From").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid_from","class"=>"form-control validate[required] mem_valid_from","value"=>(($edit && $data['membership_valid_from']!="")?date("Y/m/d",strtotime($data['membership_valid_from'])):'')]);
			echo "</div>";
			echo '<div class="col-md-1 no-padding text-center">';
			echo "To";
			echo "</div>";
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid_to","class"=>"form-control validate[required] valid_to","value"=>(($edit && $data['membership_valid_to']!="")?date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to'])):''),"readonly"=>true]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Payment Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_pay_date","class"=>"form-control datepick","value"=>(($edit)?date("Y-m-d",strtotime($data['first_pay_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";
			
			echo "<br>";
			echo $this->Form->button(__("Save Member"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"add_member"]);
			echo $this->Form->end();
		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_end_date" id="mem_date_check_path">
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_classes" id="mem_class_url">
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
 <script>
$(".membership_status_type").change(function(){
	if($(this).val() == "Prospect" || $(this).val() == "Alumni" )
	{
		$(".class-member").hide("SlideDown");
		$(".class-member input,.class-member select").attr("disabled", "disabled");				
	}else{
		$(".class-member").show("SlideUp");
		$(".class-member input,.class-member select").removeAttr("disabled");	
		$("#available_classes").attr("disabled", "disabled");
	}
});
if($(".membership_status_type:checked").val() == "Prospect" || $(".membership_status_type:checked").val() == "Alumni")
{ 
$(".class-member").hide("SlideDown");
$(".class-member input,.class-member select").attr("disabled", "disabled");		
}

	
</script>