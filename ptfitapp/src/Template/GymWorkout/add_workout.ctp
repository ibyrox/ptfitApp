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
				<small><?php echo __("Workout");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymWorkout","workoutList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Workout List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php 
			echo $this->Form->create("addworkout",["class"=>"validateForm form-horizontal","role"=>"form"]);
		?>	
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="name"><?php echo __("Workout Name");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"workout_name","class"=>"validate[required] form-control","value"=>(($edit)?$data['workout_name']:"")]);
				?>
			</div>	
		</div>
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Select Category");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("category_id",$categories,["default"=>($edit)?array($data['id']):"","empty"=>__("Select Category"),"class"=>"validate[required] cat_list form-control"]);
				?>
			</div>
		</div>
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Select Exercise");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("exercise_id",$exercises,["default"=>($edit)?array($data['id']):"","empty"=>__("Select Exercise"),"class"=>"validate[required] exercise_list form-control"]);
				?>
			</div>
		</div>
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Select Level");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("level_id",$levels,["default"=>($edit)?array($data['id']):"","empty"=>__("Select Level"),"class"=>"validate[required] level_list form-control"]);
				?>
			</div>
		</div>
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Select Mod");?><span class="text-danger"></span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("mod_id",$mods,["default"=>($edit)?array($data['id']):"","empty"=>__("Select mod"),"class"=>"mod_list form-control"]);
				?>
			</div>
		</div>
		
		<!-- Donee -->
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="workout_video"><?php echo __("Workout Video");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php  echo $this->Form->input("",["label"=>false,"name"=>"workout_video","class"=>" form-control","value"=>(($edit)?$data['workout_video']:"")]); 	?>
			</div>
		</div>

		<div class='form-group'>
			<label class="control-label col-md-2" for="workout_instructions"><?php echo __("Workout Instructions");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php  echo $this->Form->textarea("",["label"=>false,"name"=>"instructions","class"=>" form-control","value"=>(($edit)?$data['workout_instructions']:"")]); 	?>
			</div>
		</div>
		
		<div class="form-group col-md-3">
				<label class="control-label" for="weight"><?php echo __("Weight (in Kg)");?></label>				
				<?php echo $this->Form->input("",["label"=>false,"name"=>"weight","class"=>"validate[required] form-control","value"=>(($edit)?$data['weight']:"")]); ?>
		</div>
		
		<div class="form-group col-md-3">
			<label for="sets"><?php echo __("Sets");?></label>			
			<?php echo $this->Form->input("",["label"=>false,"name"=>"sets","class"=>"validate[required] form-control","value"=>(($edit)?$data['sets']:"")]); ?>
		</div>
		
		<div class="form-group col-md-3">
			<label for="reps"><?php echo __("Reps");?></label>			
			<?php echo $this->Form->input("",["label"=>false,"name"=>"reps","class"=>"validate[required] form-control","value"=>(($edit)?$data['reps']:"")]); ?>
		</div>
		
		<div class="form-group col-md-3">
			<label for="rest_time"><?php echo __("Rest Time (in min)");?></label>			
			<?php echo $this->Form->input("",["label"=>false,"name"=>"rest_time","class"=>"validate[required] form-control","value"=>(($edit)?$data['rest_time']:"")]); ?>
		</div>
		
		<?php if($session["role_name"] == "administrator" ) {  ?>	
		<div class='form-group'>
			<label class="control-label col-md-2" for="email"><?php echo __("Select Trainer");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("trainer_id",$members,["default"=>($edit)?array($data['cat_id']):"","empty"=>__("Select Trainer"),"class"=>"validate[required] cat_list form-control"]);
				?>
			</div>
		</div>
		<?php } ?>
		
		<?php 
			echo $this->Form->button(__("Save Workout"),['class'=>"col-md-offset-3 btn btn-flat btn-success"]);
			echo $this->Form->end();?>
		<br><br>
		</div>
	</div>
</section>