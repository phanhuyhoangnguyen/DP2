<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/11/17
 * Time: 11:50 PM
 */

if (isset($_POST["submit_logout"])) {
    session_start();
    session_destroy();
    header("Location: manage.php");
}