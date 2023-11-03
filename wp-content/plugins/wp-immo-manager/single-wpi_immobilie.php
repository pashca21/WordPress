<?php
/**
 * Template zur Anzeige der Immobilien als Single
 *
 */
?>
<?php

use wpi\wpi_classes\SingleViewClass;

$singleView = new SingleViewClass;

// festlegen der einzelnen Variablen
$meta = get_post_meta( get_the_ID(), false );

$uploadsUrl = get_option( 'wpi_upload_url' );

$anbieterkennung = @$meta[ 'anbieterkennung' ][ 0 ];
$firma           = @$meta[ 'anbieterfirma' ][ 0 ];
if ( strpos( $firma, ";" ) ) {
	$firma = explode( ';', $firma );
}
// Objektdaten als Arrays
$objektkategorie_array = unserialize( $meta[ 'objektkategorie' ][ 0 ] );
$geodaten              = unserialize( $meta[ 'geodaten' ][ 0 ] );
$kontaktperson         = unserialize( $meta[ 'kontaktperson' ][ 0 ] );
$preise                = unserialize( $meta[ 'preise' ][ 0 ] );
$flaechen              = unserialize( $meta[ 'flaechen' ][ 0 ] );
$ausstattung           = unserialize( $meta[ 'ausstattung' ][ 0 ] );
$zustand_angaben       = unserialize( $meta[ 'zustand_angaben' ][ 0 ] );
$anhaenge              = unserialize( $meta[ 'anhaenge' ][ 0 ] );
$freitexte             = unserialize( $meta[ 'freitexte' ][ 0 ] );
$verwaltung_objekt     = unserialize( $meta[ 'verwaltung_objekt' ][ 0 ] );
$verwaltung_techn      = unserialize( $meta[ 'verwaltung_techn' ][ 0 ] );

?>

<?php get_header(); ?>


	<!-- Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->


	<div id="wpi-primary" class="container-fluid"><?php
		if ( have_posts() ):
			while ( have_posts() ) :
				the_post();
				//zeigen($meta);


				switch ( get_option( 'wpi_single_view' ) ):
					case 'accordion':
						echo $singleView->SingleAccordion();
						break;
					case 'onepage':
						echo $singleView -> onePage();
						break;
					case 'sidebarpage':
						echo $singleView -> sidebarPage();
						break;
					default:
						echo $singleView -> SingleTabs();
						break;

				endswitch;
			endwhile;

		else:

			echo view_no_founds();

		endif;

		?>
	</div>

	<!-- Ende Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->

<?php get_footer(); ?>