<?php

function zeigen($var)
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

/*	Diese Funktion durchsucht ein Verzeichnis und speichert die Dateinamen in einem Array
	Funktion benötigt 2 Parameter,
	1. Pfadangabe des durchsuchenden Verzeichnisses
	2. Variablenname in der das Array abgelegt wird.	*/
function wpi_archiv_lesen($arg, &$arg2)
{
	$dateien = scandir($arg);
	unset($dateien['0']);
	unset($dateien['1']);
	$dateien = array_values($dateien);

	$arg2 = $dateien;
	$dateienzahl = count($dateien);
	echo '<p>- Das archiv wurde eingelesen<br />';
	echo '- <em>Es wurden ' . $dateienzahl . ' Dateien gefunden</em></p><hr>';
	return $arg2;
}

/*	Diese Funktion entpackt Zip-Dateien in eins zuvor definiertest Verzeichnis
	Als Parameter wird die Varibale mit den entpackenden Archiven erwartet. 	*/
function wpi_archiv_entpacken($arg3)
{
	$zip_archive = get_option('wpi_xml_pfad');
	$zip = new ZipArchive;
	$xmls = array('.xml', '.XML', '.Xml');
	$xml_file = 'objekt.xml';
	if ($zip->open($zip_archive . $arg3) === TRUE) {
		$zip->extractTo(WPI_TEMP_DIR); //Hier der Pfad zu Temp-Ordner
		$zip->close();
		// Entpackte Dateien nach XML suchen
		// Nur als Zwischenlösung bei ein und dem selben Namen der XML-Datei
		$dateien = scandir(WPI_TEMP_DIR);
		chdir(WPI_TEMP_DIR);
		// XMLs filtern
		foreach ($xmls as $key => $xml){
			foreach ($dateien as $item) {
				if (strrpos($item, $xml)) {
					$xml_file = $item;
					if ($xml_file = rename($xml_file, microtime() . '.xml')):
						echo 'XML-Datei wurde umbenannt.';
					endif;
				}
			}
		}

		// Entpacktes Archiv aus dem Array entfernen
		unlink($zip_archive . $arg3);
		echo 'Archiv wurde entpackt<br />';
		// Prüfen ob weitere Zip-Archive vorhanden sind, ggf. entpacken.
		foreach($dateien as $key => $value){
			if( $fund = strrpos($value, '.zip') ){
				$zip->open(WPI_TEMP_DIR.$value);
				$zip->extractTo(WPI_TEMP_DIR);
				$zip->close();

				// Entpackte Dateien nach XML suchen
				// Nur als Zwischenlösung bei ein und dem selben Namen der XML-Datei
				$dateien = scandir(WPI_TEMP_DIR);
				//$dateien = array_map('strtolower', array_values($dateien));
				chdir(WPI_TEMP_DIR);

				// XMLs filtern
				foreach ($xmls as $key => $xml){
					foreach ($dateien as $item) {
						if (strrpos($item, $xml)) {
							$xml_file = $item;
							if ($xml_file = rename($xml_file, microtime() . '.xml')):
								echo 'XML-Datei wurde umbenannt.';
							endif;
						}
					}
				}

				unlink(WPI_TEMP_DIR.$value);
			}
		}
	} else {
		exit('Beim Entpacken ist ein Fehler aufgetreten <br />');
	}
}


/*Hauptfunktion: Entpacken des Archivs, Auslesen der XML-Dateinamen und in einem Array speichern*/
// Archivordner wird ausgelesen
function wpi_xml_auslesen()
{
	$xml_pfad = get_option('wpi_xml_pfad');
	$endung = '.xml';                // Dateiendung um nach Dateien zu suchen
	$xmlfiles = array();            // Array nur mit XML-Dateien
	$tmp = WPI_TEMP_DIR;
	$uploads = get_option('wpi_upload_pfad');

	wpi_archiv_lesen($xml_pfad, $zip_array); // Zip-Dateinamen in ein Array einlesen

// Das Array wird in einem Temp-Ordner entpackt
	foreach ($zip_array as $index => $name) {
		wpi_archiv_entpacken($name);
	}
	echo 'Archive erfolgreich entpackt ';

// Dateien aus dem Temp-Ordner einlesen
	wpi_archiv_lesen(WPI_TEMP_DIR, $dateinamen);


// Nur XML-Dateien filtern
	$anzahl_xml = 0;
	$offset = 0;
	foreach ($dateinamen as $index1 => $name1) {
		$gefunden = strrpos($name1, $endung);
		if ($gefunden != FALSE) {
			$anzahl_xml = $anzahl_xml + 1;
			$xmlfiles[] = $name1;
			$offset = $gefunden + 1;
			$gefunden = strrpos($name1, $endung, $offset);
		}
	}
	echo "Davon " . $anzahl_xml . " XML-Datei(en)<br />";
	$arg4 = $xmlfiles;
// Nur Mediendateien filtern
	$imgfiles = array();
	$media_endung = array(
		'jpg'   => '.jpg',
		'JPG'   => '.JPG',
		'Jpg'   => '.Jpg',
		'jpeg'  => '.jpeg',
		'JPEG'  => '.JPEG',
		'Jpeg'  => '.Jpeg',
		'png'   => '.png',
		'PNG'   => '.PNG',
		'Png'   => '.Png',
		'pdf'   => '.pdf',
		'PDF'   => '.PDF',
		'Pdf'   => '.Pdf'
	);
	$anzahl_medien = 0;
	$offset1 = 0;
	foreach ($media_endung as $typ => $dat_typ) {
		foreach ($dateinamen as $index2 => $name2) {
			$media_fund = strrpos($name2, $dat_typ);
			if ($media_fund != FALSE) {
				$anzahl_medien = $anzahl_medien + 1;
				$imgfiles[] = $name2;
				$offset1 = $media_fund + 1;
				$media_fund = strrpos($name2, $dat_typ, $offset1);
			}
		}
	}
	echo 'Und ' . $anzahl_medien . ' Mediendateien<br />';

	foreach ($imgfiles as $index3 => $imgname) {

		rename($tmp . $imgname, $uploads . strtolower($imgname));

	}
	print count($imgfiles) . ' Medien wurden in den Uploads Ordner verschoben<br /><hr>';

	return $arg4;
}

// Funktion Dateien aus einem Ordner löschen
function wpi_deleteFilesFromDirectory($ordnername)
{
	//überprüfen ob das Verzeichnis überhaupt existiert
	if (is_dir($ordnername)) {
		//Ordner öffnen zur weiteren Bearbeitung
		if ($dh = opendir($ordnername)) {
			//Schleife, bis alle Files im Verzeichnis ausgelesen wurden
			while (($file = readdir($dh)) !== false) {
				//Oft werden auch die Standardordner . und .. ausgelesen, diese sollen ignoriert werden
				if ($file != "." AND $file != "..") {
					//Files vom Server entfernen
					unlink("" . $ordnername . "" . $file . "");
				}
			}
			//geöffnetes Verzeichnis wieder schließen
			closedir($dh);
		}
	}
}