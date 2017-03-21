<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=aponjon_inventory_summary-".date('Ymd').".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
    	<tr>
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
			$result_0=$this->general_model->count_total_rows($query_0);
			if($result_0) { echo $result_0; $result_0_total=$result_0_total+$result_0; $result_00_total=$result_0;} else echo "0";
			
			?>
            </td>
            <td>
            <?php
            $query_1="SELECT count(*) as total_rows  FROM apninv_card_inventory apninv_card_inventory WHERE (active_status = 1 AND card_type=".$card->card_id.")";			
			$result_1=$this->general_model->count_total_rows($query_1);
			if($result_1){ echo $result_1; $result_1_total=$result_1_total+$result_1; $result_11_total=$result_1;}else echo "0";
			
			?>
            </td>
            <td>
            <?php
            $query_2="SELECT count(*) as total_rows  FROM apninv_card_inventory apninv_card_inventory WHERE (active_status = 2 AND card_type=".$card->card_id.")";			
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

