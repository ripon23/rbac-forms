<?php
class Prepaid extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('account/account_model','project_site/site_model','general_model'));
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('menu', 'english');
		$this->lang->load('card', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('menu', $language);
		$this->lang->load('card', $language);
		}
		
	}

	function index()
	{
		$response["success"] = 0;	
		$response["message"] = "Wrong API URL";
		echo json_encode($response);
	}
	
	
	public function card_validation()
	{
	$api_key=$this->input->post('api_key');
	$pin=$this->input->post('pin');	
	
		if($api_key==$this->config->item("api_key")) 
		{
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$key=$this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);
				$cipherText = $this->encrypt($pin, $key);

				$cardquery="SELECT * FROM apninv_card_inventory WHERE card_pin='".$cipherText."'";
				$result_row=$this->general_model->get_all_single_row_querystring($cardquery);

				if($result_row)
				{
					
					if($result_row->active_status==1)
					{
					// Card active valid for recharge
					$response["card_info"] = array(); 
					$profile = array();
					
					$response["success"] = 1;
					$response["message"] = "Valid";
					
					if($result_row->card_type==1)
					{
					$card_amount="100";
					$card_type="UV";
					$services_period="180";
					}
					elseif($result_row->card_type==2)
					{
					$card_amount="200";
					$card_type="UV";
					$services_period="365";
					}
					elseif($result_row->card_type==3)
					{
					$card_amount="500";
					$card_type="SP|SB";
					$services_period="1000";
					}
					elseif($result_row->card_type==4)
					{
					$card_amount="60";
					$card_type="UV";
					$services_period="90";
					}
					
					
					$profile["type"] = $card_type;
					$profile["amount"] = $card_amount;
					$profile["service_period"] = $services_period;
					//$profile["serial"] = $result_row->card_serial;
					array_push($response["card_info"], $profile);					
					echo json_encode($response);					
					}
					elseif($result_row->active_status==0)
					{
					// Card inactive
					$response["success"] = 0;	
					$response["message"] = "Inactive";
					echo json_encode($response);
						
					}
					elseif($result_row->active_status==2)
					{
					// Card already recharged
					$response["success"] = 0;	
					$response["message"] = "Recharged";
					echo json_encode($response);
					
					}
					
				}
				else
				{
				$response["success"] = 0;	
				$response["message"] = "Invalid";
				echo json_encode($response);
				}										
			
		}
		else
		{
		$response["success"] = 0;	
		$response["message"] = "Wrong API KEY";
		echo json_encode($response);
		exit();
		}
	
	}
	
	
	public function card_recharge()
	{
	$api_key=$this->input->post('api_key');
	$mobile_no=$this->input->post('mobile');
	$pin=$this->input->post('pin');	
	
		if($api_key==$this->config->item("api_key")) 
		{
			
			if(preg_match("/^(01\d{9})$/", $mobile_no))
			{			
			// Check the number is block or not
			$block_query="Select * FROM  apninv_recharge_attempt WHERE msisdn='".$mobile_no."' AND is_block=1";
			$block_result_row=$this->general_model->get_all_single_row_querystring($block_query);
			
			if($block_result_row)
			{
			$last_attempt_time=$block_result_row->last_attempt_datetime;
			$current_time=mdate('%Y-%m-%d %H:%i:%s', now());
			
			$t1 = strtotime($current_time);
			$t2 = strtotime($last_attempt_time);
			$diff = $t1 - $t2;
			$hours = $diff / ( 60 * 60 );
			//echo $hours;						
				if($hours<=$this->config->item("block_hour"))
				{
					$block=1;
				}
				else
				{
					$block=0;
				}
			}
			else
			{
			$block=0;	
			}
			
			//echo $block;
			
			if($block==0)
			{
				
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$key=$this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);
				$cipherText = $this->encrypt($pin, $key);

				$cardquery="SELECT * FROM apninv_card_inventory WHERE card_pin='".$cipherText."'";
				$result_row=$this->general_model->get_all_single_row_querystring($cardquery);
				
				
				if($result_row)
				{
										
						if($result_row->active_status==1)
						{
						// Card active valid for recharge
						/*********** Block the card in inventory. set the status =2 that is recharged *************/					
						
						$update_data=array(						
							'active_status'=>2,
							'update_user_id'=>999999,											
							'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())																		
							);
						$this->general_model->update_table('apninv_card_inventory', $update_data, 'card_id', $result_row->card_id);
						
						/*********** insert into apninv_recharge_history *************/
						$insert_data=array(						
							'msisdn'=>$mobile_no,
							'card_id'=>$result_row->card_id,
							'recharge_source'=>'api',  	// Using api
							'recharge_by'=>999999,		// 999999 means API									
							'recharge_datetime'=>mdate('%Y-%m-%d %H:%i:%s', now())																		
							);
						$this->general_model->save_into_table('apninv_recharge_history', $insert_data);
						
						/*********** insert into apninv_action_log *************/
						$table_data=array(						
							'action_name'=>$this->config->item("card_recharge"),
							'action_perform_by'=>999999, // 999999 means API											
							'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
							'action_details'=>"Recharge card, Serial:$result_row->card_serial"												
							);
						$this->general_model->save_into_table('apninv_action_log', $table_data);					
						
						// Success message
						if($result_row->card_type==1)
						{
						$card_amount="100";
						$card_type="UV";
						$services_period="180";
						}
						elseif($result_row->card_type==2)
						{
						$card_amount="200";
						$card_type="UV";
						$services_period="365";
						}
						elseif($result_row->card_type==3)
						{
						$card_amount="500";
						$card_type="SP|SB";
						$services_period="1000";
						}
						elseif($result_row->card_type==4)
						{
						$card_amount="60";
						$card_type="UV";
						$services_period="90";
						}
						
						$response["card_info"] = array(); 
						$profile = array();					
						$response["success"] = 1;
						$response["message"] = "Successful";
						
						$profile["type"] = $card_type;
						$profile["amount"] = $card_amount;
						$profile["service_period"] = $services_period;						
						$profile["serial"] = $result_row->card_serial;
						array_push($response["card_info"], $profile);					
						echo json_encode($response);					
						}
						elseif($result_row->active_status==0)
						{
						// Card inactive
						$this->recharge_attempt($mobile_no,'API');
						$response["success"] = 0;	
						$response["message"] = "Inactive";
						echo json_encode($response);
							
						}
						elseif($result_row->active_status==2)
						{
						// Card already recharged
						$this->recharge_attempt($mobile_no,'API');
						$response["success"] = 0;	
						$response["message"] = "Recharged";
						echo json_encode($response);					
						}
						
					}
					else
					{
					$this->recharge_attempt($mobile_no,'API');				
					$response["success"] = 0;	
					$response["message"] = "Invalid";
					echo json_encode($response);					
					}										
				}
				else
				{
				$response["success"] = 0;	
				$response["message"] = "Number_block";
				echo json_encode($response);	
				}
			}// Invalid msisdn			
			else
			{
			$response["success"] = 0;	
			$response["message"] = "Mobile number not valid";
			echo json_encode($response);
			exit();				
			}
		}
		else
		{
		$response["success"] = 0;	
		$response["message"] = "Wrong API KEY";
		echo json_encode($response);
		exit();
		}
	
	}
	
	
	public function unblock_msisdn()
	{
		$api_key=$this->input->post('api_key');
		$mobile_no=$this->input->post('mobile');
		$reason=$this->input->post('unblock_reason');
		if(!$reason)
		$reason="Unblock using API (Unknown reason)";
		
		$pin=$this->input->post('pin');	
		
			if($api_key==$this->config->item("api_key")) 
			{
				
				if(preg_match("/^(01\d{9})$/", $mobile_no))
				{												  	
				$cardquery="SELECT * FROM apninv_recharge_attempt WHERE msisdn='".$mobile_no."' AND is_block=1";
				$result_row=$this->general_model->get_all_single_row_querystring($cardquery);
					if($result_row)
					{
					$attempt_count=0;
					$is_block=0;
					$table_data=array(						
						'attempt_count'=>$attempt_count, 
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'is_block'=>$is_block,
						'update_user_id'=>999999, // means API
						'unblock_reason'=>$reason
						);					
					$this->general_model->update_table('apninv_recharge_attempt', $table_data,'msisdn', $mobile_no);		
					$response["success"] = 1;	
					$response["message"] = "Mobile no is now unblock";
					echo json_encode($response);
					exit();	
					}
					else
					{
					$response["success"] = 0;	
					$response["message"] = "This mobile is not in block list";
					echo json_encode($response);
					exit();	
					}
				}// Invalid msisdn			
				else
				{
				$response["success"] = 0;	
				$response["message"] = "Mobile number not valid";
				echo json_encode($response);
				exit();				
				}
					
			}
			else
			{
			$response["success"] = 0;	
			$response["message"] = "Wrong API KEY";
			echo json_encode($response);
			exit();
			}					
	}
	
	
	public function is_block()
	{
		$api_key=$this->input->post('api_key');
		$mobile_no=$this->input->post('mobile');		
		
		$pin=$this->input->post('pin');	
		
			if($api_key==$this->config->item("api_key")) 
			{
				
				if(preg_match("/^(01\d{9})$/", $mobile_no))
				{												  	
				$cardquery="SELECT * FROM apninv_recharge_attempt WHERE msisdn='".$mobile_no."' AND is_block=1";
				$result_row=$this->general_model->get_all_single_row_querystring($cardquery);
					if($result_row)
					{							
					$response["success"] = 1;	
					$response["message"] = "Block";
					echo json_encode($response);
					exit();	
					}
					else
					{
					$response["success"] = 0;	
					$response["message"] = "Unblock";
					echo json_encode($response);
					exit();	
					}
				}// Invalid msisdn			
				else
				{
				$response["success"] = 0;	
				$response["message"] = "Mobile number not valid";
				echo json_encode($response);
				exit();				
				}
					
			}
			else
			{
			$response["success"] = 0;	
			$response["message"] = "Wrong API KEY";
			echo json_encode($response);
			exit();
			}					
	}
	
	
	private function recharge_attempt($msisdn,$source)
	{
	/*********** insert into apninv_action_log *************/
	$query="Select * FROM  apninv_recharge_attempt WHERE msisdn='".$msisdn."'";
	$result_row=$this->general_model->get_all_single_row_querystring($query);
	if($result_row)
		{
		$last_attempt_time=$result_row->last_attempt_datetime;
		$current_time=mdate('%Y-%m-%d %H:%i:%s', now());
		
		$t1 = strtotime($current_time);
		$t2 = strtotime($last_attempt_time);
		$diff = $t1 - $t2;
		$hours = $diff / ( 60 * 60 );
		//echo $hours;
			if($hours<=$this->config->item("block_hour"))
			{
				//attempt_count need to increases by 1
				if($result_row->attempt_count>=$this->config->item("allow_recharge_attempt"))
				{
				$is_block=1;
				}
				else
				{
				$is_block=0;
				}
			$attempt_count=$result_row->attempt_count+1;
			$table_data=array(						
				'attempt_count'=>$attempt_count, 
				'last_attempt_datetime'=>mdate('%Y-%m-%d %H:%i:%s', now()),
				'is_block'=>$is_block,
				'source'=>$source
				);
			$this->general_model->update_table('apninv_recharge_attempt', $table_data,'msisdn', $msisdn);			
			}
			else
			{
			// After the time period reset the counter and block	
			$attempt_count=1;
			$is_block=0;
			$table_data=array(						
				'attempt_count'=>$attempt_count, 
				'last_attempt_datetime'=>mdate('%Y-%m-%d %H:%i:%s', now()),
				'is_block'=>$is_block,
				'source'=>$source
				);
			$this->general_model->update_table('apninv_recharge_attempt', $table_data,'msisdn', $msisdn);	
			}
		}
		else
		{
		// Insert for first time	
		$table_data=array(						
			'msisdn'=>$msisdn,
			'attempt_count'=>1, 
			'last_attempt_datetime'=>mdate('%Y-%m-%d %H:%i:%s', now()),
			'is_block'=>0,
			'source'=>$source
			);
		$this->general_model->save_into_table('apninv_recharge_attempt', $table_data);
		}
	
	}
		
	private function encryptionKey($username, $password, $ivseed = "!!!") {
    $username = strtolower($username);
    return array(hash("sha1", $password.$username), hash("sha1", $username . $ivseed));
	}
	
	private function encrypt($data, $key) {
		return
				trim( base64_encode( mcrypt_encrypt(
						MCRYPT_RIJNDAEL_256,
						substr($key[0],0,32),
						$data,
						MCRYPT_MODE_CBC,
						substr($key[1],0,32)
				)));
		}
	
	public function decrypt($data, $key) {
				return
						mcrypt_decrypt(
								MCRYPT_RIJNDAEL_256,
								substr($key[0],0,32),
								base64_decode($data),
								MCRYPT_MODE_CBC,
								substr($key[1],0,32)
						);
	}

}


/* End of file home.php */
/* Location: ./system/application/controllers/api/prepaid.php */