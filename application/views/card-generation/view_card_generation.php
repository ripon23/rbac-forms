<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => 'aponjon')); ?>
<div class="row">
    <legend class="text-center"><?php echo lang('menu_prepaid-card-generation')?></legend>   	
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
      	<div class="alert alert-warning" role="alert">Warning! Before generate prepaid card beware about the selection. Please double check your selection. After you generate the card you cannot undo your action.</div>
      	
        <div class="well well-sm"><strong><span class="pull-left" style=" color:red; font-size:22px; margin-right:10px;">*</span> <?php echo lang('required_field')?> </strong></div>
      
      
      <div class="form-group">
        <label for="card_type"><?php echo lang('card-type')?></label>
        <div class="input-group">
          <select name="card_type" id="card_type" class="form-control" >
                        <option value=""><?=lang('select')?> <?php echo lang('card-type'); ?></option>
                    	<option value="60" <?php if(set_value('card_type')=="60") echo "selected";?> >60 TK</option>
                        <option value="100" <?php if(set_value('card_type')=="100") echo "selected";?> >100 TK</option>            
                       	<option value="200" <?php if(set_value('card_type')=="200") echo "selected";?>>200 TK</option>
                        <option value="500" <?php if(set_value('card_type')=="500") echo "selected";?>>500 TK</option>                     
	      </select>
          <span class="input-group-addon"><i class="glyphicon form-control-feedback"><span style=" color:red; font-size:9px;">*</span></i></span></div>
          <?php if(form_error('card_type')):?>
          <span class="text-danger"><?php echo form_error('card_type');?></span>
          <?php endif?>
      </div>
      
      
      <div class="form-group">
        <label for="number-of-card-to-be-generate"><?php echo lang('number-of-card-to-be-generate')?></label>
        <div class="input-group">
          <select name="number_of_card_to_be_generate" id="number_of_card_to_be_generate" class="form-control">
                        <option value=""><?=lang('select')?> <?php echo lang('number-of-card-to-be-generate'); ?></option>
                    	<option value="10" <?php if(set_value('number_of_card_to_be_generate')=="10") echo "selected";?>>10</option>
                       	<option value="20" <?php if(set_value('number_of_card_to_be_generate')=="20") echo "selected";?>>20</option>
                        <option value="50" <?php if(set_value('number_of_card_to_be_generate')=="50") echo "selected";?>>50</option>
                       	<option value="100" <?php if(set_value('number_of_card_to_be_generate')=="100") echo "selected";?>>100</option>
                        <option value="200" <?php if(set_value('number_of_card_to_be_generate')=="200") echo "selected";?>>200</option>
                        <option value="300" <?php if(set_value('number_of_card_to_be_generate')=="300") echo "selected";?>>300</option>
                        <option value="500" <?php if(set_value('number_of_card_to_be_generate')=="500") echo "selected";?>>500</option>
	      </select>
          <span class="input-group-addon"><i class="glyphicon form-control-feedback"><span style=" color:red; font-size:9px;">*</span></i></span></div>
          <?php if(form_error('number_of_card_to_be_generate')):?>
          <span class="text-danger"><?php echo form_error('number_of_card_to_be_generate');?></span>
          <?php endif?>
      </div>
                          
    
      <button type="submit" name="submit" class="btn btn-primary btn-warning"><span class="glyphicon glyphicon-send"></span> <?php echo lang('generate-card')?></button>
      
	</form>
    <br/>
    </div>


    <div class="col-md-2">
    	
    </div>
</div>	

<?php echo $this->load->view('footer'); ?>
