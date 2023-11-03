<h1>Demo using ogdbPLZnearby2.lib.php (circle shaped area search)</h1>
<?php 

require_once("ogdbPLZnearby2.lib.php");
// mixed ogdbPLZnearby ( integer $zip , integer $distance , boolean $getName , boolean $getDist)
// USAGE: ogdbPLZnearby('[ZIP]','[Distance (km)]', [Get City Names], [Get Distance (km)])

// Example 1: Returns zipcodes only
echo "<h2>Demo call: ogdbPLZnearby('04683','10') &ndash; returning zips only</h2>";
echo '<pre>';
print_r(ogdbPLZnearby('04683','10'));
echo '</pre>';

// Example 2: Returns zipcodes and city names
echo "<h2>Demo call: ogdbPLZnearby('04683','10',true) &ndash; returning zips and city names</h2>";
echo '<pre>';
print_r(ogdbPLZnearby('04683','10',true));
echo '</pre>';

// Example 3: Returns zipcodes, city names and distance
echo "<h2>Demo call: ogdbPLZnearby('04683','10',true,true) &ndash; returning zips, city names and distance</h2>";
echo '<pre>';
print_r(ogdbPLZnearby('04683','10',true,true));
echo '</pre>';

?>

