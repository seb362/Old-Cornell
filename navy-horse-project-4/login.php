<!--This page is to log in.-->

<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

//create log in function
function log_in($username, $password)
{
  global $db;
  global $current_user;
  //check if username and password was entered into the form
  if (isset($username) && isset($password) && $username && $password) {
    //select all from users table where username in table = username provided
    $sql = "SELECT * FROM users WHERE username = :username;";
    $params = array(
      ':username' => $username //use parameter markers
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $account = $records[0];
      // Verify if password is same as the password in the users table in database
      if (password_verify($password, $account['password'])) {
        // Create cookie session
        $session = session_create_id();
        // Store session ID in sessions table in database
        $sql = "INSERT INTO sessions (user_id, session_id) VALUES (:user_id, :session);";
        $params = array(
          ':user_id' => $account['id'],
          ':session' => $session
        );
        $result = exec_sql_query($db, $sql, $params);
        if ($result) {
          //if $result is a success, then session is successfully stored in DB
          //Start cookie session of 1 hour that was already defined at the top of the code
          setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
          $current_user = $account;
          return NULL;
        } else {
          //failed log in
          return ("Log in failed.");
        }
      } else {
        //username or password does not match the information in database
        return ("Invalid username or password.");
      }
    } else {
      //username or password does not match the information in database
      return ("Invalid username or password.");
    }
  } else {
    //no username or password was inputted
    return ("No username or password given.");
  }
}

// Check if username, password, login was typed into the form
if (!is_user_logged_in() && isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
  //sanitize username and password that was typed in
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  //use log_in function that was created
  $login_result = log_in($username, $password);
}

$activelogin = "currentpage";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">

  <title>Login</title>
</head>

<body>
  <?php include("includes/header.php"); ?>

  <?php if (!is_user_logged_in()) { ?>

    <div class="content">
      <h2>Login</h2>
      <p>Please login below to upload and delete images and edit information!</p>

      <?php
      if ($login_result) {
        echo ('<h4 class="form_error">' . $login_result . '</h5>');
      }
      ?>

      <form method="post" action="login.php">
        <div class="question">
          <label>Username:</label>
          <input type="text" name="username">
        </div>

        <div class="question">
          <label>Password:</label>
          <input type="password" name="password">
        </div>

        <input type="submit" name="login" value="Login">
      </form>

    <?php } else { ?>
      <div class="content">
        <h2>Success!</h2>
        <p> You have successfully logged in as User: <?php echo $current_user['username'] ?>. Now you can add and delete officer information, add and delete photos in the Gallery page, and insert and remove existing Gallery photos on the Performances page. You can do this with the form at the bottom of each of the respective pages.</p>
      </div>
    <?php } ?>

  </div>

  <?php include("includes/footer.php"); ?>
</body>

</html>
