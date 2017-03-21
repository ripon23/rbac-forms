<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_dealers_list'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_dealers_list')?></li>
</ol>

<legend class="text-center"><?=lang('menu_dealers_list')?> <?php echo $distributor_info->distributor_name." (".$distributor_info->distributor_code.")";?></legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./distributors/distributors/dealers_list_search_of_distributor/<?=$distributor_info->distributor_id?>" method="post">
    <div class="col-md-12">
    <table class="table table-bordered">
      <!--<tr class="warning">
        <td><?=lang('card-serial')?></td>
        <td><?=lang('active-inactive')?></td>
        <td><?=lang('card-type')?></td>
        <td><?=lang('create_date')?></td>
       
      </tr>-->
      <tr>
        <td><input id="dealer_code" name="dealer_code" type="text" placeholder="<?=lang('dealer_code')?>" value="<?php echo isset($dealer_code)?$dealer_code:'';?>" class="form-control input-sm"></td>
        <td><input id="dealer_name" name="dealer_name" type="text" placeholder="<?=lang('dealer_name')?>" value="<?php echo isset($dealer_name)?$dealer_name:'';?>" class="form-control input-sm"></td>
		<td>
        <select name="active_inactive" id="active_inactive" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="acitve" <?php echo set_select('active_inactive',"acitve", ( !empty($active_inactive) && $active_inactive == "acitve" ? TRUE : FALSE )); ?>><?php echo lang('active'); ?></option>
                <option value="inactive" <?php echo set_select('active_inactive',"inactive", ( !empty($active_inactive) && $active_inactive == "inactive" ? TRUE : FALSE )); ?>><?php echo lang('inactive'); ?></option>                
        </select>
        </td>
        <td>        
        <select name="site_district" id="site_district" class="form-control col-md-1 input-sm">
			<option value=""><?php echo lang('select'); ?></option>
			<?php 				
            foreach ($all_district as $district) : ?>
            <option value="<?php echo $district->district;?>" <?php if(isset($site_district)){if($district->district==$site_district) echo ' selected="selected"'; }?>><?php echo $district->loc_name_en?></option>
            <?php endforeach; ?>
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
        	<th><?=lang('dealer_code')?></th>            
            <th><?=lang('dealer_name')?></th>
            <th><?=lang('dealer_mobile')?></th>
            <th><?=lang('district')?></th>
            <th><?=lang('active')?></th>  
            <th><?=lang('action_edit')?></th>
      	</tr>
     	<?php 
		if( !empty($all_dealers) ) {
			foreach ($all_dealers as $dealers) : 
		?>
        <tr>
        	<td><?=$dealers->dealer_code?></td>            
            <td><?=$dealers->dealer_name?></td>
            <td><?=$dealers->dealer_mobile?></td>
            <td><?=$this->ref_location_model->get_district_name_from_id($dealers->dealer_district)?></td>
            <td>
            <?php
            if($dealers->active_status==1)
			echo '<span class="label label-success">'.lang('active').'</span>';
			elseif($dealers->active_status==0)
			echo '<span class="label label-warning">'.lang('inactive').'</span>';			
			?>
            </td>
            <td>
            <!-- Distribute Button -->
			<?php if ($this->authorization->is_permitted('can_distribute_card_among_own_dealers')) : ?> 
            <a href="<?php echo base_url().'distribution/distribution/distribute_to_dealer/'.$dealers->dealer_id;?>" class="btn btn-warning btn-xs"><?=lang('menu_distribute')?></a>
            <?php endif; ?>
            
            <!-- View card list Button -->
			<?php if ($this->authorization->is_permitted('can_view_own_distributed_card_list')) : ?> 
            <a href="<?php echo base_url().'distribution/distribution/dealer_card_list/'.$dealers->dealer_id;?>" class="btn btn-info btn-xs"><?=lang('action_view')?> <?=lang('card_list')?></a>
            <?php endif; ?>                       
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
