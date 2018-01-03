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
				<small><?php echo __("Exercise");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymExercise","exerciseList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Exercise List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php 
			echo $this->Form->create("addexercise",["class"=>"validateForm form-horizontal","role"=>"form"]);
		?>	
		
		<div class='form-group'>
			<label class="control-label col-md-2" for="name"><?php echo __("Exercise Name");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"name","class"=>"validate[required] form-control","value"=>(($edit)?$data['name']:"")]);
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
			echo $this->Form->button(__("Save Exercise"),['class'=>"col-md-offset-3 btn btn-flat btn-success"]);
			echo $this->Form->end();?>
		<br><br>
		</div>
	</div>
</section>