<?php
  $dbh = null;
  try {
    $dbh = @new PDO("mysql:host=######;dbname=######;charset=UTF8", "######", "######");
  } catch (PDOException $e) {
    die($e->getMessage());
  } catch (Exception $e) {
    die($e->getMessage());
  }
?>