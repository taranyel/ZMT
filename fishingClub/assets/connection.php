<?php

/**
 * <p>The <b>connection()</b> function connects to the database and terminates all processes in case of connection failure.</p>
 * @return void
 */
function connection(): void
{
    global $connection;
    $connection = mysqli_connect("localhost", "root", "", "taranyel");
    if (!$connection) {
        die("connection failed!");
    }
}