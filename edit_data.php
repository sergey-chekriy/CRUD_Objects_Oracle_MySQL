<?php
require_once("inc/initialize.php");
if(isset($_GET['edit_id']))
{
 $user = User::read_by_id($_GET['edit_id']);  
}
if(isset($_POST['btn-update']))
{
 // variables for input data
 $user = User::read_by_id($_GET['edit_id']);    
 $user->first_name = $_POST['first_name'];
 $user->last_name = $_POST['last_name'];
 $user->contact_email = $_POST['contact_email'];
 
 //update user record in database
 if($user->update())
 {
  ?>
  <script type="text/javascript">
  alert('Data Are Updated Successfully');
  window.location.href='index.php';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('error occured while updating data');
  </script>
  <?php
 }
 
}
if(isset($_POST['btn-cancel']))
{
    redirect_to("index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<title>Universal CRUD Operations (Oracle or MySQL)</title>
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
    <td><input type="text" name="first_name" placeholder="First Name" value="<?php echo $user->first_name; ?>" required /></td>
    </tr>
    <tr>
    <td><input type="text" name="last_name" placeholder="Last Name" value="<?php echo $user->last_name; ?>" required /></td>
    </tr>
    <tr>
    <td><input type="email" name="contact_email" placeholder="Contact e-mail" value="<?php echo $user->contact_email; ?>" required /></td>
    </tr>
    <tr>
    <td>
    <button type="submit" name="btn-update">Update</button>
    <button type="submit" name="btn-cancel">Cancel</button>
    </td>
    </tr>
    </table>
    </form>
    </div>
</div>

</center>
</body>
</html>