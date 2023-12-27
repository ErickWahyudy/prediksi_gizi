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
                                        <th>TTL</th>
                                        <th>Umur</th>
                                        <th>Tinggi Badan</th>
                                        <th>Berat Badan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php $no=1; foreach($data as $balita): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $balita['nama'] ?></td>
                                    <td><?= $balita['jenis_kelamin'] ?></td>
                                    <td><?= $balita['tempat_lahir'] ?>, <?= tgl_indo($balita['tgl_lahir']) ?></td>
                                    <td><?= $balita['umur'] ?> Bulan</td>
                                    <td><?= $balita['tinggi_bb'] ?> cm</td>
                                    <td><?= $balita['berat_bb'] ?> kg</td>
                                    <td>
                                        <a href="" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit<?= $balita['id_posyandu'] ?>"><i class="fa fa-edit"></i>
                                            Edit</a>
                                    </td>
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
<!-- modal edit pbalita -->
<?php foreach($data as $balita): ?>
<div class="modal fade" id="edit<?= $balita['id_posyandu'] ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditBalitaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="modalEditBalitaLabel">Edit <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="edit" method="post">
                        <input type="hidden" name="id_posyandu" value="<?= $balita['id_posyandu'] ?>">
                        <tr>
                            <td><label for="nama">Nama:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama" id="nama" class="form-control" autocomplete="off"
                                    value="<?= $balita['nama'] ?>" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="jenis_kelamin">Jenis Kelamin:</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control"
                                    autocomplete="off" value="<?= $balita['jenis_kelamin'] ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="tgl_lahir">Tanggal Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                    autocomplete="off" value="<?= tgl_indo($balita['tgl_lahir']) ?>" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="tempat_lahir">Tempat Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    autocomplete="off" value="<?= $balita['tempat_lahir'] ?>" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="umur">Umur:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="umur" id="umur" class="form-control" autocomplete="off"
                                    value="<?= $balita['umur'] ?>" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="tinggi_bb">Tinggi Badan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tinggi_bb" id="tinggi_bb" class="form-control"
                                    autocomplete="off" value="<?= $balita['tinggi_bb'] ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="berat_bb">Berat Badan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="berat_bb" id="berat_bb" class="form-control"
                                    autocomplete="off" value="<?= $balita['berat_bb'] ?>"></td>
                        </tr>
                        <tr>
                            <td>
                                <br><input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                <a href="javascript:void(0)" onclick="hapusposyandu('<?= $balita['id_posyandu'] ?>')"
                                    class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script type="text/javascript">
    //edit file
    $(document).on('submit', '#edit', function(e) {
        e.preventDefault();
        var form_data = new FormData(this);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('superadmin/posyandu/data/api_edit/') ?>" + form_data.get(
                'id_posyandu'),
            dataType: "json",
            data: form_data,
            processData: false,
            contentType: false,
            //memanggil swall ketika berhasil
            success: function(data) {
                $('#edit' + form_data.get('id_posyandu'));
                swal({
                    title: "Berhasil",
                    text: "Data Berhasil Diubah",
                    type: "success",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE",
                }).then(function() {
                    location.reload();
                });
            },
            //memanggil swall ketika gagal
            error: function(data) {
                swal({
                    title: "Gagal",
                    text: "Data Gagal Diubah",
                    type: "error",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE",
                }).then(function() {
                    location.reload();
                });
            }
        });
    });

    //ajax hapus posyandu
    function hapusposyandu(id_posyandu) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Data Akan Dihapus",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
            closeOnCancel: true // Set this to true to close the dialog when the cancel button is clicked
        }).then(function(result) {
            if (result.value) { // Only delete the data if the user clicked on the confirm button
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('superadmin/posyandu/data/api_hapus/') ?>" +
                        id_posyandu,
                    dataType: "json",
                }).done(function() {
                    swal({
                        title: "Berhasil",
                        text: "Data Berhasil Dihapus",
                        type: "success",
                        showConfirmButton: true,
                        confirmButtonText: "OKEE"
                    }).then(function() {
                        location.reload();
                    });
                }).fail(function() {
                    swal({
                        title: "Gagal",
                        text: "Data Gagal Dihapus",
                        type: "error",
                        showConfirmButton: true,
                        confirmButtonText: "OKEE"
                    }).then(function() {
                        location.reload();
                    });
                });
            } else { // If the user clicked on the cancel button, show a message indicating that the deletion was cancelled
                swal("Batal hapus", "Data Tidak Jadi Dihapus", "error");
            }
        });
    }

</script>

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

//format tgl indonesia
function tgl_indo($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember');

    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>