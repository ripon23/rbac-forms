<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_recharge_history'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_recharge_history')?></li>
</ol>

<legend class="text-center"><?=lang('menu_recharge_history')?> </legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./recharge/recharge_card/recharge_history_search/" method="post">   
    <div class="col-md-12">
    <table class="table table-bordered">      
      <tr>
        <td><input id="card_serial" name="card_serial" type="text" placeholder="<?=lang('card-serial')?>" value="<?php echo isset($card_serial)?$card_serial:'';?>" class="form-control input-sm"></td>
        <td>
        <select name="card_type" id="card_type" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="4" <?php echo set_select('card_type',"4", ( !empty($card_type) && $card_type == "4" ? TRUE : FALSE )); ?>><?php echo lang('60_tk'); ?></option>
                <option value="1" <?php echo set_select('card_type',"1", ( !empty($card_type) && $card_type == "1" ? TRUE : FALSE )); ?>><?php echo lang('100_tk'); ?></option>
                <option value="2" <?php echo set_select('card_type',"2", ( !empty($card_type) && $card_type == "2" ? TRUE : FALSE )); ?>><?php echo lang('200_tk'); ?></option>
                <option value="3" <?php echo set_select('card_type',"3", ( !empty($card_type) && $card_type == "3" ? TRUE : FALSE )); ?>><?php echo lang('500_tk'); ?></option>
        </select>
        </td>
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
	if(isset($total_records))
	echo "Total ".$total_records." records found";
	?>
    <table class="table table-bordered table-striped">
    	<tr class="warning">
        	<td><?=lang('card-serial')?></td>
            <td><?=lang('card-type')?></td>
            <td><?=lang('active-inactive')?></td>
            <td><?=lang('recharge-msisdn')?></td>
            <td><?=lang('recharge-datetime')?></td>
            <td><?=lang('recharge-by')?></td>
            <td><?=lang('distributor')?></td>
            <td><?=lang('dealer')?></td>
      	</tr>
     	<?php 
		if( !empty($recharge_list) ) {
			foreach ($recharge_list as $recharge_history) : 
		?>
        <tr>
        	<td><?=$recharge_history->card_serial?></td>
            <td>
            <?php
			if($recharge_history->card_type==1)
			echo '<span class="label label-primary">'.lang('100_tk').'</span>';
			elseif($recharge_history->card_type==2)			
			echo '<span class="label label-success">'.lang('200_tk').'</span>';
			elseif($recharge_history->card_type==3)			
			echo '<span class="label label-info">'.lang('500_tk').'</span>';
			elseif($recharge_history->card_type==4)			
			echo '<span class="label label-default">'.lang('60_tk').'</span>';
			?>
            </td>
            <td>
            <?php
            if($recharge_history->active_status==1)
			echo '<span class="label label-success">'.lang('active').'</span>';
			elseif($recharge_history->active_status==0)
			echo '<span class="label label-warning">'.lang('inactive').'</span>';
			elseif($recharge_history->active_status==2)
			echo '<span class="label label-danger">'.lang('recharged').'</span>';			
			?>
            </td>
            <td><?=$recharge_history->msisdn?></td>
            <td><?=$recharge_history->recharge_datetime?></td>
            <td>
			<?php
            if($recharge_history->recharge_by=='999999')
			echo "API";
			else
			echo $this->account_model->get_username_by_id($recharge_history->recharge_by);
			?>
            </td>
            <td>
            <?php
			$searchterm="SELECT * FROM apninv_card_distribution WHERE card_id=".$recharge_history->card_id." AND distributor_or_dealer='di'";
			$distribution_info=$this->general_model->get_all_single_row_querystring($searchterm);
			//print_r($distribution_info);
			if($distribution_info)
			{
				$distributor_detail_info=$this->site_model->get_distributor_delear_info_by_id($distribution_info->distributor_dealer_id,'di');
				echo $distributor_detail_info->distributor_name." (".$distributor_detail_info->distributor_code.")";
			}
			?>
            </td>
            <td>
            <?php
			$searchterm1="SELECT * FROM apninv_card_distribution WHERE card_id=".$recharge_history->card_id." AND distributor_or_dealer='de'";
			$dealer_info=$this->general_model->get_all_single_row_querystring($searchterm1);
			if($dealer_info)
			{
				
				$delear_detail_info=$this->site_model->get_distributor_delear_info_by_id($dealer_info->distributor_dealer_id,'de');
				echo $delear_detail_info->dealer_name." (".$delear_detail_info->dealer_code.")";
			}
			?>
            </td>
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
