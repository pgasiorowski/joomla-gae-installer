<?php

require 'vendor/autoload.php';
require 'src/JoomlaGae.php';


/**
 * Get tree of GitHub objects to be created on Google Cloud Storage
 */

$gae = new \JoomlaGae\JoomlaGae;
$gae->setRepoOwner('WooDzu');
$gae->setBranch('gae-attempt1');

?><!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>

<div class="well">
    <div class="container">
        <h3>Joomla Google AppEngine Installer!</h3>

        <a id="start" class="btn btn-primary">Start &raquo;</a>

        <div id="progressWrapper" style="display: none">
            <a id="stop" class="btn btn-danger">Stop &raquo;</a>
            <span>Progress: <em id="progress">0 %</em>
        </div>
    </div>
</div>

<div class="container" id="status">
</div> <!-- /container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>

  var gaeData = <?php echo json_encode($gae->fetchTree()) ?>;

  $('#start').on('click', function() {
    gaeJoomla.initialize(gaeData);
  });

  $('#stop').on('click', function() {
      gaeJoomla.stopIt();
  });
</script>
</body>
</html>