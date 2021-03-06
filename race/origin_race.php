<?php require('../../wp-blog-header.php'); ?>

<?php require('../connection.php');
mysqli_set_charset($conn,"utf8");

$id = $_GET['id'];
$id = mysqli_real_escape_string($conn, $id);
$id2 = $id;

$sid = $_GET['series'];
$sid = mysqli_real_escape_string($conn, $sid);

$sql = "select case when cast(races.result as unsigned) = 0 then result else cast(races.result as unsigned) end as pos, races.number, races.class, races.driver, drivers.image as img, races.entrant, races.car, races.laps, races.time, races.best, races.qual, races.id from `drivers` INNER JOIN races on drivers.id = races.driver_id where races.race_id = '" . $id . "' and races.result > 0 order by id, pos asc";

$sqlnc = "select case when cast(races.result as unsigned) = 0 then result else cast(races.result as unsigned) end as pos, races.number, races.class, races.driver, drivers.image as img, races.entrant, races.car, races.laps, races.time, races.best, races.qual, races.id from `drivers` INNER JOIN races on drivers.id = races.driver_id where races.race_id = '" . $id . "' and races.result = 0 order by id, pos asc";

$sql2 = "select distinct track, year, date from races where race_id = '" . $id . "'";

$sql3 = "select distinct concat(year,series) as yrseries from races where driver = '" . $id . "' ";

$sqlcircuit = "SELECT distinct circuits.graphic_path, circuits.circuit from circuits LEFT JOIN races on races.track = circuits.configuration WHERE races.race_id = '" . $id . "'";

$sqlnotes = "SELECT distinct notes.note from notes LEFT JOIN races on races.race_id = notes.race_id WHERE races.race_id = '" . $id . "'";

$sqlprev = "SELECT distinct race_id FROM races where race_id = (select max(race_id) from races where race_id < '" . $id . "')";
$sqlnext = "SELECT distinct race_id FROM races where race_id = (select min(race_id) from races where race_id > '" . $id . "')";

$result = mysqli_query($conn, $sql);
$resultnc = mysqli_query($conn, $sqlnc);

// echo("Error description: " . mysqli_error($conn));

$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
$resultcircuit = mysqli_query($conn, $sqlcircuit);
$resultnotes = mysqli_query($conn, $sqlnotes);
$resultprev = mysqli_query($conn, $sqlprev);
$resultnext = mysqli_query($conn, $sqlnext);
// var_dump($result);
// var_dump($result2);
// var_dump($result3);



?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> &raquo; Database &raquo; <?php echo ucwords(strtolower($id)); ?> Race Wins</title>
<link rel="stylesheet/less" type="text/css" href="flex2.less" />
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.0.0/less.min.js" ></script>

<style>
.tb-row:nth-of-type(even) {
	background-color: #f7f7f7;
}
.tb-row:nth-of-type(even):hover {
	background-color: #d1d5ff;
}
.tb-row:nth-of-type(odd):not(.header):hover {
	background-color: #d1d5ff;
}
</style>

</head>

<?php get_header(); ?>

<body>
<div class="td-container">

	<div class="td-pb-row">
		
		<div class="td-pb-span12" style="border-right: 1px solid #E6E6E6;">

			<div class="td-ss-main-content">

				<div class="clearfix"></div>

				<a href='../../'><?php bloginfo('name'); ?></a> &raquo; Statistics &raquo; <?php echo ucwords(strtolower($id2)); ?> <?php echo ucwords($sid); ?> race results

				<div class="stats-div">
				
					<div class="container-fluid" style="margin-top: 10px">
					
					<h4 class="h4h"><?php if (mysqli_num_rows($result2) > 0) {while($row = mysqli_fetch_assoc($result2)) { echo $row["track"]; } } else {	echo "Unknown"; }	?>  result</h4>
					
						<div class="tb-row header">
							<div class="wrapper pos-nr-cl">
								<div class="column pos"><span class="circled">P</span></div>
								<div class="column nr"><span class="number">Nr</span></div>
								<div class="column cl">Cl</div>
							</div>
							<div class="wrapper driver-nat">
								<div class="column driver">Driver</div>
								<div class="column nat">Nat</div>
							</div>
							<div class="wrapper entrant-car">
								<div class="column entrant">Entrant</div>
								<div class="column car">Car</div>
							</div>
							<div class="wrapper laps-time-best-gd">
								<div class="wrapper laps-time">
									<div class="column laps">Lap</div>
									<div class="column time">Time</div>
								</div>
								<div class="wrapper best-gd">
									<div class="column best">Best</div>
									<div class="column gd">Gd</div>
								</div>
							</div>
						</div>
						
						<?php if (mysqli_num_rows($result) > 0) {
							// output data of each row
							while($row = mysqli_fetch_assoc($result)) {
								echo "<div class='tb-row'>
										<div class='wrapper pos-nr-cl'><div class='column pos'><span class='circled'>" . $row["pos"]. "</span></div><div class='column nr'><span class='number'>" . $row["number"]. "</span></div><div class='column cl'>" . (( $row["class"] == 'M' or $row["class"] == 'I') ? '<span class="spanclass">' : "" ) . $row["class"] . (( $row["class"] == 'M' or $row["class"] == 'I') ? '</span>' : "" ) . "</div></div>
										<div class='wrapper driver-nat'><div class='column driver'><a href='driver.php?name=" . $row["driver"] . "'>" . $row["driver"] . "</a></div><div class='column nat'><img src='../flag/" . $row["img"] . ".gif' /></div></div>
										<div class='wrapper entrant-car'><div class='column entrant'>" . $row["entrant"]. "</div><div class='column car'>" . $row["car"]. "</div></div>
										<div class='wrapper laps-time-best-gd'><div class='wrapper laps-time'><div class='column laps'>" . $row["laps"] . "</div><div class='column time'>" . $row["time"] . "</div></div><div class='wrapper best-gd'><div class='column best'>" . (( $row["best"] == 'Unknown') ? '' : $row["best"] ) . "</div><div class='column gd'>" . $row["qual"] . "</div></div></div>
									  </div>";
							}
							} else { echo "0 results"; }

						?>
						
						<div class="tb-row header">
							<div style="width: 100%;">Not classified</div>
						</div>
						
						<?php if (mysqli_num_rows($resultnc) > 0) {
							// output data of each row
							while($row = mysqli_fetch_assoc($resultnc)) {
								echo "<div class='tb-row'>
										<div class='wrapper pos-nr-cl'><div class='column pos'><span class='circled'>" . $row["pos"]. "</span></div><div class='column nr'><span class='number'>" . $row["number"]. "</span></div><div class='column cl'>" . (( $row["class"] == 'M' or $row["class"] == 'I') ? '<span class="spanclass">' : "" ) . $row["class"] . (( $row["class"] == 'M' or $row["class"] == 'I') ? '</span>' : "" ) . "</div></div>
										<div class='wrapper driver-nat'><div class='column driver'><a href='driver.php?name=" . $row["driver"] . "'>" . $row["driver"] . "</a></div><div class='column nat'><img src='../flag/" . $row["img"] . ".gif' /></div></div>
										<div class='wrapper entrant-car'><div class='column entrant'>" . $row["entrant"]. "</div><div class='column car'>" . $row["car"]. "</div></div>
										<div class='wrapper laps-time-best-gd'><div class='wrapper laps-time'><div class='column laps'>" . $row["laps"] . "</div><div class='column time'>" . $row["time"] . "</div></div><div class='wrapper best-gd'><div class='column best'>" . (( $row["best"] == 'Unknown') ? '' : $row["best"] ) . "</div><div class='column gd'>" . $row["qual"] . "</div></div></div>
									  </div>";
							}
							} else { echo "All entries were classified in the race."; }

							mysqli_close($conn);

						?>
						
						<br />

					</div>
					
				</div>
			
			</div>

		</div>
		
		<div class="td-pb-span6">
			<div class="td-ss-main-content">
				<div class="stats-div">
					<div class="container-fluid">
						<div class="tb-row header">
							<div style="width: 100%;">Circuit info</div>
						</div>
						<div style="width: 100%; float: left; display: flex; align-items: center; justify-content: center; padding-top: 5px;">
							<?php if (mysqli_num_rows($resultcircuit) > 0) {while($row = mysqli_fetch_assoc($resultcircuit)) { echo "<a href='/circuit-wins-list?track=" . $row["circuit"] . "'><img src=" . $row["graphic_path"] . " style='max-width: 350px; object-fit: cover; object-position: 30% 130%; width: 350px; height: 240px;' /></a>"; } } else {	echo ""; }	?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="td-pb-span6">
			<div class="td-ss-main-content">
				<div class="stats-div">
					<div class="container-fluid">
						<div class="tb-row header">
							<div style="width: 100%;">Notes</div>
						</div>
						<div style="width: 100%; padding-top: 5px;">
								<?php if (mysqli_num_rows($resultnotes) > 0) {while($row = mysqli_fetch_assoc($resultnotes)) { echo "<p>" . $row["note"] . "</p>"; } } else {	echo "No notes on this race."; }	?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="td-pb-span12">
			<div class="td-ss-main-content">
				<div class="stats-div">
					<div class="container-fluid">
						<div style="width: 100%; padding-bottom: 5px;">
							<?php if (mysqli_num_rows($resultprev) > 0) {while($row = mysqli_fetch_assoc($resultprev)) { echo "<span class='prevrace'><a href='race.php?id=" . $row["race_id"] . "'>Previous race</a></span>"; } } else {	echo ""; }	?>
							<?php if (mysqli_num_rows($resultnext) > 0) {while($row = mysqli_fetch_assoc($resultnext)) { echo "<span class='nextrace'><a href='race.php?id=" . $row["race_id"] . "'>Next race</a></span>"; } } else {	echo ""; }	?>
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	</div>
	
</div><!-- End of td-container div -->

<?php get_footer(); ?>