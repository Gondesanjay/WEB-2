<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplikasi Manajemen Koperasi Pegawai" />
    <meta name="author" content="" />
    <title>Koperasi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <?php include_once '../views/includes/navigation.php'; ?>

    <div id="layoutSidenav">
        <!-- Sidebar Navigation -->
        <?php include_once '../views/includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- Content Header -->
                    <h1 class="mt-4">Pegawai</h1>
                    <p class="mb-4">Data Pegawai</p>

                    <!-- Main Content -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Dashboard
                        </div>
                        <div class="card-body">
                            <h1>THIS IS A DASHBOARD PAGE</h1>
                            <p>User Dashboard <a href="../views/pegawai/list-pegawai.php">click in here</a></p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <?php include_once '../views/includes/footer.php'; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>
