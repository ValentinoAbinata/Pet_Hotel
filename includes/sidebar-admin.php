<?php if (!isset($conn)) include_once '../config/database.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../../dashboard.php">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../hewan/">
                            Data Hewan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../monitoring/">
                            Monitoring Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reservasi/">
                            Reservasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pelanggan/">
                            Data Pelanggan
                        </a>
                    </li>
                </ul>
            </div>
        </nav>