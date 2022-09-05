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
$allowedroles = array('MODER');
if(!in_array($_SESSION['roleid'], $allowedroles))
{
    header("Location: ../php/logout.php");
}
/* include db connection file */
include("../php/connection.php");

if(isset($_POST['submit'])){

  $select = mysqli_query($conn, "SELECT * FROM staffroles WHERE USER_ID = '".$_POST['id']."'");
  if(mysqli_num_rows($select)) {
    $_SESSION['message'] = "Lab Assistant Failed to Insert: Lab Assistant Already Exist!";
    header('location:User.php');
    exit(0);
  }

$userid = mysqli_real_escape_string($conn, $_POST['id']);
$role = mysqli_real_escape_string($conn, $_POST['roleid']);
$accessstatus = mysqli_real_escape_string($conn, $_POST['astatusid']);
$labid = mysqli_real_escape_string($conn, $_POST['laboratoryid']);

$token = uniqid();
  

$sql = "INSERT INTO staffroles (srid, token, USER_ID, roleid, astatusid)
VALUES ('', '" . $token . "', '" . $userid . "', '" . $role . "', '" . $accessstatus . "')";
$sql2 = "INSERT INTO incharges (inchargeid, USER_ID, laboratoryid)
VALUES ('', '" . $userid . "', '" . $labid . "')";
$result = mysqli_query($conn,$sql);
$result2 = mysqli_query($conn,$sql2);
if($result && $result2){
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "Lab Assistant Inserted Successfully";
    header('location:User.php');
    exit(0);
} else {
    $_SESSION['message'] = "Lab Assistant Failed to Insert: Fields Are Not Filled Out!";
    header('location:User.php');
    exit(0);
}
// exit();
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Lab Assistant | Moderator</title>

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
            <a href="#" class="d-block"><?php if(isset($_SESSION['USER_NAME'])) { echo $_SESSION['USER_NAME']; } ?></a>
            <a href="#" class="d-block"><?php if(isset($_SESSION['roletitle'])) { echo $_SESSION['roletitle']; } ?></a>
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
          <li class="nav-item menu-open">
            <a href="User.php" class="nav-link active">
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
            <h1>Add New Lab Assistant</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item active">Add New Lab Assistant</li>
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
              <form name="form" method="POST" action="addNewUser.php">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputName">User ID</label>
                    <input name="id" type="name" class="form-control" id="inputUID" placeholder="Enter User ID" required>
                  </div>
                  <div class="form-group">
                    <label for="inputRole">Role</label><br>
                    <select name = "roleid" id="inputid" class="form-control" placeholder="Enter Role" required><br>
                    <option value = "">SELECT ONE</option>
                      <option value = "LBAST">LAB ASSISTANT 
                    </select>
                  </div><br>
                  <div class="form-group">
                    <label for="inputLab">Laboratory Incharged</label><br>
                    <select name="laboratoryid" id="inputid" class="form-control" placeholder="Enter Laboratory Incharged" required><br>
                      <option value="" >SELECT LABORATORY INCHARGED</option>
                        <option value="101" >MAKMAL HIDROLOGI & ALAM SEKITAR
                        <option value="102" >MAKMAL LUKISAN TEKNIK 1
                        <option value="103" >MAKMAL HIDROLOGI
                        <option value="104" >MAKMAL MEKANIK BATUAN
                        <option value="105" >MAKMAL MEKANIK TANAH
                        <option value="106" >MAKMAL PENGURUSAN BANGUNAN
                        <option value="107" >MAKMAL REKABENTUK KOMPUTER 1
                        <option value="108" >MAKMAL REKABENTUK KOMPUTER 2
                        <option value="109" >MAKMAL REKABENTUK KOMPUTER 3
                        <option value="110" >MAKMAL KEKUATAN BAHAN
                        <option value="111" >MAKMAL JALANRAYA
                        <option value="112" >MAKMAL KONKRIT
                        <option value="113" >MAKMAL KAJIUKUR
                        <option value="114" >BILIK UTILITI
                        <option value="115" >MAKMAL HIDRAUL
                        <option value="116" >STOR USMB BLOK J
                        <option value="117" >MAKMAL STRUKTUR RINGAN 
                        <option value="118" >MAKMAL STRUKTUR BERAT
                        <option value="119" >MAKMAL GEOLOGI
                    </select>
                  </div><br>
                  <div class="form-group">
                    <label for="inputRole">Access Status</label><br>
                      <select name = "astatusid" id="inputStatus" placeholder="Access Status" class="form-control" required>
                      <option value = "">SELECT ONE</option>
                          <option value="A">ACTIVE
                          <option value="I">INACTIVE
                    </select>
                  </div><br>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="location.href='User.php'">Back</button>
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
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>