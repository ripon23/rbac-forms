<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => 'aponjon')); ?>
<div class="row">
    <legend class="text-center"><?php echo lang('menu_create_org')?></legend>   	
    <div class="col-md-2">
    	
    </div>
    
    <div class="col-md-8">
    	
		
		<?php if (isset($error_msg)):?>
        <div class="alert alert-warning">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="glyphicon glyphicon-alert"></span><strong>  <?php echo "".$error_msg?> </strong>
        </div>
        <?php endif;?>
        
    	<?php if (isset($success_msg)):?>
        <div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong><span class="glyphicon glyphicon-saved"></span> <?php echo "".$success_msg?> </strong>
        </div>                	  
        <?php endif;?>                            
            
            
        
        <form role="form"  id="card-form"  name="card-form" action="" method="post" >      	
      	
        <div class="well well-sm"><strong><span class="pull-left" style=" color:red; font-size:22px; margin-right:10px;">*</span> <?php echo lang('required_field')?> </strong></div>
      
      
      <div class="form-group">
        <label for="card_type"><?php echo lang('org_name')?></label>
        <div class="input-group">
		<input id="org_name" name="org_name" type="text" value="<?php echo isset($org_name)?$org_name:'';?>" class="form-control">
          <span class="input-group-addon"><i class="glyphicon form-control-feedback"><span style=" color:red; font-size:9px;">*</span></i></span></div>
          <?php if(form_error('org_name')):?>
          <span class="text-danger"><?php echo form_error('org_name');?></span>
          <?php endif?>
      </div>
      
      
      <div class="form-group">
        <label for="number-of-card-to-be-generate"><?php echo lang('org_type')?></label>
        <div class="input-group">
        <input id="org_type" name="org_type" type="text" value="<?php echo isset($org_type)?$org_type:'';?>" class="form-control">  
          <span class="input-group-addon"><i class="glyphicon form-control-feedback"><span style=" color:red; font-size:9px;">*</span></i></span></div>
          <?php if(form_error('org_type')):?>
          <span class="text-danger"><?php echo form_error('org_type');?></span>
          <?php endif?>
      </div>
                          
    	
        <div class="form-group">
        <label for="address"><?php echo lang('address')?></label>
        <div class="input-group">
        <textarea class="form-control" id="address" name="address" placeholder="<?=lang('address')?>" rows="5"><?=set_value('address')?></textarea>
        <span class="input-group-addon"></span></div>
          <?php if(form_error('address')):?>
          <span class="text-danger"><?php echo form_error('address');?></span>
          <?php endif?>
      </div>
      
      <div class="form-group">
        <label for="color"><?php echo lang('color')?></label>
        <div class="input-group">
        <input id="color" name="color" type="text" value="<?php echo isset($color)?$color:'';?>" class="form-control">  
          <span class="input-group-addon"><span style=" color:#000; font-size:9px;">Hex Code</span></span></div>
          <?php if(form_error('color')):?>
          <span class="text-danger"><?php echo form_error('color');?></span>
          <?php endif?>
      </div>
      
      <div class="form-group">
        <label for="address"><?php echo lang('logo')?></label>
        <div class="input-group">
      	<input type="file" class="input-large" name="org_logo" /> <small>Maximum Width x Height =400 x 500 Pixel</small>
      	</div>       
      </div>
      
      <button type="submit" name="submit" class="btn btn-primary btn-warning"><span class="glyphicon glyphicon-send"></span> <?php echo lang('action_create')?></button>
      
	</form>
    <br/>
    </div>


    <div class="col-md-2">
    	
    </div>
</div>	

<?php echo $this->load->view('footer'); ?>
