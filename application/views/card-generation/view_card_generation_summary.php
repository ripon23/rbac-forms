<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => 'aponjon')); ?>
<div class="row">
    <div class="col-md-9">
    	
        <h3><?php echo lang('prepaid-card-generation-summary')?></h3>
        
      	<div class="alert alert-warning" role="alert">
        <?php
		echo "Total Card Generated <strong>".$total_number_generate."</strong></br>";
		echo "Serial Number From <strong>".$serial_from."</strong> To <strong>".$serial_to."</strong>";
		?>
        </div>
      	
        
    <br/>
    </div>


    <div class="col-md-3">
    <div class="well well-lg">
    
        <div class="panel panel-warning">
          <div class="panel-heading">
            <h3 class="panel-title"><?=lang('card-serial')?></h3>
          </div>
          <div class="panel-body">
                    <form class="navbar-form navbar-right" role="search" id="search-form"  name="search-form" action="./card-generation/card_generation/card_list_search" method="post">             
                    <div class="input-group">                        
                        <input type="text" class="form-control input-sm" name="card_serial" id="card_serial" placeholder="<?=lang('card-serial')?>" value="<?=set_value('search_param')?>">
                        <span class="input-group-btn">
                            <input type="submit" name="search_submit" id="search_submit" value="<?php echo lang('action_search');?>" class="btn btn-info btn-sm " />
                        </span>
                    </div>            
                    </form>
          </div>
        </div>
        
        
    
    </div>
    </div>
</div>	

<?php echo $this->load->view('footer'); ?>
