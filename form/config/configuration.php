<?php

$conn = mysqli_connect("localhost", "root", "", "protools-db");

if (!$conn) {
    echo "Connection Failed";
}