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

<?php
if($this->authorization->is_permitted('can_view_org_list'))
{
?> 
<div class="col-md-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_organizations')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_view_org_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>organizations/organizations/org_list"><?php echo lang('menu_organizations')." ".lang('list')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_create_org'))
			{
			?> 
			<li><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <a href="<?php echo base_url();?>organizations/organizations/create_org"><?php echo lang('menu_create_org')?> </a></li>
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
}
?>


<?php
if($this->authorization->is_permitted('can_download_card_info'))
{
?> 
<div class="col-md-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_prepaid-card-inventory')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_download_card_info'))
			{
			?>
            <li><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> <a href="<?php echo base_url();?>card-generation/card_generation/download_card"><?php echo lang('menu_prepaid-card-download')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
            <?php
			if($this->authorization->is_permitted('can_activate_card'))
			{
			?>
            <li><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <a href="<?php echo base_url();?>card-generation/card_generation/card_activation_deactivation"><?php echo lang('menu_prepaid-card-activation')?> </a></li>
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
}
?>

<?php
if($this->authorization->is_permitted('can_view_block_list'))
{
?> 
<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_block_list')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">        	
        	<?php
			if($this->authorization->is_permitted('can_unblock_msisdn'))
			{
			?>
            <li><span class="glyphicon glyphicon-check" aria-hidden="true"></span> <a href="<?php echo base_url();?>recharge/recharge_card/unblock_msisdn/"><?php echo lang('menu_unblock_msisdn')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
            <?php
			if($this->authorization->is_permitted('can_view_block_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> <a href="<?php echo base_url();?>recharge/recharge_card/block_list/"><?php echo lang('menu_block_list')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
            <?php
			//if($this->authorization->is_permitted('can_recharge_card'))
			//{
			?>
            <!--<li><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> <a href="<?php echo base_url();?>recharge/recharge_card/"><?php //echo lang('menu_card_recharge')?> </a></li>-->
            <?php
			//}
			//else
			echo "<li>&nbsp;</li>";
			?>
        </ul>       
        
      </div>
    </div>
</div>
<?php 
}
?>


<?php
if($this->authorization->is_permitted('can_view_distributors_list'))
{
?> 
<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_distributors_management')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_create_distributors'))
			{
			?>
            <li><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <a href="<?php echo base_url();?>distributors/distributors/create_distributors/"><?php echo lang('menu_create_distributors')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_view_distributors_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>distributors/distributors/distributors_list/"><?php echo lang('menu_distributors_list')?> </a></li>
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
}
?>

<?php
if($this->authorization->is_permitted('can_view_distributed_card_list'))
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
			if($this->authorization->is_permitted('can_distribute_card_among_distributors'))
			{
			?>
            <li><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <a href="<?php echo base_url();?>distribution/distribution/create_distribution/"><?php echo lang('menu_card_distribution')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_view_distributed_card_list'))
			{
			?>
            <li><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <a href="<?php echo base_url();?>distribution/distribution/distribution_list/"><?php echo lang('menu_distribution_list')?> </a></li>
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
}
?>

<?php
if($this->authorization->is_permitted('can_see_recharge_history'))
{
?> 
<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_recharge_history')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_see_recharge_history'))
			{
			?>
            <li><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> <a href="<?php echo base_url();?>recharge/recharge_card/recharge_history/"><?php echo lang('menu_recharge_history')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<li>&nbsp;</li>
			<li>&nbsp;</li>
        </ul>       
        
      </div>
    </div>
</div>
<?php 
}
?>


<?php
if($this->authorization->is_permitted('can_see_report_panel'))
{
?> 
<div class="col-md-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><?=lang('menu_reports')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
        	<?php
			if($this->authorization->is_permitted('can_see_inventory_summary_report'))
			{
			?>
            <li><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> <a href="<?php echo base_url();?>reports/reports/inventory_summary_report/"><?php echo lang('menu_inventory_summary_report')?> </a></li>
            <?php
			}
			else
			echo "<li>&nbsp;</li>";
			?>
        	<?php
			if($this->authorization->is_permitted('can_see_distributor_card_summary'))
			{
			?>
            <li><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> <a href="<?php echo base_url();?>reports/reports/distributor_card_summary/"><?php echo lang('menu_distribution_summary_report')?> </a></li>
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
}
?>


<?php
if($this->authorization->is_permitted('cms_view_news'))
{
?>
<div class="col-md-4">
    <div class="panel panel-success">
      <div class="panel-heading ">
        <h3 class="panel-title"><?php echo lang('menu_news_mangement')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            <li><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/news"><?php echo lang('menu_news_list')?> </a></li>
            <li><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/news/save"> <?php echo lang('menu_news_add')?> </a></li>
            <li>&nbsp;</li>           
            
        </ul>
      </div>
    </div>
</div>
<?php 
}
?>

<?php
if($this->authorization->is_permitted('cms_view_page'))
{
?>
<div class="col-md-4">
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo lang('menu_page_mangement')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            <li><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/pages"><?php echo lang('menu_page_list')?> </a></li>
            <li><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/pages/save"> <?php echo lang('menu_page_add')?> </a></li>
            <li>&nbsp;</li>         
            
        </ul>
      </div>
    </div>
</div>
<?php 
}
?>

<?php
if($this->authorization->is_permitted('cms_view_gallery'))
{
?>
<div class="col-md-4">
    <div class="panel panel-success">
      <div class="panel-heading ">
        <h3 class="panel-title"><?php echo lang('menu_gallery_mangement')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            <li><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/gallery"><?php echo lang('menu_gallery_list')?> </a></li>
            <li><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/gallery/save"> <?php echo lang('menu_gallery_add')?> </a></li>
            <li>&nbsp;</li>           
            
        </ul>
      </div>
    </div>
</div>
<?php 
}
?>
<?php
if($this->authorization->is_permitted('cms_view_slide'))
{
?>
<div class="col-md-4">
    <div class="panel panel-success">
      <div class="panel-heading ">
        <h3 class="panel-title"><?php echo lang('menu_slide_mangement')?></h3>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
            <li><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/slider"><?php echo lang('menu_slide_list')?> </a></li>
            <li><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <a href="<?php echo base_url();?>cms/slider/save"> <?php echo lang('menu_slide_add')?> </a></li>
            <li>&nbsp;</li>           
            
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
