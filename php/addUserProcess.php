<?php
/* include db connection file */
include("../php/connection.php");

if(isset($_POST['submit'])){

$staffid = $_POST['srid'];
$userid = $_POST['id'];
$role = $_POST['roleid'];
$accessstatus = $_POST['astatusid'];

$sql = "INSERT INTO staffroles (srid, id, roleid, astatusid)
VALUES ('" . $staffid . "', '" . $userid . "', '" . $role . "', '" . $accessstatus . "')";
echo $sql;
$query = mysqli_query($conn,$sql) or die(mysqli_error());
// exit();
}
?>