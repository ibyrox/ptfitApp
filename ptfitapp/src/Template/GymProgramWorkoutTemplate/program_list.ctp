<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                 
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}
	                ],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}		
	});
});		
</script>
<?php
if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
{ ?>
<script>

$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
	table.column(1).visible( true );
});
</script>
<?php } ?>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Program Template List");?>
				<small>&nbsp;<?php echo __("Program Template");?></small>
			  </h1>
			  <?php if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant") { ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProgramWorkoutTemplate","addTemplate");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Program Template");?></a>
			  </ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Program Name");?></th>
					<th><?php echo __("Program Duration");?></th>
					<th><?php echo __("Duration Unit");?></th>
					<?php 
						if($session["role_name"] != "staff_member" ) { 
						echo "<th>".__("Program Trainer")."</th>";
					}
					?>						
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($data as $row) {
					echo "<tr><td>{$row['name']}</td>";				
					echo "<td>{$row['duration']} </td>";
					echo "<td>{$row['duration_unit']} </td>";
					if($session["role_name"] != "staff_member" ) {
						 //echo "<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>";
					}
					
					if($session['id'] == $row['trainer_id']){
						echo  "<td><a href='{$this->request->base}/GymProgramWorkoutTemplate/viewProgram/{$row['id']}' title='View' class='btn btn-flat btn-primary'><i class='fa fa-eye'></i></a>";
					 	echo " <a href='{$this->request->base}/GymProgramWorkoutTemplate/editTemplate/{$row['id']}' title='Edit' class='btn btn-flat btn-primary'><i class='fa fa-edit'></i></a>";
					 	echo " <a href='{$this->request->base}/GymProgramWorkoutTemplate/deleteProgram/{$row['id']}' title='Delete' class='btn btn-flat btn-danger' onClick=\"return confirm('Are you sure,You want to delete this record?');\"><i class='fa fa-trash-o'></i></a>";	
					}else{
						echo  "<td><a href='{$this->request->base}/GymProgramWorkoutTemplate/viewProgram/{$row['id']}' title='View' class='btn btn-flat btn-primary'><i class='fa fa-eye'></i></a>";
						echo  "<td><a href='{$this->request->base}/GymProgramWorkoutTemplate/copyAdminTemplate/{$row['id']}' title='Copy Template' class='btn btn-flat btn-primary'><i class='fa fa-files-o'></i></a>";				
					}
					
					echo "</td></tr>";
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Program Name");?></th>
					<th><?php echo __("Program Duration");?></th>
					<th><?php echo __("Duration Unit");?></th>
					<?php if($session["role_name"] != "staff_member" ) { 
						echo "<th>".__("Program Trainer")."</th>";
					} ?>						
					<th><?php echo __("Action");?></th>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</section>