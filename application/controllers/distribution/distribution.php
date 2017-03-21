<?php
class Distribution extends CI_Controller {

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
	
	public function all_card_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_view_prepaid_card_list'))
			{
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$this->load->library('form_validation');
				$data['title'] = lang('card_list');	
				
				if($this->input->post("search_submit"))
					{
					// assign posted valued
					$data['card_serial']    	= $this->input->post('card_serial');
					$data['active_inactive']    = $this->input->post("active_inactive");
					$data['card_type']     		= $this->input->post("card_type");						
	 
					$query_string="SELECT apninv_card_inventory.card_id,
								   apninv_card_distribution.distributor_dealer_id,
								   apninv_card_distribution.distributor_or_dealer,
								   apninv_card_inventory.card_serial,
								   apninv_card_inventory.card_type,
								   apninv_card_inventory.card_pin,
								   apninv_card_inventory.active_status,
								   apninv_card_distribution.card_owner_id,
								   apninv_card_distribution.create_user_id,
								   apninv_card_distribution.create_date
							  FROM apninv_card_distribution apninv_card_distribution
								   INNER JOIN apninv_card_inventory apninv_card_inventory
									  ON (apninv_card_distribution.card_id =
											 apninv_card_inventory.card_id)";
					
						if($this->input->post("card_serial"))	
						{
							$card_serial=$this->input->post("card_serial"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_serial= $card_serial)";
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
							
							$query_string=$query_string." AND (apninv_card_inventory.active_status= $active)";
						}
						
						if($this->input->post("card_type"))	
						{
							$card_type=$this->input->post("card_type"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_type = $card_type)";
						}
																								
						
					$query_string=$query_string." ORDER BY apninv_card_inventory.card_id DESC";																	
					$searchterm = $this->general_model->searchterm_handler($query_string);
					
					}
					else
					{
					$searchterm = $this->session->userdata('searchterm');
					}
	
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/all_card_list_search/";
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/all_card_list', isset($data) ? $data : NULL);
								
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
	
	
	public function distributor_card_list_search($distributor_id)
	{	
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_view_prepaid_card_list'))
			{
			
				if($this->general_model->is_exist_in_a_table('apninv_distributors','distributor_id',$distributor_id))
				{
				
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$this->load->library('form_validation');
				$data['title'] = lang('card_list');	
				
				if($this->input->post("search_submit"))
					{
					// assign posted valued
					$data['card_serial']    	= $this->input->post('card_serial');
					$data['active_inactive']    = $this->input->post("active_inactive");
					$data['card_type']     		= $this->input->post("card_type");						
	 
					$query_string="SELECT apninv_card_inventory.card_id,
									   apninv_card_distribution.distributor_dealer_id,
									   apninv_card_distribution.distributor_or_dealer,
									   apninv_card_inventory.card_serial,
									   apninv_card_inventory.card_type,
									   apninv_card_inventory.active_status
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $distributor_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'di')";
					
						if($this->input->post("card_serial"))	
						{
							$card_serial=$this->input->post("card_serial"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_serial= '$card_serial')";
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
							
							$query_string=$query_string." AND (apninv_card_inventory.active_status= $active)";
						}
						
						if($this->input->post("card_type"))	
						{
							$card_type=$this->input->post("card_type"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_type = $card_type)";
						}
																								
						
					$query_string=$query_string." ORDER BY apninv_card_inventory.card_id DESC";																	
					$searchterm = $this->general_model->searchterm_handler($query_string);
					
					}
					else
					{
					$searchterm = $this->session->userdata('searchterm');
					}
	
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/distributor_card_list_search/".$distributor_id."/";
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/distributor_card_list', isset($data) ? $data : NULL);
				
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Distributor not exist');
				redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
				}
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
	
	
	public function my_card_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$distributor_id=$data['account']->id;
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{	
			$card_list_query="SELECT apninv_card_inventory.card_id,
							   apninv_card_distribution.distributor_dealer_id,
							   apninv_card_distribution.distributor_or_dealer,
							   apninv_card_inventory.card_serial,
							   apninv_card_inventory.card_type,
							   apninv_card_inventory.card_pin,
							   apninv_card_inventory.active_status,
							   apninv_card_distribution.card_owner_id,
							   apninv_card_distribution.create_user_id,
							   apninv_card_distribution.create_date
						  FROM apninv_card_distribution apninv_card_distribution
							   INNER JOIN apninv_card_inventory apninv_card_inventory
								  ON (apninv_card_distribution.card_id =
										 apninv_card_inventory.card_id)
                     Where apninv_card_distribution.distributor_dealer_id=$distributor_id AND apninv_card_distribution.distributor_or_dealer='di'
						ORDER BY apninv_card_inventory.card_id DESC";
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/my_card_list/";
				$config["total_rows"] = $this->general_model->total_count_query_string($card_list_query); 
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($card_list_query,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/my_all_card_list', isset($data) ? $data : NULL);
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
	
	public function my_distribution_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$distributor_id=$data['account']->id;
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{	
			$card_list_query="SELECT apninv_card_inventory.card_id,
							   apninv_card_distribution.distributor_dealer_id,
							   apninv_card_distribution.distributor_or_dealer,
							   apninv_card_inventory.card_serial,
							   apninv_card_inventory.card_type,
							   apninv_card_inventory.card_pin,
							   apninv_card_inventory.active_status,
							   apninv_card_distribution.card_owner_id,
							   apninv_card_distribution.create_user_id,
							   apninv_card_distribution.create_date
						  FROM apninv_card_distribution apninv_card_distribution
							   INNER JOIN apninv_card_inventory apninv_card_inventory
								  ON (apninv_card_distribution.card_id =
										 apninv_card_inventory.card_id)
                     Where apninv_card_distribution.card_owner_id=$distributor_id AND apninv_card_distribution.distributor_or_dealer='de'
						ORDER BY apninv_card_inventory.card_id DESC";
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/my_distribution_list/";
				$config["total_rows"] = $this->general_model->total_count_query_string($card_list_query); 
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($card_list_query,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/my_card_list', isset($data) ? $data : NULL);
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
	
	
	public function my_card_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$distributor_id=$data['account']->id;
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{	
			if($this->input->post("search_submit"))
					{
					// assign posted valued
					$data['card_serial']    	= $this->input->post('card_serial');
					$data['active_inactive']    = $this->input->post("active_inactive");
					$data['card_type']     		= $this->input->post("card_type");						
	 				
					$query_string="SELECT apninv_card_inventory.card_id,
							   apninv_card_distribution.distributor_dealer_id,
							   apninv_card_distribution.distributor_or_dealer,
							   apninv_card_inventory.card_serial,
							   apninv_card_inventory.card_type,
							   apninv_card_inventory.card_pin,
							   apninv_card_inventory.active_status,
							   apninv_card_distribution.card_owner_id,
							   apninv_card_distribution.create_user_id,
							   apninv_card_distribution.create_date
						  FROM apninv_card_distribution apninv_card_distribution
							   INNER JOIN apninv_card_inventory apninv_card_inventory
								  ON (apninv_card_distribution.card_id =
										 apninv_card_inventory.card_id)
                     Where apninv_card_distribution.distributor_dealer_id=$distributor_id AND apninv_card_distribution.distributor_or_dealer='di'";
										
						if($this->input->post("card_serial"))	
						{
							$card_serial=$this->input->post("card_serial"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_serial= '$card_serial')";
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
							
							$query_string=$query_string." AND (apninv_card_inventory.active_status= $active)";
						}
						
						if($this->input->post("card_type"))	
						{
							$card_type=$this->input->post("card_type"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_type = $card_type)";
						}
																								
						
					$query_string=$query_string." ORDER BY apninv_card_inventory.card_id DESC";																	
					$searchterm = $this->general_model->searchterm_handler($query_string);
					
					}
					else
					{
					$searchterm = $this->session->userdata('searchterm');
					}
			
			
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/my_card_list_search/";
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/my_all_card_list', isset($data) ? $data : NULL);
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
	
	
	public function my_distribution_list_search()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$distributor_id=$data['account']->id;
			$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{	
			
			if($this->input->post("search_submit"))
					{
					// assign posted valued
					$data['card_serial']    	= $this->input->post('card_serial');
					$data['active_inactive']    = $this->input->post("active_inactive");
					$data['card_type']     		= $this->input->post("card_type");						
	 				
					$query_string="SELECT apninv_card_inventory.card_id,
							   apninv_card_distribution.distributor_dealer_id,
							   apninv_card_distribution.distributor_or_dealer,
							   apninv_card_inventory.card_serial,
							   apninv_card_inventory.card_type,
							   apninv_card_inventory.card_pin,
							   apninv_card_inventory.active_status,
							   apninv_card_distribution.card_owner_id,
							   apninv_card_distribution.create_user_id,
							   apninv_card_distribution.create_date
						  FROM apninv_card_distribution apninv_card_distribution
							   INNER JOIN apninv_card_inventory apninv_card_inventory
								  ON (apninv_card_distribution.card_id =
										 apninv_card_inventory.card_id)
                     Where apninv_card_distribution.card_owner_id=$distributor_id AND apninv_card_distribution.distributor_or_dealer='de'";											
					
						if($this->input->post("card_serial"))	
						{
							$card_serial=$this->input->post("card_serial"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_serial= '$card_serial')";
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
							
							$query_string=$query_string." AND (apninv_card_inventory.active_status= $active)";
						}
						
						if($this->input->post("card_type"))	
						{
							$card_type=$this->input->post("card_type"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_type = $card_type)";
						}
																								
						
					$query_string=$query_string." ORDER BY apninv_card_inventory.card_id DESC";																	
					$searchterm = $this->general_model->searchterm_handler($query_string);
					
					}
					else
					{
					$searchterm = $this->session->userdata('searchterm');
					}
			
			
			
			
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/my_distribution_list_search/";
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/my_card_list', isset($data) ? $data : NULL);
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
	
	public function distribution_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			if($this->authorization->is_permitted('can_view_distributed_card_list'))
			{	
			$card_list_query="SELECT apninv_card_inventory.card_id,
							   apninv_card_distribution.distributor_dealer_id,
							   apninv_card_distribution.distributor_or_dealer,
							   apninv_card_inventory.card_serial,
							   apninv_card_inventory.card_type,
							   apninv_card_inventory.card_pin,
							   apninv_card_inventory.active_status,
							   apninv_card_distribution.card_owner_id,
							   apninv_card_distribution.create_user_id,
							   apninv_card_distribution.create_date
						  FROM apninv_card_distribution apninv_card_distribution
							   INNER JOIN apninv_card_inventory apninv_card_inventory
								  ON (apninv_card_distribution.card_id =
										 apninv_card_inventory.card_id)	 
						ORDER BY apninv_card_inventory.card_id DESC";
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/distribution_list/";
				$config["total_rows"] = $this->general_model->total_count_query_string($card_list_query); 
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($card_list_query,$config["per_page"], $page);									
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/all_card_list', isset($data) ? $data : NULL);
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
	
	
	public function dealer_card_list_search($dealer_id)
	{
	
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{
				if($this->general_model->is_exist_in_a_table('apninv_dealers','dealer_id',$dealer_id))
				{
				if($this->is_authorize_for_edit($data['account']->id,$dealer_id))
					{
					$this->load->helper("url");	
					$this->load->library('pagination');	
					$this->load->library('form_validation');
					$data['title'] = lang('card_list');	
				
				if($this->input->post("search_submit"))
					{
					// assign posted valued
					$data['card_serial']    	= $this->input->post('card_serial');
					$data['active_inactive']    = $this->input->post("active_inactive");
					$data['card_type']     		= $this->input->post("card_type");						
	 
					$query_string="SELECT apninv_card_inventory.card_id,
									   apninv_card_distribution.distributor_dealer_id,
									   apninv_card_distribution.distributor_or_dealer,
									   apninv_card_inventory.card_serial,
									   apninv_card_inventory.card_type,
									   apninv_card_inventory.active_status
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $dealer_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'de')";													
					
						if($this->input->post("card_serial"))	
						{
							$card_serial=$this->input->post("card_serial"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_serial= '$card_serial')";
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
							
							$query_string=$query_string." AND (apninv_card_inventory.active_status= $active)";
						}
						
						if($this->input->post("card_type"))	
						{
							$card_type=$this->input->post("card_type"); 
							$query_string=$query_string." AND (apninv_card_inventory.card_type = $card_type)";
						}
																								
						
					$query_string=$query_string." ORDER BY apninv_card_inventory.card_id DESC";																	
					$searchterm = $this->general_model->searchterm_handler($query_string);
					
					}
					else
					{
					$searchterm = $this->session->userdata('searchterm');
					}

					//pagination
					$this->load->helper("url");	
					$this->load->library('pagination');	
					$data['title'] = lang('card_list');
					$config = array();
					$config["base_url"] = base_url() . "distribution/distribution/dealer_card_list_search/$dealer_id";
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
					$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
					$data['dealer_info']=$this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
					$data["links"] = $this->pagination->create_links();
					$data["page"]=$page;
				
					$this->load->view('distribution/dealer_card_list', isset($data) ? $data : NULL);
					}
					else
					{
					$this->session->set_flashdata('parmission', 'You are not authorize to view this dealer');
					redirect('./dashboard');  // if not exist redirect to home page
					}
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Distributor not exist');
				redirect('./dashboard');  // if not exist redirect to home page
				}	
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
	
	public function dealer_card_list($dealer_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			if($this->authorization->is_permitted('can_view_own_distributed_card_list'))
			{
				if($this->general_model->is_exist_in_a_table('apninv_dealers','dealer_id',$dealer_id))
				{
				if($this->is_authorize_for_edit($data['account']->id,$dealer_id))
					{
					$card_list_query="SELECT apninv_card_inventory.card_id,
									   apninv_card_distribution.distributor_dealer_id,
									   apninv_card_distribution.distributor_or_dealer,
									   apninv_card_inventory.card_serial,
									   apninv_card_inventory.card_type,
									   apninv_card_inventory.active_status
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $dealer_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'de')
								ORDER BY apninv_card_inventory.card_id DESC";								
				
					//pagination
					$this->load->helper("url");	
					$this->load->library('pagination');	
					$data['title'] = lang('card_list');
					$config = array();
					$config["base_url"] = base_url() . "distribution/distribution/dealer_card_list/$dealer_id";
					$config["total_rows"] = $this->general_model->total_count_query_string($card_list_query); 
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
					$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($card_list_query,$config["per_page"], $page);					
					$data['dealer_info']=$this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
					$data["links"] = $this->pagination->create_links();
					$data["page"]=$page;
				
					$this->load->view('distribution/dealer_card_list', isset($data) ? $data : NULL);
					}
					else
					{
					$this->session->set_flashdata('parmission', 'You are not authorize to view this dealer');
					redirect('./dashboard');  // if not exist redirect to home page
					}
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Dealer not exist');
				redirect('./dashboard');  // if not exist redirect to home page
				}	
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
	
	
	public function distributor_card_list($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			if($this->authorization->is_permitted('can_view_prepaid_card_list'))
			{
				if($this->general_model->is_exist_in_a_table('apninv_distributors','distributor_id',$distributor_id))
				{
				$card_list_query="SELECT apninv_card_inventory.card_id,
									   apninv_card_distribution.distributor_dealer_id,
									   apninv_card_distribution.distributor_or_dealer,
									   apninv_card_inventory.card_serial,
									   apninv_card_inventory.card_type,
									   apninv_card_inventory.active_status
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $distributor_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'di')
								ORDER BY apninv_card_inventory.card_id DESC";								
				
				//pagination
				$this->load->helper("url");	
				$this->load->library('pagination');	
				$data['title'] = lang('card_list');
				$config = array();
				$config["base_url"] = base_url() . "distribution/distribution/distributor_card_list/$distributor_id";
				$config["total_rows"] = $this->general_model->total_count_query_string($card_list_query); 
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
				$data['card_info'] = $this->general_model->get_all_result_by_limit_querystring($card_list_query,$config["per_page"], $page);					
				$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;
			
				$this->load->view('distribution/distributor_card_list', isset($data) ? $data : NULL);
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Distributor not exist');
				redirect('./dashboard');  // if not exist redirect to home page
				}	
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
	
	
	public function distribute_to_dealer($dealer_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$distributor_id=$data['account']->id;
			
			if($this->authorization->is_permitted('can_distribute_card_among_own_dealers'))
			{	
				if($this->general_model->is_exist_in_a_table('apninv_dealers','dealer_id',$dealer_id))
				{
					if($this->is_authorize_for_edit($data['account']->id,$dealer_id))
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
						  'rules' => 'required'),
						array(
						  'field' => 'card_type', 
						  'label' => 'Card Type', 
						  'rules' => 'required')
					  ));
					  
					// Run form validation
						if ($this->form_validation->run())
						{
						$data['card_serial_from']=$this->input->post('card_serial_from');
						$data['card_serial_to']=$this->input->post('card_serial_to');
						$data['card_type']=$this->input->post('card_type');
						
						$check_query="SELECT card_id AS card_serial_id, card_serial, card_type, active_status 
										FROM apninv_card_inventory 
										WHERE card_serial>=".$data['card_serial_from']." 
										AND card_serial<=".$data['card_serial_to']." 
										AND card_type=".$data['card_type']." 
										AND active_status<>2
										AND card_id IN (SELECT apninv_card_distribution.card_id
										  FROM apninv_card_distribution apninv_card_distribution
										 WHERE (    apninv_card_distribution.distributor_dealer_id = $distributor_id
												AND apninv_card_distribution.distributor_or_dealer = 'di' 
												AND card_id NOT IN(SELECT apninv_card_distribution.card_id FROM apninv_card_distribution WHERE distributor_or_dealer = 'de' AND card_owner_id=$distributor_id)))";
						
						//echo $check_query;
						$data['card_info']=$this->general_model->get_all_querystring_result($check_query);
						$data['dealer_info']=$this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
						$this->load->view('distribution/distribution_to_dealer', isset($data) ? $data : NULL);
						}
						else
						{
						$data['dealer_info']=$this->general_model->get_all_table_info_by_id('apninv_dealers', 'dealer_id', $dealer_id);
						$this->load->view('distribution/distribution_to_dealer', isset($data) ? $data : NULL);	
						}
					}
					else
					{
					$this->session->set_flashdata('parmission', 'You are not authorize to see this dealer');
					redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page
					}
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Distributor not exist');
				redirect('./dashboard');  // if not exist redirect to home page
				}
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
	
	
	public function distribute_to_distributor($distributor_id)
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_distribute_card_among_distributors'))
			{	
				if($this->general_model->is_exist_in_a_table('apninv_distributors','distributor_id',$distributor_id))
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
					  'rules' => 'required'),
					array(
					  'field' => 'card_type', 
					  'label' => 'Card Type', 
					  'rules' => 'required')
				  ));
				  
				// Run form validation
					if ($this->form_validation->run())
					{
					$data['card_serial_from']=$this->input->post('card_serial_from');
					$data['card_serial_to']=$this->input->post('card_serial_to');
					$data['card_type']=$this->input->post('card_type');
					
					$check_query="SELECT card_id AS card_serial_id, card_serial, card_type, active_status 
									FROM apninv_card_inventory 
									WHERE card_serial>='".$data['card_serial_from']."' 
									AND card_serial<='".$data['card_serial_to']."' 
									AND card_type=".$data['card_type']." 
									AND active_status<>2
									AND card_id NOT IN (SELECT card_id FROM apninv_card_distribution 
									WHERE apninv_card_distribution.card_id = apninv_card_inventory.card_id)";
					
					$data['card_info']=$this->general_model->get_all_querystring_result($check_query);
					$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
					$this->load->view('distribution/distribution_to_distributor', isset($data) ? $data : NULL);
					}
					else
					{
					$data['distributor_info']=$this->general_model->get_all_table_info_by_id('apninv_distributors', 'distributor_id', $distributor_id);
					$this->load->view('distribution/distribution_to_distributor', isset($data) ? $data : NULL);	
					}
				}
				else
				{
				$this->session->set_flashdata('parmission', 'Distributor not exist');
				redirect('./dashboard');  // if not exist redirect to home page
				}
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
	
	/***** AJAX FUNCTION **********/
	public function save_my_distribution()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			$distributor_id=$data['account']->id;
			if($this->authorization->is_permitted('can_distribute_card_among_own_dealers'))
			{
			$card_serial_from=$this->input->post('card_serial_from');
			$card_serial_to=$this->input->post('card_serial_to');
			$card_type=$this->input->post('card_type');
			$dealer_id=$this->input->post('dealer_id');
			//echo "From:".$card_serial_from.", To:".$card_serial_to.", Type:".$card_type;
			$check_query="SELECT card_id, card_serial, card_type, active_status
									FROM apninv_card_inventory 
									WHERE card_serial>=".$card_serial_from." 
									AND card_serial<=".$card_serial_to." 
									AND card_type=".$card_type." 
									AND active_status<>2
									AND card_id IN (SELECT apninv_card_distribution.card_id
									  FROM apninv_card_distribution apninv_card_distribution
									 WHERE (    apninv_card_distribution.distributor_dealer_id = $distributor_id
											AND apninv_card_distribution.distributor_or_dealer = 'di' 
											AND card_id NOT IN(SELECT apninv_card_distribution.card_id FROM apninv_card_distribution WHERE distributor_or_dealer = 'de' AND card_owner_id=$distributor_id)))";
					
			$card_info=$this->general_model->get_all_querystring_result($check_query);
				foreach($card_info as $card)
				{				
				$distribution_data=array(
						'card_id'=>$card->card_id,
						'distributor_dealer_id'=>$dealer_id,
						'distributor_or_dealer'=>'de',
						'card_owner_id'=>$data['account']->id,
						'create_user_id'=>$data['account']->id,
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);				
				$success_or_fail=$this->general_model->save_into_table('apninv_card_distribution', $distribution_data);
					if($success_or_fail)
					{
					echo '<span class="text-success">Card Serial:'.$card->card_serial.' distribution successful</span><br/>';		
					}
					else
					{
					echo '<span class="text-danger">Card Serial:'.$card->card_serial.' distribution unsuccessful</span><br/>';	
					}
				}
				
				if($card_info)
				{
				/********** Insert into log table(apninv_action_log) *************/
				$total_card=sizeof($card_info);
				$table_data=array(		
						'action_name'=>$this->config->item("card_distribution"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"Distribute card from:$card_serial_from to:$card_serial_to, Card Type:$card_type, Number of Card:$total_card, Dealer Id:$dealer_id"
						);
				$this->general_model->save_into_table('apninv_action_log', $table_data);
				}
				
			}
			else
			{
			echo 'You have no permission to access this feature';				
			}		
		
		}
		else
		{
		echo "Need signin";
		}
	}
	
	
	public function save_distribution()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('can_distribute_card_among_distributors'))
			{
			$card_serial_from=$this->input->post('card_serial_from');
			$card_serial_to=$this->input->post('card_serial_to');
			$card_type=$this->input->post('card_type');
			$distributor_id=$this->input->post('distributor_id');
			//echo "From:".$card_serial_from.", To:".$card_serial_to.", Type:".$card_type;
			$check_query="SELECT card_id, card_serial, card_type, active_status 
									FROM apninv_card_inventory 
									WHERE card_serial>='".$card_serial_from."' 
									AND card_serial<='".$card_serial_to."' 
									AND card_type=".$card_type." 
									AND active_status<>2
									AND card_id NOT IN (SELECT card_id FROM apninv_card_distribution 
									WHERE apninv_card_distribution.card_id = apninv_card_inventory.card_id)";
					
			$card_info=$this->general_model->get_all_querystring_result($check_query);
				foreach($card_info as $card)
				{				
				$distribution_data=array(
						'card_id'=>$card->card_id,
						'distributor_dealer_id'=>$distributor_id,
						'distributor_or_dealer'=>'di',
						'card_owner_id'=>$data['account']->id,
						'create_user_id'=>$data['account']->id,
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);				
				$success_or_fail=$this->general_model->save_into_table('apninv_card_distribution', $distribution_data);
					if($success_or_fail)
					{
					echo '<span class="text-success">Card Serial:'.$card->card_serial.' distribution successful</span><br/>';		
					}
					else
					{
					echo '<span class="text-danger">Card Serial:'.$card->card_serial.' distribution unsuccessful</span><br/>';	
					}
				}
				
				if($card_info)
				{
				/********** Insert into log table(apninv_action_log) *************/
				$total_card=sizeof($card_info);
				$table_data=array(		
						'action_name'=>$this->config->item("card_distribution"),
						'action_perform_by'=>$data['account']->id,											
						'action_date_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'action_details'=>"Distribute card from:$card_serial_from to:$card_serial_to, Card Type:$card_type, Number of Card:$total_card, Distributor Id:$distributor_id"
						);
				$this->general_model->save_into_table('apninv_action_log', $table_data);
				}
				
			}
			else
			{
			echo 'You have no permission to access this feature';				
			}		
		
		}
		else
		{
		echo "Need signin";
		}
	}
	
	public function distributor_create_distribution($distributor_id)
	{		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_distribute_card_among_own_dealers'))
			{
				
				if($distributor_id==$data['account']->id)
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
				$config["base_url"] = base_url() . "distribution/distribution/distributor_create_distribution/".$distributor_id."/";
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
				
				$this->load->view('distribution/dealer_list_of_distributor', isset($data) ? $data : NULL);	
				}
				else
				{
				$this->session->set_flashdata('parmission', 'You have no permission to access other distributor feature');
				redirect('./dashboard');  // if not permitted "can_create_distributors" redirect to home page	
				}
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
	
	
	
	public function create_distribution()
	{		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_distribute_card_among_distributors'))
			{			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_distributors_list');	
							
			
 		  	$searchterm='SELECT * FROM apninv_distributors Order by distributor_id Desc';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "distribution/distribution/create_distribution/";
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
			
			$this->load->view('distribution/distributors_list', isset($data) ? $data : NULL);
			
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
			
			if($this->authorization->is_permitted('can_distribute_card_among_distributors'))
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
			$config["base_url"] = base_url() . "distribution/distribution/distributors_list_search/";
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
			
			$this->load->view('distribution/distributors_list', isset($data) ? $data : NULL);
			
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
/* Location: ./system/application/controllers/distribution.php */