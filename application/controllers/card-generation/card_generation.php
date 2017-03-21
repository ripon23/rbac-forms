<?php
class Card_generation extends CI_Controller {

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
			if($this->authorization->is_permitted('can_generate_prepaid_card'))
			{
				
				$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
				$this->form_validation->set_rules(
				  array(
					array(
					  'field' => 'card_type',
					  'label' => 'Card type',
					  'rules' => 'required'),
					array(
					  'field' => 'number_of_card_to_be_generate', 
					  'label' => 'Number of card to be generate', 
					  'rules' => 'required')
				  ));
				  
				// Run form validation
				if ($this->form_validation->run())
				{
				$number_of_card_to_be_generate=$this->input->post('number_of_card_to_be_generate');
				$card_type=$this->input->post('card_type');
				if($card_type=='100')
				$card_type_int=1;
				elseif($card_type=='200')
				$card_type_int=2;
				elseif($card_type=='500')
				$card_type_int=3;
				elseif($card_type=='60')
				$card_type_int=4;
				else
				{
				echo "Invalid card type";	
				die();
				}
				
				$disAllowNumber=array('10000000','11111111','22222222','33333333','44444444','55555555','66666666','77777777','88888888','99999999','20000000','30000000','40000000','50000000','60000000','70000000','80000000','90000000');
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$key = $this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);
				$query="SELECT MAX(card_serial) as max_serial FROM apninv_card_inventory";
				$response_row=$this->general_model->get_all_single_row_querystring($query);
				$serial_start=$response_row->max_serial;
				
				
				if($serial_start<$this->config->item("card_serial_start_number"))
				$serial_start=$this->config->item("card_serial_start_number");				
				
				
				for($i=1;$i<=$number_of_card_to_be_generate;$i++)
				{
				$cardNumber=mt_rand(10000000, 99999999);

					if(in_array($cardNumber, $disAllowNumber))
					{					
					echo "The $cardNumber number is in disallowed list<br/>";					
					}
					else
					{	
					$cipherText = $this->encrypt($cardNumber, $key);
					//$decryptedText = $this->decrypt($cipherText, $key);
					$sql="SELECT * FROM apninv_card_inventory WHERE card_pin='$cipherText' OR 	card_serial=$serial_start+$i";
					$response=$this->general_model->is_exist_in_a_table_querystring($sql);
						if(!$response)
						{
						// Database save array	
						$cardarray[] = array(
							'card_serial' => $serial_start+$i,
							'card_pin' => $cipherText,
							'card_type'=> $card_type_int,
							'active_status'=> 0,
							'create_user_id'=> $data['account']->id,
							'create_date'=> mdate('%Y-%m-%d %H:%i:%s', now())
						);
												
						}
											
					
					}
				}
				$success_or_fail=$this->general_model->save_into_table_batch('apninv_card_inventory', $cardarray);
				
					if($success_or_fail>0)
					{
					$total_number_generate=count($cardarray);
					$serial_from=$serial_start+1;
					$serial_to=($serial_start+$i)-1;
					$data['total_number_generate']=$total_number_generate;
					$data['serial_from']=$serial_from;
					$data['serial_to']=$serial_to;
					/********** Insert into log table *************/
					$table_data=array(						
						'action_name'=>$this->config->item("card_generation_action_name"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"Total Card Generated:$total_number_generate, Serial Number From $serial_from To $serial_to"												
						);
					$this->general_model->save_into_table('apninv_action_log', $table_data);					
					$this->load->view('card-generation/view_card_generation_summary', isset($data) ? $data : NULL);
					}
												
				}
				else
				{
				$this->load->view('card-generation/view_card_generation', isset($data) ? $data : NULL);
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
	
	public function card_activation()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_activate_card'))
			{			
			
			$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
				$this->form_validation->set_rules(
				  array(
					array(
					  'field' => 'card_serial_from',
					  'label' => 'Card serial from',
					  'rules' => 'required'),
					array(
					  'field' => 'card_serial_to', 
					  'label' => 'Card serial to', 
					  'rules' => 'required')
				  ));
				  
				// Run form validation
				if ($this->form_validation->run())
				{
			
			$this->load->helper("url");	
			$data['title'] = lang('menu_prepaid-card-activation');
			$data['card_serial_from']=$this->input->post('card_serial_from');
			$data['card_serial_to']=$this->input->post('card_serial_to');
			$data['card_type']=$this->input->post('card_type');
			$data['card_from']=$this->input->post('card_from');
			$data['card_to']=$this->input->post('card_to');
			
			$query_string='UPDATE apninv_card_inventory SET active_status=1 Where active_status=0';
				
				if($this->input->post("card_serial_from") && ($this->input->post("card_serial_to")))	
				{										
					$card_serial_from=$this->input->post('card_serial_from');
					$card_serial_to=$this->input->post('card_serial_to');
					$query_string=$query_string." AND card_serial>=$card_serial_from AND card_serial<=$card_serial_to";
					$details_text="Serial from $card_serial_from to $card_serial_to";
				}
				
				if($this->input->post("card_type"))	
				{
					$card_type=$this->input->post("card_type"); 
					$query_string=$query_string." AND (card_type= '$card_type')";
					$details_text=$details_text." Card Type $card_type";
				}
				
				if($this->input->post("date_from") && $this->input->post("date_to"))	
				{
					$date_from=$this->input->post("date_from"); 
					$date_to=$this->input->post("date_to"); 
					$query_string=$query_string." AND DATE(create_date) between '$date_from' AND '$date_to'";
					$details_text=$details_text." Date from $date_from to $date_to";
				}
				
			$affected_rows=$this->general_model->update_querystring($query_string);
				if($affected_rows>0)
				{
				$data['success_msg']=$affected_rows." Card activated";	
				
				/********** Insert into log table(apninv_action_log) *************/
				$table_data=array(		
						'action_name'=>$this->config->item("card_activation"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"$affected_rows Card activated. $details_text"												
						);
				$this->general_model->save_into_table('apninv_action_log', $table_data);
				}
				else
				{
				$data['error_msg']=	"No Card activated";
				}
			
			$this->load->view('card-generation/prepaid_card_activation', isset($data) ? $data : NULL);
			}// Validation
			else
			{
			$this->load->view('card-generation/prepaid_card_activation', isset($data) ? $data : NULL);	
			//echo "Invalid";	
			}
			
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "card_activation" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}	
	
	}
	
	public function card_deactivation()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_activate_card'))
			{
				
			$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
				$this->form_validation->set_rules(
				  array(
					array(
					  'field' => 'card_serial_from2',
					  'label' => 'Card serial from',
					  'rules' => 'required'),
					array(
					  'field' => 'card_serial_to2', 
					  'label' => 'Card serial to', 
					  'rules' => 'required')
				  ));
				  
				// Run form validation
				if ($this->form_validation->run())
				{	
			$this->load->helper("url");	
			$data['title'] = lang('menu_prepaid-card-activation');
			$data['card_serial_from']=$this->input->post('card_serial_from2');
			$data['card_serial_to']=$this->input->post('card_serial_to2');
			$data['card_type']=$this->input->post('card_type2');
			$data['card_from']=$this->input->post('card_from2');
			$data['card_to']=$this->input->post('card_to2');
			
			$query_string='UPDATE apninv_card_inventory SET active_status=0 Where active_status=1';
				
				if($this->input->post("card_serial_from2") && ($this->input->post("card_serial_to2")))	
				{										
					$card_serial_from=$this->input->post('card_serial_from2');
					$card_serial_to=$this->input->post('card_serial_to2');
					$query_string=$query_string." AND card_serial>=$card_serial_from AND card_serial<=$card_serial_to";
					$details_text="Serial from $card_serial_from to $card_serial_to";
				}
				
				if($this->input->post("card_type2"))	
				{
					$card_type=$this->input->post("card_type2"); 
					$query_string=$query_string." AND (card_type= '$card_type')";
					$details_text=$details_text." Card Type $card_type";
				}
				
				if($this->input->post("date_from2") && $this->input->post("date_to2"))	
				{
					$date_from=$this->input->post("date_from2"); 
					$date_to=$this->input->post("date_to2"); 
					$query_string=$query_string." AND DATE(create_date) between '$date_from' AND '$date_to'";
					$details_text=$details_text." Date from $date_from to $date_to";
				}
				
			$affected_rows=$this->general_model->update_querystring($query_string);
				if($affected_rows>0)
				{
				$data['success_msg2']=$affected_rows." Card deactivated";	
				
				/********** Insert into log table(apninv_action_log) *************/
				$table_data=array(		
						'action_name'=>$this->config->item("card_deactivation"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"$affected_rows Card deactivated. $details_text"												
						);
				$this->general_model->save_into_table('apninv_action_log', $table_data);
				}
				else
				{
				$data['error_msg2']=	"No Card deactivated";
				}
			
			$this->load->view('card-generation/prepaid_card_activation', isset($data) ? $data : NULL);
			}// Validation
			else
			{
			$this->load->view('card-generation/prepaid_card_activation', isset($data) ? $data : NULL);	
			}
									
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "card_activation" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}	
	
	}
	
	public function card_activation_deactivation()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_activate_card'))
			{			
			$this->load->helper("url");	
			$data['title'] = lang('action_view');
			$this->load->view('card-generation/prepaid_card_activation', isset($data) ? $data : NULL);
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "card_activation" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function card_list()
	{
		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_prepaid_card_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('action_view');	
							
			
 		  	$searchterm='SELECT * FROM apninv_card_inventory Order by card_serial Desc';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "card-generation/card_generation/card_list/";
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
			$data['all_card'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			
			
				if($this->authorization->is_permitted('can_view_prepaid_pin'))
				{
				$data['can_see_pin']=1;
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$data['key']= $this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);				
				}
				else
				{
				$data['can_see_pin']=0;
				}
			
			$this->load->view('card-generation/prepaid_card_list', isset($data) ? $data : NULL);		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	
	}
	
	public function card_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_prepaid_card_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('action_view');	
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['card_serial']    	= $this->input->post('card_serial');
				$data['active_inactive']    = $this->input->post('active_inactive');
				$data['card_type']     		= $this->input->post("card_type");
				$data['date_from']     		= $this->input->post("date_from");
				$data['date_to']     		= $this->input->post("date_to");
				
 		  		$query_string='SELECT * FROM apninv_card_inventory Where card_id > 0';
				
					if($this->input->post("card_serial"))	
					{
						$card_serial=$this->input->post("card_serial"); 
						$query_string=$query_string." AND (card_serial= '$card_serial')";
					}
	   				
					if($this->input->post("active_inactive"))	
					{
						$active_inactive=$this->input->post("active_inactive");
						if($active_inactive=="acitve")
						$active=1;
						elseif($active_inactive=="inactive")
						$active=0;
						elseif($active_inactive=="recharged")
						$active=2;
						
						$query_string=$query_string." AND (active_status= '$active')";
					}
					
					if($this->input->post("card_type"))	
					{
						$card_type=$this->input->post("card_type"); 
						$query_string=$query_string." AND (card_type= '$card_type')";
					}
					
					if($this->input->post("date_from") && $this->input->post("date_to"))	
					{
						$date_from=$this->input->post("date_from"); 
						$date_to=$this->input->post("date_to"); 
						$query_string=$query_string." AND DATE(create_date) between '$date_from' AND '$date_to'";
					}
					
				$query_string=$query_string." Order by card_serial Desc";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}

			//pagination
			$config = array();
			$config["base_url"] = base_url() . "card-generation/card_generation/card_list_search/";
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
			$data['all_card'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['total_records']=$config["total_rows"];
			
				if($this->authorization->is_permitted('can_view_prepaid_pin'))
				{
				$data['can_see_pin']=1;
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$data['key']= $this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);				
				}
				else
				{
				$data['can_see_pin']=0;
				}
			
			$this->load->view('card-generation/prepaid_card_list', isset($data) ? $data : NULL);		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}	
	
	}
	
	public function download_card()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_download_card_info'))
			{			
			$data['title'] = "";
			$this->load->view('card-generation/prepaid_card_download', isset($data) ? $data : NULL);					
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}				
		}
		else
		{
		redirect('account/sign_in');
		}		
	}
	
	public function download_card_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_download_card_info'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('action_view');	
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['card_serial_from']   = $this->input->post('card_serial_from');
				$data['card_serial_to']   	= $this->input->post('card_serial_to');
				$data['active_inactive']    = $this->input->post('active_inactive');
				$data['card_type']     		= $this->input->post("card_type");
				$data['date_from']     		= $this->input->post("date_from");
				$data['date_to']     		= $this->input->post("date_to");
				
 		  		$query_string='SELECT * FROM apninv_card_inventory Where card_id > 0';
				
					if($this->input->post("card_serial_from") && $this->input->post("card_serial_to"))	
					{
						$card_serial_from=$this->input->post("card_serial_from"); 
						$card_serial_to=$this->input->post("card_serial_to"); 
						$query_string=$query_string." AND card_serial>=$card_serial_from AND card_serial<=$card_serial_to";
						//SELECT * FROM `apninv_card_inventory` WHERE card_serial>=100030 AND card_serial<=100050
					}
										
	   				
					if($this->input->post("active_inactive"))	
					{
						$active_inactive=$this->input->post("active_inactive");
						if($active_inactive=="acitve")
						$active=1;
						elseif($active_inactive=="inactive")
						$active=0;
						elseif($active_inactive=="recharged")
						$active=2;
						
						$query_string=$query_string." AND (active_status= '$active')";
					}
					
					if($this->input->post("card_type"))	
					{
						$card_type=$this->input->post("card_type"); 
						$query_string=$query_string." AND (card_type= '$card_type')";
					}
					
					if($this->input->post("date_from") && $this->input->post("date_to"))	
					{
						$date_from=$this->input->post("date_from"); 
						$date_to=$this->input->post("date_to"); 
						$query_string=$query_string." AND DATE(create_date) between '$date_from' AND '$date_to'";
					}
					
				$query_string=$query_string." Order by card_serial ASC";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}

			
			$data['total_records'] = $this->general_model->total_count_query_string($searchterm); 			
			$data['all_card'] = $this->general_model->get_all_querystring_result($searchterm);			
			
				if($this->authorization->is_permitted('can_view_prepaid_pin'))
				{
				$data['can_see_pin']=1;
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$data['key']= $this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);				
				}
				else
				{
				$data['can_see_pin']=0;
				}
			
			$this->load->view('card-generation/prepaid_card_download', isset($data) ? $data : NULL);		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}	
	
	}
	
	public function download_to_excel()
	{
			if($this->authorization->is_permitted('can_download_card_info'))
			{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$query=$this->session->userdata('searchterm');
			$data['all_card'] = $this->general_model->get_all_querystring_result($query);
			$total_number=sizeof($data['all_card']);
				if($this->authorization->is_permitted('can_view_prepaid_pin'))
				{
				$data['can_see_pin']=1;
				$encryptionKey1='aponjon';
				$encryptionKey2='16227';
				$encryptionKey3='dnet';
				$data['key']= $this->encryptionKey($encryptionKey1, $encryptionKey2, $encryptionKey3);				
				}
				else
				{
				$data['can_see_pin']=0;
				}
			
			/********** Insert into log table *************/
			$table_data=array(						
				'action_name'=>$this->config->item("card_download"),
				'action_perform_by'=>$data['account']->id,											
				'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
				'action_details'=>"Total Card Download:$total_number, Query=$query"
				);
			$this->general_model->save_into_table('apninv_action_log', $table_data);
					
			$this->load->view('card-generation/view_card_export_to_excel', isset($data) ? $data : NULL);
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}
	}
	
	public function to_excel()
	{    
	$query=$this->session->userdata('searchterm');
	$file_name='prepaid_card_'.date("Ymd");	
 	$this->general_model->csv_export($query,$file_name);
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
/* Location: ./system/application/controllers/card_generation.php */