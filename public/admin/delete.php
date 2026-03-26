<?php
require_once '../../app/controllers/BookController.php';

$controller = new BookController();
$controller->delete($_GET['id']);
