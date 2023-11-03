# CHANGELOG HISTORY

## Version - v3
= 3.079.2 =
- svg images support in template Artur Voll 5 minutes ago
- show all immo in search filter result by default without parameters sets Artur Voll Yesterday 19:31
- advancedUmkreis search added to cookie Artur Voll Yesterday 19:21
- einbauküche in ausstattung Artur Voll Yesterday 19:03
- advancedSearch filter added to cookie Artur Voll Yesterday 19:02
- filter false an 0 values on help_handle_array in MetaHelperController.php Artur Voll 06.11.22, 23:25
-
= 3.079.1 =
- vendor/http-client-contracts dependency &update .gitignore Artur Voll 18.10.22, 07:48
- image exists checks for the first 3 images Artur Voll 18.10.22, 07:35
-
= 3.079.0 =
- added symfony vendors Artur Voll 16.09.22, 20:47
- symfony/polyfill-php80 added Artur Voll 16.09.22, 20:39
-
= 3.078.0 =
- fixed warnings in MetaHelperController.php Artur Voll 16.09.22, 19:21
- fixed php8 errors Artur Voll 16.09.22, 19:03
- update vendors to php 8 Artur Voll 16.09.22, 17:02
- update .gitignore Artur Voll 16.09.22, 17:01
- FIX: SortBy Filter query issues with CF7 Artur Voll 10.06.22, 22:22
- NEW: added body_class to immo if the meta sold exists Artur Voll 27.05.22, 19:17
- FIX: changed Html2Pdf for reading SVG Files Artur Voll 13.05.22, 15:39
- FIX: removed Energybar from the PDF Artur Voll 12.05.22, 17:28
- FIX: parentesis in DownloadFilter method Artur Voll 10.03.22, 21:17
- FIX: php version to 7.4 in composer.json Artur Voll 10.03.22, 21:15
- FIX: Objektadresse freigeben with string 1 Artur Voll 10.03.22, 21:14
- FIX: to show .svg files in the img tag Artur Voll 07.03.22, 21:59

= 3.077 =
* NEW: checked WP 3.9.1 Compatibility
* UPDATE: Shortcode App as a iframe
* FIX: deprecated if else construct
* FIX: removed a string from [immobilien] shortcode

= 3.076 =
* UPDATE: Back to list Button changed to window.history.back
* FIX: back-to-list-row on .home disabled
* FIX: flexslider nav arrows full visible
* FIX: sort-by-bar-row breakpoints

= 3.075 =
* UPDATE: PHP Version in Composer.json auf min 7.3
* FIX: StoreRequest.php changed wpurl with admin_url for compatibility to networks and multisite
* NEW: new option to set the single slug as title
* NEW: Anzahl Objekte x von y im ListContainer

= 3.074 =
- FIX: PDF Generierung unter PHP 7.4

= 3.073 =
- NEW: AdvancedUmkreis Vue Component
- NEW: AdvancedUmkreisRepository
- NEW: AdvancedUmkreis Api
- FIX: Loading Spinner for Advanced Search
- UPDATE: Hardfacts Icons for Gastraumfläche and Gesamtfläche.
- UPDATE: Template für TaxonomieLinks geändert

= 3.072 =
- FIX: enqueue bootstrap.js to FancyGalleryClass.php

= 3.071 =
- FIX: height and width of fancy-gallery fixed
- FIX: if not exists imgStr() in FancyGalleryDefault.php

= 3.070 =
- NEW: Fancy Gallery Class, View and Scss
- NEW: Fancy Gallery admin-option in select added

= 3.062 =

- testet up to Wp 5.7.1
----
* FIX: Handle Unit bei Heizkosten
----
* FIX: Flaticons für Gewerbefläche und Anzahl Wohneinheiten
----
* FIX: added title,description,alt_meta to attachment
----
* FIX: Resize Image fixing
----
* FIX: fixed empty preise meta
----
* FIX: if-else fix bei Remote Anhängen
----
* NEW: Metakeys für Flächen erweitert
* NEW: Meatkeys für Kontaktperson erweitert
----
* NEW: "wpi_allow_delete" filter to block deleting from a functions.php
* FIX: extract objektart_zusatz to taxonomies and tags
* FIX: trim stripslashes HTML on pdf expose, fixed some issues with height of header and footer
* FIX: stripslashes HTML on the frontend
* FIX: removed ob_get_contents from ListContainer.php
* FIX: fixed bug in the accordion Template
* FIX: Anzahl Zimmer etc. als FLOAT z.B. 1,5
* FIX: Updated Maps Iframe Link
----
* FIX: extract objektart_zusatz to taxonomies and tags
* FIX: trim stripslashes HTML on pdf expose, fixed some issues with height of header and footer
* FIX: stripslashes HTML on the frontend
* FIX: removed ob_get_contents from ListContainer.php
* FIX: fixed bug in the accordion Template
* FIX: Anzahl Zimmer etc. als FLOAT z.B. 1,5
* FIX: Updated Maps Iframe Link

= 3.061 =
* NEW: "wpi_allow_delete" filter to block deleting from a functions.php
* FIX: extract objektart_zusatz to taxonomies and tags
* FIX: trim stripslashes HTML on pdf expose, fixed some issues with height of header and footer
* FIX: stripslashes HTML on the frontend
* FIX: removed ob_get_contents from ListContainer.php
* FIX: fixed bug in the accordion Template
* FIX: Anzahl Zimmer etc. als FLOAT z.B. 1,5
* FIX: Updated Maps Iframe Link

= 3.060 =
* UPDATE: AdvancedSearch in separate js dateien ausgelagert, umstellung auf range statt select im formular
* UPDATE: innen_courtage als Käuferprovision, Kautionshinweise zu preisen hinzugefügt
* FIX: stripslashes on textareas for custom_css custom_html, pdf_head and pdf_footer

= 3.059.9 =
* NEW: Anhanggruppe LINKS hinzugefügt
* NEW: orte zum Immmobilien-Shortcode hinzugefügt
* FIX: check HTTP_REFERRER
* UPDATE: kompatibilität für jQuery3 und WP 5.5
* UPDATE: Stylings für search_advanced angepasst

= 3.059.6 =
* NEW: Advanced SearchFilter Form Shortcode
* UPDATE: MetaHelperController.php, OptionsController.php, ComponentsController.php -> use static instance
* FIX: MetaHelperController.php -> is_price_zero match arrays with attributes
* FIX: Umkreissuche Errors fix

= 3.059.4 =
* UPDATE: isPriceHidden & isPriceZero modified
* UPDATE: hide Hardfacts by meta sold

= 3.059.3 =
* UPDATE: MetaKeys.php -> Gesamtfläche, Bürofläche, Büroteilfläche und Teilbar_ab hinzugefügt.
* UPDATE: MetaHelperController.php -> Gesamtfläche, Bürofläche, Büroteilfläche und Teilbar_ab zu help_handle_unit() hinzugefügt.
* NEW: SmartNavigation on Settings und Frontend.
* NEW: Neues Template für Referenzen
* NEW: Shortcodebuilder as Web-Component added
* FIX: Bug behebung bei weniger als 3 Bilder im Template!

= 3.059.2 =
* FIX: UnzipDataController.php->unzipFile() checks if the xml without zip added.
* FIX: CreatePostController.php OpenimmoXML foreach Anbieter in the Array.
* FIX: CreatePostController.php OpenimmoXML foreach Anbieter in the Array.
* FIX: MetaHelperController.php - is_array_assoc changed to public

= 3.059.0 =
* FIX: Bei Preis 0 -> text "Auf Anfrage"
* FIX: 0 werte bei ausstattung werden jetzt rausgefiltert
* FIX: Bug in ReferrerModel.php

= 3.058.0 =
* New: Neues Attribut objekt_id beim [immobilien...] Shortcode
* New: Erzeugung von WordPress Thumbnail über Settings abschaltbar
* New: Vollabgleich über Settings einstellbar
* New: Thumbnail Größe über Settings einstellbar
* New: Filter: "wpi_allow_delete_by_vollabgleich" und funktion DeleteAll() in CreatePostController hinzugefügt.
* New: Backend Immobilien Page wurde Objektnummer und Schlagwörter  zur Tabelle hinzugefügt und Author entfernt.
* New: support of post_tags to the post_type
* New: nutzungsart add as post_tag by create and update a property
* FIX: Some Errors fixed

= 3.056.9 =
* New: Shortcode [search_filter_result] hinzugefügt, falls Probleme mit Content Filter anwenden

= 3.056.8 =
* FIX: Thumbnailsgröße in ListViewImage.php angepasst
* FIX: Abfrage ob Thumbnail tatsächlich ein Image_file ist

= 3.056.5 =
* BUG: building sold meta-tag bei Import
* New: Immobilien Shortcode hat 2 neue Attribute bekommen mit Meta und ohne

= 3.056 =
* New: Video-Container hinzugefügt

= 3.054 =
* New: Energybar für Epass hinzugefügt

= 3.052 =
* New: Frei ab Feld hinzugefügt

= 3.05 =
- New: PDF Export Funktion Fixed
- New: List Image Count into Immo-List
- New: Number of Logs over Settings
- New: Number of Old-Zips over Settings

= 3.043 =
- New: PDF Export Funktion
- New: ListImageCount in SCSS added
- Fixed: Check Umfang der Übertragung
- Fixed: Check Dateformat of Energiepass

= 3.042 =
- Fixed: OnepageTemplate was not rendered

= 3.041 =
- Fixed: Log Output on UnzipDataController and CreatePostController

= 3.040 =
- Fixed: $xmlstring in CreatePostController.php

= 3.039 =
- Fixed: Name of PLZ Source bootstrap.php

== Upgrade Notice ==

= 3.056 =
* New: Video-Container hinzugefügt

= 3.038 =
- New: Add Immo via MVC
- New: Add and Delete of Immo and MetaData via MVC
- Fixed: Licence Check Bug
- Fixed: Bug in Widgets
- Fixed: CSS of Widgets
- View of Custom Template in the Child-Theme too now
- ObjectNumber in all List-Templates availeble

= 3.037 =
- Fixed: ResizeImage only when Image greather and is Image
- Fixed: View street in the map and onlyPlace-component if this is allowed

= 3.036 =
- NEW: Cron Schedule Time of 15 mins and 30 mins added.

= 3.035 =
- Fixed: ResizeImage only when Image greather

= 3.034 =
- Fixed: Error in the ActionController

= 3.032 =
- Fixed: Energieausweisdaten in MetaHelperController
- Fixed: Ausstattung in MetaHelperController
= 3.033 =
- New: New Component for Freitext Meta

= 3.031 =
- Sitename added to Referrer Button

= 3.0 =
- RollOut new extended Version (Release)


## Version - v2

= 2.3.3 =
- fixing to php8
- Wordpress 6.3 test

= 2.3.1 =
- Wordpress 5.8.1 support und test

= 2.3 =
- Anpassungen an jQuery 3 und WP 5.5

= 2.2.9 =
- Link to the Settings Dashboard fixed

= 2.2.8 =
- Wordpress 5.4 compatibility

= 2.2.7 =
- Anpassung zu WP 5.1
- Admin-Notice v3
- Kaufpreis auch bei Wert 0
- Nettokaltmiete zu Meta hinzugefügt

= 2.2.4.2 =
- Routing zu Taxonomies angepasst

= 2.2.4.1 =
- Bugs bei search_filter_form und umkreissuche_form behoben

= 2.2.4 =
- Bei Mietobjekten wurde ein Vermietet-Label hinzugefügt
- Verkauft / Vermietet Labels auch zur Single-View hinzugefügt
- Bei Referenzobjekten kann der Preis ausgeblendet werden
- Bei Zustand und Ausstattung der Immobilie die Values mit Umlauten gefiltert
- Bei Archiv-Query Loop übergabe mehrerer Objekttypen / Vermarktungsarten Kommasepariert möglich
- Bei Archiv-Query Loop wurde Parameter "exclude=taxonomie-slug,slug1" hinzugefügt
- Autoloader für PHP-Klassen hinzugefügt
- User Validation via Version V2

= 2.2.3 =
- Vermarktungsart und Objekttyp mit true und 1 in wpi_create_posts.php
- nl2br-function on Post and Freitexte added.
- main.js fixed

= 2.2.2 =
- Bei Auswahl Objektadresse auf false, wird diese nicht mehr in Eckdaten angezeigt.
- Kontakt Felder sind jetzt auswählbar
- Checkboxen im Admin der Preise, Flächen, Hardfacts und Kontakt-Felder über JS auswählbar
- Bugs:
- Beim Abschalten der Bootstrap Styles wurde die bootstrap.js im Admin nicht mit abgeschaltet

= 2.2.1 =
- Bug in der wpi_create_posts.php behoben
- Bei einem Abgleich der Immobilien wurde eine Immobilie mehrfach erfasst.

= 2.2 =
- V 2.2 ist ein Major Update
- überarbeitete Namespacess
- Autoloader zum laden der Klassen eingefügt
- Zuordnung der Immobilien nicht mehr nach Titel sondern nach Objektnummer

= 2.1.9 =
- BUG in wpi_shedules behoben...


= 2.1.8 =
- Newsletter-Form für Free User integriert
- Kompatibilität zu WP 4.7.6

= 2.1.7 =
- BUG: Admin Files wurden bei SSL gesicherten verbindungen nicht geladen.
- Templates können jetzt im Uploads-Ordner abgelegt werden als Custom Templates

= 2.1.6 =
- get_image_thumbnails() Methode zu der Single-Class hinzugefügt.

= 2.1.5 =
- Bug in den Plugin-Settings für Single-Ansicht behoben...

= 2.1.4 =
- Bug in dem Query Shortcode behoben
- Die Und Verknüpfung von 2 Immobiliengruppen hat nicht funktioniert.

= 2.1.3 =
- Immobiliengruppen wurden hinzugefügt
- Ein Thumbnails-Template zu den Listen hinzugefügt.
- Query-Shortcode entsprechend angepasst, dass die Immobiliengruppen hier verwendet werden können.
- Immobilien-Widget entsprechend angepasst, dass die Immobiliengruppen hier verwendet werden können.


= 2.1.2 =
- Währungseinheiten zu den Hardfacts und Eckdaten hinzugefügt

= 2.1.1 =
- SidebarTemplate angepasst.
- Query-Shortcode um Style-Parameter erweitert

= 2.1.0 =
- SidebarTemplate hinzugefügt
- Admin Pages geändert

= 2.0.9.4 =
- Widget Umkreissuche hinzugefügt
- Widget Search-Filter hinzugefügt
- Widget Immobilien-Loop hinzugefügt
- Shortcode "Immobilien" um Parameter ID erweitert
- Post-Type Immobilien hat 2 neue Spalten im Edit-View für ID und Shortcode bekommen.

= 2.0.9.3 =
- Bugfix im Tabs-Template - Anzeige des Energiepasses war nicht möglich.

= 2.0.9.2 =
- Bugfix behoben - Deinstallation des Plugins war nicht möglich

= 2.0.9.1 =
- Bugfix behoben - Scrollen des Sliders in Single-View
- Bugfix behoben - Punkt am Ende der Taxonomien in der Listen-View

= 2.0.9 =
- Anpassungen an Wordpress 4.7
- Search-Filter integriert


= 2.0.8 =
- Objektorientierte Aufteilung Options
- Objektorientierte Aufteiung Admin Functions
- Objektorientierte Aufteilung Single Page Functions
- One Page Single Template
- Weitere Einstellmöglichkeiten im Admin Bereich

= 2.0.7 =
- Kompatibilität zu Wordpress 4.6
Bugfixes
- Bei manchen Übertragungen wird bei Bildern kein Anhangtitel übertragen,
  dadurch wurden die Bilder im Template nicht angezeigt. Wurde hiermit gefixt.
- Auf mehrfachen Wunsch wurden die Glyphicons im Titel der Immobilien ganz entfernt.

= 2.0.6 =
- Option im Backend für Title wurde ein Bug behoben
- Optionen für Immobilie "Verkauft" und "Reserviert" hinzugefügt.
- Diese Optionen in den Listen-Ansichten mit eingefügt.

= 2.0.5 =
- Energieausweisdaten überarbeitet
- Texte für Energieausweis in Backend anpassbar
- Neue Preisfelder zu Liste und Single eingefügt
- Option im Backend für Titel der Immobilien hinzugefügt.

= 2.0.4 =
- Custom HTML Textarea eingefügt
- Tab-Single-View für Multilanguage vorbereitet
- help_handle_array bei anhang um weitere Bildformate erweitert.
- PLZ / Ort bei Listenansicht hinzugefügt
- Tausenderpunkt und Nachkommastellen bei Preise und Flächen ergänzt

= 2.0.3 =
- Bei Übertragung eines einzigen Anhangs wurden die Bilder nicht angezeigt, wurde gefixt.

= 2.0.2 =
- immo-navigation bei Firefox und IE ohne Funktion, wurde gefixt.
- search-form war bei single_immobilie fehlerhaft, wurde gefixt.
- Fehler in der Umkreissuche gefixt.

= 2.0.0 =
- Erkennung der XML überarbeitet
- Die Meta Daten werden jetzt als Arrays gespeichert
- Singleansicht wurde in view_single_tabs verlagert
- Singleansicht view_single_accordion hinzugefügt
- Die Auswahl zwischen den Anzeigemodi kann in der Admin-Page eingestellt werden.
- Listenansicht wurde in die view_list_openimmo verlagert
- Listenansicht view_list_columns hinzugefügt
- Die Auswahl zwischen den Anzeigemodi kann in der Admin-Page eingestellt werden.
- Ein Excerpt-Filter für die Suchanfragen wurde hinzugefügt.
- mehrere Bugs behoben

= 1.0.7.1 =
- Beim Shortcode Immobilien wurde eine Ansicht bei nicht vorhanden der Immobilien hinzugefügt.

= 1.0.7 =
- Shortcode "Immobilien" hinzugefügt.
- Eingabefeld für Upload URL hinzugefügt.

= 1.0.6.9 =
- Bugs am Single Template behoben

= 1.0.6.8 =
- Objektdateien von Lagler werden jetzt erkannt

= 1.0.6.7 =
- Mit Wordpress 4.5 getestet.
- Tab Shortcodes in Admin-Settings bearbeitet
- Leere Preisfelder bei Single und Archiv Ansicht werden nicht mehr angezeigt.
- Loop-Navigation in der Single-Ansicht ist jetzt auch auf großen Geräten zu sehen.

= 1.0.6.6 =
- Dateierkennung mit Großbuchstaben wurde nicht erkannt
- bei Doppelt gezipten archiven konnte keine XML-Datei gefunden werden

= 1.0.6.5 =
- Ordnerstrucktur des Plugins angepasst

= 1.0.6.4 =
- Ausgabe Custom CSS in den Templates angepasst.

= 1.0.6.3 =
- Issues for WP-Repo fixed
- Pfad-Variablen abgeändert
- Konstanten angepasst
- wp-updates class entfernt

= 1.0.6.2 =
- Bugs an der Lizenzabfrage behoben.

= 1.0.6.1 =
- Lizenzierungsabfrage hinzugefügt.

= 1.0.6 =
- Eingabefeld für Pro-Lizenz hinzugefügt
- Funktion zum aktivieren/deaktivieren der Bootstrap-Styles
- Listenpunkt Shortcodes in der Admin-Page hinzugefügt
- Textarea für Benutzerdefinierte Styles hinzugefügt
- uninstall.php hinzugefügt

= 1.0.5.7 =
- Bootstrap.css im Admin-Bereich wird nur auf der Admin-Page des Plugins geladen
- Kollision mit Admin-Bar behoben
- Übersetzung zu Admin-Seite hinzugefügt.


== Upgrade Notice ==

= 2.2.1 =
- Bug in der wpi_create_posts.php behoben
- Bei einem Abgleich der Immobilien wurde eine Immobilie mehrfach erfasst.

= 2.2 =
- V 2.2 ist ein Major Update
- überarbeitete Namespacess
- Autoloader zum laden der Klassen eingefügt
- Zuordnung der Immobilien nicht mehr nach Titel sondern nach Objektnummer
**Wichtig** Alle vorhandene Immobilien vor dem Update mit DELETE übertragen ggf. per Hand löschen, dann neu einfügen.

= 2.1.7 =
- BUG: Admin Files wurden bei SSL gesicherten verbindungen nicht geladen.
- Templates können jetzt im Uploads-Ordner abgelegt werden als Custom Templates

= 2.1.6 =
- get_image_thumbnails() Methode zu der Single-Class hinzugefügt.

= 2.1.5 =
- Bug in den Plugin-Settings für Single-Ansicht behoben...

= 2.1.4 =
- Die Und-Verknüpfung im Query-Shortcode hat nicht funktioniert
- Bugs behoben.

= 2.1.3 =
- Nutze die Immobiliengruppen um z.B. Referenzobjekte oder weitere Marketinggruppen voll automatisiert darzustellen.
Weitere Infos in userer FAQ.

= 2.0.8 =
Neues OnePage Template für die Single Ansicht hinzugefügt. Diese ist mit der Pro-Version des Plugins nutzbar.

= 2.0.5 =
- Energieausweisdaten überarbeitet
- Texte für Energieausweis in Backend anpassbar
- Neue Preisfelder zu Liste und Single eingefügt
- Option im Backend für Titel der Immobilien hinzugefügt.

= 2.0.3 =
- Bei Übertragung eines einzigen Anhangs wurden die Bilder nicht angezeigt, wurde gefixt.

= 2.0.2 =
- immo-navigation bei Firefox und IE ohne Funktion, wurde gefixt.
- search-form war bei single_immobilie fehlerhaft, wurde gefixt.
- Fehler in der Umkreissuche gefixt.

= 1.0.7 =
- Shortcode "Immobilien" hinzugefügt.
Damit ist es möglich verschiedene Immobilien nach Vermarktungsart oder Objektart zu filtern.
- Eingabefeld für Upload URL hinzugefügt.
Bei Änderung des Upload-Pfads wurden die Anhänge im falschen Verzeichniss gesucht.

= 1.0.6.6 =
- Dateierkennung mit Großbuchstaben wurde nicht erkannt
- bei Doppelt gezipten archiven konnte keine XML-Datei gefunden werden

= 1.0.6.5 =
Es gab ein Bug in der Ordnerstrucktur, wodurch wichtige Dateien nicht gefunden wurden.

= 1.0.6.3 =
Anpassungen an Wordpress Plugin Bestimmungen

= 1.0.6.2 =
Diese Version behebt Bugs an der Lizenz-Abfrage

= 1.0.6.1 =
Mit dieser Version ist die Lizenzabfarge für Pro-Version abgeschlossen.
