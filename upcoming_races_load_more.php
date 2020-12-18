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

$query = mysqli_query($conc, "SELECT circuits.`code`, DATE_FORMAT(event.`date`, '%d %b') AS dd, event.`series`, event.`round`, circuits.`circuit`
FROM `event`
INNER JOIN circuits
ON circuits.`configuration` = event.`circuit`
WHERE event.`date` > CURRENT_DATE()
ORDER BY event.`date`
LIMIT {$from}, 5");

$return_result = '';
while ($row = mysqli_fetch_assoc($query)) {
    $return_result .= '<div class="table-row" style="margin-bottom: 10px;">
        <div class="custom-list">
            <span><img src=/results/flag/'.$row['code'].'.gif"></span>
            <span>' . $row['dd'] . '</span>
            &nbsp; - &nbsp;
            <span>' . $row['series'] . '</span>
            &nbsp; - &nbsp;
            <span>' . $row['circuit'] . '</span>
            &nbsp;
            <span>Round ' . $row['round'] . '</span>
        </div>
    </div>
    <hr>';
}

echo json_encode($return_result);
mysqli_close($conc);
