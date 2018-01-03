<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script>
$(document).ready(function(){
	// $(".validateForm").validationEngine();
	$('.membership_list').multiselect({
		includeSelectAllOption: true	
	});
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bicycle"></i>
				<?php echo $title;?>
				<small><?php echo __("Assign Program");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProgramAssignment","programList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Assigned Program List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		
		<div class="box-body">
			<?php 
				echo $this->Form->create("assignprogram",["id" => "program-form", "class"=>"validateForm form-horizontal","role"=>"form"]);
			?>
			<div class='form-group'>
				<label class="control-label col-md-2" for="email"><?php echo __("Select Program");?><span class="text-danger"> *</span></label>
				<div class="col-md-6">
					<?php  echo $this->Form->select("program_id",$programs,["default"=>($edit)?array($data['program_id']):"","empty"=>__("Select Program"),"class"=>"validate[required] program_list form-control"]); ?>
				</div>
			</div>
				
			<div class='form-group'>
				<label class="control-label col-md-2" for="email"><?php echo __("Select Member");?><span class="text-danger"> *</span></label>
				<div class="col-md-6">
					<?php  echo $this->Form->select("member_id",$members,["default"=>($edit)?array($data['member_id']):"","empty"=>__("Select Member"),"class"=>"validate[required] member_list form-control"]); ?>
				</div>
		    </div>	
		    
		    <div class="form-group">
				<label class="control-label col-md-2" for="start_date"><?php echo __("Program Start Date");?><span class="text-danger"> *</span></label>				
				<div class="col-md-6">	
					<input id="start_date" class="form-control hasDatepicker validate[required]" type="text" value="<?php echo (isset($_POST['start_date'])) ? $_POST["start_date"]:"";?>" name="start_date">
				</div>	
			 </div>
				
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"assigned-program-id","name"=>"assigned-program-id","value"=>(($edit)?$data['assigned-program-id']:"")]);	?>
			
			<?php 
				echo $this->Form->button(__("Assign Program"),['class'=>"col-md-offset-3 btn btn-flat btn-success"]);
				echo $this->Form->end(); 
			?>
			<br><br>
		</div>
	</div>		
</section>
