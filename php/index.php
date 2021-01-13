<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<!-- Add JQuery -->
<script><?php require("../JS/jquery.min.js");?></script>
<script><?php require("../JS/scripts.js");?></script>

<head>
	<title>Discussion Board</title>
    <style><?php require("../CSS/phpstyle.css");?></style>
</head>
<body>
  <div class="header">
    <!-- notification message, logged in user information -->
    <?php  if (isset($_SESSION['success']) || isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong>! <?php echo $_SESSION['success']; ?>. <a href="logout.php?logout='1'" style="color: red;"> Logout</a></p>
    <?php endif ?>
  </div>

  <div class="wrapper">
  	<?php echo $comments; ?>
  	<form class="comment_form">
      <div>
        <label for="name">Title:</label>
      	<input type="text" name="name" id="name">
      </div>
      <div>
      	<label for="comment">Comment:</label>
      	<textarea name="comment" id="comment" cols="30" rows="5"></textarea>
      </div>
      <button type="button" id="submit_btn">POST</button>
      <button type="button" id="update_btn" style="display: none;">UPDATE</button>
  	</form>
  </div>
</body>
</html>