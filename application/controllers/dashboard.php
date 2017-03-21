<?php

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		$this->load->model(array('account/account_model','general_model','project_site/site_model'));
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('menu', 'english');
		$this->lang->load('site', 'english');
		$this->lang->load('formlabel', 'english');
		$this->lang->load('card', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('menu', $language);
		$this->lang->load('site', $language);
		$this->lang->load('formlabel', $language);
		$this->lang->load('card', $language);
		}
		
	}




	function index()
	{
		maintain_ssl();

		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$highest_role=100;
		$all_user_role=$this->site_model->get_all_user_role($data['account']->id);
			foreach ($all_user_role as $user_role) :
				if($user_role->role_id<$highest_role)
				$highest_role=$user_role->role_id;
			endforeach; 
		
		if($highest_role==$this->config->item("distributor_role_id"))
		$this->load->view('view_dashboard_distributor', isset($data) ? $data : NULL); //Distributor Dashboard
		else
		$this->load->view('view_dashboard', isset($data) ? $data : NULL); //Admin Dashboard
		
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
		

} // End Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */