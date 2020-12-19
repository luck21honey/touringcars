<?php
$servername = "localhost:3306";
$username = "tcn_results";
$password = "BtccWtcr2020!";
$dbname = "tcn_results";

$from = $_POST['from'];
$conc = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$query = mysqli_query($conc, "SELECT DATE_FORMAT(races.`date`, '%d %b') AS dd, races.`series`, circuits.`circuit`, races.`round`, races.`race_id`
FROM races
INNER JOIN circuits
ON races.`track` = circuits.`configuration`
GROUP BY races.`race_id`
ORDER BY CAST(races.`race_id` AS UNSIGNED) DESC
LIMIT {$from}, 5");

$return_result = '';
while ($row = mysqli_fetch_assoc($query)) {
    $return_result .= '<div class="table-row" style="margin-bottom: 10px;">
        <div class="custom-list">
            <div>
                <span>' . $row['dd'] . '</span>
                &nbsp; - &nbsp;
                <span>' . $row['series'] . '</span>
                &nbsp; - &nbsp;
                <span>' . $row['circuit'] . '</span>
                &nbsp;
                <span>Round ' . $row['round'] . '</span>
            </div>
        </div>
    </div>
    <hr>';
}

echo json_encode($return_result);
mysqli_close($conc);
