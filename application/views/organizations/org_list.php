<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_prepaid-card-list'))); ?>
    <script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/dist/js/bootstrap-datepicker.min.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/dist/css/bootstrap-datepicker.min.css"/>
    
    <script type="text/javascript">
		// When the document is ready
		$(document).ready(function () {
			
			$('#date_from').datepicker({
				format: "yyyy-mm-dd",
				autoclose:true
			}); 
			$('#date_to').datepicker({
				format: "yyyy-mm-dd",
				autoclose:true
			});   
		
		});
	</script>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_organizations')?> <?=lang('list')?></li>
</ol>

<legend class="text-center"><?=lang('menu_organizations')?> <?=lang('list')?></legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./card-generation/card_generation/card_list_search" method="post">   
    <div class="col-md-12">
    <table class="table table-bordered">
      <!--<tr class="warning">
        <td><?=lang('card-serial')?></td>
        <td><?=lang('active-inactive')?></td>
        <td><?=lang('card-type')?></td>
        <td><?=lang('create_date')?></td>
       
      </tr>-->
      <tr>
        <td><input id="search_org_name" name="search_org_name" type="text" placeholder="<?=lang('org_name')?>" value="<?php echo isset($search_org_name)?$search_org_name:'';?>" class="form-control input-sm"></td>
		<td>
        <input id="search_org_type" name="search_org_type" type="text" placeholder="<?=lang('org_type')?>" value="<?php echo isset($search_org_type)?$search_org_type:'';?>" class="form-control input-sm">
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
        	<td><?=lang('org_name')?></td>            
            <td><?=lang('org_type')?></td>
            <td><?=lang('address')?></td>
            <td><?=lang('create_date')?></td>
            <td><?=lang('action_edit')?></td>            
      	</tr>
     	<?php 
		if( !empty($all_org) ) {
			foreach ($all_org as $org) : 
		?>
        <tr>
        	<td><?=$org->tx_partner_name?></td>            
			<td><?=$org->tx_partner_type?></td>
            <td><?=$org->tx_partner_address?></td>
            <td><?php echo $org->dtt_mod;?></td>
            <td>
           <!-- Edit Button -->
			<?php if ($this->authorization->is_permitted('can_edit_org')) : ?> 
            <a href="<?php echo base_url().'organizations/organizations/edit_org/'.$org->int_outreach_partner_key;?>" class="btn btn-warning btn-xs"><?=lang('action_edit')?></a>
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
