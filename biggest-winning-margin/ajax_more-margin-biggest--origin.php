<?php

$servername = "localhost:3306";
$username = "tcn_results";
$password = "Tourenw4gen1!_";
$dbname = "tcn_results";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$sql = "SELECT COUNT(*) as num_rows From marginbigwin Where seed < '" . $_POST['id'] . "'";

$result = mysqli_query($conn, $sql);

// Count all records except already displayed
$row = mysqli_fetch_assoc($result);
$totalRowCount = $row['num_rows'];

$showLimit = 12;

$sid = $_GET['series'];
$sid = mysqli_real_escape_string($conn, $sid);

if (empty($_GET['series'])) {
    $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin WHERE seed < '" . $_POST['id'] . "' LIMIT " . $showLimit;
} else {
    $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin WHERE series in ('" . $sid . "') and seed < '" . $_POST['id'] . "' LIMIT " . $showLimit;
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $circuitID = $row["id"];
        echo "
        <div class='tb-row'>
            <div class='wrapper text-0'>
                <div class='wrapper text-0'>
                    <div class='text-series rownums'></div>
                    <div class='text-series'>
                        " . (($row["cship"] == 'STW Cup') ? 'STW' : $row["cship"]) . "
                    </div>
                    <div class='text-year'>
                        " . $row["yr"] . "
                    </div>
                </div>
            </div>
            <div class='wrapper text-2'>
                <div class='wrapper text-2'>
                    <div class='text-layout'>
                        " . $row["rd"] . "
                    </div>
                    <div class='text-driver'>
                        <a href='/results/statistics/lists/driver-wins.php?series=" . $row["cship"] . "&driver=" . $row["pilot"] . "'>" . $row["pilot"] . "</a>
                    </div>
                </div>
            </div>
            <div class='wrapper text-2'>
                <div class='wrapper text-2'>
                    <div class='text-entrant' title='" . $row["trvar"] . "'>
                        " . mb_strimwidth($row["trvar"], 0, 34, "...") . "
                    </div>
                    <div class='text-car' title='" . $row["vehicle"] . "'>
                        <em>" . mb_strimwidth($row["vehicle"], 0, 27, "...") . "</em>
                    </div>
                </div>
            </div>
            <div class='wrapper text-4'>
                <div class='wrapper text-4'>
                    <div class='text-time'>
                        " . $row["timed"] . "
                    </div>
                </div>
            </div>
        </div>";
    }
?>
    <?php if ($totalRowCount > $showLimit) { ?>
        <tr class="smore-tr">
            <td colspan="7">
                <div class="show_more_main" id="show_more_main<?php echo $circuitID; ?>">
                    <span id="<?php echo $circuitID; ?>" class="show_more" title="Load more posts">Show more</span>
                    <span class="loding" style="display: none;"><span class="loding_txt">Loading...</span></span>
                </div>
            </td>
        </tr>
    <?php } ?>
<?php
}
?>