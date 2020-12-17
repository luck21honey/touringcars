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

$driver_sql = "SELECT s.driver, s.wins, s.cnt, ROUND(s.wins/s.cnt*100, 1) AS percent, d.image
FROM
	(SELECT r.driver, r.driver_id,
	SUM(CASE WHEN r.year='2001' AND r.class_pos='1' AND r.class='' THEN 1 ELSE 0 END)+SUM(CASE WHEN r.year!='2001' AND r.result='1' AND r.class != 'P' THEN 1 ELSE 0 END) AS wins,
	COUNT(r.driver) AS cnt
	FROM races r
	WHERE r.series='{$sid}' AND r.driver_id != 0
	GROUP BY r.driver) s, drivers d
WHERE s.driver_id=d.id
ORDER BY s.wins DESC";

$driver2_sql = "SELECT s.driver2 AS driver, s.wins, s.cnt, ROUND(s.wins/s.cnt*100, 1) AS percent, d.image
FROM
	(SELECT r.driver2, r.driver_id2,
	SUM(CASE WHEN r.year='2001' AND r.class_pos='1' AND r.class='' THEN 1 ELSE 0 END)+SUM(CASE WHEN r.year!='2001' AND r.result='1' AND r.class != 'P' THEN 1 ELSE 0 END) AS wins,
	COUNT(r.driver2) AS cnt
	FROM races r
	WHERE r.series='{$sid}' AND driver_id2 != 0
	GROUP BY r.driver2) s, drivers d
WHERE s.driver_id2=d.id
ORDER BY s.wins DESC";

$driver3_sql = "SELECT s.driver3 AS driver, s.wins, s.cnt, ROUND(s.wins/s.cnt*100, 1) AS percent, d.image
FROM
	(SELECT r.driver3, r.driver_id3,
	SUM(CASE WHEN r.year='2001' AND r.class_pos='1' AND r.class='' THEN 1 ELSE 0 END)+SUM(CASE WHEN r.year!='2001' AND r.result='1' AND r.class != 'P' THEN 1 ELSE 0 END) AS wins,
	COUNT(r.driver3) AS cnt
	FROM races r
	WHERE r.series='{$sid}' AND driver_id3 != 0
	GROUP BY r.driver3) s, drivers d
WHERE s.driver_id3=d.id
ORDER BY s.wins DESC";

$driver4_sql = "SELECT s.driver4 AS driver, s.wins, s.cnt, ROUND(s.wins/s.cnt*100, 1) AS percent, d.image
FROM
	(SELECT r.driver4, r.driver_id4,
	SUM(CASE WHEN r.year='2001' AND r.class_pos='1' AND r.class='' THEN 1 ELSE 0 END)+SUM(CASE WHEN r.year!='2001' AND r.result='1' AND r.class != 'P' THEN 1 ELSE 0 END) AS wins,
	COUNT(r.driver4) AS cnt
	FROM races r
	WHERE r.series='{$sid}' AND driver_id4 != 0
	GROUP BY r.driver4) s, drivers d
WHERE s.driver_id4=d.id
ORDER BY s.wins DESC";

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


$driver_result = mysqli_query($conn, $driver_sql);
$driver2_result = mysqli_query($conn, $driver2_sql);
$driver3_result = mysqli_query($conn, $driver3_sql);
$driver4_result = mysqli_query($conn, $driver4_sql);
$result2 = mysqli_query($conn, $sql2);

$driver1 = [];
while ($row = mysqli_fetch_assoc($driver_result)) {
    $driver1[] = $row;
}
$driver2 = [];
while ($row = mysqli_fetch_assoc($driver2_result)) {
    $driver2[$row['driver']][] = $row;
}
$driver3 = [];
while ($row = mysqli_fetch_assoc($driver3_result)) {
    $driver3[$row['driver']][] = $row;
}
$driver4 = [];
while ($row = mysqli_fetch_assoc($driver4_result)) {
    $driver4[$row['driver']][] = $row;
}

// var_dump($driver1[0]['driver']);exit;
// var_dump($driver4['Geoff KIMBER-SMITH'][0]['driver']); exit;

$driver_wins = [];
for ($i = 0; $i < count($driver1); $i++) {
    $driver = $driver1[$i]['driver'];
    $updated_wins = $driver1[$i]['wins'];
    $updated_cnt = $driver1[$i]['cnt'];

    if (array_key_exists($driver, $driver2)) {
        if ($updated_wins < $driver2[$driver][0]['wins'])
            $updated_wins = $driver2[$driver][0]['wins'];
        if ($updated_wins < $driver2[$driver][0]['cnt'])
            $updated_cnt = $driver2[$driver][0]['cnt'];
    }

    if (array_key_exists($driver, $driver3)) {
        if ($updated_wins < $driver3[$driver][0]['wins'])
            $updated_wins = $driver3[$driver][0]['wins'];
        if ($updated_wins < $driver3[$driver][0]['cnt'])
            $updated_cnt = $driver3[$driver][0]['cnt'];
    }

    if (array_key_exists($driver, $driver4)) {
        if ($updated_wins < $driver4[$driver][0]['wins'])
            $updated_wins = $driver4[$driver][0]['wins'];
        if ($updated_wins < $driver4[$driver][0]['cnt'])
            $updated_cnt = $driver4[$driver][0]['cnt'];
    }

    $updated_percent = round(($updated_wins / $updated_cnt) * 100, 1);
    $driver_img = $driver1[$i]['image'];

    if ($updated_wins > 0)
        $driver_wins[] = [$driver, $updated_wins, $updated_cnt, $updated_percent, $driver_img];
}
// var_dump($driver_wins);
// exit;

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
                                    // $driver_wins[] = [$driver, $updated_wins, $updated_cnt, $updated_percent, $driver_img];
                                    echo "<tr>
                            <td class='rownums'></td>
                            <td>
                                <img src='../results/flag/" . $row[4] . ".gif' />&nbsp;
                                <a href='../results/statistics/lists/driver-wins.php?series=" . urlencode($sid) . "&driver=" . urlencode($row[0]) . "'>" . $row[0] . "</a>
                            </td>
                            <td>" . $row[1] . "</td>
                            <td>" . $row[2] . "</td>
                            <td>" . $row[3] . "</tr>";
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