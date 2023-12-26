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

class Posyandu extends CI_controller
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
	

	//hitung status gizi dengan metode fuzzy sukamoto
	public function status_gizi() {
		// Mengambil data dari database
		$data = $this->m_balita->view()->result_array();
	 
		// Menyiapkan array untuk menyimpan hasil perhitungan status gizi
		$result = [];
	 
		foreach ($data as $key => $value) {
		   // Mengambil data berat badan, tinggi badan, dan umur
		   $bb = $value['berat_bb'];
		   $tb = $value['tinggi_bb'];
		   $tgl_lahir = new DateTime($value['tgl_lahir']);
		   $tgl_sekarang = new DateTime();
		   
		   $umur_tahun = $tgl_lahir->diff($tgl_sekarang)->y;
		   $umur_bulan = $tgl_lahir->diff($tgl_sekarang)->m;
		   $umur_hari = $tgl_lahir->diff($tgl_sekarang)->d;
		   
		   $total_bulan = $umur_tahun * 12 + $umur_bulan;
		   $umur = $total_bulan;
	 
		   // Menghitung status gizi
		   $status_gizi = $this->hitungFuzzyTsukamoto($umur, $tb, $bb);
		   $total_z_score = $status_gizi['z_score_bb_u'] + $status_gizi['z_score_tb_u'] + $status_gizi['z_score_bb_tb'];
	 
		   // Menyimpan hasil perhitungan status gizi ke dalam array
		   $result[] = [
			  'nama' => $value['nama'],
			  'umur' => $umur,
			  'tinggi_bb' => $tb,
			  'berat_bb' => $bb,
			  'z_score_bb_u' => $status_gizi['z_score_bb_u'],
			  'z_score_tb_u' => $status_gizi['z_score_tb_u'],
			  'z_score_bb_tb' => $status_gizi['z_score_bb_tb'],
			  'total_z_score' => $total_z_score,
			  'status_gizi_bbu' => $status_gizi['status_gizi_bbu'],
			  'status_gizi_tbu' => $status_gizi['status_gizi_tbu'],
			  'status_gizi_bb_tb' => $status_gizi['status_gizi_bb_tb'],
			  'status_gizi' => $status_gizi['status_gizi']
		   ];
		   $view = array(
			'judul'            	=>'Status Gizi Balita',
		   );
		}
	 
		// Menampilkan hasil perhitungan status gizi ke view
		$this->load->view('admin/posyandu/lihat', ['data' => $result] + $view);
	 }
	 

	
	 private function hitungFuzzyTsukamoto($berat_badan, $tinggi_badan, $umur) {
        // Implementasikan logika Fuzzy Tsukamoto di sini
		
		// Fuzzifikasi
        $z_score_bb_u = $this->fuzzifikasiBBU($berat_badan, $umur);
        $z_score_tb_u = $this->fuzzifikasiTBU($tinggi_badan, $umur);
        $z_score_bb_tb = $this->fuzzifikasiBBTB($berat_badan, $tinggi_badan);

		// Menyimpan hasil perhitungan fuzzifikasi, inferensi, dan defuzzifikasi
		$hasil_perhitungan = [
			'z_score_bb_u' => $z_score_bb_u,
			'z_score_tb_u' => $z_score_tb_u,
			'z_score_bb_tb' => $z_score_bb_tb,
			'status_gizi_bbu' => $this->tentukanStatusGiziBBU($z_score_bb_u),
			'status_gizi_tbu' => $this->tentukanStatusGiziTBU($z_score_tb_u),
			'status_gizi_bb_tb' => $this->tentukanStatusGiziBBTB($z_score_bb_tb),
			'status_gizi' => $this->inferensi($z_score_bb_u, $z_score_tb_u, $z_score_bb_tb)
		];

		return $hasil_perhitungan;
		
	}

	
	private function fuzzifikasiBBU($berat_badan, $umur) {
		// Contoh: Rata-rata berat badan anak pada kelompok usia tertentu
		$mean_berat_badan = 30; // Gantilah dengan nilai rata-rata yang sesuai
		$std_dev_berat_badan = 5; // Gantilah dengan deviasi standar yang sesuai
	
		// Hitung Z-Score berat badan terhadap umur
		$z_score = ($berat_badan - $mean_berat_badan) / $std_dev_berat_badan;
		return $z_score;
	}
	
	
	private function fuzzifikasiTBU($tinggi_badan, $umur) {
		// Contoh: Rata-rata tinggi badan anak pada umur tertentu
		$mean_tinggi_badan_umur = 80; // Gantilah dengan nilai rata-rata yang sesuai
		$std_dev_tinggi_badan_umur = 5; // Gantilah dengan deviasi standar yang sesuai
	
		// Hitung Z-Score tinggi badan terhadap umur
		$z_score = ($tinggi_badan - $mean_tinggi_badan_umur) / $std_dev_tinggi_badan_umur;
	
		return $z_score;
	}
	
	
	private function fuzzifikasiBBTB($berat_badan, $tinggi_badan) {
		// Contoh: Rata-rata berat badan anak pada tinggi badan tertentu
		$mean_berat_badan_tinggi = 30; // Gantilah dengan nilai rata-rata yang sesuai
		$std_dev_berat_badan_tinggi = 2; // Gantilah dengan deviasi standar yang sesuai
	
		// Hitung Z-Score berat badan terhadap tinggi badan
		$z_score = ($berat_badan - $mean_berat_badan_tinggi) / $std_dev_berat_badan_tinggi;
	
		return $z_score;
	}
	

	private function tentukanStatusGiziBBU($z_score_bb_u) {
		// Implementasikan logika penentuan status gizi berdasarkan z-score berat badan terhadap umur di sini
		// ...
	
		// Contoh: Tentukan status gizi berdasarkan z-score berat badan terhadap umur
		// Sesuaikan dengan logika penentuan status gizi yang benar
		if ($z_score_bb_u < -3) {
			return 'Gizi Buruk';
		} else if ($z_score_bb_u <= -2) {
			return 'Gizi Kurang';
		} else if ($z_score_bb_u <= 2) {
			return 'Normal';
		} else if ($z_score_bb_u <= 3) {
			return 'Gizi Lebih';
		} else if ($z_score_bb_u > 3) {
			return 'Obesitas';
		} else {
			return 'false';
		}
	}

	private function tentukanStatusGiziTBU($z_score_tb_u) {
		// Implementasikan logika penentuan status gizi berdasarkan z-score tinggi badan terhadap umur di sini
		// ...
	
		// Contoh: Tentukan status gizi berdasarkan z-score tinggi badan terhadap umur
		// Sesuaikan dengan logika penentuan status gizi yang benar
		if ($z_score_tb_u < -3) {
			return 'Sangat Pendek';
		} else if ($z_score_tb_u <= -2) {
			return 'Pendek';
		} else if ($z_score_tb_u <= 2) {
			return 'Normal';
		} else if ($z_score_tb_u > 2) {
			return 'Tinggi';
		} else {
			return 'false';
		}
	}

	private function tentukanStatusGiziBBTB($z_score_bb_tb) {
		// Implementasikan logika penentuan status gizi berdasarkan z-score berat badan terhadap tinggi badan di sini
		// ...
	
		// Contoh: Tentukan status gizi berdasarkan z-score berat badan terhadap tinggi badan
		// Sesuaikan dengan logika penentuan status gizi yang benar
		if ($z_score_bb_tb < -3) {
			return 'Sangat Kurus';
		} else if ($z_score_bb_tb <= -2) {
			return 'Kurus';
		} else if ($z_score_bb_tb <= 2) {
			return 'Normal';
		} else if ($z_score_bb_tb > 2) {
			return 'Gemuk';
		} else {
			return 'false';
		}
	}

	private function inferensi($z_score_bb_u, $z_score_tb_u, $z_score_bb_tb) {
		// Implementasikan logika inferensi di sini
		// ...
	
		// Contoh: Tentukan status gizi berdasarkan z-score berat badan terhadap umur
		// menjumlahkan semua z-score
		$total_z_score = $z_score_bb_u + $z_score_tb_u + $z_score_bb_tb;
		if ($total_z_score < -3) {
			return 'Gizi Buruk';
		} else if ($total_z_score <= -2) {
			return 'Gizi Kurang';
		} else if ($total_z_score <= 2) {
			return 'Normal';
		} else if ($total_z_score <= 3) {
			return 'Gizi Lebih';
		} else if ($total_z_score > 3) {
			return 'Obesitas';
		} else {
			return 'false';
		}
	}
	
	
}