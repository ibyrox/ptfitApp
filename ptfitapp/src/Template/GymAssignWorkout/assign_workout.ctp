<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function() {
$(".mem_list_workout").select2();
$(".date").datepicker();
var box_height = $(".box").height();
var box_height = box_height + 100 ;
$(".content-wrapper").css("height",box_height+"px");

/* FETCH Activity On Page Load */

	var member_id = $(".mem_list_workout option:selected").val()	
	var ajaxurl = $("#getcategory").attr("data-url");
	var curr_data = {member_id:member_id};
	$.ajax({
		url : ajaxurl,
		type : "POST",
		data : curr_data,
		success : function(result)
		{
			$("#append").html("");			
			$("#append").append(result);			
		},
		error : function(e)
		{
			console.log(e.responseText);
		}
	});
	
/* FETCH Activity On Page Load */


});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-hand-grab-o"></i>
				<?php echo $title;?>
				<small><?php echo __("Assign Workout");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymAssignWorkout","workoutLog");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Workout Logs");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			echo $this->Form->create("assignWorkout",["class"=>"validateForm form-horizontal","role"=>"form"]);
		?>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Select Client");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("user_id",$members,["default"=>($edit)?$this->request->params["pass"]:"","class"=>"mem_list_workout"]);
				?>
			<input type="hidden" id="getcategory" data-url="<?php echo $this->request->base;?>/GymAjax/getCategoriesByMember" >
			</div>
			<!--<div class="col-md-3">
				<a href="<?php echo $this->request->base;?>/GymMember/addMember" class="btn btn-default btn-flat"><?php echo __("Add Client");?></a>
			</div> iby--> 
		</div>		
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Level");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->select("level_id",$levels,["empty"=>__("Select Level"),"class"=>"form-control level_list validate[required]"]);
				?>
			</div>
			<div class="col-md-3">
				<a href="javascrip:void(0)" class="btn btn-default btn-flat level-list" data-url="<?php echo $this->request->base;?>/GymAjax/levelsList"><?php echo __("Add Level");?></a>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-md-3 control-label" for="description"><?php echo __('Description');?></label>
			<div class="col-md-8">
				<textarea id="description" class="form-control" name="description"></textarea>
			</div>
		</div>	
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Start Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"start_date","class"=>"date validate[required] form-control"]);
				?>
			</div>	
		</div>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("End Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"end_date","class"=>"date validate[required] form-control"]);
				?>
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-1 control-label"></label>
			<div class="col-sm-10 border">
				<br>
				<div class="col-md-3">
					<label class="list-group-item bg-default"><?php echo __("Select Days");?></label>
					<?php foreach ($this->Gym->days_array() as $key=>$name){?>
					<div class="checkbox">
					<label><input type="checkbox" value="" name="day[]" value="<?php echo $key;?>" id="<?php echo $key;?>" data-val="day"><?php echo __($name); ?> </label>
					</div>
					<?php }?>
				</div>
				<div class="col-md-8 activity_list">
				<label class="col-md-8 list-group-item bg-default"><?php echo __("Select workout activity to add on selected days");?></label>
			<!-- <input type="button" value="<?php // echo __('Step-1 Add Workout');?>" name="sadd_workouttype" id="add_workouttype" class="pull-right btn btn-flat btn-info"/> -->
				<div class="clearfix"></div>
				<div id="append">			
				</div>
				<br>
				<input type="button" value="<?php echo __('Step-1 Add Workout');?>" name="sadd_workouttype" id="add_workouttype" class="pull-left btn btn-flat btn-info"/>
				</div>
			</div>
		</div>	
		<div class="col-sm-offset-2 col-sm-8">
			<div class="form-group">
				<div class="col-md-8">
				<!--	<input type="button" value="<?php //echo __('Step-1 Add Workout');?>" name="sadd_workouttype" id="add_workouttype" class="btn btn-success"/> -->
				</div>
			</div>
		</div>
		<div id="display_rout_list"></div>		
		<br><br>
		<div class="col-md-offset-2 col-sm-8 schedule-save-button">
        	
        	<input type="submit" value="<?php if($edit){ echo __('Step-2 Save Workout'); }else{ echo __('Save Workout');}?>" name="save_workouttype" class="btn btn-flat btn-success"/>
        </div>
		<input type="hidden" id="add_workout_url" value="<?php echo $this->request->base;?>/GymAjax/gmgt_add_workout">
		<div class='clear'>
		<br><br>
		<?php 
		$this->Form->end();
		
		if($edit)
		{
			foreach($work_outdata as $data=>$row)
			{				
				foreach($row as $r)
				{
					if(is_array($r))
					{
						$days_array[$data]["start_date"] = $row["start_date"];
						$days_array[$data]["end_date"] = $row["end_date"];
						$day = $r["day_name"];
						$days_array[$data][$day][] = $r;
					}
				}					
			}
			
			
			foreach($days_array as $data=>$row)
			{?>
				<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
				  <div class="panel-heading">
					<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))."</span> ".__("TO")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["end_date"]))."</span>";?>
					<span class="del_panel" del_id="<?php echo $data;?>" data-url="<?php echo $this->request->base;?>/GymAjax/deleteWorkoutData/<?php echo $data;?>"><i class='fa fa-times-circle' aria-hidden="true"></i></span>
				  </div>
				  <br>
				<div class="work_out_datalist_header">
					<div class="col-md-2 col-sm-2">  
						<strong><?php echo __("Day name");?></strong>
					</div>
					<div class="col-md-10 col-sm-10 hidden-xs">
						<span class="col-md-3"><?php echo __("Activity");?></span>
						<span class="col-md-3"><?php echo __("Sets");?></span>
						<span class="col-md-2"><?php echo __("Reps");?></span>
						<span class="col-md-2"><?php echo __("KG");?></span>
						<span class="col-md-2"><?php echo __("Rest Time");?></span>
					</div>
				</div>				
				<?php 
				foreach($row as $day=>$value)
				{
					if(is_array($value))
					{ 
					?>
						<div class="work_out_datalist">
						<div class="col-md-2 day_name"><?php echo __($day);?></div>
						<div class="col-md-10 col-xs-12">
						<?php foreach($value as $r)
							{?>
							<div class="col-md-12">
							<span class="col-md-3 col-sm-3 col-xs-12"><?php echo $this->Gym->get_activity_by_id($r["workout_name"]);?></span>   
							<span class="col-md-3 col-sm-3 col-xs-6"><?php echo $r["sets"];?></span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["reps"];?> </span>
							<span class="col-md-2 col-sm-2 col-xs-6"><?php echo $r["kg"];?> </span>
							<span class="col-md-2 col-sm-2  col-xs-6"><?php echo $r["time"];?> </span>
							</div>
						<?php } ?>
						</div>
						</div>
					<?php } 
				}?>				
				</div>
	  <?php } 
		}?>		
		<br><br>
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>
jQuery("body").on("click", "#add_workouttype", function(event){
		 var count = $("#display_rout_list div").length;		
		
		 var day = '';
		 var activity = '';
		 var check_val = '';
		 jsonObj1 = [];
		 jsonObj2 = [];
		 jsonObj = [];
		
		 $(":checkbox:checked").each(function(o){
			
			  var chkID = $(this).attr("id");
			  var check_val = $(this).attr("data-val");
			  
			  if(check_val == 'day')
			  {
				  //day += ' ' + chkID;
				  day += add_day(chkID,chkID);
				  item = {}
			        item ["day_name"] =chkID;
			       
			        jsonObj1.push(item);
			        //$(this).prop("disabled", true);
			  }
			  if(check_val == 'activity')
			  {
				  activity_name = $(this).attr("activity_title");
				  item = {};
			        item ["activity"] = {"activity":activity_name,"sets":$("#sets_"+chkID).val(),"reps":$("#reps_"+chkID).val(),"kg":$("#time_"+chkID).val(),"time":$("#time_"+chkID).val()};
				  activity += add_activity(activity_name,chkID);
				 
			       
			        jsonObj2.push(item);
			  }
			  $(this).prop('checked', false);
			 
			 // $("#"+chkID+"summ").removeAttr("disabled");
			  /* ... */
			  jsonObj = {"days":jsonObj1,"activity":jsonObj2};
			});
		var ajaxurl = $("#add_workout_url").val();
		 var curr_data = {					
						data_array: jsonObj											
						};
		$.ajax({
			url:ajaxurl,
			type:"POST",
			data:curr_data,
			success:function(response){
						var list_workout =  workout_list(day,activity,count,response);						 
						$("#display_rout_list").append(list_workout);
						return false;
					}
		});
		return false;					
		var list_workout =  workout_list(day,activity);
		 $("#display_rout_list").append(list_workout);
	}); 
	
function workout_list(day,activity,id,response)
{
	var string = '';
	string += "<div class='activity border' id='block_"+id+"'>";
	string += '<div class="col-md-4">'+day+'</div>';
	string += '<div class="col-md-6">'+activity +'</div>';
	string += '<span>'+ response+'</span>';
	string += "<div id='"+id+"' class='removethis col-md-2'><span did='"+id+"' class='badge badge-delete pull-right del_box'>X</span></div></div>";
	return string;
}
function add_day(day,id)
 {
	var string = '';
	string = '<span id="'+id+'">'+day+'</span>, ';
	string += '<input type="hidden" name="day[day]['+day+']" value="'+day+'">';
	return string;
 }
 
function add_activity(activity,id)
{
	var string = '';
	var sets = '';
	var reps = '';
	sets = $("#sets_"+id).val();
	reps = $("#reps_"+id).val();
	kg = $("#kg_"+id).val();
	time = $("#time_"+id).val();
	string += '<p id="'+id+'"><strong>'+activity+' </strong>: ';
	string += '<span id="sets_'+id+'"> Sets '+sets+', </span>';
	string += '<span id="reps_'+id+'"> Reps '+reps+', </span>';
	string += '<span id="kg_'+id+'"> KG '+kg+', </span>';
	string += '<span id="time_'+id+'"> Rest Time '+time+', </span></p>';
	string += '<input type="hidden" name="sets[]" value="'+sets+'">';
	string += '<input type="hidden" name="reps[]" value="'+reps+'">';
	string += '<input type="hidden" name="kg[]" value="'+kg+'">';
	string += '<input type="hidden" name="time[]" value="'+time+'">';
	string += '<input type="hidden" name="activity[]" value="'+activity+'">';
	sets = $("#sets_"+id).val('');
	reps = $("#reps_"+id).val('');
	kg = $("#kg_"+id).val('');
	time = $("#time_"+id).val('');
	return string;
}

/* $(".activity_check").change(function(){ */
$("body").on("change",".activity_check",function(){
			
			//id = $(this).attr('id');
			//alert("Hello" + id);
			
			//$("#reps_sets_"+id).html('<P>Sets <input type="text" name = "sets_' + id + '"></p><P>Reps <input type="text" name = "reps_' + id + '"></p>');
			
			
		 if($(this).is(":checked"))
		{
			 //alert("chekked");
			 //$('#hmsg_message_sent').addClass('hmsg_message_block');
			 id = $(this).attr('id');
				//alert("Hello" + id);
			 string = '';
			
			string += '<div class="achilactiveadd"><span class="label"> Sets </span><input type="text" name = "sets_' + id + '" id = "sets_' + id + '" placeholder="Sets"></div>';
			string += '<div class="achilactiveadd"><span class="label"> Reps</span> <input type="text" name = "reps_' + id + '" id = "reps_' + id + '" placeholder="Reps"></div>';
			string += '<div class="achilactiveadd"><span class="label"> KG </span><input type="text" name = "kg_' + id + '" id = "kg_' + id + '" placeholder="KG"></div>';
			string += '<div class="achilactiveadd"><span class="label">Rest Time </span><input type="text" name = "time_' + id + '" id = "time_' + id + '" placeholder="Min"></div>';
			
				$("#reps_sets_"+id).html(string);
			 
		}
		 else
		{
			// $('#hmsg_message_sent').addClass('hmsg_message_none');
			// $('#hmsg_message_sent').removeClass('hmsg_message_block');
			 id = $(this).attr('id');
				//alert("Hello" + id);
				
				$("#reps_sets_"+id).html('');
		}
	 });

$("body").on("click",".badge-delete",function(){		
	var remove = $(this).attr("did");
	$("#block_"+remove).remove(); 
});
</script>