<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$sqlconvert = "SELECT * FROM companies WHERE token = '".$_GET['token']."'";
$resultconvert = mysqli_query($conn,$sqlconvert);
$getID = mysqli_fetch_assoc($resultconvert);
$companyid =$getID['companyid'];
if(isset($_POST['save'])){
  $companyid = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $compadd1 = mysqli_real_escape_string($conn, $_POST['address1']);
  $compadd2 = mysqli_real_escape_string($conn,$_POST['address2']);
  $compadd3 = mysqli_real_escape_string($conn, $_POST['address3']);
  $compPCode = mysqli_real_escape_string($conn, $_POST['postcode']);
  $compTown = mysqli_real_escape_string($conn, $_POST['town']);
  $state = mysqli_real_escape_string($conn,$_POST['state']);
  $compTel = mysqli_real_escape_string($conn, $_POST['tel']);
  $compFax = mysqli_real_escape_string($conn,$_POST['fax']);

  $sql = "UPDATE companies SET 
          companyname='$name',
          companyaddress1='$compadd1',
          companyaddress2='$compadd2',
          companyaddress3='$compadd3',
          companypostcode='$compPCode',
          companytown='$compTown',
          stateid='$state',
          companytel='$compTel',
          companyfax='$compFax'
          WHERE companyid=$companyid";
  $result = mysqli_query($conn,$sql);
  if($result){
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "Company Edited Successfully";
    header('location:Companies.php');
    exit(0);
  }else{
    $_SESSION['message'] = "Company Failed to Edit";
    header('location:User.php');
    exit(0);
  }
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Company | Moderator</title>

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
            <a href="User.php" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <li class="nav-item">
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
            <h1>Edit Company</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item active">Edit Company</li>
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
              <?php
              if(isset($_GET['token']))
              {
                $companyid = mysqli_real_escape_string($conn, $_GET['token']);
                $sql = "SELECT * FROM companies c
                JOIN states s ON c.stateid = s.stateid
                WHERE c.token='$companyid'";
                $result = mysqli_query($conn,$sql); 
                $row = mysqli_num_rows($result);

                if($row > 0){
                  $company = mysqli_fetch_assoc($result);
                  ?>
                <form name="form" action="editCompany.php" method="POST">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputID">Company ID</label>
                    <input name="id" type="id" class="form-control" id="inputID" value="<?= $company['companyid']; ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="inputName">Company Name</label>
                    <input name="name" type="name" class="form-control" id="inputName" value="<?= $company['companyname'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="inputAddress">Company Address 1</label>
                    <input name="address1" type="address" class="form-control" id="inputAddress1" value="<?= $company['companyaddress1'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="inputAddress">Company Address 2</label>
                    <input name="address2" type="address" class="form-control" id="inputAddress2" value="<?= $company['companyaddress2'];?>"required>
                  </div>
                  <div class="form-group">
                    <label for="inputAddress">Company Address 3</label>
                    <input name="address3" type="address" class="form-control" id="inputAddress3" value="<?= $company['companyaddress3'];?>"required>
                  </div>
                  <div class="form-group">
                    <label for="inputPostcode">Company Postcode</label>
                    <input name="postcode" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                      type = "number" minlength="4" maxlength = "5"class="form-control" id="inputPostcode" value="<?= $company['companypostcode'];?>"required>
                  </div>
                  <div class="form-group">
                    <label for="inputTown">Company Town</label>
                    <input name="town" type="town" class="form-control" id="inputTown" value="<?= $company['companytown'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="inputRole">Company State</label><br>
                    <select name = "state" id="inputState" value="<?= $company['statetitle'];?>" class="form-control" required>
                    <option value = "">SELECT STATE</option>
                    <?php 
                      $sqlState = "SELECT * FROM states";
                      $qryState = mysqli_query($conn, $sqlState);
                      $rowState = mysqli_num_rows($qryState);
                      if($rowState > 0)
                      {
                          while($dState = mysqli_fetch_assoc($qryState))
                          {  
                            if($company['stateid'] == $dState['stateid'])
                              echo "<option value='".$dState['stateid']."' selected>".$dState['statetitle']."</option>";
                            else
                              echo "<option value='".$dState['stateid']."'>".$dState['statetitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div><br>
                  <div class="form-group">
                    <label for="inputTel">Company Tel</label>
                    <input name="tel" type="tel" class="form-control" id="inputTel" minlength="10" maxlength="14" value="<?= $company['companytel'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="inputFax">Company Fax</label>
                    <input name="fax" type="tel" class="form-control" id="inputFax" minlength="6" maxlength="10" value="<?= $company['companyfax'];?>">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="button" class="btn btn-primary" onclick="location.href='Companies.php'">Back</button>
                  <button type="submit" class="btn btn-primary" name="save">Save</button>
                </div>
              </form>
              <?php
                }
              }
              ?>
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>