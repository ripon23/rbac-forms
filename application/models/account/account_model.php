<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model {	
	
	/**
	 * Get all accounts
	 *
	 * @access public
	 * @return object all accounts
	 */
	 
	function get($name=NULL, $whereClause=NULL, $start=NULL, $limit=NULL)
	{		
		$this->db->select('apninv_a3m_account.id, apninv_a3m_account.username, apninv_a3m_account.email,  apninv_a3m_account_details.fullname, apninv_a3m_acl_role.name');
		$this->db->from('apninv_a3m_account');
		$this->db->join('apninv_a3m_account_details', 'apninv_a3m_account_details.account_id = apninv_a3m_account.id');
		$this->db->join('apninv_a3m_rel_account_role', 'apninv_a3m_rel_account_role.account_id = apninv_a3m_account.id');
		$this->db->join('apninv_a3m_acl_role', 'apninv_a3m_acl_role.id = apninv_a3m_rel_account_role.role_id');
		
		if ($name!==NULL):
		$this->db->like('fullname',$name);
		endif;
		if (is_array($whereClause) && !empty($whereClause)):
		$this->db->where($whereClause);
		endif;
		$this->db->order_by("id", "ASC");		 		
		if($limit!=NULL):
			$this->db->limit($limit, $start);
		endif;
		
		return $this->db->get()->result();
	}
	
	function get_list_multi_clause($table_name=NULL, $field_array=NULL, $select=NULL, $order_by=NULL, $asc_or_desc=NULL, $start=NULL, $limit=NULL)
	{
		if ($select!=NULL):
		$this->db->select($select);
		endif;
		if($order_by!=NULL && $asc_or_desc!=NULL):
			$this->db->order_by($order_by, $asc_or_desc);
		endif;
		if(is_array($field_array) && count($field_array)>0):
			$this->db->where($field_array);
		endif;
		if($start!=NULL && $limit!=NULL):
			$this->db->limit($limit, $start);
		endif;
		$query = $this->db->get($table_name);
		//if($query->num_rows()>0):
		return $query->result();
			
	}

	/**
	 * Get account by id
	 *
	 * @access public
	 * @param string $account_id
	 * @return object account object
	 */
	function get_by_id($account_id)
	{
		return $this->db->get_where('apninv_a3m_account', array('id' => $account_id))->row();
	}


	function get_username_by_id($account_id)
	{
		//return $this->db->get_where('apninv_a3m_account', array('id' => $account_id))->row();
		
		$this->db->select('username');
		$this->db->from('apninv_a3m_account');
		$this->db->where('id', $account_id);		
		$result_set = $this->db->get();
		return $result_set->row()->username;
		//echo $result_set;
		
	}
	// --------------------------------------------------------------------

	/**
	 * Get account by username
	 *
	 * @access public
	 * @param string $username
	 * @return object account object
	 */
	function get_by_username($username)
	{
		return $this->db->get_where('apninv_a3m_account', array('username' => $username))->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Get account by email
	 *
	 * @access public
	 * @param string $email
	 * @return object account object
	 */
	function get_by_email($email)
	{
		return $this->db->get_where('apninv_a3m_account', array('email' => $email))->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Get account by username or email
	 *
	 * @access public
	 * @param string $username_email
	 * @return object account object
	 */
	function get_by_username_email($username_email)
	{
		return $this->db->from('apninv_a3m_account')->where('username', $username_email)->or_where('email', $username_email)->get()->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Create an account
	 *
	 * @access public
	 * @param string $username
	 * @param string $hashed_password
	 * @return int insert id
	 */
	function create($username, $email = NULL, $password = NULL)
	{
		// Create password hash using phpass
		if ($password !== NULL)
		{
			$this->load->helper('account/phpass');
			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
			$hashed_password = $hasher->HashPassword($password);
		}

		$this->load->helper('date');
		$this->db->insert('apninv_a3m_account', array('username' => $username, 'email' => $email, 'password' => isset($hashed_password) ? $hashed_password : NULL, 'createdon' => mdate('%Y-%m-%d %H:%i:%s', now())));

		return $this->db->insert_id();
	}

	// --------------------------------------------------------------------

	/**
	 * Change account username
	 *
	 * @access public
	 * @param int $account_id
	 * @param int $new_username
	 * @return void
	 */
	function update_username($account_id, $new_username)
	{
		$this->db->update('apninv_a3m_account', array('username' => $new_username), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Change account email
	 *
	 * @access public
	 * @param int $account_id
	 * @param int $new_email
	 * @return void
	 */
	function update_email($account_id, $new_email)
	{
		$this->db->update('apninv_a3m_account', array('email' => $new_email), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Change account password
	 *
	 * @access public
	 * @param int $account_id
	 * @param int $hashed_password
	 * @return void
	 */
	function update_password($account_id, $password_new)
	{
		$this->load->helper('account/phpass');
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$new_hashed_password = $hasher->HashPassword($password_new);

		$this->db->update('apninv_a3m_account', array('password' => $new_hashed_password), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Update account last signed in dateime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function update_last_signed_in_datetime($account_id)
	{
		$this->load->helper('date');

		$this->db->update('apninv_a3m_account', array('lastsignedinon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Update password reset sent datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return int password reset time
	 */
	function update_reset_sent_datetime($account_id)
	{
		$this->load->helper('date');

		$resetsenton = mdate('%Y-%m-%d %H:%i:%s', now());

		$this->db->update('apninv_a3m_account', array('resetsenton' => $resetsenton), array('id' => $account_id));

		return strtotime($resetsenton);
	}

	/**
	 * Remove password reset datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function remove_reset_sent_datetime($account_id)
	{
		$this->db->update('apninv_a3m_account', array('resetsenton' => NULL), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Update account deleted datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function update_deleted_datetime($account_id)
	{
		$this->load->helper('date');

		$this->db->update('apninv_a3m_account', array('deletedon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $account_id));
	}

	/**
	 * Remove account deleted datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function remove_deleted_datetime($account_id)
	{
		$this->db->update('apninv_a3m_account', array('deletedon' => NULL), array('id' => $account_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Update account suspended datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function update_suspended_datetime($account_id)
	{
		$this->load->helper('date');

		$this->db->update('apninv_a3m_account', array('suspendedon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $account_id));
	}

	/**
	 * Remove account suspended datetime
	 *
	 * @access public
	 * @param int $account_id
	 * @return void
	 */
	function remove_suspended_datetime($account_id)
	{
		$this->db->update('apninv_a3m_account', array('suspendedon' => NULL), array('id' => $account_id));
	}

}


/* End of file account_model.php */
/* Location: ./application/account/models/account_model.php */