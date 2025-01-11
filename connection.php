<?php
$conn = mysqli_connect('localhost','root','password','hamro_job');

if(!$conn){
    die('Connection Failed'.mysqli_connect_error());
}
?>