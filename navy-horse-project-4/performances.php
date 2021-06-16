<!-- Info to be included:
  - schedule/list of performances-->

<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

$activeperformances = "currentpage";

function printPerformance($result)
{
  echo '<div class="recent-performance">';
  echo '<h2>Event: ' . htmlspecialchars($result['performance']) . '</h2>';
  echo '<div class="performance-description">' . htmlspecialchars($result['description']) . '</div>';
  echo '<img src="uploads/gallery/' . htmlspecialchars($result['image_id']) . "." . htmlspecialchars($result['file_extension']) . '" class="performance-img" alt="lion dance performance"/>';
  echo '<div class=lion_citation><cite>Source: ' . htmlspecialchars($result['source']) . '</cite></div>';
  if (is_user_logged_in()) { ?>
    <form method="post" action="performances.php" class="inline-block">
      <input type="hidden" name="image_id" value="<?php echo $result['image_id'] ?>">
      <input type="hidden" name="performance_id" value="<?php echo $result['id'] ?>">
      <button type="submit" name="delete_performance_submit" class="delete_button">
        Delete Photo From Performances
      </button>
    </form>
  <?php }
echo '</div>';
}

// This section executed if an admin sent a post request to add a performance
if (isset($_POST['performance-image']) && isset($_POST['upload-performance-submit']) && is_user_logged_in()) {
  $image_id = filter_input(INPUT_POST, 'performance-image', FILTER_SANITIZE_NUMBER_INT);
  $performance_event = filter_input(INPUT_POST, 'performance-event', FILTER_SANITIZE_STRING);
  $performance_event_other = filter_input(INPUT_POST, 'performance-event-other', FILTER_SANITIZE_STRING);

  $db->beginTransaction();

  $sql = "SELECT * FROM images WHERE id = :image_id";
  $params = [
    ':image_id' => $image_id
  ];
  $image_results = exec_sql_query($db, $sql, $params)->fetchAll();

  $edit_performance_result = false;
  $error_message = "Error in editing performances!";
  // Case where image added to recent performances is submitted from an already existing event
  if ($image_results && $performance_event != "other") {
    // Check that the event does indeed exist
    $sql = "SELECT * FROM performances WHERE performance = :event";
    $params = [
      ':event' => $performance_event
    ];
    $performance_results = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($performance_results) {
      // Check that image and performance event are not already associated on this page
      $sql = 'SELECT * FROM performance_images WHERE image_id = :image_id AND performance_id = :performance_id';
      $params = [
        ':image_id' => $image_id,
        ':performance_id' => $performance_results[0]['id']
      ];
      $existing_results = exec_sql_query($db, $sql, $params)->fetchAll();
      if (!$existing_results) {
        // Associate the performance with the gallery image
        $sql = 'INSERT INTO performance_images (image_id, performance_id) VALUES (:image_id, :performance_id)';
        $params = [
          ':image_id' => $image_id,
          ':performance_id' => $performance_results[0]['id']
        ];
        $edit_performance_result = exec_sql_query($db, $sql, $params);
        if (!$edit_performance_result) {
          $error_message = "Error in submitting performance";
        }
      } else {
        $error_message = "The image for the performance you tried to submit is already on the page!";
      }
    } else {
      $error_message = "Error in submitting performance";
    }
    // Case where new performance event is supposedly to be added
  } else if ($image_results && $performance_event == "other" && $performance_event_other) {
    // Check that the performance event is not in the database:
    // If it is not, we add it, and if it is, we do not add it
    $sql  = 'SELECT * FROM performances WHERE performance = :event';
    $params = [
      ':event' => $performance_event_other
    ];
    $performance_results = exec_sql_query($db, $sql, $params)->fetchAll();
    if (!$performance_results) {
      // Add the new event to the database's performance events
      $sql = 'INSERT INTO performances (performance) VALUES (:event)';
      $params = [
        ':event' => $performance_event_other
      ];
      $insert_performance_results = exec_sql_query($db, $sql, $params);
    }
    if ($insert_performance_results || $performance_results) {
      $sql  = 'SELECT * FROM performances WHERE performance = :event';
      $params = [
        ':event' => $performance_event_other
      ];
      $performance_results = exec_sql_query($db, $sql, $params)->fetchAll();
      // Check that image and performance event are not already associated on this page
      $sql = 'SELECT * FROM performance_images WHERE image_id = :image_id AND performance_id = :performance_id';
      $params = [
        ':image_id' => $image_id,
        ':performance_id' => $performance_results[0]['id']
      ];
      $existing_results = exec_sql_query($db, $sql, $params)->fetchAll();
      if (!$existing_results) {
        // Associate the performance with the gallery image
        $sql = 'INSERT INTO performance_images (image_id, performance_id) VALUES (:image_id, :performance_id)';
        $params = [
          ':image_id' => $image_id,
          ':performance_id' => $performance_results[0]['id']
        ];
        $edit_performance_result = exec_sql_query($db, $sql, $params);
        if (!$edit_performance_result) {
          $error_message = "Error in submitting performance";
        }
      } else {
        $error_message = "The performance you tried to submit is already on the page!";
      }
    } else {
      $error_message = "Error in submitting performance!";
    }
  } else if ($image_results && $performance_event == "other") {
    $error_message = "You must enter an event name if you selected Other!";
  } else {
    $error_message = "Error in submitting performance!";
  }
  $db->commit();

  // This section executed if admin sent post request to delete a performance
} else if (isset($_POST['delete_performance_submit']) && is_user_logged_in()) {
  $sql = 'DELETE FROM performance_images WHERE image_id = :image_id AND performance_id = :performance_id';
  $params = array(
    ':image_id' => htmlspecialchars($_POST['image_id']),
    ':performance_id' => htmlspecialchars($_POST['performance_id'])
  );
  $edit_performance_result = exec_sql_query($db, $sql, $params);
  if (!$edit_performance_result) {
    $error_message = 'Error in deleting performance!';
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">

  <title>Performances</title>
</head>

<body>
  <?php include("includes/header.php"); ?>

  <div id="performance_title">
    <h1>Performances</h1>
  </div>

  <?php
  if (isset($_POST['upload-performance-submit']) && $edit_performance_result) {
    echo ('<h2 class="form_success center">Performance event successfully submitted!</h2>');
  } else if (isset($_POST['upload-performance-submit'])) {
    echo ('<h2 class="form_error center">' . $error_message . '</h2>');
  } else if (isset($_POST['delete_performance_submit']) && $edit_performance_result) {
    echo ('<h2 class="form_success center">Performance event successfully deleted!</h2>');
  } else if (isset($_POST['delete_performance_submit'])) {
    echo ('<h2 class="form_error center">' . $error_message . '</h2>');
  }
  ?>

  <h2 class="center bigger">Our Calendar</h2>

  <div id="calendarEmbed">
    <!--<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=cornell.edu_tnc0cka4vt20o7rhr5g0qo549c%40group.calendar.google.com&amp;color=%23AB8B00&amp;ctz=America%2FNew_York" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe> -->
    <img src="documents/schedule-tmp.png" alt="temp" class="schedule">
    <!-- temporary non embed image of calender -->
  </div>

  <h2 class="bigger center">Photos from Recent Performances</h2>

  <?php
  $sql = "SELECT performances.id, performances.performance, performance_images.image_id, performance_images.performance_id, images.description, images.name, images.file_extension, images.source FROM performances INNER JOIN performance_images ON performances.id = performance_images.performance_id INNER JOIN images ON performance_images.image_id = images.id";
  $params = [];
  $results = exec_sql_query($db, $sql, $params)->fetchAll();
  if (count($results) > 0) {
    foreach ($results as $result) {
      printPerformance($result);
    }
  }
  ?>

  <?php
  if (is_user_logged_in()) {
    ?>
    <h2 class="center">Admin: Add New Performance to Recent Performances</h2>
    <form action="performances.php" method="POST" id="upload-performance">

      <div><label for="performance-image">Select image from gallery to add to recent performances: * </label>
        <select name="performance-image">
          <?php
          $sql = "SELECT * FROM images";
          $params = [];
          $results = exec_sql_query($db, $sql, $params)->fetchAll();
          if (count($results) > 0) {
            foreach ($results as $result) {
              $opt = $result['name'] . ": " . $result['file_name'];
              echo '<option value="' . $result['id'] . '">' . $opt . '</option>';
            }
          }
          ?>
        </select>
      </div>
      <br />
      <div><label for="performance-event">Select event from which this photo was taken: * </label>
        <select name="performance-event">
          <?php
          $sql = "SELECT * FROM performances";
          $params = [];
          $results = exec_sql_query($db, $sql, $params)->fetchAll();
          if (count($results) > 0) {
            foreach ($results as $result) {
              $opt = htmlspecialchars($result['performance']);
              echo '<option value="' . $opt . '">' . $opt . '</option>';
            }
          }
          ?>
          <option value="other">Other (not listed)</option>
        </select>
      </div>
      <br />
      <div><label for="performance-event-other">Enter the event for the photo, only if you chose "Other" above: </label>
        <textarea name="performance-event-other" rows=1 cols=30></textarea></div>
      <br />

      <button name="upload-performance-submit" type="submit">Submit</button>
    </form>

  <?php
}


?>

  <?php include("includes/footer.php"); ?>

</body>

</html>
