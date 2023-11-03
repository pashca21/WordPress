<?php
/**
 * In dieser Datei werden zusätzliche Zeiten zur Synchronisation in WP Filter angelegt
 * und die Funktionen der automatischen Synchronisation definiert.
 */

$admin = new \wpi\wpi_classes\AdminClass();

$pro = $admin ::versionStatus();

if ( ! $pro ) {
	return;
}

add_filter( 'cron_schedules', 'wpi_cron_schedules' );
add_action( 'wpi_time_event', 'wpi_do_this_on_time' );


// WP-Cron Filter Zeit von 15Min. und 30 Min. setzen

// add custom time to cron
function wpi_cron_schedules( $schedules ) {

	$schedules[ 'half_hour' ]        = array(
		'interval' => 1800, // seconds
		'display'  => __( 'Halbe Stunde' )
	);
	$schedules[ 'fiveteen_minutes' ] = array(
		'interval' => 900, // seconds
		'display'  => __( '15 Min.' )
	);

	return $schedules;
}


// Prüfen ob Shedule bereits registriert ist, wenn nicht registrieren.
add_action( 'wp', 'wpi_setup_schedule' );
function wpi_setup_schedule() {
	$shedule_time = get_option( 'wpi_shedule_time' ); // Abrufen der eingestellten Werte
	if ( ! wp_next_scheduled( 'wpi_time_event' ) ) {
		wp_schedule_event( time(), $shedule_time, 'wpi_time_event' );
	}
	if ( ! wp_next_scheduled( 'wpi_check_status' ) ) {
		wp_schedule_event( time(), 'hourly', 'wpi_check_status' );
	}
}


// An einem registrierten shedule, Befehle ausführen.

function wpi_do_this_on_time() {
	// Hier kommen die Befehle der Synchronisation etc. hin.

	$xml_file_array         = wpi_xml_auslesen(); //Funktion definiert in wpi_unzip_functions.php
	$GLOBALS[ 'xml_array' ] = wpi_xml_array( $xml_file_array ); //Funktion definiert in wpi_create_posts.php
	wpi_xml_standard();
}


add_action( 'wpi_check_status', 'enqueue_valid_status' );

function enqueue_valid_status() {
	add_action( 'wp_footer', 'check_valid_status' );
}

function check_valid_status() {
	$licence     = get_option( 'wpi_licence' ) != '' ? get_option( 'wpi_licence' ) : '';
	$admin_email = get_option( 'wpi_admin' ) != '' ? get_option( 'wpi_admin' ) : get_option( 'admin_email' );
	//http vs https
	is_ssl() ? $schema = 'https' : $schema = 'http';
	ob_start();
	?>
	<script type="application/javascript">

		(jQuery)(function ($) {
			// Validate User
			var d1 = $.Deferred();
			var licence = "<?php echo $licence; ?>";
			var email = "<?php echo $admin_email; ?>";
			var domain = "<?php echo esc_url( home_url( '/', $schema)); ?>";
			var url = 'https://media-store.net/wp-json/wp/v2/wpmi/validateUser_v2';
			var wpurl = "<?php echo esc_url( home_url( '/wp-admin/admin-ajax.php?action=wpi_valid_status', $schema) ); ?>";
			$.ajax({
				"type": "GET",
				"url": url,
				"data": {"licence": licence, "email": email, "domain": domain},
				"cache": false,
				complete: function (xhr) {
					//console.log(xhr);
					d1.resolve(xhr);

					return d1.promise;
				},
				success: function (data, status, xhr) {
					console.log(data);
				},
				error: function (xhr, status, errorThrown) {
					//console.dir(xhr);
					//console.log(xhr);
				}

			});
			d1.promise().then(function (jqXHR) {
				//console.log('Promise geladen...');
				//console.log(jqXHR.responseJSON);
				var response = jqXHR.responseJSON;

				$.ajax({
					"url": wpurl,
					"action": "wpi_valid_status",
					"type": "POST",
					"data": response,
					"cache": false,
					complete: function (xhr) {
						console.log(xhr.responseText);
					},
				});
			});

		});
	</script>
	<?php
	return ob_get_clean();
}