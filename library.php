<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .gradient-text {
        background: linear-gradient(90deg, #007bff, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    </style>
</head>

<body>
    <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded">
        <h3 class="text-primary fw-bold">Supply Chain</h3>
        <nav>
            <a href="index.php?page=0" class="me-3 text-decoration-none">Barang</a>
            <a href="index.php?page=4" class="me-3 text-decoration-none">Tipe</a>
            <a href="index.php?page=8" class="me-3 text-decoration-none">Pelanggan</a>
            <a href="index.php?page=12" class="text-decoration-none">Transaksi</a>
        </nav>
    </div>
    <?php
    function GET($key, $default)
    {
        $val = isset($_SESSION[$key]) && $_SESSION[$key] != '' ? $_SESSION[$key] : $default;
        $val = isset($_POST[$key]) && $_POST[$key] != '' ? $_POST[$key] : $val;
        $val = isset($_GET[$key]) && $_GET[$key] != '' ? $_GET[$key] : $val;
        return $val;
    }

    function DataBarang()
    {
        include 'koneksi.php';

        $page = GET('page', 0);

        if ($page == 1) { // Menambah barang
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Tambah Barang</div>';
            echo '<div class="card-body">';

            $name = $_POST['nama'] ?? '';
            $price = $_POST['harga'] ?? '';
            $stock = $_POST['stok'] ?? '';
            $kategori = $_POST['kategori'] ?? '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($name) && !empty($price) && !empty($stock) && !empty($kategori)) {
                $sql = "INSERT INTO tb_barang (brg_nama, brg_harga, brg_stok, brg_jenis, brg_time) VALUES ('$name', '$price', '$stock', '$kategori', NOW())";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    header('Location: index.php?page=0');
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
            }

            echo '<form method="POST" action="index.php?page=' . $page . '">';

            echo '<div class="mb-3">';
            echo '<label for="nama" class="form-label">Nama Barang</label>';
            echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $name . '" required>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="harga" class="form-label">Harga</label>';
            echo '<input type="number" class="form-control" id="harga" name="harga" value="' . $price . '" required>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="stok" class="form-label">Stok</label>';
            echo '<input type="number" class="form-control" id="stok" name="stok" value="' . $stock . '" required>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="kategori" class="form-label">Kategori</label>';
            echo '<select class="form-select" id="kategori" name="kategori" required>';
            echo '<option value="">Pilih Kategori</option>';
            
            // Query to get categories from tb_jenis
            $sqlJenis = "SELECT * FROM tb_jenis";
            $queryJenis = mysqli_query($conn, $sqlJenis);

            // Loop through categories
            while ($jenis = mysqli_fetch_assoc($queryJenis)) {
                echo '<option value="' . $jenis['jenis_id'] . '">' . $jenis['jenis_nama'] . '</option>';
            }

            echo '</select>';
            echo '</div>';

            echo '<button type="submit" class="btn btn-primary">Tambah</button>';
            echo '<a href="index.php?page=0" class="btn btn-danger ms-3">Kembali</a>';
            echo '</form>';

            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close container
        }



        if ($page == 2) {
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Hapus Barang</div>';
            echo '<div class="card-body">';
            $id = GET('id', 0);

            if ($id) {
                $sql = "DELETE FROM tb_barang WHERE brg_id = '$id'";
                $query = mysqli_query($conn, $sql);

                if ($query) {
                    // Reset auto-increment
                    mysqli_query($conn, "ALTER TABLE tb_barang AUTO_INCREMENT = 1");
                    header('Location: index.php?page=0');
                    exit();
                } else {
                    echo 'Error: ' . mysqli_error($conn);
                }
            }

            echo '<a href="index.php?page=0" class="btn btn-danger">Kembali</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }


        if ($page == 3) { // Form Edit Barang
            $id = GET('id', 0);
            include 'koneksi.php';

            if ($id) {
                $sql = "SELECT * FROM tb_barang WHERE brg_id = '$id'";
                $result = mysqli_query($conn, $sql);
                $barang = mysqli_fetch_assoc($result);
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['nama'] ?? '';
                    $harga = $_POST['harga'] ?? '';
                    $stok = $_POST['stok'] ?? '';
                    $brg_jenis = $_POST['jenis'] ?? '';
                
                    if (!empty($name) && !empty($harga) && !empty($stok) && !empty($brg_jenis)) {
                        $sql = "UPDATE tb_barang SET 
                                brg_nama = '$name',
                                brg_harga = '$harga',
                                brg_stok = '$stok',
                                brg_jenis = '$brg_jenis',
                                brg_time = NOW()
                                WHERE brg_id = '$id'";

                        if (mysqli_query($conn, $sql)) {
                            header('Location: index.php?page=0');
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                        }
                    }
                }
            
                if ($barang) {
                    echo '<div class="container mt-5">';
                    echo '<div class="card">';
                    echo '<div class="card-header bg-warning text-white display-6 fw-bold">Edit Barang</div>';
                    echo '<div class="card-body">';
                    echo '<form method="POST" action="index.php?page=3&id=' . $id . '">';
                
                    echo '<div class="mb-3">';
                    echo '<label for="nama" class="form-label">Nama Barang</label>';
                    echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $barang['brg_nama'] . '" required>';
                    echo '</div>';
                
                    echo '<div class="mb-3">';
                    echo '<label for="harga" class="form-label">Harga barang</label>';
                    echo '<input type="number" class="form-control" id="harga" name="harga" value="' . $barang['brg_harga'] . '" required>';
                    echo '</div>';
                
                    echo '<div class="mb-3">';
                    echo '<label for="stok" class="form-label">Stok Barang</label>';
                    echo '<input type="number" class="form-control" id="stok" name="stok" value="' . $barang['brg_stok'] . '" required>';
                    echo '</div>';

                    echo '<div class="mb-3">';
                    echo '<label for="jenis" class="form-label">Kategori</label>';
                    echo '<select class="form-select" id="jenis" name="jenis" required>';
                    echo '<option value="">Pilih Kategori</option>';

                    // Query to get categories from tb_jenis
                    $sqlJenis = "SELECT * FROM tb_jenis";
                    $queryJenis = mysqli_query($conn, $sqlJenis);

                    // Loop through categories and mark the selected one
                    while ($jenis = mysqli_fetch_assoc($queryJenis)) {
                        $selected = ($barang['brg_jenis'] == $jenis['jenis_id']) ? 'selected' : '';
                        echo '<option value="' . $jenis['jenis_id'] . '" ' . $selected . '>' . $jenis['jenis_nama'] . '</option>';
                    }

                    echo '</select>';
                    echo '</div>';
                
                    echo '<button type="submit" class="btn btn-warning">Update</button>';
                    echo '<a href="index.php?page=0" class="btn btn-danger ms-3">Kembali</a>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "<div class='alert alert-danger'>Barang tidak ditemukan!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ID tidak valid!</div>";
            }
        }

        if ($page == 4) {
            echo '
                <div class="container mt-5">
                    <div class="border-start border-4 border-primary ps-3 mb-4">
                        <h4 class="text-dark mb-0">Tipe Barang</h4>
                    </div>
                </div>
            ';
            echo '<div class="container mt-5">';
            echo '<a href="index.php?page=5" class="btn btn-success mb-3">+ Tambah Tipe</a>';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Deskripsi</th>';
            echo '<th>Ditambahkan</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $sql = "SELECT * FROM tb_jenis";
            $query = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
                echo '<tr>
                <td>' . $row['jenis_id'] . '</td>
                <td>' . $row['jenis_nama'] . '</td>
                <td>' . $row['jenis_time'] . '</td>
                <td>
                    <a href="index.php?page=6&id='.$row['jenis_id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item ini?\');">Hapus</a>
                    <a href="index.php?page=7&id=' . $row['jenis_id'] . '" class="btn btn-primary btn-sm">Edit</a>
                </td>
                </td>

            </tr>';
            }
            echo '</table>';
            

        }

        if ($page == 5){
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Tambah Tipe</div>';
            echo '<div class="card-body">';

            $name = $_POST['nama'] ?? '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($name)) {
                $sql = "INSERT INTO tb_jenis (jenis_nama, jenis_time) VALUES ('$name', NOW())";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    header('Location: index.php?page=4');
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
            }

            echo '<form method="POST" action="index.php?page=' . $page . '">';

            echo '<div class="mb-3">';
            echo '<label for="nama" class="form-label">Deskripsi</label>';
            echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $name . '" required>';
            echo '</div>';

            echo '<button type="submit" class="btn btn-primary">Tambah</button>';
            echo '<a href="index.php?page=4" class="btn btn-danger ms-3">Kembali</a>';
            echo '</form>';

            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close container

        }

        if ($page == 6){
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Hapus Tipe</div>';
            echo '<div class="card-body">';
            $id = GET('id', 0);

            if ($id) {
                $sql = "DELETE FROM tb_jenis WHERE jenis_id = '$id'";
                $query = mysqli_query($conn, $sql);

                if ($query) {
                    // Reset auto-increment
                    mysqli_query($conn, "ALTER TABLE tb_jenis AUTO_INCREMENT = 1");
                    header('Location: index.php?page=4');
                    exit();
                } else {
                    echo 'Error: ' . mysqli_error($conn);
                }
            }

            echo '<a href="index.php?page=4" class="btn btn-danger">Kembali</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        if ($page == 7){
            $id = GET('id', 0);
            include 'koneksi.php';

            if ($id) {
                $sql = "SELECT * FROM tb_jenis WHERE jenis_id = '$id'";
                $result = mysqli_query($conn, $sql);
                $barang = mysqli_fetch_assoc($result);
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['nama'] ?? '';
                
                    if (!empty($name) ) {
                        $sql = "UPDATE tb_jenis SET 
                                jenis_nama = '$name',
                                jenis_time = NOW()
                                WHERE jenis_id = '$id'";

                        if (mysqli_query($conn, $sql)) {
                            header('Location: index.php?page=4');
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                        }
                    }
                }
            
                if ($barang) {
                    echo '<div class="container mt-5">';
                    echo '<div class="card">';
                    echo '<div class="card-header bg-warning text-white display-6 fw-bold">Edit Pelanggan</div>';
                    echo '<div class="card-body">';
                    echo '<form method="POST" action="index.php?page=7&id=' . $id . '">';
                
                    echo '<div class="mb-3">';
                    echo '<label for="nama" class="form-label">Deskripsi</label>';
                    echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $barang['jenis_nama'] . '" required>';
                    echo '</div>';
                
                    echo '<button type="submit" class="btn btn-warning">Update</button>';
                    echo '<a href="index.php?page=4" class="btn btn-danger ms-3">Kembali</a>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "<div class='alert alert-danger'>Barang tidak ditemukan!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ID tidak valid!</div>";
            }
        
        }

        if ($page == 8){
            echo '
                <div class="container mt-5">
                    <div class="border-start border-4 border-primary ps-3 mb-4">
                        <h4 class="text-dark mb-0">Daftar Pelanggan</h4>
                    </div>
                    <!-- Tambahkan logika untuk menampilkan transaksi -->
                </div>
            ';
            echo '<div class="container mt-5">';
            echo '<a href="index.php?page=9" class="btn btn-success mb-3">+ Tambah Pelanggan</a>';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>ID Pelanggan</th>';
            echo '<th>Nama Pelanggan</th>';
            echo '<th>Alamat</th>';
            echo '<th>Telepon / HP</th>';
            echo '<th>Ditambahkan</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $sql = "SELECT * FROM tb_pelanggan";
            $query = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
                echo '<tr>
                <td>' . $row['pelanggan_id'] . '</td>
                <td>' . $row['pelanggan_nama'] . '</td>
                <td> ' . $row['pelanggan_alamat'] . '</td>
                <td>' . $row['pelanggan_telepon'] . '</td>
                <td>' . $row['pelanggan_time'] . '</td>
                <td>
                    <a href="index.php?page=10&id='.$row['pelanggan_id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item ini?\');">Hapus</a>
                    <a href="index.php?page=11&id=' . $row['pelanggan_id'] . '" class="btn btn-primary btn-sm">Edit</a>
                </td>
                </td>

            </tr>';
            }
            echo '</table>';
        }

        if ($page == 9){
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Tambah Pelanggan</div>';
            echo '<div class="card-body">';

            $name = $_POST['nama'] ?? '';
            $alamat = $_POST['alamat'] ?? '';
            $telepon = $_POST['telepon'] ?? '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($name) && !empty($alamat) && !empty($telepon)) {
                $sql = "INSERT INTO tb_pelanggan (pelanggan_nama, pelanggan_alamat, pelanggan_telepon, pelanggan_time) VALUES ('$name', '$alamat', '$telepon', NOW())";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    header('Location: index.php?page=8');
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
            }

            echo '<form method="POST" action="index.php?page=' . $page . '">';

            echo '<div class="mb-3">';
            echo '<label for="nama" class="form-label">Nama Pelanggan</label>';
            echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $name . '" required>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="alamat" class="form-label">Alamat</label>';
            echo '<input type="text" class="form-control" id="alamat" name="alamat" value="' . $alamat . '" required>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="telepon" class="form-label">Nomor Telepon</label>';
            echo '<input type="number" class="form-control" id="telepon" name="telepon" value="' . $telepon . '" required>';
            echo '</div>';

            echo '<button type="submit" class="btn btn-primary">Tambah</button>';
            echo '<a href="index.php?page=8" class="btn btn-danger ms-3">Kembali</a>';
            echo '</form>';

            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close container
        }

        if ($page == 10){
            $id = GET('id', 0);

            if ($id) {
                $sql = "DELETE FROM tb_pelanggan WHERE pelanggan_id = '$id'";
                $query = mysqli_query($conn, $sql);
            
                if ($query) {
                    // Reset auto-increment
                    mysqli_query($conn, "ALTER TABLE tb_pelanggan AUTO_INCREMENT = 1");
                    header('Location: index.php?page=8');
                    exit();
                } else {
                    echo 'Error: ' . mysqli_error($conn);
                }
            }
        }

        if ($page == 11){
            $id = GET('id', 0);
            include 'koneksi.php';

            if ($id) {
                $sql = "SELECT * FROM tb_pelanggan WHERE pelanggan_id = '$id'";
                $result = mysqli_query($conn, $sql);
                $barang = mysqli_fetch_assoc($result);
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['nama'] ?? '';
                    $alamat = $_POST['alamat'] ?? '';
                    $telepon = $_POST['telepon'] ?? '';
                
                    if (!empty($name) && !empty($alamat) && !empty($telepon)) {
                        $sql = "UPDATE tb_pelanggan SET 
                                pelanggan_nama = '$name',
                                pelanggan_alamat = '$alamat',
                                pelanggan_telepon = '$telepon',
                                pelanggan_time = NOW()
                                WHERE pelanggan_id = '$id'";

                        if (mysqli_query($conn, $sql)) {
                            header('Location: index.php?page=8');
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                        }
                    }
                }
            
                if ($barang) {
                    echo '<div class="container mt-5">';
                    echo '<div class="card">';
                    echo '<div class="card-header bg-warning text-white display-6 fw-bold">Edit Pelanggan</div>';
                    echo '<div class="card-body">';
                    echo '<form method="POST" action="index.php?page=11&id=' . $id . '">';
                
                    echo '<div class="mb-3">';
                    echo '<label for="nama" class="form-label">Nama Pelanggan</label>';
                    echo '<input type="text" class="form-control" id="nama" name="nama" value="' . $barang['pelanggan_nama'] . '" required>';
                    echo '</div>';
                
                    echo '<div class="mb-3">';
                    echo '<label for="alamat" class="form-label">Alamat</label>';
                    echo '<input type="text" class="form-control" id="alamat" name="alamat" value="' . $barang['pelanggan_alamat'] . '" required>';
                    echo '</div>';
                
                    echo '<div class="mb-3">';
                    echo '<label for="telepon" class="form-label">Nomor Telepon</label>';
                    echo '<input type="number" class="form-control" id="telepon" name="telepon" value="' . $barang['pelanggan_telepon'] . '" required>';
                    echo '</div>';
                
                    echo '<button type="submit" class="btn btn-warning">Update</button>';
                    echo '<a href="index.php?page=8" class="btn btn-danger ms-3">Kembali</a>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "<div class='alert alert-danger'>Barang tidak ditemukan!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ID tidak valid!</div>";
            }
        }

        if ($page == 12){
            echo '
                <div class="container mt-5">
                    <div class="border-start border-4 border-primary ps-3 mb-4">
                        <h4 class="text-dark mb-0">Daftar Transaksi</h4>
                    </div>
                </div>
            ';
            echo '<div class="container mt-5">';
            echo '<a href="index.php?page=13" class="btn btn-success mb-3">+ Tambah Transaksi</a>';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Tanggal</th>';
            echo '<th>Pelanggan</th>';
            echo '<th>Barang</th>';
            echo '<th>Jumlah</th>';
            echo '<th>Total</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $sql = "SELECT t.*, p.pelanggan_nama, b.brg_nama, b.brg_harga 
                    FROM tb_transaksi t
                    JOIN tb_pelanggan p ON t.transaksi_pelanggan = p.pelanggan_id
                    JOIN tb_barang b ON t.transaksi_barang = b.brg_id";
            $query = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
                echo '<tr>
                <td>' . $row['transaksi_id'] . '</td>
                <td>' . $row['transaksi_time'] . '</td>
                <td>' . $row['pelanggan_nama'] . '</td>
                <td>' . $row['brg_nama'] . '</td>
                <td>' . $row['transaksi_jumlah'] . '</td>
                <td>Rp. ' . number_format($row['transaksi_total'], 0, ',', '.') . '</td>
                <td>
                    <a href="index.php?page=14&id='.$row['transaksi_id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item ini?\');">Hapus</a>
                    <a href="index.php?page=15&id=' . $row['transaksi_id'] . '" class="btn btn-primary btn-sm">Edit</a>
                </td>
            </tr>';
            }
            echo '</table>';
        }

        if ($page == 13){
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Tambah Transaksi</div>';
            echo '<div class="card-body">';

            $pelanggan = $_POST['pelanggan'] ?? '';
            $barang = $_POST['barang'] ?? '';
            $jumlah = $_POST['jumlah'] ?? '';
            $total = 'total harga dengan rumus jumlah pembelian dikali dengan harga barang';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($pelanggan) && !empty($barang) && !empty($jumlah)) {
                // Get the price of the selected item
                $sqlGetPrice = "SELECT brg_harga FROM tb_barang WHERE brg_id = '$barang'";
                $queryPrice = mysqli_query($conn, $sqlGetPrice);
                $rowPrice = mysqli_fetch_assoc($queryPrice);
                $hargaBarang = $rowPrice['brg_harga'];

                // Calculate total
                $total = $jumlah * $hargaBarang;

                $sql = "INSERT INTO tb_transaksi (transaksi_time, transaksi_pelanggan, transaksi_barang, transaksi_jumlah, transaksi_total) VALUES (NOW(),'$pelanggan', '$barang', '$jumlah', '$total')";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    header('Location: index.php?page=12');
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
            }

            echo '<form method="POST" action="index.php?page=' . $page . '">';

            echo '<div class="mb-3">';
            echo '<label for="pelanggan" class="form-label">Pelanggan</label>';
            echo '<select class="form-select" id="pelanggan" name="pelanggan" required>';
            echo '<option value="">Pilih Pelanggan</option>';
            
            // Query to get categories from tb_jenis
            $sqlJenis = "SELECT * FROM tb_pelanggan";
            $queryJenis = mysqli_query($conn, $sqlJenis);

            // Loop through categories
            while ($jenis = mysqli_fetch_assoc($queryJenis)) {
                echo '<option value="' . $jenis['pelanggan_id'] . '">' . $jenis['pelanggan_nama'] . '</option>';
            }

            echo '</select>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="barang" class="form-label">Barang</label>';
            echo '<select class="form-select" id="barang" name="barang" required>';
            echo '<option value="">Pilih Pelanggan</option>';
            
            // Query to get categories from tb_jenis
            $sqlJenis = "SELECT * FROM tb_barang";
            $queryJenis = mysqli_query($conn, $sqlJenis);

            // Loop through categories
            while ($jenis = mysqli_fetch_assoc($queryJenis)) {
                echo '<option value="' . $jenis['brg_id'] . '">' . $jenis['brg_nama'] . '</option>';
            }

            echo '</select>';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="jumlah" class="form-label">Jumlah</label>';
            echo '<input type="number" class="form-control" id="jumlah" name="jumlah" value="' . $jumlah . '" required>';
            echo '</div>';

            echo '<button type="submit" class="btn btn-primary">Tambah</button>';
            echo '<a href="index.php?page=12" class="btn btn-danger ms-3">Kembali</a>';
            echo '</form>';

            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close container
        }

        if ($page == 14){
            echo '<div class="container mt-5">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white display-6 fw-bold ">Hapus Transaksi</div>';
            echo '<div class="card-body">';
            $id = GET('id', 0);

            if ($id) {
                $sql = "DELETE FROM tb_transaksi WHERE transaksi_id = '$id'";
                $query = mysqli_query($conn, $sql);

                if ($query) {
                    // Reset auto-increment
                    mysqli_query($conn, "ALTER TABLE tb_transaksi AUTO_INCREMENT = 1");
                    header('Location: index.php?page=12');
                    exit();
                } else {
                    echo 'Error: ' . mysqli_error($conn);
                }
            }

            echo '<a href="index.php?page=12" class="btn btn-danger">Kembali</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        if ($page == 15){
            $id = GET('id', 0);
            include 'koneksi.php';
        
            if ($id) {
                $sql = "SELECT * FROM tb_transaksi WHERE transaksi_id = '$id'";
                $result = mysqli_query($conn, $sql);
                $transaksi = mysqli_fetch_assoc($result);
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $pelanggan = $_POST['pelanggan'] ?? '';
                    $barang = $_POST['barang'] ?? '';
                    $jumlah = $_POST['jumlah'] ?? '';
        
                    // Get the price of the selected item
                    $sqlGetPrice = "SELECT brg_harga FROM tb_barang WHERE brg_id = '$barang'";
                    $queryPrice = mysqli_query($conn, $sqlGetPrice);
                    $rowPrice = mysqli_fetch_assoc($queryPrice);
                    $hargaBarang = $rowPrice['brg_harga'];
        
                    // Calculate total
                    $total = $jumlah * $hargaBarang;
        
                    if (!empty($pelanggan) && !empty($barang) && !empty($jumlah)) {
                        $sql = "UPDATE tb_transaksi SET 
                                transaksi_time = NOW(),
                                transaksi_pelanggan = '$pelanggan',
                                transaksi_barang = '$barang',
                                transaksi_jumlah = '$jumlah',
                                transaksi_total = '$total'
                                WHERE transaksi_id = '$id'";
        
                        if (mysqli_query($conn, $sql)) {
                            header('Location: index.php?page=12');
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                        }
                    }
                }
            
                if ($transaksi) {
                    echo '<div class="container mt-5">';
                    echo '<div class="card">';
                    echo '<div class="card-header bg-warning text-white display-6 fw-bold">Edit Transaksi</div>';
                    echo '<div class="card-body">';
                    echo '<form method="POST" action="index.php?page=15&id=' . $id . '">';
                
                    echo '<div class="mb-3">';
                    echo '<label for="pelanggan" class="form-label">Pelanggan</label>';
                    echo '<select class="form-select" id="pelanggan" name="pelanggan" required>';
                    
                    // Query to get customers from tb_pelanggan
                    $sqlPelanggan = "SELECT * FROM tb_pelanggan";
                    $queryPelanggan = mysqli_query($conn, $sqlPelanggan);
        
                    // Loop through customers and mark the selected one
                    while ($pelanggan = mysqli_fetch_assoc($queryPelanggan)) {
                        $selected = ($transaksi['transaksi_pelanggan'] == $pelanggan['pelanggan_id']) ? 'selected' : '';
                        echo '<option value="' . $pelanggan['pelanggan_id'] . '" ' . $selected . '>' . $pelanggan['pelanggan_nama'] . '</option>';
                    }
        
                    echo '</select>';
                    echo '</div>';
        
                    echo '<div class="mb-3">';
                    echo '<label for="barang" class="form-label">Barang</label>';
                    echo '<select class="form-select" id="barang" name="barang" required>';
                    
                    // Query to get items from tb_barang
                    $sqlBarang = "SELECT * FROM tb_barang";
                    $queryBarang = mysqli_query($conn, $sqlBarang);
        
                    // Loop through items and mark the selected one
                    while ($barang = mysqli_fetch_assoc($queryBarang)) {
                        $selected = ($transaksi['transaksi_barang'] == $barang['brg_id']) ? 'selected' : '';
                        echo '<option value="' . $barang['brg_id'] . '" ' . $selected . '>' . $barang['brg_nama'] . '</option>';
                    }
        
                    echo '</select>';
                    echo '</div>';
        
                    echo '<div class="mb-3">';
                    echo '<label for="jumlah" class="form-label">Jumlah</label>';
                    echo '<input type="number" class="form-control" id="jumlah" name="jumlah" value="' . $transaksi['transaksi_jumlah'] . '" required>';
                    echo '</div>';
                
                    echo '<button type="submit" class="btn btn-warning">Update</button>';
                    echo '<a href="index.php?page=12" class="btn btn-danger ms-3">Kembali</a>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "<div class='alert alert-danger'>Transaksi tidak ditemukan!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ID tidak valid!</div>";
            }
        }


        if ($page == 0) {                            //menampilkan barang
            echo '
                <div class="container mt-5">
                    <div class="border-start border-4 border-primary ps-3 mb-4">
                        <h4 class="text-dark mb-0">Daftar Barang</h4>
                    </div>
                </div>
            ';
            echo '<div class="container mt-5">';
            echo '<a href="index.php?page=1" class="btn btn-success mb-3">+ Tambah Barang</a>';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>ID Barang</th>';
            echo '<th>Nama Barang</th>';
            echo '<th>Harga</th>';
            echo '<th>Stok</th>';
            echo '<th>Jenis</th>';
            echo '<th>Time Update</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Modified SQL with JOIN
            $sql = "SELECT tb_barang.*, tb_jenis.jenis_nama 
            FROM tb_barang 
            LEFT JOIN tb_jenis ON tb_barang.brg_jenis = tb_jenis.jenis_id";
            $query = mysqli_query($conn, $sql);
                
            while ($row = mysqli_fetch_assoc($query)) {
            echo '<tr>
            <td>' . $row['brg_id'] . '</td>
            <td>' . $row['brg_nama'] . '</td>
            <td>Rp. ' . number_format($row['brg_harga'], 0, ',', '.') . '</td>
            <td>' . $row['brg_stok'] . '</td>
            <td>' . $row['jenis_nama'] . '</td> <!-- Display jenis_nama -->
            <td>' . $row['brg_time'] . '</td>
            <td>
                <a href="index.php?page=2&id='.$row['brg_id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item ini?\');">Hapus</a>
                <a href="index.php?page=3&id=' . $row['brg_id'] . '" class="btn btn-primary btn-sm">Edit</a>
            </td>
            </tr>';
            }
            echo '</table>';
        }
    }
    ?>
</body>