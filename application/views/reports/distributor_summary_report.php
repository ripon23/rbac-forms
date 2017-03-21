<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_prepaid-card-list'))); ?>    
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_distribution_summary_report')?></li>
</ol>

<legend class="text-center"><?=lang('menu_distribution_summary_report')?> </legend>  

<div class="row">
    <div class="col-md-12">
    	<div style="width:40%; text-align:right; float:right"> 
			<?php if ($this->authorization->is_permitted('can_download_distributor_card_summary')) : ?> 
            <a href="<?php echo base_url().'reports/reports/download_distributor_card_summary/';?>" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> <?=lang('action_download')?></a>
            <?php endif; ?>                    
        </div> 
    <table class="table table-bordered table-striped">
    	<tr class="warning">
            <th><?=lang('distributor_code')?></th>
            <th><?=lang('distributor_name')?></th>
            <th><?=lang('mobile_no')?></th>
            <th><?=lang('district')?></th>          
            <th><?=lang('60_tk')?></th>
            <th><?=lang('100_tk')?></th>
            <th><?=lang('200_tk')?></th>
            <th><?=lang('500_tk')?></th>
            <th><?=lang('total')?></th>
            <th><?=lang('recharged')?></th>            
      	</tr>
     	<?php
		//echo "<pre>";
		//print_r($result_all_distributor);
		//echo "</pre>";
		
		if( !empty($result_all_distributor) ) {
						
		foreach($result_all_distributor as $distributor_info) : 
		?>
        <tr>
        	<td><?=$distributor_info->distributor_code?></td>
            <td><?=$distributor_info->distributor_name?></td>
            <td><?=$distributor_info->distributor_mobile?></td>
            <td><?=$distributor_info->district_name?></td>
            <td><?=$distributor_info->tk_60?></td>
            <td><?=$distributor_info->tk_100?></td>
            <td><?=$distributor_info->tk_200?></td>
            <td><?=$distributor_info->tk_500?></td>
            <td><?=$distributor_info->total_card?></td>
            <td><?=$distributor_info->recharge_card_count?></td>
        </tr>        
        <?php
		endforeach; //end if
		}
		else
		{
		?>
         <tr>
         	<td colspan="11">
			<?php	
            echo lang('no-data-found');            
            ?>   
        	</td>
        </tr>
        <?php  
		}
		?>
    </table>
    <div style="text-align:left"><?php echo $links; ?></div> 
    </div>


</div>	

<?php echo $this->load->view('footer'); ?>
