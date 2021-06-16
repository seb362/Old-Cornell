<!--This page is to log out.-->

<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

//create function to log out of account
function log_out()
{
  global $current_user;
  global $db;
  $session = $_COOKIE['session'];
  if (isset($session)) {
    //Remove session from database
    $sql = 'DELETE FROM sessions WHERE session_id = :session';
    $params = array(':session' => $session);
    exec_sql_query($db, $sql, $params);
    //Expire session cookie
    setcookie('session', '', time() - SESSION_COOKIE_DURATION);
  }
  $current_user = NULL;
}

// Check if we should logout the user
if (isset($current_user)) {
  log_out();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">

  <title>Logout</title>
</head>

<body>
  <?php include("includes/header.php"); ?>

<div class="content">
  <h2>Logout</h2>
  <p>You have successfully been logged out.</p>
</div>

  <?php include("includes/footer.php"); ?>
</body>

</html>
