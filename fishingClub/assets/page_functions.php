<?php

function getPageName(string $id_page): mysqli_result
{
    global $connection;
    $query = "SELECT name from page WHERE id_page = '$id_page'";
    return mysqli_query($connection, $query);
}

function fillPageName(): void
{
    global $connection;
    $query = "SELECT * from page";
    $result = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $id_page = $row["id_page"];
        $name = $row["name"];
        if (empty($_SESSION["admin"]) && $name == "main_page") {
            continue;
        } else {
            echo "<option value='$id_page'>$name</option>";
        }
    }
}

function showLink($url, int $id, int $offset, int $page_number, int $counter): void
{
    if ($counter == $page_number) {
        echo "<a href='$url=$id&offset=$offset'><span id='visited_page' class='page_link_text'>$counter</span></a>";
    } else {
        echo "<a href='$url=$id&offset=$offset'><span class='page_link_text'>$counter</span></a>";
    }
}

function showLastLinks($url, int $id, int $page_number): void
{
    echo "<a><span class='dots'>...</span></a>";
    $second_last_offset = $_SESSION["amount_of_all_pages"] - 2;
    $last_offset = $second_last_offset + 1;
    showLink($url, $id, $second_last_offset, $page_number, $last_offset);

    $second_last_offset += 1;
    $last_offset += 1;
    showLink($url, $id, $second_last_offset, $page_number, $last_offset);
}

function showFirstLinks($url, int $id): void
{
    echo "<a href='$url=$id&offset=0'><span class='page_link_text'>1</span></a>";
    echo "<a href='$url=$id&offset=1'><span class='page_link_text'>2</span></a>";
    echo "<a><span class='dots'>...</span></a>";
}

function showPagination($url, int $id, int $page_number): void
{
    echo "<div class='page_links'>";

    if ($_SESSION["amount_of_all_pages"] <= 10) {
        for ($counter = 1; $counter <= $_SESSION["amount_of_all_pages"]; $counter++) {
            $page_offset = $counter - 1;
            showLink($url, $id, $page_offset, $page_number, $counter);
        }

    } else {
        if ($page_number <= 4) {

            for ($counter = 1; $counter < 8; $counter++) {
                $page_offset = $counter - 1;
                showLink($url, $id, $page_offset, $page_number, $counter);
            }
            showLastLinks($url, $id, $page_number);

        } elseif ($page_number < $_SESSION["amount_of_all_pages"] - 4) {
            showFirstLinks($url, $id);

            for ($counter = $page_number - 2; $counter <= $page_number + 2; $counter++) {
                $page_offset = $counter - 1;
                showLink($url, $id, $page_offset, $page_number, $counter);
            }
            showLastLinks($url, $id, $page_number);

        } else {
            showFirstLinks($url, $id);

            for ($counter = $_SESSION["amount_of_all_pages"] - 6; $counter <= $_SESSION["amount_of_all_pages"]; $counter++) {
                $page_offset = $counter - 1;
                showLink($url, $id, $page_offset, $page_number, $counter);
            }
        }
    }
    echo "</div>";
}

function validateDigitGetParam($parameter): void
{
    if (!ctype_digit($parameter)){
        header("HTTP/1.0 404 Not Found");
        header("location: error_page.php");
    }
}