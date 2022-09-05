<?php
session_start();
if(!isset($_SESSION['userlogged']) || ($_SESSION['userlogged'] != 1))
{
    header("Location: ../index.php");
}
if(!isset($_SESSION['roleid']))
{
    header("Location: ../php/logout.php");
}
$allowedroles = array('SYSAD');
if(!in_array($_SESSION['roleid'], $allowedroles))
{
    header("Location: ../php/logout.php");
}
include("../php/connection.php");
if (isset($_GET['token'])) {
  $token = mysqli_real_escape_string($conn, $_GET['token']);
  $sql = "SELECT * 
  FROM maintenances m 
  JOIN instruments i ON m.instrumentid = i.instrumentid 
  JOIN companies c on m.companyid = c.companyid
  JOIN maintenancetypes mt ON m.typeid = mt.typeid
  JOIN maintenancestatus ms ON m.mstatusid = ms.mstatusid
  WHERE m.token='$token'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    $maintenance = mysqli_fetch_assoc($result);
  } else {
    die(mysqli_error($conn));
  }
  //sql to retrieve data for this user
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Instrument Maintenance | Admin</title>

  <!-- To add favicon -->
	<link rel="icon" href="../icon/favicon.ico">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index.php" class="nav-link">Home</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">FKA LAB INVENTORY SYSTEM</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="info">
            <a href="#" class="d-block"><?php if (isset($_SESSION['USER_NAME'])) {
                                          echo $_SESSION['USER_NAME'];
                                        } ?></a>
            <a href="#" class="d-block"><?php if (isset($_SESSION['roletitle'])) {
                                          echo $_SESSION['roletitle'];
                                        } ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="User.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                  User
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Companies.php" class="nav-link">
                <i class="nav-icon fas fa-building "></i>
                <p>
                  Companies
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Instrument.php" class="nav-link">
                <i class="nav-icon fas fa-compass"></i>
                <p>
                  Instrument
                </p>
              </a>
            </li>
            <li class="nav-item menu-open">
              <a href="InstrumentMaintenance.php" class="nav-link active">
                <i class="nav-icon fas fa-toolbox"></i>
                <p>
                  Instrument Maintenance
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../php/logout.php" class="nav-link">
                <i class="nav-icon fas fa-arrow-left"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>View Instrument Maintenance</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="InstrumentMaintenance.php">Instrument Maintenance</a></li>
                <li class="breadcrumb-item active">View Instrument Maintenance</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="inputID">Maintenance ID</label>
                      <input name="id" type="text" class="form-control" id="inputID" value="<?= $maintenance['maintenanceid']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="instrumentID">Instrument Name</label>
                      <input name="iid" class="form-control" id="inputIID" value="<?= $maintenance['instrumentname']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="CompanyID">Company ID</label>
                      <input name="cid" class="form-control" id="inputCID" value="<?= $maintenance['companyid']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="CompanyID">Company Name</label>
                      <input name="cid" class="form-control" id="inputCName" value="<?= $maintenance['companyname']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="maintenanceType">Maintenance Type</label>
                      <input name="typeid" class="form-control" id="inputypeid" value="<?= $maintenance['typetitle']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="SOD">Send Out Date</label>
                      <input name="SOD" class="form-control" id="inputSOD" value="<?= $maintenance['sendoutdate']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="returndate">Return Date</label>
                      <input name="returndate" class="form-control" id="inputReturnDate" value="<?= $maintenance['returndate']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="caliDate">Calibration Date</label>
                      <input name="calidate" class="form-control" id="calidate" value="<?= $maintenance['calibrationdate']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="Ncalidate">Next Calibraton Date</label>
                      <input name="ncalidate" class="form-control" id="ncalidate" value="<?= $maintenance['nextcalibrationdate']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="evidence">Evidence</label>
                      <?php
                      //sql to retrieve images
                      $maintenanceid = $maintenance['maintenanceid'];
                      $sql2 = "SELECT * FROM evidences WHERE maintenanceid = '".$maintenanceid."'";
                      //echo $sql2;
                      $result2 = mysqli_query($conn, $sql2);
                      $row = mysqli_num_rows($result2);
                      if($row >0) {
                      while($maintenance2 = mysqli_fetch_assoc($result2))
                      {
                    ?>
                      <a href="../evidence/<?= $maintenance2['evidencefilename'];?>"></a>
                    <?php
                      } }
                    ?>
                    </div>
                    <div class="form-group">
                      <label for="mstatustitle">Maintenance Status</label>
                      <input name="mstatustitle" class="form-control" id="mstatustitle" value="<?= $maintenance['mstatustitle']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="description">Description</label>
                      <input name="mdesc" class="form-control" id="mdesc" value="<?= $maintenance['description']; ?>" readonly>
                    </div>
                  </div>
                  <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="location.href='InstrumentMaintenance.php'">Back</button>
                </div>
                  <!-- /.card-body -->
                </form>
              </div>
              <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <!--/.col (right) -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      bsCustomFileInput.init();
    });
  </script>
</body>

</html>