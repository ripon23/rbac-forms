<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_block_list'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_block_list')?></li>
</ol>

<legend class="text-center"><?=lang('menu_block_list')?> </legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./recharge/recharge_card/block_list_search/" method="post">   
    <div class="col-md-12">
    <table class="table table-bordered">      
      <tr>
        <td><input id="msisdn" name="msisdn" type="text" placeholder="01XXXXXXXXX" value="<?php echo isset($msisdn)?$msisdn:'';?>" class="form-control input-sm"></td>		
        <td>
        <input type="submit" name="search_submit" id="search_submit" value="<?=lang('action_search')?>" class="btn btn-primary btn-sm" />
        </td>
      </tr>
    </table>
        
       
    
    </div> <!-- col-md-12 -->
    </form>
<div class="row">
    <div class="col-md-12"> 
    <?php
	if(isset($block_list))
	echo "Total ".$total_records." records found";
	?>
    <table class="table table-bordered table-striped">
    	<tr class="warning">
        	<td><?=lang('mobile_no')?></td>
            <td><?=lang('last_attempt_datetime')?></td>
            <td><?=lang('data_source')?></td>            
      	</tr>
     	<?php 
		if( !empty($block_list) ) {
			foreach ($block_list as $block) : 
		?>
        <tr>
        	<td><?=$block->msisdn?></td>
            <td><?=$block->last_attempt_datetime?></td>
            <td><?=$block->source?></td>
        </tr>
        <?php 			
			endforeach; 
		}	//end if
		else
		{
		?>
         <tr>
         	<td colspan="6">
			<?php	
            echo lang('no-data-found');
            }
            ?>   
        	</td>
        </tr>  
    </table>    
        
        
        
        
        
        
    <div style="text-align:left"><?php echo $links; ?></div>  
    
    </div>


</div>	

<?php echo $this->load->view('footer'); ?>
