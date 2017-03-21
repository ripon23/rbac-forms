<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_prepaid-card-download'))); ?>
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
  <li class="active"><?=lang('menu_prepaid-card-list')?></li>
</ol>

<legend class="text-center"><?=lang('menu_prepaid-card-download')?> </legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./card-generation/card_generation/download_card_search" method="post">   
	
    <div class="col-md-12">
    <table class="table table-bordered">
      <!--<tr class="warning">
        <td><?=lang('card-serial')?></td>
        <td><?=lang('active-inactive')?></td>
        <td><?=lang('card-type')?></td>
        <td><?=lang('create_date')?></td>
       
      </tr>-->
      <tr>
        <td>
        <div class="form-inline"> 
        <input id="card_serial_from" name="card_serial_from" type="text" placeholder="<?=lang('card-serial')?> <?=lang('from')?>" value="<?php echo isset($card_serial_from)?$card_serial_from:'';?>" class="form-control input-sm">
        <input id="card_serial_to" name="card_serial_to" type="text" placeholder="<?=lang('card-serial')?> <?=lang('to')?>" value="<?php echo isset($card_serial_to)?$card_serial_to:'';?>" class="form-control input-sm">
        </div>
        </td>
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
        <div class="form-inline">        
        <input id="date_from" name="date_from" type="text" placeholder="<?=lang('create_date')?> <?=lang('from')?> (YYYY-MM-DD)" value="<?php echo isset($date_from)?$date_from:'';?>" class="form-control input-sm"> <input id="date_to" name="date_to" type="text" placeholder="<?=lang('create_date')?> <?=lang('to')?> (YYYY-MM-DD)" value="<?php echo isset($date_to)?$date_to:'';?>" class="form-control input-sm">
        </div>
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
    
        <div style="width:40%; text-align:right; float:right"> 
			<?php if ($this->authorization->is_permitted('can_download_card_info')) : ?> 
            <a href="<?php echo base_url().'card-generation/card_generation/download_to_excel/';?>" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> <?=lang('action_download')?></a>
            <?php endif; ?>                    
        </div>
    <?php
	if(isset($total_records))
	echo "Total ".$total_records." records found";
	?>
    <table class="table table-bordered table-striped">
    	<tr class="warning">
        	<td><?=lang('card-serial')?></td>
            <td><?=lang('card-pin')?></td>
            <td><?=lang('card-type')?></td>
            <td><?=lang('active-inactive')?></td>
            <td><?=lang('create_user')?></td>
            <td><?=lang('create_date')?></td>            
      	</tr>
     	<?php 
		if( !empty($all_card) ) {
			foreach ($all_card as $card) : 
		?>
        <tr>
        	<td><?=$card->card_serial?></td>
            <td>
            <?php
			if($can_see_pin==1)
			{
			$CI =& get_instance();			
			$decryptedText = $CI->decrypt($card->card_pin, $key);
			echo $decryptedText;
			}
			else			
            echo $card->card_pin;
            ?>
            </td>
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
            <td><?php echo $this->account_model->get_username_by_id($card->create_user_id);?></td>
            <td><?=$card->create_date?></td>
            
           
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
        
        
        
        
        
    <?php 
	if(isset($links)){
	?>
    <div style="text-align:left"><?php echo $links; ?></div>  
    <?php
	}
	?>
    
    
    </div>


</div>	

<?php echo $this->load->view('footer'); ?>
