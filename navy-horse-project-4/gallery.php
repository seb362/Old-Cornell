<!--Info to be included:
  - performance videos
  - image gallery
  - upload pictures form-->

<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

// This is executed if this page was reached by a logged in user submitting an image:
// handles deleting the image and removing it from the database
if (is_user_logged_in() && isset($_POST['submit_image'])) {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $source = $_POST['source'];
  $upload_info = $_FILES['gallery_image'];

  if (isset($_FILES['gallery_image']) && $upload_info['error'] == UPLOAD_ERR_OK && $source != "" && $description != "") {
    $file_name = basename($upload_info['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $sql = 'INSERT INTO images (file_name, file_extension, name, description, source) VALUES (:file_name, :file_extension, :name, :description, :source)';
    $params = array(
      ":file_name" => $file_name,
      ":file_extension" => $file_ext,
      ":name" => $name,
      ":description" => $description,
      ":source" => $source
    );
    $submit_result = exec_sql_query($db, $sql, $params);
    $image_id = $db->lastInsertId('id');
    if ($submit_result) {
      move_uploaded_file($_FILES['gallery_image']['tmp_name'], ('uploads' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . $image_id . '.' . $file_ext));
    }
  }
}

//This is executed if this page was reached by a user deleting an image:
// handles deleting the image and removing it from the database
else if (is_user_logged_in() && isset($_POST['delete_gallery_submit'])) {
  if (isset($_POST['delete_gallery_id']) && isset($_POST['delete_gallery_path'])) {
    $sql_delete_image = 'DELETE FROM images WHERE id = :image_id';
    $params = array(':image_id' => $_POST['delete_gallery_id']);
    $delete_result = exec_sql_query($db, $sql_delete_image, $params);
    $delete_result_uploads = unlink($_POST['delete_gallery_path']);
    if ($delete_result && $delete_result_uploads) {
      $delete_image_result = true;
    } else {
      $delete_image_result = false;
    }
  } else {
    $delete_image_result = false;
  }
}


function display_image($index, $record)
{ ?>
  <div class="galleryImg">
    <!-- Source: <?php echo htmlspecialchars($record['source']) ?> -->
    <figure>
      <img src="<?php echo ("uploads/gallery/") . $record["id"] . "." . $record["file_extension"] ?>" alt="<?php echo $record["description"]; ?>" class="galleryPic" onclick="openSlideshow();currentSlide(<?php echo $index ?>)">
      <figcaption>
        <cite>Source: <?php echo htmlspecialchars($record['source']) ?></cite>
      </figcaption>
    </figure>
    <?php if (is_user_logged_in()) { ?>
      <form method="post" action="gallery.php" class="inline-block">
        <input type="hidden" name="delete_gallery_id" value="<?php echo $record["id"] ?>">
        <input type="hidden" name="delete_gallery_path" value="<?php echo ("uploads" . DIRECTORY_SEPARATOR . "gallery" . DIRECTORY_SEPARATOR . $record["id"] . "." . $record["file_extension"]); ?>">
        <button type="submit" name="delete_gallery_submit" class="delete_button">
          Delete Image
        </button>
      </form>
    <?php } ?>
  </div>
<?php }

$activegallery = "currentpage";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">
  <script src="includes/slideshow.js"></script>

  <title>Gallery</title>
</head>

<body>
  <?php include("includes/header.php"); ?>

  <div id="gallery_title">
    <h1>Gallery</h1>
  </div>

  <?php
  //Display success or error messages if attempt made to submit or delete gallery image
  if (isset($_POST['submit_image']) && $submit_result) {
    echo ('<h2 class="form_success center">Image successfully submitted</h2>');
  } else if (isset($_POST['submit_image'])) {
    echo ('<h2 class="form_error center">Error in submitting image! Did you fill out all the fields?</h2>');
  } else if (isset($_POST['delete_gallery_submit']) && $delete_result) {
    echo ('<h2 class="form_success center">Image successfully deleted</h2>');
  } else if (isset($_POST['delete_gallery_submit'])) {
    echo ('<h2 class="form_error center">Error in deleting image!</h2>');
  }

  //Retrieve all image information and display images
  $sql = 'SELECT * FROM images';
  $params = array();
  $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);

  foreach ($records as $index => $record) {
    if ($index % 3 == 0) { ?>
      <div class="galleryRow">
        <?php
        display_image($index, $record);
      } else if ($index % 3 == 1) {
        display_image($index, $record);
      } else {
        display_image($index, $record);
        ?>
      </div>
    <?php
  }
}
if (count($records) % 3 != 0) echo '</div>';
?>
  <div id="slideshow">
    <span id="closeSlideshow" onclick="closeSlideshow()">&times;</span>
    <div id="slideContainer">
      <?php
      foreach ($records as $record) {
        ?>
        <div class="slide">
          <img class="slideImg" src="<?php echo ("uploads/gallery/") . $record["id"] . "." . $record["file_extension"] ?>" alt="<?php echo ($record["name"]) ?>">
        </div>
        <div class="slideshowDescription">
          <p class="slideDescription">
            <?php echo ($record["name"]) ?><br />
            <?php echo ($record["description"]) ?>
          </p>
        </div>
      <?php
    }
    ?>
    </div>
    <a id="prevSlide" onclick="prevSlide()">&#10094;</a>
    <a id="nextSlide" onclick="nextSlide()">&#10095;</a>

  </div>

  <?php if (is_user_logged_in()) { ?>
    <div class="content">
      <h2>Admin: Add image to gallery</h2>

      <form method="post" action="gallery.php" class="requestform" enctype="multipart/form-data">

        <div class="question">
          <label>Select image to submit: </label>
          <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
          <input type="file" name="gallery_image" id="gallery_image">
        </div>

        <div class="question">
          <label>Enter a name for the image: * </label>
          <input type="text" name="name">
        </div>

        <div class="question">
          <label>Enter a short description of the image: * </label>
          <textarea id="description" name="description" rows=6 cols=50></textarea>
        </div>

        <div class="question">
          <label>Enter a source for the image: * </label>
          <input type="text" name="source">
        </div>

        <input type="submit" name="submit_image" value="Submit Image" id="submit_image">

      </form>
    </div>

  <?php } ?>

  <?php include("includes/footer.php"); ?>
</body>

</html>
