<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
<script type="text/javascript"> 
function js_default_password()
{
	if(document.getElementById('default_password').checked) {
    var input = '123456';
	$('#password').val(input);
	
	} else {
		var clear;
		$("#password").val(clear);
		//alert("clear");
	}
}


function js_default_email()
{
	if(document.getElementById('default_email').checked) {
    var input = $('#username');
	var email=input.val()+'@domain.com';
	$('#email').val(email);
	
	} else {
		var clear;
		$("#email").val(clear);
		//alert("clear");
	}
}


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
			
			//$( "span#registration_no_part1" ).text()=dtid;			
			$("#registration_no_part1").html(dtid);
			//document.getElementById('registration_no_part1').value = dtid;
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
			//document.getElementById('registration_no_part1').value = dtid;
			//var box = $("#registration_no_part1");
   			//box.val(box.val()+upid);
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
			//document.getElementById('registration_no_part1').value = dtid;
			//var box = $("#registration_no_part1");
			//box.val(box.val()+upid);
			//var box = $("#registration_no_part1");
			//box.val(box.val()+unid);
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
  <li class="active"><?=lang('menu_create_distributors')?></li>
</ol>

<div style="overflow:hidden">

   
<div class="col-md-12">
	<form class="form-horizontal" role="form" id="create-distributor-form"  name="create-distributor-form" action="" method="post" enctype="multipart/form-data">
   <fieldset>
    <legend class="text-center"><?=lang('menu_create_distributors')?> </legend>
                		                        
			
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
            
            <!-- Distributor username input-->            
            <div class="form-group <?php echo (form_error('username')) ? 'has-error' : ''; ?>">            	
              <label class="col-md-3 control-label" for="username"><?=lang('username')?> *</label>
              <div class="col-md-6">              	 
                <input id="username" name="username" type="text" placeholder="<?=lang('username')?>" class="form-control" value="<?=$distributor_code?>" readonly>
                <?php if (form_error('username')) :?>                                                  
                    <?php echo form_error('username', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
            
            <!-- Distributor password input-->            
            <div class="form-group <?php echo (form_error('password')) ? 'has-error' : ''; ?>">            	
              <label class="col-md-3 control-label" for="password"><?=lang('password')?> *</label>
              <div class="col-md-6">              	 
                <input id="password" name="password" type="password" placeholder="<?=lang('password')?>" class="form-control">
                
                <?php if (form_error('password')) :?>                                                  
                    <?php echo form_error('password', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
              <div class="col-md-3">
              <label><input name="default_password" id="default_password" type="checkbox" value="1" onClick="js_default_password()"> Default Password (123456)</label>
              </div>
            </div>
            
            <!-- Distributor email input-->            
            <div class="form-group <?php echo (form_error('email')) ? 'has-error' : ''; ?>">            	
              <label class="col-md-3 control-label" for="email"><?=lang('email')?> *</label>
              <div class="col-md-6">              	 
                <input id="email" name="email" type="text" placeholder="<?=lang('email')?>" class="form-control" value="<?php if(isset($email)) {echo $email;} ?>">
                <?php if (form_error('email')) :?>                                                  
                    <?php echo form_error('email', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
              <div class="col-md-3">
              <label><input name="default_email" id="default_email" type="checkbox" value="1" onClick="js_default_email()"> Default Email</label>
              </div>
            </div>
            
            	
            <!-- Distributor Name input-->            
            <div class="form-group <?php echo (form_error('distributor_name')) ? 'has-error' : ''; ?>">            	
              <label class="col-md-3 control-label" for="distributor_name"><?=lang('distributor_name')?> *</label>
              <div class="col-md-6">              	 
                <input id="distributor_name" name="distributor_name" type="text" value="<?php if(isset($distributor_name)) {echo $distributor_name;} ?>" placeholder="<?=lang('distributor_name')?>" class="form-control">
                <?php if (form_error('distributor_name')) :?>                                                  
                    <?php echo form_error('distributor_name', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
    
            <!-- Distributor code input-->
            <div class="form-group <?php echo (form_error('distributor_code')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="distributor_code"><?=lang('distributor_code')?> *</label>
              <div class="col-md-6">
                <input id="distributor_code" name="distributor_code" type="text" value="<?php if(isset($distributor_code)) {echo $distributor_code;} ?>" placeholder="<?=lang('distributor_code')?>" class="form-control" readonly>
                <?php if (form_error('distributor_code')) :?>                                                  
                    <?php echo form_error('distributor_code', '<p class="text-danger">', '</p>'); ?>
                <?php endif; ?>
              </div>
            </div>
    		
            <!-- Site Latitude input-->
            <div class="form-group <?php echo (form_error('distributor_mobile')) ? 'has-error' : ''; ?>">
              <label class="col-md-3 control-label" for="distributor_mobile"><?=lang('distributor_mobile')?> *</label>
              <div class="col-md-6">
                <input id="distributor_mobile" name="distributor_mobile" value="<?php if(isset($distributor_mobile)) {echo $distributor_mobile;} ?>" type="text" placeholder="01XXXXXXXXX" class="form-control">
                <?php if (form_error('distributor_mobile')) :?>                                                  
                    <?php echo form_error('distributor_mobile', '<p class="text-danger">', '</p>'); ?>
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
            		<option value="<?php echo $division->division; ?>"><?php echo $division->loc_name_en?></option>
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
                <select name="site_district" id="site_district" class="form-control">
          			<option value=""><?php echo '--'.lang('select').'--'; ?></option>                            
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
                <select name="site_upazila" id="site_upazila" class="form-control">
          	<option value=""><?php echo '--'.lang('select').'--'; ?></option>                            
        </select>
              </div>
            </div>
            
            <!-- Site union input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="site_union"><?=lang('union')?> </label>
              <div class="col-md-6">
                <select name="site_union" id="site_union" class="form-control">
          	<option value=""><?php echo '--'.lang('select').'--'; ?></option>                            
        </select>
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
