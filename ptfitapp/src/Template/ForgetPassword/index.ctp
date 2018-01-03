<?php
echo $this->Html->script('jQuery/jQuery-2.1.4.min.js');
echo $this->Html->script('jquery-ui.min');
echo $this->Html->css('bootstrap.min');

$is_rtl = $this->Gym->getSettings("enable_rtl");
if($is_rtl)
{
	echo $this->Html->css('bootstrap-rtl.min');
}
echo $this->Html->script('bootstrap/js/bootstrap.min.js');
echo $this->Html->css('plugins/datepicker/datepicker3');
echo $this->Html->script('datepicker/bootstrap-datepicker.js');
$dtp_lang = $this->gym->getSettings("datepicker_lang");
echo $this->Html->script("datepicker/locales/bootstrap-datepicker.{$dtp_lang}");
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
echo $this->Html->css('validationEngine/validationEngine.jquery');
echo $this->Html->script('validationEngine/languages/jquery.validationEngine-en');
echo $this->Html->script('validationEngine/jquery.validationEngine'); 
?>
<style>
.content{   
   padding-bottom: 0;
}

body *{
	    font-family: "Roboto", sans-serif;
}
.datepicker.dropdown-menu {   
    max-width: 300px;
}
.form-control {
    height: 34px !important;
	font-size: 14px !important;
}
#form-head{
	color : #eee;
}
</style>
<script type="text/javascript">
$(document).ready(function() {	
$(".validateForm").validationEngine();
	$('.group_list').multiselect({
		includeSelectAllOption: true	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$('.class_list').multiselect({
		includeSelectAllOption: true	
	});
	
	$(".datepick").datepicker({format: 'yyyy-mm-dd',"language" : "<?php echo $dtp_lang;?>"});
		
	$(".content-wrapper").css("height","2600px");
	
	$(".mem_valid_from").datepicker({format: 'yyyy-mm-dd'}).on("changeDate",function(ev){
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				var membership = $(".membership_id option:selected").val();		
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculatind date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response)
									{
										// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
										$(".valid_to").val(response);
										// alert(response);
										// console.log(response);
									},
							error: function(e){
									console.log(e.responseText);
							}
						});
				}else{
					$(".valid_to").val("Select Membership");
				}
			});	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<i class="fa fa-user"></i>
				<?php echo __("Forget Password");?>
			  </h3>			  
			</section>
		</div>
		<div class="panel">
		<?php				
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<br>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Registered Email Id").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email_id","class"=>"form-control validate[required]","value"=>(($edit)?$data['email_id']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "</fieldset>";

							
			echo "<br>";
			echo '<div class="form-group">';
			echo '<div class="col-md-4 col-sm-6 col-xs-6">';
			echo $this->Form->button(__("Submit"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"reset_request"]);
			echo "</div>";
			echo '<div class="col-md-5 col-sm-6 col-xs-6 pull-right">';
			echo "<a href='".$this->request->base ."/Users/' class='btn btn-success'>".__('Go Back')."</a>";
			echo '</div>';
			echo '</div>';
			echo $this->Form->end();
		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/MemberRegistration/getMembershipEndDate/" id="mem_date_check_path">
		
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