<?php
/*
*	ogdbPLZnearby.lib
*	Author:				André Herdling (mail@andreherdling.de)
*	Based on:			ogdbDistance.lib.php by Manuel Hoppe (m.hoppe@hoppe-media.net)
*	Date / Revision:	2013-11-05
*	License:			Public Domain
*
*	Thanks to Manuel Hoppe for ogdbDistance.lib.php (http://opengeodb.org/wiki/Datei:OgdbDistance.zip)
*	and OpenGeoDB for providing the data (zipcodes and coordinates). Great work!
*	Read more about OpenGeoDB and interesting usecases on http://opengeodb.org
*
*	Final thoughts:
*	I'm not a developer, if you have questions – ask them and i will try to answer.
*	If you can make this script better, faster, accurate or whatever ... just do it ;-)
*/ 

if ( version_compare(PHP_VERSION,"5.0.0","<") ) {
	die("ABBRUCH: es wird PHP5 oder hoeher benoetigt.\n"
		." -> http://de.php.net/downloads.php\n");
}

define("OGDB_REMOTE_DATA_FILE","http://fa-technik.adfc.de/code/opengeodb/PLZ.tab");
define("OGDB_LOCAL_DATA_FILE","./PLZ.tab");

function ogdbPLZnearby($origin,$distance,$getName=false) {
	if ( !is_file(OGDB_LOCAL_DATA_FILE) || filesize(OGDB_LOCAL_DATA_FILE)==0 ) {
		$fileData = file_get_contents(OGDB_REMOTE_DATA_FILE);
		if ( $fileData == FALSE ) {
			die("ABBRUCH: konnte Daten nicht laden (".OGDB_REMOTE_DATA_FILE.")\n");
		}
		if ( file_put_contents(OGDB_LOCAL_DATA_FILE,$fileData) == FALSE ) {
			die("ABBRUCH: konnte Daten nicht speichern (".OGDB_LOCAL_DATA_FILE.")\n");
		}
		unset($fileData);
	}
	$fileData = @file_get_contents(OGDB_LOCAL_DATA_FILE);
	if ( $fileData == FALSE ) {
		die("ABBRUCH: konnte Daten nicht laden (".OGDB_LOCAL_DATA_FILE.")\n");
	}
	error_reporting(1);
	$distance = intval($distance);
	$distance = $distance / 6378; //6378 = Earth radius in km
	$fileData = explode("\n",$fileData);
	
	/* STEP 1: Loop through the data, search for PLZ, 
	   transform coordinates to RAD and define MIN and MAX 
	   for other coordinates.
	*/
	for ( $i=1; $i < count($fileData); $i++ ) {
		$fileRow = explode("\t",$fileData[$i]);
		if ( $origin == $fileRow[1] ) {
			$origin_lon = deg2rad($fileRow[2]);
			$origin_lat = deg2rad($fileRow[3]);
			
			//defining MIN and MAX
			$maxCoord_lon = $origin_lon + $distance;
			$maxCoord_lat = $origin_lat + $distance;
			$minCoord_lon = $origin_lon - $distance;
			$minCoord_lat = $origin_lat - $distance;
		}
		
	};
	/* STEP 2: Loop through the data again and search for entries
	   where the coordinates are between MIN and MAX.	
	*/
	$offset = 0;
	for ( $i=1; $i < count($fileData); $i++ ) {
		$fileRow = explode("\t",$fileData[$i]);
		if ($maxCoord_lon >= deg2rad($fileRow[2]) AND
			$minCoord_lon <= deg2rad($fileRow[2]) AND  
			$maxCoord_lat >= deg2rad($fileRow[3]) AND
			$minCoord_lat <= deg2rad($fileRow[3])){
			//$getNames = TRUE (array containing PLZ and city names)
			if($getName){
				$returnvalue[$offset]['zip'] = $fileRow[1];
				$returnvalue[$offset]['city'] = $fileRow[4];
			} 
			//$getNames = FALSE (array containing PLZ only)
			else {
				$returnvalue[$offset] = $fileRow[1];
			}
			$offset++;
		}
	
	}	
	return $returnvalue;
}

