<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=distribution_report-".date('Ymd').".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table class="table table-bordered table-striped">
    	<tr class="warning">
            <th><?=lang('distributor_code')?></th>
            <th><?=lang('distributor_name')?></th>
            <th><?=lang('mobile_no')?></th>
            <th><?=lang('district')?></th>          
            <th><?=lang('60_tk')?></th>
            <th><?=lang('100_tk')?></th>
            <th><?=lang('200_tk')?></th>
            <th><?=lang('500_tk')?></th>
            <th><?=lang('total')?></th>
            <th><?=lang('recharged')?></th>
      	</tr>
     	<?php
		//echo "<pre>";
		//print_r($result_all_distributor);
		//echo "</pre>";
		
		if( !empty($result_all_distributor) ) {
						
		foreach($result_all_distributor as $distributor_info) : 
		?>
        <tr>
        	<td><?=$distributor_info->distributor_code?></td>
            <td><?=$distributor_info->distributor_name?></td>
            <td><?=$distributor_info->distributor_mobile?></td>
            <td><?=$distributor_info->district_name?></td>
            <td><?=$distributor_info->tk_60?></td>
            <td><?=$distributor_info->tk_100?></td>
            <td><?=$distributor_info->tk_200?></td>
            <td><?=$distributor_info->tk_500?></td>
            <td><?=$distributor_info->total_card?></td>
            <td><?=$distributor_info->recharge_card_count?></td>
        </tr>        
        <?php
		endforeach; //end if
		}
		else
		{
		?>
         <tr>
         	<td colspan="11">
			<?php	
            echo lang('no-data-found');            
            ?>   
        	</td>
        </tr>
        <?php  
		}
		?>
    </table>

