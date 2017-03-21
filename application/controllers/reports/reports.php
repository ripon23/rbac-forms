<?php
class Reports extends CI_Controller {

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
			if($this->authorization->is_permitted('can_see_inventory_summary_report'))
			{
			redirect('./reports/reports/inventory_summary_report/');	
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
	
	public function download_to_excel()
	{
		if ($this->authentication->is_signed_in())
			{
				$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
				if($this->authorization->is_permitted('can_download_inventory_summary_report'))
				{
				$searchterm = $this->session->userdata('searchterm');
				
				/*$query_all_card_type="SELECT DISTINCT apninv_card_inventory.card_type,
					apninv_card_type.card_id,
					apninv_card_type.card_amount,
					apninv_card_type.card_display_name,
					apninv_card_type.status,
					apninv_card_type.display_order
	  FROM apninv_card_inventory apninv_card_inventory
		   INNER JOIN apninv_card_type apninv_card_type
			  ON (apninv_card_inventory.card_type = apninv_card_type.card_id)
	ORDER BY apninv_card_type.display_order ASC";*/
				$result_all_card_type=$this->general_model->get_all_querystring_result($searchterm);
										
				$data['all_card_type']=$result_all_card_type;
								
				$this->load->view('reports/export_to_excel_summary', isset($data) ? $data : NULL);		
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

	
	public function inventory_summary_report()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_see_inventory_summary_report'))
			{
			$query_all_card_type="SELECT DISTINCT apninv_card_inventory.card_type,
				apninv_card_type.card_id,
                apninv_card_type.card_amount,
                apninv_card_type.card_display_name,
                apninv_card_type.status,
                apninv_card_type.display_order
  FROM apninv_card_inventory apninv_card_inventory
       INNER JOIN apninv_card_type apninv_card_type
          ON (apninv_card_inventory.card_type = apninv_card_type.card_id)
ORDER BY apninv_card_type.display_order ASC";
			$result_all_card_type=$this->general_model->get_all_querystring_result($query_all_card_type);
									
			$data['all_card_type']=$result_all_card_type;
			
			$searchterm = $this->general_model->searchterm_handler($query_all_card_type);
			
			$this->load->view('reports/inventory_summary_report', isset($data) ? $data : NULL);		
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
	
	public function inventory_summary_report_search()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_see_inventory_summary_report'))
			{
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$this->load->library('form_validation');
			$data['title'] = lang('action_view');	
			
				if($this->input->post("search_submit"))
				{
				// assign posted valued				
				$data['date_from']     		= $this->input->post("date_from");
				$data['date_to']     		= $this->input->post("date_to");
				
				$query_string="SELECT DISTINCT apninv_card_inventory.card_type,
				apninv_card_type.card_id,
                apninv_card_type.card_amount,
                apninv_card_type.card_display_name,
                apninv_card_type.status,
                apninv_card_type.display_order
  FROM apninv_card_inventory apninv_card_inventory
       INNER JOIN apninv_card_type apninv_card_type
          ON (apninv_card_inventory.card_type = apninv_card_type.card_id)";
				
				if($this->input->post("date_from") && $this->input->post("date_to"))	
					{
						$date_from=$this->input->post("date_from"); 
						$date_to=$this->input->post("date_to"); 
						$query_string=$query_string." Where DATE(create_date) between '$date_from' AND '$date_to'";
					}
					
				$query_string=$query_string." ORDER BY apninv_card_type.display_order ASC";																	
				$searchterm = $this->general_model->searchterm_handler($query_string);												
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
			
			//echo $searchterm;
			$result_all_card_type=$this->general_model->get_all_querystring_result($searchterm);									
			$data['all_card_type']=$result_all_card_type;
			$this->load->view('reports/inventory_summary_report', isset($data) ? $data : NULL);		
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
	
	
	public function distributor_card_summary()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_see_distributor_card_summary'))
			{			
			$data['title'] = lang('menu_distribution_summary_report');		
			$this->load->helper("url");	
				$this->load->library('pagination');
				
			$query_all_card_type="SELECT apninv_distributors.distributor_id,
				   apninv_distributors.distributor_code,
				   apninv_distributors.distributor_name,
				   apninv_distributors.distributor_mobile,
				   apninv_distributors.distributor_division,
				   apninv_distributors.distributor_district,
				   apninv_distributors.distributor_upazila,
				   apninv_distributors.distributor_union,
				   apninv_distributors.active_status
			  FROM apninv_distributors apninv_distributors
			ORDER BY apninv_distributors.distributor_id ASC";
			$result_all_distributor=$this->general_model->get_all_querystring_result($query_all_card_type);

			$query_card_type="SELECT apninv_card_type.card_id,
				   apninv_card_type.card_amount,
				   apninv_card_type.card_display_name,
				   apninv_card_type.display_order,
				   apninv_card_type.status
			  FROM apninv_card_type apninv_card_type
			ORDER BY apninv_card_type.display_order ASC";
			$query_card_type_result=$this->general_model->get_all_querystring_result($query_card_type);

			$district="district_name";
			$total_card="total_card";
			$recharge_card="recharge_card_count";
			
			
			foreach ($result_all_distributor as $row) {
                
				$total_card_count=0;
				
				foreach($query_card_type_result as $type_rows)
				{
				/***********************   Location Name   **************************/
				$district_name=$this->ref_location_model->get_district_name_from_id($row->distributor_district);				
				$row->$district = $district_name;
				
				$query="SELECT count(*) as total_rows
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $row->distributor_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'di') 
									   AND (apninv_card_inventory.card_type = $type_rows->card_id)";
				$card_count=$this->general_model->count_total_rows($query);	
				
				/***********************   Recharged Card   **************************/
				$query2="SELECT count(*) as total_rows
								  FROM apninv_card_distribution apninv_card_distribution
									   INNER JOIN apninv_card_inventory apninv_card_inventory
										  ON (apninv_card_distribution.card_id =
												 apninv_card_inventory.card_id)
								 WHERE     (apninv_card_distribution.distributor_dealer_id = $row->distributor_id)
									   AND (apninv_card_distribution.distributor_or_dealer = 'di') 
									   AND (apninv_card_inventory.active_status =2)";
				$recharge_card_count=$this->general_model->count_total_rows($query2);
				
				//echo "di=".$row->distributor_id.", Card Type=".$type_rows->card_id.", Number=".$card_count."<br/>";
				$tk="tk_".$type_rows->card_amount;
				$row->$tk = $card_count;
				
				$total_card_count=$total_card_count+$card_count;
				$row->$total_card = $total_card_count;
				$row->$recharge_card = $recharge_card_count;
				}              
				
				//$result_all_distributor[] = $row;
             }					
				
			//echo "<pre>";
			//print_r($result_all_distributor);
			//echo "</pre>";
			
			//pagination
			$config = array();								
			$start = $this->uri->segment(4);
			$data['result_all_distributor'] = array_slice($result_all_distributor,$start,$this->config->item("pagination_perpage"));
			$config['base_url'] = base_url() . "reports/reports/distributor_card_summary/";
			$config['total_rows'] = count($result_all_distributor);
			$config['per_page'] = $this->config->item("pagination_perpage");
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
			$data["links"] = $this->pagination->create_links();
			//$data['result_all_distributor']=$result_all_distributor;
			$this->load->view('reports/distributor_summary_report', isset($data) ? $data : NULL);
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
	
	
	public function download_distributor_card_summary()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('can_download_distributor_card_summary'))
			{
			$query_all_card_type="SELECT apninv_distributors.distributor_id,
				   apninv_distributors.distributor_code,
				   apninv_distributors.distributor_name,
				   apninv_distributors.distributor_mobile,
				   apninv_distributors.distributor_division,
				   apninv_distributors.distributor_district,
				   apninv_distributors.distributor_upazila,
				   apninv_distributors.distributor_union,
				   apninv_distributors.active_status
			  FROM apninv_distributors apninv_distributors
			ORDER BY apninv_distributors.distributor_id ASC";
			$result_all_distributor=$this->general_model->get_all_querystring_result($query_all_card_type);
				
				
			$query_card_type="SELECT apninv_card_type.card_id,
				   apninv_card_type.card_amount,
				   apninv_card_type.card_display_name,
				   apninv_card_type.display_order,
				   apninv_card_type.status
			  FROM apninv_card_type apninv_card_type
			ORDER BY apninv_card_type.display_order ASC";
			$query_card_type_result=$this->general_model->get_all_querystring_result($query_card_type);

			$district="district_name";
			$total_card="total_card";
			$recharge_card="recharge_card_count";
			
			foreach ($result_all_distributor as $row) 
				{
                
				$total_card_count=0;
				
					foreach($query_card_type_result as $type_rows)
					{
					/***********************   Location Name   **************************/
					$district_name=$this->ref_location_model->get_district_name_from_id($row->distributor_district);
					$row->$district = $district_name;
					
					$query="SELECT count(*) as total_rows
									  FROM apninv_card_distribution apninv_card_distribution
										   INNER JOIN apninv_card_inventory apninv_card_inventory
											  ON (apninv_card_distribution.card_id =
													 apninv_card_inventory.card_id)
									 WHERE     (apninv_card_distribution.distributor_dealer_id = $row->distributor_id)
										   AND (apninv_card_distribution.distributor_or_dealer = 'di') 
										   AND (apninv_card_inventory.card_type = $type_rows->card_id)";
					$card_count=$this->general_model->count_total_rows($query);		
					
					/***********************   Recharged Card   **************************/
					$query2="SELECT count(*) as total_rows
									  FROM apninv_card_distribution apninv_card_distribution
										   INNER JOIN apninv_card_inventory apninv_card_inventory
											  ON (apninv_card_distribution.card_id =
													 apninv_card_inventory.card_id)
									 WHERE     (apninv_card_distribution.distributor_dealer_id = $row->distributor_id)
										   AND (apninv_card_distribution.distributor_or_dealer = 'di') 
										   AND (apninv_card_inventory.active_status =2)";
					$recharge_card_count=$this->general_model->count_total_rows($query2);
				
					$tk="tk_".$type_rows->card_amount;
					$row->$tk = $card_count;
					
					$total_card_count=$total_card_count+$card_count;
					$row->$total_card = $total_card_count;
					$row->$recharge_card = $recharge_card_count;
					}              
					
            	}	
			$data['result_all_distributor'] = $result_all_distributor;
			$this->load->view('reports/distribution_export_to_excel', isset($data) ? $data : NULL);
			
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

}


/* End of file home.php */
/* Location: ./system/application/controllers/reports/reports.php */