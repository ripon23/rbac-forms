<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=aponjon_card_info-".date('Ymd').".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
    	<tr class="warning">
        	<td><?=lang('card-serial')?></td>
            <td><?=lang('card-pin')?></td>
            <td><?=lang('card-type')?></td>           
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

