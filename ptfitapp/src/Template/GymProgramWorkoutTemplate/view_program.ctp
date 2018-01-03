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
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true}
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
				<?php echo __("Complete Program Detail");?>
				<small>&nbsp;<?php echo __("Program Detail");?></small>
			  </h1>
			  <?php if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant") { ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymProgramWorkoutTemplate","programList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Program List");?></a>
			  </ol>
			<?php } ?>
			</section>
		</div>
		
		<div class="box-header">
			<div class='form-group'>
				<label class="control-label col-md-10" for="name"><?php echo __("Program Name : ");?><?php echo $program_data['name'];?></label>
			</div>
			
			<div class='form-group'>
				<label class="control-label col-md-10" for="duration"><?php echo __("Program Duration(Days) : ");?> <?php echo $program_data['duration'];?></label>
			</div>
		</div>
		<hr>
		
		<div class="box-body">
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Workout Day");?></th>
					<th><?php echo __("Workout Sequence ");?></th>
					<th><?php echo __("Workout Category");?></th>
					<th><?php echo __("Workout Exercise");?></th>
					<th><?php echo __("Workout Level");?></th>
					<th><?php echo __("Workout mod");?></th>
					<th><?php echo __("Weight");?></th>
					<th><?php echo __("Sets");?></th>
					<th><?php echo __("Reps");?></th>
					<th><?php echo __("Ret Time");?></th>
					<th><?php echo __("Workout Video");?></th>
					<th><?php echo __("Workout Instructions");?></th>
					<?php 
						if($session["role_name"] != "staff_member" ) { 
						//echo "<th>".__("Program Trainer")."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($workouts as $row) {
					echo "<tr><td>{$row['day']}</td>";				
					echo "<td>{$row['seq']}</td>";
					echo "<td>{$row['gym_category']['name']} </td>";
					echo "<td>{$row['gym_exercise']['name']} </td>";
					echo "<td>{$row['gym_level']['level']} </td>";
					echo "<td>{$row['gym_mod']['name']} </td>";
					echo "<td>{$row['weight']} </td>";
					echo "<td>{$row['sets']} </td>";
					echo "<td>{$row['[reps']} </td>";
					echo "<td>{$row['rest_time']} </td>";
					echo "<td><a target='_blank' href='{$row['wrkout_video']}' >Watch Video</a> </td>";
					echo "<td>{$row['instructions']} </td>";

					if($session["role_name"] != "staff_member" ) {
						 //echo "<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>";
					}
					echo "</tr>";
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Workout Day");?></th>
					<th><?php echo __("Workout Sequence ");?></th>
					<th><?php echo __("Workout Category");?></th>
					<th><?php echo __("Workout Exercise");?></th>
					<th><?php echo __("Workout Level");?></th>
					<th><?php echo __("Workout mod");?></th>
					<th><?php echo __("Weight");?></th>
					<th><?php echo __("Sets");?></th>
					<th><?php echo __("Reps");?></th>
					<th><?php echo __("Ret Time");?></th>
					<th><?php echo __("Workout Video");?></th>
					<th><?php echo __("Workout Instructions");?></th>
					<?php if($session["role_name"] != "staff_member" ) { 
						//echo "<th>".__("Program Trainer")."</th>";
					} ?>						
				</tr>
			</tfoot>
			</table>
		</div>		
		
	</div>
</section>