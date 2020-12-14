<?php
define('WP_USE_THEMES', false);
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$servername = "localhost:3306";
$username = "tcn_results";
$password = "BtccWtcr2020!";
$dbname = "tcn_results";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$sid = $_GET['series'];
$sid = mysqli_real_escape_string($conn, $sid);

if ($sid == 'ITC') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc limit 100";
} elseif ($sid == 'DTM') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('DTM', 'ITC') Group by races.driver HAVING Wins > 0 order by 9 desc limit 100";
} elseif ($sid == 'BTCC') {
    $sql = "(SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0)

UNION

(SELECT races.driver2 as Drvr2, drivers.races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,image,sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End) as Wins, (SELECT count(races.driver) from `races` where races.driver = Drvr2) + (SELECT count(races.driver2) from `races` where races.driver2 = Drvr2), Round(((sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End)) / (SELECT count(races.driver2) from `races` where races.driver = Drvr2))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id2 Where `Series` in ('" . $sid . "') Group by races.driver2 HAVING Wins > 0)

order by 9 desc";
} elseif ($sid == 'ETCC') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('ETCC', 'Euro STC') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'Euro STC') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'ETC Cup') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'RCRS') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'STCC' or $sid == 'TCR SC') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'STW Cup') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'WC TCR') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'WTCC') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'WTCR') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR Asia') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR AU') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR DE') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR EE') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR EU') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR Intl') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR IT') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR JP') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR UK') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'TCR KR' or $sid == 'TCR MY') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,Sum(Case When races.result=\"1\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((Sum(Case When races.result=\"1\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('" . $sid . "') Group by races.driver HAVING Wins > 0 order by 9 desc";
} elseif ($sid == 'ALL') {
    $sql = "SELECT races.driver, drivers.image,races.`driver2`, races.`driver_id2`, races.`driver3`, races.`driver_id3`, races.`driver4`, races.`driver_id4`,sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End) as Wins, count(races.driver) as Races, Round(((sum(case when races.year=\"2001\" and races.class_pos=\"1\" and races.class=\"\" then 1 else 0 end)+Sum(Case When races.year!=\"2001\" and races.result=\"1\" and races.class != \"P\" Then 1 Else 0 End)) / count(races.driver))*100,1) as Percent From `drivers` INNER JOIN races on drivers.id = races.driver_id Where `Series` in ('BTCC', 'DTM', 'ETCC', 'Euro STC', 'ETC Cup', 'ITC', 'STCC', 'STW Cup', 'TCR Asia', 'TCR DE', 'TCR EU', 'RCRS', 'TCR IT', 'TCR Intl', 'TCR UK', 'WC TCR', 'WTCC', 'WTCR') Group by races.driver HAVING Wins > 0 order by 9 desc";
} else {
    $sql = "";
}

if ($sid == 'ITC') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'DTM') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series in ('DTM', 'ITC') and Result = '1'";
} elseif ($sid == 'BTCC') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'ETCC') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series in ('ETCC', 'Euro STC') and Result = '1'";
} elseif ($sid == 'ETC Cup') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'RCRS') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'STCC' or $sid == 'TCR SC') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'STW Cup') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'WC TCR') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'WTCC') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'WTCR') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR Asia') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR AU') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR DE') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR EE') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR EU') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR Intl') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR IT') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR JP') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR UK') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'TCR KR' or $sid == 'TCR MY') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series = '" . $sid . "' and Result = '1'";
} elseif ($sid == 'ALL') {
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE series in ('BTCC', 'DTM', 'ETCC', 'Euro STC', 'ITC', 'WC TCR', 'TCR Asia', 'TCR EU', 'TCR Intl', 'TCR IT', 'TCR DE', 'TCR UK', 'WTCC', 'WTCR', 'STCC') and Result = '1'";
} else {
    $sql2 = "";
}

$sqldrivers = "SELECT id, driver, image FROM drivers ORDER BY id";
$drivers_result = mysqli_query($conn, $sqldrivers);
$drivers = [];
while ($row = mysqli_fetch_assoc($drivers_result)) {
    $drivers[$row['driver']] = $row;
}

$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);

$driver_wins = [];
while ($row = mysqli_fetch_assoc($result)) {
    $driver_wins[] = $row;
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php bloginfo('name'); ?> &raquo; Database &raquo; <?php echo $sid ?> &raquo; Race wins by driver</title>
    <style type="text/css">
        .h4h {
            margin-left: 10px;
        }

        .stats-table {
            width: 96%;
            padding: 3px;
            margin: 5px;
            margin-left: 10px;
        }

        .stats-table img {
            display: inline;
        }

        .stats-table thead {
            background-color: #e5e5e5;
        }

        .stats-table tbody tr {
            border-bottom: 1px dashed #000000;
        }

        .stats-table tbody tr:hover {
            background-color: #d1d5ff;
        }

        .stats-table tbody tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .stats-table tbody tr:nth-child(even):hover {
            background-color: #d1d5ff;
        }

        tbody tr {
            counter-increment: rownum;
        }

        tbody {
            counter-reset: rownum;
        }

        table#sample1 td:first-child:before {
            content: counter(rownum) " ";
        }

        table#nat-table td.rownums:before {
            content: counter(rownum);
        }

        .rank {
            width: 10%;
        }

        .identifier {
            width: 59%;
        }

        .stat {
            width: 9%;
        }

        .tot {
            width: 11%;
        }

        .percent {
            width: 11%;
        }
    </style>

    <script src="/results/tablesorter/js/jquery-latest.min.js"></script>

    <script src="/results/tablesorter/js/jquery.tablesorter.min.js"></script>
    <script src="/results/tablesorter/js/jquery.tablesorter.widgets.min.js"></script>
    <script>
        $(function() {
            $('table').tablesorter({
                widgets: ['columns'],
                usNumberFormat: false,
                sortReset: true,
                sortRestart: true
            });
        });
    </script>

</head>

<?php include(TEMPLATEPATH . '/header2.php'); ?>

<body>
    <div class="td-container">

        <div class="td-pb-row">

            <div class="td-pb-span8">

                <div class="td-ss-main-content">

                    <div class="clearfix"></div>

                    <a href='../../'><?php bloginfo('name'); ?></a> &raquo; Database &raquo; <?php echo $sid ?> &raquo; Race wins by driver

                    <h4 class="h4h"><?php echo $sid ?> race wins - Drivers</h4>

                    &nbsp;&nbsp;<em>Note: Data valid for period between <?php if (mysqli_num_rows($result2) > 0) {
                                                                            while ($row = mysqli_fetch_assoc($result2)) {
                                                                                echo $row["mindate"] . " and " . $row["maxdate"];
                                                                            }
                                                                        } else {
                                                                            echo "0 results";
                                                                        }    ?></em>

                    <table id="nat-table" class="stats-table tablesorter">
                        <thead>
                            <tr>
                                <th class="rank">Rank</th>
                                <th class="identifier">Driver</th>
                                <th class="stat">Wins</th>
                                <th class="tot">Races</th>
                                <th class="percent">Percent</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (count($driver_wins) > 0) {
                                // output data of each row
                                foreach ($driver_wins as $row) {
                                    // echo "<tr><td>" . $row["rank"]. "</td><td>" . $row["driver"]. "</td><td>" . $row["sessions"]. "</td></tr>";
                                    echo "<tr>
                            <td class='rownums'></td>
                            <td>
                                <img src='../results/flag/" . $row["image"] . ".gif' />&nbsp;
                                <a href='../results/statistics/lists/driver-wins.php?series=" . urlencode($sid) . "&driver=" . urlencode($row["driver"]) . "'>" . $row["driver"] . "</a>"
                                        . ($row["driver2"] ?
                                            "<br><img src='../results/flag/" . $drivers[$row['driver2']]['image'] . ".gif' />&nbsp;
                                            <a href='../results/statistics/lists/driver-wins.php?series=" . urlencode($sid) . "&driver=" . urlencode($row["driver2"]) . "' >" . $row["driver2"] . "</a>"
                                            : "")
                                        . ($row["driver3"] ?
                                            "<br><img src='../results/flag/" . $drivers[$row['driver3']]['image'] . ".gif' />&nbsp;
                                            <a href='../results/statistics/lists/driver-wins.php?series=" . urlencode($sid) . "&driver=" . urlencode($row["driver3"]) . "'>" . $row["driver3"] . "</a>"
                                            : "")
                                        . ($row["driver4"] ?
                                            "<br><img src='../results/flag/" . $drivers[$row['driver4']]['image'] . ".gif' />&nbsp;
                                            <a href='../results/statistics/lists/driver-wins.php?series=" . urlencode($sid) . "&driver=" . urlencode($row["driver4"]) . "'>" . $row["driver4"] . "</a>"
                                            : "") .
                                        "</td>
                            <td>" . $row["Wins"] . "</td>
                            <td>" . $row["Races"] . "</td>
                            <td>" . $row["Percent"] . "</tr>";
                                }
                            } else {
                                echo "0 results";
                            }

                            mysqli_close($conn);

                            ?>

                        </tbody>
                    </table>

                    <br />

                </div>

            </div>

            <div class="td-pb-span4">

                <div class="td-ss-main-sidebar">

                    <div class="clearfix"></div>

                    <?php dynamic_sidebar('DB Summary'); ?>

                    <div class="clearfix"></div>

                </div>

            </div>

        </div>

    </div><!-- End of td-container div -->

    <?php get_footer(); ?>