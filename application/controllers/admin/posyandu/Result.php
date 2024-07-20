<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        if ($this->session->userdata('admin') != TRUE) {
            redirect(base_url(''));
            exit;
        }

        $this->load->model('m_balita');
        $this->load->model('m_posyandu');
    }

    public function status_gizi()
    {
        if (isset($_POST['cari'])) {
            $bulan = $this->input->post('bulan');
            $tahun = $this->input->post('tahun');

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
            $data['judul'] = 'Hasil Status Gizi'. ' Bulan ' . $bulan . ' Tahun ' . $tahun;
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $data['depan'] = FALSE;
            $this->load->view('admin/posyandu/view', $data);
        } else {
            $data['judul'] = 'Hasil Status Gizi';
            $data['depan'] = TRUE;
            $this->load->view('admin/posyandu/view', $data);
        }
    }

    private function fuzzyUmur($umur)
    {
        // Inisialisasi fase umur
        $umur_muda = $umur_tua = 0;
        
        // Fungsi keanggotaan untuk "muda" dan "tua"
        if ($umur >= 0 && $umur <= 60) {
            $umur_muda = (60 - $umur) / 60;
            $umur_tua = ($umur - 0) / 60;
        }
        
        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $umur_muda = number_format($umur_muda, 3, '.', '');
        $umur_tua = number_format($umur_tua, 3, '.', '');
    
        return [$umur_muda, $umur_tua];
    }
    


    private function fuzzyBeratBadan($berat_badan, $jenis_kelamin)
    {
        // Inisialisasi nilai berat_badan
        $berat_kurus = $berat_gemuk = 0;

        // Fungsi keanggotaan untuk laki-laki
        if ($jenis_kelamin == 'Laki-laki') {
            if ($berat_badan >= 2.1 && $berat_badan <= 27.9) {
                $berat_kurus = max(0, (27.9 - $berat_badan) / 25.8);
                $berat_gemuk = max(0, ($berat_badan - 2.1) / 25.8);
            }
        }
        
        // Fungsi keanggotaan untuk perempuan
        elseif ($jenis_kelamin == 'Perempuan') {
            if ($berat_badan >= 2 && $berat_badan <= 29.5) {
                $berat_kurus = max(0, (29.5 - $berat_badan) / 27.5);
                $berat_gemuk = max(0, ($berat_badan - 2) / 27.5);
            }
        }

        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $berat_kurus = number_format($berat_kurus, 3, '.', '');
        $berat_gemuk = number_format($berat_gemuk, 3, '.', '');

        return [$berat_kurus, $berat_gemuk];
    }


            

    private function fuzzyTinggiBadan($tinggi_badan, $jenis_kelamin)
    {
        // Inisialisasi nilai tinggi_badan
        $tinggi_pendek = $tinggi_tinggi = 0;

        // Fungsi keanggotaan untuk laki-laki
        if ($jenis_kelamin == 'Laki-laki') {
            if ($tinggi_badan >= 49 && $tinggi_badan <= 123.9) {
                $tinggi_pendek = max(0, (123.9 - $tinggi_badan) / 79.7);
                $tinggi_tinggi = max(0, ($tinggi_badan - 44.2) / 79.7);
            }
        }
        
        // Fungsi keanggotaan untuk perempuan
        elseif ($jenis_kelamin == 'Perempuan') {
            if ($tinggi_badan >= 43.6 && $tinggi_badan <= 123.7) {
                $tinggi_pendek = max(0, (123.7 - $tinggi_badan) / 80.1);
                $tinggi_tinggi = max(0, ($tinggi_badan - 43.6) / 80.1);
            }
        }

        // Tambahkan pembulatan agar hasil sesuai dengan yang diharapkan
        $tinggi_pendek = number_format($tinggi_pendek, 3, '.', '');
        $tinggi_tinggi = number_format($tinggi_tinggi, 3, '.', '');

        $total_alpha = $tinggi_pendek + $tinggi_tinggi;
        $tinggi_fuzzy = [$tinggi_pendek, $tinggi_tinggi, $total_alpha];

        return $tinggi_fuzzy;
    }



    private function applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy)
    {
        $rules = [];
        $total_alpha = 0; // Initialize total alpha

        $status_rules = [
            'Gizi Kurang' => [
                [0, 0, 0],   // Fase Muda Kurus Pendek
                [0, 0, 2],   // Fase Muda Kurus Tinggi
                [0, 2, 0],   // Fase Muda Gemuk Pendek
                [0, 2, 2],   // Fase Muda Gemuk Tinggi
                [1, 0, 0],   // Fase Tua Kurus Pendek
                [1, 0, 2],   // Fase Tua Kurus Tinggi
                [1, 2, 0],   // Fase Tua Gemuk Pendek
                [1, 2, 2],   // Fase Tua Gemuk Tinggi
            ],
            'Gizi Baik' => [
                [0, 1, 0],   // Fase Muda Normal Pendek
                [0, 1, 2],   // Fase Muda Normal Tinggi
                [1, 1, 0],   // Fase Tua Normal Pendek
                [1, 1, 2],   // Fase Tua Normal Tinggi
                [1, 2, 0],   // Fase Tua Gemuk Pendek
                [1, 2, 2],   // Fase Tua Gemuk Tinggi
            ],
        ];

        foreach ($status_rules as $status_gizi => $conditions) {
            foreach ($conditions as $cond) {
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

    private function getZValue($status_gizi, $alpha)
    {
        switch ($status_gizi) {
            case 'Gizi Kurang':
                return 3 - ($alpha * 6); // Sesuai dengan rumus µ gizi kurang
                break;
            case 'Gizi Baik':
                return -3 + ($alpha * 6); // Sesuai dengan rumus µ gizi baik
                break;
            default:
                return 0;
                break;
        }
    }

    private function getStatusGizi($final_result)
    {
        // Sesuaikan ambang batas berdasarkan aturan fuzzy yang benar
        if ($final_result < 0) {
            return 'Gizi Kurang';
        } else {
            return 'Gizi Baik';
        }
    }
}
