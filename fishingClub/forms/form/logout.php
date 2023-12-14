<?php
session_start();
session_unset();
session_destroy();
header("location: ../../index.php?id_page=5&offset=0");