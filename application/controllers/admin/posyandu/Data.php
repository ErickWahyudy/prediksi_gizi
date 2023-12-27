<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Data extends CI_controller
{
  function __construct()
	{
	 parent:: __construct();
     $this->load->helper('url');
      // needed ???
      $this->load->database();
      $this->load->library('session');
      $this->load->library('form_validation');
      
	 // error_reporting(0);
	 if($this->session->userdata('admin') != TRUE){
     redirect(base_url(''));
     exit;
	};
   $this->load->model('m_balita');
   $this->load->model('m_posyandu');
	}

    
    //Lihat Data
    public function index($value='')
    {
      if (isset($_POST['cari'])) {
        //cek data apabila berhasi Di kirim maka postdata akan melakukan cek .... dan sebaliknya
        $bulan =$this->input->post('bulan');
        $tahun =$this->input->post('tahun');
        $data = $this->m_posyandu->view()->row_array();
        $view = array('judul'                  =>'Data Posyandu',
                        'aksi'                  =>'lihat',
                        'data'                  =>$this->m_posyandu->view($bulan,$tahun)->result_array(),
                        'bulan'                 =>$bulan,
                        'tahun'                 =>$tahun,
                        'depan'                 =>FALSE,
                      );
        $this->load->view('admin/posyandu/lihat',$view);
      }else{
        $view = array('judul'                   =>'Buka Data Posyandu',
                        'aksi'                  =>'lihat',
                        'depan'                 =>TRUE,
                      );
        $this->load->view('admin/posyandu/lihat',$view);
      }
    }
    
    //Add Data
    public function add($value='')
    {
      if (isset($_POST['cari'])) {
        //cek data apabila berhasi Di kirim maka postdata akan melakukan cek .... dan sebaliknya
        $bulan =$this->input->post('bulan');
        $tahun =$this->input->post('tahun');

        $data = $this->m_balita->view()->row_array();
        $view = array('judul'                   =>'Buat Data Posyandu',
                        'aksi'                  =>'lihat',
                        'balita'                =>$this->m_balita->view()->result_array(),
                        'bulan'                 =>$bulan,
                        'tahun'                 =>$tahun,
                        'depan'                 =>FALSE,
                      );
        $this->load->view('admin/posyandu/add',$view);
      }else{
        $view = array('judul'                   =>'Buat Data Posyandu',
                        'aksi'                  =>'lihat',
                        'depan'                 =>TRUE,
                      );
        $this->load->view('admin/posyandu/add',$view);
      }
    }

    private function acak_id($panjang)
    {
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $string = '';
        for ($i = 0; $i < $panjang; $i++) {
            $pos = rand(0, strlen($karakter) - 1);
            $string .= $karakter{$pos};
        }
        return $string;
    }
    
     //mengambil id urut terakhir
     private function id_posyandu_urut($value='')
     {
     $this->m_posyandu->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_posyandu'];
     $karakter= $this->acak_id(6);
     $urut    = substr($id, 1, 3);
     $tambah  = (int) $urut + 1;
     
     if (strlen($tambah) == 1){
     $newID = "P"."00".$tambah.$karakter;
         }else if (strlen($tambah) == 2){
         $newID = "P"."0".$tambah.$karakter;
             }else (strlen($tambah) == 3){
             $newID = "P".$tambah.$karakter
             };
         return $newID;
     }

  //API add
  public function api_add($value='')
  {
    $rules = array(
      array(
        'field' => 'umur',
        'label' => 'Umur',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Umur tidak boleh kosong',
          ),
        ),
      array(
          'field' => 'berat_bb',
          'label' => 'Berat Badan',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Berat Badan tidak boleh kosong',
            ),
          ),
      array(
          'field' => 'tinggi_bb',
          'label' => 'Tinggi Badan',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Tinggi Badan tidak boleh kosong',
            ),
          ),
    );
    $this->form_validation->set_rules($rules);
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => false,
        'message' => validation_errors(),
      ];
    } else {
      $SQLinsert = [
        'id_posyandu'    =>$this->id_posyandu_urut(),
        'id_balita'      =>$this->input->post('id_balita'),
        'umur'           =>$this->input->post('umur'),
        'berat_bb'       =>$this->input->post('berat_bb'),
        'tinggi_bb'      =>$this->input->post('tinggi_bb'),
        'bulan'          =>$this->input->post('bulan'),
        'tahun'          =>$this->input->post('tahun'),
      ];
      if ($this->m_posyandu->add($SQLinsert)) {
        $response = [
          'status' => true,
          'message' => 'Berhasil menambahkan data'
        ];
      } else {
        $response = [
          'status' => false,
          'message' => 'Gagal menambahkan data'
        ];
      }
  }
  
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}

      //API edit
      public function api_edit($id='', $SQLupdate='')
      {
        $rules = array(
          array(
            'field' => 'tinggi_bb',
            'label' => 'Tinggi Badan',
            'rules' => 'required',
            'errors' => array(
                'required' => 'Tinggi Badan tidak boleh kosong',
              ),
            ),
          array(
              'field' => 'berat_bb',
              'label' => 'Berat Badan',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Berat Badan tidak boleh kosong',
                ),
              ),
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {
          $response = [
            'status' => false,
            'message' => validation_errors(),
          ];
        } else {
          $SQLupdate = [
            'tinggi_bb'      =>$this->input->post('tinggi_bb'),
            'berat_bb'       =>$this->input->post('berat_bb'),
          ];
          if ($this->m_posyandu->update($id, $SQLupdate)) {
            $response = [
              'status' => true,
              'message' => 'Berhasil mengubah data'
            ];
          } else {
            $response = [
              'status' => false,
              'message' => 'Gagal mengubah data'
            ];
          }
      }

      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
      }
      
      //API hapus
      public function api_hapus($id='')
      {
        if(empty($id)){
          $response = [
            'status' => false,
            'message' => 'Data kosong'
          ];
        }else{
          if ($this->m_posyandu->delete($id)) {
            $response = [
              'status' => true,
              'message' => 'Berhasil menghapus data'
            ];
          } else {
            $response = [
              'status' => false,
              'message' => 'Gagal menghapus data'
            ];
          }
        }
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
      }
	
}