<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?></title>
</head>
<body>

<h2><?= $judul ?></h2>
<!-- Tampilkan hasil status gizi -->
<table border="1">
    <tbody>
    <?php foreach ($result as $row): ?>
        <tr>
            <th>No :</th>
            <td><?= $row['no'] ?></td>
        </tr>
        <tr>
            <th>Nama :</th>
            <td><?= $row['nama'] ?></td>
        </tr>
        <tr>
            <th>Jenis Kelamin :</th>
            <td><?= $row['jenis_kelamin'] ?></td>
        </tr>
        <tr>
            <th>Berat Badan :</th>
            <td><?= $row['berat_badan'] ?></td>
        </tr>
        <tr>
            <th>Tinggi Badan :</th>
            <td><?= $row['tinggi_badan'] ?></td>
        </tr>
        <tr>
            <th>Umur :</th>
            <td><?= $row['umur'] ?></td>
        </tr>
        <tr>
            <th>Umur Fase 1 :</th>
            <td><?= $row['umur_fase1'] ?></td>
        </tr>
        <tr>
            <th>Umur Fase 2 :</th>
            <td><?= $row['umur_fase2'] ?></td>
        </tr>
        <tr>
            <th>Umur Fase 3 :</th>
            <td><?= $row['umur_fase3'] ?></td>
        </tr>
        <tr>
            <th>Umur Fase 4 :</th>
            <td><?= $row['umur_fase4'] ?></td>
        </tr>
        <tr>
            <th>Umur Fase 5 :</th>
            <td><?= $row['umur_fase5'] ?></td>
        </tr>
        <tr>
            <th>BB Ringan :</th>
            <td><?= $row['bb_ringan'] ?></td>
        </tr>
        <tr>
            <th>BB Sedang :</th>
            <td><?= $row['bb_sedang'] ?></td>
        </tr>
        <tr>
            <th>BB Lebih :</th>
            <td><?= $row['bb_lebih'] ?></td>
        </tr>
        <tr>
            <th>TB Pendek :</th>
            <td><?= $row['tb_pendek'] ?></td>
        </tr>
        <tr>
            <th>TB Normal :</th>
            <td><?= $row['tb_normal'] ?></td>
        </tr>
        <tr>
            <th>TB Tinggi :</th>
            <td><?= $row['tb_tinggi'] ?></td>
        </tr>
        <tr>
            <th>Total Alpha :</th>
            <td><?= $row['total_alpha'] ?></td>
        </tr>
        <tr>
            <th>Total Alpha x Z :</th>
            <td><?= $row['total_alpha_z'] ?></td>
        </tr>
        <tr>
            <th>Defuzzy :</th>
            <td><?= $row['defuzzy'] ?></td>
        </tr>
        <tr>
            <th>Status Gizi :</th>
            <td><?= $row['status_gizi'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
