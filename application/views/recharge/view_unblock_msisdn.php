<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => 'aponjon')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_unblock_msisdn')?></li>
</ol>

<legend class="text-center"><?=lang('menu_unblock_msisdn')?> </legend>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">    	        		
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
      	
      	<div class="form-group">
        <label for="card_type"><?php echo lang('mobile_no')?></label>
            <div class="input-group">
            <input type="text" name="mobile_no" id="mobile_no" placeholder="01XXXXXXXXX" value="<?php echo isset($moblie)?$moblie:'';?>" class="form-control">
            <span class="input-group-addon"><i class="glyphicon form-control-feedback">
            <span style=" color:red; font-size:9px;">*</span></i></span>
            </div>
          <?php if(form_error('mobile_no')):?>
          <span class="text-danger"><?php echo form_error('mobile_no');?></span>
          <?php endif?>
      	</div>
      
      
      <div class="form-group">
        <label for="number-of-card-to-be-generate"><?php echo lang('unblock_reason')?></label>
        <div class="input-group">
          <input type="text" name="unblock_reason" id="unblock_reason" value="<?php echo isset($unblock_reason)?$unblock_reason:'';?>" class="form-control">
          <span class="input-group-addon"><i class="glyphicon form-control-feedback"><span style=" color:red; font-size:9px;">*</span></i></span></div>
          <?php if(form_error('unblock_reason')):?>
          <span class="text-danger"><?php echo form_error('unblock_reason');?></span>
          <?php endif?>
      </div>
                          
    
      <button type="submit" name="submit" class="btn btn-primary btn-warning"><span class="glyphicon glyphicon-check"></span> <?php echo lang('unblock')?></button>
      
	</form>
    <br/>
    </div>
    
    <div class="col-md-3"></div>
    
</div>	

<?php echo $this->load->view('footer'); ?>
