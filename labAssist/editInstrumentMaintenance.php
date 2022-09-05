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
$sqlconvert = "SELECT * FROM maintenances WHERE token = '".$_GET['token']."'";
$resultconvert = mysqli_query($conn,$sqlconvert);
$getID = mysqli_fetch_assoc($resultconvert);
$maintenanceid =$getID['maintenanceid'];
if(isset($_POST['save']) || isset($_FILES['image'])){
  //echo "Hello";
  echo "<pre>";
  print_r($_FILES['image']);
  echo "</pre>";
  $maintenanceid = mysqli_real_escape_string($conn, $_POST['id']);
  $sendoutdate = mysqli_real_escape_string($conn, $_POST['sod']);
  $returndate = mysqli_real_escape_string($conn, $_POST['rd']);
  $caliDate = mysqli_real_escape_string($conn, $_POST['cd']);
  $nextcalidate = mysqli_real_escape_string($conn, $_POST['ncalidate']);
  $desc = mysqli_real_escape_string($conn, $_POST['mdesc']);
  $instrumentid = mysqli_real_escape_string($conn, $_POST['iid']);
  $companyid = mysqli_real_escape_string($conn, $_POST['cid']);
  $mtype = mysqli_real_escape_string($conn, $_POST['typemid']);
  $mstatus = mysqli_real_escape_string($conn, $_POST['mstatus']);
  $img_name = $_FILES['image']['name'];
  $img_size = $_FILES['image']['size'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $error = $_FILES['image']['error'];

  if($maintenanceid != null && $sendoutdate != null && $returndate != null && $caliDate != null
     && $nextcalidate != null  && $instrumentid != null && $mstatus != null 
     && $mtype != null){
    $sql = "UPDATE maintenances SET maintenanceid='$maintenanceid',sendoutdate='$sendoutdate',returndate='$returndate',calibrationdate='$caliDate',nextcalibrationdate='$nextcalidate',`description`='$desc',instrumentid='$instrumentid',
      mstatusid='$mstatus',companyid='$companyid',typeid='$mtype'
      WHERE maintenanceid = '$maintenanceid'";
    $result = mysqli_query($conn,$sql);
  }
  if($error === 0){
    if($img_size > 125000){
      //$em = "Sorry your file is too large";
      header('location: editInstrumentMaintenance.php?error=Sorry your file is too large');
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
        $sql = "UPDATE `evidences` SET `evidencefilename`='$new_img_name'
                WHERE `evidences`.`maintenanceid` = $maintenanceid";
        //$sql = "INSERT INTO `images`(`imageid`, `imagefilename`, `token`, `instrumentid`)
                //VALUES ('','" . $new_img_name . "', '" . $token2 . "', '" . $instrumentid . "')";
        $result2 = mysqli_query($conn, $sql);
        header('location: editInstrumentMaintenance.php');
      }else {
        //$em = "You can't upload files of this type";
        header('location: editInstrumentMaintenance.php?error=You cant upload files of this type');
      }
    }
  } else {
    //$em = "unknown error occured!";
    header('location: editInstrumentMaintenance.php?error=unknown error occured!');
  }
  if ($result || $result2) {
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "Instrument Maintenance Record Updated Successfully";
    header('location:InstrumentMaintenance.php');
    exit(0);
  } else {
    $_SESSION['message'] = "Instrument Maintenance Record Failed to Update";
    header('location:editInstrumentMaintenance.php');
    exit(0);

  }
  // exit();
}
/*session_start();
 //include db connection file
include("../php/connection.php");
$maintenanceid =$_GET['editID'];
if(isset($_POST['save']) && isset($_FILES['image'])){
  //echo "Hello";
  echo "<pre>";
  print_r($_FILES['image']);
  echo "</pre>";
  $maintenanceid = mysqli_real_escape_string($conn, $_POST['id']);
  $sendoutdate = mysqli_real_escape_string($conn, $_POST['sod']);
  $returndate = mysqli_real_escape_string($conn, $_POST['rd']);
  $caliDate = mysqli_real_escape_string($conn, $_POST['cd']);
  $nextcalidate = mysqli_real_escape_string($conn, $_POST['ncalidate']);
  $desc = mysqli_real_escape_string($conn, $_POST['mdesc']);
  $instrumentid = mysqli_real_escape_string($conn, $_POST['iid']);
  $companyid = mysqli_real_escape_string($conn, $_POST['cid']);
  $mtype = mysqli_real_escape_string($conn, $_POST['typemid']);
  $mstatus = mysqli_real_escape_string($conn, $_POST['mstatus']);
  $img_name = $_FILES['image']['name'];
  $img_size = $_FILES['image']['size'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $error = $_FILES['image']['error'];

  $sql = "UPDATE maintenances SET typeid='$mtype', sendoutdate='$sendoutdate',returndate='$returndate',
          calibrationdate='$caliDate', nextcalibrationdate='$nextcalidate', `description`='$desc',`instrumentid`='$instrumentid',
          `mstatusid`='$mstatus'
          WHERE maintenanceid=$maintenanceid";
  $result = mysqli_query($conn,$sql);
  if($error === 0){
    if($img_size > 125000){
      //$em = "Sorry your file is too large";
      header('location: editInstrumentMaintenance.php?error=Sorry your file is too large');
    }else{
      //echo "Not more than 1mb";
      $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
      //echo($img_ex);
      $img_ex_lc = strtolower($img_ex);
      
      $allowed_exs = array("pdf");
      
      if(in_array($img_ex_lc, $allowed_exs)){
        $new_img_name = uniqid("EVD-", true).'.'.$img_ex_lc;
        $img_upload_path = '../images/'.$new_img_name;
        move_uploaded_file($tmp_name, $img_upload_path); 
        
        //Insert into database
        $sql = "UPDATE `evidences` SET `evidencefilename`='$new_img_name'
                WHERE `evidences`.`maintenanceid` = $maintenanceid";
        //$sql = "INSERT INTO `images`(`imageid`, `imagefilename`, `token`, `instrumentid`)
                //VALUES ('','" . $new_img_name . "', '" . $token2 . "', '" . $instrumentid . "')";
        $result2 = mysqli_query($conn, $sql);
        header('location: editInstrumentMaintenance.php');
      }else {
        //$em = "You can't upload files of this type";
        header('location: editInstrumentMaintenance.php?error=You cant upload files of this type');
      }
    }
  } else {
    //$em = "unknown error occured!";
    header('location: editInstrumentMaintenance.php?error=unknown error occured!');
  }
  if($result || $result2){
      //echo "Data Inserted Successfully";
      $_SESSION['message'] = "Data Inserted Successfully";
      header('location:InstrumentMaintenance.php');
      exit(0);
      //echo $sql;
  } else {
      $_SESSION['message'] = "Data Failed to Insert";
      header('location:Instrument.php');
      exit(0);
      //die(mysqli_error($conn));
  }
}*/
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Instrument Maintenance | Lab Assist</title>

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
              <a href="Instrument.php" class="nav-link">
                <i class="nav-icon fas fa-compass"></i>
                <p>
                  Instrument
                </p>
              </a>
            </li>
            <li class="nav-item">
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
            <h1>Edit Instrument Maintenance</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="Instrument.php">Instrument</a></li>
              <li class="breadcrumb-item active">Edit Instrument Maintenance</li>
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
                $token = mysqli_real_escape_string($conn, $_GET['token']);
                $sql = "SELECT * 
                FROM maintenances m 
                JOIN instruments i ON m.instrumentid = i.instrumentid 
                JOIN companies c on m.companyid = c.companyid
                JOIN maintenancetypes mt ON m.typeid = mt.typeid
                JOIN maintenancestatus ms ON m.mstatusid = ms.mstatusid
                WHERE m.token='$token'";
                $result = mysqli_query($conn,$sql); 
                $row = mysqli_num_rows($result);

                if($row > 0){
                  $maintenance = mysqli_fetch_assoc($result);
                  ?>
              <form  name="form" method="POST" action="editInstrumentMaintenance.php">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputID">Maintenance ID</label>
                    <input name="id" type="id" class="form-control" id="inputID" value="<?= $maintenance['maintenanceid'];?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="instrumentID">Instrument Name</label>
                    <input name="iid" class="form-control" id="inputIID" value="<?= $maintenance['instrumentname'];?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="CompanyID">Company Name</label>
                    <input name="cid" class="form-control" id="inputCID" value="<?= $maintenance['companyname'];?>" readonly>
                  </div>
                  <div class="form-group">
                      <label for="inputRole">Maintenance Type</label><br>
                      <select name="typemid" id="inputmid" placeholder="Enter Maintenance Type" class="form-control" required>
                    <option value = "">SELECT ONE</option>
                    <?php 
                      $sqlMtnce = "SELECT * FROM maintenancetypes";
                      $qryMtnce = mysqli_query($conn, $sqlMtnce);
                      $rowMtnce = mysqli_num_rows($qryMtnce);
                      if($rowMtnce > 0)
                      {
                          while($dMaintenance = mysqli_fetch_assoc($qryMtnce))
                          {  
                            if($maintenance['typeid'] == $dMaintenance['typeid'])
                              echo "<option value='".$dMaintenance['typeid']."' selected>".$dMaintenance['typetitle']."</option>";
                            else
                              echo "<option value='".$dMaintenance['typeid']."'>".$dMaintenance['typetitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="SOD">Send Out Date</label>
                    <input name="sod" type="date" class="form-control" id="inputSOD" value="<?= $maintenance['sendoutdate'];?>">
                  </div>
                  <div class="form-group">
                    <label for="returndate">Return Date</label>
                    <input name="rd" type="date" class="form-control" id="inputReturnDate" value="<?= $maintenance['returndate'];?>">
                  </div>
                  <div class="form-group">
                    <label for="caliDate">Calibration Date</label>
                    <input name="cd" type="date" class="form-control" id="calidate" value="<?= $maintenance['calibrationdate'];?>">
                  </div>
                  <div class="form-group">
                    <label for="Ncalidate">Next Calibraton Date</label>
                    <input name="ncalidate" type="date" class="form-control" id="ncalidate" value="<?= $maintenance['nextcalibrationdate'];?>">
                  </div>
                  <div class="form-group">
                  <label for="mstatustitle">Maintenance Status</label><br>
                      <select name="mstatus" id="mstatus" placeholder="Enter Maintenance Status" class="form-control" required>
                    <option value = "">SELECT ONE</option>
                    <?php 
                      $sqlStatus = "SELECT * FROM maintenancestatus";
                      $qryStatus = mysqli_query($conn, $sqlStatus);
                      $rowStatus = mysqli_num_rows($qryStatus);
                      if($rowStatus > 0)
                      {
                          while($dStatus = mysqli_fetch_assoc($qryStatus))
                          {  
                            if($maintenance['mstatusid'] == $dStatus['mstatusid'])
                              echo "<option value='".$dStatus['mstatusid']."' selected>".$dStatus['mstatustitle']."</option>";
                            else
                              echo "<option value='".$dStatus['mstatusid']."'>".$dStatus['mstatustitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                    <div class="form-group">
                    <label for="images">Evidence</label><br>
                    <?php
                      //sql to retrieve images
                      $sql2 = "SELECT * FROM evidences WHERE maintenanceid = '".$maintenanceid."'";
                      //echo $sql2;
                      $result2 = mysqli_query($conn, $sql2);
                      $row = mysqli_num_rows($result2);
                      if($row >0) {
                      while($maintenance2 = mysqli_fetch_assoc($result2))
                      {
                    ?>
                      <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile"><?= $maintenance2['evidencefilename'];?></label>
                      </div>
                      <div type="submit" name="save" value="upload" class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                    <?php
                      } } else {
                        ?>
                        <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file (pdf)</label>
                      </div>
                      <div type="submit" name="save" value="upload" class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                      <?php
                      }
                    ?>
                  </div>
                    <div class="form-group">
                    <label for="description">Description</label>
                    <input name="mdesc" class="form-control" id="mdesc" value="<?= $maintenance['description'];?>">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="button" class="btn btn-primary" onclick="location.href='InstrumentMaintenance.php'">Back</button>
                    <button type="submit" class="btn btn-primary" name="save">Save</button>
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