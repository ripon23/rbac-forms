<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_distributors_list'))); ?>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_distributors_list')?></li>
</ol>

<legend class="text-center"><?=lang('menu_distributors_list')?> </legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./distributors/distributors/distributors_list_search" method="post">
    <div class="col-md-12">
    <table class="table table-bordered">
      <!--<tr class="warning">
        <td><?=lang('card-serial')?></td>
        <td><?=lang('active-inactive')?></td>
        <td><?=lang('card-type')?></td>
        <td><?=lang('create_date')?></td>
       
      </tr>-->
      <tr>
        <td><input id="distributor_code" name="distributor_code" type="text" placeholder="<?=lang('distributor_code')?>" value="<?php echo isset($distributor_code)?$distributor_code:'';?>" class="form-control input-sm"></td>
        <td><input id="distributor_name" name="distributor_name" type="text" placeholder="<?=lang('distributor_name')?>" value="<?php echo isset($distributor_name)?$distributor_name:'';?>" class="form-control input-sm"></td>
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
        	<th><?=lang('distributor_code')?></th>            
            <th><?=lang('distributor_name')?></th>
            <th><?=lang('distributor_mobile')?></th>
            <th><?=lang('district')?></th>
            <th><?=lang('active')?></th>  
            <th><?=lang('action_edit')?></th>
      	</tr>
     	<?php 
		if( !empty($all_distributors) ) {
			foreach ($all_distributors as $distributors) : 
		?>
        <tr>
        	<td><?=$distributors->distributor_code?></td>            
            <td><?=$distributors->distributor_name?></td>
            <td><?=$distributors->distributor_mobile?></td>
            <td><?=$this->ref_location_model->get_district_name_from_id($distributors->distributor_district)?></td>
            <td>
            <?php
            if($distributors->active_status==1)
			echo '<span class="label label-success">'.lang('active').'</span>';
			elseif($distributors->active_status==0)
			echo '<span class="label label-warning">'.lang('inactive').'</span>';			
			?>
            </td>
            <td>
            <!-- Edit Button -->
			<?php if ($this->authorization->is_permitted('can_create_distributors')) : ?> 
            <a href="<?php echo base_url().'distributors/distributors/edit_single_distributor/'.$distributors->distributor_id;?>" class="btn btn-warning btn-xs"><?=lang('action_edit')?></a>
            <?php endif; ?>
            
            <!-- Add Dealer Button -->
            <?php if ($this->authorization->is_permitted('can_create_delear_for_distributor')) : ?> 
            <a href="<?php echo base_url().'distributors/distributors/add_dealer_for_distributor/'.$distributors->distributor_id;?>" class="btn btn-primary btn-xs"><?=lang('action_add')?> <?=lang('dealer')?></a>
            <?php endif; ?>
            
            <!-- Add Dealer List Button -->
            <?php if ($this->authorization->is_permitted('can_view_all_delear_list')) : ?> 
            <a href="<?php echo base_url().'distributors/distributors/dealer_list_of_distributor/'.$distributors->distributor_id;?>" class="btn btn-success btn-xs"><?=lang('menu_dealers_list')?></a>
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
