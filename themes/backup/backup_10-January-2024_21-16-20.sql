#
# TABLE STRUCTURE FOR: tb_balita
#

DROP TABLE IF EXISTS `tb_balita`;

CREATE TABLE `tb_balita` (
  `id_balita` varchar(15) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tempat_lahir` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `nama_ayah` varchar(40) NOT NULL,
  `nama_ibu` varchar(40) NOT NULL,
  PRIMARY KEY (`id_balita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B001ejYHAq', 'Raskara Malik Arimbawa', 'Laki-laki', '2021-09-25', 'Ponorogo', 'Balong', 'Bani', 'Ina');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B002WqCtjH', 'Satria zidane pangarsa', 'Laki-laki', '2021-11-25', 'Ponorogo', 'Siman', 'Budi', 'Nia');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B0036Xvl02', 'Ragil Bayu Setiawan', 'Laki-laki', '2022-01-25', 'Ponorogo', 'Babadan', 'Joni', 'Sri');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B0047BMGKC', 'Afnan Alghofar', 'Laki-laki', '2020-03-26', 'Ponorogo', 'Siman', 'Badrus', 'Hindi');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B005VFgM6v', 'Radhea Salsabila Calista A', 'Perempuan', '2021-06-26', 'Ponorogo', 'Slahung', 'Bandi', 'Sumi');
INSERT INTO `tb_balita` (`id_balita`, `nama`, `jenis_kelamin`, `tgl_lahir`, `tempat_lahir`, `alamat`, `nama_ayah`, `nama_ibu`) VALUES ('B006JyKhLn', 'Amelia Shintya Putri', 'Perempuan', '2021-06-27', 'Ponorogo', 'Kauman', 'Bar', 'Rial');


#
# TABLE STRUCTURE FOR: tb_level
#

DROP TABLE IF EXISTS `tb_level`;

CREATE TABLE `tb_level` (
  `id_level` varchar(2) NOT NULL,
  `level` varchar(15) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('1', 'superadmin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('2', 'admin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('3', 'user');


#
# TABLE STRUCTURE FOR: tb_pengaturan
#

DROP TABLE IF EXISTS `tb_pengaturan`;

CREATE TABLE `tb_pengaturan` (
  `id_pengaturan` varchar(7) NOT NULL,
  `nama_judul` varchar(50) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `background` text NOT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengaturan` (`id_pengaturan`, `nama_judul`, `meta_keywords`, `meta_description`, `background`) VALUES ('P1xhDwL', 'Status Gizi Balita', 'Kassandra Production, Prediksi Gizi, Gizi Anak, Gizi Balita', 'Sistem Prediksi Gizi Balita merupakan web aplikasi yang dirancang untuk mengimplementasikan sistem untuk mengindentifikasi status gizi balita menggunakan metode Fuzzy Tsukamoto', 'header_656f3421970de.jpg');


#
# TABLE STRUCTURE FOR: tb_pengguna
#

DROP TABLE IF EXISTS `tb_pengguna`;

CREATE TABLE `tb_pengguna` (
  `id_pengguna` varchar(15) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `keterangan` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `foto_profile` text NOT NULL,
  `id_level` varchar(2) NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `tb_pengguna_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `tb_level` (`id_level`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A001bnHDs', 'Erik W', '081456141227', 'Ini admin', 'erik@gmail.com', '202cb962ac59075b964b07152d234b70', 'profile_658bb959385e8.jpeg', '1');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A002hfv3Ec', 'Budi', '186361', 'Faskes', 'budi@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A0035j2ctA', 'Dika', '927762', 'Kepala Lab', 'dika@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A004oN9VpN', 'Rani Dwi', '8176351', 'Jingglong', 'rani@gmail.com', '202cb962ac59075b964b07152d234b70', '', '3');


#
# TABLE STRUCTURE FOR: tb_posyandu
#

DROP TABLE IF EXISTS `tb_posyandu`;

CREATE TABLE `tb_posyandu` (
  `id_posyandu` varchar(50) NOT NULL,
  `id_balita` varchar(50) NOT NULL,
  `umur` varchar(4) NOT NULL,
  `tinggi_bb` varchar(5) NOT NULL,
  `berat_bb` varchar(5) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  PRIMARY KEY (`id_posyandu`),
  KEY `id_balita` (`id_balita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P001iiqSEf', 'B001ejYHAq', '27', '86', '11.8', '1', '2024');
INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P002TeTcS4', 'B002WqCtjH', '25', '84', '9.5', '1', '2024');
INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P003pgKdHE', 'B0036Xvl02', '23', '80', '11', '1', '2024');
INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P004bbYsLS', 'B0047BMGKC', '45', '95', '12', '1', '2024');
INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P005V0fDzM', 'B005VFgM6v', '30', '89', '12.6', '1', '2024');
INSERT INTO `tb_posyandu` (`id_posyandu`, `id_balita`, `umur`, `tinggi_bb`, `berat_bb`, `bulan`, `tahun`) VALUES ('P006THacYJ', 'B006JyKhLn', '30', '84', '10', '1', '2024');


