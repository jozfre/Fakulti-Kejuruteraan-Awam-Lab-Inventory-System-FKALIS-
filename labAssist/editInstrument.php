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
$allowedroles = array('LBAST');
if(!in_array($_SESSION['roleid'], $allowedroles))
{
    header("Location: ../php/logout.php");
}
//include db connection file
include("../php/connection.php");
$sqlconvert = "SELECT * FROM instruments WHERE token = '".$_GET['token']."'";
$resultconvert = mysqli_query($conn,$sqlconvert);
$getID = mysqli_fetch_assoc($resultconvert);
$instrumentid =$getID['instrumentid'];
if(isset($_POST['save']) || isset($_FILES['image'])){
  //echo "Hello";
  echo "<pre>";
  print_r($_FILES['image']);
  echo "</pre>";
  $instrumentid = mysqli_real_escape_string($conn, $_POST['id']);
  $instrumanename = mysqli_real_escape_string($conn, $_POST['iname']);
  $labid = mysqli_real_escape_string($conn, $_POST['laboratoryid']);
  $istatus = mysqli_real_escape_string($conn, $_POST['istatusid']);
  $img_name = $_FILES['image']['name'];
  $img_size = $_FILES['image']['size'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $error = $_FILES['image']['error'];

  if($instrumentid != null && $instrumanename != null && $labid != null && $istatus != null){
    $sql = "UPDATE instruments SET instrumentid = '$instrumentid', instrumentname = '$instrumanename', laboratoryid = '$labid', istatusid = '$istatus'
    WHERE instrumentid = '$instrumentid'";
    $result = mysqli_query($conn,$sql);
  }
  if($error === 0){
    if($img_size > 125000){
      //$em = "Sorry your file is too large";
      header('location: editInstrument.php?error=Sorry your file is too large');
    }else{
      //echo "Not more than 1mb";
      $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
      //echo($img_ex);
      $img_ex_lc = strtolower($img_ex);
      
      $allowed_exs = array("jpg", "jpeg", "png");
      
      if(in_array($img_ex_lc, $allowed_exs)){
        $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
        $img_upload_path = '../images/'.$new_img_name;
        move_uploaded_file($tmp_name, $img_upload_path); 
        
        //Insert into database
        $sql2 = "UPDATE images SET imagefilename='$new_img_name'
                WHERE instrumentid = '$instrumentid'";
        //$sql = "INSERT INTO `images`(`imageid`, `imagefilename`, `token`, `instrumentid`)
                //VALUES ('','" . $new_img_name . "', '" . $token2 . "', '" . $instrumentid . "')";
        $result2 = mysqli_query($conn, $sql2);
        header('location: editInstrument.php');
      }else {
        //$em = "You can't upload files of this type";
        header('location: editInstrument.php?error=You cant upload files of this type');
      }
    }
  } else {
    //$em = "unknown error occured!";
    header('location: editInstrument.php?error=unknown error occured!');
  }
  if ($result || $result2) {
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "Instrument Information Updated Successfully";
    header('location:Instrument.php');
    exit(0);
  } else {
    $_SESSION['message'] = "Instrument Information Failed to Update";
    header('location:editInstrument.php');
    exit(0);

  }
  // exit();
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Instrument | Lab Assist</title>
  
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
            <li class="nav-item">
              <a href="Instrument.php" class="nav-link active">
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
            <h1>Edit Instrument</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="Instrument.php">Instrument</a></li>
              <li class="breadcrumb-item active">Edit Instrument</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <?php include('../php/message.php');?>
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
                $token = mysqli_real_escape_string($conn, $_GET['token']);
                $sql = "SELECT * FROM instruments i
                JOIN laboratories l ON i.laboratoryid = l.laboratoryid
                JOIN instrumentstatus it ON i.istatusid = it.istatusid
                WHERE i.token='$token'";
                $result = mysqli_query($conn,$sql); 
                $row = mysqli_num_rows($result);

                if($row > 0){
                  $instrument = mysqli_fetch_assoc($result);
                  ?>
              <form name="form" method="POST" action="editInstrument.php">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputID">Instrument ID</label>
                    <input name="id" type="text" class="form-control" id="inputID" value="<?= $instrument['instrumentid'];?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="ID">Instrument Name</label>
                    <input name="iname" type="text" class="form-control" id="iname" value="<?= $instrument['instrumentname'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="inputLab">Laboratory</label><br>
                    <select name="laboratoryid" id="inputlabid" class="form-control" placeholder="Choose Laboratory" required><br>
                      <option value="" >SELECT LABORATORY</option>
                      <?php 
                      $sqlLab = "SELECT * FROM laboratories";
                      $qryLab = mysqli_query($conn, $sqlLab);
                      $rowLab = mysqli_num_rows($qryLab);
                      if($rowLab > 0)
                      {
                          while($dLab = mysqli_fetch_assoc($qryLab))
                          {  
                            if($instrument['laboratoryid'] == $dLab['laboratoryid'])
                              echo "<option value='".$dLab['laboratoryid']."' selected>".$dLab['laboratorytitle']."</option>";
                            else
                              echo "<option value='".$dLab['laboratoryid']."'>".$dLab['laboratorytitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="images">Image</label><br>
                    <?php
                      //sql to retrieve images
                      $sql2 = "SELECT * FROM images WHERE instrumentid = '".$instrumentid."'";
                      //echo $sql2;
                      $result2 = mysqli_query($conn, $sql2);
                      $row = mysqli_num_rows($result2);
                      if($row >0) {
                      while($instrument2 = mysqli_fetch_assoc($result2))
                      {
                    ?>
                      <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile"><?= $instrument2['imagefilename'];?></label>
                      </div>
                      <div type="submit" name="save" value="upload" class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                    <?php
                      } } else {
                        ?>
                        <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file (jpg, jpeg, png)</label>
                      </div>
                      <div type="submit" name="save" value="upload" class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                      <?php
                      }
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="inputRole">Access Status</label><br>
                    <select name = "istatusid" id="inputStatus" placeholder="Access Status" class="form-control" required>
                    <option value = "">SELECT ONE</option>
                    <?php 
                      $sqlStatus = "SELECT * FROM instrumentstatus";
                      $qryStatus = mysqli_query($conn, $sqlStatus);
                      $rowStatus = mysqli_num_rows($qryStatus);
                      if($rowStatus > 0)
                      {
                          while($dStatus = mysqli_fetch_assoc($qryStatus))
                          {  
                            if($instrument['istatusid'] == $dStatus['istatusid'])
                              echo "<option value='".$dStatus['istatusid']."' selected>".$dStatus['istatustitle']."</option>";
                            else
                              echo "<option value='".$dStatus['istatusid']."'>".$dStatus['istatustitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="location.href='Instrument.php'">Back</button>
                  <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
              </form>
              <?php
                }
              else{
                echo"<h4>No Such ID Found</h4>";
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