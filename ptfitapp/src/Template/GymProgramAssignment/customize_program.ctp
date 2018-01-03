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
				<small><?php echo __("Custom Program");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProgramAssignment","programList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Program List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		
		<?php 
			echo $this->Form->create("customizeprogram",["id" => "program-form", "class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
		<div class="box-header">
			<div class="form-row">
		   		<div class='form-group col-md-6'>
					<label class="control-label" for="name"><?php echo __("Program Name : ");?> <?php echo $program_data['name'];?></label>
				</div>
			
				<div class='form-group col-md-6'>
					<label class="control-label" for="duration"><?php echo __("Program Duration(Days) : ");?> <?php echo $program_data['duration'];?></label>
				</div>
		   </div>
		</div>
		<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"program-id","name"=>"program-id","value"=>(($edit)?$program_data['id']:"")]);	?>
		<input type="hidden" name="program_assign_id" value="<?php echo $program_data['program_assign_id']; ?>">
		<hr>
		<br>
		<?php
		 $cnt = 0;	
		 foreach($workouts as $row) { ?>
			<!-- Start -->			
			<div id="workout-plan-<?php echo $cnt; ?>" class="box-body border border-primary workout-plan">
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"wrkout-persist-id-$cnt","name"=>"wrkout-persist-id-$cnt","value"=>(($edit)?$row['id']:"")]);	?>
			
			<?php  echo $this->Form->hidden("",["type"=>"number","label"=>false,"id"=>"wrkout-mstr-id-$cnt","name"=>"wrkout-mstr-id-$cnt","value"=>(($edit)?$row['wrkout-mstr-id']:"")]);	?>
		   <div class="form-row">
		   		<div class='form-group col-md-6'>
					<label class="control-label" for="email"><?php echo __("Workout Day: ");?> <?php echo $row['day'];?></label>
				</div>
			
				<div class='form-group col-md-6'>
					<label class="control-label" for="sequence"><?php echo __("Workout Sequence: ");?> <?php echo $row['seq'];?></label>
				</div>
		   </div>
		   <br>

		   <div class="form-row"> 
				 <div class='form-group col-md-3'>
						<label class="control-label" for="name"><?php echo __("Category: ");?> <?php echo $row['gym_category']['name'];?></label>
				</div>
		   		<div class='form-group col-md-3'>
						<label class="control-label" for="email"><?php echo __("Exercise: ");?> <?php echo $row['gym_exercise']['name'];?></label>
				</div>
				<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Level: ");?> <?php echo $row['gym_level']['level'];?></label>
				</div>
				
				<div class='form-group col-md-3'>
					<label class="control-label" for="email"><?php echo __("Mod: ");?><?php echo $row['gym_mod']['name'];?></label>
				</div>
				<input type="hidden" name="wrkout-days-<?php echo $cnt; ?>" value="<?php echo $row['day']; ?>">
				<input type="hidden" name="wrkout-seq-<?php echo $cnt; ?>" value="<?php echo $row['seq']; ?>">
				<input type="hidden" name="wrkout-category_id-<?php echo $cnt; ?>" value="<?php echo $row['category_id']; ?>">
				<input type="hidden" name="wrkout-exercise_id-<?php echo $cnt; ?>" value="<?php echo $row['exercise_id']; ?>">
				<input type="hidden" name="wrkout-mod_id-<?php echo $cnt; ?>" value="<?php echo $row['mod_id']; ?>">
				<input type="hidden" name="wrkout-level_id-<?php echo $cnt; ?>" value="<?php echo $row['level_id']; ?>">
				<input type="hidden" name="wrkout-mstr-id-<?php echo $cnt; ?>" value="<?php echo $row['workout_id']; ?>">
				
		 </div>
		<!-- END -->
		<div class="form-row"> 
			<div class="form-group col-md-3">
					<label class="control-label" for="weight"><?php echo __("Weight (in Kg)");?></label>				
					<?php echo $this->Form->input("wrkout-weight-$cnt",["id"=>"wrkout-weight-$cnt","type"=>"number","label"=>false,"name"=>"wrkout-weight-$cnt","class"=>"form-control","value"=>(($edit)?$row['weight']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="sets"><?php echo __("Sets");?></label>			
				<?php echo $this->Form->input("wrkout-sets-$cnt",["id"=>"wrkout-sets-$cnt","type"=>"number","label"=>false,"name"=>"wrkout-sets-$cnt","class"=>"form-control","value"=>(($edit)?$row['sets']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="reps"><?php echo __("Reps");?></label>			
				<?php echo $this->Form->input("wrkout-reps-$cnt",["id"=>"wrkout-reps-$cnt","type"=>"number","label"=>false,"name"=>"wrkout-reps-$cnt","class"=>"form-control","value"=>(($edit)?$row['reps']:"")]); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label for="rest_time"><?php echo __("Rest Time (in min)");?></label>			
				<?php echo $this->Form->input("wrkout-rest-$cnt",["id"=>"wrkout-rest-$cnt","type"=>"number","label"=>false,"name"=>"wrkout-rest-$cnt","class"=>"form-control","value"=>(($edit)?$row['rest_time']:"")]); ?>
			</div>
			
		</div>
		<!-- Done -->
		
	   <div class='form-group'>
			<label class="control-label col-md-2" for="workout_video"><?php echo __("Workout Video");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("wrkout_video-$cnt",["label"=>false,"id"=>"wrkout_video-$cnt","name"=>"wrkout_video-$cnt","class"=>"form-control","value"=>(($edit)?$row['wrkout_video']:"")]);
					?>
			</div>	
		</div>
		
	    <div class='form-group'>
			<label class="control-label col-md-3" for="workout_instructions"><?php echo __("Workout Instructions");?><span class="text-danger"> *</span></label>
			<div class="col-md-8">
				<?php  echo $this->Form->textarea("wrkout-instructions-$cnt",["id"=>"wrkout-instructions-$cnt","name"=>"wrkout-instructions-$cnt","label"=>false,"class"=>"form-control","value"=>(($edit)?$row['instructions']:"")]); 	?>
			</div>
	   </div>
	</div>
					
	<?php $cnt++; } ?>
		
<hr>
	<?php echo $this->Form->button(__("Save Customize Program"),['id'=>"save-workout-plan" ,'class'=>"col-md-offset-5 btn btn-flat btn-success"]); ?>
	<br><br>
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
	
	$(function() {
		var htm = '';
		for (var i = 1; i <= <?php echo $program_data['duration'];?>; i++) {
			htm += '<option>' + i + '</option>';	
		}
		//$('.wrkout-days-select').append(htm);	
	});
	
	$('.wrkout_vid').click(function () {
        var src = 'http://www.youtube.com/watch?v=ClpanvK2bII&amp;autoplay=1';
        $('#wrkout_vidModal').modal('show');
        $('#wrkout_vidModal iframe').attr('src', src);
    });

    $('#wrkout_vidModal button').click(function () {
        $('#myModal iframe').removeAttr('src');
    });
    
   //$('#wrkout-days-0').multiselect();
   
   var next = <?php echo $cnt-1; ?>;
   $(".add-more-wrkout").click(function(e){
	   e.preventDefault();
	   next = next + 1;
	   var wrkoutHtml = '<div id="workout-plan-'+next+'" class="box-body border border-primary workout-plan">'+$('#workout-plan-0').html()+'</div>';
	   
	   wrkoutHtml = wrkoutHtml.replace(/wrkout-persist-id-0/g, "wrkout-persist-id-"+next).replace(/wrkout-remove_id-0/g,"wrkout-remove_id-"+next)
	   .replace(/wrkout-category_id-0/g, "wrkout-category_id-"+next).replace(/wrkout-exercise_id-0/g, "wrkout-exercise_id-"+next).replace(/wrkout-mod_id-0/g, "wrkout-mod_id-"+next)
	   .replace(/wrkout-level-id-0/g, "wrkout-level-id-"+next).replace(/wrkout-weight-0/g, "wrkout-weight-"+next).replace(/wrkout-sets-0/g, "wrkout-sets-"+next)
	   .replace(/wrkout-reps-0/g, "wrkout-reps-"+next).replace(/wrkout-rest-0/g, "wrkout-rest-"+next).replace(/wrkout-instructions-0/g, "wrkout-instructions-"+next)
	   .replace(/wrkout-days-0/g, "wrkout-days-"+next).replace(/wrkout_video-0/g, "wrkout_video-"+next).replace(/wrkout-seq-0/g, "wrkout-seq-"+next)
	   .replace(/wrkout-mstr-id-0/g, "wrkout-mstr-id-"+next).replace("invisible","");
	   
	  $('.workout-plan:last').after('<hr>'+wrkoutHtml);
	  $('#wrkout-persist-id-'+next).val("");
	  $('#wrkout-sets-'+next).val("");
	  $('#wrkout-reps-'+next).val("");
	  $('#wrkout-rest-'+next).val("");
	  $('#wrkout-seq-'+next).val("");
	  $('#wrkout-weight-'+next).val("");
	  $('#wrkout_video-'+next).val("");
	  $('#wrkout-instructions-'+next).val("");
	  $('#wrkout-days-'+next+' option:eq(0)').attr('selected','selected');
	  $('#wrkout-category_id-'+next+' option:eq(0)').attr('selected','selected');
	  $('#wrkout-exercise_id-'+next+' option:eq(0)').attr('selected','selected');
	  $('#wrkout-level-id-'+next+' option:eq(0)').attr('selected','selected');
	  $('#wrkout-mod_id-'+next+' option:eq(0)').attr('selected','selected');
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