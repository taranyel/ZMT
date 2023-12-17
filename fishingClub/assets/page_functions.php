<?php

/**
 * @param string $id_page <p>is page unique id.</p>
 * @return string <p>
 *Returns page name according to its id.</p>
 */
function getPageName(string $id_page): string
{
    global $connection;
    $query = "SELECT name from page WHERE id_page = '$id_page'";
    return mysqli_fetch_array(mysqli_query($connection, $query))["name"];
}

/**
 * <p>The <b>fillPageName()</b> function fills options in page selector, which is located on the adding article page
 * (add_update_message.php).</p>
 * @return void
 */
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

/**
 * <p>The <b>showLink()</b> function shows pagination link.</p>
 * @param $url <p>is address of the page to which the link will point.</p>
 * @param int $id <p>is id of the page (category) to which the link will point.</p>
 * @param int $offset <p>is the shift from which articles will be displayed on the page.</p>
 * @param int $page_number <p>is number of the current displayed page.</p>
 * @param int $counter <p>is the current link, which will displayed in the pagination block.</p>
 * @return void
 */
function showLink($url, int $id, int $offset, int $page_number, int $counter): void
{
    if ($counter == $page_number) {
        echo "<a href='$url=$id&offset=$offset'><span id='visited_page' class='page_link_text'>$counter</span></a>";
    } else {
        echo "<a href='$url=$id&offset=$offset'><span class='page_link_text'>$counter</span></a>";
    }
}

/**
 * <p>The <b>showLastLinks()</b> function shows two last pagination links.</p>
 * @param $url <p>is address of the page to which the link will point.</p>
 * @param int $id <p>is id of the page (category) to which the link will point.</p>
 * @param int $page_number <p>is number of the current displayed page.</p>
 * @return void
 */
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

/**
 * <p>The <b>showFirstLinks()</b> function shows two first pagination links.</p>
 * @param $url <p>is address of the page to which the link will point.</p>
 * @param int $id <p>is id of the page (category) to which the link will point.</p>
 * @param int $page_number <p>is number of the current displayed page.</p>
 * @return void
 */
function showFirstLinks($url, int $id, int $page_number): void
{
    showLink($url, $id, 0, $page_number, 1);
    showLink($url, $id, 1, $page_number, 2);
    echo "<a><span class='dots'>...</span></a>";
}

/**
 * <p>The <b>showPagination()</b> function shows pagination block in the bottom of the page.</p>
 * @param $url <p>is address of the page to which the link will point.</p>
 * @param int $id <p>is id of the page (category) to which the link will point.</p>
 * @param int $page_number <p>is number of the current displayed page.</p>
 * @return void
 */
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
            showFirstLinks($url, $id, $page_number);

            for ($counter = $page_number - 2; $counter <= $page_number + 2; $counter++) {
                $page_offset = $counter - 1;
                showLink($url, $id, $page_offset, $page_number, $counter);
            }
            showLastLinks($url, $id, $page_number);

        } else {
            showFirstLinks($url, $id, $page_number);

            for ($counter = $_SESSION["amount_of_all_pages"] - 6; $counter <= $_SESSION["amount_of_all_pages"]; $counter++) {
                $page_offset = $counter - 1;
                showLink($url, $id, $page_offset, $page_number, $counter);
            }
        }
    }
    echo "</div>";
}

/**
 * <p>The <b>validateIdPageParam()</b> function is used for page id validation.</p>
 * @param $id_page <p>is given page id.</p>
 * @return int <p>
 * Function returns 5 as a default value in case of validation failure.
 * If validation passed successfully, returns current page id.</p>
 */
function validateIdPageParam($id_page): int
{
    if (!ctype_digit($id_page) || strpos($id_page, ".") || (intval($id_page) < 0) || (intval($id_page) > 5)){
        return 5;
    }
    return intval($id_page);
}

/**
 * <p>The <b>validateOffsetParam()</b> function is used for offset value validation.</p>
 * @param $offset <p>is given offset value.</p>
 * @return int <p>
 *  Function returns 0 as a default value in case of validation failure.
 *  If validation passed successfully, returns current offset value.</p>
 */
function validateOffsetParam($offset): int
{
    if (!ctype_digit($offset) || strpos($offset, ".") || (intval($offset) < 0)){
        return 0;
    }
    return intval($offset);
}

/**
 * <p>The <b>validateIdUserMessageParam()</b> function is used for user and article id validation.</p>
 * @param $id <p>is given id.</p>
 * @return int <p>
 *   Function returns 0 as a default value in case of validation failure.
 *   If validation passed successfully, returns current id.</p>
 */
function validateIdUserMessageParam($id): int
{
    if (!ctype_digit($id)){
        return 0;
    }
    return intval($id);
}