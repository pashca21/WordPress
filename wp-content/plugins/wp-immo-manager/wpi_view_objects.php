<?php

// Funktion Single Immobilien als Tabs
function view_single_tabs() {
	// auslesen der globalen Meta und Arrays
	global $meta, $post, $uploadsUrl, $anbieterkennung, $firma, $objektkategorie_array, $geodaten, $kontaktperson,
	       $preise, $flaechen, $ausstattung, $zustand_angaben, $anhaenge, $freitexte, $verwaltung_objekt, $verwaltung_techn, $pro;
	// Auslesen der Kategorien
	$pro         = get_option( 'wpi_pro' );
	$taxonomies  = get_the_taxonomies();
	$objekttyp   = strtolower( strstr( $taxonomies[ 'objekttyp' ], ' ' ) );
	$vermarktung = strtolower( strstr( $taxonomies[ 'vermarktungsart' ], ' ' ) );
	$objekttyp   = trim( $objekttyp, '.' );
	$vermarktung = trim( $vermarktung, '.' );

	// Laden der Optinen aus DB

	$single_preise      = get_option( 'wpi_single_preise' );
	$single_flaechen    = get_option( 'wpi_single_flaechen' );
	$single_ausstattung = get_option( 'wpi_single_ausstattung' );
	$html_inject        = get_option( 'wpi_html_inject' );
	$html               = get_option( 'wpi_custom_html' );

	// Überprüfung ob die Optionen in den Meta vorhanden sind

	if ( ! empty( $single_preise ) ) :
		foreach ( $single_preise as $preis_key => $preis_value ) {
			if ( array_key_exists( $preis_key, $preise ) ) :
				$preis[ $preis_value ] = $preise[ $preis_key ];
			endif;
		}
	endif;

	if ( ! empty( $single_flaechen ) ) :
		foreach ( $single_flaechen as $fl_key => $fl_value ) {
			if ( array_key_exists( $fl_key, $flaechen ) ) :
				$flaeche[ $fl_value ] = $flaechen[ $fl_key ];
			endif;
		}
	endif;

	//Ausgabe puffern
	ob_start();
	?>
	<article <?php post_class( 'single-immobilie-tabs' ); ?> >

		<div class="row">
			<div class="search-div">
				<?php echo view_searchfield_wpmi(); ?>
			</div>

			<div id="wpi-main" class="site-main col-xs-12" role="main">

				<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					// Variablen im Loop
					$previous = ( is_attachment() ) ? get_post( get_post() -> post_parent ) : get_adjacent_post( false, '', true );
					$next     = get_adjacent_post( false, '', false );

					$link_to_immo  = get_post_type_archive_link( 'wpi_immobilie' );
					$button_middle = '<a href="' . $link_to_immo . ' ">';
					$button_middle .= __( 'Immobilien-Übersicht', WPI_PLUGIN_NAME );
					$button_middle .= '</a>';

					?>

					<div class="article-navigation">
						<nav class="navigation immo-navigation top-navi" role="navigation">
							<div class="btn-group col-xs-12 text-center">
								<div class="btn btn-default col-xs-4 btn-down">
									<?php previous_post_link( '%link', 'Zurück' ); ?>
								</div>
								<div class="btn btn-default col-xs-4 btn-overview">
									<?php echo $button_middle; ?>
								</div>
								<div class="btn btn-default col-xs-4 btn-up">
									<?php next_post_link( '%link', 'Nächste' ); ?>
								</div>
							</div>
						</nav>
						<!-- Loop-Navigation -->
					</div><!-- article-navigation -->
					<header class="wpi-header">

						<h2><?php the_title(); ?></h2>

					</header>
					<!-- .entry-header -->
					<div class="clearfix"></div>

					<div id="media" class="imageslider col-md-8">
						<?php
						$anhang = help_handle_array( $anhaenge, 'anhang' );
						@$bilder = $anhang[ 'bilder' ];

						if ( $bilder ) : ?>
							<div id="carousel-example-generic"
							     class="carousel slide"
							     data-ride="carousel">
								<!-- Positionsanzeiger -->
								<ol class="carousel-indicators">
									<?php
									for ( $i = 0; $i < count( $bilder ); $i ++ ) {
										foreach ( $bilder[ $i ] as $alt => $pfad ) {
											echo '<li data-target="#carousel-example-generic" data-slide-to="' . $pfad . '"></li>';
										}
									}
									?>
								</ol>

								<!-- Verpackung für die Elemente -->
								<div class="carousel-inner" role="listbox">
									<?php
									for ( $j = 0; $j < count( $bilder ); $j ++ ) {
										foreach ( $bilder[ $j ] as $alt => $pfad ) {
											! empty( $alt ) ? $alt : $alt = '';
											if ( $j === 0 ) :
												$str = '<div class="item active">';
											else :
												$str = '<div class="item">';
											endif;

											$str .= '<img src="' . $uploadsUrl . $pfad . '" alt="' . $alt . '">';
											$str .= '<div class="carousel-caption">';
											$str .= '';
											$str .= '</div> </div>';
											echo $str;
										}
									}
									?>
								</div>

								<!-- Schalter -->
								<a class="left carousel-control" href="#carousel-example-generic"
								   role="button"
								   data-slide="prev">
									<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
									<span class="sr-only">Zurück</span>
								</a>
								<a class="right carousel-control" href="#carousel-example-generic"
								   role="button"
								   data-slide="next">
									<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
									<span class="sr-only">Weiter</span>
								</a>
							</div>
							<?php
						else :
							echo '<img src="' . get_option( 'wpi_img_platzhalter' ) . '"/>';
						endif;
						?>
					</div>
					<!-- Ende Media Imageslider -->
					<div id="eckdaten" class="col-md-4">
						<?php //zeigen($flaechen);
						?>
						<ul class="list-unstyled eckdaten">
							<li>
								<span class="glyphicon glyphicon-map-marker"></span>
								<span
										class="eckdaten_ort"><?php echo $geodaten[ 'plz' ] . ' ' . $geodaten[ 'ort' ] ?></span>
							</li><?php
							//TODO Abfrage wenn Adresse freigegeben, Strasse hinzufügen.
							if ( ! empty( $preis ) ):
								// Tabellen-Array ohne leere Werte in die Tabelle schreiben
								foreach ( array_filter( $preis ) as $key => $wert ) {
									if ( $key != 'Faktor' ) {
										echo '<li>' . $key . ' in  (' . $preise[ 'waehrung' ][ '@attributes' ][ 'iso_waehrung' ] . ') ';
										if ( is_numeric( $wert ) ):
											echo '<span class="price value">' . number_format( $wert, 2, ",", "." ) . '</span></li>';
										else:
											echo '<span class="price value">' . $wert . '</span></li>';
										endif;
									} else {
										echo '<li>' . $key;
										if ( is_numeric( $wert ) ):
											echo '<span class="price value">' . number_format( $wert, 2, ",", "." ) . '</span></li>';
										else:
											echo '<span class="price value">' . $wert . '</span></li>';
										endif;
									}
								}
							endif;
							if ( ! empty( $zustand_angaben[ 'baujahr' ] ) ):
								echo '<li>' . __( "Baujahr", WPI_PLUGIN_NAME ) . ' <span class="baujahr value"> ' . $zustand_angaben[ 'baujahr' ] . '</span></li>';
							endif;
							if ( ! empty( $flaechen[ 'wohnflaeche' ] ) ):
								echo '<li>' . __( "Wohnfläche in m²", WPI_PLUGIN_NAME ) . ' <span class="wohnflaeche value">' . number_format( $flaechen[ 'wohnflaeche' ], 1, ",", "" ) . '</span></li>';
							endif;
							if ( ! empty( $flaechen[ 'grundstuecksflaeche' ] ) ):
								echo '<li>' . __( "Grundstück in m²", WPI_PLUGIN_NAME ) . ' <span class="grundsteuck value">' . number_format( $flaechen[ 'grundstuecksflaeche' ], 1, ",", "" ) . '</span></li>';
							endif;
							if ( ! empty( $flaechen[ 'anzahl_zimmer' ] ) ):
								echo '<li>' . __( "Anzahl Zimmer", WPI_PLUGIN_NAME ) . ' <span class="zimmerzahl value">' . number_format( $flaechen[ 'anzahl_zimmer' ], 1, ",", "" ) . '</span></li>';
							endif;
							if ( ! empty( $verwaltung_techn[ "objektnr_extern" ] ) ):
								echo '<li>' . __( "Objektnummer", WPI_PLUGIN_NAME ) . ' <span class="objektnummer value">' . $verwaltung_techn[ "objektnr_extern" ] . '</span></li>';
							endif;
							?>
						</ul>
					</div><!-- ende Eckdaten -->

					<div class="clearfix"></div>

					<div id="wpi-tabs" role="tabpanel"><?php
						$tabs = get_option( 'wpi_single_view_tabs' );
						?>
						<!-- Tabs-Navs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#details" role="tab" data-toggle="tab"><?php echo $tabs[ 'details' ]; ?></a>
							</li>
							<li role="presentation">
								<a href="#beschreibung-tab" role="tab"
								   data-toggle="tab"><?php echo $tabs[ 'beschreibung' ]; ?></a>
							</li>
							<li role="presentation">
								<a href="#doku" role="tab" data-toggle="tab"><?php echo $tabs[ 'bilder' ]; ?></a>
							</li>
							<li role="presentation">
								<a href="#kontaktdaten" role="tab"
								   data-toggle="tab"><?php echo $tabs[ 'kontakt' ]; ?></a>
							</li>
						</ul>

						<!-- Tab-Inhalte -->
						<div class="tab-content">
							<div role="tabpanel" class="col-xs-12 tab-pane fade in active" id="details">
								<div class="objektdetails panel panel-default">

									<table class="table table-hover table-striped">
										<tr>
											<td class="col-xs-4"><?= __( 'Vermarktung', WPI_PLUGIN_NAME ); ?></td>
											<td class="text-capitalize"><?php echo $vermarktung ?></td>
										</tr>
										<tr>
											<td class="col-xs-4"><?= __( 'Objektart', WPI_PLUGIN_NAME ); ?></td>
											<td class="text-capitalize"><?php echo $objekttyp ?></td>
										</tr>
										<tr>
											<td class="col-xs-4"><?= __( 'Anbieterkennung', WPI_PLUGIN_NAME ); ?></td>
											<td><?php echo $anbieterkennung; ?></td>
										</tr>
										<tr>
											<td class="col-xs-4"><?= __( 'Objekt / Online - Nr.', WPI_PLUGIN_NAME ); ?></td>
											<td><?php echo $verwaltung_techn[ "objektnr_extern" ] ?></td>
										</tr>
									</table>

								</div><!-- .objektdetails -->

								<?php if ( ! empty( $preis ) ): ?>
									<div class="preise panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><?= __( 'Preise', WPI_PLUGIN_NAME ); ?></h3>
										</div>
										<div class="panel-body">
											<table class="table table-hover table-striped">
												<?php
												// Tabellen-Array ohne leere Werte in die Tabelle schreiben
												foreach ( array_filter( $preis ) as $key => $wert ) {
													echo '<tr>';
													if ( $key != 'Faktor' ) {
														echo '<td class="col-xs-4">' . $key . ' in  (' . $preise[ 'waehrung' ][ '@attributes' ][ 'iso_waehrung' ] . ')</td>';
														if ( is_numeric( $wert ) ):
															echo '<td>' . number_format( $wert, 2, ",", "." ) . '</td>';
														else:
															echo '<td>' . $wert . '</td>';
														endif;
													} else {
														echo '<td class="col-xs-4">' . $key . '</td>';
														if ( is_numeric( $wert ) ):
															echo '<td>' . number_format( $wert, 2, ",", "." ) . '</td>';
														else:
															echo '<td>' . $wert . '</td>';
														endif;
													}
													echo '</tr>';

												}
												?>
											</table>
										</div>
									</div>
									<!-- Div .Preise-->
								<?php endif; ?>

								<?php if ( ! empty( $flaeche ) ): ?>
									<div class="flaechen panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><?= __( 'Flächen', WPI_PLUGIN_NAME ); ?></h3>
										</div>
										<div class="panel-body">
											<table class="table table-hover table-striped">
												<?php
												// Tabellen-Array ohne leere Werte in die Tabelle schreiben
												foreach ( array_filter( $flaeche ) as $fl_key => $fl_wert ) {
													echo '<tr>';
													echo '<td class="col-xs-4">' . $fl_key . '</td>';
													if ( is_numeric( $fl_wert ) ):
														echo '<td>' . number_format( $fl_wert, "1", ",", "" ) . '</td>';
													else:
														echo '<td>' . $fl_wert . '</td>';
													endif;
													echo '</tr>';
												}
												?>
											</table>
										</div>
									</div>
									<!-- Div .Flaechen -->
								<?php endif; ?>

								<?php if ( ! empty( $ausstattung ) ): ?>
									<div class="ausstattung panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><?= __( 'Ausstattung', WPI_PLUGIN_NAME ); ?></h3>
										</div>
										<div class="panel-body">
											<table class="table table-hover table-striped">
												<?php
												$ausstatt = help_handle_array( $ausstattung, 'ausstattung' );
												// Tabellen-Array ohne leere Werte in die Tabelle schreiben
												foreach ( array_filter( $ausstatt ) as $aus_key => $aus_wert ) {
													echo '<tr>';
													echo '<td class="col-xs-4">' . ucfirst( $aus_key ) . '</td>';
													echo '<td>' . $aus_wert . '</td>';
													echo '</tr>';
												}
												?>
											</table>
										</div>
									</div>
									<!-- Div .Ausstattung -->
								<?php endif; ?>

								<?php $zustand = help_handle_array( $zustand_angaben, 'zustand' ); ?>

								<?php if ( ! empty( $zustand ) ): ?>
									<div class="zustand panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><?= __( 'Objektzustand / Energiepass', WPI_PLUGIN_NAME ); ?></h3>
										</div>
										<div class="panel-body">
											<table class="table table-hover table-striped"><?php
												if ( array_key_exists( 'baujahr', $zustand ) ):
													echo '<tr><td class="col-xs-4">' . __( "Baujahr", WPI_PLUGIN_NAME ) . '</td>';
													echo "<td>" . $zustand[ 'baujahr' ] . "</td></tr>";
												endif;
												if ( array_key_exists( 'zustand', $zustand ) ):
													echo '<tr><td class="col-xs-4">' . __( "Zustand", WPI_PLUGIN_NAME ) . '</td>';
													echo "<td>" . $zustand[ 'zustand' ] . "</td></tr>";
												endif;
												if ( array_key_exists( 'letztemodernisierung', $zustand ) ):
													echo '<tr><td class="col-xs-4">' . __( "Letzte Modernisierung", WPI_PLUGIN_NAME ) . '</td>';
													echo "<td>" . $zustand[ 'letztemodernisierung' ] . "</td></tr>";
												endif;
												if ( array_key_exists( 'energiepass', $zustand ) && $pro === 'true' ):
													echo '<tr><td class="col-xs-4">' . __( "Energiepass", WPI_PLUGIN_NAME ) . '</td>';
													echo '<td><ul class="list-unstyled">';
													if ( is_array( $zustand[ 'energiepass' ] ) ):
														foreach ( $zustand[ 'energiepass' ] as $en_key => $en_value ) {
															empty( $en_value ) ? $en_value = 'n.A.' : $en_value = $en_value;
															echo '<li>';
															echo ucfirst( $en_key ) . ' - <strong>' . $en_value . '</strong>';
															echo '</li>';
														}
													else:
														echo $zustand[ 'energiepass' ];
													endif;
													echo '</ul></td>';
													echo '</tr>';
												endif;
												?>
											</table>
										</div>
									</div>
									<!-- Div .Zustand -->
								<?php endif; ?>

								<div class="custom-details-div"><?php
									if ( '' != $html_inject && $html_inject === 'details' ):
										echo do_shortcode( $html );
									endif;
									?>
								</div>
							</div>
							<!-- Ende Details Panel -->
							<div role="tabpanel" class="col-xs-12 tab-pane fade" id="beschreibung-tab">
								<div id="beschreibung"><?php the_content(); ?></div>
								<div class="custom-beschreibung-div"><?php
									if ( '' != $html_inject && $html_inject === 'beschreibung' ):
										echo do_shortcode( $html );
									endif;
									?>
								</div>
							</div>
							<!-- Ende Beschreibung -->
							<div role="tabpanel" class="col-xs-12 tab-pane fade" id="doku"><?php

								if ( @$anhang[ 'dokumente' ] > 0 ): ?>
									<div class="dokumente">

									<ul class="list-unstyled"><?php
										for ( $i = 0; $i < count( $anhang[ 'dokumente' ] ); $i ++ ) {
											foreach ( $anhang[ 'dokumente' ][ $i ] as $name => $link ) {
												$offset = 1;
												$ext    = '<span class="glyphicon glyphicon-new-window"></span>';
												if ( $name != '' ):
													$name = $name;
												else:
													$name = __( 'Dokument', WPI_PLUGIN_NAME ) . $offset;
													$offset ++;
												endif;
												echo '<li><a target="_blank" href="' . $uploadsUrl . $link . '">' . $ext . ' ' . $name . '</a></li>';
											}
										}
										?>
									</ul>
									</div><?php
								else:
									echo '<p class="lead">' . __( "Keine Dokumente vorhanden", WPI_PLUGIN_NAME ) . '</p>';
									?>
								<?php endif; ?>
								<div class="custom-doku-div"><?php
									if ( '' != $html_inject && $html_inject === 'dokumente' ):
										echo do_shortcode( $html );
									endif;
									?>
								</div>
							</div>
							<!-- Ende #doku -->
							<div role="tabpanel" class="col-xs-12 tab-pane fade" id="kontaktdaten"><?php
								if ( $firma ):
									?>
									<div id="firma" class="panel panel-default">
									<div class="panel-heading">
										<h3><?= __( 'Anbieter / Makler', WPI_PLUGIN_NAME ); ?></h3>
									</div>
									<div class="panel-body"><?php
										// Abfrage wenn Firma ein String ist
										if ( ! is_array( $firma ) ) {
											echo $firma;
										} else {
											$count = 1;
											echo '<ul class="list-unstyled">';
											foreach ( $firma as $value ) {
												echo '<li id="li-' . $count . '">' . $value . '</li>';
												$count ++;
											}
											echo '</ul>';
										}
										?>
									</div>
									</div><?php
								endif;
								if ( $kontaktperson ):
									?>
									<div id="ansprechpartner" class="panel panel-default">
									<div class="panel-heading">
										<h3><?= __( 'Ansprechpartner', WPI_PLUGIN_NAME ); ?></h3>
									</div>
									<div class="panel-body">
										<?php $kontakt = help_handle_array( $kontaktperson, 'kontakt' ); ?>
										<dl class="dl-horizontal"><?php

											foreach ( $kontakt as $bez => $value ) {
												echo '<dt>' . ucfirst( $bez ) . '</dt>';
												echo '<dd>' . $value . '</dd>';
											}
											?>

										</dl>
									</div>
									</div><?php
								endif;
								?>
								<div class="custom-kontakt-div"><?php
									if ( '' != $html_inject && $html_inject === 'kontaktperson' ):
										echo do_shortcode( $html );
									endif;
									?>
								</div>
							</div>
							<!-- Ende Kontaktdaten -->
						</div>
						<!-- Ende Tab-content -->
					</div>
					<!-- Ende Tab-Panel -->

					<div class="article-navigation">
						<nav class="navigation immo-navigation bottom-navi" role="navigation">
							<div class="btn-group col-xs-12 text-center">
								<div class="btn btn-default col-xs-4 btn-down">
									<?php previous_post_link( '%link', 'Zurück' ); ?>
								</div>
								<div class="btn btn-default col-xs-4 btn-overview">
									<?php echo $button_middle; ?>
								</div>
								<div class="btn btn-default col-xs-4 btn-up">
									<?php next_post_link( '%link', 'Nächste' ); ?>
								</div>
							</div>
						</nav>
						<!-- Loop-Navigation -->
					</div><?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
					?>
				</section>
			</div>
			<!-- #main -->

			<div class="search-div">
				<?php echo view_searchfield_wpmi(); ?>
			</div>

		</div>
		<!-- row -->
	</article>
	<?php
	// Ausgeben und löschen des Puffers
	return ob_get_clean();
}

// Funktion Single Immobilie als Accordion
function view_single_accordion() {
	// auslesen der globalen Meta und Arrays
	global $meta;
	global $uploadsUrl;
	global $anbieterkennung;
	global $firma;
	global $objektkategorie_array;
	global $geodaten;
	global $kontaktperson;
	global $preise;
	global $flaechen;
	global $ausstattung;
	global $zustand_angaben;
	global $anhaenge;
	global $freitexte;
	global $verwaltung_objekt;
	global $verwaltung_techn;
	global $pro;

	// Auslesen der Kategorien
	$taxonomies  = get_the_taxonomies();
	$objekttyp   = strtolower( strstr( $taxonomies[ 'objekttyp' ], ' ' ) );
	$vermarktung = strtolower( strstr( $taxonomies[ 'vermarktungsart' ], ' ' ) );
	$objekttyp   = trim( $objekttyp, '.' );
	$vermarktung = trim( $vermarktung, '.' );

	// Laden der Optinen aus DB
	$single_preise      = get_option( 'wpi_single_preise' );
	$single_flaechen    = get_option( 'wpi_single_flaechen' );
	$single_ausstattung = get_option( 'wpi_single_ausstattung' );
	$html_inject        = get_option( 'wpi_html_inject' );
	$html               = get_option( 'wpi_custom_html' );

	// Überprüfung ob die Optionen in den Meta vorhanden sind
	if ( ! empty( $single_preise ) ):
		foreach ( $single_preise as $preis_key => $preis_value ) {
			if ( array_key_exists( $preis_key, $preise ) ):
				$preis[ $preis_value ] = $preise[ $preis_key ];
			endif;
		}
	endif;

	if ( ! empty( $single_flaechen ) ):
		foreach ( $single_flaechen as $fl_key => $fl_value ) {
			if ( array_key_exists( $fl_key, $flaechen ) ):
				$flaeche[ $fl_value ] = $flaechen[ $fl_key ];
			endif;
		}
	endif;


	$tabs          = get_option( 'wpi_single_view_tabs' );
	$link_to_immo  = get_post_type_archive_link( "wpi_immobilie" );
	$button_middle = '<a href="' . $link_to_immo . ' ">';
	$button_middle .= __( "Immobilien-Übersicht", WPI_PLUGIN_NAME );
	$button_middle .= '</a>';

	ob_start();
	?>
	<article id="post-<?php the_ID(); ?>" class="<?php post_class( 'single-immobilie-accordion' ); ?>">

		<div class="search-div">
			<?php echo view_searchfield_wpmi(); ?>
		</div><!-- search-div -->

		<div class="clearfix"></div>

		<div class="article-navigation">
			<nav class="navigation immo-navigation top-navi" role="navigation">
				<div class="btn-group col-xs-12 text-center">
					<div class="btn btn-default col-xs-4 btn-down">
						<?php previous_post_link( '%link', 'Zurück' ); ?>
					</div>
					<div class="btn btn-default col-xs-4 btn-overview">
						<?php echo $button_middle; ?>
					</div>
					<div class="btn btn-default col-xs-4 btn-up">
						<?php next_post_link( '%link', 'Nächste' ); ?>
					</div>
				</div>
			</nav>
			<!-- Loop-Navigation -->
		</div><!-- article-navigation -->

		<div class="clearfix"></div>

		<hr>

		<div class="entry header">
			<header class="wpi-header">
				<h2><?php the_title(); ?></h2>
			</header>
			<!-- .entry-header -->
		</div><!-- entry-header -->

		<div class="clearfix"></div>

		<div id="media" class="imageslider col-md-8"><?php
			$anhang = help_handle_array( $anhaenge, 'anhang' );
			@$bilder = $anhang[ 'bilder' ];

			if ( $bilder ): ?>
				<div id="carousel-example-generic"
				     class="carousel slide"
				     data-ride="carousel">
				<!-- Positionsanzeiger -->
				<ol class="carousel-indicators">
					<?php
					for ( $i = 0; $i < count( $bilder ); $i ++ ) {
						foreach ( $bilder[ $i ] as $alt => $pfad ) {
							echo '<li data-target="#carousel-example-generic" data-slide-to="' . $pfad . '"></li>';
						}
					}
					?>
				</ol>

				<!-- Verpackung für die Elemente -->
				<div class="carousel-inner" role="listbox"><?php
					for ( $j = 0; $j < count( $bilder ); $j ++ ) {
						foreach ( $bilder[ $j ] as $alt => $pfad ) {
							! empty( $alt ) ? $alt : $alt = '';
							if ( $j === 0 ):
								$str = '<div class="item active">';
							else:
								$str = '<div class="item">';
							endif;

							$str .= '<img src="' . $uploadsUrl . $pfad . '" alt="' . $alt . '">';
							$str .= '<div class="carousel-caption">';
							$str .= '';
							$str .= '</div> </div>';
							echo $str;
						}
					}
					?>
				</div>

				<!-- Schalter -->
				<a class="left carousel-control" href="#carousel-example-generic"
				   role="button"
				   data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>
					<span class="sr-only">Zurück</span>
				</a>
				<a class="right carousel-control" href="#carousel-example-generic"
				   role="button"
				   data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"
                                                          aria-hidden="true"></span>
					<span class="sr-only">Weiter</span>
				</a>
				</div><?php
			else:
				echo '<img src="' . get_option( 'wpi_img_platzhalter' ) . '"/>';
			endif;
			?>
		</div>
		<!-- Ende Media Imageslider -->
		<div id="eckdaten" class="col-md-4">
			<?php //zeigen($flaechen);
			?>
			<ul class="list-unstyled eckdaten">
				<li>
					<span class="glyphicon glyphicon-map-marker"></span>
					<span
							class="eckdaten_ort"><?php echo $geodaten[ 'plz' ] . ' ' . $geodaten[ 'ort' ] ?></span>
				</li><?php
				//TODO Abfrage wenn Adresse freigegeben, Strasse hinzufügen.
				if ( ! empty( $preis ) ):
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach ( array_filter( $preis ) as $key => $wert ) {
						if ( $key != 'Faktor' ) {
							echo '<li>' . $key . ' in  (' . $preise[ 'waehrung' ][ '@attributes' ][ 'iso_waehrung' ] . ') ';
							if ( is_numeric( $wert ) ):
								echo '<span class="price value">' . number_format( $wert, 2, ",", "." ) . '</span></li>';
							else:
								echo '<span class="price value">' . $wert . '</span></li>';
							endif;
						} else {
							echo '<li>' . $key;
							if ( is_numeric( $wert ) ):
								echo '<span class="price value">' . number_format( $wert, 2, ",", "." ) . '</span></li>';
							else:
								echo '<span class="price value">' . $wert . '</span></li>';
							endif;
						}
					}
				endif;
				if ( ! empty( $zustand_angaben[ 'baujahr' ] ) ):
					echo '<li>' . __( "Baujahr", WPI_PLUGIN_NAME ) . ' <span class="baujahr value"> ' . $zustand_angaben[ 'baujahr' ] . '</span></li>';
				endif;
				if ( ! empty( $flaechen[ 'wohnflaeche' ] ) ):
					echo '<li>' . __( "Wohnfläche in m²", WPI_PLUGIN_NAME ) . ' <span class="wohnflaeche value">' . number_format( $flaechen[ 'wohnflaeche' ], 1, ",", "" ) . '</span></li>';
				endif;
				if ( ! empty( $flaechen[ 'grundstuecksflaeche' ] ) ):
					echo '<li>' . __( "Grundstück in m²", WPI_PLUGIN_NAME ) . ' <span class="grundsteuck value">' . number_format( $flaechen[ 'grundstuecksflaeche' ], 1, ",", "" ) . '</span></li>';
				endif;
				if ( ! empty( $flaechen[ 'anzahl_zimmer' ] ) ):
					echo '<li>' . __( "Anzahl Zimmer", WPI_PLUGIN_NAME ) . ' <span class="zimmerzahl value">' . number_format( $flaechen[ 'anzahl_zimmer' ], 1, ",", "" ) . '</span></li>';
				endif;
				if ( ! empty( $verwaltung_techn[ "objektnr_extern" ] ) ):
					echo '<li>' . __( "Objektnummer", WPI_PLUGIN_NAME ) . ' <span class="objektnummer value">' . $verwaltung_techn[ "objektnr_extern" ] . '</span></li>';
				endif;
				?>
			</ul>
		</div><!-- ende Eckdaten -->

		<div class="clearfix"></div>

		<div class="panel-group" id="wpi-accordion" role="tablist" aria-multiselectable="true">
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="details">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#wpi-accordion" href="#collapseEins"
						   aria-expanded="true" aria-controls"collapseEins">
						<?php echo $tabs[ 'details' ]; ?>
						</a>
					</h4>
				</div>
				<div id="collapseEins" class="panel-collapse collapse in" role="tabpanel"
				     aria-labelledby="details">
					<div class="panel-body">

						<div class="objektdetails ">
							<div class="body">
								<table class="table table-hover table-striped">
									<tr>
										<td class="col-xs-4">Vermarktung</td>
										<td class="text-capitalize"><?php echo $vermarktung ?></td>
									</tr>
									<tr>
										<td class="col-xs-4">Objektart</td>
										<td class="text-capitalize"><?php echo $objekttyp ?></td>
									</tr>
									<tr>
										<td class="col-xs-4">Anbieterkennung</td>
										<td><?php echo $anbieterkennung; ?></td>
									</tr>
									<tr>
										<td class="col-xs-4">Objekt / Online - Nr.</td>
										<td><?php echo $verwaltung_techn[ "objektnr_extern" ] ?></td>
									</tr>
								</table>
							</div>
						</div>

						<?php if ( ! empty( $preis ) ): ?>
							<div class="preise panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __( 'Preise', WPI_PLUGIN_NAME ); ?></h3>
								</div>
								<div class="panel-body">
									<table class="table table-hover table-striped">
										<?php
										// Tabellen-Array ohne leere Werte in die Tabelle schreiben
										foreach ( array_filter( $preis ) as $key => $wert ) {
											echo '<tr>';
											if ( $key != 'Faktor' ) {
												echo '<td class="col-xs-4">' . $key . ' in  (' . $preise[ 'waehrung' ][ '@attributes' ][ 'iso_waehrung' ] . ')</td>';
												if ( is_numeric( $wert ) ):
													echo '<td>' . number_format( $wert, 2, ",", "." ) . '</td>';
												else:
													echo '<td>' . $wert . '</td>';
												endif;
											} else {
												echo '<td class="col-xs-4">' . $key . '</td>';
												if ( is_numeric( $wert ) ):
													echo '<td>' . number_format( $wert, 2, ",", "." ) . '</td>';
												else:
													echo '<td>' . $wert . '</td>';
												endif;
											}
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<!-- Div .Preise-->
						<?php endif; ?>

						<?php if ( ! empty( $flaeche ) ): ?>
							<div class="flaechen panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __( 'Flächen', WPI_PLUGIN_NAME ); ?></h3>
								</div>
								<div class="panel-body">
									<table class="table table-hover table-striped">
										<?php
										// Tabellen-Array ohne leere Werte in die Tabelle schreiben
										foreach ( array_filter( $flaeche ) as $fl_key => $fl_wert ) {
											echo '<tr>';
											echo '<td class="col-xs-4">' . $fl_key . '</td>';
											if ( is_numeric( $fl_wert ) ):
												echo '<td>' . number_format( $fl_wert, "1", ",", "" ) . '</td>';
											else:
												echo '<td>' . $fl_wert . '</td>';
											endif;
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<!-- Div .Flaechen -->
						<?php endif; ?>

						<?php if ( ! empty( $ausstattung ) ): ?>
							<div class="ausstattung panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Ausstattung</h3>
								</div>
								<div class="panel-body">
									<table class="table table-hover table-striped">
										<?php
										$ausstatt = help_handle_array( $ausstattung, 'ausstattung' );
										// Tabellen-Array ohne leere Werte in die Tabelle schreiben
										foreach ( array_filter( $ausstatt ) as $aus_key => $aus_wert ) {
											echo '<tr>';
											echo '<td class="col-xs-4">' . ucfirst( $aus_key ) . '</td>';
											echo '<td>' . $aus_wert . '</td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<!-- Div Ausstattung -->
						<?php endif; ?>

						<?php $zustand = help_handle_array( $zustand_angaben, 'zustand' ); ?>

						<?php if ( ! empty( $zustand ) ): ?>
							<div class="zustand panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Objektzustand / Energiepass</h3>
								</div>
								<div class="panel-body">
									<table class="table table-hover table-striped"><?php
										if ( array_key_exists( 'baujahr', $zustand ) ):
											echo '<tr><td class="col-xs-4">Baujahr</td>';
											echo "<td>" . $zustand[ 'baujahr' ] . "</td></tr>";
										endif;
										if ( array_key_exists( 'zustand', $zustand ) ):
											echo '<tr><td class="col-xs-4">Zustand</td>';
											echo "<td>" . $zustand[ 'zustand' ] . "</td></tr>";
										endif;
										if ( array_key_exists( 'letztemodernisierung', $zustand ) ):
											echo '<tr><td class="col-xs-4">Letzte Modernisierung</td>';
											echo "<td>" . $zustand[ 'letztemodernisierung' ] . "</td></tr>";
										endif;
										if ( array_key_exists( 'energiepass', $zustand ) && $pro != false ):
											echo '<tr><td class="col-xs-4">' . __( "Energiepass", WPI_PLUGIN_NAME ) . '</td>';
											echo '<td><ul class="list-unstyled">';
											if ( is_array( $zustand[ 'energiepass' ] ) ):
												foreach ( $zustand[ 'energiepass' ] as $en_key => $en_value ) {
													empty( $en_value ) ? $en_value = 'n.A.' : $en_value = $en_value;
													echo '<li>';
													echo ucfirst( $en_key ) . ' - <strong>' . $en_value . '</strong>';
													echo '</li>';
												}
											else:
												echo $zustand[ 'energiepass' ];
											endif;
											echo '</ul></td>';
											echo '</tr>';
										endif;
										?>
									</table>
								</div>
							</div>
							<!-- Div Zustand -->
						<?php endif; ?>

					</div>
					<div class="custom-details-div"><?php
						if ( '' != $html_inject && $html_inject === 'details' ):
							echo do_shortcode( $html );
						endif;
						?>
					</div>
				</div>
			</div><!-- panel 1 -->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="objektbeschreibung">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#wpi-accordion"
						   href="#collapseZwei" aria-expanded="false" aria-controls"collapseZwei">
						<?php echo $tabs[ 'beschreibung' ]; ?>
						</a>
					</h4>
				</div>
				<div id="collapseZwei" class="panel-collapse collapse" role="tabpanel"
				     aria-labelledby="objektbeschreibung">
					<div class="panel-body">
						<div id="beschreibung"><?php the_content(); ?></div>
						<div class="custom-beschreibung-div"><?php
							if ( '' != $html_inject && $html_inject === 'beschreibung' ):
								echo do_shortcode( $html );
							endif;
							?>
						</div>
					</div>
				</div>
			</div><!-- panel 2 -->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="doku">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#wpi-accordion"
						   href="#collapseDrei" aria-expanded="false" aria-controls"collapseDrei">
						<?php echo $tabs[ 'bilder' ]; ?>
						</a>
					</h4>
				</div>
				<div id="collapseDrei" class="panel-collapse collapse" role="tabpanel"
				     aria-labelledby="doku">
					<div class="panel-body"><?php

						if ( @$anhang[ 'dokumente' ] > 0 ): ?>
							<div id="dokumente" class="">

								<ul class="list-unstyled"><?php
									for ( $i = 0; $i < count( $anhang[ 'dokumente' ] ); $i ++ ) {
										foreach ( $anhang[ 'dokumente' ][ $i ] as $name => $link ) {
											$offset = 1;
											$ext    = '<span class="glyphicon glyphicon-new-window"></span>';
											if ( $name != '' ):
												$name = $name;
											else:
												$name = 'Dokument ' . $offset;
												$offset ++;
											endif;
											echo '<li><a target="_blank" href="' . $uploadsUrl . $link . '">' . $ext . ' ' . $name . '</a></li>';
										}
									}
									?>
								</ul>
							</div><!-- Ende Dokumente -->

							<?php
						else:
							echo '<p class="lead">Keine Dokumente vorhanden</p>';
						endif;
						?>

					</div>
					<div class="custom-doku-div"><?php
						if ( '' != $html_inject && $html_inject === 'dokumente' ):
							echo do_shortcode( $html );
						endif;
						?>
					</div>
				</div>
			</div><!-- panel 3 -->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="überschriftVier">
					<h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#wpi-accordion"
						   href="#collapseVier" aria-expanded="false" aria-controls"collapseDrei">
						<?php echo $tabs[ 'kontakt' ]; ?>
						</a>
					</h4>
				</div>
				<div id="collapseVier" class="panel-collapse collapse" role="tabpanel"
				     aria-labelledby="überschriftVier">
					<div class="panel-body"><?php
						if ( $firma ):
							?>
							<div id="firma" class="panel panel-default">
							<div class="panel-heading">
								<h3><?= __( 'Anbieter / Makler', WPI_PLUGIN_NAME ); ?></h3>
							</div>
							<div class="panel-body"><?php
								// Abfrage wenn Firma ein String ist
								if ( ! is_array( $firma ) ) {
									echo $firma;
								} else {
									$count = 1;
									echo '<ul class="list-unstyled">';
									foreach ( $firma as $value ) {
										echo '<li id="li-' . $count . '">' . $value . '</li>';
										$count ++;
									}
									echo '</ul>';
								}
								?>
							</div>
							</div><?php
						endif;
						if ( $kontaktperson ):
							?>
							<div id="ansprechpartner" class="panel panel-default">
							<div class="panel-heading">
								<h3>Ansprechpartner</h3>
							</div>
							<div class="panel-body">
								<?php $kontakt = help_handle_array( $kontaktperson, 'kontakt' ); ?>
								<dl class="dl-horizontal"><?php

									foreach ( $kontakt as $bez => $value ) {
										echo '<dt>' . ucfirst( $bez ) . '</dt>';
										echo '<dd>' . $value . '</dd>';
									}
									?>

								</dl>
							</div>
							</div><?php
						endif;
						?>

					</div>
					<div class="custom_kontakt_div"><?php
						if ( '' != $html_inject && $html_inject === 'kontaktperson' ):
							echo do_shortcode( $html );
						endif;
						?>
					</div>
				</div>
			</div><!-- panel 4 -->

		</div><!-- Ende wpi-accordion -->

		<div class="clearfix"></div>

		<hr>

		<div class="search-div">
			<?php echo view_searchfield_wpmi(); ?>
		</div><!-- search-div -->
	</article>
	<?php
	return ob_get_clean();
}

// Funktion Single Immobilie als One Page
function view_single_onepage() {
	ob_start();
	?>
	<h2>One Page Ansicht</h2>
	<p class="lead">Diese Ansicht ist leider noch nicht fertig. </p>
	<?php
	return ob_get_clean();
}

// Funktion der Details zur Anzeige des OpenImmo Formats Single-Post
function view_single_openimmo() {
	//global $post;
	global $meta;
	$kontaktstr    = $meta[ "_kontaktperson" ][ 0 ];
	$kontaktperson = unserialize( $kontaktstr );
	if ( ! is_array( $kontaktperson ) ) {
		$kontaktperson = unserialize( $kontaktperson );
	}

	// Laden der Optinen aus DB

	$single_preise      = get_option( 'wpi_single_preise' );
	$single_flaechen    = get_option( 'wpi_single_flaechen' );
	$single_ausstattung = get_option( 'wpi_single_ausstattung' );

	// Überprüfung ob die Optionen in den Meta vorhanden sind

	if ( ! empty( $single_preise ) ):
		foreach ( $single_preise as $preis_key => $preis_value ) {
			if ( array_key_exists( $preis_key, $meta ) ):
				$preise[ $preis_value ] = $meta[ $preis_key ][ 0 ];
			endif;
		}
	endif;

	if ( ! empty( $single_flaechen ) ):
		foreach ( $single_flaechen as $fl_key => $fl_value ) {
			if ( array_key_exists( $fl_key, $meta ) ):
				$flaechen[ $fl_value ] = $meta[ $fl_key ][ 0 ];
			endif;
		}
	endif;

	if ( ! empty( $single_ausstattung ) ):
		foreach ( $single_ausstattung as $aus_key => $aus_value ) {
			if ( array_key_exists( $aus_key, $meta ) ):
				$ausstattung[ $aus_value ] = $meta[ $aus_key ][ 0 ];
			endif;
		}
	endif;
	?>

	<?php if ( ! empty( $preise ) && array_filter( $preise ) ):
		array_walk( $preise, 'trim_value' ) ?>
		<div class="preise panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Preise</h3>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-striped">
					<?php
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach ( array_filter( $preise ) as $key => $wert ) {
						echo '<tr>';
						echo '<td class="col-xs-4">' . $key . '</td>';
						echo '<td>' . $wert . '</td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
		</div>
		<!-- Div Preise-->
	<?php endif; ?>

	<?php if ( ! empty( $flaechen ) && array_filter( $flaechen ) ): ?>
		<div class="flaechen panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Flächen</h3>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-striped">
					<?php
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach ( array_filter( $flaechen ) as $fl_key => $fl_wert ) {
						echo '<tr>';
						echo '<td class="col-xs-4">' . $fl_key . '</td>';
						echo '<td>' . $fl_wert . '</td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
		</div>
		<!-- Div Flaechen -->
	<?php endif; ?>

	<?php if ( ! empty( $ausstattung ) && array_filter( $ausstattung ) ): ?>
		<div class="ausstattung panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Ausstattung</h3>
			</div>
			<div class="panel-body">
				<table class="table table-hover table-striped">
					<?php
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach ( array_filter( $ausstattung ) as $aus_key => $aus_wert ) {
						echo '<tr>';
						echo '<td class="col-xs-4">' . $aus_key . '</td>';
						echo '<td>' . $aus_wert . '</td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
		</div>
		<!-- Div Ausstattung -->
	<?php endif; ?>

	<div class="energieausweis panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Energieausweis</h3>
		</div>
		<div class="panel-body">
			...
		</div>
	</div>
	<!-- Ende Div Energieausweis -->


	<div class="objektdaten panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Sonstige Angaben</h3>
			<div class="panel-body">
				<table class="table table-hover table-striped">
					<tr>
						<td>Anbieter Kennung:</td>
						<td>12345(Statische Eingabe)</td>
					</tr>
					<tr>
						<td>Objekt / Online-Nr.</td>
						<td>12345(Statische Eingabe)</td>
					</tr>
					<tr>
						<td>Objektart:</td>
						<td><a href="#">Haus(Statische Eingabe)</a></td>
					</tr>
					<tr>
						<td>Vermarktungsart:</td>
						<td><a href="#">Kauf (StatischeEingabe)</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<!-- Div Objektdaten -->

	<?php
}

// Funktion der Details zur Anzeige des IS24 Formats Single-Post
function view_single_is24() {
	//TODO Single_IS24 Template ist noch nicht angepasst
	?>
	<div class="details col-lg-6">
		<h3>Details:</h3>
		<?php
		$taxonomies  = get_the_taxonomies();
		$objekttyp   = strstr( $taxonomies[ 'objekttyp' ], ' ' );
		$vermarktung = strstr( $taxonomies[ 'vermarktungsart' ], ' ' );
		?>
		<div class="details-inner">
			<p class="lead">Objekttyp: <?= $objekttyp; ?><br/>
				Vermarktungsart: <?= $vermarktung; ?></p>
			<dl class="dl-horizontal">
				<?php if ( ! empty( $meta[ 'WohnungKategorie' ][ '0' ] ) ):
					echo "<dt>Objekttyp:</dt><dd>" . $meta[ 'WohnungKategorie' ][ '0' ] . "</dd>";
				endif;
				?>
				<?php if ( ! empty( $meta[ 'Zimmer' ][ '0' ] ) ) {
					echo "<dt>Zimmer:</dt><dd>" . $meta[ 'Zimmer' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'Wohnflaeche' ][ '0' ] ) ) {
					echo "<dt>Wohnfläche ca.:</dt><dd>" . $meta[ 'Wohnflaeche' ][ '0' ] . " m²</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'Etage' ][ '0' ] ) ) {
					echo "<dt>Etage:</dt><dd>" . $meta[ 'Etage' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'Etagenzahl' ][ '0' ] ) ) {
					echo "<dt>Etagenanzahl:</dt><dd>" . $meta[ 'Etagenzahl' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'AnzahlSchlafzimmer' ][ '0' ] ) ) {
					echo "<dt>Schlafzimmer:</dt><dd>" . $meta[ 'AnzahlSchlafzimmer' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'AnzahlBadezimmer' ][ '0' ] ) ) {
					echo "<dt>Badezimmer:</dt><dd>" . $meta[ 'AnzahlBadezimmer' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'FreiAb' ][ '0' ] ) ) {
					echo "<dt>Bezugsfrei ab:</dt><dd>" . $meta[ 'FreiAb' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'Haustiere' ][ '0' ] ) ) {
					echo "<dt>Haustiere:</dt><dd>" . $meta[ 'Haustiere' ][ '0' ] . "</dd>";
				} ?>
				<?php if ( ! empty( $meta[ 'Ausstattungsqualitaet' ][ '0' ] ) ) {
					echo "<dt>Ausstattung:</dt><dd>" . $meta[ 'Ausstattungsqualitaet' ][ '0' ] . "</dd>";
				} ?>
				<?php //if ($meta['Garage']['0'] != '') echo "
				//               <dt>Anzahl Garage/Stellplatz:</dt><dd>" . $meta['Garage']['0'] . "</dd>";
				?>
				<dt>Einbauküche:</dt>
				<dd><?php if ( ! empty( $meta[ 'Einbaukueche' ][ '0' ] ) && $meta[ 'Einbaukueche' ][ '0' ] == '1' ) {
						echo 'Ja';
					} else {
						echo 'Nein';
					} ?></dd>
				<dt>Balkon/Terrasse:</dt>
				<dd><?php if ( ! empty( $meta[ 'BalkonTerrasse' ][ '0' ] ) && $meta[ 'BalkonTerrasse' ][ '0' ] == '1' ) {
						echo 'Ja';
					} else {
						echo 'Nein';
					} ?></dd>
				<dt>Keller:</dt>
				<dd><?php if ( ! empty( $meta[ 'Keller' ][ '0' ] ) ) {
						echo $meta[ 'Keller' ][ '0' ];
					} else {
						echo 'Nein';
					} ?></dd>
				<dt>Aufzug vorhanden:</dt>
				<dd><?php if ( ! empty( $meta[ 'Aufzug' ][ '0' ] ) ) {
						echo $meta[ 'Aufzug' ][ '0' ];
					} else {
						echo 'Nein';
					} ?></dd>
			</dl>

		</div>
	</div>
	<!-- Details 1 -->
	<div class="details col-lg-6">
		<h3>Kosten:</h3>

		<div class="details-inner">
			<dl class="dl-horizontal">
				<?php if ( ! empty( $meta[ 'Kaufpreis' ][ 0 ] ) ): ?>
					<dt>Kaufpreis:</dt>
					<dd><?= $meta[ 'Kaufpreis' ][ '0' ]; ?> €</dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'Kaltmiete' ][ 0 ] ) ): ?>
					<dt>Kaltmiete:</dt>
					<dd><?= $meta[ 'Kaltmiete' ][ '0' ]; ?> €</dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'Nebenkosten' ][ 0 ] ) ): ?>
					<dt>Nebenkosten:</dt>
					<dd><?= $meta[ 'Nebenkosten' ][ '0' ]; ?> €</dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'HeizkostenInWarmmieteEnthalten' ][ 0 ] ) ): ?>
					<dt>Heizkosten:</dt>
					<dd>
						<?php
						if ( $meta[ 'HeizkostenInWarmmieteEnthalten' ][ '0' ] == 'true' ):
							echo 'in Nebenkosten enthalten';
						else:
							echo 'nicht in Nebenkosten erfasst';
						endif;
						?>
					</dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'Warmmiete' ][ 0 ] ) ): ?>
					<dt><strong>Gesamtmiete:</strong></dt>
					<dd><strong><?= $meta[ 'Warmmiete' ][ '0' ]; ?> €</strong></dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'Kaution' ][ 0 ] ) ): ?>
					<dt>Kaution:</dt>
					<dd><?php echo $meta[ 'Kaution' ][ '0' ]; ?></dd>
				<?php endif; ?>
				<?php if ( ! empty( $meta[ 'Provision' ][ '0' ] ) ): ?>
					<dt>Provision:</dt>
					<dd><?= $meta[ 'Provision' ][ '0' ]; ?> </dd>
				<?php endif; ?>
			</dl>
		</div>
		<!-- Ende class="" -->
	</div>
	<!-- Ende Details 2-->
	<?php
}

// Funktion der Details zur Anzeige als Excerpt
function view_list_excerpt( $meta ) {
	ob_start();
	$uploadUrl = get_option( 'wpi_upload_url' );
	$anhaenge  = unserialize( $meta[ 'anhaenge' ][ 0 ] );
	$anhang    = help_handle_array( $anhaenge, 'anhang' );
	@$bilder = $anhang[ 'bilder' ];;

	// Auslesen der Kategorien
	$taxonomies  = get_the_taxonomies();
	$objekttyp   = strtolower( strstr( $taxonomies[ 'objekttyp' ], ' ' ) );
	$vermarktung = strtolower( strstr( $taxonomies[ 'vermarktungsart' ], ' ' ) );
	$objekttyp   = trim( $objekttyp, '.' );
	$vermarktung = trim( $vermarktung, '.' )

	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'archiv-immobilien' ); ?>>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<header class="wpi-header">
						<h2><span class="glyphicon glyphicon-folder-close"> </span> <a
									href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					</header>
					<div class="col-md-3">
						<div>
							<a href="<?php the_permalink(); ?>" class="thumbnail">
								<?php
								//Bilder aus Meta laden
								if ( ! empty( $bilder[ 0 ] ) ):
									$first_image = array_values( $bilder[ '0' ] );
									?>
									<img src="<?php echo $uploadUrl . $first_image[ 0 ]; ?> "/>
									<?php
								//unset($bilder);
								else:
									?>
									<img
											src="<?php echo WPI_PLUGIN_URL . 'images/Fotolia_61039451_XS.jpg'; ?> "
											alt="Platzhalter"/>
									<?php
								endif;
								?>
							</a>
							<div class="text-center">
								<span class="info-text text-capitalize"><?= $objekttyp ?></span>
								<span class="info-text text-capitalize"><?= $vermarktung ?></span>
							</div>
							<div class="visible-xs">
								<hr/>
							</div>
						</div>
						<?php if ( isset( $meta[ 'topimmo' ] ) && get_option( 'wpi_show_top_immo' ) == 'true' ): ?>
							<div>
								<img
										style="width: 60px; height: auto; border-radius: 50%; position: absolute; top: -10px; right: 0px;"
										src="<?= get_option( 'wpi_top_immo_source' ); ?>"/>
							</div>
						<?php endif; ?>

					</div>

					<div class="col-md-9">
						<?php $exc_length = get_option( 'wpi_list_excerpt_length' ); ?>
						<div class="">
							<?php echo wp_trim_words( get_the_excerpt(), $exc_length ); ?>
						</div>
					</div>
				</div>
				<div class="more pull-right">
					<a href="<?php the_permalink(); ?>">
						<button type="button" class="btn btn-default">
							Mehr Details <span class="glyphicon glyphicon-chevron-right"></span>
						</button>
					</a>
				</div>
				<!-- more -->
			</div>
			<!-- .panel-body -->
		</div>
	</article>

	<?php
	return ob_get_clean();
}

// Funktion der Details zur Anzeige des OpenImmo Formats Archive-Post
function view_list_openimmo( $meta ) {

	$anbieterkennung = $meta[ 'anbieterkennung' ][ 0 ];
	$firma           = $meta[ 'anbieterfirma' ][ 0 ];
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

	ob_start();

	$anhang = help_handle_array( $anhaenge, 'anhang' );
	@$bilder = $anhang[ 'bilder' ];
	$uploadUrl = get_option( 'wpi_upload_url' );
	// Auslesen der Kategorien
	$taxonomies  = get_the_taxonomies();
	$objekttyp   = strtolower( strstr( $taxonomies[ 'objekttyp' ], ' ' ) );
	$vermarktung = strtolower( strstr( $taxonomies[ 'vermarktungsart' ], ' ' ) );
	$objekttyp   = trim( $objekttyp, '.' );
	$vermarktung = trim( $vermarktung, '.' );
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'archiv-immobilien' ); ?>>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<header class="wpi-header">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					</header>
					<div class="col-md-3">
						<div>
							<a href="<?php the_permalink(); ?>" class="thumbnail">
								<?php
								//Bilder aus Meta laden
								if ( ! empty( $bilder[ 0 ] ) ):
									$first_image = array_values( $bilder[ '0' ] );
									?>
									<img src="<?php echo $uploadUrl . $first_image[ 0 ]; ?> "/>
									<?php
								//unset($bilder);
								else:
									?>
									<img src="<?= get_option( 'wpi_img_platzhalter' ); ?>"/>
									<?php
								endif;
								?>
							</a>
							<div class="text-center">
								<span class="info-text text-capitalize"><?= $objekttyp ?></span>
								<span class="info-text text-capitalize"><?php echo $vermarktung ?></span>
							</div>
							<div class="visible-xs">
								<hr/>
							</div>
						</div>
						<?php if ( isset( $meta[ 'topimmo' ] ) && get_option( 'wpi_show_top_immo' ) == 'true' ): ?>
							<div class="topimmo">
								<img src="<?php echo get_option( 'wpi_top_immo_source' ); ?>"/>
							</div>
						<?php endif; ?>
						<?php if ( isset( $meta[ 'sold' ] ) && get_option( 'wpi_show_sold' ) == 'true' ): ?>
							<div class="sold-text">
								<p><?php echo get_option( 'wpi_sold_text' ); ?></p>
							</div>
						<?php endif; ?>
						<?php if ( isset( $meta[ 'reserved' ] ) && get_option( 'wpi_show_reserved' ) == 'true' ): ?>
							<div class="reserved-text">
								<p><?php echo get_option( 'wpi_reserved_text' ); ?></p>
							</div>
						<?php endif; ?>

					</div>

					<div class="col-md-9"><?php
						if ( get_option( 'wpi_list_excerpt' ) == 'true' ): ?>
							<?php $exc_length = get_option( 'wpi_list_excerpt_length' ); ?>
							<div class="">
							<?php echo wp_trim_words( get_the_excerpt(), $exc_length ); ?>
							</div><?php
						elseif ( get_option( 'wpi_list_excerpt' ) == 'false' ):
							$list_details = get_option( 'wpi_list_detail' );
							if ( ! empty( $list_details ) ):
								foreach ( $list_details as $listkey => $listvalue ) {
									if ( array_key_exists( $listkey, $preise ) ):
										if ( is_numeric( $preise[ $listkey ] ) ):
											$liste[ $listvalue ] = number_format( $preise[ $listkey ], 2, ',', '.' );
										else:
											$liste[ $listvalue ] = $preise[ $listkey ];
										endif;
									endif;
									if ( array_key_exists( $listkey, $flaechen ) ):
										if ( is_numeric( $flaechen[ $listkey ] ) ):
											$liste[ $listvalue ] = number_format( $flaechen[ $listkey ], 1, ',', '' );
										else:
											$liste[ $listvalue ] = $flaechen[ $listkey ];
										endif;
									endif;
								}
							endif;
							?>
							<table class="table table-hover">
								<tr>
									<td class="col-xs-4"><?= __( 'PLZ / Ort', WPI_PLUGIN_NAME ); ?></td>
									<td>
										<span class="glyphicon glyphicon-map-marker"></span>
										<span class="eckdaten_ort">
                                            <?php echo $geodaten[ 'plz' ] . ' ' . $geodaten[ 'ort' ] ?>
                                        </span>
									</td>
								</tr>
								<?php
								// Tabellen-Array ohne leere Werte in die Tabelle schreiben
								if ( ! empty( $liste ) && array_filter( $liste ) ):
									array_walk( $liste, 'trim_value' );
									//zeigen(array_filter($liste));
									foreach ( array_filter( $liste ) as $key => $wert ) {
										echo '<tr>';
										echo '<td class="col-xs-4">' . $key . '</td>';
										echo '<td>' . $wert . '</td>';
										echo '</tr>';
									}
								endif;

								?>
							</table>
						<?php endif; ?>
					</div>
				</div>
				<div class="more pull-right">
					<a href="<?php the_permalink(); ?>">
						<button type="button" class="btn btn-default">
							Mehr Details <span class="glyphicon glyphicon-chevron-right"></span>
						</button>
					</a>
				</div>
				<!-- more -->
			</div>
			<!-- .panel-body -->
		</div>
	</article>


	<?php
	return ob_get_clean();
}

function view_list_columns( $meta ) {
	$uploadsUrl = get_option( 'wpi_upload_url' );

	$anbieterkennung = $meta[ 'anbieterkennung' ][ 0 ];
	$firma           = $meta[ 'anbieterfirma' ][ 0 ];
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

	ob_start();

	$anhang = help_handle_array( $anhaenge, 'anhang' );
	@$bild = $anhang[ 'bilder' ][ 0 ];
	// Setzen der Variable "Preis"
	if ( array_key_exists( 'kaufpreis', $preise ) ):
		$preis = preg_replace( "#[.].*#", "", $preise[ 'kaufpreis' ] );
		$preis = 'Kaufpreis: ' . $preis . ' ' . $preise[ "waehrung" ][ "@attributes" ][ "iso_waehrung" ];
	elseif ( array_key_exists( 'kaltmiete', $preise ) ):
		$preis = preg_replace( "#[.].*#", "", $preise[ 'kaltmiete' ] );
		$preis = 'Kaltmiete: ' . $preis . ' ' . $preise[ "waehrung" ][ "@attributes" ][ "iso_waehrung" ];
	endif;
	// Link zur Single-Seite
	$link = get_permalink();
	// Auslesen der Kategorien
	$taxonomies  = get_the_taxonomies();
	$objekttyp   = strtolower( strstr( $taxonomies[ 'objekttyp' ], ' ' ) );
	$vermarktung = strtolower( strstr( $taxonomies[ 'vermarktungsart' ], ' ' ) );
	$vermarktung = trim( $vermarktung, '.' );
	$objekttyp   = trim( $objekttyp, '.' );
	preg_match( '#.*[_]{1}#', $vermarktung, $miete );
	if ( $miete ) {
		$miete = trim( $miete[ 0 ], '_' );
	}
	// Listen Array aus den Options holen
	$list_details = get_option( 'wpi_list_detail' );
	if ( ! empty( $list_details ) ):
		foreach ( $list_details as $listkey => $listvalue ) {
			if ( array_key_exists( $listkey, $preise ) ):
				if ( is_numeric( $preise[ $listkey ] ) ):
					$liste[ $listvalue ] = number_format( $preise[ $listkey ], 2, ',', '.' );
				else:
					$liste[ $listvalue ] = $preise[ $listkey ];
				endif;
			endif;
			if ( array_key_exists( $listkey, $flaechen ) ):
				if ( is_numeric( $flaechen[ $listkey ] ) ):
					$liste[ $listvalue ] = number_format( $flaechen[ $listkey ], 1, ',', '' );
				else:
					$liste[ $listvalue ] = $flaechen[ $listkey ];
				endif;
			endif;
		}
	endif;

//zeigen($verwaltung_objekt);
	?>

	<div class="col-sm-6 col-md-4 immo-columns">
		<div class="thumbnail"><?php

			if ( ! empty( $bild ) ) {
				echo '<a href="' . $link . '"><img src="' . $uploadsUrl . current( $bild ) . '" alt="' . current( $bild ) . '"></a>';
			} else {
				echo '<a href="' . $link . '"><img src="' . get_option( "wpi_img_platzhalter" ) . '"/></a>';
			}
			?>
			<?php if ( isset( $meta[ 'topimmo' ] ) && get_option( 'wpi_show_top_immo' ) == 'true' ): ?>
				<div class="topimmo">
					<img src="<?= get_option( 'wpi_top_immo_source' ); ?>"/>
				</div>
			<?php endif; ?>
			<?php if ( isset( $meta[ 'sold' ] ) && get_option( 'wpi_show_sold' ) == 'true' ): ?>
				<div class="sold-text">
					<p><?= get_option( 'wpi_sold_text' ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( isset( $meta[ 'reserved' ] ) && get_option( 'wpi_show_reserved' ) == 'true' ): ?>
				<div class="reserved-text">
					<p><?= get_option( 'wpi_reserved_text' ); ?></p>
				</div>
			<?php endif; ?>

			<?php echo '<span class="preis">' . $preis . '</span>'; ?>

			<div class="caption">
				<h4><?php the_title(); ?></h4>
				<table>
					<tr>
						<td><?= __( 'PLZ / Ort' ); ?></td>
						<td><span class="eckdaten_ort">
                                <?php echo $geodaten[ 'plz' ] . ' ' . $geodaten[ 'ort' ] ?>
                            </span>
						</td>
					</tr>
					<tr>
						<td><?= __( 'Objektart' ); ?></td>
						<td><span class="text-capitalize"><?php echo $objekttyp; ?></span></td>
					</tr>
					<tr>
						<td><?= __( 'Vermarktung' ); ?></td>
						<td><span class="text-capitalize"><?php echo $vermarktung; ?></span></td>
					</tr>
					<?php
					// TODO Ort-PLZ wie bei list-view hinzufügen
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					if ( ! empty( $liste ) && array_filter( $liste ) ):
						array_walk( $liste, 'trim_value' );
						//zeigen(array_filter($liste));
						foreach ( array_filter( $liste ) as $key => $wert ) {
							echo '<tr>';
							echo '<td>' . $key . '</td>';
							echo '<td>' . $wert . '</td>';
							echo '</tr>';
						}
					endif;

					?>
				</table>
				<p class="text-center"><a href="<?php echo $link; ?>" class="btn btn-default" role="button">Ansehen</a>
				</p>
			</div>
		</div>
	</div>

	<?php
	return ob_get_clean();
}

// Funktion der Details zur Anzeige des IS24 Formats Archive-Post
function view_list_is24() {

}

// Template Funktion, falls keine Immobilie gefunden wurde.
// TODO muss einstellbar gemacht und in die Klasse verlagert werden
function view_no_founds() {
	ob_start();
	?>
	<!-- Keine Immobilien gefunden -->
	<article id="no_found" <?php post_class( 'archiv-immobilien' ); ?>>
		<h2>Zur Zeit leider kein Angebot!</h2>
	</article>
	<?php
	return ob_get_clean();
}

if ( ! function_exists( 'view_searchfield_wpmi' ) ) {
	function view_searchfield_wpmi() {
		ob_start(); ?>
		<div class="search col-xs-12">
			<form class="search-field" role="search" action="<?php echo home_url( '/' ); ?>" method="get">
				<div class="form-group">
					<label>
						<span class="screen-reader-text"><?php echo _x( 'Suche:', 'label' ) ?></span>
						<input type="search" class="form-control"
						       placeholder="<?= __( 'Suche...', WPI_PLUGIN_NAME ); ?>"
						       value="<?php echo get_search_query() ?>" name="s"
						       title="<?php echo esc_attr_x( 'Suche:', 'label' ) ?>"/>
					</label>
					<button type="submit" class="btn btn-default"><?= __( 'Los', WPI_PLUGIN_NAME ); ?></button>
				</div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}

/**
 * @param $arg = array to handle
 * @param $arg2 = argument to handle
 *
 * @return $array handeled
 */
if ( ! function_exists( 'help_handle_array' ) ) {
	function help_handle_array( $arg, $arg2 ) {
		$array     = $arg;
		$new_array = array();

		// Vorbereiten des Ausstattung Arrays
		if ( $arg2 === 'ausstattung' ) {
			$glyph   = '<span class="glyphicon glyphicon-ok"></span>';
			$glyph_x = '<span class="glyphicon glyphicon-remove"></span>';

			$texte = array(
				'ausstatt_kategorie'       => 'Ausstattungskategorie',
				'wg_geeignet'              => 'WG-Geeignet',
				'raeume_veraenderbar'      => 'Räume veränderbar',
				'kueche'                   => 'Küche',
				'ausricht_balkon_terrasse' => 'Ausrichtung Balkon/ Terrasse',
				'moebliert'                => 'Möbeliert',
				'kabel_sat_tv'             => 'Kabel/SAT TV',
				'wasch_trockenraum'        => 'Wasch / Trockenraum',
				'dv_verkabelung'           => 'DV Verkabelung',
				'hebebuehne'               => 'Hebebühne',
				'kantine_cafeteria'        => 'Kantine/Cafeteria',
				'teekueche'                => 'Teeküche',
				'hallenhoehe'              => 'Hallenhöhe',
				'angeschl_gastronomie'     => 'Mit Gastronomie',
				'telefon_ferienimmobilie'  => 'Telefon Ferienimmobilie',
				'gaestewc'                 => 'Gäste-WC',
				'kabelkanaele'             => 'Kabelkanäle',
				'breitband_zugang'         => 'Breitband Internet',
				'umts_empfang'             => 'UMTS Empfang'
			);

			$textchange = changeKeyNames( $array, $texte );

			foreach ( $textchange as $key1 => $value1 ) {
				//TODO Das "user_defined_simplefield" vorerst ausgeblendet!!!
				if ( $key1 != 'user_defined_simplefield' ) {
					if ( ! is_array( @$array[ $key1 ] ) ) {
						$key1 = str_replace( '_', '-', $key1 );
						@$new_array[ $key1 ] = ucfirst( strtolower( $value1 ) );
					} else {
						foreach ( $array[ $key1 ] as $key2 => $value2 ) {
							unset( $values );
							foreach ( $array[ $key1 ][ $key2 ] as $key3 => $value3 ) {
								$values[] = ucfirst( strtolower( $key3 ) );
							}
							$key1 = str_replace( '_', '-', $key1 );
							@$new_array[ $key1 ] = implode( ', ', $values );
						}
					}
				}
			}
			// Prüfen der Values mit True oder 1 und ausgeben als Glyphicon
			foreach ( $new_array as $key => $item ) {
				if ( strtolower( $item ) === 'true' || $item === '1' ) {
					$new_array[ $key ] = $glyph;
				} elseif ( strtolower( $item ) === 'false' || $item === '0' ) {
					$new_array[ $key ] = $glyph_x;
				}
			}

			return @$new_array;

		} // Vorbereiten des Zustand-Arrays
		elseif ( $arg2 === 'zustand' ) {

			$new_array  = array();
			$epart_keys = array(
				'epart'                    => __( 'Energieausweistyp' ),
				'art'                      => __( 'Energieausweistyp' ),
				'gueltig_bis'              => __( 'Gültig bis' ),
				'energieverbrauchkennwert' => __( 'Energieverbrauchkennwert' ),
				'mitwarmwasser'            => __( 'Mit Warmwasser' ),
				'endenergiebedarf'         => __( 'Energiebedarf' ),
				'primaerenergietraeger'    => __( 'Wesentlicher Energieträger' ),
				'stromwert'                => __( 'Stromwert' ),
				'waermewert'               => __( 'Wärmewert' ),
				'wertklasse'               => __( 'Wertklasse' ),
				'baujahr'                  => __( 'Baujahr' ),
				'ausstelldatum'            => __( 'Ausstelldatum' ),
				'jahrgang'                 => __( 'Jahrgang des Energieausweises' ),
				'gebaeudeart'              => __( 'Gebäudeart' )
			);

			foreach ( $array as $key1 => $value1 ) {

				if ( ! is_array( $array[ $key1 ] ) ) {
					$new_array[ $key1 ] = ucfirst( strtolower( $value1 ) );
				} else {
					foreach ( $array[ $key1 ] as $key2 => $value2 ) {
						// Wenn Attribute vorhanden
						if ( is_array( $array[ $key1 ] ) && array_key_exists( '@attributes', $array[ $key1 ] ) ) {
							foreach ( $array[ $key1 ][ '@attributes' ] as $key3 => $value3 ) {
								unset( $values );
								$values[] = ucfirst( strtolower( $value3 ) );
							}
							$new_array[ $key1 ] = implode( ', ', $values );
						} else {
							@$new_array[ $key1 ][ $key2 ] = ucfirst( strtolower( $value2 ) );
						}
					}
				}
			}
			//zeigen($new_array['energiepass']);
			/**
			 * Anpassung der Energieausweisdaten...
			 **/
			// Options-Texte
			$epass_texte = get_option( 'wpi_single_epass' );
			// Wenn kein Energiepass übergeben wurde
			if ( ! @$new_array[ 'energiepass' ] || @$new_array[ 'energiepass' ][ 'jahrgang' ] === 'Ohne' ) {
				$new_array[ 'energiepass' ] = $epass_texte[ 'nicht_vorhanden' ];
			} // Wenn Energieausweis nicht erforderlich z.B. bei Denkmalschutz
			elseif ( @$new_array[ 'enrgiepass' ][ 'jahrgang' ] === 'Nicht_noetig' ) {
				$new_array[ 'energiepass' ] = $epass_texte[ 'nicht_benoetigt' ];
			} // Bei übergebenem Energieausweis anpassen der Values
			else {
				// Value für "epart"
				$einheit = ' kWh/(m²*a)';
				switch ( @$new_array[ 'energiepass' ][ 'epart' ] ) {
					case 'Verbrauch':
						$new_array[ 'energiepass' ][ 'epart' ] = __( 'Verbrauchsausweis' );
						if ( @$new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] > 0 ) {
							$new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] = $new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] . $einheit;
						}
						if ( @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] === 'True' ) {
							$new_array[ 'energiepass' ][ 'mitwarmwasser' ] = __( 'Energieverbrauch für Warmwasser enthalten' );
						} elseif ( @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] === 'False' ) {
							$new_array[ 'energiepass' ][ 'mitwarmwasser' ] = __( 'Energieverbrauch für Warmwasser enthalten' );
						} else {
							unset( $new_array[ 'energiepass' ][ 'mitwarmwasser' ] );
						}
						break;
					case 'Bedarf':
						$new_array[ 'energiepass' ][ 'epart' ] = __( 'Bedarfsausweis' );
						if ( @$new_array[ 'energiepass' ][ 'endenergiebedarf' ] > 0 ) {
							@$new_array[ 'energiepass' ][ 'endenergiebedarf' ] = $new_array[ 'energiepass' ][ 'endenergiebedarf' ] . $einheit;
						}
						if ( @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] > 0 && @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] === 'True' ) {
							$new_array[ 'energiepass' ][ 'mitwarmwasser' ] = __( 'Energieverbrauch für Warmwasser enthalten' );
						}
						break;
				}
				// Value für "ART"
				switch ( @$new_array[ 'energiepass' ][ 'art' ] ) {
					case 'Verbrauch':
						$new_array[ 'energiepass' ][ 'art' ] = __( 'Verbrauchsausweis' );
						if ( @$new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] > 0 ) {
							$new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] = $new_array[ 'energiepass' ][ 'energieverbrauchkennwert' ] . $einheit;
						}
						if ( @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] === 'True' ) {
							$new_array[ 'energiepass' ][ 'mitwarmwasser' ] = __( 'Energieverbrauch für Warmwasser enthalten' );
						} elseif ( @$new_array[ 'energiepass' ][ 'mitwarmwasser' ] === 'False' ) {
							$new_array[ 'energiepass' ][ 'mitwarmwasser' ] = __( 'Energieverbrauch für Warmwasser nicht enthalten' );
						} else {
							unset( $new_array[ 'energiepass' ][ 'mitwarmwasser' ] );
						}
						break;
					case 'Bedarf':
						$new_array[ 'energiepass' ][ 'art' ] = __( 'Bedarfsausweis' );
						if ( @$new_array[ 'energiepass' ][ 'endenergiebedarf' ] > 0 ) {
							@$new_array[ 'energiepass' ][ 'endenergiebedarf' ] = $new_array[ 'energiepass' ][ 'endenergiebedarf' ] . $einheit;
						}
						/*if (@$new_array['energiepass']['mitwarmwasser'] > 0 || @$new_array['energiepass']['mitwarmwasser'] === 'true') {
														$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
												}*/
						break;
				}
				// Value für Gültig
				if ( @$new_array[ 'energiepass' ][ 'gueltig_bis' ] ) {
					$gueltig                                     = $new_array[ 'energiepass' ][ 'gueltig_bis' ];
					$format                                      = 'd.m.Y';
					$datum                                       = strtotime( $gueltig );
					$new_array[ 'energiepass' ][ 'gueltig_bis' ] = date( $format, $datum );
				}

				// Value für Ausstelldatum
				if ( @$new_array[ 'energiepass' ][ 'ausstelldatum' ] ) {
					$ausstell                                      = $new_array[ 'energiepass' ][ 'ausstelldatum' ];
					$format                                        = 'd.m.Y';
					$datum                                         = strtotime( $ausstell );
					$new_array[ 'energiepass' ][ 'ausstelldatum' ] = date( $format, $datum );

				}
				// Value für "gebaeudeart"
				switch ( @$new_array[ 'energiepass' ][ 'gebaeudeart' ] ) {
					case 'Wohn':
						$new_array[ 'energiepass' ][ 'gebaeudeart' ] = __( 'Wohngebäude' );
						break;
					case 'Nichtwohn':
						$new_array[ 'energiepass' ][ 'gebaeudeart' ] = __( 'Nichtwohngebäude' );
						break;
				}
				// Austauschen der Schlüssel für Energieausweisdaten
				if ( is_array( @$new_array[ 'energiepass' ] ) ) {
					foreach ( $epart_keys as $epart_key => $epart_value ) {
						if ( array_key_exists( $epart_key, $new_array[ 'energiepass' ] ) ) {
							$new_array[ 'energiepass' ][ $epart_value ] = $new_array[ 'energiepass' ][ $epart_key ];
							unset( $new_array[ 'energiepass' ][ $epart_key ] );
						}
					}
				}
			}

			return $new_array;
		} // Vorbereiten des Anhaenge Arrays
		elseif ( $arg2 === 'anhang' ) {
			// erlaubte Bildformate
			$bildformate = array(
				'jpg',
				'jpeg',
				'png',
				'JPG',
				'JPEG',
				'PNG',
				'image/jpeg',
				'image/jpg',
				'image/png',
				'IMAGE/JPEG',
				'IMAGE/JPG',
				'IMAGE/PNG'
			);
			// Erlaubte Dokumentenvormate
			$dokumentformate = 'pdf';
			// Vorauswahl Anhangtitel, ist einer vorhanden anwenden sonst String 'bild' setzen;
			empty( $array[ 'anhang' ][ $key ][ 'anhangtitel' ] ) ? $anhangtitel = 'bild' : $anhangtitel = $array[ 'anhang' ][ $key ][ 'anhangtitel' ];

			if ( ! is_array_assoc( $array[ 'anhang' ] ) ):
				// Wenn mehrere Anhänge als Array verfügbar
				foreach ( $array[ 'anhang' ] as $key => $item2 ) {

					foreach ( $bildformate as $formvalue ) {
						// Bilder
						if ( ( @$array[ 'anhang' ][ $key ][ 'format' ] ) && strtolower( @$array[ 'anhang' ][ $key ][ 'format' ] ) == $formvalue ) {
							@$new_array[ 'bilder' ][] = array(
								$anhangtitel =>
									strtolower( $array[ 'anhang' ][ $key ][ 'daten' ][ 'pfad' ] )
							);
						}
					}

					// Dokumente
					if ( ( @$array[ 'anhang' ][ $key ][ 'format' ] ) && strtolower( @$array[ 'anhang' ][ $key ][ 'format' ] ) == $dokumentformate ) {
						@$new_array[ 'dokumente' ][] = array(
							$array[ 'anhang' ][ $key ][ 'anhangtitel' ] =>
								strtolower( $array[ 'anhang' ][ $key ][ 'daten' ][ 'pfad' ] )
						);
					}

				}
			else:
				// Wenn nur ein Anhang
				foreach ( $bildformate as $formvalue ) {
					// Bilder
					if ( ( @$array[ 'anhang' ][ 'format' ] ) && strtolower( @$array[ 'anhang' ][ 'format' ] ) === $formvalue ) {
						@$new_array[ 'bilder' ][] = array(
							$array[ 'OriginalDateiname' ] =>
								strtolower( $array[ 'anhang' ][ 'daten' ][ 'pfad' ] )
						);
					}
					// Dokumente
					if ( ( @$array[ 'anhang' ][ 'format' ] ) && strtolower( @$array[ 'anhang' ][ 'format' ] ) === $dokumentformate ) {
						@$new_array[ 'dokumente' ][] = array(
							$array[ 'anhang' ][ 'anhangtitel' ] =>
								strtolower( $array[ 'anhang' ][ 'daten' ][ 'pfad' ] )
						);
					}
				}

			endif;

			return $new_array;
		} // Vorbereiten des Kontakt-Arrays
		elseif ( $arg2 === 'kontakt' ) {

			// Array zum austausch der Texte
			$textarray = array(
				'email_zentrale' => 'Email Zentrale',
				'email_direkt'   => 'Email Direkt',
				'tel_zentrale'   => 'Telefon Zentrale',
				'tel_durchw'     => 'Telefon Durchwahl',
				'tel_fax'        => 'Fax',
				'tel_handy'      => 'Mobil',
				'postf_plz'      => 'Postfach PLZ',
				'postf_ort'      => 'Postfach Ort',
				'email_privat'   => 'Email Privat',
				'email_sonstige' => 'Weitere Email',
				'email_feedback' => 'Feedback',
				'tel_privat'     => 'Telefon Privat',
				'tel_sonstige'   => 'Weitere Rufnummern',
			);

			foreach ( $array as $key => $item ) {

				if ( $item !== '' && $item !== '-' && ! is_array( $item ) ) {
					//$key = str_replace('_', ' ', $key);
					$kont_array[ $key ] = $item;
					$new_array          = changeKeyNames( $kont_array, $textarray );

				}
			}

			//zeigen($new_array);
			return $new_array;
		}
	}
}

/**
 * @param $array
 *
 * @return bool
 * Funktion prüft ob ein Array assoziativ ist
 */
function is_array_assoc( $array ) {
	if ( ! is_array( $array ) ) {
		return;
	} else {
		foreach ( $array as $key => $value ) {
			if ( is_integer( $key ) ) {
				return false;
			}
		}

		return true;
	}
}

/**
 * @param $arrayToChange
 * @param $arrayTexte
 *
 * @return $new_array
 * Funktion tauscht die Texte der ArrayKeys gegen andere Texte
 */
function changeKeyNames( $arrayToChange, $arrayTexte ) {
	foreach ( $arrayToChange as $textkey => $value ) {
		if ( array_key_exists( $textkey, $arrayTexte ) ) {
			$text               = $arrayTexte[ $textkey ];
			$new_array[ $text ] = $value;
		} else {
			$new_array[ $textkey ] = $value;
		}
	}

	return $new_array;
}