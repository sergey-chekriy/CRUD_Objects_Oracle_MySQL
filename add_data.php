<?php
require_once("inc/initialize.php");
if(isset($_POST['btn-save']))
{
 // variables for input data
 $first_name = $_POST['first_name'];
 $last_name = $_POST['last_name'];
 $contact_email = $_POST['contact_email'];
 // variables for input data
 $user = User::make($first_name, $last_name, $contact_email);

 if($user->save())
 {
  ?>
  <script type="text/javascript">
  alert('Data Are Inserted Successfully ');
  window.location.href='index.php';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('error occured while inserting your data');
  </script>
  <?php
 }
 // sql query execution function
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<title>CRUD Operations Oracle and OracleCRUDObject</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
<center>

<div id="header">
 <div id="content">
    <label>Universal CRUD Operations (Oracle or MySQL)</label>
    </div>
</div>
<div id="body">
 <div id="content">
    <form method="post">
    <table align="center">
    <tr>
    <td align="center"><a class="btn" href="index.php">back to main page</a></td>
    </tr>
    <tr>
    <td><input type="text" name="first_name" placeholder="First Name" required /></td>
    </tr>
    <tr>
    <td><input type="text" name="last_name" placeholder="Last Name" required /></td>
    </tr>
    <tr>
    <td><input type="email" name="contact_email" placeholder="e-mail" required /></td>
    </tr>
    <tr>
    <td><button  type="submit" name="btn-save">Save</button></td>
    </tr>
    </table>
    </form>
    </div>
</div>

</center>
</body>
</html>