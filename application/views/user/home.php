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
            <div class="x_title">
    
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Umur (bulan)</th>
                                        <th>Berat Badan (kg)</th>
                                        <th>Tinggi Badan (cm)</th>
                                        <th>Z-Score </th>
                                        <th>Status Gizi</th>
                                    </tr>
                                </thead>
                                <?php $no=1; foreach($result as $balita): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $balita['nama']; ?></td>
                                    <td><?= $balita['jenis_kelamin']; ?></td>
                                    <td><?= $balita['umur']; ?></td>
                                    <td><?= $balita['berat_badan']; ?></td>
                                    <td><?= $balita['tinggi_badan']; ?></td>
                                    <td><?= $balita['defuzzy']; ?></td>
                                    <td><?= $balita['status_gizi']; ?></td>
                                </tr>
                                <?php $no++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
        </div>
    </div>
</div>
<div>
    <a href="<?= base_url('user/home') ?>" class="btn btn-danger">Kembali</a>
</div>

<?php endif; ?>
<?php $this->load->view('template/footer'); ?>

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