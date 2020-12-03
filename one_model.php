<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class One_model extends CI_Model
{
  /**
   * IMPORTANT!! PLEASE READ BEFORE USE
   * 1. primary key field in database must be named as "id" not "user_id"
   * 2. all tables in database must have created_by, created_at, updated_by, updated_at, deleted_by, deleted_at field
   */
  
  private $soft_delete = true;

  public function __construct()
  {
      
    parent::__construct();
  }

  /**
   * 
   * Return one row of record in object
   * Optional parameters
   *  1. org_id: to specify any org_id or set org_id = 0 to query without org_id
   *  2. query_field: to specify field to query. if not set, will use primary field (id)
   *  3. return_field: to specify only one field name to return instead of one row of record   
   * 
   */
  public function get($table, $id, $options = array())
  {
    // default value
    $request_field = $table.".id";
  
    // options
    if (isset($options['org_id'])) {
      $org_id = $options['org_id'];
    }
    if (isset($options['return_field'])) {
      $return_field = $options['return_field'];
    }

    if (isset($options['request_field'])) {
      $request_field = $options['request_field'];
    }

    // check if table has org_id field
    // if has org_id field, org_id will be taken from parameter or session. 
    // if no value, then will not include org_id condition
    if ($this->db->field_exists('org_id', $table)) {
      $org_id_field = $table . '.org_id';
      // check if org_id is sent in parameter and value > 0
      if (isset($options['org_id'])) { 
        $org_id = $options['org_id'];
        if ($org_id > 0) { 
          $this->db->where($org_id_field, $org_id);
        }
      }
      // check if org_id in session is set
      else {
        $this->db->where($org_id_field, $this->session->org_id);
      }
    }
    
    // additional conditions
    $this->db->where($request_field, $id);    
    $this->db->where($table . '.deleted_at is null'); // check not deleted
    
    $q = $this->db->get($table);

    if ($q->num_rows() > 0) {
      if (empty($return_field)) {
        return $q->row();
      } else {
        return $q->row($return_field);
      }
    }
    return false;
  }

   // Return all records in the table
   public function get_all($table)
   {
     // if multi tenant
     if ($this->db->field_exists('org_id', $table)){
       $org_id_field = $table.'.org_id';
       $this->db->where($org_id_field, $this->session->org_id);
     }
 
     //get only active sessions based on session
     if (isset($this->session->sessions)) {
       if ($this->db->field_exists('sessions', $table)){
         $sessions_field = $table.'.sessions';
         $this->db->where($sessions_field, $this->session->sessions);
       }
     }
 
     $q = $this->db->get($table);
     if($q->num_rows() > 0)
     {
         return $q->result();
     }
     return array();
   }

  /**
   * Return all records in array
   * Normally will add where condition (where) in controller or model before calling this function
   * 
   * Optional paremeter:
   *  1: org_id: to specify any org_id or set org_id = 0 to query without org_id
   */
  public function get_list($table, $options = array())
  {
    // check if table has org_id field
    // if has org_id field, org_id will be taken from parameter or session. 
    // if no value, then will not include org_id condition
    if ($this->db->field_exists('org_id', $table)) {
      $org_id_field = $table . '.org_id';
      // check if org_id is sent in parameter and value > 0
      if (isset($options['org_id'])) { 
        $org_id = $options['org_id'];
        if ($org_id > 0) { 
          $this->db->where($org_id_field, $org_id);
        }
      }
      // check if org_id in session is set
      else {
        $this->db->where($org_id_field, $this->session->org_id);
      }
    }

    // if soft delete enabled
    $this->db->where($table . '.deleted_at is null');

    $q = $this->db->get($table);

    if ($q->num_rows() > 0) {
      return $q->result();
    }
    return array();
  }

  /**
   * Return reference data in array - key & value
   * Optional parameters:
   *  1. dropdown: '' = not a dropdown; 'select' = "Please Select; 'all' = "All Values"
   *  2. options: key = value for key; value = value for value
   */
  public function get_ref($table, $dropdown = false, $options = array())
  {
    // value for array key
    if (isset($options['key'])) {
      $key = $options['key'];
    } else {
      $key = "id";
    }

    // value for array value (element)
    if (isset($options['value'])) {
      $value = $options['value'];
    } else {
      $value = "name";
    }

    // if multitenant
    if ($this->db->field_exists('org_id', $table)) {
      $org_id_field = $table . '.org_id';
      // check if org_id is sent in parameter and value > 0
      if (isset($options['org_id'])) { 
        $org_id = $options['org_id'];
        if ($org_id > 0) { 
          $this->db->where($org_id_field, $org_id);
        }
      }
      // check if org_id in session is set
      else {
        $this->db->where($org_id_field, $this->session->org_id);
      }
    }

    // if soft delete enabled
    if ($this->soft_delete) {
      $this->db->where($table.'.deleted_at is null');
    }

    $this->db->from($table);
    $this->db->order_by($value);
    $result = $this->db->get();

    // set if the ref is dropdown, set the default dropdown
    $array = array();
    if ($dropdown) {
      if ($dropdown == 'please_select') {
        $array = array('' => lang('gen_please_select'));
      } elseif ($dropdown == 'all') {
        $array = array('' => lang('gen_all'));
      } elseif ($dropdown == 'exact') {
      } else {
        $array[''] = $dropdown;
      }
    }

    // loop to fill up the ref in an array and return the earray
    if ($result->num_rows() > 0) {
      foreach ($result->result_array() as $row) {
        $array[$row[$key]] = $row[$value];
      }
    }
    return $array;
  }
  
  public function insert($table, $data)
  {
    // if multi tenant
    if ($this->db->field_exists('org_id', $table) && !isset($data['org_id'])) {
      $data['org_id'] = $this->session->org_id;
    }

    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by'] = $this->session->user_id;
    $this->db->insert($table, $data);
    return $this->db->insert_id();
  }

  public function update($table, $data, $id, $primaryfield = "id")
  {
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['updated_by'] = $this->session->user_id;
    $this->db->where($primaryfield, $id);
    $q = $this->db->update($table, $data);
    return $q;
  }

  public function delete($table, $id, $primaryfield = "id")
  {
    if ($this->soft_delete) {
      $data['deleted_at'] = date('Y-m-d H:i:s');
      $data['deleted_by'] = $this->session->user_id;
      $this->update($table, $data, $id, $primaryfield);
    } else {
      $this->db->where($primaryfield, $id);
      $this->db->delete($table);
    }
  }

  public function is_exist($value_to_search, $field, $table)
  {
    $this->db->select($field);
    $this->db->where($field, $value_to_search);
    $this->db->where($table . '.deleted_at is null');
    $result = $this->db->get($table);

    if ($result->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function check_same_org($org_id)
	{
		if ($this->session->org_id != $org_id) {
			display_alert('danger', lang('gen_record_not_found'));
			redirect('alert');
		}
		else {
			return true;
		}
	}
  
}
