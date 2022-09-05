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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/* include db connection file */
include("../php/connection.php");
$sqlconvert = "SELECT * FROM staffroles WHERE token = '".$_GET['token']."'";
$resultconvert = mysqli_query($conn,$sqlconvert);
$getID = mysqli_fetch_assoc($resultconvert);
$staffid =$getID['USER_ID'];
if(isset($_POST['save'])){
  $staffid = mysqli_real_escape_string($conn, $_POST['userid']);
  $role = mysqli_real_escape_string($conn, $_POST['roles']);
  $accessstatus = mysqli_real_escape_string($conn,$_POST['status']);
  $labid = mysqli_real_escape_string($conn, $_POST['laboratoryid']);

  if($staffid != null && $role != null && $accessstatus != null)
  {
    $sql = "UPDATE staffroles SET roleid='$role', astatusid='$accessstatus'
          WHERE USER_ID = '".$staffid."'";
    $result = mysqli_query($conn,$sql);
    if($role != 'LBAST')
    {
      $sql3 = "DELETE FROM incharges WHERE USER_ID = '".$staffid."'" ;
      $result3 = mysqli_query($conn,$sql3);
    }
  }

  if($labid != null)
  {
    //$select = mysqli_query($conn, "SELECT * FROM incharges WHERE USER_ID = '".$staffid."'");
    //if(mysqli_num_rows($select))
    //{
      //$sql2 = "UPDATE incharges SET laboratoryid='$labid' WHERE USER_ID = '".$staffid."'";
      //$result2 = mysqli_query($conn,$sql2);
    //}
    //else
      $sqlchecklab = "SELECT *
      FROM incharges i 
      JOIN laboratories l ON i.laboratoryid = l.laboratoryid
      WHERE i.USER_ID = '" . $staffid . "'";
      $resultcheck = mysqli_query($conn,$sqlchecklab);
      $labcheck = mysqli_fetch_assoc($resultcheck);
      if($labid != $labcheck['laboratoryid'])
      {
        $sql2 = "INSERT INTO incharges (inchargeid, USER_ID, laboratoryid)
        VALUES ('', '" . $staffid . "', '" . $labid . "')";
        $result2 = mysqli_query($conn,$sql2);
      }
  }

  if($result || $result2){
    //echo "Data Inserted Successfully";
    $_SESSION['message'] = "Lab Assistant Edited Successfully";
    header('location:User.php');
    exit(0);
  }else{
    $_SESSION['message'] = "Lab Assistant Failed to Edit";
    header('location:User.php');
    exit(0);
  }
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Lab Assistant | Moderator</title>

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
            <h1>Edit Lab Assistant</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="User.php">List of User</a></li>
              <li class="breadcrumb-item active">Edit Lab Assistant</li>
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
                $sql = "SELECT * FROM staffroles sr
                JOIN user u ON sr.USER_ID = u.USER_ID
                JOIN roles r ON sr.roleid = r.roleid
                JOIN accesstatus ac ON sr.astatusid = ac.astatusid
                WHERE sr.token='$token'";
                $result = mysqli_query($conn,$sql); 
                $row = mysqli_num_rows($result);

                $sql2 = "SELECT * FROM incharges i
                 JOIN user u ON i.USER_ID = u.USER_ID
                JOIN staffroles sr ON u.USER_ID = sr.USER_ID
                JOIN laboratories l ON i.laboratoryid = l.laboratoryid
                WHERE sr.token='$token'";
                $result2 = mysqli_query($conn,$sql2); 
                $row2 = mysqli_num_rows($result2);

                if($row > 0 || $row2 > 0){
                  $user = mysqli_fetch_assoc($result);
                  $user2 = mysqli_fetch_assoc($result2);
                  ?>
                <form name="form" method="POST" action="editUser.php">
                <div class="card-body">
                  <div class="form-group">
                    <label for="inputID">User ID</label>
                    <input name="userid" type="text" class="form-control" id="inputID"  value="<?= $user['USER_ID'];?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="inputName">Name</label>
                    <input name="name" type="text" class="form-control" id="inputName" value="<?= $user['USER_NAME'];?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="inputRole">Role</label><br>
                    <select name = "roles" id="inputRoles" value="<?= $user['roletitle'];?>" class="form-control" required>
                    <option value = "">SELECT ONE</option>
                    <?php 
                      $sqlUser = "SELECT * FROM roles";
                      $qryUser = mysqli_query($conn, $sqlUser);
                      $rowUser = mysqli_num_rows($qryUser);
                      if($rowUser > 0)
                      {
                          while($dUser = mysqli_fetch_assoc($qryUser))
                          {  
                            if($user['roleid'] == $dUser['roleid'])
                              echo "<option value='".$dUser['roleid']."' selected>".$dUser['roletitle']."</option>";
                            else
                              echo "<option value='".$dUser['roleid']."'>".$dUser['roletitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div>
                  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                  <script type="text/javascript">
                    $(function () {
                        $("#inputRoles").change(function () {
                            if ($(this).val() == "LBAST") {
                                $("#inputlabid").removeAttr("disabled");
                                $("#inputlabid").focus();
                            } else {
                                $("#inputlabid").attr("disabled", "disabled");
                            }
                        });
                    });
                  </script>
                  <div class="form-group">
                    <label for="inputlabid">Laboratory Incharged</label><br>
                    <select name = "laboratoryid" id="inputlabid" value="<?= $user2['laboratorytitle'];?>" class="form-control" disabled="disabled" required><br>
                    <option value = "">SELECT ONE</option>
                      <?php 
                        $sqlLab = "SELECT * FROM laboratories";
                        $qryLab = mysqli_query($conn, $sqlLab);
                        $rowLab = mysqli_num_rows($qryLab);
                        if($rowLab > 0)
                        {
                            while($dLab = mysqli_fetch_assoc($qryLab))
                            {  
                              if($user2['laboratoryid'] == $dLab['laboratoryid'])
                                echo "<option value='".$dLab['laboratoryid']."' selected>".$dLab['laboratorytitle']."</option>";
                              else
                                echo "<option value='".$dLab['laboratoryid']."'>".$dLab['laboratorytitle']."</option>";
                            }
                          }
                    ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="inputRole">Access Status</label><br>
                    <select name = "status" id="inputStatus" placeholder="Access Status" class="form-control" required>
                    <option value = "">SELECT ONE</option>
                    <?php 
                      $sqlStatus = "SELECT * FROM accesstatus";
                      $qryStatus = mysqli_query($conn, $sqlStatus);
                      $rowStatus = mysqli_num_rows($qryStatus);
                      if($rowStatus > 0)
                      {
                          while($dStatus = mysqli_fetch_assoc($qryStatus))
                          {  
                            if($user['astatusid'] == $dStatus['astatusid'])
                              echo "<option value='".$dStatus['astatusid']."' selected>".$dStatus['astatustitle']."</option>";
                            else
                              echo "<option value='".$dStatus['astatusid']."'>".$dStatus['astatustitle']."</option>";
                          }
                        }
                    ?>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="location.href='User.php'">Back</button>
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