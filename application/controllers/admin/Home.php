<?php
/**
 * PHP for Codeigniter
 *
 * @package        	CodeIgniter
 * @pengembang		Kassandra Production (https://kassandra.my.id)
 * @Author			@erikwahyudy
 * @version			3.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Home extends CI_controller
{
	function __construct()
	{
	 parent:: __construct();
     $this->load->helper('url');
      // needed ???
      $this->load->database();
      $this->load->library('session');
      
	 // error_reporting(0);
	 if($this->session->userdata('admin') != TRUE){
     redirect(base_url(''));
     exit;
	};
	 $this->load->model('M_admin');
	 $this->load->model('m_balita');

	}

	public function index($id='')
	{
	 $view = array(
        'judul'            	=>'Diagram Balita',
		'data'      		=>$this->m_balita->view()->result_array(),
		//count balita
		'count_balita'		=>$this->db->query("SELECT COUNT(id_balita) as count_balita FROM tb_balita")->row_array(),

     );
	 $this->load->view('template/home',$view);
	}
	
}