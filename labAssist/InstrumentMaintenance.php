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
$allowedroles = array('LBAST');
if(!in_array($_SESSION['roleid'], $allowedroles))
{
    header("Location: ../php/logout.php");
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Instrument Maintenance | Lab Assist</title>

  <!-- To add favicon -->
	<link rel="icon" href="../icon/favicon.ico">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
            <h1>Instrument Maintenance</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Instrument Maintenance</li>
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
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>	
                    <th>Maintainance ID</th>
                    <th>Maintainance Type</th>
                    <th>Status</th>
                    <th>Send Out Date</th>
                    <th>Return Date</th>
                    <th>Calibaration Date</th>
                    <th>Next Calibration Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    include("../php/connection.php");
                    $sql = "SELECT *
                    FROM maintenances m 
                    JOIN maintenancetypes mt ON m.typeid = mt.typeid
                    JOIN maintenancestatus ms ON m.mstatusid = ms.mstatusid";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_num_rows($result);

                    $sql2 = "SELECT i.USER_ID , i.laboratoryid, it.instrumentid, m.maintenanceid FROM incharges i 
                    JOIN laboratories l ON i.laboratoryid = l.laboratoryid
                    JOIN instruments it ON l.laboratoryid = it.laboratoryid
                    JOIN maintenances m ON it.instrumentid = m.instrumentid
                    WHERE i.USER_ID = '" . $_SESSION['USER_ID'] . "'";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_num_rows($result2);

                 

                    if($row > 0 || $row2 > 0)
                    {
                      $arr_labs = array();
                      $x = 0;
                      while ($labassistant = mysqli_fetch_assoc($result2)) {
                        $arr_labs[$x] = $labassistant['instrumentid'];
                        $x++;
                      }
                        while ($maintenance = mysqli_fetch_assoc($result))
                        {
                  ?>
                  <tr>
                    <td><?php echo $maintenance['maintenanceid']; ?></td>
                    <td><?php echo $maintenance['typetitle']; ?></td>
                    <td><?php echo $maintenance['mstatustitle']; ?></td>
                    <td><?php echo $maintenance['sendoutdate']; ?></td>
                    <td><?php echo $maintenance['returndate']; ?></td>
                    <td><?php echo $maintenance['calibrationdate']; ?></td>
                    <td><?php echo $maintenance['nextcalibrationdate']; ?></td>
                    <?php if (in_array($maintenance['instrumentid'], $arr_labs)) : ?>
                      <td>
                        <a href="viewInstrumentMaintenance.php?token=<?php echo $maintenance['token']; ?>" class="btn btn-primary btnn-block btn-sm float-middle fas fa-eye"></a>
                        <a href="editInstrumentMaintenance.php?token=<?php echo $maintenance['token']; ?>" class="btn btnn-block btn-primary btn-sm float-middle fas fa-edit"></a>
                      </td>
                      </tr>
                      <?php else : ?>
                        <td>
                        <a href="viewInstrumentMaintenance.php?token=<?php echo $maintenance['token']; ?>" class="btn btn-primary btnn-block btn-sm float-middle fas fa-eye"></a>
                      </td>
                      </tr>
                      <?php endif; ?>
                  <?php
                         
                        } 
                    }
                  ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Maintainance ID</th>
                    <th>Maintainance Type</th>
                    <th>Status</th>
                    <th>Send Out Date</th>
                    <th>Return Date</th>
                    <th>Calibaration Date</th>
                    <th>Next Calibration Date</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
                <div class="col-sm-6">
                  <button type="button" class="btn btnn-block btn-primary btn-sm float-right" onclick="location.href='addNewInstrumentMaintenance.php'">Add New Record</button>
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

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
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.php5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
