<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menucontroller extends CI_Controller {
    public function __construct(){
        parent :: __construct();
        $this->load->model('one_model');
    }
	
	public function index()

        {
            $this->listing();
        }

    public function listing(){
        $data['result'] = $this->one_model->get_list('orders');
		$this->load->view('menu/form',$data);
    }
    public function add(){
        $this->add_process();
        redirect("Menucontroller");
    }
    public function add_process(){
        $post = $this->input->post();
        $data_array = array (
            'name' => $post['name'],
            'price' => $post['price'],
            'ingredient' => $post['ingredient'],  
        );
		$this->one_model->insert('orders', $data_array);
        redirect("Menucontroller");
    
    }
    public function update($id) {
        $data['row'] = $this->one_model->get('orders',$id);
        $this->load->view('menu/formedit',$data);
    }
    public function update_process($id){
        $post = $this->input->post();
        $data_array = array (
            'name' => $post['name'],
            'price' => $post['price'],
            'ingredient' => $post['ingredient'],  
        );
		$this->one_model->update('orders', $data_array, $id);
        redirect("Menucontroller");
    }
    public function delete(){
        $id = $this->uri->segment(3);
        $this->one_model->delete('orders',$id);
        redirect("Menucontroller");
    }
}
