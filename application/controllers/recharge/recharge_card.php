<?php
class Recharge_card extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('account/account_model','project_site/site_model'));
		
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
		maintain_ssl();		
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_recharge_card'))
			{
				
				$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
				$this->form_validation->set_rules(
				  array(
					array(
					  'field' => 'mobile_no',
					  'label' => 'Mobile number',
					  'rules' => 'required|min_length[11]|max_length[11]'),
					array(
					  'field' => 'card_pin', 
					  'label' => 'Card Pin', 
					  'rules' => 'required|min_length[8]|max_length[8]')
				  ));
				  
				// Run form validation
				if ($this->form_validation->run() == FALSE)
				{				
				$data['title'] = lang('menu_card_recharge');								
				$this->load->view('recharge/view_card_recharge', isset($data) ? $data : NULL);
				}
				else
				{
				$mobile_no=$this->input->post('mobile_no');
				$card_pin=$this->input->post('card_pin');
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$key=$this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);
				$cipherText = $this->encrypt($card_pin, $key);

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
						'update_user_id'=>$data['account']->id,											
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())																		
						);
					$this->general_model->update_table('apninv_card_inventory', $update_data, 'card_id', $result_row->card_id);
					
					/*********** insert into apninv_recharge_history *************/
					$insert_data=array(						
						'msisdn'=>$mobile_no,
						'card_id'=>$result_row->card_id,
						'recharge_source'=>'inv',  // Using inventory s/w
						'recharge_by'=>$data['account']->id,											
						'recharge_datetime'=>mdate('%Y-%m-%d %H:%i:%s', now())																		
						);
					$this->general_model->save_into_table('apninv_recharge_history', $insert_data);
					
					/*********** insert into apninv_action_log *************/
					$table_data=array(						
						'action_name'=>$this->config->item("card_recharge"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"Recharge card, Serial:$result_row->card_serial"												
						);
					$this->general_model->save_into_table('apninv_action_log', $table_data);
					
					/*********************************************************************************************/
					/****************************************  API CALL ******************************************/
					/*********************************************************************************************/
					
					//API Code
					
					/*********************************************************************************************/
					
					
					$data['success_msg']="Your recharge is successful";
					}
					elseif($result_row->active_status==0)
					{
					// Card inactive
					$data['error_msg']="This card number is not active for recharge";
						
					}
					elseif($result_row->active_status==2)
					{
					// Card already recharged
					$data['error_msg']="$card_pin number is already recharged";
					
					}
					
				}
				else
				{
				$data['error_msg']="You input a invalid card number";	
				}
				
								
				$this->load->view('recharge/view_card_recharge', isset($data) ? $data : NULL);
				}
				
				
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard
			
			}	
		}		
		else
		{
		redirect('account/sign_in');
		}
	}
	
		
	public function unblock_msisdn()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_unblock_msisdn'))
			{
				
				$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
				$this->form_validation->set_rules(
				  array(
					array(
					  'field' => 'mobile_no',
					  'label' => 'Mobile number',
					  'rules' => 'required|min_length[11]|max_length[11]'),
					array(
					  'field' => 'unblock_reason', 
					  'label' => 'Unblock reason', 
					  'rules' => 'required')
				  ));
				  
				// Run form validation
				if ($this->form_validation->run() == FALSE)
				{				
				$data['title'] = lang('menu_unblock_msisdn');								
				$this->load->view('recharge/view_unblock_msisdn', isset($data) ? $data : NULL);
				}
				else
				{
				$mobile_no=$this->input->post('mobile_no');
				$reason=$this->input->post('unblock_reason');	
				$cardquery="SELECT * FROM apninv_recharge_attempt WHERE msisdn='".$mobile_no."'";
				$result_row=$this->general_model->get_all_single_row_querystring($cardquery);
					if($result_row)
					{
					$attempt_count=0;
					$is_block=0;
					$table_data=array(						
						'attempt_count'=>$attempt_count, 
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'is_block'=>$is_block,
						'update_user_id'=>$data['account']->id,
						'unblock_reason'=>$reason
						);
					$data['success_msg']="$mobile_no mobile no is now unblock";
					$this->general_model->update_table('apninv_recharge_attempt', $table_data,'msisdn', $mobile_no);		
					}
					else
					{
					$data['error_msg']="$mobile_no mobile is not in block list";
					}
				$this->load->view('recharge/view_unblock_msisdn', isset($data) ? $data : NULL);
				}
				
				
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard
			
			}	
		}		
		else
		{
		redirect('account/sign_in');
		}
	
	}

	public function block_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_view_block_list'))
			{
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_block_list');	
							
			
 		  	$searchterm='SELECT * FROM apninv_recharge_attempt WHERE is_block=1 Order by attempt_id Desc';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "recharge/recharge_card/block_list/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			

			$this->pagination->initialize($config);
			
 
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['block_list'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
			$data['total_records']=$config["total_rows"];
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;					
			$this->load->view('recharge/view_block_list', isset($data) ? $data : NULL);				
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard
			
			}	
		}		
		else
		{
		redirect('account/sign_in');
		}		
	}
	
	
	public function block_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_view_block_list'))
			{
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_block_list');	
							
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['msisdn']    	= $this->input->post('msisdn');
				
 		  		$query_string='SELECT * FROM apninv_recharge_attempt WHERE is_block=1';
				
					if($this->input->post("msisdn"))	
					{
						$msisdn=$this->input->post("msisdn"); 
						$query_string=$query_string." AND (msisdn='$msisdn')";
					}
					   																		
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "recharge/recharge_card/block_list/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			

			$this->pagination->initialize($config);
			
 
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['block_list'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
			$data['total_records']=$config["total_rows"];
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;					
			$this->load->view('recharge/view_block_list', isset($data) ? $data : NULL);				
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard
			
			}	
		}		
		else
		{
		redirect('account/sign_in');
		}		
	}
	
	public function recharge_history_search(){
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_view_block_list'))
			{
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_recharge_history');
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['msisdn']    	= $this->input->post('msisdn');
				$data['card_serial']= $this->input->post('card_serial');
				$data['card_type']    	= $this->input->post('card_type');
				
 		  		$query_string='SELECT apninv_card_inventory.card_id,
					   apninv_card_inventory.card_serial,
					   apninv_card_inventory.card_pin,
					   apninv_card_inventory.card_type,
					   apninv_recharge_history.msisdn,
					   apninv_recharge_history.card_id,
					   apninv_recharge_history.recharge_source,
					   apninv_recharge_history.recharge_datetime,
					   apninv_recharge_history.recharge_by,
					   apninv_card_inventory.active_status
				  FROM apninv_card_inventory apninv_card_inventory
					   INNER JOIN apninv_recharge_history apninv_recharge_history
						  ON (apninv_card_inventory.card_id = apninv_recharge_history.card_id)
				 WHERE (apninv_card_inventory.active_status = 2)';
				
					if($this->input->post("msisdn"))	
					{
						$msisdn=$this->input->post("msisdn"); 
						$query_string=$query_string." AND (msisdn='$msisdn')";
					}
					 
					if($this->input->post("card_serial"))	
					{
						$card_serial=$this->input->post("card_serial"); 
						$query_string=$query_string." AND (card_serial=$card_serial)";
					}
					 
					if($this->input->post("card_type"))	
					{
						$card_type=$this->input->post("card_type"); 
						$query_string=$query_string." AND (card_type=$card_type)";
					}
				
				$query_string=$query_string." ORDER BY recharge_datetime DESC";
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
							
			
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "recharge/recharge_card/recharge_history_search/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			

			$this->pagination->initialize($config);
			
 
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['recharge_list'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
			$data['total_records']=$config["total_rows"];
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;					
			$this->load->view('recharge/view_recharge_history', isset($data) ? $data : NULL);
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard			
			}	
		}		
		else
		{
		redirect('account/sign_in');
		}
	
	
	}
	
	public function recharge_history(){
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_see_recharge_history'))
			{
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_recharge_history');
						
			$searchterm="SELECT apninv_card_inventory.card_id,
					   apninv_card_inventory.card_serial,
					   apninv_card_inventory.card_pin,
					   apninv_card_inventory.card_type,
					   apninv_recharge_history.msisdn,
					   apninv_recharge_history.card_id,
					   apninv_recharge_history.recharge_source,
					   apninv_recharge_history.recharge_datetime,
					   apninv_recharge_history.recharge_by,
					   apninv_card_inventory.active_status
				  FROM apninv_card_inventory apninv_card_inventory
					   INNER JOIN apninv_recharge_history apninv_recharge_history
						  ON (apninv_card_inventory.card_id = apninv_recharge_history.card_id)
				 WHERE (apninv_card_inventory.active_status = 2) ORDER BY recharge_datetime DESC";
			
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "recharge/recharge_card/recharge_history/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			

			$this->pagination->initialize($config);
			
 
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['recharge_list'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
			$data['total_records']=$config["total_rows"];
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;					
			$this->load->view('recharge/view_recharge_history', isset($data) ? $data : NULL);
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted redirect to  dashboard			
			}	
		}		
		else
		{
		redirect('account/sign_in');
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
/* Location: ./system/application/controllers/recharge_card.php */