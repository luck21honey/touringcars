<?php
define('WP_USE_THEMES', false);
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// DB connection 
require('../results/connection.php');
mysqli_set_charset($conn, "utf8");

/**
 * Get all series
 */
$all_races = [];
$all_races_sql = "SELECT r.series, r.year, s.title
                FROM (SELECT series, `year`
                FROM races
                GROUP BY series, `year`
                ORDER BY `year` DESC, `date`) r
                LEFT JOIN series s
                ON r.series=s.code";
$all_races_query_result = mysqli_query($conn, $all_races_sql);
while ($row = mysqli_fetch_assoc($all_races_query_result)) {
    $all_races[$row['series']][] = [$row['year'], $row['title']];
}



/**
 * Get latest results
 */
$latest_results = [];
$latest_sql = "SELECT DATE_FORMAT(races.`date`, '%d %b') AS dd, races.`series`, circuits.`circuit`, races.`round`, races.`race_id`
FROM races
INNER JOIN circuits
ON races.`track` = circuits.`configuration`
GROUP BY races.`race_id`
ORDER BY CAST(races.`race_id` AS UNSIGNED) DESC
LIMIT 0, 5";
$latest_query_result = mysqli_query($conn, $latest_sql);
if (mysqli_num_rows($latest_query_result)) {
    while ($row = mysqli_fetch_assoc($latest_query_result)) {
        $latest_results[] = $row;
    }
}


/**
 * Get count of latest results
 */
$count_of_latest_results = 0;
$latest_count_sql = "SELECT COUNT(rr.date) AS latest_cnt
                FROM (
                SELECT *
                FROM races
                GROUP BY races.`date`
                ) rr";
$latest_count_query_result = mysqli_query($conn, $latest_count_sql);
if (mysqli_num_rows($latest_query_result)) {
    while ($row = mysqli_fetch_assoc($latest_count_query_result)) {
        $count_of_latest_results = $row['latest_cnt'];
    }
}



/**
 * Get upcoming results
 */
$upcoming_races = [];
$upcoming_sql = "SELECT circuits.`code`, DATE_FORMAT(event.`date`, '%d %b') AS dd, event.`series`, event.`round`, circuits.`circuit`
                FROM `event`
                INNER JOIN circuits
                ON circuits.`configuration` = event.`circuit`
                WHERE event.`date` > CURRENT_DATE()
                ORDER BY event.`date`
                LIMIT 0, 5";
$upcoming_query_result = mysqli_query($conn, $upcoming_sql);
if (mysqli_num_rows($upcoming_query_result)) {
    while ($row = mysqli_fetch_assoc($upcoming_query_result)) {
        $upcoming_races[] = $row;
    }
}


/**
 * Get count of upcoming results
 */
$count_of_upcoming_results = 0;
$upcoming_count_sql = "SELECT COUNT(ee.date) AS upcoming_cnt
                        FROM (
                        SELECT *
                        FROM `event`
                        WHERE event.`date` > CURRENT_DATE()) ee";
$upcoming_count_query_result = mysqli_query($conn, $upcoming_count_sql);
if (mysqli_num_rows($upcoming_count_query_result)) {
    while ($row = mysqli_fetch_assoc($upcoming_count_query_result)) {
        $count_of_upcoming_results = $row['upcoming_cnt'];
    }
}

/**
 * Get random result
 */
$random_data = [];
$random_sql = "select distinct race_id from races order by rand() limit 1";
$random_query_result = mysqli_query($conn, $random_sql);
while ($row = mysqli_fetch_assoc($random_query_result)) {
    $random_data[$row['race_id']];
}


/**
 * Get footer data (unique series from series table, year)
 */
$footer_data = [];
$footer_sql = "SELECT r.series, r.year, s.title
                FROM (SELECT series, `year`
                FROM races
                GROUP BY series, `year`
                ORDER BY series, `year`) r
                LEFT JOIN series s
                ON r.series=s.code";
$footer_query_result = mysqli_query($conn, $footer_sql);
while ($row = mysqli_fetch_assoc($footer_query_result)) {
    $footer_data[$row['series']][] = [$row['title'], $row['year']];
}

// echo count($footer_data);exit;
// echo "<pre>";
// var_dump($footer_data);
// echo "</pre>";
// exit;



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php bloginfo('name'); ?> &raquo; Results</title>
    <!-- <script src="/results/tablesorter/js/jquery-latest.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">

</head>

<style>
    .series_name {
        cursor: pointer;
    }

    .series_name:hover {
        color: #F1545A;
    }

    .custom-card {
        box-shadow: 0 0px 4px 0 rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgb(0 0 0 / 0%);
        padding: 10px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .custom-card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, .2), 0 6px 20px 0 rgba(0, 0, 0, .19);
        /* box-shadow: 0 0px 4px 0 rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgb(0 0 0 / 0%); */
        cursor: pointer;
    }

    .custom-list {
        margin-left: 12px;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
    }

    .load-more {
        text-align: center;
    }

    hr {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .td-pb-span6::after {
        clear: both;
    }
</style>


<?php get_header(); ?>

<div class="td-container-wrap" style="padding: 20px;">
    <div class="td-pb-row">
        <div class="td-pb-span12">
            <div class="td-post-header td-pb-padding-side">
                <ul class="td-category">
                    <li class='entry-category'><a href='/'><?php bloginfo('name'); ?></a></li>
                    <li class='entry-category'><a href='/results/season.php'>Results</a></li>
                </ul>

                <div class="td_block_wrap tdb_title tdi_78_07e tdb-single-title td-pb-border-top td_block_template_1" style="margin-bottom: 0px;">
                    <div class="tdb-block-inner td-fix-index">
                        <h1 class='tdb-title-text' style="font-family: Oxygen; font-size: 32px; font-weight: 800;">Database</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="td-pb-row">
        <div class="td-pb-span8 td-main-content">
            <div class="td-ss-main-content">
                <div class="td-post-content" style="display:flex;">
                    <div style="width: 50%; margin-right: 10px;">
                        <?php
                        $i = 0;
                        foreach ($all_races as $key => $values) {
                            $i++; ?>

                            <?php if ($i % 2 == 0) {
                                $all_races_string = "";
                                foreach ($values as $item) {
                                    $all_races_string .= "<div class='footeryear'><a href='season.php?series=" . urlencode($key) . "&year=" . $item[0] . "' style='line-height: 16px;'>" . $item[0] . "</a></div> ";
                                } ?>
                                <div class="custom-card">
                                    <div class="qualifying">
                                        <h1><span title="<?php echo $values[0][1]; ?>" class="series_name"><?php echo $key; ?></span></h1>
                                        <span class="qual">
                                            <?php echo rtrim($all_races_string, " - "); ?>
                                        </span>
                                    </div>

                                    <div class="qualifying">
                                        <span class="qual">
                                            <a href="driver-wins.php?series=<?php echo urlencode($key); ?>" style="line-height: 16px;">Most driver wins</a>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php
                        } ?>
                    </div>

                    <div style="width: 50%;">
                        <?php
                        $i = 0;
                        foreach ($all_races as $key => $values) {
                            $i++; ?>

                            <?php if ($i % 2 != 0) {
                                $all_races_string = "";
                                foreach ($values as $item) {
                                    $all_races_string .= "<div class='footeryear'><a href='season.php?series=" . urlencode($key) . "&year=" . $item[0] . "' style='line-height: 16px;'>" . $item[0] . "</a></div> ";
                                } ?>
                                <div class="custom-card">
                                    <div class="qualifying">
                                        <h1><span title="<?php echo $values[0][1]; ?>" class="series_name"><?php echo $key; ?></span></h1>
                                        <span class="qual">
                                            <?php echo rtrim($all_races_string, " - "); ?>
                                        </span>
                                    </div>

                                    <div class="qualifying">
                                        <span class="qual">
                                            <a href="driver-wins.php?series=<?php echo urlencode($key); ?>" style="line-height: 16px;">Most driver wins</a>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php
                        } ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="td-pb-span4 td-main-sidebar td-pb-border-top" style="margin-top: 21px;" role="complementary">
            <div class="td-ss-main-sidebar">
                <aside class="widget widget_meta custom-sidebar">
                    <div class="block-title">
                        <span style="font-size: 14px !important;">Latest Results</span>
                    </div>

                    <div id="latest_results_area" style="padding-right: 5px;">
                        <?php
                        for ($i = 0; $i < count($latest_results); $i++) { ?>
                            <div class="table-row" style="margin-bottom: 10px;">
                                <div class="custom-list">
                                    <div>
                                        <a href="race.php?id=<?php echo $latest_results[$i]['race_id']; ?>">
                                            <span><?php echo  $latest_results[$i]['dd']; ?></span>
                                            &nbsp; - &nbsp;
                                            <span><?php echo  $latest_results[$i]['series']; ?></span>
                                            &nbsp; - &nbsp;
                                            <span><?php echo  $latest_results[$i]['circuit']; ?></span>
                                            &nbsp;
                                            <span>Round <?php echo  $latest_results[$i]['round']; ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php }
                        ?>
                    </div>

                    <div class="table-row" style="margin-bottom: 10px;" id="latest_load_more">
                        <div class="load-more">
                            <button class="btn btn-primary" id="latest_results_more"> Load more</button>
                            <button class="btn btn-primary" id="latest_results_more_loading" style="display: none;" disabled>
                                <span class="spinner-border spinner-border-sm"></span>
                                Loading..
                            </button>
                        </div>
                    </div>

                </aside>

                <?php dynamic_sidebar('HomeS1'); ?>
            </div>


            <div class="td-ss-main-sidebar">
                <aside class="widget widget_meta custom-sidebar">
                    <div class="block-title">
                        <span style="font-size: 14px !important;">Upcoming races</span>
                    </div>

                    <div id="upcoming_races_area" style="padding-right: 5px;">
                        <?php
                        for ($i = 0; $i < count($upcoming_races); $i++) { ?>
                            <div class="table-row" style="margin-bottom: 10px;">
                                <div class="custom-list">
                                    <span><img src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/results/flag/<?php echo $upcoming_races[$i]['code']; ?>.gif"></span>
                                    &nbsp;
                                    <span><?php echo  $upcoming_races[$i]['dd']; ?></span>
                                    &nbsp; - &nbsp;
                                    <span><?php echo  $upcoming_races[$i]['series']; ?></span>
                                    &nbsp; - &nbsp;
                                    <span><?php echo  $upcoming_races[$i]['circuit']; ?></span>
                                    &nbsp;
                                    <span>Round <?php echo  $upcoming_races[$i]['round']; ?></span>
                                </div>
                            </div>
                            <?php
                            if (count($upcoming_races) > 1) { ?>
                                <hr>
                        <?php }
                        }
                        ?>
                    </div>


                    <div class="table-row" style="margin-bottom: 10px;" id="upcoming_load_more">
                        <div class="load-more">
                            <button class="btn btn-primary" id="upcoming_races_more"> Load more</button>
                            <button class="btn btn-primary" id="upcoming_races_more_loading" style="display: none;" disabled>
                                <span class="spinner-border spinner-border-sm"></span>
                                Loading..
                            </button>
                        </div>
                    </div>
                </aside>

                <?php dynamic_sidebar('HomeS1'); ?>
            </div>

            <div class="td-ss-main-sidebar">
                <aside class="widget widget_meta custom-sidebar">
                    <div class="block-title">
                        <span style="font-size: 14px !important;">Random result</span>
                    </div>

                    <div style="padding-right: 5px;">
                        <div class="table-row" style="margin-bottom: 10px;">
                            <div class="custom-list">
                                <span>Random result</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <?php dynamic_sidebar('HomeS1'); ?>
            </div>


        </div>
    </div>

    <div class="td-pb-row">
        <div class="td-pb-span12">
            <div class="custom-footer">
                <div class="block-title" style="margin-bottom: 0px;">
                    <span>Browse results by season</span>
                </div>
                <div class="td-post-content" style="margin-top: 0px;">
                    <?php
                    $i = 0;
                    foreach ($footer_data as $key => $values) {
                        echo "<div class='seriesgroup'><b>" . $values[0][0] . ': &nbsp;&nbsp;</b>';
                        $string = '';
                        echo "";
                        foreach ($values as $value) {
                            $string .= "<div class='footeryear'><a href='" . get_option('home') . "/database/season.php?series=" . $key . "&year=" . $value[1] . "'>" . $value[1] . "</a></div> ";
                        }
                        echo rtrim($string, "-");
                        echo "</div>";
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var latest_results_from = 0;
    var upcoming_races_from = 0;
    var count_of_latest_results = <?php echo $count_of_latest_results; ?>;
    var count_of_upcoming_results = <?php echo $count_of_upcoming_results; ?>;

    // console.log('count of latest results >>>', count_of_latest_results)
    // console.log('count of upcoming results >>>', count_of_upcoming_results)

    $(document).ready(function() {
        if (count_of_latest_results <= latest_results_from) {
            $("#latest_load_more").hide();
        }
        if (count_of_upcoming_results <= latest_results_from) {
            $("#upcoming_load_more").hide();
        }
    });

    $("#latest_results_more").click(function() {
        latest_results_from += 5;
        $("#latest_results_more").hide();
        $("#latest_results_more_loading").show();

        $.ajax({
            url: "/database/latest_results_load_more.php",
            type: "post",
            dataType: 'json',
            data: {
                from: latest_results_from
            },
            success: function(data) {
                // console.log('latest ajax return result>>', data);
                $("#latest_results_area").last().append(data);
                $("#latest_results_more_loading").hide();
                $("#latest_results_more").show();
                if (count_of_latest_results <= latest_results_from + 5) {
                    $("#latest_load_more").hide();
                }
            }
        });
    });

    $("#upcoming_races_more").click(function() {
        upcoming_races_from += 5;
        $("#upcoming_races_more").hide();
        $("#upcoming_races_more_loading").show();

        $.ajax({
            url: "/database/upcoming_races_load_more.php",
            type: "post",
            dataType: 'json',
            data: {
                from: upcoming_races_from
            },
            success: function(data) {
                // console.log('upcoming ajax return result>>', data);
                $("#upcoming_races_area").last().append(data);
                $("#upcoming_races_more_loading").hide();
                $("#upcoming_races_more").show();
                if (count_of_upcoming_results <= upcoming_races_from + 5) {
                    $("#upcoming_load_more").hide();
                }
            }
        });
    });
</script>

<?php get_footer(); ?>