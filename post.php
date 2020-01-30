<?php
session_start();

require_once('fonc/functions.php');

addMediaAndPost();

header("Location: index.php?postAdded=true");
?>


