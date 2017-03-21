<?php
class Distributors extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('account/account_model','project_site/site_model','project_site/ref_location_model'));
		
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
		}

		$this->load->view('home', isset($data) ? $data : NULL);
	}
	
	
	public function create_dealer($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_own_dealer'))
			{			
			
			
			if($distributor_id==$data['account']->id)
			{
			$data['title'] = lang('menu_dealers_management');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('dealer_name', 'Dealer Name', 'required');
			$this->form_validation->set_rules('dealer_code', 'Dealer Code', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
			$this->form_validation->set_rules('dealer_mobile', 'Dealer Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			
			$data['password']=$this->input->post('password');
			$data['email']=$this->input->post('email');
			$data['dealer_name']=$this->input->post('dealer_name');
			$data['dealer_code']=$this->input->post('dealer_code');
			$data['dealer_mobile']=$this->input->post('dealer_mobile');
			
				if ($this->form_validation->run() == FALSE)
				{
				$query="SELECT * FROM `apninv_dealers` WHERE dealer_id=(SELECT max(dealer_id) FROM apninv_dealers)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->dealer_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("dealer_code_start_from"))
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");
					
				$data['dealer_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/view_create_dealer_for_distributor', isset($data) ? $data : NULL);
				}
				else
				{
				$this->load->helper('account/phpass');
				$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				$new_hashed_password = $hasher->HashPassword($this->input->post('password'));
		
				$a3m_account_data=array(
						'username'=>$this->input->post('username'),
						'email'=>$this->input->post('email'),
						'password'=>$new_hashed_password,						
						'createdon'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
				$a3m_account_id=$this->general_model->save_into_table_and_return_insert_id('apninv_a3m_account', $a3m_account_data);				
				
				$fullname=ucwords(strtolower($this->input->post('dealer_name')));   // camelcase to words
				$pieces = explode(" ", $fullname);
				$length = count($pieces);
				
				for($i=1;$i<=$length-1;$i++)
				{
					if($i==1)
					$lastname=$pieces[$i];	
					else
					$lastname=$lastname." ".$pieces[$i];
				}
				
				
				
				$a3m_account_details_data=array(
						'account_id'=>$a3m_account_id,
						'fullname'=>$fullname,
						'firstname'=>$pieces[0],
						'lastname'=>$lastname					
						);
				
				$success_or_fail1=$this->general_model->save_into_table('apninv_a3m_account_details', $a3m_account_details_data);
				
				
				
				$distributor_data=array(
						'dealer_id'=>$a3m_account_id,
						'dealer_code'=>$this->input->post('dealer_code'),
						'dealer_name'=>$this->input->post('dealer_name'),		
						'dealer_mobile'=>$this->input->post('dealer_mobile'),
						'dealer_division'=>$this->input->post('site_division'),
						'dealer_district'=>$this->input->post('site_district'),
						'dealer_upazila'=>$this->input->post('site_upazila'),
						'dealer_union'=>$this->input->post('site_union'),
						'active_status'=>1,
						'create_user_id'=>$data['account']->id,
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail2=$this->general_model->save_into_table('apninv_dealers', $distributor_data);
				
				$a3m_rel_account_role_data=array(
						'account_id'=>$a3m_account_id,
						'role_id'=>$this->config->item("dealer_role_id")												
						);
								
				$success_or_fail3=$this->general_model->save_into_table('apninv_a3m_rel_account_role', $a3m_rel_account_role_data);								
				
				
				$apninv_distributors_dealers_map_data=array(
						'distributors_id'=>$distributor_id,
						'dealer_id'=>$a3m_account_id												
						);
								
				$success_or_fail4=$this->general_model->save_into_table('apninv_distributors_dealers_map', $apninv_distributors_dealers_map_data);								
				
				if($success_or_fail1 && $success_or_fail2 && $success_or_fail3 && $success_or_fail4)
					$data['success_msg']=lang('saveed_successfully');
				else
					$data['error_msg']=lang('save_unsuccessfull');	
				
				
				$query="SELECT * FROM `apninv_dealers` WHERE dealer_id=(SELECT max(dealer_id) FROM apninv_dealers)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->dealer_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("dealer_code_start_from"))
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("dealer_code_start_from");
					
				$data['dealer_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/view_create_dealer_for_distributor', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access other distributor list');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page	
			}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	
	public function add_dealer_for_distributor($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_delear_for_distributor'))
			{			
			
			
			if($this->general_model->is_exist_in_a_table('apninv_distributors','distributor_id',$distributor_id))
			{
			$data['title'] = lang('menu_dealers_management');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('dealer_name', 'Dealer Name', 'required');
			$this->form_validation->set_rules('dealer_code', 'Dealer Code', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
			$this->form_validation->set_rules('dealer_mobile', 'Dealer Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			
			$data['password']=$this->input->post('password');
			$data['email']=$this->input->post('email');
			$data['dealer_name']=$this->input->post('dealer_name');
			$data['dealer_code']=$this->input->post('dealer_code');
			$data['dealer_mobile']=$this->input->post('dealer_mobile');
			
				if ($this->form_validation->run() == FALSE)
				{
				$query="SELECT * FROM `apninv_dealers` WHERE dealer_id=(SELECT max(dealer_id) FROM apninv_dealers)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->dealer_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("dealer_code_start_from"))
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");
					
				$data['dealer_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/view_create_dealer_for_distributor', isset($data) ? $data : NULL);
				}
				else
				{
				$this->load->helper('account/phpass');
				$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				$new_hashed_password = $hasher->HashPassword($this->input->post('password'));
		
				$a3m_account_data=array(
						'username'=>$this->input->post('username'),
						'email'=>$this->input->post('email'),
						'password'=>$new_hashed_password,						
						'createdon'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
				$a3m_account_id=$this->general_model->save_into_table_and_return_insert_id('apninv_a3m_account', $a3m_account_data);				
				
				$fullname=ucwords(strtolower($this->input->post('dealer_name')));   // camelcase to words
				$pieces = explode(" ", $fullname);
				$length = count($pieces);
				
				for($i=1;$i<=$length-1;$i++)
				{
					if($i==1)
					$lastname=$pieces[$i];	
					else
					$lastname=$lastname." ".$pieces[$i];
				}
				
				
				
				$a3m_account_details_data=array(
						'account_id'=>$a3m_account_id,
						'fullname'=>$fullname,
						'firstname'=>$pieces[0],
						'lastname'=>$lastname					
						);
				
				$success_or_fail1=$this->general_model->save_into_table('apninv_a3m_account_details', $a3m_account_details_data);
				
				
				
				$distributor_data=array(
						'dealer_id'=>$a3m_account_id,
						'dealer_code'=>$this->input->post('dealer_code'),
						'dealer_name'=>$this->input->post('dealer_name'),		
						'dealer_mobile'=>$this->input->post('dealer_mobile'),
						'dealer_division'=>$this->input->post('site_division'),
						'dealer_district'=>$this->input->post('site_district'),
						'dealer_upazila'=>$this->input->post('site_upazila'),
						'dealer_union'=>$this->input->post('site_union'),
						'active_status'=>1,
						'create_user_id'=>$data['account']->id,
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail2=$this->general_model->save_into_table('apninv_dealers', $distributor_data);
				
				$a3m_rel_account_role_data=array(
						'account_id'=>$a3m_account_id,
						'role_id'=>$this->config->item("dealer_role_id")												
						);
								
				$success_or_fail3=$this->general_model->save_into_table('apninv_a3m_rel_account_role', $a3m_rel_account_role_data);								
				
				
				$apninv_distributors_dealers_map_data=array(
						'distributors_id'=>$distributor_id,
						'dealer_id'=>$a3m_account_id												
						);
								
				$success_or_fail4=$this->general_model->save_into_table('apninv_distributors_dealers_map', $apninv_distributors_dealers_map_data);								
				
				if($success_or_fail1 && $success_or_fail2 && $success_or_fail3 && $success_or_fail4)
					$data['success_msg']=lang('saveed_successfully');
				else
					$data['error_msg']=lang('save_unsuccessfull');	
				
				
				$query="SELECT * FROM `apninv_dealers` WHERE dealer_id=(SELECT max(dealer_id) FROM apninv_dealers)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->dealer_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("dealer_code_start_from"))
					$serial_start_with_prefix=$this->config->item("dealer_prefix").$this->config->item("dealer_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("dealer_code_start_from");
					
				$data['dealer_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/view_create_dealer_for_distributor', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'Dealer not exist');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page	
			}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	public function create_distributors()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_distributors'))
			{			
			$data['title'] = lang('menu_create_distributors');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('distributor_name', 'Distributor Name', 'required');
			$this->form_validation->set_rules('distributor_code', 'Distributor Code', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
			$this->form_validation->set_rules('distributor_mobile', 'Distributor Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			
			$data['password']=$this->input->post('password');
			$data['email']=$this->input->post('email');
			$data['distributor_name']=$this->input->post('distributor_name');
			$data['distributor_code']=$this->input->post('distributor_code');
			$data['distributor_mobile']=$this->input->post('distributor_mobile');
			
				if ($this->form_validation->run() == FALSE)
				{
				$query="SELECT * FROM `apninv_distributors` WHERE distributor_id=(SELECT max(distributor_id) FROM apninv_distributors)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->distributor_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("distributor_code_start_from"))
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("distributor_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("distributor_code_start_from");
					
				$data['distributor_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$this->load->view('distributors/view_create_distributors', isset($data) ? $data : NULL);
				}
				else
				{
				$this->load->helper('account/phpass');
				$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				$new_hashed_password = $hasher->HashPassword($this->input->post('password'));
		
				$a3m_account_data=array(
						'username'=>$this->input->post('username'),
						'email'=>$this->input->post('email'),
						'password'=>$new_hashed_password,						
						'createdon'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
				$a3m_account_id=$this->general_model->save_into_table_and_return_insert_id('apninv_a3m_account', $a3m_account_data);				
				
				$fullname=ucwords(strtolower($this->input->post('distributor_name')));   // camelcase to words
				$pieces = explode(" ", $fullname);
				$length = count($pieces);
				
				for($i=1;$i<=$length-1;$i++)
				{
					if($i==1)
					$lastname=$pieces[$i];	
					else
					$lastname=$lastname." ".$pieces[$i];
				}
				
				
				
				$a3m_account_details_data=array(
						'account_id'=>$a3m_account_id,
						'fullname'=>$fullname,
						'firstname'=>$pieces[0],
						'lastname'=>$lastname					
						);
				
				$success_or_fail1=$this->general_model->save_into_table('apninv_a3m_account_details', $a3m_account_details_data);
				
				
				
				$distributor_data=array(
						'distributor_id'=>$a3m_account_id,
						'distributor_code'=>$this->input->post('distributor_code'),
						'distributor_name'=>$this->input->post('distributor_name'),		
						'distributor_mobile'=>$this->input->post('distributor_mobile'),
						'distributor_division'=>$this->input->post('site_division'),
						'distributor_district'=>$this->input->post('site_district'),
						'distributor_upazila'=>$this->input->post('site_upazila'),
						'distributor_union'=>$this->input->post('site_union'),
						'active_status'=>1,
						'create_user_id'=>$data['account']->id,
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail2=$this->general_model->save_into_table('apninv_distributors', $distributor_data);
				
				$a3m_rel_account_role_data=array(
						'account_id'=>$a3m_account_id,
						'role_id'=>$this->config->item("distributor_role_id")												
						);
				
				$success_or_fail3=$this->general_model->save_into_table('apninv_a3m_rel_account_role', $a3m_rel_account_role_data);								
					
				if($success_or_fail1 && $success_or_fail2)
					$data['success_msg']=lang('saveed_successfully');
				else
					$data['error_msg']=lang('save_unsuccessfull');	
				
				
				$query="SELECT * FROM `apninv_distributors` WHERE distributor_id=(SELECT max(distributor_id) FROM apninv_distributors)";				
				$response_row=$this->general_model->get_all_single_row_querystring($query);
					if($response_row)
					{
					$serial_start_string=$response_row->distributor_code;
					
					$serial_start=substr($serial_start_string, 2, strlen($serial_start_string));					
					$serial_start=$serial_start+1;
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$serial_start;
					//echo "Distributor code:".$serial_start;					
					if($serial_start<$this->config->item("distributor_code_start_from"))
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("distributor_code_start_from");											
					}
					else
					$serial_start_with_prefix=$this->config->item("distributor_prefix").$this->config->item("distributor_code_start_from");
					
				$data['distributor_code']=$serial_start_with_prefix;
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				
				$this->load->view('distributors/view_create_distributors', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	
	public function edit_single_distributor($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_distributors'))
			{			
			$data['title'] = lang('menu_create_distributors');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('distributor_name', 'Distributor Name', 'required');
			$this->form_validation->set_rules('distributor_mobile', 'Distributor Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			$this->form_validation->set_rules('active_status', 'Active status', 'required');

			$data['distributor_name']=$this->input->post('distributor_name');
			$data['distributor_mobile']=$this->input->post('distributor_mobile');			
			
			
				if ($this->form_validation->run() == FALSE)
				{				
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info'] = $this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/edit_single_distributor', isset($data) ? $data : NULL);
				}
				else
				{
				
				if($this->input->post('active_status')=='active')
				$active_status=1;
				else
				$active_status=0;
				
				
				$distributor_data=array(
						'distributor_name'=>$this->input->post('distributor_name'),		
						'distributor_mobile'=>$this->input->post('distributor_mobile'),
						'distributor_division'=>$this->input->post('site_division'),
						'distributor_district'=>$this->input->post('site_district'),
						'distributor_upazila'=>$this->input->post('site_upazila'),
						'distributor_union'=>$this->input->post('site_union'),
						'active_status'=>$active_status,
						'update_user_id'=>$data['account']->id,
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail1=$this->general_model->update_table('apninv_distributors', $distributor_data,'distributor_id', $distributor_id);
				if($success_or_fail1)
					$data['success_msg']=lang('update_successfully');
				else
					$data['error_msg']=lang('update_unsuccessfull');													
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['distributor_info'] = $this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$this->load->view('distributors/edit_single_distributor', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	private function is_authorize_for_edit($distributor_id,$dealer_id)
	{
		if ($this->authentication->is_signed_in())
		{
		$searchterm="SELECT * FROM apninv_distributors_dealers_map WHERE distributors_id=$distributor_id AND dealer_id=$dealer_id";	
		if($this->general_model->is_exist_in_a_table_querystring($searchterm))
		return true;
		else
		return false;
		}
		else
		{
		return false;
		}
		
	}
	
	
	public function edit_single_dealer_of_distributor($dealer_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->is_authorize_for_edit($data['account']->id,$dealer_id))
			{
			if($this->authorization->is_permitted('can_create_own_dealer'))
			{			
			$data['title'] = lang('dealer');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('dealer_name', 'Dealer Name', 'required');
			$this->form_validation->set_rules('dealer_mobile', 'Dealer Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			$this->form_validation->set_rules('active_status', 'Active status', 'required');

			$data['dealer_name']=$this->input->post('dealer_name');
			$data['dealer_mobile']=$this->input->post('dealer_mobile');			
			
			
				if ($this->form_validation->run() == FALSE)
				{				
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['dealer_info'] = $this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
				$this->load->view('distributors/edit_single_dealer', isset($data) ? $data : NULL);
				}
				else
				{
				
				if($this->input->post('active_status')=='active')
				$active_status=1;
				else
				$active_status=0;
				
				
				$dealer_data=array(
						'dealer_name'=>$this->input->post('dealer_name'),		
						'dealer_mobile'=>$this->input->post('dealer_mobile'),
						'dealer_division'=>$this->input->post('site_division'),
						'dealer_district'=>$this->input->post('site_district'),
						'dealer_upazila'=>$this->input->post('site_upazila'),
						'dealer_union'=>$this->input->post('site_union'),
						'active_status'=>$active_status,
						'update_user_id'=>$data['account']->id,
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail1=$this->general_model->update_table('apninv_dealers', $dealer_data,'dealer_id', $dealer_id);
				if($success_or_fail1)
					$data['success_msg']=lang('update_successfully');
				else
					$data['error_msg']=lang('update_unsuccessfull');													
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['dealer_info'] = $this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
				$this->load->view('distributors/edit_single_dealer', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to edit this dealer');
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}
			
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	public function edit_single_dealer($dealer_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_delear_for_distributor'))
			{			
			$data['title'] = lang('dealer');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('dealer_name', 'Dealer Name', 'required');
			$this->form_validation->set_rules('dealer_mobile', 'Dealer Mobile', 'required|min_length[11]|max_length[11]|is_natural');
			$this->form_validation->set_rules('site_division', 'Division', 'required');
			$this->form_validation->set_rules('site_district', 'District', 'required');
			$this->form_validation->set_rules('active_status', 'Active status', 'required');

			$data['dealer_name']=$this->input->post('dealer_name');
			$data['dealer_mobile']=$this->input->post('dealer_mobile');			
			
			
				if ($this->form_validation->run() == FALSE)
				{				
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['dealer_info'] = $this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
				$this->load->view('distributors/edit_single_dealer', isset($data) ? $data : NULL);
				}
				else
				{
				
				if($this->input->post('active_status')=='active')
				$active_status=1;
				else
				$active_status=0;
				
				
				$dealer_data=array(
						'dealer_name'=>$this->input->post('dealer_name'),		
						'dealer_mobile'=>$this->input->post('dealer_mobile'),
						'dealer_division'=>$this->input->post('site_division'),
						'dealer_district'=>$this->input->post('site_district'),
						'dealer_upazila'=>$this->input->post('site_upazila'),
						'dealer_union'=>$this->input->post('site_union'),
						'active_status'=>$active_status,
						'update_user_id'=>$data['account']->id,
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);
				
				$success_or_fail1=$this->general_model->update_table('apninv_dealers', $dealer_data,'dealer_id', $dealer_id);
				if($success_or_fail1)
					$data['success_msg']=lang('update_successfully');
				else
					$data['error_msg']=lang('update_unsuccessfull');													
				
				$division_query="SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011 location_bbs2011
 WHERE (location_bbs2011.loc_type = 'DV') ORDER BY location_bbs2011.loc_name_en ASC";
				$data['all_division'] = $this->general_model->get_all_querystring_result($division_query);
				$data['dealer_info'] = $this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
				$this->load->view('distributors/edit_single_dealer', isset($data) ? $data : NULL);
				}
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function dealer_list($distributor_id)
	{			
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($distributor_id==$data['account']->id)
			{
			
			if($this->authorization->is_permitted('can_view_own_dealer_list'))
			{			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_dealers_list');	
							
			
 		  	$searchterm="SELECT apninv_dealers.*, apninv_distributors_dealers_map.distributors_id
  FROM apninv_distributors_dealers_map apninv_distributors_dealers_map
       INNER JOIN apninv_dealers apninv_dealers
          ON (apninv_distributors_dealers_map.dealer_id =
                 apninv_dealers.dealer_id)
 WHERE (apninv_distributors_dealers_map.distributors_id = $distributor_id) Order by dealer_id Desc";
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/dealer_list/".$distributor_id."/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 5;
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
			
 			$data['total_records']=$config["total_rows"];
			$page = ($this->uri->segment(5))? $this->uri->segment(5) : 0;		
			$data['all_dealers'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;										
			
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			$data['all_district'] =$this->ref_location_model->get_all_district_info();
			
			$this->load->view('distributors/dealer_list_of_distributor', isset($data) ? $data : NULL);		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access other distributor list');	
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}
			}
			else
			{
			$this->session->set_flashdata('parmission', 'Dealer not exist');
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}
		
		}
		else
		{
		redirect('account/sign_in');
		}			
		
	}
	
	public function dealer_list_of_distributor($distributor_id)
	{			
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->general_model->is_exist_in_a_table('apninv_distributors','distributor_id',$distributor_id))
			{
			
			if($this->authorization->is_permitted('can_view_all_delear_list'))
			{			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_dealers_list');	
							
			
 		  	$searchterm="SELECT apninv_dealers.*, apninv_distributors_dealers_map.distributors_id
  FROM apninv_distributors_dealers_map apninv_distributors_dealers_map
       INNER JOIN apninv_dealers apninv_dealers
          ON (apninv_distributors_dealers_map.dealer_id =
                 apninv_dealers.dealer_id)
 WHERE (apninv_distributors_dealers_map.distributors_id = $distributor_id) Order by dealer_id Desc";
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/dealer_list_of_distributor/".$distributor_id."/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 5;
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
			
 			$data['total_records']=$config["total_rows"];
			$page = ($this->uri->segment(5))? $this->uri->segment(5) : 0;		
			$data['all_dealers'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;										
			
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			$data['all_district'] =$this->ref_location_model->get_all_district_info();
			
			$this->load->view('distributors/dealer_list', isset($data) ? $data : NULL);		
			
			}
			else
			{
			$this->session->set_flashdata('parmission', 'You have no permission to access this feature');	
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}
			}
			else
			{
			$this->session->set_flashdata('parmission', 'Dealer not exist');
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}
		
		}
		else
		{
		redirect('account/sign_in');
		}			
		
	}
	
	public function distributors_list()
	{
		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_distributors_list'))
			{			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_distributors_list');	
							
			
 		  	$searchterm='SELECT * FROM apninv_distributors Order by distributor_id Desc';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/distributors_list/";
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
			
 			$data['total_records']=$config["total_rows"];
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_distributors'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;										
			
			$data['all_district'] =$this->ref_location_model->get_all_district_info();
			
			$this->load->view('distributors/distributors_list', isset($data) ? $data : NULL);		
			
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
	
	public function dealers_list_search_of_distributor($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_own_dealer_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('dealer_list');	
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['dealer_code']    	= $this->input->post('dealer_code');
				$data['dealer_name']    	= $this->input->post('dealer_name');
				$data['active_inactive']     	= $this->input->post("active_inactive");
				$data['site_district']     		= $this->input->post("site_district");						
 
 		  		$query_string="SELECT apninv_dealers.*, apninv_distributors_dealers_map.distributors_id
  FROM apninv_distributors_dealers_map apninv_distributors_dealers_map
       INNER JOIN apninv_dealers apninv_dealers
          ON (apninv_distributors_dealers_map.dealer_id =
                 apninv_dealers.dealer_id)
 WHERE (apninv_distributors_dealers_map.distributors_id = $distributor_id)";
				
					if($this->input->post("dealer_code"))	
					{
						$dealer_code=$this->input->post("dealer_code"); 
						$query_string=$query_string." AND (dealer_code= '$dealer_code')";
					}
	   				
					if($this->input->post("active_inactive"))	
					{
						$active_inactive=$this->input->post("active_inactive");
						if($active_inactive=="acitve")
						$active=1;
						elseif($active_inactive=="inactive")
						$active=0;						
						
						$query_string=$query_string." AND (active_status= '$active')";
					}
					
					if($this->input->post("dealer_name"))	
					{
						$dealer_name=$this->input->post("dealer_name"); 
						$query_string=$query_string." AND (dealer_name Like '%$dealer_name%')";
					}
					
					if($this->input->post("site_district"))	
					{
						$site_district=$this->input->post("site_district"); 
						$query_string=$query_string." AND (dealer_district='$site_district')";
					}
					
					
					
				$query_string=$query_string." Order by dealer_id Desc";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}

			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/dealers_list_search_of_distributor/".$distributor_id."/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 5;
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
			
 
			$page = ($this->uri->segment(5))? $this->uri->segment(5) : 0;		
			$data['all_dealers'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['total_records']=$config["total_rows"];
			$data['all_district'] =$this->ref_location_model->get_all_district_info();				
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			$this->load->view('distributors/dealer_list_of_distributor', isset($data) ? $data : NULL);
			
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
	
	public function dealers_list_search($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_all_delear_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('dealer_list');	
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['dealer_code']    	= $this->input->post('dealer_code');
				$data['dealer_name']    	= $this->input->post('dealer_name');
				$data['active_inactive']     	= $this->input->post("active_inactive");
				$data['site_district']     		= $this->input->post("site_district");		
				
 		  		$query_string="SELECT apninv_dealers.*, apninv_distributors_dealers_map.distributors_id
  FROM apninv_distributors_dealers_map apninv_distributors_dealers_map
       INNER JOIN apninv_dealers apninv_dealers
          ON (apninv_distributors_dealers_map.dealer_id =
                 apninv_dealers.dealer_id)
 WHERE (apninv_distributors_dealers_map.distributors_id = $distributor_id)";
				//$query_string='SELECT * FROM apninv_dealers Where dealer_id > 0';
				
					if($this->input->post("dealer_code"))	
					{
						$dealer_code=$this->input->post("dealer_code"); 
						$query_string=$query_string." AND (dealer_code= '$dealer_code')";
					}
	   				
					if($this->input->post("active_inactive"))	
					{
						$active_inactive=$this->input->post("active_inactive");
						if($active_inactive=="acitve")
						$active=1;
						elseif($active_inactive=="inactive")
						$active=0;						
						
						$query_string=$query_string." AND (active_status= '$active')";
					}
					
					if($this->input->post("dealer_name"))	
					{
						$dealer_name=$this->input->post("dealer_name"); 
						$query_string=$query_string." AND (dealer_name Like '%$dealer_name%')";
					}
					
					if($this->input->post("site_district"))	
					{
						$site_district=$this->input->post("site_district"); 
						$query_string=$query_string." AND (dealer_district='$site_district')";
					}
					
					
					
				$query_string=$query_string." Order by dealer_id Desc";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}

			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/dealers_list_search/".$distributor_id."/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 5;
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
			
 
			$page = ($this->uri->segment(5))? $this->uri->segment(5) : 0;		
			$data['all_dealers'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['total_records']=$config["total_rows"];
			$data['all_district'] =$this->ref_location_model->get_all_district_info();				
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			$this->load->view('distributors/dealer_list', isset($data) ? $data : NULL);
			
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
	
	public function distributors_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_distributors_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('menu_distributors_list');	
			
			if($this->input->post("search_submit"))
				{
				// assign posted valued
				$data['distributor_code']    	= $this->input->post('distributor_code');
				$data['distributor_name']    	= $this->input->post('distributor_name');
				$data['active_inactive']     	= $this->input->post("active_inactive");
				$data['site_district']     		= $this->input->post("site_district");		
				
 		  		$query_string='SELECT * FROM apninv_distributors Where distributor_id > 0';
				
					if($this->input->post("distributor_code"))	
					{
						$distributor_code=$this->input->post("distributor_code"); 
						$query_string=$query_string." AND (distributor_code= '$distributor_code')";
					}
	   				
					if($this->input->post("active_inactive"))	
					{
						$active_inactive=$this->input->post("active_inactive");
						if($active_inactive=="acitve")
						$active=1;
						elseif($active_inactive=="inactive")
						$active=0;						
						
						$query_string=$query_string." AND (active_status= '$active')";
					}
					
					if($this->input->post("distributor_name"))	
					{
						$distributor_name=$this->input->post("distributor_name"); 
						$query_string=$query_string." AND (distributor_name Like '%$distributor_name%')";
					}
					
					if($this->input->post("site_district"))	
					{
						$site_district=$this->input->post("site_district"); 
						$query_string=$query_string." AND (distributor_district='$site_district')";
					}
					
					
					
				$query_string=$query_string." Order by distributor_id Desc";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);
				
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}

			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distributors/distributors/distributors_list_search/";
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
			$data['all_distributors'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['total_records']=$config["total_rows"];
			$data['all_district'] =$this->ref_location_model->get_all_district_info();				
			
			$this->load->view('distributors/distributors_list', isset($data) ? $data : NULL);
			
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

	public function to_excel()
	{    
	$query=$this->session->userdata('searchterm');
	$file_name='prepaid_card_'.date("Ymd");	
 	$this->general_model->csv_export($query,$file_name);
	}
	
	public function email_check($email)
	{				
		$is_exist=$this->general_model->is_exist_in_a_table('apninv_a3m_account','email',$email);
		
		if ($is_exist > 0)
		{
			$this->form_validation->set_message('email_check', ' The email '.$email .' is already exits');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/**** Ajax function *****/
	function get_all_child_location()
	{
	$dvid=$this->input->post('dvid');				$dvid  = empty($dvid) ? NULL : $dvid;
	$dtid=$this->input->post('dtid');				$dtid  = empty($dtid) ? NULL : $dtid;
	$upid=$this->input->post('upid');				$upid  = empty($upid) ? NULL : $upid;
	$unid=$this->input->post('unid');				$unid  = empty($unid) ? NULL : $unid;
	$maid=$this->input->post('maid');				$maid  = empty($maid) ? NULL : $maid;
	$ltype=$this->input->post('ltype');				$ltype  = empty($ltype) ? NULL : $ltype;
	
	$this->ref_location_model->get_child_location($dvid,$dtid,$upid,$unid,$maid,$ltype);				
	}
	
}


/* End of file home.php */
/* Location: ./system/application/controllers/distributors.php */