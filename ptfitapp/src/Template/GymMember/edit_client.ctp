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
	
	$(".content-wrapper").css("height","2600px");	
});

function validate_multiselect()
{		

//Do JS alidate
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
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","disabled"=>"disabled","class"=>"form-control validate[required]","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","disabled"=>"disabled","class"=>"form-control","value"=>(($edit)?$data['middle_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","disabled"=>"disabled","class"=>"form-control validate[required]","value"=>(($edit)?$data['last_name']:'')]);
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
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","disabled"=>"disabled","class"=>"form-control dob validate[required] datepick","value"=>(($edit)?date("Y-m-d",strtotime($data['birth_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('More Information')."</legend>";	
					
			echo "<div class='form-group'>";	
			if($session["role_name"] == "staff_member"){
				echo '<label class="control-label col-md-2" for="email">'. __("Staff Member").'<span class="text-danger"> *</span></label>';
				echo '<div class="col-md-9">';
				echo '<label class="control-label col-md-2" for="email">'.$assign_staff_name.'<span class="text-danger"></span></label>';
				echo "</div>";
			}else{
				echo '<label class="control-label col-md-2" for="email">'. __("Select Staff Member").'<span class="text-danger"> *</span></label>';
				echo '<div class="col-md-6">';
				echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Staff Member"),"class"=>"form-control validate[required]"]);
				echo "</div>";	
			}
			echo '<div class="col-md-2">';
			echo "</div>";	
			echo "</div>";
			
			if($session["role_name"] == "staff_member"){
				echo "<div class='form-group'>";
				echo '<label class="control-label col-md-2" for="email">'. __("Group").'</label>';
				echo '<div class="col-md-8">';			
				echo @$this->Form->select("assign_group",$groups,["default"=>json_decode($data['assign_group']),"multiple"=>"multiple","class"=>"form-control group_list"]);
				echo "</div>";	
				echo '<div class="col-md-2">';
				echo "<a href='{$this->request->base}/GymGroup/addGroup/' class='btn btn-flat btn-default'>".__("Add Group")."</a>";
				echo "</div>";	
				echo "</div>";
			}
			
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