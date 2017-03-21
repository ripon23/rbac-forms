<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_prepaid-card-download'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_prepaid-card-activation')?></li>
</ol>

<legend class="text-center"><?=lang('menu_prepaid-card-activation')?> </legend>  

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

<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./card-generation/card_generation/card_activation" method="post">   
	
    <div class="col-md-12">
    <table class="table table-bordered">      
      <tr>
        <td>
        <div class="form-inline"> 
        <input id="card_serial_from" name="card_serial_from" type="text" placeholder="<?=lang('card-serial')?> <?=lang('from')?>" value="<?php echo isset($card_serial_from)?$card_serial_from:'';?>" class="form-control input-sm">
        <?php if(form_error('card_serial_from')):?>
        <span class="text-danger" style="font-size:10px;"><?php echo form_error('card_serial_from');?></span>
        <?php endif?>
          
        <input id="card_serial_to" name="card_serial_to" type="text" placeholder="<?=lang('card-serial')?> <?=lang('to')?>" value="<?php echo isset($card_serial_to)?$card_serial_to:'';?>" class="form-control input-sm">
        <?php if(form_error('card_serial_to')):?>
        <span class="text-danger" style="font-size:10px;"><?php echo form_error('card_serial_to');?></span>
        <?php endif?>
        </div>
        </td>		
        <td>
        <select name="card_type" id="card_type" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="4" <?php echo set_select('card_type',"4", ( !empty($card_type) && $card_type == "4" ? TRUE : FALSE )); ?>><?php echo lang('60_tk'); ?></option>
                <option value="1" <?php echo set_select('card_type',"1", ( !empty($card_type) && $card_type == "1" ? TRUE : FALSE )); ?>><?php echo lang('100_tk'); ?></option>
                <option value="2" <?php echo set_select('card_type',"2", ( !empty($card_type) && $card_type == "2" ? TRUE : FALSE )); ?>><?php echo lang('200_tk'); ?></option>
                <option value="3" <?php echo set_select('card_type',"3", ( !empty($card_type) && $card_type == "3" ? TRUE : FALSE )); ?>><?php echo lang('500_tk'); ?></option>
        </select>
        </td>
        <td>
        <div class="form-inline">        
        <input id="date_from" name="date_from" type="text" placeholder="<?=lang('create_date')?> <?=lang('from')?> (YYYY-MM-DD)" value="<?php echo isset($date_from)?$date_from:'';?>" class="form-control input-sm"> <input id="date_to" name="date_to" type="text" placeholder="<?=lang('create_date')?> <?=lang('to')?> (YYYY-MM-DD)" value="<?php echo isset($date_to)?$date_to:'';?>" class="form-control input-sm">
        </div>
        </td>
        <td>
        <input type="submit" name="search_submit" id="search_submit" value="<?=lang('activate')?>" class="btn btn-warning btn-sm" />
        </td>
      </tr>
    </table>
        
       
    
    </div> <!-- col-md-12 -->
</form>


<legend class="text-center"><?=lang('menu_prepaid-card-deactivation')?> </legend>  
<?php if (isset($error_msg2)):?>
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <span class="glyphicon glyphicon-alert"></span><strong>  <?php echo "".$error_msg2?> </strong>
</div>
<?php endif;?>

<?php if (isset($success_msg2)):?>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong><span class="glyphicon glyphicon-saved"></span> <?php echo "".$success_msg2?> </strong>
</div>                	  
<?php endif;?>        
        
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./card-generation/card_generation/card_deactivation" method="post">   
	
    <div class="col-md-12">
    <table class="table table-bordered">      
      <tr>
        <td>
        <div class="form-inline"> 
        <input id="card_serial_from2" name="card_serial_from2" type="text" placeholder="<?=lang('card-serial')?> <?=lang('from')?>" value="<?php echo isset($card_serial_from2)?$card_serial_from2:'';?>" class="form-control input-sm">
        <?php if(form_error('card_serial_from2')):?>
        <span class="text-danger" style="font-size:10px;"><?php echo form_error('card_serial_from2');?></span>
        <?php endif?>
        
        <input id="card_serial_to2" name="card_serial_to2" type="text" placeholder="<?=lang('card-serial')?> <?=lang('to')?>" value="<?php echo isset($card_serial_to2)?$card_serial_to2:'';?>" class="form-control input-sm">
        <?php if(form_error('card_serial_to2')):?>
        <span class="text-danger" style="font-size:10px;"><?php echo form_error('card_serial_to2');?></span>
        <?php endif?>
        </div>
        </td>		
        <td>
        <select name="card_type2" id="card_type2" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="4" <?php echo set_select('card_type2',"4", ( !empty($card_type2) && $card_type2 == "4" ? TRUE : FALSE )); ?>><?php echo lang('60_tk'); ?></option>
                <option value="1" <?php echo set_select('card_type2',"1", ( !empty($card_type2) && $card_type2 == "1" ? TRUE : FALSE )); ?>><?php echo lang('100_tk'); ?></option>
                <option value="2" <?php echo set_select('card_type2',"2", ( !empty($card_type2) && $card_type2 == "2" ? TRUE : FALSE )); ?>><?php echo lang('200_tk'); ?></option>
                <option value="3" <?php echo set_select('card_type2',"3", ( !empty($card_type2) && $card_type2 == "3" ? TRUE : FALSE )); ?>><?php echo lang('500_tk'); ?></option>
        </select>
        </td>
        <td>
        <div class="form-inline">        
        <input id="date_from2" name="date_from2" type="text" placeholder="<?=lang('create_date')?> <?=lang('from')?> (YYYY-MM-DD)" value="<?php echo isset($date_from2)?$date_from2:'';?>" class="form-control input-sm"> <input id="date_to2" name="date_to2" type="text" placeholder="<?=lang('create_date')?> <?=lang('to')?> (YYYY-MM-DD)" value="<?php echo isset($date_to2)?$date_to2:'';?>" class="form-control input-sm">
        </div>
        </td>
        <td>
        <input type="submit" name="search_submit" id="search_submit" value="<?=lang('deactivate')?>" class="btn btn-warning btn-sm" />
        </td>
      </tr>
    </table>
        
       
    
    </div> <!-- col-md-12 -->
</form>

	

<?php echo $this->load->view('footer'); ?>
