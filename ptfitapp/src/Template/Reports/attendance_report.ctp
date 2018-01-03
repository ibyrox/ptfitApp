<script type="text/javascript">
			$(document).ready(function() {
				$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
				$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 
			} );
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bar-chart"></i>
				<?php echo __("Attendance Report");?>
				<small><?php echo __("Reports");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("Reports","membershipReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Membership Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","membershipStatusReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-pie-chart"></i> <?php echo __("Membership Status Report");?></a>
				&nbsp;
				<a href="<?php echo $this->Gym->createurl("Reports","paymentReport");?>" class="btn btn-flat btn-custom"><i class="fa fa-bar-chart"></i> <?php echo __("Payment Report");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<form method="post">  
			<div class="form-group col-md-3">
				<label for="exam_id"><?php echo __('Strat Date');?></label>
			   
							
						<input type="text"  class="form-control sdate" name="sdate" 
						value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else /* echo getdate_in_input_box(date('Y-m-d')); */?>">
						
			</div>
			<div class="form-group col-md-3">
				<label for="exam_id"><?php echo __('End Date');?></label>
					<input type="text"  class="form-control edate" name="edate" 
					value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else/*  echo getdate_in_input_box(date('Y-m-d')); */?>">
						
			</div>
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" name="attendance_report" Value="<?php echo __('Go');?>"  class="btn btn-flat btn-success"/>
			</div>   	
    	</form>
		<?php
		if(isset($_REQUEST['attendance_report']))
		{
			$options = Array(
				'title' => __('Member Attendance Report','gym_mgt'),
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
					
				'hAxis' => Array(
						'title' =>  __('Class','gym_mgt'),
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 10),
						'maxAlternation' => 2
				),
				'vAxis' => Array(
						'title' =>  __('No of Member','gym_mgt'),
						'minValue' => 0,
						'maxValue' => 5,
						'format' => '#',
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 12)
				),
				'colors' => array('#22BAA0','#f25656')
			);
			$GoogleCharts = new GoogleCharts;
			if(isset($report_2) && count($report_2) >0)
			{
				$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    		?>
				<div id="chart_div" style="width: 100%; height: 500px;margin-top: 100px;"></div>
				  
				  <!-- Javascript --> 
				  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
				  <script type="text/javascript">
						<?php echo $chart;?>
				</script>
		<?php }
 if(isset($report_2) && empty($report_2)) {?>
  
  <div class="clear col-md-12">
  <i>
  <?php echo __("There is not enough data to generate report.");?>
  </i>
  </div>
  <?php }
			
		}
		
	?>	
		
		
		<!-- END -->
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>