<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header'); ?>
<div class="cl-md-12">
	<?php if ($this->session->flashdata('parmission')):?>
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-info-sign"></span>
        	<strong>Warning!</strong>
			<?php
            echo  $this->session->flashdata('parmission');
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <?php endif;?>
</div>
<div class="row">
<div class="col-md-12">
<div class="well well-lg" style="overflow:hidden;">


<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_dealers_management')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_create_own_dealer'))
			{
			?>
            <li><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <a href="<?php echo base_url();?>distributors/distributors/create_dealer/<?=$account->id?>"><?php echo lang('menu_create_dealer')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_view_own_dealer_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>distributors/distributors/dealer_list/<?=$account->id?>"><?php echo lang('menu_dealers_list')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>            
			<li>&nbsp;</li>			
        </ul>       
        
      </div>
    </div>
</div>


<?php
if($this->authorization->is_permitted('can_distribute_card_among_own_dealers'))
{
?> 
<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_distribution')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_distribute_card_among_own_dealers'))
			{
			?>
            <li><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <a href="<?php echo base_url();?>distribution/distribution/distributor_create_distribution/<?=$account->id?>"><?php echo lang('menu_card_distribution')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>distribution/distribution/my_distribution_list/"><?php echo lang('menu_distribution_list')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>            
			<?php
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>distribution/distribution/my_card_list/"><?php echo lang('menu_my_card_list')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?> 		
        </ul>       
        
      </div>
    </div>
</div>
<?php 
}
?>



</div>
</div>

</div>
<?php echo $this->load->view('footer'); ?>