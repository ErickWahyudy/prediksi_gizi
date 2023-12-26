<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr style="text-align: center">
                                    <th rowspan="2" style="vertical-align: middle">No</th>
                                    <th rowspan="2" style="vertical-align: middle">Nama</th>
                                    <th rowspan="2" style="vertical-align: middle">Umur (bulan)</th>
                                    <th rowspan="2" style="vertical-align: middle">Tinggi Badan (cm)</th>
                                    <th rowspan="2" style="vertical-align: middle">Berat Badan (kg)</th>
                                    <!-- <th colspan="3" style="vertical-align: middle">Z-Score</th> -->
                                    <!-- <th rowspan="2" style="vertical-align: middle">total_z_score</th> -->
                                    <th colspan="4" style="vertical-align: middle">Status Gizi</th>
                                </tr>
                                <tr>
                                    <!-- <th style="vertical-align: middle">BB/U</th>
                                    <th style="vertical-align: middle">TB/U</th>
                                    <th style="vertical-align: middle">BB/TB</th> -->
                                    <th style="vertical-align: middle">BB/U</th>
                                    <th style="vertical-align: middle">TB/U</th>
                                    <th style="vertical-align: middle">BB/TB</th>
                                    <th style="vertical-align: middle">Total</th>
                                </tr>
                                </thead>
                                <?php $no=1; foreach($data as $balita): ?>
                                <tr style="text-align: center">
                                <td><?php echo $no; ?></td>
                                <td><?php echo $balita['nama']; ?></td>
                                <td><?php echo $balita['umur']; ?></td>
                                <td><?php echo $balita['tinggi_bb']; ?></td>
                                <td><?php echo $balita['berat_bb']; ?></td>
                                <!-- <td><?php echo $balita['z_score_bb_u']; ?></td>
                                <td><?php echo $balita['z_score_tb_u']; ?></td>
                                <td><?php echo $balita['z_score_bb_tb']; ?></td> -->
                                <!-- <td><?php echo $balita['total_z_score']; ?></td> -->
                                <td><?php echo $balita['status_gizi_bbu']; ?></td>
                                <td><?php echo $balita['status_gizi_tbu']; ?></td>
                                <td><?php echo $balita['status_gizi_bb_tb']; ?></td>
                                <td><?php echo $balita['status_gizi']; ?></td>
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

<?php $this->load->view('template/footer'); ?>