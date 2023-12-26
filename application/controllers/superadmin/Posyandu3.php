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

class Posyandu3 extends CI_controller
{
	function __construct()
	{
	 parent:: __construct();
     $this->load->helper('url');
      // needed ???
      $this->load->database();
      $this->load->library('session');
      
	 // error_reporting(0);
	 if($this->session->userdata('superadmin') != TRUE){
     redirect(base_url(''));
     exit;
	};
	 $this->load->model('M_admin');
	 $this->load->model('m_balita');

	}
	

	public function status_gizi() {
		// Mengambil data dari database
		$data_balita = $this->m_balita->view()->result_array();
		
		// Menyiapkan array untuk menyimpan hasil perhitungan status gizi
		$result = [];
	
		foreach ($data_balita as $balita) {
			// Mengambil data berat badan, tinggi badan, dan umur
			$bb = $balita['berat_bb'];
			$tb = $balita['tinggi_bb'];
			$tgl_lahir = new DateTime($balita['tgl_lahir']);
			$tgl_sekarang = new DateTime();
	
			$umur_tahun = $tgl_lahir->diff($tgl_sekarang)->y;
			$umur_bulan = $tgl_lahir->diff($tgl_sekarang)->m;
			$umur_hari = $tgl_lahir->diff($tgl_sekarang)->d;
	
			$total_bulan = $umur_tahun * 12 + $umur_bulan;
			$umur = $total_bulan;
	
			// Fuzzyfication
			$umur_fuzzy = $this->fuzzyUmur($umur);
			$berat_fuzzy = $this->fuzzyBeratBadan($bb);
			$tinggi_fuzzy = $this->fuzzyTinggiBadan($tb);
	
			// Inference
			$rules = $this->applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy);
	
			// Defuzzyfication
			$final_result = $this->defuzzify($rules);
	
			// Menyimpan hasil perhitungan status gizi ke dalam array result
			$result[] = [
				'nama' => $balita['nama'], // Ganti dengan nama field di tabel Anda
				'jenis_kelamin' => $balita['jenis_kelamin'], // Ganti dengan nama field di tabel Anda
				'umur' => $umur,
				'berat_badan' => $bb,
				'tinggi_badan' => $tb,
				'final_result' => $final_result,
			];
		}
	
		// Pass the results to the view
		$data['result'] = $result;
		$this->load->view('superadmin/posyandu/lihat', $data);
	}
	
	private function fuzzyUmur($umur) {
		// Implement the fuzzyfication for Umur
		$umur_fase3 = max(0, min(1, ($umur - 24) / 12));
		$umur_fase4 = max(0, min(1, (36 - $umur) / 12));
	
		return [$umur_fase3, $umur_fase4];
	}
	
	private function fuzzyBeratBadan($berat_badan) {
		// Implement the fuzzyfication for Berat Badan
		$berat_normal = max(0, min(1, ($berat_badan - 7) / 6));
		$berat_kurus = max(0, min(1, (13 - $berat_badan) / 6));
	
		return [$berat_kurus, $berat_normal];
	}
	
	private function fuzzyTinggiBadan($tinggi_badan) {
		// Implement the fuzzyfication for Tinggi Badan
		$tinggi_normal = max(0, min(1, ($tinggi_badan - 75) / 26));
		$tinggi_tinggi = max(0, min(1, (101 - $tinggi_badan) / 26));
	
		return [$tinggi_normal, $tinggi_tinggi];
	}
	
	private function applyRules($umur, $berat, $tinggi) {
		// Implement the inference rules based on the provided example
		// ...
	
		$rules = array();
	
		// Rule 1
		$alpha1 = min($umur[0], $berat[1], $tinggi[0]);
		$z1 = 48 - ($alpha1 * 5);
		$rules[] = array('alpha' => $alpha1, 'z' => $z1);
	
		// Rule 2
		$alpha2 = min($umur[0], $berat[1], $tinggi[1]);
		$z2 = 48 - ($alpha2 * 5);
		$rules[] = array('alpha' => $alpha2, 'z' => $z2);
	
		// Rule 3
		$alpha3 = min($umur[0], $berat[0], $tinggi[0]);
		$z3 = 70 - ($alpha3 * 17);
		$rules[] = array('alpha' => $alpha3, 'z' => $z3);
	
		// Rule 4
		$alpha4 = min($umur[0], $berat[0], $tinggi[1]);
		$z4 = ($alpha4 * 5) + 48;
		$rules[] = array('alpha' => $alpha4, 'z' => $z4);
	
		// Rule 5
		$alpha5 = min($umur[1], $berat[1], $tinggi[0]);
		$z5 = 43 + ($alpha5 * 5);
		$rules[] = array('alpha' => $alpha5, 'z' => $z5);
	
		// Rule 6
		$alpha6 = min($umur[1], $berat[1], $tinggi[1]);
		$z6 = 43 + ($alpha6 * 5);
		$rules[] = array('alpha' => $alpha6, 'z' => $z6);
	
		// Rule 7
		$alpha7 = min($umur[1], $berat[0], $tinggi[0]);
		$z7 = 48 - ($alpha7 * 5);
		$rules[] = array('alpha' => $alpha7, 'z' => $z7);
	
		// Rule 8
		$alpha8 = min($umur[1], $berat[0], $tinggi[1]);
		$z8 = 48 - ($alpha8 * 5);
		$rules[] = array('alpha' => $alpha8, 'z' => $z8);
	
		return $rules;
	}
	
	
	private function defuzzify($rules) {
		// Extract alpha and z values from the rules
		$alpha_values = array_column($rules, 'alpha');
		$z_values = array_column($rules, 'z');
	
		// Calculate the numerator and denominator for defuzzyfication
		$numerator = array_sum(array_map(function ($alpha, $z) {
			return $alpha * $z;
		}, $alpha_values, $z_values));
	
		$denominator = array_sum($alpha_values);
	
		// Avoid division by zero
		if ($denominator != 0) {
			$final_result = $numerator / $denominator;
	
			// Kategori berdasarkan ambang batas
			$kategori = $this->determineCategory($final_result);
	
			return ['result' => $final_result, 'category' => $kategori];
		}
	
		return ['result' => 0, 'category' => 'Unknown']; // Atau nilai default lainnya
	}
	
	// Fungsi untuk menentukan kategori berdasarkan ambang batas
	private function determineCategory($value) {
		// Tentukan kategori berdasarkan ambang batas tertentu
		if ($value < -3) {
			return 'Gizi Buruk';
		} elseif ($value >= -3 && $value < -2) {
			return 'Gizi Kurang';
		} elseif ($value >= -2 && $value < 2) {
			return 'Gizi Baik';
		} elseif ($value >= 2 && $value < 3) {
			return 'Gizi Lebih';
		} elseif ($value >= 3) {
			return 'Obesitas';
		} else {
			return 'Unknown';
		}
	}
	
	
	
	
}
	  