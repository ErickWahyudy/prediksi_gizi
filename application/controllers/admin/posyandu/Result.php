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
            //cek data apabila berhasi Di kirim maka postdata akan melakukan cek .... dan sebaliknya
            $bulan =$this->input->post('bulan');
            $tahun =$this->input->post('tahun');

            $data_balita = $this->m_posyandu->view($bulan, $tahun)->result_array();
            $result = [];

            foreach ($data_balita as $balita) {
                $bb = $balita['berat_bb'];
                $tb = $balita['tinggi_bb'];
                $umur = $balita['umur'];

                $umur_fuzzy = $this->fuzzyUmur($umur);
                $berat_fuzzy = $this->fuzzyBeratBadan($bb);
                $tinggi_fuzzy = $this->fuzzyTinggiBadan($tb);

                $rules = $this->applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy);
                $final_result = $this->defuzzify($rules);

                $result[] = [
                    'nama' => $balita['nama'],
                    'jenis_kelamin' => $balita['jenis_kelamin'],
                    'umur' => $umur,
                    'berat_badan' => $bb,
                    'tinggi_badan' => $tb,
                    'final_result' => $final_result,
                    'status_gizi' => $this->getStatusGizi($final_result)
                ];
            }

            $data['result'] = $result;
            $data['judul'] = 'Hasil Status Gizi';
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $data['depan'] = FALSE;
            $this->load->view('superadmin/posyandu/view', $data);
        } else {
            $data['judul'] = 'Hasil Status Gizi';
            $data['depan'] = TRUE;
            $this->load->view('superadmin/posyandu/view', $data);
        }
    }

    private function fuzzyUmur($umur)
    {
        $umur_fase1 = max(0, min(1, ($umur - 6) / 6));
        $umur_fase2 = max(0, min(1, ($umur - 12) / 6));
        $umur_fase3 = max(0, min(1, ($umur - 24) / 6));
        $umur_fase4 = max(0, min(1, ($umur - 36) / 6));
        $umur_fase5 = max(0, min(1, ($umur - 48) / 6));
        $umur_fase6 = max(0, min(1, ($umur - 60) / 6));
    
        // Penanganan untuk nilai umur kurang dari 6
        if ($umur < 6) {
            $umur_fase1 = 1;
            $umur_fase2 = $umur_fase3 = $umur_fase4 = $umur_fase5 = $umur_fase6 = 0;
        }
    
        return [$umur_fase1, $umur_fase2, $umur_fase3, $umur_fase4, $umur_fase5, $umur_fase6];
    }
    

    private function fuzzyBeratBadan($berat_badan)
    {
        $berat_kurus = max(0, min(1, (7 - $berat_badan) / 7));
        $berat_normal = max(0, min(1, ($berat_badan - 7) / 7));
        $berat_gemuk = max(0, min(1, ($berat_badan - 13) / 6)); // Sesuaikan pembagian dengan fase berikutnya
        $berat_obesitas = max(0, min(1, ($berat_badan - 19) / 7));

        // Penanganan nilai berat_badan yang lebih besar
        if ($berat_badan > 26) {
            $berat_kurus = $berat_normal = $berat_gemuk = $berat_obesitas = 0;
        }

        return [$berat_kurus, $berat_normal, $berat_gemuk, $berat_obesitas];
    }


    private function fuzzyTinggiBadan($tinggi_badan)
    {
        $tinggi_pendek = max(0, min(1, (49 - $tinggi_badan) / 49));
        $tinggi_normal = max(0, min(1, ($tinggi_badan - 49) / 26)); // Sesuaikan pembagian dengan fase berikutnya
        $tinggi_tinggi = max(0, min(1, ($tinggi_badan - 75) / 26)); // Sesuaikan pembagian dengan fase berikutnya
        $tinggi_sangat_tinggi = max(0, min(1, ($tinggi_badan - 101) / 49));

        // Penanganan nilai tinggi_badan yang lebih besar
        if ($tinggi_badan > 150) {
            $tinggi_pendek = $tinggi_normal = $tinggi_tinggi = $tinggi_sangat_tinggi = 0;
        }

        return [$tinggi_pendek, $tinggi_normal, $tinggi_tinggi, $tinggi_sangat_tinggi];
    }


    private function applyRules($umur_fuzzy, $berat_fuzzy, $tinggi_fuzzy)
    {
        $rules = [];
    
        $status_rules = [
            'Gizi Buruk' => [
                [0, 0, 0],
            ],
            'Gizi Kurang' => [
                [0, 0, 1],
                [0, 1, 0],
                [0, 1, 1],
                [0, 2, 0],
                [0, 2, 1],
                [0, 3, 0],
                [0, 3, 1],
                [1, 0, 0],
                [1, 0, 1],
                [1, 1, 0],
                [1, 1, 1],
                [1, 2, 0],
                [1, 2, 1],
                [1, 3, 0],
                [1, 3, 1],
            ],
            'Gizi Baik' => [
                [2, 0, 0],
                [2, 0, 1],
                [2, 1, 0],
                [2, 1, 1],
                [2, 2, 0],
                [2, 2, 1],
                [3, 0, 0],
                [3, 0, 1],
                [3, 1, 0],
                [3, 1, 1],
                [3, 2, 0],
                [3, 2, 1],
                [4, 0, 0],
                [4, 0, 1],
                [4, 1, 0],
                [4, 1, 1],
                [4, 2, 0],
                [4, 2, 1],
            ],
            'Gizi Lebih' => [
                [2, 3, 0],
                [2, 3, 1],
                [3, 3, 0],
                [3, 3, 1],
                [4, 3, 0],
                [4, 3, 1],
            ],
            'Obesitas' => [
                [5, 0, 0],
                [5, 0, 1],
                [5, 1, 0],
                [5, 1, 1],
                [5, 2, 0],
                [5, 2, 1],
                [5, 3, 0],
                [5, 3, 1],
            ],
        ];
    
        foreach ($status_rules as $status_gizi => $condition) {
            foreach ($condition as $cond) {
                $key_umur = $cond[0];
                $key_berat = $cond[1];
                $key_tinggi = $cond[2];
    
                $alpha = min($umur_fuzzy[$key_umur], $berat_fuzzy[$key_berat], $tinggi_fuzzy[$key_tinggi]);
    
                $rules[] = [$status_gizi, $alpha];
            }
        }
    
        return $rules;
    }
    
    

    private function defuzzify($rules)
    {
        $alpha_z_values = [];

        foreach ($rules as $rule) {
            $status_gizi = $rule[0];
            $alpha = $rule[1];

            // Implement defuzzification logic here...
            $z = $this->getZValue($status_gizi);
            $alpha_z_values[] = $alpha * $z;
        }

        $total_alpha = array_column($rules, 1);
        $total_alpha_z = array_sum($alpha_z_values);

        if (array_sum($total_alpha) !== 0) {
            $final_result = $total_alpha_z / array_sum($total_alpha);
            return $final_result;
        } else {
            return 0;
        }
    }

    

    private function getZValue($status_gizi)
    {
        // Implement custom logic to get Z value based on status_gizi
        switch ($status_gizi) {
            case 'Gizi Buruk':
                return 43;  // Adjust this value based on your rules
            case 'Gizi Kurang':
                return 48;  // Adjust this value based on your rules
            case 'Gizi Baik':
                return 53;  // Adjust this value based on your rules
            case 'Gizi Lebih':
                return 70;  // Adjust this value based on your rules
            case 'Obesitas':
                return 83;  // Adjust this value based on your rules
            default:
                return 0;
        }
    }

    private function getStatusGizi($final_result)
    {
        // Determine status gizi based on final result
        // Adjust the threshold values based on your rules
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
