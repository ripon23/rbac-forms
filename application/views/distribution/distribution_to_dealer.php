<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head',array('title'=>lang('menu_distribution'))); ?>
    <script type="text/javascript">
	function confirm_distribution(card_serial_from,card_serial_to,card_type,dealer_id)
	{
	
	var postForm = { //Fetch form data				
				'card_serial_from':card_serial_from,
				'card_serial_to':card_serial_to,
				'card_type':card_type,
				'dealer_id':dealer_id
			};
	
	
	
	$.ajax({ //Process the form using $.ajax()
					type      : 'POST', //Method type
					url       : 'distribution/distribution/save_my_distribution', //Your form processing file URL
					beforeSend: function() {
					$('.well').html("<img src='<?php echo base_url().RES_DIR; ?>/img/ajax-loader.gif' />");
					},
					data      : postForm, //Forms name
					success   : function(response) {									
									$( ".well" ).empty();
									$(".well").html(response);
									}
				});

	}

</script>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_distribution')?></li>
</ol>

<legend class="text-center"><?=lang('menu_distribution')?>: <?php echo $dealer_info->dealer_name." (".$dealer_info->dealer_code.")";?></legend>  

<div class="row">
    <div class="col-md-8">
    	
    <form class="form-horizontal" method="post" name="check_card">
        <div class="form-group">        	
            <label for="card_serial_from" class="col-sm-3 control-label"><?=lang('card-serial')?> <?=lang('from')?> *</label>
            <div class="col-sm-9">
                <input id="card_serial_from" name="card_serial_from" type="text" value="<?php echo isset($card_serial_from)?$card_serial_from:'';?>" class="form-control input-sm">
                <?php if(form_error('card_serial_from')):?>
                <span class="text-danger" style="font-size:12px;"><?php echo form_error('card_serial_from');?></span>
            <?php endif?>
            </div>
        </div>
        
        <div class="form-group"> 
            <label for="card_serial_to" class="col-sm-3 control-label"><?=lang('card-serial')?> <?=lang('to')?> *</label>  
            <div class="col-sm-9">
                <input id="card_serial_to" name="card_serial_to" type="text" value="<?php echo isset($card_serial_to)?$card_serial_to:'';?>" class="form-control input-sm">
                <?php if(form_error('card_serial_to')):?>
                <span class="text-danger" style="font-size:12px;"><?php echo form_error('card_serial_to');?></span>
                <?php endif?>
            </div>
        </div>
        
        <div class="form-group"> 
        <label for="card_type" class="col-sm-3 control-label"><?=lang('card-type')?> *</label>  
        	<div class="col-sm-9">
        	<select name="card_type" id="card_type" class="form-control col-md-1 input-sm">
                <option value=""><?php echo lang('select'); ?></option>
                <option value="4" <?php echo set_select('card_type',"4", ( !empty($card_type) && $card_type == "4" ? TRUE : FALSE )); ?>><?php echo lang('60_tk'); ?></option>
                <option value="1" <?php echo set_select('card_type',"1", ( !empty($card_type) && $card_type == "1" ? TRUE : FALSE )); ?>><?php echo lang('100_tk'); ?></option>
                <option value="2" <?php echo set_select('card_type',"2", ( !empty($card_type) && $card_type == "2" ? TRUE : FALSE )); ?>><?php echo lang('200_tk'); ?></option>
                <option value="3" <?php echo set_select('card_type',"3", ( !empty($card_type) && $card_type == "3" ? TRUE : FALSE )); ?>><?php echo lang('500_tk'); ?></option>
        	</select>
            	<?php if(form_error('card_type')):?>
                <span class="text-danger" style="font-size:12px;"><?php echo form_error('card_type');?></span>
                <?php endif?>
        	</div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
            <input type="submit" name="check" id="check" value="<?=lang('action_check')?>" class="btn btn-success btn-sm" />
        	</div>
        </div>        
    </form>   
    
    </div> <!-- col-md-8 -->
	
    <div class="col-md-8">
		   
		<?php
        if(isset($card_info))
        {
		echo '<div class="well">';
        echo sizeof($card_info)." valid card found for distribution. ";
		if(sizeof($card_info)>0)
		{
		echo '<button id="btnconfirm" onClick="confirm_distribution('.$card_serial_from.','.$card_serial_to.','.$card_type.','.$dealer_info->dealer_id.')" name="btnconfirm" class="btn btn-warning btn-xs">'.lang('action_distribute_now').'</button>';
		}
        //echo "<pre>";
        //print_r($card_info);
        //echo "</pre>";
		//echo '<div id="success"></div>';
		echo '</div>';
        }
        ?>
		
    </div> <!-- col-md-8 -->
    
    
</div>	

<?php echo $this->load->view('footer'); ?>
