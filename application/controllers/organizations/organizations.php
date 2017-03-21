<?php
class Organizations extends CI_Controller {

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
			if($this->authorization->is_permitted('can_view_org_list'))
			{
			redirect('./organizations/org_list');	
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
	
	
	public function org_list()
	{		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_view_org_list'))
			{
			
			$this->load->helper("url");	
			$this->load->library('pagination');	
			$data['title'] = lang('menu_organizations');	
							
			
 		  	$searchterm='SELECT * FROM outreach_partners Order by tx_partner_name asc';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "organizations/organizations/org_list/";
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
			$data['all_org'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;	
			$data["total_records"]=$config["total_rows"];
			
			$this->load->view('organizations/org_list', isset($data) ? $data : NULL);			
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
	
	public function create_org()
	{		
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('can_create_org'))
			{
			
			
			$data['title'] = lang('menu_create_org') ;	
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
				
			$this->form_validation->set_rules('org_name', 'Organization Name', 'required');
			$this->form_validation->set_rules('org_type', 'Organization Type', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				$this->load->view('organizations/create_org', isset($data) ? $data : NULL);		
				}
				else
				{										
				$org_data=array(
						'tx_partner_name'=>$this->input->post('org_name'),
						'tx_partner_type'=>$this->input->post('org_type'),
						'tx_partner_address'=>$this->input->post('address'),
						'tx_color'=>$this->input->post('color'),
						'is_active'=>1,
						'int_mod_user_key'=>$data['account']->id,
						'dtt_mod'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
				$new_org_id=$this->general_model->save_into_table_and_return_insert_id('outreach_partners', $org_data);	
				
				/********** Image upload	********************/
				if(isset($_FILES['org_logo']) && $_FILES['org_logo']['size'] > 0)		
				{

				$config['upload_path'] = RES_DIR."/org/";
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '500';
				$config['overwrite'] = 'TRUE';
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '100%';
				$config['max_width']  = '400';
				$config['max_height']  = '500';
				$config['file_name']  = $new_org_id;
			
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('org_logo'))
					{
						
						$data['error'] = $this->upload->display_errors();								
					}
					else
					{
						$image_data = $this->upload->data();												
						$original_image= $image_data['raw_name'].$image_data['file_ext'];
						
						$config2 = array(
						'source_image' => $image_data['full_path'],
						'new_image' => RES_DIR."/org/",
						'image_name'=> $new_org_id,
						'maintain_ratio' => false,
						'overwrite' => true,
						'maintain_ratio' => FALSE,
						'quality' => '100%',
						'width' => 100,
						'height' => 100
						);
						$this->load->library('image_lib');
						$this->image_lib->initialize($config2);
						if ( !$this->image_lib->resize()){
							$data['error'] = $this->image_lib->display_errors('', '');
              				}																		
						
						
						$data['upload_data'] = $this->upload->data();
						
						$image_table=array(						
						'int_outreach_partner_key'=>$original_image						
						);				
		
					$success_or_fail2=$this->general_model->update_table('outreach_partners',$image_table,'int_outreach_partner_key', $new_org_id);										
					
					}							
				}
				
				if($new_org_id)
					$data['success_msg']=lang('saveed_successfully');
				else
					$data['error_msg']=lang('save_unsuccessfull');
					
				$this->load->view('organizations/create_org', isset($data) ? $data : NULL);		
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
	

}


/* End of file home.php */
/* Location: ./system/application/controllers/card_generation.php */