<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$activeofficers = "currentpage";

// This is executed if this page was reached by a user submitting an officer:
// handles adding the officer's image into the filesystem and info into the database
if (is_user_logged_in() && isset($_POST['submit_officer'])) {
  $name = $_POST['name'];
  $position = $_POST['position'];
  $biography = $_POST['biography'];
  $source = $_POST['source'];
  $upload_info = $_FILES['officer_image'];

  if (isset($_FILES['officer_image']) && $upload_info['error'] == UPLOAD_ERR_OK && $name && $position && $biography && $source) {
    $file_name = basename($upload_info['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $db->beginTransaction();

    $sql = 'INSERT INTO officer_images (file_name, file_extension, source) VALUES (:file_name, :file_extension, :source)';
    $params = array(
      ":file_name" => $file_name,
      ":file_extension" => $file_ext,
      ":source" => $source
    );
    $result = exec_sql_query($db, $sql, $params);
    print_r($result);

    if ($result) {
      $image_id = $db->lastInsertId('id');
      move_uploaded_file($_FILES['officer_image']['tmp_name'], ('uploads' . DIRECTORY_SEPARATOR . 'officers' . DIRECTORY_SEPARATOR . $image_id . '.' . $file_ext));
      $sql = 'INSERT INTO officers (name, position, biography, officer_image_id) VALUES (:name, :position, :biography, :image_id)';
      $params = array(
        ":name" => $name,
        ":position" => $position,
        ":biography" => $biography,
        ":image_id" => $image_id
      );
      $result = exec_sql_query($db, $sql, $params);
    }
    $db->commit();
  }
}

//This is executed if this page was reached by a user deleting an officer:
// handles deleting the officer's image and removing the officer's info from the database
else if (is_user_logged_in() && isset($_POST['delete_officer_submit'])) {
  if (isset($_POST['delete_officer_id']) && isset($_POST['delete_officer_image_path'])) {
    $sql_delete_officer = 'DELETE from officers WHERE id = :officer_id';
    $params_delete_officer = array(':officer_id' => $_POST['delete_officer_id']);
    $sql_delete_image = 'DELETE FROM officer_images WHERE id = :officer_image_id';
    $params_delete_image = array(':officer_image_id' => $_POST['delete_officer_image_id']);

    $db->beginTransaction();
    $delete_officer_result = exec_sql_query($db, $sql_delete_officer, $params_delete_officer);
    $delete_image_result = exec_sql_query($db, $sql_delete_image, $params_delete_image);
    $delete_result_uploads = unlink($_POST['delete_officer_image_path']);
    $db->commit();

    if ($delete_officer_result && $delete_image_result && $delete_result_uploads) {
      $delete_officer_success = true;
    } else {
      $delete_officer_success = false;
    }
  } else {
    $delete_officer_success = false;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">

  <title>Officers</title>
</head>

<body>
  <?php include("includes/header.php"); ?>
  <div id="officers_title">
    <h1>Executive Board 2018-2019</h1>
  </div>

  <div class="content">

    <?php
    if (isset($_POST['submit_officer']) && $result) {
      echo ('<h2 class="form_success center">Officer successfully submitted</h2>');
    } else if (isset($_POST['submit_officer'])) {
      echo ('<h2 class="form_error center">Error in submitting officer! Did you fill out all the fields?</h2>');
    } else if (isset($_POST['delete_officer_submit']) && $delete_officer_success) {
      echo ('<h2 class="form_success center">Officer successfully deleted</h2>');
    } else if (isset($_POST['delete_officer_submit'])) {
      echo ('<h2 class="form_error center">Error in deleting officer!</h2>');
    }

    $sql = 'SELECT officers.id, officers.name, officers.position, officers.biography, officers.officer_image_id, officer_images.file_extension, officer_images.source FROM officers LEFT OUTER JOIN officer_images ON officers.officer_image_id = officer_images.id';
    $records = exec_sql_query($db, $sql, array())->fetchAll(PDO::FETCH_ASSOC);
    foreach ($records as $record) {
      ?>

      <div class="flex-container">
        <div class="flex-officer">
          <!-- Source: <?php echo htmlspecialchars($record['source']) ?> -->
          <img src="<?php echo ("uploads/officers/") . $record["officer_image_id"] . "." . $record["file_extension"] ?>" class="officer-image" alt="Officer Image" />
          <div class=lion_citation>
            <cite>Source: <?php echo htmlspecialchars($record['source']) ?></cite>
          </div>
          <?php if (is_user_logged_in()) { ?>
            <form method="post" action="officers.php" class="inline-block">
              <input type="hidden" name="delete_officer_id" value="<?php echo $record["id"] ?>">
              <input type="hidden" name="delete_officer_image_id" value="<?php echo $record["officer_image_id"] ?>">
              <input type="hidden" name="delete_officer_image_path" value="<?php echo ("uploads" . DIRECTORY_SEPARATOR . "officers" . DIRECTORY_SEPARATOR . $record["officer_image_id"] . "." . $record["file_extension"]); ?>">
              <button type="submit" name="delete_officer_submit" class="delete_button">
                Delete Officer
              </button>
            </form>
          <?php } ?>
        </div>

        <div class="flex-officer">
          <div class="officer-name"><?php echo htmlspecialchars($record['name']); ?></div>
          <div class="officer-title"><?php echo htmlspecialchars($record['position']); ?></div>
          <div class="officer-bio"><?php echo htmlspecialchars($record['biography']); ?></div>
        </div>
      </div>

    <?php }
  if (is_user_logged_in()) { ?>
      <div class="officer-admin-form">
        <br />
        <h2>Admin: Add officer</h2>
        <form method="post" action="officers.php" class="requestform" enctype="multipart/form-data">

          <div class="question">
            <label>Select a picture of the officer: </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <input type="file" name="officer_image" id="officer_image">
          </div>

          <div class="question">
            <label>Enter a source for the picture: * </label>
            <input type="text" name="source">
          </div>

          <div class="question">
            <label>Enter the officer's name: * </label>
            <input type="text" name="name">
          </div>

          <div class="question">
            <label>Enter the officer's title: * </label>
            <input type="text" name="position">
          </div>

          <div class="question">
            <label>Enter a short biography for the officer: * </label>
            <textarea id="biography" name="biography" rows=6 cols=50></textarea>
          </div>

          <input type="submit" name="submit_officer" value="Submit" id="submit_officer">

        </form>
      <?php }
    ?>

    </div>
  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>
