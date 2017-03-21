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
		
		
	function fnExcelReport()
	{		
		var tab_text = '<table border="1px" style="font-size:16px" ">';
		var textRange; 
		var j = 0;
		var tab = document.getElementById('DataTableId'); // id of table
		var lines = tab.rows.length;
	
		// the first headline of the table
		if (lines > 0) {
			tab_text = tab_text + '<tr bgcolor="#DFDFDF">' + tab.rows[0].innerHTML + '</tr>';
		}
	
		// table data lines, loop starting from 1
		for (j = 1 ; j < lines; j++) {     
			tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
		}
	
		tab_text = tab_text + "</table>";
		tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");             //remove if u want links in your table
		tab_text = tab_text.replace(/<img[^>]*>/gi,"");                 // remove if u want images in your table
		tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");    // reomves input params
		// console.log(tab_text); // aktivate so see the result (press F12 in browser)
	
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE "); 
	
		 // if Internet Explorer
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
			txtArea1.document.open("txt/html","replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus(); 
			sa = txtArea1.document.execCommand("SaveAs", true, "aponjon_inventory_summary.xls");
		}  
		else // other browser not tested on IE 11
			sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
	
		return (sa);
	} 	
	</script>
</head>
<body>
<?php echo $this->load->view('header',array('active' => '#')); ?>
<ol class="breadcrumb">
  <li><a href="<?=base_url()?>"><?=lang('menu_home')?></a></li>
  <li class="active"><?=lang('menu_inventory_summary_report')?></li>
</ol>

<legend class="text-center"><?=lang('menu_inventory_summary_report')?> </legend>  
<form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./reports/reports/inventory_summary_report_search" method="post">   
	
    <div class="col-md-12">
    <table class="table table-bordered">     
        <td>
        <div class="form-inline">        
        <input id="date_from" name="date_from" type="text" placeholder="<?=lang('create_date')?> <?=lang('from')?> (YYYY-MM-DD)" value="<?php echo isset($date_from)?$date_from:'';?>" class="form-control input-sm" size="40"> <input id="date_to" name="date_to" type="text" placeholder="<?=lang('create_date')?> <?=lang('to')?> (YYYY-MM-DD)" value="<?php echo isset($date_to)?$date_to:'';?>" class="form-control input-sm" size="40">
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
			<?php if ($this->authorization->is_permitted('can_download_inventory_summary_report')) : ?> 
            
            <button id="btnExport" onclick="fnExcelReport();" class="btn btn-info btn-xs"> <?=lang('action_download')?> </button>
            <?php endif; ?>                    
        </div> 
    <table class="table table-bordered table-striped" id="DataTableId">
    	<tr class="warning">
            <th><?=lang('card-type')?></th>
            <th><?=lang('inactive')?></th>
            <th><?=lang('active')?></th>
            <th><?=lang('recharged')?></th>
            <th><?=lang('total')?></th>
      	</tr>
     	<?php 
		if( !empty($all_card_type) ) {
			
			$result_0_total=0;
			$result_1_total=0;
			$result_2_total=0;
			$result_00_total=0;
			$result_11_total=0;
			$result_22_total=0;
			
			foreach($all_card_type as $card) : 
		?>
        <tr>
        	<td><?=$card->card_display_name?></td>
            <td>
            <?php
            $query_0="SELECT count(*) as total_rows  FROM apninv_card_inventory apninv_card_inventory WHERE (active_status = 0 AND card_type=".$card->card_id.")";
			if($this->input->post("date_from") && $this->input->post("date_to"))	
			{
			$date_from=$this->input->post("date_from"); 
			$date_to=$this->input->post("date_to"); 
			$query_0=$query_0." AND DATE(create_date) between '$date_from' AND '$date_to'";
			}
			$result_0=$this->general_model->count_total_rows($query_0);
			if($result_0) { echo $result_0; $result_0_total=$result_0_total+$result_0; $result_00_total=$result_0;} else echo "0";
			
			?>
            </td>
            <td>
            <?php
            $query_1="SELECT count(*) as total_rows  FROM apninv_card_inventory apninv_card_inventory WHERE (active_status = 1 AND card_type=".$card->card_id.")";	
			if($this->input->post("date_from") && $this->input->post("date_to"))	
			{
			$date_from=$this->input->post("date_from"); 
			$date_to=$this->input->post("date_to"); 
			$query_1=$query_1." AND DATE(create_date) between '$date_from' AND '$date_to'";
			}
			$result_1=$this->general_model->count_total_rows($query_1);
			if($result_1){ echo $result_1; $result_1_total=$result_1_total+$result_1; $result_11_total=$result_1;}else echo "0";
			
			?>
            </td>
            <td>
            <?php
            $query_2="SELECT count(*) as total_rows  FROM apninv_card_inventory apninv_card_inventory WHERE (active_status = 2 AND card_type=".$card->card_id.")";
			if($this->input->post("date_from") && $this->input->post("date_to"))	
			{
			$date_from=$this->input->post("date_from"); 
			$date_to=$this->input->post("date_to"); 
			$query_2=$query_2." AND DATE(create_date) between '$date_from' AND '$date_to'";
			}
			$result_2=$this->general_model->count_total_rows($query_2);
			if($result_2) { echo $result_2; $result_2_total=$result_2_total+$result_2; $result_22_total=$result_2;} else echo "0";
						
			?>
            </td>
            <th><?php echo $result_00_total+$result_11_total+$result_22_total;?></th>
        </tr>
        <?php
			$result_00_total=0;
			$result_11_total=0;
			$result_22_total=0;
			endforeach; //end if
		?>
        <tr>
        	<th><?=lang('select_all')?></th>
            <th><?=$result_0_total?></th>
            <th><?=$result_1_total?></th>
            <th><?=$result_2_total?></th>
            <th><?php echo $result_0_total+$result_1_total+$result_2_total;?></th>
        </tr>
        <?php
		}
		else
		{
		?>
         <tr>
         	<td colspan="5">
			<?php	
            echo lang('no-data-found');            
            ?>   
        	</td>
        </tr>
        <?php  
		}
		?>
    </table>
    </div>


</div>	

<?php echo $this->load->view('footer'); ?>
