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
/* include db connection file */
include("../php/connection.php");

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $address3 = $_POST['address3'];
  $postcode = $_POST['postcode'];
  $town = $_POST['town'];
  $tel = $_POST['tel'];
  $fax = $_POST['fax'];
  $state = $_POST['state'];

  $token = uniqid();

  $sql = "INSERT INTO companies (companyid, companyname ,companyaddress1, companyaddress2, companyaddress3, companypostcode, companytown, companytel, companyfax, token, stateid)
VALUES ('', '" . $name . "', '" . $address1 . "', '" . $address2 . "', '" . $address3 . "', '" . $postcode . "','" . $town . "', " . $tel . ", '" . $fax . "', '" . $token . "', '" . $state . "')";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "New Company Inserted Successfully";
    header('location:Companies.php');
    exit(0);
  } else {
    $_SESSION['message'] = "New Company Failed to Insert";
    header('location:Companies.php');
    exit(0);
  }
  // exit();
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Company | Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- To add favicon -->
	<link rel="icon" href="../icon/favicon.ico">
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
            <li class="nav-item menu-open">
              <a href="Companies.php" class="nav-link active">
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
            <li class="nav-item">
              <a href="InstrumentMaintenance.php" class="nav-link">
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
              <h1>Add New Company</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="Companies.php">Companies</a></li>
                <li class="breadcrumb-item active">Add New Company</li>
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
                  <h3 class="card-title">Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form name="form" action="addNewCompany.php" method="POST">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="inputName">Company Name</label>
                      <input name="name" type="name" class="form-control" id="inputName" placeholder="Enter Company Name" required>
                    </div>
                    <div class="form-group">
                      <label for="inputAddress">Company Address 1</label>
                      <input name="address1" type="address" class="form-control" id="inputAddress1" placeholder="Enter Company Address 1" required>
                    </div>
                    <div class="form-group">
                      <label for="inputAddress">Company Address 2</label>
                      <input name="address2" type="address" class="form-control" id="inputAddress2" placeholder="Enter Company Address 2" required>
                    </div>
                    <div class="form-group">
                      <label for="inputAddress">Company Address 3</label>
                      <input name="address3" type="address" class="form-control" id="inputAddress3" placeholder="Enter Company Address 3" required>
                    </div>
                    <div class="form-group">
                      <label for="inputPostcode">Company Postcode</label>
                      <input name="postcode" 
                      oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                      type = "number" minlength="4" maxlength = "5" class="form-control" id="inputPostcode" placeholder="Enter Postcode" required>
                    </div>
                    <div class="form-group">
                      <label for="inputTown">Company Town</label>
                      <input name="town" type="town" class="form-control" id="inputTown" placeholder="Enter Town" required>
                    </div>
                    <div class="form-group">
                    <label for="inputRole">Company State</label><br>
                    <select name = "state" id="inputState" value="<?= $company['state'];?>" class="form-control" required>
                    <option value = "">SELECT STATE</option>
                    <option value = "JHR"/>JOHOR
                    <option value = "KDH"/>KEDAH
                    <option value = "KLU"/>KUALA LUMPUR
                    <option value = "KTN"/>KELANTAN
                    <option value = "LBN"/>LABUAN
                    <option value = "MLK"/>MELAKA
                    <option value = "NSN"/>NEGERI SEMBILAN
                    <option value = "PLS"/>PERLIS
                    <option value = "PNG"/>PULAU PINANG
                    <option value = "PRK"/>PERAK
                    <option value = "PTY"/>PUTRAJAYA
                    <option value = "SBH"/>SABAH
                    <option value = "SEL"/>SELANGOR 
                    <option value = "SWK"/>SARAWAK
                    <option value = "TRG">TERENGGANU
                    </select>
                  </div><br>
                    <div class="form-group">
                      <label for="inputTel">Company Tel</label>
                      <input name="tel" type="tel" class="form-control" id="inputTel" minlength="10" maxlength="14" placeholder="Enter Tel"  required>
                    </div>
                    <div class="form-group">
                      <label for="inputFax">Company Fax</label>
                      <input name="fax" type="tel" class="form-control" id="inputFax" minlength="6" maxlength="10"  placeholder="Enter Fax">
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="location.href='Companies.php'">Back</button>
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  </div>
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