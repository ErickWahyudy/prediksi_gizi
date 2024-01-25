<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $judul ?></title>
    <meta name="keywords" content="<?= $nama_judul ?>, <?= $meta_keywords ?>, <?= $meta_description ?>, kassandra, kassandra hd production, KASSANDRA, KASSANDRA HD PRODUCTION">
    <meta name="description" content="<?= $nama_judul ?>, <?= $meta_keywords ?>, <?= $meta_description ?>">
    <meta name="author" content="KASSANDRA, KASSANDRA HD PRODUCTION">
    <meta content='index,follow' name='robots'/>

    <!-- Bootstrap -->
    <link href="<?= base_url('themes/gentelella') ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= base_url('themes/gentelella') ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?= base_url('themes/gentelella') ?>/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?= base_url('themes/gentelella') ?>/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?= base_url('themes/gentelella') ?>/build/css/custom.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="<?= base_url('themes/gentelella') ?>/vendors/jquery/dist/jquery.min.js"></script>

    <!-- Favicon -->
    <link href="<?= base_url('themes') ?>/favicon.ico" rel="icon">

    <!-- sweetalert -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  </head>

  <body class="login" background="<?= base_url('themes/foto_background/'.$background) ?>" style="background-size: cover; background-attachment: fixed;">
  <?= $this->session->flashdata('pesan') ?>
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
          <form action="" method="post">
              <h1>Login Form</h1>
              <div>
                <input type="text" class="form-control" name="email" placeholder="Email" required="" autocomplete="off" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required="" autocomplete="off" />
              </div>
              <div>
                <button type="submit" name="login" class="btn btn-primary submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
					<strong>Copyright &copy; <?php echo date('Y'); ?>
                        <?php  $nama_judul = $this->db->get('tb_pengaturan')->row_array(); ?>
					    <a href="https://bit.ly/kassandrahdproduction" target="blank"><?= $nama_judul['nama_judul'] ?>.</a></strong> <br> All rights reserved.
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form id="add" method="post">
              <h1>Create Account User</h1>
              <div>
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required="" autocomplete="off" />
              </div>
              <div>
                <input type="text" class="form-control" name="no_hp" placeholder="No HP" required="" autocomplete="off" />
              </div>
              <div>
                <input type="text" class="form-control" name="keterangan" placeholder="Alamat" required="" autocomplete="off" />
              </div>
              <div>
                <input type="email" class="form-control" name="email" placeholder="Email" required="" autocomplete="off" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required="" autocomplete="off" />
              </div>
              <div>
                <button type="submit" class="btn btn-primary submit">Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
					<strong>Copyright &copy; <?php echo date('Y'); ?>
                        <?php  $nama_judul = $this->db->get('tb_pengaturan')->row_array(); ?>
					    <a href="https://bit.ly/kassandrahdproduction" target="blank"><?= $nama_judul['nama_judul'] ?>.</a></strong> <br> All rights reserved.
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
<script type="text/javascript">
  //register data
  $(document).ready(function() {
      $('#add').submit(function(e) {
          e.preventDefault();
          $.ajax({
              url: "<?= site_url('register/api_add') ?>",
              type: "POST",
              data: new FormData(this),
              processData: false,
              contentType: false,
              cache: false,
              async: false,
              success: function(data) {
                  if (data.status) {
                      $('#add')[0].reset();
                      swal({
                          title: "Berhasil",
                          text: "Data berhasil ditambahkan",
                          type: "success",
                          showConfirmButton: true,
                          confirmButtonText: "OKEE",
                      });
                      setTimeout(function() {
                          window.location.href = "<?= site_url('login') ?>";
                      }, 1000);
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
