<?php
/**
 * Created by
 * User: Media-Store.net
 * Date: 07.12.2016
 * Time: 22:41
 */

namespace wpi\wpi_classes;

use WP_Query;
use wpi\wpi_classes\components\BootstrapCarouselClass;

class ShortcodesClass {

	public function __construct() {
	}

	static function search_filter_form( $atts ) {
		extract( shortcode_atts( array(
			'btn_text' => 'Filter anwenden',
		), $atts ) );

		if ( isset( $_GET ) && ! empty( $_GET ) ) {


		}
		$immoarray = get_posts( array( 'post_type' => 'wpi_immobilie', 'posts_per_page' => - 1 ) );
		$orte      = array();

		foreach ( $immoarray as $immo ) {
			$ort    = get_post_meta( $immo -> ID, 'geodaten', true );
			$orte[] = $ort[ 'ort' ];
		}
		$orte = array_unique( $orte );

		$page = get_option( 'wpi_search_page' );

		$link = esc_url( home_url( $page ) );

		$objekttyp_array   = get_terms( array(
			'taxonomy'   => 'objekttyp',
			'hide_empty' => true,
		) );
		$vermarktung_array = get_terms( array(
			'taxonomy'   => 'vermarktungsart',
			'hide_empty' => true,
		) );

		ob_start();
		?>

		<div id="search-filter-form" class="panel panel-default">
			<form class="panel-body form-inline" action="<?php echo $link ?>">
				<input name="script" type="hidden" value="immosearchfilter"/>
				<div class="form-group">
					<!-- <p class="lead"><?php echo __( 'Suche', WPI_PLUGIN_NAME ) ?></p> -->
					<label><?php echo __( 'Objektart', WPI_PLUGIN_NAME ) ?></label>
					<select name="objektart">
						<option value="all"><?php echo __( 'Alle Objekte', WPI_PLUGIN_NAME ); ?></option>
						<?php foreach ( $objekttyp_array as $objekttyp ) : ?>
							<option value="<?php echo $objekttyp -> slug ?>"><?php echo $objekttyp -> name ?></option>
						<?php endforeach; ?>
					</select>

					<label><?php echo __( 'Vermarktung', WPI_PLUGIN_NAME ) ?></label>
					<select name="marktart">
						<option value="all"><?php echo __( 'Mieten und Kaufen', WPI_PLUGIN_NAME ); ?></option>
						<?php foreach ( $vermarktung_array as $vermarktung ) : ?>
							<option value="<?php echo $vermarktung -> slug ?>"><?php echo $vermarktung -> name ?></option>
						<?php endforeach; ?>
					</select>

					<label><?php echo __( 'Ort', WPI_PLUGIN_NAME ) ?></label>
					<select name="ort">
						<option value=""><?php echo __( 'Alle Orte', WPI_PLUGIN_NAME ); ?></option>
						<?php foreach ( $orte as $ort ) : ?>
							<option value="<?php echo $ort ?>"><?php echo $ort ?></option>
						<?php endforeach; ?>
					</select>

				</div>
				<button type="submit"
				        class="btn btn-default"><?php echo $btn_text; ?></button>
			</form>
		</div>
		<?php
		return ob_get_clean();

	}

	static function search_filter_view( $params ) {

		$optionsClass = new WpOptionsClass;
		$options      = $optionsClass -> wpi_get_options();


		// CSS einbinden
		wp_register_style( 'plugin-styles', WPI_PLUGIN_URL . 'styles.css', true, '' );
		wp_enqueue_style( 'plugin-styles' );
		// Aus den Optionen
		add_action( 'wp_enqueue_scripts', 'wpi_add_custom_css' );


		// Festlegen der Relation für Taxonomien und Tax_Query
		if ( $params[ 'objektart' ] == 'all' && $params[ 'marktart' ] == 'all' ) {
			$tax = '';
		} elseif ( $params[ 'objektart' ] == 'all' && $params[ 'marktart' ] != 'all'
		           || $params[ 'objektart' ] != 'all' && $params[ 'marktart' ] == 'all'
		) {
			$tax = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'objekttyp',
					'field'    => 'slug',
					'terms'    => array( $params[ 'objektart' ] ),
				),
				array(
					'taxonomy' => 'vermarktungsart',
					'field'    => 'slug',
					'terms'    => array( $params[ 'marktart' ] ),
				)
			);
		} else {
			$tax = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'objekttyp',
					'field'    => 'slug',
					'terms'    => array( $params[ 'objektart' ] ),
				),
				array(
					'taxonomy' => 'vermarktungsart',
					'field'    => 'slug',
					'terms'    => array( $params[ 'marktart' ] ),
				)
			);
		}

		// Meta Query
		// DEFAULT
		$meta_query = '';
		if ( $params[ 'ort' ] != '' ) {
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => 'geodaten',
					'value'   => strtolower( $params[ 'ort' ] ),
					'compare' => 'LIKE',
				)
			);
		} else {
			$meta_query = $meta_query;
		}

		// Args für den Query
		$args  = ( array(
			'post_type'      => 'wpi_immobilie',
			'order'          => 'ASC',
			'posts_per_page' => - 1,
			'tax_query'      => $tax,
			'meta_query'     => $meta_query,
		) );
		$query = new WP_Query( $args );

		echo __( '<strong>Gefundene Objekte ' . $query -> post_count . '</strong>', WPI_PLUGIN_NAME );

		if ( $query -> have_posts() ) :

			while ( $query -> have_posts() ) : $query -> the_post();

				$id        = get_the_ID();
				$meta      = get_post_meta( get_the_ID() );
				$list_view = new ListViewClass();
				$style     = $list_view -> list_options[ 'wpi_list_view_column' ];

				echo '<div id="wpi-main" class="search-filter">';

				echo $list_view -> list_view_post( $id, $style );

				echo '</div';

			endwhile;

		endif;
	}

	// Shortcode zur Umkreissuche

	static function umkreissuche_form( $atts ) {
		extract( shortcode_atts( array(
			'anzahl' => '-1',
		), $atts ) );

		$page = get_option( 'wpi_search_page' );

		$link = esc_url( home_url( $page ) );

		ob_start();
		?>

		<form action="<?php echo $link ?>" method="get">
			<div class="form-group">
				<input type="hidden" name="script" value="umkreissuche">
				<label for="plz"><?php echo __( 'Postleitzahl', WPI_PLUGIN_NAME ) ?></label>
				<input type="number" class="form-control" id="plz" name="plz" placeholder="PLZ eingeben" required>
			</div>
			<div class="form-group">
				<label for="distanz"><?php echo __( 'Umkreis in km', WPI_PLUGIN_NAME ) ?></label>
				<input type="number" class="form-control" id="distanz" name="distanz" placeholder="km">
			</div>
			<button type="submit" class="btn btn-default"><?php echo __( 'Suchen', WPI_PLUGIN_NAME ) ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	static function umkreissuche_view( $params ) {
		ob_start();
		$plz     = trim( $params[ 'plz' ] );
		$distanz = trim( $params[ 'distanz' ] );

		@$plz_array = ogdbPLZnearby( $plz, $distanz, true );

		foreach ( $plz_array as $value ) {
			//zeigen($value);
			$zip  = $value[ 'zip' ];
			$city = $value[ 'city' ];

			$args     = ( array(
				'post_type'  => 'wpi_immobilie',
				'order'      => 'ASC',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => 'geodaten',
						'value'   => $zip,
						'compare' => 'LIKE',
					),
				),
			) );
			$querys[] = new WP_Query( $args );
		}

		$querys = array_reverse( $querys );

		foreach ( $querys as $query ) {

			if ( $query -> have_posts() ) :

				echo __( '<strong>Gefundene Objekte ' . $query -> post_count . '</strong>', WPI_PLUGIN_NAME );

				while ( $query -> have_posts() ) : $query -> the_post();

					$id        = get_the_ID();
					$meta      = get_post_meta( get_the_ID() );
					$list_view = new ListViewClass();
					$style     = $list_view -> list_options[ 'wpi_list_view_column' ];


					echo '<div id="wpi-main" class="umkreissuche">';

					echo $list_view -> list_view_post( $id, $style );

					echo '</div';

				endwhile;

			endif;


		}

		return ob_get_clean();
	}


	// Shortcode mit Archiv-Query (Einbindung in den Seiten)

	static function archiv_query_loop( $atts ) {
		// Standardparameter übergeben
		extract( shortcode_atts( array(
			'id'          => array(),
			'anzahl'      => '-1',
			'order'       => 'DESC',
			'orderby'     => 'ID',
			'objekttyp'   => '',
			'vermarktung' => '',
			'immogruppe'  => '',
			'exclude'     => '',
			'relation'    => 'OR',
			'paginated'   => true,
			'columns'     => false,
			'style'       => 'table',
		), $atts ) );

		//Prüfen ob Objekttyp / Vermarktung / Immogruppe Komma enthalten, dann exploden zu Array
		if ( isset( $atts[ 'vermarktung' ] ) && strpos( $atts[ 'vermarktung' ], ',' ) ):
			$vermarktung = explode( ',', $atts[ 'vermarktung' ] );
		endif;
		if ( isset( $atts[ 'objekttyp' ] ) && strpos( $atts[ 'objekttyp' ], ',' ) ):
			$objekttyp = explode( ',', $atts[ 'objekttyp' ] );
		endif;
		if ( isset( $atts[ 'immogruppe' ] ) && strpos( $atts[ 'immogruppe' ], ',' ) ):
			$immogruppe = explode( ',', $atts[ 'immogruppe' ] );
		endif;
		// Prüfen ob exclude angegeben und bei Kommatrennung zum Array bilden
		/**
		 * $exclude_name  --> Name der Taxonomie
		 * $exclude_items --> Slug der Taxonomie
		 * $term_tax_ids  --> term_taxonomie_id der übergebenen Slugs
		 */
		if ( isset( $atts[ 'exclude' ] ) ):
			if ( strpos( $atts[ 'exclude' ], '-' ) ):
				$exclude      = explode( '-', $atts[ 'exclude' ] );
				$exclude_name = $exclude[ 0 ];
				//Prüfen ob mehrere kommagetrennte Parameter übergeben wurden
				if ( strpos( $exclude[ 1 ], ',' ) ):
					$exclude_items = explode( ',', $exclude[ 1 ] );
					// Get the term_taxonomie_ids as array
					foreach ( $exclude_items as $item ):
						$term_tax_ids[] = get_term_by( 'slug', $item, $exclude_name ) -> term_taxonomy_id;
					endforeach;
				else:
					$exclude_items = $exclude[ 1 ];
					// Get the term_taxonomie_id as string
					$term_tax_ids = get_term_by( 'slug', $exclude_items, $exclude_name ) -> term_taxonomy_id;
				endif;
			endif;
		endif;
		ob_start();

		$list_view = new ListViewClass;

		// CSS einbinden
		wp_register_style( 'plugin-styles', WPI_PLUGIN_URL . 'styles.css', true, '' );
		wp_enqueue_style( 'plugin-styles' );
		// Aus den Optionen
		//if(!empty(get_option('wpi_custom_css'))){
		add_action( 'wp_enqueue_scripts', 'wpi_add_custom_css' );
		//}


		// WP_Query arguments initialisation
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args  = array( 'post_type' => 'wpi_immobilie' );

		// Argument nur wenn objekttyp oder vermarktung oder Immogruppe übergeben wird
		if ( isset( $atts[ 'objekttyp' ] ) || isset( $atts[ 'vermarktung' ] ) ) {
			$args = array(
				'post_type'      => 'wpi_immobilie',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => $anzahl,
				'order'          => trim( $order ),
				'orderby'        => trim( $orderby ),
				'page'           => $paged,
				'tax_query'      => array(
					'relation' => $relation,
					array(
						'taxonomy' => 'objekttyp',
						'field'    => 'slug',
						'terms'    => (array) $objekttyp,
					),
					array(
						'taxonomy' => 'vermarktungsart',
						'field'    => 'slug',
						'terms'    => (array) $vermarktung,
					)
				)
			);
			//zeigen( $args );
		} elseif ( isset( $atts[ 'immogruppe' ] ) ) {
			$args = array(
				'post_type'      => 'wpi_immobilie',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => (int) $anzahl,
				'order'          => trim( $order ),
				'orderby'        => trim( $orderby ),
				'page'           => $paged,
				'tax_query'      => array(
					'relation' => $relation,
					array(
						'taxonomy' => 'immobiliengruppe',
						'field'    => 'slug',
						'terms'    => $immogruppe,
					),
				),
			);
		} elseif ( isset( $atts[ 'exclude' ] ) ) {
			$args = array(
				'post_type'      => 'wpi_immobilie',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => (int) $anzahl,
				'order'          => trim( $order ),
				'orderby'        => trim( $orderby ),
				'page'           => $paged,
				'tax_query'      => array(
					array(
						'taxonomie' => $exclude_name,
						'field'     => 'term_taxonomy_id',
						'terms'     => $term_tax_ids,
						'operator'  => 'NOT IN'
					)
				)
			);
			//zeigen($args);
		} else {
			$args = array(
				'post_type'      => 'wpi_immobilie',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => (int) $anzahl,
				'order'          => trim( $order ),
				'orderby'        => trim( $orderby ),
				'page'           => $paged,
			);
		}
		if ( ! empty( $id ) ):
			$id   = explode( ',', $id );
			$args = array(
				'post_type' => 'wpi_immobilie',
				'post__in'  => $id,
			);
		endif;

		// The Query
		$im_query = new \WP_Query( $args );

		$pagination_args = array(
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'total'              => $im_query -> max_num_pages,
			'current'            => max( 1, get_query_var( 'paged' ) ),
			'show_all'           => true,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => false,
			'prev_text'          => __( '« Zurück' ),
			'next_text'          => __( 'Weiter »' ),
			'type'               => 'list',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);
		// Initialisiere Variable "Content"
		?>
		<div id="wpi-main" class="site-main row" role="main"><?php

		// The Loop
		if ( $im_query -> have_posts() ) {
			//add_filter( 'the_content', '' );
			$i = 0;
			while ( $im_query -> have_posts() ) {
				$im_query -> the_post();
				$id   = get_the_ID();
				$meta = get_post_meta( $id );

				switch ( $style ):
					case 'full_image':
						$full_image[ $i ] = $list_view -> list_view_full_image_slider( $meta );
						$i ++;
						break;

					default:
						echo $list_view -> list_view_post( $id, $style );
						break;

				endswitch;
			}

			//Abfrage wenn full_image starte slider
			if ( isset( $full_image ) && count($full_image) > 0 ):
				echo new BootstrapCarouselClass($full_image);
			endif;
			// Pagination einfügen
			$sep  = ' &nbsp; ';
			$down = '<button class="btn btn-default navbar-btn">Vorige Seite</button>';
			$up   = '<button class="btn btn-default navbar-btn">Nächste Seite</button>';
			?>

			<div class="clearfix"></div>
			<?php if ( $paginated != 'false' ): ?>
				<nav class="text-center nav-pagination">
					<?php echo paginate_links( $pagination_args ) ?>
				</nav>
			<?php endif; ?>
			</div><!-- Ende #main --><?php

		} else {
			echo view_no_founds();
		}


		// Restore original Post Data
		wp_reset_postdata();

		return ob_get_clean();
	}

}
