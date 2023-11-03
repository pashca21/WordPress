<?php
$admin_email = get_option( 'wpi_admin' ) != '' ? get_option( 'wpi_admin' ) : get_option( 'admin_email' );

setcookie( 'wpi_licence', get_option( 'wpi_licence' ) );
setcookie( 'wpi_admin', $admin_email );

use wpi\wpi_classes\AdminClass;
use wpi\wpi_classes\WpOptionsClass;

/** Step 3. */
function wpi_plugin_options() {

	$admin        = new AdminClass();
	$optionsClass = new WpOptionsClass();
	$options      = $optionsClass->optionslist;

	global $version;
	global $pro;

	$css = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/css/bootstrap.css';
	$js  = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js';

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'Du hast keine Berechtigung diese Einstellungen zu ändern.', WPI_PLUGIN_NAME ) );
	}
	?>
    <div class="wrap row">
		<?php
		if ( ! isset( $_REQUEST['settings-updated'] ) ) {
			$_REQUEST['settings-updated'] = false;
		}
		?>
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php echo __( 'Einstellungen gespeichert!', WPI_PLUGIN_NAME ); ?></strong></p>
            </div>
		<?php endif; ?>
        <!-- Laden von Bootstrap css -->
        <link rel="stylesheet" href="<?php echo $css; ?>"
              type="text/css" media="all"/>
        <div class="wpi_admin_page content">
            <h1>WP Immo Manager - <?php echo $admin->versionName; ?></h1>

            <h2>Created by <a href="http://media-store.net">Media-Store.net</a></h2>

            <p><?php echo __( 'Dieses Tool integriert XML-Dateien, Basierend auf IS24 oder OpenImmo Standard, in Wordpress in Form eines fertigen Artikels.<br/>
Nach dem auslesen der XML-Dateien stehen anschließend alle Immobilien in einem eigenem Beitragslink "Immobilien".',
					WPI_PLUGIN_NAME ); ?>
            </p>

            <div class="tabpanel col-md-9" role="tabpanel">

                <!-- Tabs-Navs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#synchronisation" role="tab" data-toggle="tab">
							<?php echo __( 'Synchronisation', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#general" role="tab" data-toggle="tab">
							<?php echo __( 'General', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#post-type" role="tab" data-toggle="tab">
							<?php echo __( 'Post-Type', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#single" role="tab" data-toggle="tab">
							<?php echo __( 'Immo-Single', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#liste" role="tab" data-toggle="tab">
							<?php echo __( 'Immo-Liste', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#shortcodes" role="tab" data-toggle="tab">
							<?php echo __( 'Shortcodes', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#features" role="tab" data-toggle="tab">
							<?php echo __( 'Features', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </li>
                </ul>

                <!-- Tab-Inhalte -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="synchronisation">
                        <div id="sync" class="col-sm-8">
                            <h3><?php echo __( 'Manuelle Synchronisation', WPI_PLUGIN_NAME ); ?></h3>

                            <form id="form1" name="form1" method="post" action="">
								<?php submit_button( __( 'Immobilien synchronisieren', WPI_PLUGIN_NAME ), 'primary', 'immo_sync' ); ?>
                            </form>
                            <hr/>
                            <h3><?php echo __( 'Automatische Synchsronisation', WPI_PLUGIN_NAME ); ?> <span
                                        class="badge">WPIM-Pro</span>
                            </h3>
							<?php if ( ! $admin->versionStatus ): ?>
                                <div class="alert alert-danger">
                                    <p class="lead"><span class="glyphicon glyphicon-remove"></span> Diese Funktion ist
                                        nur in der PRO-Version verfügbar.</p>
                                </div>
							<?php endif; ?>
                            <div class="<?php echo ! $admin->versionStatus ? 'hidden' : 'pro_function'; ?>">
                                <div class="alert alert-info" role="alert">
                                    <p><?php echo __( 'Beachte die Performance der Seite!!! Wenn nicht unbedingt notwendig, dann stell die Synchronisation auf "Täglich" oder "Halbtäglich". <br>
In Ausnahmefällen kann die Synchronisation auch Manuell durchgeführt werden.', WPI_PLUGIN_NAME ); ?>
                                    </p>
                                </div>

								<?php echo $admin->auto_sync_form(); ?>
                            </div>
                        </div>


                        <div class="wpi_preview col-sm-4">
                            <h2>InfoBox</h2>

                            <p><?php echo __( 'Meldungen der Synchronisation werden hier ausgegeben', WPI_PLUGIN_NAME ); ?></p>
							<?php
							if ( isset( $_POST['immo_sync'] ) ):
								$xml_file_array       = wpi_xml_auslesen(); //Funktion definiert in wpi_unzip_functions.php
								$GLOBALS['xml_array'] = wpi_xml_array( $xml_file_array ); //Funktion definiert in wpi_create_posts.php

								return wpi_xml_standard();
							endif;
							?>
                        </div>
                        <!--Ende Preview Div-->
                    </div>
                    <!--tab-panel-"synchronisation"-->
                    <div role="tabpanel" class="tab-pane fade" id="general">


                        <div id="proFeatures">
                            <h2 class="text-danger"><?php echo __( 'Pro-Version aktivieren', WPI_PLUGIN_NAME ); ?></h2>
                            <label for="wpi_licence">Lizenzeingabe:</label>
                            <input type="text" name="wpi_licence" value="<?php echo $options['wpi_licence']; ?>">
                            <span
                                    class=""><?php echo $pro === true ? '<i class="glyphicon glyphicon-ok text-success"></i>' : '<i class="glyphicon glyphicon-remove text-danger"></i>' ?></span>
                            <label for=""><?php echo __( 'Admin-Email', WPI_PLUGIN_NAME ) ?></label>
                            <input name="wpi_admin"
                                   value="<?php echo $options['wpi_admin'] != '' ? $options['wpi_admin'] : get_option( 'admin_email' ); ?>">

                        </div>
                        <br>
						<?php if ( ! $pro ):
							echo '<div class="alert alert-danger" role="alert">
<p class="lead">' . __( 'Sie verwenden die Basis-Version, jetzt die <a class="alert-link" href="https://media-store.net/wp-immo-manager-landingpage/" target="_blank">Pro-Version aktivieren</a> um alle Features zu nutzen.', WPI_PLUGIN_NAME ) . '</p>
</div>';
						endif;
						?>
                        <hr>
                        <div id="bootstrap-styles">
                            <h2 class="text-danger">Bootstrap-Styles deaktivieren</h2>
                            <p class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"
                                          style="font-size: 3em; color: orange; float: left; padding-right: 0.5em; padding-bottom: 0.5em;">
                                    </span>
								<?php echo __( '<strong>Dies ist nur sinnvoll, wenn das verwendete Theme bereits Bootstrap verwendet.</strong><br>
                                Da die Immobilien-Templates auf Bootstrap aufbauen, kann es beim Abschalten der Styles zu fehlerhaften Ansichten führen.', WPI_PLUGIN_NAME ); ?>
                            </p>
                            <div class="input_buttons col-xs-12 col-sm-6">
                                <input type="radio" name="wpi_bootstrap_styles"
                                       value="active"<?php echo( get_option( 'wpi_bootstrap_styles' ) == 'active' ? 'checked="checked"' : '' ); ?>/>
                                <label
                                        for=""><?php echo __( 'Styles aktivieren(Standard)', WPI_PLUGIN_NAME ); ?></label>
                            </div>
                            <div class="input_buttons col-xs-12 col-sm-6">
                                <input type="radio" name="wpi_bootstrap_styles"
                                       value="deactivate"<?php echo( get_option( 'wpi_bootstrap_styles' ) == 'deactivate' ? 'checked="checked"' : '' ); ?>/>
                                <label for=""><?php echo __( 'Styles deaktivieren', WPI_PLUGIN_NAME ); ?></label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <div id="standard">
                            <h2 class="text-danger"><?php echo __( 'Auswahl des XML-Standards', WPI_PLUGIN_NAME ); ?></h2>

                            <p><strong>
									<?php echo __( 'Welchen XML-Standard möchtest du verwenden?', WPI_PLUGIN_NAME ); ?>
                                </strong>
                            </p>
							<?php settings_fields( 'wpi_standard_group' ); ?>
							<?php do_settings_sections( 'wpi_standard_group' ); ?>
                            <table class="form-table">
                                <tr valign="top" class="col-sm-6">
                                    <td>
                                        <input type="radio" name="wpi_standard"
                                               value="IS24"<?php echo( get_option( 'wpi_standard' ) == 'IS24' ? 'checked="checked"' : '' ); ?> />
                                    </td>
                                    <th scope="row">
                                        IS24-Standard (noch nicht implementiert)
                                    </th>
                                </tr>

                                <tr valign="top" class="col-sm-6">
                                    <td>
                                        <input type="radio" name="wpi_standard"
                                               value="OpenImmo"<?php echo( get_option( 'wpi_standard' ) == 'OpenImmo' ? 'checked="checked"' : '' ); ?> />
                                    </td>
                                    <th scope="row">
                                        OpenImmo
                                    </th>
                                </tr>
                            </table>
                            <p> <?php echo __( 'Derzeitige Standard ist:', WPI_PLUGIN_NAME ); ?>
                                <strong>
									<?php
									$standard = get_option( 'wpi_standard' );
									echo $standard;
									?>
                                </strong>
                            </p>
							<?php submit_button(); ?>
                        </div>
                        <!--Ende Standard-->
                        </form>
                        <!--Ende XML-Standard-Form-->

                        <hr>

                        <form method="post" action="options.php">
							<?php settings_fields( 'wpi_xmlpath_group' ); ?>
							<?php do_settings_sections( 'wpi_xmlpath_group' ); ?>
                            <div id="path">
                                <h2 class="text-danger"><?php echo __( 'Einstellungen Pfade', WPI_PLUGIN_NAME ); ?></h2>

                                <p>
									<?php
									echo __( 'Hier den Pfad zum Ordner angeben, wohin die Zip-Files durch
<em>Ihre Immobilien-Software</em> kopiert werden.', WPI_PLUGIN_NAME );
									?>
                                </p>

                                <p class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"
                                          style="font-size: 3em; color: orange; float: left; padding-right: 0.5em; padding-bottom: 0.5em;">
                                    </span>
									<?php echo __( 'Beim Einrichten eines neuen Portals in Ihrer Immobilien-Software wie "Makler-Server" oder "Makler-Manager" muss ein FTP-Pfad
festgelegt werden. Dieser Pfad muss hier eingetragen werden, das Plugin WP Immo Manager sucht zukünftig in diesem Ordner
nach neuen Zip_Dateien.', WPI_PLUGIN_NAME ); ?>
                                </p>

                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">ZIP-Files:</th>
                                        <td><input type="text" name="wpi_xml_pfad" size="70"
                                                   value="<?php echo esc_attr( get_option( 'wpi_xml_pfad' ) ); ?>"/>
                                        </td>
                                    </tr>
                                </table>
                                <table class="form-table">
                                    <p class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"
                                          style="font-size: 3em; color: orange; float: left; padding-right: 0.5em; padding-bottom: 0.5em;">
                                    </span>
                                        <strong>
											<?php echo __( 'Wenn ein eigener Ordner für Uploads festgelegt wird.</strong><br>
Sorgen Sie dafür dass dieser auch existiert und über <strong>entsprechende Schreibrechte</strong> verfügt!', WPI_PLUGIN_NAME ); ?>
                                    </p>
                                    <tr valign="top">
                                        <th scope="row"><?php __( 'Uploads:', WPI_PLUGIN_NAME ); ?></th>
                                        <td><input type="text" name="wpi_upload_pfad" size="70"
                                                   value="<?php echo esc_attr( get_option( 'wpi_upload_pfad' ) ); ?>"/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php __( 'Uploads_URL:', WPI_PLUGIN_NAME ); ?></th>
                                        <td><input type="text" name="wpi_upload_url" size="70"
                                                   value="<?php echo esc_attr( get_option( 'wpi_upload_url' ) ); ?>"/>
                                        </td>
                                    </tr>
                                </table>

								<?php submit_button(); ?>
                            </div>
                            <!--Ende Path-->
                        </form>
                        <!--Ende XML-Path-Form-->
                        <hr>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h2><?php echo __( 'Standardeinstellungen laden', WPI_PLUGIN_NAME ); ?></h2>
                                <form method="post">
									<?php submit_button( __( 'Einstellungen zurücksetzen', WPI_PLUGIN_NAME ), 'primary', 'set_defaults' ); ?>
                                </form>
                            </div>
                        </div>
						<?php

						if ( isset( $_POST['set_defaults'] ) ) {
							$admin->set_defaults();
						}

						?>
                    </div>
                    <!--tab-panel-"general"-->
                    <div role="tabpanel" class="tab-pane fade" id="post-type">
                        <form method="post" action="options.php">
							<?php settings_fields( 'wpi_post_type_group' ); ?>
							<?php do_settings_sections( 'wpi_post_type_group' ); ?>
                            <div id="post_type">
                                <h2 class="text-danger"><?php echo __( 'Einstellungen für Post-Type', WPI_PLUGIN_NAME ); ?></h2>

                                <p>&nbsp;</p>

                                <div>
                                    <p class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"
                                          style="font-size: 3em; color: orange; float: left; padding-right: 0.5em; padding-bottom: 0.5em;">
                                    </span>
										<?php echo __( '<strong>" Slug "</strong> ist die Bennenung des Post-Types in der URL-Strucktur.<br>
                                    z.B. <strong>www.ihre-seite.de/<span
                                            class="bg-danger">immobilien</span>/single...</strong>', WPI_PLUGIN_NAME ); ?>
                                    </p>
                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row">Slug:</th>
                                            <td><input type="text" name="wpi_post_type_slug" class="form-control"
                                                       value="<?php echo esc_attr( get_option( 'wpi_post_type_slug' ) ); ?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="text-danger"><?php echo __( 'Titel der Immobilien' ); ?></h4>
                                        <div class="alert alert-info">
											<?php echo __( 'Durch diese Einstellung wird der Ort der Immobilie im Titel vorangestellt. z.B. <strong>Mainz-Ihre Immobilien Überschrift</strong> <br/> Für die SEO empfehlenswert.', WPI_PLUGIN_NAME ); ?>
                                        </div>
                                        <table>
                                            <tr>
                                                <td class="col-xs-6"><strong>Ortsnamen am Anfang des Titels
                                                        voranstellen.</strong></td>
                                                <td class="col-xs-6">
                                                    <label for="wpi_place_to_title">Ja</label>
                                                    <input type="radio" value="true" name="wpi_place_to_title"
														<?php echo( get_option( 'wpi_place_to_title' ) == 'true' ? 'checked="checked"' : '' ); ?>/>
                                                    <label for="wpi_place_to_title">Nein</label>
                                                    <input type="radio" value="false" name="wpi_place_to_title"
														<?php echo( get_option( 'wpi_place_to_title' ) == 'false' ? 'checked="checked"' : '' ); ?>/>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>&nbsp;</p>
                                        <div class="alert alert-danger">
                                            <strong>Hinweis!!!</strong> Diese Einstellung wirkt sich erst bei nächster
                                            Übertragung aus. Bereits bestehende Immobilien, bleiben wie diese angelegt
                                            wurden. <br/>
                                            <strong>Diese Immobilien können nach Änderung der Einstellung nur noch
                                                manuell gelöscht werden.</strong>
                                        </div>
                                    </div>
                                </div>
								<?php submit_button(); ?>
                            </div>
                            <!--Ende #post_type-->
                        </form>

                        <hr>

                    </div>
                    <!--tab-panel-"post-type"-->
                    <div role="tabpanel" class="tab-pane fade" id="single">
                        <div class="">
                            <h2 class="text-danger"><?php echo __( 'Ansicht Single-Seite', WPI_PLUGIN_NAME ); ?></h2>

                            <p>
                            <hr>
                            </p>
                            <form method="post" action="options.php">
								<?php settings_fields( 'wpi_post_single_view' ); ?>
								<?php do_settings_sections( 'wpi_post_single_view' ); ?>
								<?php
								$single_preise   = get_option( 'wpi_single_preise' );
								$single_flaechen = get_option( 'wpi_single_flaechen' );
								?>
								<?php
								/**
								 * Optionen zur Auswahl angezeigter Meta-Angaben
								 */

								// Tabellen-Array für Preise
								@$preise = array
								(
									'kaufpreis'         => __( 'Kaufpreis', WPI_PLUGIN_NAME ),
									'kaltmiete'         => __( 'Kaltmiete', WPI_PLUGIN_NAME ),
									'nettokaltmiete'      => __( 'Nettokaltmiete', WPI_PLUGIN_NAME ),
									'nebenkosten'       => __( 'Nebenkosten', WPI_PLUGIN_NAME ),
									'warmmiete'         => __( 'Warmmiete', WPI_PLUGIN_NAME ),
									'mietpreis_pro_qm'  => __( 'Mietpreis pro m²', WPI_PLUGIN_NAME ),
									'kaufpreis_pro_qm'  => __( 'Kaufpreis pro m²', WPI_PLUGIN_NAME ),
									'mieteinnahmen_ist' => __( 'Jahresnettomiete', WPI_PLUGIN_NAME ),
									'x_fache'           => __( 'Faktor', WPI_PLUGIN_NAME ),
									'kaution'           => __( 'Kaution', WPI_PLUGIN_NAME ),
									'aussen_courtage'   => __( 'Provision', WPI_PLUGIN_NAME )
								);
								// Tabellen-Array für Flächen
								@$flaechen = array
								(
									'wohnflaeche'             => __( 'Wohnfläche in m²', WPI_PLUGIN_NAME ),
									'nutzflaeche'             => __( 'Nutzfläche in m²', WPI_PLUGIN_NAME ),
									'grundstuecksflaeche'     => __( 'Grundstückfläche in m²', WPI_PLUGIN_NAME ),
									'anzahl_zimmer'           => __( 'Anzahl Zimmer', WPI_PLUGIN_NAME ),
									'anzahl_schlafzimmer'     => __( 'Anzahl Schlafzimmer', WPI_PLUGIN_NAME ),
									'anzahl_badezimmer'       => __( 'Anzahl Badezimmer', WPI_PLUGIN_NAME ),
									'anzahl_sep_wc'           => __( 'Gäste-WC', WPI_PLUGIN_NAME ),
									'anzahl_stellplaetze'     => __( 'Anzahl Stellplätze', WPI_PLUGIN_NAME ),
									'anzahl_balkone'          => __( 'Anzahl Balkone', WPI_PLUGIN_NAME ),
									'anzahl_terrassen'        => __( 'Anzahl Terrassen', WPI_PLUGIN_NAME ),
									'anzahl_gewerbeeinheiten' => __( 'Anzahl Gewerbeeinheiten', WPI_PLUGIN_NAME ),
									'lagerflaeche'            => __( 'Lagerfläche in m²', WPI_PLUGIN_NAME ),
									'bueroflaeche'            => __( 'Bürofläche in m²', WPI_PLUGIN_NAME ),
									'kellerflaeche'           => __( 'Kellerfläche in m²', WPI_PLUGIN_NAME )
								);
								?>


								<?php echo $admin->SingleViewSelectForm(); ?>
								<?php submit_button(); ?>

                                <hr>
                                <div id="tabnames" class="">
									<?php echo $admin->SingleTabsNames(); ?>
                                </div>
								<?php if ( $admin->options['wpi_pro'] === 'true' ): ?>
                                    <div id="pagenames" class="">
										<?php echo $admin->SingleOnePagePanels(); ?>
                                    </div>
                                    <div id="avatar">
										<?php echo $admin->SingleAvatarForm(); ?>
                                    </div>
                                    <div id="activateSmartNavi">
										<?php echo $admin->SingleActivateSmartNavigation(); ?>
                                    </div>
								<?php endif; ?>
                                <div id="activateArticleNavigation">
									<?php echo $admin->SingleActivateArticleNavigation(); ?>
                                </div>


								<?php submit_button(); ?>

                                <p>
                                <hr>
                                </p>

                                <h2 class="text-danger">Metadaten</h2>
                                <p class="lead">
									<?php echo __( 'Wähle die Meta-Daten aus, die auf der Single-Seite unter Details angezeigt werden sollen.',
										WPI_PLUGIN_NAME ); ?>
                                </p>

                                <p class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"
                                          style="font-size: 3em; color: orange; float: left; padding-right: 0.5em; padding-bottom: 0.5em;">
                                    </span>

									<?php echo __( '<strong>Hinweis: </strong>Wenn die Meta-Informationen bei einer Immobilie nicht
                                vorhanden sind, werden diese trotz Häckchen nicht angezeigt.
                                <br>
                                Um eine komplette Gruppe von der Anzeige auszuschließen, müssen alle Felder dieser
                                Gruppe deaktiviert sein.', WPI_PLUGIN_NAME ); ?>
                                </p>

                                <div class="clearfix"></div>

                                <div id="preise" class="col-xs-12 list-group preise">
                                    <table class="form-table list-group-item">
                                        <tr valign="top row">
                                            <td scope="row" class="col-xs-12">
                                                <h4 class="text-danger">
													<?php echo __( 'Preise', WPI_PLUGIN_NAME ); ?>
                                                </h4>
                                            </td>
                                        </tr>
                                        <tr valign="top row">
                                            <td>
                                                <fieldset id="preisselect">
													<?php foreach ( $preise as $preiskey => $preistext ) { ?>
                                                        <label class="col-xs-12 col-md-6">
                                                            <input id="wpi_single_preise[<?= $preiskey; ?>]"
                                                                   name="wpi_single_preise[<?= $preiskey; ?>]"
                                                                   type="checkbox"
                                                                   value="<?php esc_attr_e( $preistext ); ?>"
                                                                   class=""
																<?php if ( isset( $single_preise[ $preiskey ] ) ):
																	echo 'checked="checked"';
																else:
																	echo '';
																endif;
																?> />
															<?php echo '&nbsp;' . $preistext ?>
                                                        </label>
													<?php } ?>
                                                </fieldset>
                                            </td>
                                        </tr>

                                    </table>
									<?php submit_button(); ?>
                                </div>

                                <div class="col-xs-12 list-group flaechen">
                                    <table class="form-table list-group-item">
                                        <tr valign="top row">
                                            <td scope="row" class="col-xs-12">
                                                <h4 class="text-danger">
													<?php echo __( 'Flächen', WPI_PLUGIN_NAME ); ?>
                                                </h4>
                                            </td>
                                        </tr>
                                        <tr valign="top row">
                                            <td>
                                                <fieldset>
													<?php foreach ( $flaechen as $fl_key => $fl_text ) { ?>
                                                        <label class="col-xs-12 col-md-6">
                                                            <input id="wpi_single_flaechen[<?= $fl_key; ?>]"
                                                                   name="wpi_single_flaechen[<?= $fl_key; ?>]"
                                                                   type="checkbox"
                                                                   value="<?php esc_attr_e( $fl_text ); ?>"
                                                                   class=""
																<?php if ( isset( $single_flaechen[ $fl_key ] ) ):
																	echo 'checked="checked"';
																else:
																	echo '';
																endif;
																?> />
															<?php echo '&nbsp;' . $fl_text ?>
                                                        </label>
													<?php } ?>
                                                </fieldset>
                                            </td>
                                        </tr>

                                    </table>
									<?php submit_button(); ?>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-xs-12 list-group ausstattung">
                                    <table class="form-table list-group-item">
                                        <tr valign="top row">
                                            <td scope="row" class="col-xs-12">
                                                <h4 class="text-danger">
													<?php echo __( 'Ausstattung / Zustand', WPI_PLUGIN_NAME ); ?>
                                                </h4>
                                                <p class="lead">Diese werden nur angezeigt wenn die Informationen auch
                                                    verfügbar sind.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-xs-12 list-group energiepass">
                                    <div class="form-table list-group-item">
										<?php $epass_texte = get_option( 'wpi_single_epass' ); ?>
                                        <h4 class="text-danger">
											<?php echo __( 'Texte Energiepass', WPI_PLUGIN_NAME ); ?>
                                        </h4>
                                        <table>
                                            <tr>
                                                <td class="col-xs-12 col-sm-6 col-md-4">Text bei nicht vorhandenem
                                                    Energiepass
                                                </td>
                                                <td class="col-xs-12 col-sm-6 col-md-8">
                                                    <textarea rows="6" class="col-xs-12"
                                                              name="wpi_single_epass[nicht_vorhanden]"><?= esc_html( $epass_texte['nicht_vorhanden'] ); ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-xs-12 col-sm-6 col-md-4">Text bei nicht benötigtem
                                                    Energiepass<br>
                                                    (z.B. Denkmalschutz)
                                                </td>
                                                <td class="col-xs-12 col-sm-6 col-md-8">
                                                    <textarea rows="6" class="col-xs-12"
                                                              name="wpi_single_epass[nicht_benoetigt]"><?= esc_html( $epass_texte['nicht_benoetigt'] ); ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <div
                                                class="alert alert-danger">Alle anderen Werte des Energieausweises
                                            werden
                                            aus der XML übernommen.
                                        </div>
                                    </div>
                                </div>
								<?php submit_button(); ?>

                                <div class="clearfix"></div>
                            </form>

                        </div>
                    </div>
                    <!--tab-panel-"single"-->
                    <div role="tabpanel" class="tab-pane fade" id="liste">
                        <div class="">
                            <form method="post" action="options.php">
                                <h2 class="text-danger">
									<?php echo __( 'Ansicht Listen-Seite', WPI_PLUGIN_NAME ); ?>
                                </h2>
								<?php
								settings_fields( 'wpi_post_list_view' );
								do_settings_sections( 'wpi_post_list_view' );
								// Options aus der DB
								$list_options = $options['wpi_list_options'];

								$list_details = $list_options['wpi_list_detail']; ?>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h3 class="text-danger">Suche im Template integrieren <span class="badge">WPIM-Pro</span>
                                        </h3>
                                        <label for="wpi_list_options[wpi_list_search]">Derzeit ausgewählt</label>
                                        <span class="text-success"><?php echo @$list_options['wpi_list_search']; ?></span></p>
                                        <select name="wpi_list_options[wpi_list_search]">
                                            <option value="<?= $list_options['wpi_list_search'] ?>"><?= $list_options['wpi_list_search'] ?></option>
                                            <option value="">Deaktivieren</option>
                                            <option value="searchbar">Suchfeld</option>
                                            <option value="searchfilter">Such-Filter</option>
                                        </select>
                                    </div>
                                </div>
                                <table class="form-table">
                                    <tr valign="top" class="col-sm-6">
                                        <td>
                                            <input type="radio" name="wpi_list_options[wpi_list_excerpt]"
                                                   value="true"<?php echo( $list_options['wpi_list_excerpt'] == 'true' ? 'checked="checked"' : '' ); ?> />
                                        </td>
                                        <th scope="row">
                                            <p class="">Excerpt ( Auszug ) anzeigen<br/>
                                                <small>
                                                    <a title="Beispiel anzeigen" data-toggle="modal"
                                                       data-target="#ModalExcerpt">
														<?php echo __( 'Beispiel ansehen!', WPI_PLUGIN_NAME ); ?>
                                                    </a>
                                                </small>
                                            </p>
                                            <!-- Modal -->
                                            <div class="modal fade" id="ModalExcerpt" tabindex="-1" role="dialog"
                                                 aria-labelledby="ModalExcerptLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Schließen"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="meinModalLabel">
																<?php echo __( 'Excerpt Beispiel', WPI_PLUGIN_NAME ); ?>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img class="img-responsive"
                                                                 src="<?php echo WPI_PLUGIN_URL . 'images/liste-excerpt-info.PNG' ?>"/>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">
                                                                Schließen
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>

                                    <tr valign="top" class="col-sm-6">
                                        <td>
                                            <input type="radio" name="wpi_list_options[wpi_list_excerpt]"
                                                   value="false"<?php echo( $list_options['wpi_list_excerpt'] == 'false' ? 'checked="checked"' : '' ); ?> />
                                        </td>
                                        <th scope="row">
                                            <p class="">
												<?php echo __( 'Detailinformationen anzeigen', WPI_PLUGIN_NAME ); ?>
                                                <br/>
                                                <small>
                                                    <a title="Beispiel anzeigen" data-toggle="modal"
                                                       data-target="#ModalDetails">
														<?php echo __( 'Beispiel ansehen!', WPI_PLUGIN_NAME ); ?>
                                                    </a>
                                                </small>
                                            </p>
                                            <!-- Modal -->
                                            <div class="modal fade" id="ModalDetails" tabindex="-1" role="dialog"
                                                 aria-labelledby="ModalDetailsLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Schließen"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="meinModalLabel">Detail
                                                                Beispiel</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h4>Als Liste</h4>
                                                            <img class="img-responsive"
                                                                 src="<?php echo WPI_PLUGIN_URL . 'images/liste-details-info.PNG' ?>"/>
                                                            <h4>Als Spalten</h4>
                                                            <img class="img-responsive"
                                                                 src="<?php echo WPI_PLUGIN_URL . 'images/snip_columns_view.png' ?>"/>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">
                                                                Schließen
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </table>
								<?php
								//
								// Variablen für Auswahlliste Excerpt zum deaktivieren weiterer Einstellungen
								//
								if ( $list_options['wpi_list_excerpt'] == 'false' ):
									$detail_disable = '';
									$exc_disable    = 'disabled';
                                elseif
								( $list_options['wpi_list_excerpt'] == 'true'
								):
									$detail_disable = 'disabled';
									$exc_disable    = '';
								endif;

								// Array für Auswahlliste Details
								$metakeys = array(
									'kaufpreis'           => __( 'Kaufpreis in (EUR)', WPI_PLUGIN_NAME ),
									'kaltmiete'           => __( 'Kaltmiete in (EUR)', WPI_PLUGIN_NAME ),
									'nettokaltmiete'      => __( 'Nettokaltmiete', WPI_PLUGIN_NAME ),
									'warmmiete'           => __( 'Warmmiete in (EUR)', WPI_PLUGIN_NAME ),
									'nebenkosten'         => __( 'Nebenkosten in (EUR)', WPI_PLUGIN_NAME ),
									'mietpreis_pro_qm'    => __( 'Mietpreis pro m²', WPI_PLUGIN_NAME ),
									'kaufpreis_pro_qm'    => __( 'Kaufpreis pro m²', WPI_PLUGIN_NAME ),
									'mieteinnahmen_ist'   => __( 'Jahresnettomiete in (EUR)', WPI_PLUGIN_NAME ),
									'x_fache'             => __( 'Faktor', WPI_PLUGIN_NAME ),
									'ausen_courtage'      => __( 'Kaution', WPI_PLUGIN_NAME ),
									'anzahl_zimmer'       => __( 'Anzahl Zimmer', WPI_PLUGIN_NAME ),
									'anzahl_badezimmer'   => __( 'Anzahl Badezimmer', WPI_PLUGIN_NAME ),
									'anzahl_schlafzimmer' => __( 'Anzahl Schlafzimmer', WPI_PLUGIN_NAME ),
									'anzahl_sep_wc'       => __( 'Anzahl Gäste WC', WPI_PLUGIN_NAME ),
									'baujahr'             => __( 'Baujahr', WPI_PLUGIN_NAME ),
									'wohnflaeche'         => __( 'Wohnfläche in m²', WPI_PLUGIN_NAME ),
									'nutzflaeche'         => __( 'Nutzfläche in m²', WPI_PLUGIN_NAME )
								);
								?>
                                <div class="col-sm-6 list-group">
                                    <table class="form-table list-group-item <?= $exc_disable; ?>">
                                        <tr valign="top row">
                                            <th scope="row" class="col-sm-6">
												<?php echo __( 'Länge des Auszugs in Wörtern', WPI_PLUGIN_NAME ); ?></th>
                                            <br/>
                                            <td><input type="text" name="wpi_list_options[wpi_list_excerpt_length]"
                                                       size="15"
                                                       value="<?php echo esc_attr( $list_options['wpi_list_excerpt_length'] ); ?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-6 list-group">
									<?php get_option( 'wpi_pro' ) === 'true' ? $list = 'column' : $list = 'list' ?>
                                    <div class="col-sm-12 <?= $detail_disable; ?>">
                                        <p class="col-sm-6 pull-left">
                                            <label for="list-view">Als Liste anzeigen</label>
                                            <input type="radio" name="wpi_list_options[wpi_list_view_column]"
                                                   value="list"
												<?php echo( $list_options['wpi_list_view_column'] == 'list' ? 'checked="checked"' : '' ); ?> />
                                        </p>
                                        <p class="col-sm-6">
                                            <label for="column-view">Als Spalten anzeigen <span
                                                        class="badge">WPIM-Pro</span></label>
                                            <input type="radio" name="wpi_list_options[wpi_list_view_column]"
                                                   value="<?= $list; ?>"
												<?php echo( $list_options['wpi_list_view_column'] == 'column' ? 'checked="checked"' : '' ); ?>/>
                                        </p>
                                    </div>
                                    <table class="form-table list-group-item <?= $detail_disable; ?>">
                                        <tr valign="top row">
                                            <td scope="row" class="col-xs-12"><h4 class="text-danger">
													<?php echo __( 'Auswahl der angezeigten Felder', WPI_PLUGIN_NAME ); ?>
                                                </h4>

                                                <p>
													<?php echo __( '<strong>Hinweis:</strong>Wenn die Meta-Informationen bei einer
Immobilie nicht vorhanden sind werden diese trotz Häckchen nicht angezeigt.', WPI_PLUGIN_NAME ); ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr valign="top row">
                                            <td>
                                                <fieldset>
													<?php foreach ( $metakeys as $metakey => $metatext ) { ?>
                                                        <label class="col-xs-12 col-md-6">
                                                            <input id="wpi_list_options[wpi_list_detail][<?= $metakey; ?>]"
                                                                   name="wpi_list_options[wpi_list_detail][<?= $metakey; ?>]"
                                                                   type="checkbox"
                                                                   value="<?php esc_attr_e( $metatext ); ?>"
                                                                   class="" <?= $detail_disable; ?>
																<?php if ( isset( $list_details[ $metakey ] ) ):
																	echo 'checked="checked"';
																else:
																	echo '';
																endif;
																?> />
															<?php echo '&nbsp;' . $metatext ?>
                                                        </label>
													<?php } ?>
                                                </fieldset>
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="clearfix" style="border-bottom: 1px dotted;"></div>

                                <div class="col-sm-12 list-group">
                                    <div class="list-group-item">
                                        <h2 class="text-danger">
											<?php echo __( 'Sidebar in der Listenansicht anzeigen?', WPI_PLUGIN_NAME ); ?>
                                        </h2>

                                        <p>
                                            <input type="radio" name="wpi_list_options[wpi_list_sidebar]"
                                                   value="true" <?php echo( $list_options['wpi_list_sidebar'] == 'true' ? 'checked="checked"' : '' ); ?>/>
                                            <label>Anzeigen</label><br/>
                                            <input type="radio" name="wpi_list_options[wpi_list_sidebar]"
                                                   value="false" <?php echo( $list_options['wpi_list_sidebar'] == 'false' ? 'checked="checked"' : '' ); ?> />
                                            <label>Verbergen</label>
                                        </p>
                                        <label for="wpi_list_options[wpi_list_sidebar_name]">
											<?php echo __( 'Sidebar-Auswahl', WPI_PLUGIN_NAME ); ?>
                                        </label><br/>
                                        <select name="wpi_list_options[wpi_list_sidebar_name]">
                                            <option placeholder="Ausgewählt"><?= $list_options['wpi_list_sidebar_name']; ?></option>
											<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
                                                <option
                                                        value="<?php echo $sidebar['id']; ?>">
													<?php echo ucwords( $sidebar['name'] ); ?>
                                                </option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>

								<?php submit_button(); ?>
                            </form>
                        </div>
                    </div>
                    <!--tab-panel-"liste"-->

                    <div role="tabpanel" class="tab-pane fade" id="shortcodes">
                        <h2>Shortcodes</h2>
                        <form method="post" action="options.php">
							<?php settings_fields( 'wpi_shortcodes_group' ); ?>
							<?php do_settings_sections( 'wpi_shortcodes_group' ); ?>

                            <div class="panel panel-default">
                                <h3>Seite für Suchergebnisse auswählen</h3>
                                <div class="alert alert-info">
                                    <p>Die Suchergebnisse aus der Umkreissuche bzw. Such-Filter werden hierher
                                        umgeleitet!</p>
                                </div>
                                <div class="panel-body">
									<?php echo $admin->ShortcodesSelectPage(); ?>
                                </div>
								<?php submit_button(); ?>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3>Umkreissuche <span class="badge">WPIM-Pro</span></h3>
                                    <pre><code>[umkreissuche]</code></pre>
                                    <p>
                                        Mit dem Shortcode "Umkreissuche" kann ein Formular für eine Umkreissuche
                                        entweder in
                                        einer Sidebar
                                        oder aber auf einer Page eingebunden werden.
                                    </p>
                                    <p class="lead"><strong>Wichtig! </strong>Legen Sie zuvor eine neue Seite mit dem
                                        namen
                                        "Umkreissuche" an. Diese kann auch leer bleiben. Die Suchergebnisse werden auf
                                        diese
                                        Seite gerootet.
                                    </p>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3>Search-Filter <span class="badge">WPIM-Pro</span></h3>
                                    <pre><code>[search_filter_form]</code></pre>
                                    <p>
                                        Mit dem Shortcode "Search Filter Form" kann ein erweiterter Such-Filter
                                        entweder in einer Sidebar oder aber auf einer Page eingebunden werden.
                                    </p>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3>Immobilien</h3>
                                    <pre><code>[immobilien anzahl=5 order=ASC orderby=id vermarktung=leer objekttyp=leer
                                        relation=OR columns=false]</code></pre>
                                    <p>
                                        Mit dem o.g. Shortcode ist es möglich unterschiedliche Query's zu generieren und
                                        somit diesen Ansprüchen gerecht zu werden.
                                        Im dem Beispiel sind die möglichen Parameter mit Ihren Standard-Werten
                                        abgebildet.
                                        Beim verwenden des Shortcodes ohne jegliche Parameter werden alle Immobilien
                                        aufgelistet, ähnlich einer Archiv-Darstellung.
                                    </p>
                                    <p>
                                        <strong>Beispiele:</strong><br><br>
                                        <span>1. Anzeige nur Immobilien mit <em>Objekttyp Haus</em> und <em>Vermarktungsart
                                            Kauf</em></span>

                                    <pre><code>[immobilien vermarktung=kauf objekttyp=haus relation=and]</code></pre>
                                    <span>Diese Eingabe führt dazu, dass nur die Immobilien mit der Vermarktungsart KAUF und
                                    Objekttyp HAUS angezeigt werden.</span><br><br>

                                    <span>2. Anzeige nur Immobilien mit <em>Objekttyp Wohnung</em> und <em>Vermarktungsart
                                            miete</em> sortiert
                                    nach Überschrift mit 10 Immobilien pro Seite.</span>

                                    <pre><code>[immobilien anzahl=10 orderby=title vermarktung=miete_pacht objekttyp=wohnung
                                        relation=and]</code></pre>
                                    </p>
                                    <p class="lead text-warning">Bei Eingabe "columns=true" kann die Immobilienliste als
                                        Spalten organisiert angezeigt werden.<br>
                                        Als Beispiel dafür, siehe Screenshot unter Menüpunkt "Immo-Liste".</p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--tab-panel-"shortcodes"-->
                    <div role="tabpanel" class="tab-pane fade" id="features">
                        <form method="post" action="options.php">
							<?php settings_fields( 'wpi_features_group' ); ?>
							<?php do_settings_sections( 'wpi_features_group' ); ?>

                            <div id="custom_css" class="panel panel-default">
                                <div class="panel-body">
                                    <h2 class="text-danger col-xs-12 col-md-8"><?php echo __( 'Benutzerdefinierte Styles (CSS)', WPI_PLUGIN_NAME ); ?></h2>
                                    <textarea rows="10" class="col-xs-12 col-md-8" name="wpi_custom_css">
                                        <?php echo trim( esc_html( get_option( 'wpi_custom_css' ) ) ); ?>
                                    </textarea>
                                    <div class="clearfix"></div>
									<?php submit_button(); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
							<?php if ( get_option( 'wpi_pro' ) === 'true' ): ?>
                                <div id="custom-html" class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-xs-12 col-md-8">
                                            <h2 class="text-danger"><?php echo __( 'Benutzerdefiniertes HTML / Shortcodes', WPI_PLUGIN_NAME ); ?></h2>
                                            <textarea rows="5" class="col-xs-12" name="wpi_custom_html">
                                        <?php echo trim( esc_html( get_option( 'wpi_custom_html' ) ) ); ?>
                                        </textarea>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <h2 class="text-danger"><?php echo __( 'Auswahl des Anzeigebereichs', WPI_PLUGIN_NAME ); ?></h2>
                                            <fieldset>
                                                <select name="wpi_html_inject">
                                                    <option><?= get_option( 'wpi_html_inject' ); ?></option>
                                                    <option value="details">Im Tab Details</option>
                                                    <option value="beschreibung">Im Tab Beschreibung</option>
                                                    <option value="dokumente">Im Tab Dokumnete</option>
                                                    <option value="kontaktperson">Im Tab Kontaktperson</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="clearfix"></div>
										<?php submit_button(); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div id="smart-navi" class="panel panel-default">
                                    <div class="panel-body">
                                        <h2 class="text-danger"><?php echo __( 'Smart-Navigation', WPI_PLUGIN_NAME ); ?></h2>
										<?php echo $admin->smart_navi_setup(); ?>
                                    </div>
                                </div>
                                <div id="top-immobilie" class="panel panel-default">
                                    <div class="panel-body">
                                        <h2 class="text-danger">
											<?php echo __( 'Einige Sonder-Features zur Anzeige der Immobilien', WPI_PLUGIN_NAME ); ?>
                                        </h2>
                                        <div id="platzhalter" class="list-group">
                                            <div class="list-group-item col-xs-12 col-md-8">
                                                <table class="">

                                                    <tr>
                                                        <h4 class="text-danger">
															<?php echo __( 'Platzhalter für Immobilien ohne Bilder', WPI_PLUGIN_NAME ); ?>
                                                        </h4>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-xs-1">
                                                        </td>
                                                        <td class="col-xs-9"><input type="text"
                                                                                    name="wpi_img_platzhalter"
                                                                                    id="wpi_img_platzhalter"
                                                                                    placeholder="Bild-Url eingeben"
                                                                                    value="<?php echo get_option( 'wpi_img_platzhalter' ); ?>"
                                                                                    style="width: 100%;"></td>
                                                        <td class="col-xs-2"><input type="button" name="source-button"
                                                                                    id="source-button"
                                                                                    value="<?php echo __( 'Auswahl', WPI_PLUGIN_NAME ); ?>">
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                        <!-- .list-group Platzhalter -->
                                        <div id="top-immo" class="list-group">
                                            <div class="list-group-item col-xs-12 col-md-8">
                                                <table class="">

                                                    <tr>
                                                        <h4 class="text-danger">
															<?php echo __( 'Option für Top-Immobilie anzeigen', WPI_PLUGIN_NAME ); ?>
                                                        </h4>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-xs-1"><input type="checkbox"
                                                                                    name="wpi_show_top_immo"
                                                                                    id="wpi_show_top_immo" value="true"
																<?php echo( get_option( 'wpi_show_top_immo' ) == 'true' ? 'checked="checked"' : '' ); ?>/>
                                                        </td>
                                                        <td class="col-xs-9"><input type="text"
                                                                                    name="wpi_top_immo_source"
                                                                                    id="wpi_top_immo_source"
                                                                                    placeholder="Bild-Url eingeben"
                                                                                    value="<?php echo get_option( 'wpi_top_immo_source' ); ?>"
                                                                                    style="width: 100%;"></td>
                                                        <td class="col-xs-2"><input type="button" name="source-button"
                                                                                    id="source-button"
                                                                                    value="<?php echo __( 'Auswahl', WPI_PLUGIN_NAME ); ?>">
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                        <!-- .list-group Top-Immo -->
                                        <div id="reserv-immo" class="list-group">
                                            <div class="list-group-item col-xs-12 col-md-8">
                                                <table class="">

                                                    <tr>
                                                        <h4 class="text-danger">
															<?php echo __( 'Option für Reserviert anzeigen', WPI_PLUGIN_NAME ); ?>
                                                        </h4>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-xs-1"><input type="checkbox"
                                                                                    name="wpi_show_reserved"
                                                                                    id="wpi_show_reserved" value="true"
																<?php echo( get_option( 'wpi_show_reserved' ) == 'true' ? 'checked="checked"' : '' ); ?>/>
                                                        </td>
                                                        <td class="col-xs-9"><input type="text"
                                                                                    name="wpi_reserved_text"
                                                                                    id="wpi_reserved_text"
                                                                                    placeholder="Gib den Text ein der angezeigt werden soll"
                                                                                    value="<?php echo get_option( 'wpi_reserved_text' ); ?>"
                                                                                    style="width: 100%;">
                                                        </td>

                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                        <!-- .list-group Reserviert -->
                                        <div id="sold-immo" class="list-group">
                                            <div class="list-group-item col-xs-12 col-md-8">
                                                <table class="">

                                                    <tr>
                                                        <h4 class="text-danger">
															<?php echo __( 'Option für Verkauft anzeigen', WPI_PLUGIN_NAME ); ?>
                                                        </h4>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-xs-1"><input type="checkbox"
                                                                                    name="wpi_show_sold"
                                                                                    id="wpi_show_sold" value="true"
																<?php echo( get_option( 'wpi_show_sold' ) == 'true' ? 'checked="checked"' : '' ); ?>/>
                                                        </td>
                                                        <td class="col-xs-9"><input type="text"
                                                                                    name="wpi_sold_text"
                                                                                    id="wpi_sold_text"
                                                                                    placeholder="Gib den Text ein der angezeigt werden soll"
                                                                                    value="<?php echo get_option( 'wpi_sold_text' ); ?>"
                                                                                    style="width: 100%;">
                                                        </td>

                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                        <!-- .list-group Verkauft -->
                                        <!-- <script type="text/javascript">
											/*Javascript zum Laden des Bildes aus der Media-Library*/
											jQuery(document).ready(function () {
												jQuery('#source-button').click(function () {
													formfield = jQuery('#wpi_top_immo_source').attr('name');
													tb_show('', 'media-upload.php?type=image&tab=library&TB_iframe=true');
													return false;
												});

												window.send_to_editor = function (html) {
													imgurl = jQuery('img', html).attr('src');
													jQuery('#wpi_top_immo_source').val(imgurl);
													tb_remove();
												}

											});
										</script> -->
                                        <div class="clearfix"></div>

										<?php submit_button(); ?>
                                    </div>
                                </div>
							<?php endif; //Wenn PRO
							?>

                        </form>

                    </div><!-- tab-panel features -->
                </div>
                <!-- Ende tab-content-->
            </div>
            <!--Ende tabpanel-->
            <div id="sidebar" class="col-md-3">
                <div id="version-details" class="panel panel-primary">
                    <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-info-sign"></span>
                        Plugin-Details
                    </div>
                    <div class="panel-body">
                        <strong>Version: </strong><?= $version; ?>
                        <hr>
                        <p>Du verwendest die <strong><?php echo $admin->versionName; ?> </strong> Version.</p>
                    </div>
                </div>

                <div id="bewertungen" class="panel panel-primary">
                    <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-star"></span>
                        Bewertungen
                        <span class="glyphicon glyphicon-star"></span></div>
                    <div class="panel-body">
                        <p>
                            Bewerten Sie <strong>WP-Immo-Manager</strong><br>
                            und erleichtern Sie die Entscheidung den anderen Usern.
                        </p>
                        <div class="rate">
                            <div class="wporg-ratings rating-stars"><a target="_blank"
                                                                       href="//wordpress.org/support/view/plugin-reviews/wp-immo-manager?rate=1#postform"
                                                                       data-rating="1" title=""><span
                                            class="dashicons dashicons-star-filled"
                                            style="color:#ffb900 !important;"></span></a><a
                                        href="//wordpress.org/support/view/plugin-reviews/wp-immo-manager?rate=2#postform"
                                        data-rating="2" title=""><span class="dashicons dashicons-star-filled"
                                                                       style="color:#ffb900 !important;"></span></a><a
                                        href="//wordpress.org/support/view/plugin-reviews/wp-immo-manager?rate=3#postform"
                                        data-rating="3" title=""><span class="dashicons dashicons-star-filled"
                                                                       style="color:#ffb900 !important;"></span></a><a
                                        href="//wordpress.org/support/view/plugin-reviews/wp-immo-manager?rate=4#postform"
                                        data-rating="4" title=""><span class="dashicons dashicons-star-filled"
                                                                       style="color:#ffb900 !important;"></span></a><a
                                        href="//wordpress.org/support/view/plugin-reviews/wp-immo-manager?rate=5#postform"
                                        data-rating="5" title=""><span class="dashicons dashicons-star-empty"
                                                                       style="color:#ffb900 !important;"></span></a>
                            </div>
                            <script>
															jQuery(document).ready(function ($) {
																$('.rating-stars').find('a').hover(
																	function () {
																		$(this).nextAll('a').children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
																		$(this).prevAll('a').children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
																		$(this).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
																	}, function () {
																		var rating = $('input#rating').val();
																		if (rating) {
																			var list = $('.rating-stars a');
																			list.children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
																			list.slice(0, rating).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
																		}
																	}
																);
															});
                            </script>
                        </div>
                        <br>
                        <a class="btn btn-primary col-xs-12"
                           href="https://wordpress.org/support/view/plugin-reviews/wp-immo-manager" target="_blank">
                            Jetzt Bewerten
                        </a>
                    </div>
                </div>

				<?php if ( ! $admin->versionStatus ): ?>
                    <div id="spende" class="panel panel-primary">
                        <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-heart"></span> Hilf
                            uns
                        </div>
                        <div class="panel-body">
                            <p>Die Entwicklung kostet viel Zeit. <br>
                                Jeder Euro hilft uns den Service und die Funktionen des Plugins zu verbessern und
                                weiterzuentwickeln.</p>
                            <p>Die Höhe der Spende bleibt dir überlassen.</p>
                            <a target="_blank" href="https://media-store.net" class="btn btn-primary col-xs-12">Jetzt
                                Spenden</a>
                        </div>
                    </div>
				<?php endif; ?>

                <div id="partner" class="panel panel-primary">
                    <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-gift"></span> Partner
                    </div>
                    <div class="panel-body">
                        <p>Sie sind Designer oder Agentur? <br>
                            Erfahren Sie mehr über unser Partner-Programm.</p>
                        <p class="lead"><strong>Es lohnt sich.</strong></p>
                        <a target="_blank" href="https://media-store.net" class="btn btn-primary col-xs-12">Partner
                            werden</a>
                    </div>
                </div>

            </div>
            <!-- Ende Sidebar -->
        </div>
        <!--Ende Content-->
        <script type="application/javascript" src="<?= WPI_PLUGIN_URL; ?>js/adminpage/js-cookie.js"></script>
        <script type="application/javascript" src="<?= WPI_PLUGIN_URL; ?>js/adminpage/main.js"></script>
    </div><!-- Ende Wrap -->

	<?php
}

function wpi_dashboard_page() {
	$content = \wpi\wpi_classes\AdminClass::wpi_dashboard_page();

	return AdminClass::fullwidth_container( $content );
}

function wpi_general_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_general_page();

	return AdminClass::sidebar_container( $content );
}

function wpi_posttype_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_posttype_page();

	return AdminClass::sidebar_container( $content );
}

function wpi_single_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_single_page();

	return AdminClass::fullwidth_container( $content );
}

function wpi_list_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_list_page();

	return AdminClass::fullwidth_container( $content );
}

function wpi_shortcodes_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_shortcodes_page();

	return AdminClass::sidebar_container( $content );
}

function wpi_features_page() {

	$content = \wpi\wpi_classes\AdminClass::wpi_features_page();

	return AdminClass::fullwidth_container( $content );
}