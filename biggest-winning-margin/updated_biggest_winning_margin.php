<?php
define('WP_USE_THEMES', false);
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// DB connection 
require('../results/connection.php');
mysqli_set_charset($conn, "utf8");

$meta_value = rwmb_meta('prefix-text_1');

$sid = $_GET['series'];
$sid = mysqli_real_escape_string($conn, $sid);

$id = mysqli_real_escape_string($conn, $meta_value);
$id2 = $id;

if (empty($_GET)) {
    // $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin LIMIT 25";
    $sql = "SELECT r.*, c.layout AS trvar
            FROM (SELECT series AS cship, `year` AS yr, `round` AS rd, `driver` AS pilot, track, `car` AS vehicle, `race_id`, ROUND(((`time` + (`id` / 100000000)) * 100000000),0) AS `id`
                FROM races
                WHERE result=1) r
            LEFT JOIN circuits c
            ON c.configuration=r.`track`
            ORDER BY r.`yr` DESC, r.`rd` DESC";
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races";
    // $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE result = '" . $id . "'";
} else {
    // $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin WHERE series in ('" . $sid . "') LIMIT 25";
    $sql = "SELECT r.*, c.layout AS trvar
            FROM (SELECT series AS cship, `year` AS yr, `round` AS rd, `driver` AS pilot, track, `car` AS vehicle, `race_id`, ROUND(((`time` + (`id` / 100000000)) * 100000000),0) AS `id`
                FROM races
                WHERE result=1) r
            LEFT JOIN circuits c
            ON c.configuration=r.`track`
            ORDER BY r.`yr` DESC, r.`rd` DESC";
    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE `series` in ('" . $sid . "')";
    // $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE `Series` in ('" . $sid . "') and result = '" . $id . "'";
}

$sql3 = "SELECT race_id, ROUND((`time` + 0),3) AS `timed`
FROM races
WHERE result=2";


$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);

$res3 = [];
while($row=mysqli_fetch_assoc($result3)) {
    $res3[$row['race_id']] = $row['timed'];
}

$res = [];
while($row=mysqli_fetch_assoc($result)) {
    $res[] = array('cship' => $row['cship'],'yr' => $row['yr'],'rd' => $row['rd'],'pilot' => $row['pilot'],'track' => $row['track'],'vehicle' => $row['vehicle'],'id' => $row['id'],'trvar' => $row['trvar'],'timed' => $res3[$row['race_id']]);
}

$timed = array_column($res, 'timed');
array_multisort($timed, SORT_DESC, $res);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php bloginfo('name'); ?> &raquo; Biggest winning margin</title>
</head>

<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.0.0/less.min.js"></script>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet/less" type="text/css" href="./flex-ages.less" />
<style type="text/css">
    .h4h {
        margin-left: 10px;
    }

    .tb-row:nth-of-type(even) {
        background-color: #f7f7f7;
    }

    .tb-row:nth-of-type(even):hover {
        background-color: #d1d5ff;
    }

    .tb-row:nth-of-type(odd):not(.header):hover {
        background-color: #d1d5ff;
    }

    .tb-row.header {
        font-weight: bold !important;
    }

    .stats-div {
        width: 100%;
        padding: 5px;
        font-size: 13px;
    }

    .stats-table {
        width: 100%;
    }

    .stats-table img {
        display: inline;
    }

    .show_more_main {
        margin: 15px 25px;
    }

    .show_more {
        background-color: #f8f8f8;
        background-image: -webkit-linear-gradient(top, #fcfcfc 0, #f8f8f8 100%);
        background-image: linear-gradient(top, #fcfcfc 0, #f8f8f8 100%);
        border: 1px solid;
        border-color: #d3d3d3;
        color: #333;
        font-size: 12px;
        outline: 0;
        cursor: pointer;
        display: block;
        padding: 10px 0;
        text-align: center;
        font-weight: bold;
    }

    .loding {
        background-color: #e9e9e9;
        border: 1px solid;
        border-color: #c6c6c6;
        color: #333;
        font-size: 12px;
        display: block;
        text-align: center;
        padding: 10px 0;
        outline: 0;
        font-weight: bold;
    }

    .loding_txt {
        background-image: url('<?php echo get_template_directory_uri(); ?>/images/loading.gif');
        background-position: left;
        background-repeat: no-repeat;
        border: 0;
        display: inline-block;
        height: 16px;
        padding-left: 20px;
    }

    .smore-tr {
        border-bottom: none !important;
    }

    .smore-tr:hover {
        background-color: #FFFFFF !important;
    }

    body {
        counter-reset: my-sec-counter;
    }

    .rownums::before {
        counter-increment: my-sec-counter;
        content: counter(my-sec-counter);
    }
</style>

<?php get_header(); ?>

<div class="td-container">
    <div class="td-container-border wpb_content_element" style="border-left: 1px solid #E5E5E5;">

        <div class='block-title' style='margin-left: 0px;'>
            <span>Biggest winning margin</span>
        </div>

        <p>&nbsp;&nbsp;<em>Note: Data valid for period between <?php if (mysqli_num_rows($result2) > 0) {
                                                                    while ($row = mysqli_fetch_assoc($result2)) {
                                                                        echo $row["mindate"] . " and " . $row["maxdate"];
                                                                    }
                                                                } else {
                                                                    echo "0 results";
                                                                }    ?></em></p>

        <aside class='widget widget_meta'>
            <ul>
                <li>
                    <span class="circuit"><a href="/list-of-biggest-winning-margins">ALL</a></span>
                    <span class="circuit"><a href="?series=WTCC">WTCC</a></span>
                    <span class="circuit"><a href="?series=WTCR">WTCR</a></span>
                    <span class="circuit"><a href="?series=BTCC">BTCC</a></span>
                    <span class="circuit"><a href="?series=DTM">DTM</a></span>
                    <span class="circuit"><a href="?series=STCC">STCC</a></span>
                    <span class="circuit"><a href="?series=TCR EU">TCR Europe</a></span>
                    <span class="circuit"><a href="?series=TCR DE">TCR Germany</a></span>
                    <span class="circuit"><a href="?series=TCR IT">TCR Italy</a></span>
                    <span class="circuit"><a href="?series=TCR UK">TCR UK</a></span>
                    <span class="circuit"><a href="?series=TCR Asia">TCR Asia</a></span>
                    <span class="circuit"><a href="?series=TCR Intl">TCR International</a></span>
                    <span class="circuit"><a href="?series=WC TCR">Pirelli World Challenge TCR</a></span>
                    <span class="circuit"><a href="?series=ETCC">ETCC</a></span>
                    <span class="circuit"><a href="?series=ETC Cup">ETC Cup</a></span>
                    <span class="circuit"><a href="?series=STW Cup">STW Cup</a></span>
                </li>
            </ul>
        </aside>

        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Filter on table contents..." title="Type in a name">

        <div class="stats-div" id="stats1">

            <div class="container-fluid" style="margin-top: 10px; padding: 0px;">
                <div class="tb-row header">
                    <div class="wrapper text-0">
                        <div class="wrapper text-0">
                            <div class="text-series">Rank</div>
                            <div class="text-series">Series</div>
                            <div class="text-year">Year</div>
                        </div>
                    </div>
                    <div class="wrapper text-2">
                        <div class="wrapper text-2">
                            <div class="text-layout">Rd</div>
                            <div class="text-driver">Driver</div>
                        </div>
                    </div>
                    <div class="wrapper text-2">
                        <div class="wrapper text-2">
                            <div class="text-entrant">Circuit</div>
                            <div class="text-car"><em>Car</em></div>
                        </div>
                    </div>
                    <div class="wrapper text-4">
                        <div class="wrapper text-4">
                            <div class="text-time">Margin (secs)</div>
                        </div>
                    </div>
                </div>
                <div class="postList">

                    <?php

                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        for ($i = 0; $i < 25; $i++) {
                            $circuitID = $res[$i]['id'];
                            echo "
                                    <div class='tb-row'>
                                        <div class='wrapper text-0'>
                                            <div class='wrapper text-0'>
                                                <div class='text-series rownums'></div>
                                                <div class='text-series'>
                                                    " . (($res[$i]["cship"] == 'STW Cup') ? 'STW' : $res[$i]["cship"]) . "
                                                </div>
                                                <div class='text-year'>
                                                    " . $res[$i]["yr"] . "
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-2'>
                                            <div class='wrapper text-2'>
                                                <div class='text-layout' title='" . $res[$i]["trvar"] . "'>
                                                    " . $res[$i]["rd"] . "
                                                </div>
                                                <div class='text-driver'>
                                                    <a href='/results/statistics/lists/driver-wins.php?series=" . $res[$i]["cship"] . "&driver=" . $res[$i]["pilot"] . "'>" . $res[$i]["pilot"] . "</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-2'>
                                            <div class='wrapper text-2'>
                                                <div class='text-entrant' title='" . $res[$i]["trvar"] . "'>
                                                    " . mb_strimwidth($res[$i]["trvar"], 0, 30, "..") . "
                                                </div>
                                                <div class='text-car'title='" . $res[$i]["vehicle"] . "'>
                                                    <em>" . mb_strimwidth($res[$i]["vehicle"], 0, 27, "...") . "</em>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-4'>
                                            <div class='wrapper text-4'>
                                                <div class='text-time'>
                                                    " . $res[$i]["timed"] . "
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
                    ?>
                        <tr class="smore-tr">
                            <td colspan="7">
                                <div class="show_more_main" id="show_more_main<?php echo $circuitID; ?>">
                                    <span id="<?php echo $circuitID; ?>" class="show_more" title="Load more posts">Show more</span>
                                    <span class="loding" style="display: none;"><span class="loding_txt">Loading...</span></span>
                                </div>
                            </td>
                        </tr>
                    <?php } else {
                        echo "0 results";
                    }

                    mysqli_close($conn);

                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="td-container">
    <div class="td-container-border">
        <div class="vc_row wpb_row td-pb-row">
            <div class="wpb_column vc_column_container td-pb-span12">
                <div class="wpb_wrapper">
                    <div class="wpb_text_column wpb_content_element ">
                        <div class="wpb_wrapper">
                            <p><em>Tip: Filter on the database results by entering free text into the search box at the top.</em>
                                <br />
                                TouringCars.Net contains the ultimate statistical record of touring car racing on the internet.
                                <br />
                                <br />
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- Responsive header -->
                                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3615539307566661" data-ad-slot="8677277793" data-ad-format="auto"></ins>
                                <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function myFunction() {
        // Declare variables 
        var input, filter, table, tr, td, i, occurrence;

        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("stats1");
        tr = table.getElementsByClassName("tb-row");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) {
            occurrence = false; // Only reset to false once per row.
            td = tr[i].getElementsByClassName("wrapper");
            for (var j = 1; j < td.length; j++) {
                currentTd = td[j];
                if (currentTd) {
                    if (currentTd.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        occurrence = true;
                    }
                }
            }
            if (!occurrence) {
                tr[i].style.display = "none";
            } else {
                tr[i].style.display = "";
            }
        }
    }

    jQuery(document).ready(function() {
        jQuery(document).on('click', '.show_more', function() {
            var ID = jQuery(this).attr('id');
            jQuery('.show_more').hide();
            jQuery('.loding').show();
            jQuery.ajax({
                type: 'POST',
                url: '<?php if (!empty($sid)) {
                            echo '/ajax_more-margin-biggest.php?series=' . $sid;
                        } else {
                            echo '/ajax_more-margin-biggest.php';
                        } ?>',
                data: 'id=' + ID,
                success: function(html) {
                    jQuery('#show_more_main' + ID).remove();
                    jQuery('.postList').append(html);
                }
            });
        });
    });
</script>

<?php
get_footer();
