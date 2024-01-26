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
    $this->load->library('form_validation');
	 // error_reporting(0);
	 if($this->session->userdata('user') != TRUE){
    redirect(base_url(''));
     exit;
	};
   $this->load->model('m_balita'); 
   $this->load->model('m_posyandu');
}

	public function index()
	{
		if (isset($_POST['cari'])) {
			//cek data apabila berhasi Di kirim maka postdata akan melakukan cek .... dan sebaliknya
			$bulan =$this->input->post('bulan');
			$tahun =$this->input->post('tahun');

			$data_balita = $this->m_posyandu->view($bulan, $tahun)->result_array();
            $result = [];

            foreach ($data_balita as $balita) {
                $berat_badan    = $balita['berat_bb'];
                $tinggi_badan   = $balita['tinggi_bb'];
                $umur           = $balita['umur'];
                $jenis_kelamin  = $balita['jenis_kelamin'];

                // Fuzzyfikasi
                $umur_fuzzy = $this->fuzzyUmur($umur);
                $berat_fuzzy = $this->fuzzyBeratBadan($berat_badan, $jenis_kelamin);
                $tinggi_fuzzy = $this->fuzzyTinggiBadan($tinggi_badan, $jenis_kelamin);

                // Inferensi
                $rules = $this->applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy);
            
                // Defuzzyfikasi
                $defuzzy = $this->defuzzify($rules);

                $result[] = [
                    'nama' => $balita['nama'],
                    'jenis_kelamin' => $balita['jenis_kelamin'],
                    'umur' => $umur,
                    'berat_badan' => $berat_badan,
                    'tinggi_badan' => $tinggi_badan,
                    'total_alpha' => $defuzzy['total_alpha'],
                    'total_alpha_z' => $defuzzy['total_alpha_z'],
                    'defuzzy' => $defuzzy['final_result'],
                    'status_gizi' => $this->getStatusGizi($defuzzy['final_result']),
                ];
            }

            $data['result'] = $result;
            $data['judul'] = 'Hasil Status Gizi';
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $data['depan'] = FALSE;
            $this->load->view('user/home', $data);
        } else {
            $data['judul'] = 'Hasil Status Gizi';
            $data['depan'] = TRUE;
            $this->load->view('user/home', $data);
        }
    }

    private function fuzzyUmur($umur)
    {
        // Inisialisasi fase umur
        $umur_fase1 = $umur_fase2 = $umur_fase3 = $umur_fase4 = $umur_fase5 = 0;
    
        // Hitung nilai fuzzy untuk setiap fase
        if ($umur <= 6) {
            $umur_fase1 = 1;
        } elseif ($umur > 6 && $umur <= 12) {
            $umur_fase1 = (12 - $umur) / 6;
            $umur_fase2 = ($umur - 6) / 6;
        } elseif ($umur > 12 && $umur <= 24) {
            $umur_fase2 = (24 - $umur) / 12;
            $umur_fase3 = ($umur - 12) / 12;
        } elseif ($umur > 24 && $umur <= 36) {
            $umur_fase3 = (36 - $umur) / 12;
            $umur_fase4 = ($umur - 24) / 12;
        } elseif ($umur > 36 && $umur <= 48) {
            $umur_fase4 = (48 - $umur) / 12;
            $umur_fase5 = ($umur - 36) / 12;
        } elseif ($umur > 48) {
            $umur_fase5 = 1;
        }
    
        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $umur_fase1 = number_format($umur_fase1, 3, '.', '');
        $umur_fase2 = number_format($umur_fase2, 3, '.', '');
        $umur_fase3 = number_format($umur_fase3, 3, '.', '');
        $umur_fase4 = number_format($umur_fase4, 3, '.', '');
        $umur_fase5 = number_format($umur_fase5, 3, '.', '');
    
        return [$umur_fase1, $umur_fase2, $umur_fase3, $umur_fase4, $umur_fase5];
    }


    private function fuzzyBeratBadan($berat_badan, $jenis_kelamin)
    {
        // Inisialisasi nilai berat_badan
        $berat_kurus = $berat_normal = $berat_gemuk = 0;

        // Fungsi keanggotaan untuk laki-laki
        if ($jenis_kelamin == 'Laki-laki') {
            if ($berat_badan <= 7) {
                $berat_kurus = 1;
            } elseif ($berat_badan > 7 && $berat_badan <= 13) {
                $berat_kurus = max(0, min(1, (13 - $berat_badan) / 6));
                $berat_normal = max(0, min(1, ($berat_badan - 7) / 6));
            } elseif ($berat_badan > 13 && $berat_badan <= 19) {
                $berat_normal = max(0, min(1, 1 - ($berat_badan - 13) / 6));
                $berat_gemuk = max(0, min(1, ($berat_badan - 13) / 6));
            } elseif ($berat_badan > 19) {
                $berat_gemuk = max(0, min(1, 1 - ($berat_badan - 19) / 7));
            }
        }

        // Fungsi keanggotaan untuk perempuan
        elseif ($jenis_kelamin == 'Perempuan') {
            if ($berat_badan <= 7) {
                $berat_kurus = 1;
            } elseif ($berat_badan > 7 && $berat_badan <= 12) {
                $berat_kurus = max(0, min(1, (12 - $berat_badan) / 5));
                $berat_normal = max(0, min(1, ($berat_badan - 7) / 5));
            } elseif ($berat_badan > 12 && $berat_badan <= 18) {
                $berat_normal = max(0, min(1, 1 - ($berat_badan - 12) / 6));
                $berat_gemuk = max(0, min(1, ($berat_badan - 12) / 6));
            } elseif ($berat_badan > 18) {
                $berat_gemuk = max(0, min(1, 1 - ($berat_badan - 18) / 6));
            }
        }

        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $berat_kurus = number_format($berat_kurus, 3, '.', '');
        $berat_normal = number_format($berat_normal, 3, '.', '');
        $berat_gemuk = number_format($berat_gemuk, 3, '.', '');

        return [$berat_kurus, $berat_normal, $berat_gemuk];
    }

        

    private function fuzzyTinggiBadan($tinggi_badan, $jenis_kelamin)
    {
        // Inisialisasi nilai tinggi_badan
        $tinggi_pendek = $tinggi_normal = $tinggi_tinggi = 0;

        // Fungsi keanggotaan untuk laki-laki
        if ($jenis_kelamin == 'Laki-laki') {
            if ($tinggi_badan <= 49) {
                $tinggi_pendek = 1;
            } elseif ($tinggi_badan > 49 && $tinggi_badan <= 75) {
                $tinggi_pendek = max(0, min(1, (75 - $tinggi_badan) / 26));
                $tinggi_normal = max(0, min(1, ($tinggi_badan - 49) / 26));
            } elseif ($tinggi_badan > 75 && $tinggi_badan <= 101) {
                $tinggi_normal = max(0, min(1, (101 - $tinggi_badan) / 26));
                $tinggi_tinggi = max(0, min(1, ($tinggi_badan - 75) / 26));
            } elseif ($tinggi_badan > 101) {
                $tinggi_tinggi = max(0, min(1, 1 - ($tinggi_badan - 101) / 26));
            }
        }

        // Fungsi keanggotaan untuk perempuan
        elseif ($jenis_kelamin == 'Perempuan') {
            if ($tinggi_badan <= 48) {
                $tinggi_pendek = 1;
            } elseif ($tinggi_badan > 48 && $tinggi_badan <= 74) {
                $tinggi_pendek = max(0, min(1, (74 - $tinggi_badan) / 26));
                $tinggi_normal = max(0, min(1, ($tinggi_badan - 48) / 26));
            } elseif ($tinggi_badan > 74 && $tinggi_badan <= 100) {
                $tinggi_normal = max(0, min(1, (100 - $tinggi_badan) / 26));
                $tinggi_tinggi = max(0, min(1, ($tinggi_badan - 74) / 26));
            } elseif ($tinggi_badan > 100) {
                $tinggi_tinggi = max(0, min(1, 1 - ($tinggi_badan - 100) / 26));
            }
        }

        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $tinggi_pendek = number_format($tinggi_pendek, 3, '.', '');
        $tinggi_normal = number_format($tinggi_normal, 3, '.', '');
        $tinggi_tinggi = number_format($tinggi_tinggi, 3, '.', '');

        $total_alpha = $tinggi_pendek + $tinggi_normal + $tinggi_tinggi;
        $tinggi_fuzzy = [$tinggi_pendek, $tinggi_normal, $tinggi_tinggi, $total_alpha];

        return $tinggi_fuzzy;
    }



    private function applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy)
    {
        $rules = [];
        $total_alpha = 0; // Initialize total alpha

        $status_rules = [
            'Gizi Buruk' => [        
                    [2, 0, 0],   // Fase 3 Kurus Pendek
                    [2, 0, 1],   // Fase 3 Kurus Normal
                    [2, 0, 2],   // Fase 3 Kurus Tinggi
                
                    [4, 0, 0],   // Fase 5 Kurus Pendek
                    [4, 0, 1],   // Fase 5 Kurus Normal
                    [4, 0, 2],   // Fase 5 Kurus Tinggi
            ],
            'Gizi Kurang' => [
                    [0, 0, 2],   // Fase 1 Kurus Tinggi

                    [1, 0, 0],   // Fase 2 Kurus Pendek
                    [1, 0, 1],   // Fase 2 Kurus Normal
                    [1, 0, 2],   // Fase 2 Kurus Tinggi

                    [3, 0, 0],   // Fase 4 Kurus Pendek
                    [3, 0, 1],   // Fase 4 Kurus Normal
                    [3, 0, 2],   // Fase 4 Kurus Tinggi

                    [4, 1, 0],   // Fase 5 Normal Pendek
                    [4, 1, 1],   // Fase 5 Normal Normal
                    [4, 1, 2],   // Fase 5 Normal Tinggi

            ],
            'Gizi Baik' => [
                    [0, 1, 0],   // Fase 1 Kurus Pendek
                    [0, 1, 1],   // Fase 1 Kurus Normal
        
                    [1, 1, 0],   // Fase 2 Normal Pendek
                    [1, 1, 1],   // Fase 2 Normal Normal
                    [1, 1, 2],   // Fase 2 Normal Tinggi
        
                    [2, 1, 0],   // Fase 3 Normal Pendek
                    [2, 1, 1],   // Fase 3 Normal Normal
                    [2, 1, 2],   // Fase 3 Normal Tinggi
        
                    [3, 1, 0],   // Fase 4 Normal Pendek
                    [3, 1, 1],   // Fase 4 Normal Normal
                    [3, 1, 2],   // Fase 4 Normal Tinggi
        
                    [3, 2, 2],   // Fase 4 Gemuk Tinggi
        
                    [4, 2, 2],   // Fase 5 Gemuk Tinggi
            ],
            'Gizi Lebih' => [
                    [0, 1, 0],   // Fase 1 Normal Pendek
                    [0, 1, 1],   // Fase 1 Normal Normal
                    [0, 1, 2],   // Fase 1 Normal Tinggi

                    [0, 2, 0],   // Fase 1 Gemuk Pendek
                    [0, 2, 1],   // Fase 1 Gemuk Normal

                    [1, 2, 0],   // Fase 2 Gemuk Pendek
                    [1, 2, 1],   // Fase 2 Gemuk Normal

                    [2, 2, 0],   // Fase 3 Gemuk Pendek
                    [2, 2, 1],   // Fase 3 Gemuk Normal

                    [3, 2, 0],   // Fase 4 Gemuk Pendek
                    [3, 2, 1],   // Fase 4 Gemuk Normal

                    [4, 2, 0],   // Fase 5 Gemuk Pendek
                    [4, 2, 1],   // Fase 5 Gemuk Normal

            ],
            'Obesitas' => [
                    [0, 2, 2],   // Fase 1 Gemuk Tinggi
                    [1, 2, 2],   // Fase 2 Gemuk Tinggi
                    [2, 2, 2],   // Fase 3 Gemuk Tinggi
            ],
        ];

        foreach ($status_rules as $status_gizi => $condition) {
            foreach ($condition as $cond) {
                $key_umur = $cond[0];
                $key_berat = $cond[1];
                $key_tinggi = $cond[2];

                if (isset($umur_fuzzy[$key_umur]) && isset($berat_fuzzy[$key_berat]) && isset($tinggi_fuzzy[$key_tinggi])) {
                    $alpha = min($umur_fuzzy[$key_umur], $berat_fuzzy[$key_berat], $tinggi_fuzzy[$key_tinggi]);

                    if ($status_gizi !== 'Total Alpha') {
                        $rules[] = [$status_gizi, $alpha];
                        $total_alpha += $alpha;
                    }
                }
            }
        }

        // Add Total Alpha to the rules
        $rules[] = ['Total Alpha', $total_alpha];

        return ['rules' => $rules, 'total_alpha' => $total_alpha];
    }


    private function defuzzify($rules)
    {
        $alpha_z_values = [];
        $total_alpha = $rules['total_alpha'];
    
        if ($total_alpha > 0) {
            foreach ($rules['rules'] as $rule) {
                list($status_gizi, $alpha) = $rule;
                if ($status_gizi !== 'Total Alpha') {
                    $z_value = $this->getZValue($status_gizi, $alpha);
                    $alpha_z_values[] = $alpha * $z_value;
                }
            }
    
            // Calculate the final result (Total Alpha * Z)
            $total_alpha_z = array_sum($alpha_z_values);
    
            // Return the results with the correct format
            return [
                'total_alpha' => round($total_alpha, 2),
                'total_alpha_z' => round($total_alpha_z, 2),
                'final_result' => round($total_alpha_z / $total_alpha, 2),
            ];
        } else {
            // If total_alpha is zero, return default values
            return [
                'total_alpha' => 0,
                'total_alpha_z' => 0,
                'final_result' => 0,
            ];
        }
    }
    
    //. Rumus yang digunakan untuk menentukan nilai z yaitu menggunakan rumus variabel nilai gizi, dan untuk menentukan rumus yaitu melalui keterangan status gizi pada setiap rule, jika pada rule pertama nilai status gizinya kurang mka menggunakan rumus gizi kurang. Apabila nilai alpha MIN diawah 0,5 maka menggunakan rumus yang naik atau (atas), jika nilai alpha MIN diatas 0,5 maka menggunakan rumus yang turun atau (bawah).
    private function getZValue($status_gizi, $alpha)
    {
        switch ($status_gizi) {
            case 'Gizi Buruk':
                //gizi buruk  = (48-x)/5
                return 48 - ($alpha * 5); // Sesuai dengan rumus
                break;
            case 'Gizi Kurang':
                //gizi kurang = (x-43)/5 & (53-x)/5
                if ($alpha <= 0.5) {
                    return 43 + ($alpha * 5); // Sesuai dengan rumus
                } else {
                    return 53 - ($alpha * 5); // Sesuai dengan rumus
                }
                break;
            case 'Gizi Baik':
                //gizi baik  =  (x-48)/5 & (70-x)/17
                if ($alpha <= 0.5) {
                    return 48 + ($alpha * 5); // Sesuai dengan rumus
                } else {
                    return 70 - ($alpha * 17); // Sesuai dengan rumus
                }
                break;
            case 'Gizi Lebih':
                //gizi lebih  = (x-53)/17 & (83-x)/13
                if ($alpha <= 0.5) {
                    return 53 + ($alpha * 17); // Sesuai dengan rumus
                } else {
                    return 83 - ($alpha * 13); // Sesuai dengan rumus
                }
                break;
            case 'Obesitas':
                //obesitas  = (x-70)/13
                return 70 + ($alpha * 13); // Sesuai dengan rumus
                break;
            default:
                return 0;
                break;
            }            
        
    }

    private function getZValue1($status_gizi, $alpha)
    {
        switch ($status_gizi) {
            case 'Gizi Buruk':
                return 48 - (5 * $alpha); // Sesuai dengan rumus
                break;
            case 'Gizi Kurang':
                return 43 + (5 * $alpha); // Sesuai dengan rumus
                break;
            case 'Gizi Baik':
                return 48 + (5 * $alpha); // Sesuai dengan rumus
                break;
            case 'Gizi Lebih':
                return 53 + (17 * $alpha); // Sesuai dengan rumus
                break;
            case 'Obesitas':
                return 70 + (13 * $alpha); // Sesuai dengan rumus
                break;
            default:
                return 0;
                break;
        }
    }
    
    private function getStatusGizi($final_result)
    {
        // Sesuaikan ambang batas berdasarkan aturan fuzzy yang benar
        if ($final_result < 43) {
            return 'Gizi Buruk';
        } elseif ($final_result >= 43 && $final_result < 48) {
            return 'Gizi Kurang';
        } elseif ($final_result >= 48 && $final_result < 53) {
            return 'Gizi Baik';
        } elseif ($final_result >= 53 && $final_result < 70) {
            return 'Gizi Lebih';
        } else {
            return 'Obesitas';
        }
    }
}
