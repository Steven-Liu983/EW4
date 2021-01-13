<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array();

$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = "root"; /* Password */
$dbname = "registration"; /* Database name */
$port = 8889;

// connect to the database
$db = mysqli_connect($host, $user, $password, $dbname, $port);

if (!$db) {
    die('Connection failed ' . mysqli_error($db));
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

date_default_timezone_set("America/New_York");
if (isset($_POST['save'])) {
    $name = mysqli_real_escape_string($db, $_POST['name'])." (By: ".$_SESSION['username'].")";
  	$comment = mysqli_real_escape_string($db, $_POST['comment'])." <".date("Y-m-d h:i:sa").">";
  	$sql = "INSERT INTO comments (name, comment) VALUES ('$name', '$comment')";
  	if (mysqli_query($db, $sql)) {
  	  $id = mysqli_insert_id($db);
      $saved_comment = '<div class="comment_box">
      		<span class="delete" data-id="' . $id . '" >delete</span>
      		<span class="edit" data-id="' . $id . '">edit</span>
      		<div class="display_name">'. $name .'</div>
      		<div class="comment_text">'. $comment .'</div>
      	</div>';
  	  echo $saved_comment;
  	}else {
  	  echo "Error: ". mysqli_error($db);
  	}
  	exit();
  }
  // delete comment fromd database
  if (isset($_GET['delete'])) {
  	$id = mysqli_real_escape_string($db, $_GET['id']);
  	$sql = "DELETE FROM comments WHERE id=" . $id;
  	mysqli_query($db, $sql);
  	exit();
  }
  if (isset($_POST['update'])) {
  	$id = mysqli_real_escape_string($db, $_POST['id']);
  	$name = mysqli_real_escape_string($db, $_POST['name'])." (By: ".$_SESSION['username'].")";
  	$comment = mysqli_real_escape_string($db, $_POST['comment'])." <".date("Y-m-d h:i:sa").">";
  	$sql = "UPDATE comments SET name='{$name}', comment='{$comment}' WHERE id=".$id;
  	if (mysqli_query($db, $sql)) {
  		$id = mysqli_insert_id($db);
  		$saved_comment = '<div class="comment_box">
  		  <span class="delete" data-id="' . $id . '" >delete</span>
  		  <span class="edit" data-id="' . $id . '">edit</span>
  		  <div class="display_name">'. $name .'</div>
  		  <div class="comment_text">'. $comment .'</div>
  	  </div>';
  	  echo $saved_comment;
  	}else {
  	  echo "Error: ". mysqli_error($db);
  	}
  	exit();
  }

  // Retrieve comments from database
  $sql = "SELECT * FROM comments";
  $result = mysqli_query($db, $sql);
  $comments = '<div id="display_area">'; 
  while ($row = mysqli_fetch_array($result)) {
  	$comments .= '<div class="comment_box">
  		  <span class="delete" data-id="' . $row['id'] . '" >delete</span>
  		  <span class="edit" data-id="' . $row['id'] . '">edit</span>
  		  <div class="display_name">'. $row['name'] .'</div>
  		  <div class="comment_text">'. $row['comment'] .'</div>
  	  </div>';
  }
  $comments .= '</div>';
?>