<?php require $_SERVER['DOCUMENT_ROOT'] . ('/wp-blog-header.php');

// DB connection 
require('../results/connection.php');
mysqli_set_charset($conn, "utf8");

/**
 * Get all series
 */
$all_races = [];
$all_races_sql = "SELECT series, `year`
                        FROM races
                        GROUP BY series, `year`
                        ORDER BY series, `year` DESC";
$all_races_query_result = mysqli_query($conn, $all_races_sql);
while ($row = mysqli_fetch_assoc($all_races_query_result)) {
    $all_races[$row['series']][] = $row['year'];
}



/**
 * Get latest results
 */
$latest_results = [];
$latest_sql = "SELECT DATE_FORMAT(races.`date`, '%d %b') AS dd, races.`series`, circuits.`circuit`, races.`round`
            FROM races
            INNER JOIN circuits
            ON races.`track` = circuits.`configuration`
            GROUP BY races.`date`
            ORDER BY races.`date` DESC
            LIMIT 0, 5";
$latest_query_result = mysqli_query($conn, $latest_sql);
if (mysqli_num_rows($latest_query_result)) {
    while ($row = mysqli_fetch_assoc($latest_query_result)) {
        $latest_results[] = $row;
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
    <link rel="stylesheet" href="index.css">
</head>

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
                <div class="td-post-content">

                    <?php
                    foreach ($all_races as $key => $values) {
                        $all_races_string = "";
                        foreach ($values as $item) {
                            $all_races_string .= "<a href='season.php?series=" . urlencode($key) . "&year=" . $item . "' style='line-height: 16px;'>" . $item . "</a> - ";
                        } ?>
                        <div class="td-pb-span6">
                            <div class="custom-card">
                                <h1><?php echo $key; ?></h1>
                                <p class="qualifying">
                                    <span class="qual">
                                        <?php echo rtrim($all_races_string, " - "); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php
                    } ?>

                </div>
            </div>
        </div>

        <div class="td-pb-span4 td-main-sidebar td-pb-border-top" style="padding-right: 40px; margin-top: 21px; padding-bottom: 16px;" role="complementary">
            <div class="td-ss-main-sidebar">
                <aside class="widget widget_meta custom-sidebar">
                    <div class="block-title">
                        <span>Latest Results</span>
                    </div>

                    <?php
                    for ($i = 0; $i < count($latest_results); $i++) { ?>
                        <div class="table-row" style="margin-bottom: 10px;">
                            <div class="custom-list">
                                <div>
                                    <span><?php echo  $latest_results[$i]['dd']; ?></span>
                                    &nbsp; - &nbsp;
                                    <span><?php echo  $latest_results[$i]['series']; ?></span>
                                    &nbsp; - &nbsp;
                                    <span><?php echo  $latest_results[$i]['circuit']; ?></span>
                                    &nbsp;
                                    <span>Round <?php echo  $latest_results[$i]['round']; ?></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php }
                    ?>

                    <div class="table-row" style="margin-bottom: 10px;">
                        <div class='standings-topten'><button class="primary"> Load more</button></div>
                    </div>

                </aside>

                <?php dynamic_sidebar('HomeS1'); ?>
            </div>


            <div class="td-ss-main-sidebar">
                <aside class="widget widget_meta custom-sidebar">
                    <div class="block-title">
                        <span>Upcoming races</span>
                    </div>

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

                    <div class="table-row" style="margin-bottom: 10px;">
                        <div class='standings-topten'><button class="primary"> Load more</button></div>
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
<?php get_footer(); ?>