<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
<script type="text/javascript"> 
    jQuery(document).ready(function(){			
	
	//Start
	$("#site_division").change(function()
	{
	var dvid=$(this).val();
	var ltype='DT';
	var dataString = 'dvid='+ dvid+'&ltype='+ltype;
	
	$.ajax
		({
			type: "POST",
			url: "distributors/distributors/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_district").html(html);	
			}
		});
	
	});
	//End
	
	//Start
	$("#site_district").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$(this).val();
	var ltype='UP';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&ltype='+ltype;	
	
	$.ajax
		({
			type: "POST",
			url: "distributors/distributors/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_upazila").html(html);					
			$("#registration_no_part1").html(dtid);
			}
		});		
		
	
	});
	//End
	
	//Start
	$("#site_upazila").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$(this).val();
	var ltype='UN';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&ltype='+ltype;			
	
	$.ajax
		({
			type: "POST",
			url: "distributors/distributors/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_union").html(html);
			$("#registration_no_part1").html(dtid);
			$("#registration_no_part1_1").html(upid);
			}
		});
		
	
	});
	//End
	
	//Start
	$("#site_union").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$("#site_upazila").val();
	var unid=$(this).val();
	var ltype='MA';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&unid='+unid+'&ltype='+ltype;				
	$.ajax
		({
			type: "POST",
			url: "distributors/distributors/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_mouza").html(html);
			$("#registration_no_part1").html(dtid);
			$("#registration_no_part1_1").html(upid);
			$("#registration_no_part1_2").html(unid);
			}
		});
	
	
	});
	//End
});
</script>	
</head>
<body>
<?php echo $this->load->view('header'); ?>


<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('action_edit')?> <?=lang('dealer')?></li>
</ol>

<div style="overflow:hidden">

   
<div class="col-md-12">
	<form class="form-horizontal" role="form" id="create-dealer-form"  name="create-dealer-form" action="" method="post" enctype="multipart/form-data">
   <fieldset>
    <legend class="text-center"><?=lang('action_edit')?> <?=lang('dealer')?> </legend>
                		                        
			
			<?php 
			if(isset($success_msg))
			{					 
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<?=$success_msg?>
			</div>
			<?php
			}
			?>
			
            <?php 
			if(isset($error_msg))
			{					 
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<?=$error_msg?>
			</div>
			<?php
			}
			?>                                    	            
    
            <!-- dealer code input-->
            <div class="form-group <?php echo (form_error('dealer_code')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="dealer_code"><?=lang('dealer_code')?> *</label>
              <div class="col-md-6">
                <input id="dealer_code" name="dealer_code" type="text" value="<?php echo $dealer_info->dealer_code; ?>" placeholder="<?=lang('dealer_code')?>" class="form-control" readonly>
                <?php if (form_error('dealer_code')) :?>                                                  
                    <?php echo form_error('dealer_code', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
    		
            <!-- dealer Name input-->            
            <div class="form-group <?php echo (form_error('dealer_name')) ? 'has-error' : ''; ?>">            	
              <label class="col-md-3 control-label" for="dealer_name"><?=lang('dealer_name')?> *</label>
              <div class="col-md-6">              	 
                <input id="dealer_name" name="dealer_name" type="text" value="<?php echo $dealer_info->dealer_name; ?>" placeholder="<?=lang('dealer_name')?>" class="form-control">
                <?php if (form_error('dealer_name')) :?>                                                  
                    <?php echo form_error('dealer_name', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
            
            <!-- dealer Mobile input-->
            <div class="form-group <?php echo (form_error('dealer_mobile')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="dealer_mobile"><?=lang('dealer_mobile')?> *</label>
              <div class="col-md-6">
                <input id="dealer_mobile" name="dealer_mobile" value="<?php echo $dealer_info->dealer_mobile; ?>" type="text" placeholder="01XXXXXXXXX" class="form-control">
                <?php if (form_error('dealer_mobile')) :?>                                                  
                    <?php echo form_error('dealer_mobile', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>                                   
            
            
            <!-- Site division input-->
            <div class="form-group <?php echo (form_error('site_division')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="site_division"><?=lang('division')?> *</label>
              <div class="col-md-6">
                <select name="site_division" id="site_division" class="form-control">
          			<option value=""><?php echo '--'.lang('select').'--'; ?></option>            
                	<?php foreach ($all_division as $division) : ?>
            		<option value="<?php echo $division->division; ?>" <?php if($division->division==$dealer_info->dealer_division) echo ' selected="selected"'; ?>><?php echo $division->loc_name_en?></option>
					<?php endforeach; ?>
       			</select>
                <?php if (form_error('site_division')) :?>                                                  
                    <?php echo form_error('site_division', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
            
            
            <!-- Site district input-->
            <div class="form-group <?php echo (form_error('site_district')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="site_district"><?=lang('district')?> *</label>
              <div class="col-md-6">
               <?php 
				if($dealer_info->dealer_division)
				$district_list=$this->ref_location_model->get_location_list_by_id($dealer_info->dealer_division,NULL,NULL,NULL,NULL,NULL,'DT'); ?>
            <select name="site_district" id="site_district" class="form-control">
            	<option value=""><?php echo '--'.lang('select').'--'; ?></option>
          		<?php 
				if($dealer_info->dealer_division)
				{
				foreach ($district_list as $district) : ?>
            	<option value="<?php echo $district->district; ?>" <?php if($district->district==$dealer_info->dealer_district) echo ' selected="selected"'; ?>><?php echo $district->loc_name_en?></option>
				<?php endforeach; 
				}
				?>
        	</select>
                <?php if (form_error('site_district')) :?>                                                  
                    <?php echo form_error('site_district', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
            
            
            <!-- Site upazila input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="site_upazila"><?=lang('upazila')?> </label>
              <div class="col-md-6">
                <?php 
				if($dealer_info->dealer_district)
				$upazila_list=$this->ref_location_model->get_location_list_by_id($dealer_info->dealer_division,$dealer_info->dealer_district,NULL,NULL,NULL,NULL,'UP'); ?>
				<select name="site_upazila" id="site_upazila" class="form-control">
                	<option value=""><?php echo '--'.lang('select').'--'; ?></option>
					<?php foreach ($upazila_list as $upazila) : ?>
					<option value="<?php echo $upazila->upazila; ?>" <?php if($upazila->upazila==$dealer_info->dealer_upazila) echo ' selected="selected"'; ?>><?php echo $upazila->loc_name_en?></option>
					<?php endforeach; ?>                             
				</select>
              </div>
            </div>
            
            <!-- Site union input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="site_union"><?=lang('union')?> </label>
              <div class="col-md-6">
                <?php 
				if($dealer_info->dealer_upazila)
				$union_list=$this->ref_location_model->get_location_list_by_id($dealer_info->dealer_division,$dealer_info->dealer_district,$dealer_info->dealer_upazila,NULL,NULL,NULL,'UN'); ?>
				<select name="site_union" id="site_union" class="form-control">
					<option value=""><?php echo '--'.lang('select').'--'; ?></option>
					<?php foreach ($union_list as $union) : ?>
					<option value="<?php echo $union->unionid; ?>" <?php if($union->unionid==$dealer_info->dealer_union) echo ' selected="selected"'; ?>><?php echo $union->loc_name_en?></option>
					<?php endforeach; ?>                           
				</select>
              </div>
            </div>
            
           <!-- Site active_status input-->
            <div class="form-group <?php echo (form_error('active_status')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="active_status"><?=lang('status')?> *</label>
              <div class="col-md-6">
                <select name="active_status" id="active_status" class="form-control">
          			<option value=""><?php echo '--'.lang('select').'--'; ?></option>            
            		<option value="active" <?php if($dealer_info->active_status==1) echo ' selected="selected"'; ?>><?php echo lang('active')?></option>
                    <option value="inactive" <?php if($dealer_info->active_status==0) echo ' selected="selected"'; ?>><?php echo lang('inactive')?></option>						
       			</select>
                <?php if (form_error('active_status')) :?>                                                  
                    <?php echo form_error('active_status', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
                         
            <!-- Form actions -->
            <div class="form-group">
                <label class="col-md-3 control-label" for="current_site"></label>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary "><?=lang('action_save')?></button>
              </div>
            </div>
            
          </fieldset>
    </form>
</div> <!-- col-md-5 -->





</div>


<?php echo $this->load->view('footer'); ?>
