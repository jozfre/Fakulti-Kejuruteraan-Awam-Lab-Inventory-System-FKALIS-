<?php
include("php/connection.php");
session_start();
if (isset($_POST['login'])) {
  $user = mysqli_real_escape_string($conn, $_POST['USER_ID']);
  $password = mysqli_real_escape_string($conn, $_POST['USER_PASSWORD']);
  // i-staff portal api
  /*$curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt_array($curl, array(
    CURLOPT_PORT => "444",
    CURLOPT_URL => "https://integrasi.uitm.edu.my:444/stars/login/json/" . $user,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"password\": \"" . $password . "\"\n}",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "postman-token: a5f640ca-aedf-6572-f4ef-b6ae06cad9eb",
      "token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiY2xhc3Nib29raW5nIn0._dTe9KRNSHSBMybfC4Gs6Brv6vO2HxQ8CWp9lOtI0hk"
    ),
  ));*/

  //$response = curl_exec($curl);
  //$err = curl_error($curl);

  //curl_close($curl);

  //$json = json_decode($response, TRUE);
  //var_dump($json);
  //if ($json['status'] == "true") {
    $sql3 = "SELECT * FROM staffroles sr 
            JOIN user u ON sr.USER_ID = u.USER_ID 
            JOIN roles r ON sr.roleid = r.roleid
            WHERE u.USER_ID='" . $user . "' AND sr.astatusid='A' ";
    //echo $sql3;
    $qry3 = mysqli_query($conn, $sql3);
    $row3 = mysqli_num_rows($qry3);
    if ($row3 > 0) {
      $re2 = mysqli_fetch_assoc($qry3);
      session_start();
      $_SESSION['USER_ID'] = $user;
      $_SESSION['USER_NAME'] = $re2['USER_NAME'];
      $_SESSION['roleid'] = $re2['roleid'];
      $_SESSION['roletitle'] = $re2['roletitle'];
      $_SESSION['userlogged'] = 1;

      if ($_SESSION['roletitle'] == 'DEVELOPER') {
        header('Location: developer/index.php');
      } else if ($_SESSION['roletitle'] == 'SYSTEM ADMINISTRATOR') {
        header('Location: admin/index.php');
      } else if ($_SESSION['roletitle'] == 'MODERATOR') {
        header('Location: moderator/index.php');
      } else if ($_SESSION['roletitle'] == 'LAB ASSISTANT') {
        header('Location: labAssist/index.php');
      } else {
        echo "Your not logged in";
      }
    }
  }

  /* else
  {
n0r4sH1k1n}>0390    
  } */

?>
<html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="dist/css/style.css">
  <!-- To add favicon -->
	<link rel="icon" href="icon/favicon.ico">
  <title>FKA LAB INVENTORY SYSTEM</title>
</head>

<body>
  <div class="login-wrapper">
    <form action="index.php" class="form" method="post">
      <img src="dist/img/uitmlogo.png" alt="UiTM Logo">
      <h1>FKA LAB INVENTORY SYSTEM</h1>
      <h2>LOG IN</h2>
      <div class="input-group">
        <input type="text" name="USER_ID" id="USER_ID" required>
        <label for="USER_ID">Number ID</label>
      </div>
      <div class="input-group">
        <input type="password" name="USER_PASSWORD" id="USER_PASSWORD" required>
        <label for="USER_PASSWORD">Password</label>
      </div>
      <input type="submit" name="login" value="Login" class="submit-btn">
    </form>
  </div>
</body>

</html>