<?php
/* include db connection file */
include("../php/connection.php");

if(isset($_POST['submit'])){

$id = $_POST['id'];
$name = $_POST['name'];
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$town = $_POST['town'];
$tel = $_POST['tel'];
$fax = $_POST['fax'];

$sql = "INSERT INTO companies (companyid, companyname ,companyaddress1, companypostcode, companytown, companytel, companyfax)
VALUES ('" . $id . "', '" . $name . "', '" . $address . "', '" . $postcode . "','" . $town . "', " . $tel . ", '" . $fax . "')";
echo $sql;
$query = mysqli_query($conn,$sql) or die(mysqli_error());
exit();
}
?>