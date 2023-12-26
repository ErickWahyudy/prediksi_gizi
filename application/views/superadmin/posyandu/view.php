<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Gizi Balita</title>
    <!-- Include CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
</head>
<body>

    <h2>Status Gizi Balita</h2>

    <table id="datatable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nama Balita</th>
                <th>Umur (bulan)</th>
                <th>Berat Badan (kg)</th>
                <th>Tinggi Badan (cm)</th>
                <th>Status Gizi</th>
                <th>Z-Score BB/U</th>
                <th>Z-Score TB/U</th>
                <th>Z-Score BB/TB</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through the balita data and display each row -->
            <?php foreach($data as $balita): ?>
                <tr>
                    <td><?php echo $balita['nama']; ?></td>
                    <td><?php echo $balita['umur']; ?></td>
                    <td><?php echo $balita['berat_bb']; ?></td>
                    <td><?php echo $balita['tinggi_bb']; ?></td>
                    <td><?php echo $balita['status_gizi']; ?></td>
                    <td>
                        <?php echo $balita['z_score_bb_u'] ?>
                    </td>
                    <td>
                        <?php echo $balita['z_score_tb_u'] ?>
                    </td>
                    <td>
                        <?php echo $balita['z_score_bb_tb'] ?>
                    </td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

    <script>
        // Activate DataTables
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>

</body>
</html>
