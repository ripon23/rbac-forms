<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('card_list'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('card_list')?></li>
</ol>

<legend class="text-center"><?=lang('card_list')?>: <?php echo $distributor_info->distributor_name." (".$distributor_info->distributor_code.")";?></legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./distribution/distribution/distributor_card_list_search/<?=$distributor_info->distributor_id?>" method="post">
    <div class="col-md-12">
    <table class="table table-bordered">
      <tr>
        <td><input id="card_serial" name="card_serial" type="text" placeholder="<?=lang('card-serial')?>" value="<?php echo isset($card_serial)?$card_serial:'';?>" class="form-control input-sm"></td>
		<td>
        <select name="active_inactive" id="active_inactive" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="acitve" <?php echo set_select('active_inactive',"acitve", ( !empty($active_inactive) && $active_inactive == "acitve" ? TRUE : FALSE )); ?>><?php echo lang('active'); ?></option>
                <option value="inactive" <?php echo set_select('active_inactive',"inactive", ( !empty($active_inactive) && $active_inactive == "inactive" ? TRUE : FALSE )); ?>><?php echo lang('inactive'); ?></option>
                <option value="recharged" <?php echo set_select('active_inactive',"recharged", ( !empty($active_inactive) && $active_inactive == "recharged" ? TRUE : FALSE )); ?>><?php echo lang('recharged'); ?></option>
        </select>
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
        	<th><?=lang('card-serial')?></th>            
            <th><?=lang('card-type')?></th>
            <th><?=lang('status')?></th> 
      	</tr>
     	<?php 
		if( !empty($card_info) ) {
			foreach ($card_info as $card) : 
		?>
        <tr>
        	<td><?=$card->card_serial?></td>            
            <td>
			<?php
			if($card->card_type==1)
			echo '<span class="label label-primary">'.lang('100_tk').'</span>';
			elseif($card->card_type==2)			
			echo '<span class="label label-success">'.lang('200_tk').'</span>';
			elseif($card->card_type==3)			
			echo '<span class="label label-info">'.lang('500_tk').'</span>';
			elseif($card->card_type==4)			
			echo '<span class="label label-default">'.lang('60_tk').'</span>';
			?> 
            </td>
            <td>
            <?php
            if($card->active_status==1)
			echo '<span class="label label-success">'.lang('active').'</span>';
			elseif($card->active_status==0)
			echo '<span class="label label-warning">'.lang('inactive').'</span>';
			elseif($card->active_status==2)
			echo '<span class="label label-danger">'.lang('recharged').'</span>';			
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
