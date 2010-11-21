<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	include('save.php');
}
if (isset($_GET['delete']) && $_GET['delete'] == 'y') {
	include('delete.php');
}

include "list.php";

?>
