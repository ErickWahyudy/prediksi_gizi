<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Balita extends CI_controller
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
	}

    //Lihat Data
    public function index($value='')
    {
     $view = array('judul'      =>'Data Balita',
                    'aksi'      =>'lihat',
                    'data'      =>$this->m_balita->view()->result_array(),
                  );

      $this->load->view('admin/balita/lihat',$view);
    }

    //Tambah Data
    public function tambah_data($value='')
    {
     $view = array('judul'      =>'Tambah Data Balita',
                    'aksi'      =>'tambah',
                  );

      $this->load->view('admin/balita/tambah',$view);
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
     private function id_balita_urut($value='')
     {
     $this->m_balita->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_balita'];
     $karakter= $this->acak_id(6);
     $urut    = substr($id, 1, 3);
     $tambah  = (int) $urut + 1;
     
     if (strlen($tambah) == 1){
     $newID = "B"."00".$tambah.$karakter;
         }else if (strlen($tambah) == 2){
         $newID = "B"."0".$tambah.$karakter;
             }else (strlen($tambah) == 3){
             $newID = "B".$tambah.$karakter
             };
         return $newID;
     }

  //API add
  public function api_add($value='')
  {
    $rules = array(
      array(
        'field' => 'nama',
        'label' => 'Nama Balita',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Nama Balita tidak boleh kosong',
          ),
        ),
      array(
          'field' => 'tgl_lahir',
          'label' => 'Tanggal Lahir',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Tanggal Lahir tidak boleh kosong',
            ),
          ),
      array(
          'field' => 'tempat_lahir',
          'label' => 'Tempat Lahir',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Tempat Lahir tidak boleh kosong',
            ),
          ),
      array(
          'field' => 'alamat',
          'label' => 'Alamat',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Alamat tidak boleh kosong',
            ),
          ),
      array(
          'field' => 'nama_ayah',
          'label' => 'Nama Ayah',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Nama Ayah tidak boleh kosong',
            ),
          ),
      array(
          'field' => 'nama_ibu',
          'label' => 'Nama Ibu',
          'rules' => 'required',
          'errors' => array(
              'required' => 'Nama Ibu tidak boleh kosong',
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
        'id_balita'      =>$this->id_balita_urut(),
        'nama'           =>$this->input->post('nama'),
        'jenis_kelamin'  =>$this->input->post('jenis_kelamin'),
        'tgl_lahir'      =>$this->input->post('tgl_lahir'),
        'tempat_lahir'   =>$this->input->post('tempat_lahir'),
        'alamat'         =>$this->input->post('alamat'),
        'nama_ayah'      =>$this->input->post('nama_ayah'),
        'nama_ibu'       =>$this->input->post('nama_ibu'),
      ];
      if ($this->m_balita->add($SQLinsert)) {
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
            'field' => 'nama',
            'label' => 'Nama Balita',
            'rules' => 'required',
            'errors' => array(
                'required' => 'Nama Balita tidak boleh kosong',
              ),
            ),
          array(
              'field' => 'tgl_lahir',
              'label' => 'Tanggal Lahir',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Tanggal Lahir tidak boleh kosong',
                ),
              ),
          array(
              'field' => 'tempat_lahir',
              'label' => 'Tempat Lahir',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Tempat Lahir tidak boleh kosong',
                ),
              ),
          array(
              'field' => 'alamat',
              'label' => 'Alamat',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Alamat tidak boleh kosong',
                ),
              ),
          array(
              'field' => 'nama_ayah',
              'label' => 'Nama Ayah',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Nama Ayah tidak boleh kosong',
                ),
              ),
          array(
              'field' => 'nama_ibu',
              'label' => 'Nama Ibu',
              'rules' => 'required',
              'errors' => array(
                  'required' => 'Nama Ibu tidak boleh kosong',
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
            'nama'           =>$this->input->post('nama'),
            'jenis_kelamin'  =>$this->input->post('jenis_kelamin'),
            'tgl_lahir'      =>$this->input->post('tgl_lahir'),
            'tempat_lahir'   =>$this->input->post('tempat_lahir'),
            'alamat'         =>$this->input->post('alamat'),
            'nama_ayah'      =>$this->input->post('nama_ayah'),
            'nama_ibu'       =>$this->input->post('nama_ibu'),
          ];
          if ($this->m_balita->update($id, $SQLupdate)) {
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
          if ($this->m_balita->delete($id)) {
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