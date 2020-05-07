<?php
$orig = "I'll \"walk\" the <br>dog<br> now";






?>

<h2><?php echo html_entity_decode(htmlentities($orig)); ?></h2>