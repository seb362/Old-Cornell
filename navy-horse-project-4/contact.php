<!-- Info for this page:
  - meeting information
  - contact information -->

<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

$activecontact = "currentpage";

///Performance Request Form Validation

if (isset($_POST['submit_request'])){
  $submitform = TRUE;

  $event_name = filter_input(INPUT_POST, "event_name", FILTER_SANITIZE_STRING);
  $location = filter_input(INPUT_POST, "location", FILTER_SANITIZE_STRING);
  $space_size = filter_input(INPUT_POST, "space_size", FILTER_SANITIZE_STRING);
  $performance_length = filter_input(INPUT_POST, "performance_length", FILTER_SANITIZE_STRING);
  $interaction = filter_input(INPUT_POST, "interaction", FILTER_SANITIZE_STRING);
  $pre_performance = filter_input(INPUT_POST, "pre_performance", FILTER_SANITIZE_STRING);
  $contact_name = filter_input(INPUT_POST, "contact_name", FILTER_SANITIZE_STRING);
  $contact_email = filter_input(INPUT_POST, "contact_email", FILTER_VALIDATE_EMAIL);
  $other_info = filter_input(INPUT_POST, "other_info", FILTER_SANITIZE_STRING);

  $contact_name = $_POST['contact_name'];
  $contact_number = $_POST['contact_number'];
  $contact_email = $_POST['contact_email'];
  $event_name = $_POST['event_name'];
  $event_date = $_POST['event_date'];
  $event_start = $_POST['event_start'];
  $arrive_time = $_POST['arrive_time'];
  $perform_time = $_POST['perform_time'];
  $location = $_POST['location'];
  $space_size = $_POST['space_size'];
  $performance_length = $_POST['performance_length'];
  $pre_performance = $_POST['pre_performance'];
  $interaction = $_POST['interaction'];
  $other_info = $_POST['other_info'];

  $msg =
  "Contact Name: ".$contact_name."\r\n" .
  "Contact Number: ".$contact_number."\r\n" .
  "Contact Email: ".$contact_email."\r\n" .
  "Event Name: ".$event_name."\r\n" .
  "Event Date: ".$event_date."\r\n" .
  "Start of Event: ".$event_start."\r\n" .
  "Time of Arrival: ".$arrive_time."\r\n" .
  "Performance Time: ".$perform_time."\r\n" .
  "Location: ".$location."\r\n" .
  "Size of the Space: ".$space_size."\r\n" .
  "Performance Length: ".$performance_length."\r\n" .
  "Any soundcheck, practice, and pre-performance events to attend: ".$pre_performance."\r\n" .
  "Level of Interaction: ".$interaction."\r\n" .
  "Other Info: ".$other_info;

  $header = "From: lion-dance@cornell.edu\r\n";

  $success = mail("kshi17@gmail.com","Performance Request from Website", $msg, $header);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">


  <title>Contact</title>
</head>

<body>
<?php include("includes/header.php"); ?>

<div id="contact_title">
  <h1>Contact</h1>
</div>

<div class="content">
  <h2>Email Us!</h2>
    <p>Email Cornell Lion Dance at <a href="mailto:lion-dance@cornell.edu" class="email">lion-dance@cornell.edu</a> if you have any questions!</p>

    <?php if (isset($submitform) && $submitform && $success) { ?>
      <h2>Performance Request Successful!</h2>
      <p>Thank you for requesting a performance with Cornell Lion Dance! We will get back to you as soon as possible with more information. Feel free to also email us at <a href="mailto:lion-dance@cornell.edu" class="email">lion-dance@cornell.edu</a>.</p>

    <?php } else { ?>

  <h2>Request a Performance</h2>
    <p>Please provide as much information as possible so we plan accordingly.</p>

    <p><em>IMPORTANT: I cannot stress how valuable rehearsals and setup are.</em> We like to scope out a performance space beforehand because we often start offstage. Also, often times the layout of a performance space has a huge impact on the choreography of a routine. So, giving us time to observe the performance space beforehand allows us to make small adjustments in order to make our performance more enjoyable, as well as to prevent the dancers from running into or tripping on things and people. True story.</p>

    <p>If the performance is on Cornell campus, then we can get ourselves there on our own. If it is off campus, any provided transportation would be greatly appreciated! Also, we will need some space to put our equipment, which consists of 1-3 lion heads, a drum, and 1-2 large bags.</p>

    <form method="post" action="contact.php" class="requestform">

    <div class="question">
      <label>Contact person's name</label>
      <input type="text" name="contact_name">
    </div>

    <div class="question">
      <label>Contact person's phone number</label>
      <input type="tel" name="contact_number">
    </div>

    <div class="question">
      <label>Contact person's email</label>
      <input type="email" name="contact_email">
    </div>

    <div class="question">
      <label>Name and/or theme of the event:</label>
      <input type="text" name="event_name">
    </div>

    <div class="question">
      <label>Date of the event:</label>
      <input type="date" name="event_date" min="2019-01-01">
    </div>

    <div class="question">
      <label>Start time of the event:</label>
      <input type="time" name="event_start">
    </div>

    <div class="question">
      <label>Time we should arrive:</label>
      <input type="time" name="arrive_time">
    </div>

    <div class="question">
      <label>Performance time:</label>
      <input type="time" name="perform_time">
    </div>

    <div class="question">
      <label>Location of the event:</label>
      <input type="text" name="location">
    </div>

    <div class="question">
      <label>Estimated size of performance space:</label>
      <input type="text" name="space_size">
    </div>

    <div class="question">
      <label>Requested length of performance:</label>
      <input type="text" name="performance_length">
    </div>

    <div class="question">
      <label>Any setup, soundcheck, rehearsal, or practice times we need to attend beforehand:</label>
      <input type="text" name="pre_performance">
      </div>

    <div class="question">
      <label>Level of audience interaction the routine should have</label>
      <input type="text" name="interaction">
    </div>

    <div class="question">
      <label>Other information and details</label>
      <input type="text" name="other_info">
    </div>

    <input type="submit" name="submit_request" value="Submit Performance Request" id="submit_request_button">

    </form>

    <?php } ?>

    </div>

<?php include("includes/footer.php"); ?>

</body>
</html>
