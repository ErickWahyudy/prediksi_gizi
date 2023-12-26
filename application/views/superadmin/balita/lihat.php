<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBalita"><i
                        class="fa fa-plus"></i>
                    Tambah</a>
        

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        <th>Alamat</th>
                                        <th>Nama Ayah</th>
                                        <th>Nama Ibu</th>
                                        <th>Umur</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php $no=1; foreach($data as $balita): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $balita['nama'] ?></td>
                                    <td><?= $balita['jenis_kelamin'] ?></td>
                                    <td><?= $balita['tempat_lahir'] ?>, <?= tgl_indo($balita['tgl_lahir']) ?></td>
                                    <td><?= $balita['alamat'] ?></td>
                                    <td><?= $balita['nama_ayah'] ?></td>
                                    <td><?= $balita['nama_ibu'] ?></td>
                                    <td>
                                        <?php
                                        $tgl_lahir = new DateTime($balita['tgl_lahir']);
                                        $tgl_sekarang = new DateTime();
                                        
                                        $umur_tahun = $tgl_lahir->diff($tgl_sekarang)->y;
                                        $umur_bulan = $tgl_lahir->diff($tgl_sekarang)->m;
                                        $umur_hari = $tgl_lahir->diff($tgl_sekarang)->d;
                                        
                                        $total_bulan = $umur_tahun * 12 + $umur_bulan;
                                        echo $total_bulan . ' Bulan';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit<?= $balita['id_balita'] ?>"><i class="fa fa-edit"></i>
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


<!-- modal tambah balita -->
<div class="modal fade" id="modalTambahBalita" tabindex="-1" role="dialog" aria-labelledby="modalTambahBalitaLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBalitaLabel">Tambah <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="add" method="post">
                        <tr>
                            <td><label for="nama">Nama:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama" id="nama" class="form-control" autocomplete="off"
                                    required placeholder="Nama Balita"></td>
                        </tr>
                        <tr>
                            <td><label for="jenis_kelamin">Jenis Kelamin:</label></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="tgl_lahir">Tanggal Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                    autocomplete="off" required value="<?= date('Y-m-d'); ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="tempat_lahir">Tempat Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    autocomplete="off" required placeholder="Tempat Lahir"></td>
                        </tr>
                        <tr>
                            <td><label for="alamat">Alamat:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="alamat" id="alamat" class="form-control" autocomplete="off"
                                    required placeholder="Alamat"></td>
                        </tr>
                        <tr>
                            <td><label for="nama_ayah">Nama Ayah:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama_ayah" id="nama_ayah" class="form-control"
                                    autocomplete="off" required placeholder="Nama Ayah"></td>
                        </tr>
                        <tr>
                            <td><label for="nama_ibu">Nama Ibu:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama_ibu" id="nama_ibu" class="form-control" autocomplete="off"
                                    required placeholder="Nama Ibu"></td>
                        </tr>

                        <tr>
                            <td><br><input type="submit" name="kirim" value="Simpan" class="btn btn-success"></td>
                        </tr>
                    </form>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- modal edit pbalita -->
<?php foreach($data as $balita): ?>
<div class="modal fade" id="edit<?= $balita['id_balita'] ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="id_balita" value="<?= $balita['id_balita'] ?>">
                        <tr>
                            <td><label for="nama">Nama:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama" id="nama" class="form-control" autocomplete="off"
                                    value="<?= $balita['nama'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="jenis_kelamin">Jenis Kelamin:</label></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                    <option value="Laki-laki"
                                        <?php if ($balita['jenis_kelamin'] == 'Laki-laki') { echo 'selected'; } ?>>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        <?php if ($balita['jenis_kelamin'] == 'Perempuan') { echo 'selected'; } ?>>
                                        Perempuan</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="tgl_lahir">Tanggal Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                    autocomplete="off" value="<?= $balita['tgl_lahir'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="tempat_lahir">Tempat Lahir:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    autocomplete="off" value="<?= $balita['tempat_lahir'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="alamat">Alamat:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="alamat" id="alamat" class="form-control" autocomplete="off"
                                    value="<?= $balita['alamat'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="nama_ayah">Nama Ayah:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama_ayah" id="nama_ayah" class="form-control"
                                    autocomplete="off" value="<?= $balita['nama_ayah'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="nama_ibu">Nama Ibu:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama_ibu" id="nama_ibu" class="form-control" autocomplete="off"
                                    value="<?= $balita['nama_ibu'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>
                                <br><input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                <a href="javascript:void(0)" onclick="hapusbalita('<?= $balita['id_balita'] ?>')"
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


<script>
//add data
$(document).ready(function() {
    $('#add').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('superadmin/balita/api_add') ?>",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                if (data.status) {
                    $('#modalTambahBalita');
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

//edit file
$(document).on('submit', '#edit', function(e) {
    e.preventDefault();
    var form_data = new FormData(this);

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('superadmin/balita/api_edit/') ?>" + form_data.get(
            'id_balita'),
        dataType: "json",
        data: form_data,
        processData: false,
        contentType: false,
        //memanggil swall ketika berhasil
        success: function(data) {
            $('#edit' + form_data.get('id_balita'));
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

//ajax hapus balita
function hapusbalita(id_balita) {
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
                url: "<?php echo site_url('superadmin/balita/api_hapus/') ?>" +
                    id_balita,
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

<?php $this->load->view('template/footer'); ?>

<?php

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