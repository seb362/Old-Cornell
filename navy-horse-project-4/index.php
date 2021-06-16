
<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");

$activehome = "currentpage";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css">

  <title>Home</title>
</head>

<body>
  <?php include("includes/header.php"); ?>

<div id="index_title">
  <h1>Cornell Lion Dance</h1>
</div>

  <div class="content">
    <h2>Introduction to the Club</h2>
    <p>Cornell Lion Dance is a group of Cornell students dedicated to learning and spreading the joy of the traditional Chinese lion dance. We welcome members of any experience level -- in fact, most of our members joined with zero experience, learning from more senior members. If you are interested in joining, feel free to contact us with any questions or stop by any of our practices.</p>
    <p id="practicetime">Our practices are in the Helen Newman basement classroom every Friday 6:50-8:20 pm and Sunday 3:15-4:45 pm.</p>
    <p>Additionally, we perform regularly during the academic year at events on campus as well as within the greater Ithaca community. Check out our events calendar to catch our next performance!</p>

    <h3>What is Lion Dance?</h3>
    <p>Lion dance is a form of traditional dance in Chinese culture and other Asian countries where performers mimic a lion's movements in a lion costume to bring good luck and fortune. The lion dance is usually performed during the Chinese New Year and other Chinese traditional, cultural and religious festivals. It is also performed at important occasions such as business opening events, special celebrations, or wedding ceremonies.</p>
    <p>Generally, a lion dance performance will include at least one lion, a drum, and sometimes other instruments and props. Two dancers control each lion, with one holding the head and the other acting as the tail. There are several styles of lion dancing, with the main two groups being the Northern Lion and Southern Lion. The style of lion dancing that we do falls under the Southern style, which is characterized by rich expression and unique footwork.</p>

    <div class="lion_image"><img src="images/lion.jpg" alt="Lion" class="lion" /></div>
    <div class="lion_citation">Image Source: Wikipedia</div>

    <p><em>This is a lion, NOT A DRAGON</em>, although it may seem to have some dragon-like features. This is said to be because there were no lions in ancient China, so people took some creative liberties in interpreting the lion’s appearance.</p>
  </div>


  <?php include("includes/footer.php"); ?>

</body>

</html>
