<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                 
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
				<?php echo __("Mod List");?>
				<small>&nbsp;<?php echo __("Mod");?></small>
			  </h1>
			  <?php if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant") { ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMod","addMod");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Mod");?></a>
			  </ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Mod Name");?></th>
					<?php 
						if($session["role_name"] != "staff_member" ) { 
						echo "<th>".__("Trainer")."</th>";
					}
					?>						
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($data as $row) {
					echo "<tr><td>{$row['name']}</td>";				
					if($session["role_name"] != "staff_member" ) {
						 //echo "<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>";
					}
					echo  "<td>
								<a href='{$this->request->base}/GymMod/editMod/{$row['id']}' title='Edit' class='btn btn-flat btn-primary'><i class='fa fa-edit'></i></a>
								<a href='{$this->request->base}/GymMod/deleteMod/{$row['id']}' title='Delete' class='btn btn-flat btn-danger' onClick=\"return confirm('Are you sure,You want to delete this record?');\"><i class='fa fa-trash-o'></i></a>						
							</td>					
					</tr>";
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Category Name");?></th>
					<?php if($session["role_name"] != "staff_member" ) { 
						echo "<th>".__("Trainer")."</th>";
					} ?>						
					<th><?php echo __("Action");?></th>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</section>