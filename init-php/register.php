<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/13/17
 * Time: 8:58 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "User";
@mysqli_select_db($connection, $table);
session_start();

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else if (isset($_POST["submit_register"])) {

    if (!($_SESSION["role"] == "manager") || !($_SESSION["role"] == "admin")) {
        echo "Only manager and administrator can create new user.";
    } else {
        $errMsg = "";

        $full_name = mysqli_real_escape_string($connection, $_POST["full_name"]);
        $username = strtolower(mysqli_real_escape_string($connection, $_POST["user_id"]));
        $password = mysqli_real_escape_string($connection, $_POST["password"]);
        $confirm_password = mysqli_real_escape_string($connection, $_POST["confirm_password"]);
        $email = mysqli_real_escape_string($connection, $_POST["email"]);
        $role = mysqli_real_escape_string($connection, $_POST["role"]);

        if ($full_name == "") {
            $errMsg .= "<p>You must provide your full name.</p>";
        } else if (!preg_match("/^[a-zA-Z ]*$/", $full_name)) {
            $errMsg .= "<p>Only alphabet characters allowed for your full name.</p>";
        }

        if ($username == "") {
            $errMsg .= "<p>You must provide your username.</p>";
        } else if (!preg_match("/^[a-zA-Z0-9_]{3,15}$/", $username)) {
            $errMsg .= "<p>Only alphabet characters, numbers and underscore allowed for your username. And it must be between 3 - 15 characters.</p>";
        }

        if ($password == "") {
            $errMsg .= "<p>You must submit a passcode.</p>";
        } else if ($role == "staff") {
            if (!preg_match("/^[0-9]{1,15}$/", $password)) {
                $errMsg .= "Passcode for staff user must be less than 15 digits.";
            }
        } else if ($role == "manager") {
            if (!preg_match("/^[0-9]{6,15}$/", $password)) {
                $errMsg .= "Passcode for manager user must be at least 6 digits and less than 15 digits.";
            }
        }

        if ($confirm_password == "") {
            $errMsg .= "<p>You must type your passcode second time.</p>";
        } else if ($confirm_password != $password) {
            $errMsg .= "<p>Your passcode does not match.</p>";
        }

        if ($email == "") {
            $errMsg .= "<p>You must provide your email address.</p>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errMsg .= "<p>Your email address is invalid. Please try again.</p>";
        }

        if ($role == "") {
            $errMsg .= "<p>You must select account type</p>";
        }

        if ($errMsg != "") {
            echo $errMsg;
        } else {
            echo "Fuck";
        }
    }
}