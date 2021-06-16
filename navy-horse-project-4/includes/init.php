<?php
// vvv DO NOT MODIFY/REMOVE vvv

// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define(VERSION_MESSAGE, "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}
check_php_version();

function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (file_exists($init_sql_filename)) {
      $db_init_sql = file_get_contents($init_sql_filename);
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    } else {
      unlink($db_filename);
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return null;
}

function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return null;
}

// ^^^ DO NOT MODIFY/REMOVE ^^^

// You may place any of your code here.

$db = open_or_init_sqlite_db('secure/liondance.sqlite', 'secure/init.sql');

//set cookie session duration to 1 hour
define('SESSION_COOKIE_DURATION', 3600);

//create find user account function
function find_user($user_id)
{
  global $db;
  //select all info from users table where id in database = user_id given
  $sql = "SELECT * FROM users WHERE id = :user_id;";
  $params = array(
    ':user_id' => $user_id
  );
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if ($records) {
    return $records[0];
  }
  return NULL;
}
//create find session function
function find_session($session)
{
  global $db;
  if (isset($session)) {
    //select all from sessions table where session in database = session cookie from user
    $sql = "SELECT * FROM sessions WHERE session_id = :session;";
    $params = array(
      ':session' => $session
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      return $records[0];
    }
  }
  return NULL;
}
//create function to log in automatically when you are still in the 1 hour cookie session
function session_login()
{
  global $db;
  global $current_user;
  //check if the cookie session still exists
  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];
    $session_record = find_session($session);
    //check if there was a found session
    if (isset($session_record)) {
      $current_user = find_user($session_record['user_id']);
      // Renew cookie for another hour as defined by the cookie duration
      setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
      return $current_user;
    }
  }
}
//create function is checks if user is logged in or not
function is_user_logged_in()
{
  global $current_user;
  // if current_user variable is not NULL, then a user is logged in (if there is a current_user, then that means there is a user logged in).
  return ($current_user != NULL);
}

session_login();
