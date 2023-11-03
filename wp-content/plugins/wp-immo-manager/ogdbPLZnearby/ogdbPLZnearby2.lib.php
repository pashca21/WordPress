<?php
/*
*	ogdbPLZnearby2.lib
*	Author:				André Herdling (mail@andreherdling.de)
*	Based on:			ogdbDistance.lib.php by Manuel Hoppe (m.hoppe@hoppe-media.net)
*	Date / Revision:	2014-06-06
*	License:			Public Domain
*
*	Thanks to Manuel Hoppe for ogdbDistance.lib.php (http://opengeodb.org/wiki/Datei:OgdbDistance.zip)
*	and OpenGeoDB for providing the data (zipcodes and coordinates). Great work!
*	Read more about OpenGeoDB and interesting usecases on http://opengeodb.org
*  
*	Differences to version 1:
*	Version 1 of this library defines a square shaped area around the origin and searches all zips (PLZ)
*	inside this area. That was good enough but not really accurate.
*	In version 2 we no calculate the distance between the zips based on ogdbDistance.lib.php from Manuel Hoppe
*	(see credits above). This needs a bit more computing time but makes the results even better.
*
*	Final thoughts:
*	I'm not a developer, if you have questions – ask them and i will try to answer.
*	If you can make this script better, faster, accurate or whatever ... just do it ;-)
*/

if (version_compare(PHP_VERSION, "5.0.0", "<")) {
    die("ABBRUCH: es wird PHP5 oder hoeher benoetigt.\n"
        . " -> http://de.php.net/downloads.php\n");
}
define("OGDB_LOCAL_DATA_FILE", WPI_PLUGIN_DIR . "ogdbPLZnearby/PLZ.tab");

function ogdbPLZnearby($origin, $distance, $getName = false, $getDist = false)
{

    $fileData = @file_get_contents(OGDB_LOCAL_DATA_FILE);
    if ($fileData == FALSE) {
        die("ABBRUCH: konnte Daten nicht laden (" . OGDB_LOCAL_DATA_FILE . ")\n");
    }
    $distance = intval($distance);
    $fileData = explode("\n", $fileData);

    /* STEP 1: Loop through the data, search for PLZ,
       transform coordinates to RAD
    */
    for ($i = 1; $i < count($fileData); $i++) {
        $fileRow = explode("\t", $fileData[$i]);
        if ($origin == @$fileRow[1]) {
            $origin_lon = deg2rad($fileRow[2]);
            $origin_lat = deg2rad($fileRow[3]);

        }

    };
    /* STEP 2: Loop through the data again, calculate the distance from origin for each item
       and store matching items into array
    */
    $offset = 0;
    for ($i = 1; $i < count($fileData); $i++) {
        $fileRow = explode("\t", $fileData[$i]);
        $destination_lon = deg2rad(@$fileRow[2]);
        $destination_lat = deg2rad(@$fileRow[3]);

        //distance between origin and destination
        $distance_org_dest = acos(sin($destination_lat) * sin($origin_lat) + cos($destination_lat) * cos($origin_lat) * cos($destination_lon - $origin_lon)) * 6375;
        $distance_org_dest = round($distance_org_dest);

        if ($distance_org_dest <= $distance) {
            if ($getName OR $getDist) {
                $returnvalue[$offset]['zip'] = $fileRow[1];
                if ($getName) {
                    $returnvalue[$offset]['city'] = $fileRow[4];
                };
                if ($getDist) {
                    $returnvalue[$offset]['dist'] = $distance_org_dest;
                };
            } else {
                $returnvalue[$offset] = $fileRow[1];
            }

            $offset++;
        }

    }
    return $returnvalue;
}

