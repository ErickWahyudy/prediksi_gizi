<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan'); ?>

<?php if($depan == TRUE): 
      $kode_tahun = date("Y");
      $kode_bulan = date("m");
      
?>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="" style="width:100%">
                                <form action="" method="POST">
                                    <tr>
                                        <th>Bulan</th>
                                        <td>
                                            <select name="bulan" class="form-control" required="">
                                                <option value="">--Pilih Bulan--</option>
                                                <?php
                                                $bulan = array(
                                                    '1' => 'Januari',
                                                    '2' => 'Februari',
                                                    '3' => 'Maret',
                                                    '4' => 'April',
                                                    '5' => 'Mei',
                                                    '6' => 'Juni',
                                                    '7' => 'Juli',
                                                    '8' => 'Agustus',
                                                    '9' => 'September',
                                                    '10' => 'Oktober',
                                                    '11' => 'November',
                                                    '12' => 'Desember',
                                                ); foreach ($bulan as $key => $value) { ?>
                                                    <option value="<?= $key ?>" <?php if($key == $kode_bulan){echo "selected";} ?>><?= $value ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tahun</th>
                                        <td>
                                            <input type="number" name="tahun" class="form-control" value="<?= $kode_tahun ?>" placeholder="tahun"
                                                required="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td><br>
                                            <input type="submit" name="cari" value="Buka Data" class="btn btn-primary">
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php elseif($depan == FALSE): ?>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <div>
                    <h2 class="text-center">Buat Data Bulan <?= bulan($bulan) ?> Tahun <?= $tahun ?></h2>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="" style="width:100%">
                                <form id="add" method="post">
                                    <tr>
                                        <td width="20%">Nama Balita</td>
                                        <td width="80%">
                                            <select name="id_balita" id="id_balita" class="form-control select2" style="width: 100%;" required>
                                                <option value="">--Pilih Nama Balita--</option>
                                                <?php foreach($balita as $blt): ?>
                                                    <option value="<?= $blt['id_balita'] ?>" data-tgl_lahir="<?= $blt['tgl_lahir'] ?>">
                                                        <?= ucfirst($blt['nama']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Umur (bulan)</td>
                                        <td width="80%">
                                            <input type="text" name="umur" id="umur" class="form-control" placeholder="umur" autocomplete="off" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tinggi Badan</td>
                                        <td width="80%">
                                            <input type="text" name="tinggi_bb" class="form-control" placeholder="Tinggi Badan" autocomplete="off" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Berat Badan</td>
                                        <td width="80%">
                                            <input type="text" name="berat_bb" class="form-control" placeholder="Berat Badan" autocomplete="off" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="bulan" class="form-control" value="<?= $bulan ?>" placeholder="Bulan" autocomplete="off" readonly>
                                            <input type="hidden" name="tahun" class="form-control" value="<?= $tahun ?>" placeholder="Tahun" autocomplete="off" readonly>
                                        </td>
                                        <td colspan="2"><br>
                                            <a href="<?= base_url('superadmin/posyandu/data/add') ?>" class="btn btn-danger btn-sm">Kembali</a>
                                            <button type="submit" class="btn btn-primary btn-sm" name="submit" value="submit">Simpan</button>
                                        </td>
                                    </tr>                                  
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menghitung umur berdasarkan tanggal lahir
    function calculateAge(birthdate) {
        var birthdate = new Date(birthdate);
        var today = new Date();
        var age = today.getFullYear() - birthdate.getFullYear();
        var m = today.getMonth() - birthdate.getMonth();
        //jadikan satuan bulan
        age = age * 12;
        //tambahkan bulan
        age = age + m;
        return age;

    }

    
    $(document).ready(function() {
        // Inisialisasi Select2 pada elemen dengan id 'id_balita'
        $('#id_balita').select2({
            placeholder: "--Pilih Nama Balita--",
            allowClear: true
        });

        // Event change pada dropdown nama balita
        $('#id_balita').change(function () {
            var selectedBalita = $(this).find(':selected');
            var birthdate = selectedBalita.data('tgl_lahir');

            // Jika tanggal lahir ada, hitung umur dan tampilkan
            if (birthdate) {
                var age = calculateAge(birthdate);
                $('#umur').val(age);
            }
        });
    });

    //add data
    $(document).ready(function() {
        $('#add').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= site_url('superadmin/posyandu/data/api_add') ?>",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    if (data.status) {
                        $('#add');
                        $('#add')[0].reset();
                        swal({
                            title: "Berhasil",
                            text: "Data berhasil ditambahkan",
                            type: "success",
                            showConfirmButton: true,
                            confirmButtonText: "OKEE",
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        // Hapus tag HTML dari pesan error
                        var errorMessage = $('<div>').html(data.message).text();
                        swal({
                            title: "Gagal",
                            text: errorMessage, // Menampilkan pesan error dari server
                            type: "error",
                            showConfirmButton: true,
                            confirmButtonText: "OK",
                        });
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Menampilkan pesan error jika terjadi kesalahan pada AJAX request
                    swal({
                        title: "Error",
                        text: "Terjadi kesalahan saat mengirim data",
                        type: "error",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                }
            });
        });
    });
</script>

<?php endif; ?>
<?php

function bulan($bln)
{
    $bulan = array(
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );
    return $bulan[$bln];
}

?>
<?php $this->load->view('template/footer'); ?>
