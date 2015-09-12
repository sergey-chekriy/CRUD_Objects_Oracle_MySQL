<?php 
    //load all needed database classes and make all work to open connection to db
    require_once("inc/initialize.php");
?>
<?php

// process delete user record button
if(isset($_GET['delete_id']))
{
  $user = User::read_by_id($_GET['delete_id']);
  $user->delete();
  redirect_to("index.php");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CRUD Operations Oracle and OracleCRUDObject</title>
        <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript">
function delete_id(id)
{
 if(confirm('Are you sure to delete?'))
 {
  window.location.href='index.php?delete_id='+id;
 }
}
</script>
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
    <table align="center">
    <tr>
    <th colspan="5"><a class="btn" href="add_data.php"><i class="fa fa-plus fa-fw"></i>&nbsp;add record</a></th>
    </tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Contact e-mail</th>
    <th colspan="2">Operations</th>
    </tr>
<?php
 //read all users from db into array and list them in table
 $users_arr = User::read_all();
 foreach($users_arr as $user)
 {
  ?>
       <tr>
        <td><?php echo $user->first_name; ?></td>
        <td><?php echo $user->last_name; ?></td>
        <td><?php echo $user->contact_email; ?></td>
        <td align="center"><a href="edit_data.php?edit_id=<?php echo $user->user_id; ?>"><i class="fa fa-pencil"></i></a></td>
        <td align="center"><a href="javascript:delete_id('<?php echo $user->user_id; ?>')"><i class="fa fa-trash"></i></a></td>
       </tr>
<?php
 }
?>
    </table>
    </div>
</div>

</center>
</body>
</html>