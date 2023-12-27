<?php $this->load->view('template/header'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if($this->session->userdata('level') == "1"){ ?>

<div class="row" style="display: inline-block;">
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-tasks"></i></div>
                <div class="count"><?= $count_balita['count_balita'] ?></div>
                <h3>Jumlah Balita yang terdaftar</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('superadmin/balita') ?>">
                <div class="icon"><i class="fa fa-smile-o"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Balita</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('superadmin/add_posyandu') ?>">
                <div class="icon"><i class="fa fa-child"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Posyandu</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('superadmin/status_gizi') ?>">
                <div class="count"> <i class="fa fa-bar-chart"></i></div>
                <h3 style="color: #1ABB9C;">Status Gizi Balita</h3>
            </a>
            </div>
        </div>

        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <!-- <div class="x_title">
                <h2><i class="fa fa-bars"></i> Data <?= $judul ?>
            </div> -->
            <div class="x_content">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="demo-container" style="height:280px">
                        <canvas id="chart"></canvas>
                    </div>
                    <div class="tiles">
                        <?php
                        // Prepare data for the chart
                        $chart_labels = [];
                        $chart_data = [];
                        foreach ($data as $row) {
                            $chart_labels[] = $row['nama']; // We don't need the row ID for the chart
                            
                            $tgl_lahir = new DateTime($row['tgl_lahir']);
                            $tgl_sekarang = new DateTime();
                            $umur = $tgl_lahir->diff($tgl_sekarang)->y;
                            $chart_umur[] = $umur;
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modify the Chart.js script for a line chart with multiple datasets -->
<script>
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [
            {
                label: 'Umur (tahun)',
                data: <?php echo json_encode($chart_umur); ?>,
                fill: false,
                borderColor: 'darkred',
                borderWidth: 3
            }
        ]
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    labels: <?php echo json_encode($chart_labels); ?>
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<!-- /page content -->


<?php }elseif($this->session->userdata('level') == "2"){ ?>
    
    <div class="row" style="display: inline-block;">
    <div class="top_tiles">
        <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-tasks"></i></div>
                <div class="count"><?= $count_balita['count_balita'] ?></div>
                <h3>Jumlah Balita yang terdaftar</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('admin/balita') ?>">
                <div class="icon"><i class="fa fa-smile-o"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Balita</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('admin/add_posyandu') ?>">
                <div class="icon"><i class="fa fa-child"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Posyandu</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('admin/status_gizi') ?>">
                <div class="count"> <i class="fa fa-bar-chart"></i></div>
                <h3 style="color: #1ABB9C;">Status Gizi Balita</h3>
            </a>
            </div>
        </div>

        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <!-- <div class="x_title">
                <h2><i class="fa fa-bars"></i> Data <?= $judul ?>
            </div> -->
            <div class="x_content">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="demo-container" style="height:280px">
                        <canvas id="chart"></canvas>
                    </div>
                    <div class="tiles">
                        <?php
                        // Prepare data for the chart
                        $chart_labels = [];
                        $chart_data = [];
                        foreach ($data as $row) {
                            $chart_labels[] = $row['nama']; // We don't need the row ID for the chart
                            
                            $tgl_lahir = new DateTime($row['tgl_lahir']);
                            $tgl_sekarang = new DateTime();
                            $umur = $tgl_lahir->diff($tgl_sekarang)->y;
                            $chart_umur[] = $umur;
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modify the Chart.js script for a line chart with multiple datasets -->
<script>
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [
            {
                label: 'Umur (tahun)',
                data: <?php echo json_encode($chart_umur); ?>,
                fill: false,
                borderColor: 'darkred',
                borderWidth: 3
            }
        ]
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    labels: <?php echo json_encode($chart_labels); ?>
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<!-- /page content -->

<?php }elseif($this->session->userdata('level') == "3"){ ?>

<?php } ?>

<?php $this->load->view('template/footer'); ?>