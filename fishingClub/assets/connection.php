<?php

function connection(): void
{
    global $connection;
    $connection = mysqli_connect("localhost", "root", "", "taranyel");
    if (!$connection) {
        die("connection failed!");
    }
}