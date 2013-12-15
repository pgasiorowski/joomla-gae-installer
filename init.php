<?php

require 'vendor/autoload.php';
require 'src/JoomlaGae.php';

header('Content-type: application/json');

/**
 * Create files/folders on Google Cloud Storage
 */

try
{
  $gae = new \JoomlaGae\JoomlaGae;
  $gae->setRepoOwner('WooDzu');
  $gae->setBranch('gae-attempt1');
  $gae->setBucket('gs-joomla');
  $gae->copyData($_POST);

  echo json_encode(array(
    'Status' => 'OK',
  ));
}
catch (Exception $e)
{
  echo json_encode(array(
    'Status' => 'ERROR',
    'Msg' => $e->getMessage()
  ));
}
