<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan'); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="" style="width:100%">
                                <form id="add" method="post">
                                    <tr>
                                        <td width="20%">Nama Balita</td>
                                        <td width="80%">
                                            <input type="text" name="nama" class="form-control" placeholder="Nama Balita" autocomplete="off" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Jenis Kelamin</td>
                                        <td width="80%">
                                            <input type="radio" name="jenis_kelamin" value="Laki-laki" autocomplete="off" required> Laki-laki
                                            <input type="radio" name="jenis_kelamin" value="Perempuan" autocomplete="off" required> Perempuan
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tanggal Lahir</td>
                                        <td width="80%">
                                            <input type="date" name="tgl_lahir" class="form-control" placeholder="Tanggal Lahir" autocomplete="off" required value="<?= date('Y-m-d'); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tempat Lahir</td>
                                        <td width="80%">
                                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir" autocomplete="off" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Alamat</td>
                                        <td width="80%">
                                            <textarea name="alamat" class="form-control" placeholder="Alamat" autocomplete="off" required></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Nama Ayah</td>
                                        <td width="80%">
                                            <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Ayah" autocomplete="off" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Nama Ibu</td>
                                        <td width="80%">
                                            <input type="text" name="nama_ibu" class="form-control" placeholder="Nama Ibu" autocomplete="off" required>
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
                                        <td></td>
                                        <td colspan="2"><br>
                                            <a href="<?= base_url('admin/balita'); ?>" class="btn btn-danger btn-sm">Lihat Data</a>
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
//add data
$(document).ready(function() {
    $('#add').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/balita/api_add') ?>",
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

</script>

<?php $this->load->view('template/footer'); ?>