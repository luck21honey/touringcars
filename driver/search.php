<?php
// $servername = "db603417003.db.1and1.com";
// $username = "dbo603417003";
// $password = "Tourenw4gen1!";
// $dbname = "db603417003";

$servername = "localhost:3306";
$username = "tcn_results";
$password = "BtccWtcr2020!";
$dbname = "tcn_results";

$key = $_GET['key'];
$array = array();
$conc = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$query = mysqli_query($conc, "select * from drivers where driver LIKE '%{$key}%'");
while ($row = mysqli_fetch_assoc($query)) {
  // $array[] = $row['driver'];
  $array[] = "<a href='driver.php?name=" . urlencode($row['driver']) . "'>" . $row['driver'] . "</a>";
}
echo json_encode($array);
mysqli_close($conc);
