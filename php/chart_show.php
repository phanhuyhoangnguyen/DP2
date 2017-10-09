<?php
session_start();
$jsonArray =$_SESSION['data'];
header('Content-type: application/json');
echo json_encode($jsonArray);