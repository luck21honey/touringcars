<?php require $_SERVER['DOCUMENT_ROOT'] . ('/wp-blog-header.php');

// DB connection 
require('../results/connection.php');
mysqli_set_charset($conn, "utf8");


// Get params
$series = $_GET['series'];
$series = mysqli_real_escape_string($conn, $series);
$year = $_GET['year'];

/**
 * Get title
 */
$title_sql = "SELECT `code`, title FROM series WHERE `code`='" . $series . "'";
$title_query_result = mysqli_query($conn, $title_sql);
while ($row = mysqli_fetch_assoc($title_query_result)) {
    $series_title = $row['title'];
}


include('./get_column_header.php');
include('./get_drivers_data.php');
include('./get_touring_data.php');
include('./get_saturday_data.php'); //For TCR Japan
include('./get_independent_data.php');
include('./get_privateers_data.php');
include('./get_trophy_data.php');
include('./get_amateurs_data.php');
include('./get_production_data.php');
include('./get_classB_data.php');

?>


<!DOCTYPE html>

<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php bloginfo('name'); ?> &raquo; Results &raquo; British Touring Car Championship &raquo; <?php echo $year; ?> &raquo; Championship Standings</title>

    <script src="../results/js/Chart.bundle.js"></script>
</head>

<?php get_header(); ?>

<div class="td-container-wrap" style="padding: 20px;">

    <a href='../../../'><?php bloginfo('name'); ?></a> &raquo; <a href='../../index.php'>Results</a> &raquo; <?php echo $series_title; ?> &raquo; <a href='season.php?series=<?php echo $series; ?>&year=<?php echo $year; ?>'><?php echo $year; ?></a> &raquo; Championship Standings<br /><br />

    <!-- First table : Drivers | Touring -->

    <table border="0" width="100%">
        <tr>
            <td width='50%'>
				<div class="td_block_wrap tdb_title tdi_78_07e tdb-single-title td-pb-border-top td_block_template_1" style="margin-bottom: 0px;">
					<div class="tdb-block-inner td-fix-index">
						<?php
						if (count($drivers_data)) { ?>
							<h1 class='tdb-title-text' style="font-family: Oxygen; font-size: 32px; font-weight: 800;"><?php echo $year . ' ' . $driver_classification; ?>' Standings</h1>
						<?php } else if (count($touring_data)) { ?>
							<h1 class='tdb-title-text' style="font-family: Oxygen; font-size: 32px; font-weight: 800;"><?php echo $year . ' ' . $touring_classification; ?> Drivers' Standings</h1>
						<?php } else { ?>
							<h1 class='tdb-title-text' style="font-family: Oxygen; font-size: 32px; font-weight: 800;"><?php echo $year . ' ' . $saturday_classification; ?>Series Standings</h1>
						<?php } ?>
					</div>
				</div>
            </td>
            <td width="50%" align="right"></td>
        </tr>
    </table>

    The table below displays race finishing positions. Key: R (Retired), NC (Not classified), NS (Did not start), EX (Excluded), DQ (Disqualified).<br />

    <?php
    if (count($drivers_data)) {
        include('./drivers_standing_table.php');
    } else if (count($touring_data)) {
        include('./touring_standing_table.php');
	} else {
        include('./saturday_standing_table.php');
    }
    ?>


    <!-- Second table : Independent | Privateers | Trophy | Amateurs -->

    <?php
    if (count($independent_data)) { ?>
        <h2><?php echo $year . ' ' . $independent_classification; ?>'s Standings</h2>
    <?php include('./independent_standing_table.php');
    } else if (count($privateers_data)) { ?>
        <h2><?php echo $year . ' ' . $privateers_classification; ?>' Standings</h2>
    <?php include('./privateers_standing_table.php');
    } else if (count($trophy_data)) { ?>
        <h2><?php echo $year . ' ' . $trophy_classification; ?>' Standings</h2>
    <?php include('./trophy_standing_table.php');
    } else if (count($amateurs_data)) { ?>
        <h2><?php echo $year . ' ' . $amateurs_classification; ?>' Standings</h2>
    <?php include('./amateurs_standing_table.php');
    } ?>


    <!-- Third table : Production | Class B -->

    <?php
    if (count($production_data)) { ?>
        <h2><?php echo $year . ' ' . $production_classification; ?>'s Standings</h2>
    <?php include('./production_standing_table.php');
    } else if (count($classB_data)) { ?>
        <h2><?php echo $year . ' ' . $classB_classification; ?> Standings</h2>
    <?php include('./classB_standing_table.php');
    }  ?>


</div>

<?php get_footer(); ?>