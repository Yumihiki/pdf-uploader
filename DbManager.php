<?php

function getDb() {
  $dsn = 'sqlite:uploader.db';
  $usr = 'root';
  $pas = '';

  $db = new PDO($dsn, $usr, $pas );
  $db->setAttribute(PDO::ATTR_ERRMODE, PDOLLERRMODE_EXCEPTION);
  return $db;
}

?>