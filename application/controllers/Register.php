<?php
/* halaman register utama 
   author by Kassandra Production
*/

class Register extends CI_controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->helper('url');
      $this->load->database();
      $this->load->library('session');
      $this->load->model('m_pengguna');
      $this->load->library('form_validation');
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
    
     //mengambil id dokter urut terakhir
     private function id_pengguna_urut($value='')
     {
     $this->m_pengguna->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_pengguna'];
     $karakter= $this->acak_id(6);
     $urut    = substr($id, 1, 3);
     $tambah  = (int) $urut + 1;
     
     if (strlen($tambah) == 1){
     $newID = "A"."00".$tambah.$karakter;
         }else if (strlen($tambah) == 2){
         $newID = "A"."0".$tambah.$karakter;
             }else (strlen($tambah) == 3){
             $newID = "A".$tambah.$karakter
             };
         return $newID;
     }

  //API add 
  public function api_add($value='')
  {
    $rules = array(
      array(
        'field' => 'nama',
        'label' => 'Nama',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Nama tidak boleh kosong',
        ),
      ),
      array(
        'field' => 'no_hp',
        'label' => 'NO HP',
        'rules' => 'required|is_unique[tb_pengguna.no_hp]',
        'errors' => array(
            'required' => 'No HP tidak boleh kosong',
            'is_unique' => 'No HP sudah terdaftar',
        ),
      ),
      array(
        'field' => 'keterangan',
        'label' => 'Keterangan',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Keterangan tidak boleh kosong',
        ),
      ),
      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email|is_unique[tb_pengguna.email]',
        'errors' => array(
            'required' => 'Email tidak boleh kosong',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar',
        ),
      ),

    );
    $this->form_validation->set_rules($rules);
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => false,
        'message' => validation_errors()
      ];
    } else {
      $SQLinsert = [
        'id_pengguna'       =>$this->id_pengguna_urut(),
        'nama'              =>$this->input->post('nama'),
        'no_hp'              =>$this->input->post('no_hp'),
        'keterangan'        =>$this->input->post('keterangan'),
        'email'             =>$this->input->post('email'),
        'password'          =>md5($this->input->post('password')),
        'id_level'          =>'3',
      ];
      if ($this->m_pengguna->add($SQLinsert)) {
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

}
