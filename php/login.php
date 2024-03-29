<?php
/**
 * Created by PhpStorm.
 * User: vietnguyenswin
 * Date: 9/11/17
 * Time: 8:33 PM
 */

/*connect database*/
error_reporting(0);
$connection = @mysqli_connect("localhost", "westudyi_pharma", "pharmacy", "westudyi_pharmacy");
$table = "User";
@mysqli_select_db($connection, $table);

if (!$connection)
{
    echo "<script type='text/javascript'>";
    echo "alert('Database connection failure');";
    echo "</script>";
} else {

    $errMsg = "";

    $username = mysqli_real_escape_string($connection, $_POST["login_id"]);
    $password = mysqli_real_escape_string($connection, $_POST["login_pass"]);

    if ($username == "") {
        $errMsg .= "<p>You must submit your username.</p>";
    }

    if ($password == "") {
        $errMsg .= "<p>You must submit a password with your username.</p>";
    }

    $check_match_login_query = "SELECT username, password FROM $table WHERE username='$username' AND password='$password'";
    $check_match_login = mysqli_query($connection, $check_match_login_query);

    $user_role_query = "SELECT role FROM $table WHERE username = '$username'";
    $user_role_fetch = $connection->query($user_role_query)->fetch_assoc();

    $check = mysqli_num_rows($check_match_login);

    if ($check != 1 && $username != "" && $password != "") {
        $errMsg .= "<p>Wrong login details. Please try again!</p>";
    }

    if ($errMsg != "") {
        echo $errMsg;
    } else {
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $user_role_fetch["role"];
    }
    mysqli_close($connection);
}