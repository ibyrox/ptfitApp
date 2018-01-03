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
				<small><?php echo __("Program Template");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProgramWorkoutTemplate","programList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Program List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		
		<?php 
			echo $this->Form->create("addtemplate",["id" => "program-form", "class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
		<div class="box-body">
			<?php if($session["role_name"] == "dummy_not_required" ) {  ?>	
				<div class='form-group'>
					<label class="control-label col-md-2" for="email"><?php echo __("Select Trainer");?><span class="text-danger"> *</span></label>
					<div class="col-md-6">
						<?php  echo $this->Form->select("trainer_id",$members,["default"=>($edit)?array($data['cat_id']):"","empty"=>__("Select Trainer"),"class"=>"validate[required] cat_list form-control"]); ?>
					</div>
				</div>
			<?php } ?>
		
			<div class='form-group'>
				<label class="control-label col-md-2" for="name"><?php echo __("Program Name");?><span class="text-danger"> *</span></label>
				<div class="col-md-6">
					<?php 
						echo $this->Form->input("",["label"=>false,"id"=>"program_name","name"=>"program_name","class"=>"validate[required] form-control","value"=>(($edit)?$data['program_name']:"")]);
					?>
				</div>	
			</div>
		
			<div class='form-group'>
				<label class="control-label col-md-2" for="duration"><?php echo __("Program Duration(Days)");?><span class="text-danger"> *</span></label>
				<div class="col-md-6">
					<?php 
						echo $this->Form->input("",["type"=>"number","label"=>false,"id"=>"program_duration","name"=>"program_duration","class"=>"validate[required] form-control","value"=>(($edit)?$data['program_duration']:"")]);
					?>
				</div>	
			</div>
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"program-id","name"=>"program-id","value"=>(($edit)?$data['program-id']:"")]);	?>
			
			<div class='form-group'>
				<div class=" col-md-offset-3 col-md-2">
					<button id="save_program" type="button" data-url="<?php echo $this->request->base;?>/GymProgram/ajaxAddProgram" class=" btn btn-success save-program form-control"><?php echo __("Save Program");?></button>
				</div>
			</div>
			
		</div>

<!-- START -->
		<div id="workout-plan-0" class="box-body border border-primary workout-plan">
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"wrkout-persist-id-0","name"=>"wrkout-persist-id-0","value"=>(($edit)?$data['wrkout-persist-id']:"")]);	?>
			
			<input type="hidden" id="" name=""  value="0" />
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"wrkout-mstr-id-0","name"=>"wrkout-mstr-id-0","value"=>(($edit)?$data['wrkout-mstr-id']:"")]);	?>
			
		   <div class="form-row col-md-12">
		   		<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Workout Day:");?><span class="text-danger"> *</span></label>
					<?php 
							echo $this->Form->select("wrkout-days-0",$days,["id"=>"wrkout-days-0", "name"=>"wrkout-days-0","default"=>($edit)?array($data['id']):"","empty"=>__("Select Day"),"class"=>"validate[required] cat_list form-control wrkout-days-select"]);
				?>
				</div>
			
				<div class='form-group col-md-3'>
					<label class="control-label" for="sequence"><?php echo __("Workout Sequence");?><span class="text-danger"> *</span></label>
					<?php 
						echo $this->Form->input("wrkout-seq-0",["id"=>"wrkout-seq-0","type"=>"number","label"=>false,"id"=>"wrkout-seq-0","name"=>"wrkout-seq-0","class"=>"validate[required] form-control","value"=>(($edit)?$data['sequence']:"1")]);						
					?>
				</div>
				
				<div class='form-group col-md-3'>
					<button id="wrkout-remove_id-0" type="button" class="btn btn-danger remove-me invisible">Remove</button>
				</div>
				
		   </div>

		   <div class="form-row"> 
		    	<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Select Category");?><span class="text-danger"> *</span></label>
					<?php 
							echo $this->Form->select("wrkout-category_id-0",$categories,["id"=>"wrkout-category_id-0", "default"=>($edit)?array($data['id']):"","empty"=>__("Select Category"),"class"=>"validate[required] cat_list form-control"]);
				?>
				</div>
			
				<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Select Exercise");?><span class="text-danger"> *</span></label>
					<?php 
						echo $this->Form->select("wrkout-exercise_id-0",$exercises,["id"=>"wrkout-exercise_id-0","default"=>($edit)?array($data['id']):"","empty"=>__("Select Exercise"),"class"=>"validate[required] exercise_list form-control"]);
				?>
				</div>
				
				<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Select Level");?><span class="text-danger"> *</span></label>
					<?php 
						echo $this->Form->select("wrkout-level_id-0",$levels,["id"=>"wrkout-level-id-0","default"=>($edit)?array($data['id']):"","empty"=>__("Select Level"),"class"=>"validate[required] level_list form-control"]);
				?>
				</div>
				
				<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Select Mod");?></label>
					<?php 
						echo $this->Form->select("wrkout-mod_id-0",$mods,["id"=>"wrkout-mod_id-0","default"=>($edit)?array($data['id']):"","empty"=>__("Select Mod"),"class"=>"mod_list form-control"]);
				?>
				</div>
		    </div>
		
		<!-- END -->
		<div class="form-row"> 
			<div class="form-group col-md-3">
					<label class="control-label" for="weight"><?php echo __("Weight (in Kg)");?></label>				
					<?php echo $this->Form->input("wrkout-weight-0",["id"=>"wrkout-weight-0","type"=>"number","label"=>false,"name"=>"wrkout-weight-0","class"=>"form-control","value"=>(($edit)?$data['weight']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="sets"><?php echo __("Sets");?></label>			
				<?php echo $this->Form->input("wrkout-sets-0",["id"=>"wrkout-sets-0","type"=>"number","label"=>false,"name"=>"wrkout-sets-0","class"=>"form-control","value"=>(($edit)?$data['sets']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="reps"><?php echo __("Reps");?></label>			
				<?php echo $this->Form->input("wrkout-reps-0",["id"=>"wrkout-reps-0","type"=>"number","label"=>false,"name"=>"wrkout-reps-0","class"=>"form-control","value"=>(($edit)?$data['reps']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="rest_time"><?php echo __("Rest Time (in min)");?></label>			
				<?php echo $this->Form->input("wrkout-rest-0",["id"=>"wrkout-rest-0","type"=>"number","label"=>false,"name"=>"wrkout-rest-0","class"=>"form-control","value"=>(($edit)?$data['rest_time']:"")]); ?>
			</div>
			
		</div>
		<!-- Done -->
		
	   <div class='form-group'>
			<label class="control-label col-md-2" for="workout_video"><?php echo __("Workout Video");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("wrkout_video-0",["label"=>false,"id"=>"wrkout_video-0","name"=>"wrkout_video-0","class"=>"form-control","value"=>(($edit)?$data['workout_video']:"")]);
					?>
			</div>	
		</div>
		
	    <div class='form-group'>
			<label class="control-label col-md-3" for="workout_instructions"><?php echo __("Workout Instructions");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
				<?php  echo $this->Form->textarea("wrkout-instructions-0",["id"=>"wrkout-instructions-0","name"=>"wrkout-instructions-0","label"=>false,"class"=>"form-control","value"=>(($edit)?$data['workout_instructions']:"")]); 	?>
			</div>
	   </div>
	</div>
		
<hr>
	<div class="form-row">
		<div class='form-group col-md-6 col-md-offset-3'>
			<?php echo $this->Form->button(__("Save Program Template"),['id'=>"save-workout-plan" ,'class'=>"btn btn-flat btn-success"]); ?>
		</div>
		<div class='form-group col-md-4 col-md-offset-3'>
			<button class="control-label btn btn-flat btn-success add-more-wrkout" type="button">Add More Workout</button>
		</div>
		<br><br>
	</div>
	
	<?php echo $this->Form->end(); ?>
	</div>
		
</section>

<div id="wrkout_vidModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <iframe width="400" height="300" frameborder="0" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function(){
	$('.wrkout_vid').click(function () {
        var src = 'http://www.youtube.com/watch?v=ClpanvK2bII&amp;autoplay=1';
        $('#wrkout_vidModal').modal('show');
        $('#wrkout_vidModal iframe').attr('src', src);
    });

    $('#wrkout_vidModal button').click(function () {
        $('#myModal iframe').removeAttr('src');
    });
    
   //$('#wrkout-days-0').multiselect();
   
   var next = 0;
   $(".add-more-wrkout").click(function(e){
	   e.preventDefault();
	   next = next + 1;
	   var wrkoutHtml = '<div id="workout-plan-'+next+'" class="box-body border border-primary workout-plan">'+$('#workout-plan-0').html()+'</div>';
	   
	   wrkoutHtml = wrkoutHtml.replace(/wrkout-persist-id-0/g, "wrkout-persist-id-"+next).replace(/wrkout-remove_id-0/g,"wrkout-remove_id-"+next)
	   .replace(/wrkout-category_id-0/g, "wrkout-category_id-"+next).replace(/wrkout-exercise_id-0/g, "wrkout-exercise_id-"+next).replace(/wrkout-mod_id-0/g, "wrkout-mod_id-"+next)
	   .replace(/wrkout-level_id-0/g, "wrkout-level_id-"+next).replace(/wrkout-weight-0/g, "wrkout-weight-"+next).replace(/wrkout-sets-0/g, "wrkout-sets-"+next)
	   .replace(/wrkout-reps-0/g, "wrkout-reps-"+next).replace(/wrkout-rest-0/g, "wrkout-rest-"+next).replace(/wrkout-instructions-0/g, "wrkout-instructions-"+next)
	   .replace(/wrkout-days-0/g, "wrkout-days-"+next).replace(/wrkout_video-0/g, "wrkout_video-"+next).replace(/wrkout-seq-0/g, "wrkout-seq-"+next)
	   .replace(/wrkout-mstr-id-0/g, "wrkout-mstr-id-"+next).replace("invisible","");
	   
	  $('.workout-plan:last').after('<hr>'+wrkoutHtml);
	  //$('#wrkout-days-'+next).multiselect('rebuild');
	  //$('.wrkout-days-select').multiselect();
	  $(".remove-me").click(function(e){
		   e.preventDefault();
		   var elemID = $(this).attr('id');
		   elemID = elemID.replace("wrkout-remove_id-","");
		   $('#workout-plan-'+elemID).prev().remove();
		   $('#workout-plan-'+elemID).remove();
	   });
   });
   
});
    
</script>