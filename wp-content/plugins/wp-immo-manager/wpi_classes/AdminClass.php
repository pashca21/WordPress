<?php
/**
 * Created by
 * User: Media-Store.net
 * Date: 21.08.2016
 * Time: 22:14
 */

namespace wpi\wpi_classes;

class AdminClass {

	public function __construct() {
		// Laden der Optinen aus DB Admin-Options
		$this->optionsClass    = new WpOptionsClass;
		$this->options         = $this->optionsClass->optionslist;
		$this->default_options = $this->optionsClass->wpi_options;

		$this->single_preise      = get_option( 'wpi_single_preise' );
		$this->single_flaechen    = get_option( 'wpi_single_flaechen' );
		$this->single_ausstattung = get_option( 'wpi_single_ausstattung' );
		$this->html_inject        = get_option( 'wpi_html_inject' );
		$this->html               = get_option( 'wpi_custom_html' );
		$this->upload_url         = get_option( 'wpi_upload_url' );
		$this->html_inject        = get_option( 'wpi_html_inject' );
		$this->html               = get_option( 'wpi_custom_html' );
		$this->tabs               = get_option( 'wpi_single_view_tabs' );
		$this->smart_nav          = get_option( 'wpi_smartnav' );

	}

	public static function versionStatus() {

		$admin = new \wpi\wpi_classes\AdminClass();

		if ( $admin->options['wpi_pro'] === 'true' ) {
			return true;
		} else {
			return false;
		}
	}

	public static function versionName() {
		$admin = new \wpi\wpi_classes\AdminClass();

		if ( $admin::versionStatus() ) {
			return 'PRO';
		} else {
			return 'FREE';
		}
	}

	/**
	 *  Sets all Options to Defaults
	 */

	public function set_defaults() {
		foreach ( $this->default_options as $opt_name => $opt_value ) {

			update_option( $opt_name, $opt_value );
			//wp_redirect();
		}
	}

	public function get_pro_badge() {
		return '<a href="https://media-store.net/wp-immo-manager-landingpage/?link=plugin" target="_blank"><span class="badge">WPIM-Pro</span></a>';
	}

	public function get_immgroup_terms() {
		$terms = get_terms( array(
			'taxonomy'   => 'immobiliengruppe',
			'hide_empty' => false,
		) );

		return $terms;
	}

	// Views & Functions Forms
	public function auto_sync_form() {
		ob_start();

		?>

        <form method="post" action="options.php">
			<?php settings_fields( 'wpi_shedule_group' ); ?>
			<?php do_settings_sections( 'wpi_shedule_group' ); ?>
            <fieldset>
                <label for="wpi_shedule_time">
					<?php echo __( 'Auswahl für die Automatische Steuerung der Synchronisation', WPI_PLUGIN_NAME ); ?>
                </label>
                <table class="form-table">
                    <tr valign="top" class="col-sm-12 col-md-4">
                        <td>
                            <input type="radio" name="wpi_shedule_time"
                                   value="hourly"<?php echo( get_option( 'wpi_shedule_time' ) == 'hourly' ? 'checked="checked"' : '' ); ?> />
                        </td>
                        <td>
							<?php echo __( 'Stündliche Synchronisation', WPI_PLUGIN_NAME ); ?>
                        </td>
                    </tr>

                    <tr valign="top" class="col-sm-12 col-md-4">
                        <td>
                            <input type="radio" name="wpi_shedule_time"
                                   value="twicedaily"<?php echo( get_option( 'wpi_shedule_time' ) == 'twicedaily' ? 'checked="checked"' : '' ); ?> />
                        </td>
                        <td>
							<?php echo __( 'Halbtägliche Synchronisation', WPI_PLUGIN_NAME ); ?>
                        </td>
                    </tr>

                    <tr valign="top" class="col-sm-12 col-md-4">
                        <td>
                            <input type="radio" name="wpi_shedule_time"
                                   value="daily"<?php echo( get_option( 'wpi_shedule_time' ) == 'daily' ? 'checked="checked"' : '' ); ?> />
                        </td>
                        <td>
							<?php echo __( 'Tägliche Synchronisation', WPI_PLUGIN_NAME ); ?>
                        </td>
                    </tr>
                </table>

            </fieldset>
			<?php submit_button(); ?>
        </form>

		<?php
		return ob_get_clean();
	}

	/**
	 * List Options Form Immogroup
	 * @return string
	 */
	public function List_immogroup_form() {
		ob_start();
		$immogroups   = $this->get_immgroup_terms();
		$list_options = get_option( 'wpi_list_options' ); ?>

        <div class="list-group col-xs-12 col-md-6">
            <div class="list-group-item">
                <div class="col-sm-6">
                    <h5>
                        <a href="admin.php?page=wpi_features_page#top-immobilie">
							<?php echo __( 'Immobiliengruppe für Referenzen / Verkaufte Immobilien', WPI_PLUGIN_NAME ); ?>
                        </a>
                    </h5>
                    <select name="wpi_list_options[wpi_immogroup_sold]">
                        <option value="<?php echo $list_options['wpi_immogroup_sold']; ?>">
							<?php echo $list_options['wpi_immogroup_sold']; ?>
                        </option>
                        <option value="">Keine Auswahl</option>
						<?php
						foreach ( $immogroups as $immogroup ):
							echo '<option value="' . trim( $immogroup->name ) . '">' . $immogroup->name . '</option>';
						endforeach;
						?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <h5 class="text-primary"><?php echo __( 'Preise bei Referenzobjketen ausblenden', WPI_PLUGIN_NAME ); ?></h5>
					<?php echo self::ToggleSwitch( 'wpi_list_options[wpi_immogroup_sold_hide_price]', @$list_options['wpi_immogroup_sold_hide_price'] ); ?>
                </div>
                <p>&nbsp;</p>
                <div class="alert alert-info">
                    Diese Einstellung bewirkt, dass alle Immobilien die dieser Immobiliengruppe zugeordnet sind,
                    automatisch mit einem "Verkauft" Label versehen werden.
                </div>
            </div>
        </div>

        <div class="list-group col-xs-12 col-md-6">
            <div class="list-group-item">
                <h5>
                    <a href="admin.php?page=wpi_features_page#top-immobilie">
						<?php echo __( 'Immobiliengruppe für Reservierte Immobilien', WPI_PLUGIN_NAME ); ?>
                    </a>
                </h5>
                <select name="wpi_list_options[wpi_immogroup_reserved]">
                    <option value="<?php echo $list_options['wpi_immogroup_reserved']; ?>">
						<?php echo $list_options['wpi_immogroup_reserved'] ?>
                    </option>
                    <option value="">Keine Auswahl</option>
					<?php
					foreach ( $immogroups as $immogroup ):
						echo '<option value="' . trim( $immogroup->name ) . '">' . $immogroup->name . '</option>';
					endforeach;
					?>
                </select>
                <br><br>
                <div class="alert alert-info">
                    Diese Einstellung bewrkt, dass alle Immobilien die dieser Immobiliengruppe zugeordnet sind,
                    automatisch mit einem "Reserviert" Label versehen werden.
                </div>
            </div>
        </div>

        <div class="list-group col-xs-12">
            <div class="list-group-item">
                <h5>
                    <a href="admin.php?page=wpi_features_page#top-immobilie">
						<?php echo __( 'Immobiliengruppe für Top Immobilie', WPI_PLUGIN_NAME ); ?>
                    </a>
                </h5>
                <select name="wpi_list_options[wpi_immogroup_top]">
                    <option value="<?php echo $list_options['wpi_immogroup_top']; ?>">
						<?php echo $list_options['wpi_immogroup_top']; ?>
                    </option>
                    <option value="">Keine Auswahl</option>
					<?php
					foreach ( $immogroups as $immogroup ):
						echo '<option value="' . trim( $immogroup->name ) . '">' . $immogroup->name . '</option>';
					endforeach;
					?>
                </select>
                <br><br>
                <div class="alert alert-info">
                    Diese Einstellung bewrkt, dass alle Immobilien die dieser Immobiliengruppe zugeordnet sind,
                    automatisch mit einem "Top Immobilie" Label versehen werden.
                </div>
            </div>
        </div>

		<?php
		//zeigen( $immgroups );

		return ob_get_clean(); ?>
        }

        // Single Seite Forms
        public function SingleViewSelectForm() {
        ob_start();

        $this -> versionStatus === true ? $disable_radio_view = '' : $disable_radio_view = 'disabled';
        $this -> versionStatus === true ? $accordion = 'accordion' : $accordion = 'tabs';
        $this -> versionStatus === true ? $onepage = 'onepage' : $onepage = 'tabs';
        $this -> versionStatus === true ? $sidebarpage = 'sidebarpage' : $sidebarpage = 'tabs';
        ?>
        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Auswahl Single-Template', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <div id="radio-tabs" class="radio single-radio col-xs-12 col-md-3">
                    <label>
                        <input type="radio" name="wpi_single_view" id="wpi_single_view1"
                               value="tabs" <?php echo $this->options['wpi_single_view'] === 'tabs' ? 'checked="checked"' : ''; ?>>
                        Tabs (<a data-toggle="modal" data-target="#tabsModal">Beispiel</a>)
                    </label>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="tabsModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="tabsModalLabel">Tabs</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_tabs.png' ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="radio-tabs" class="radio single-radio col-xs-12 col-md-3  <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view2"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $accordion; ?>" <?php echo $this->options['wpi_single_view'] === 'accordion' ? 'checked="checked"' : ''; ?>>
                        Accordion (<a data-toggle="modal"
                                      data-target="#accordionModal">Beispiel</a>)
                    </label>
					<?php echo $this->get_pro_badge(); ?>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="accordionModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="accordionModalLabel">
                                    Accordion</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_accordion.png' ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="radio-onePage"
                     class="radio single-radio col-xs-12 col-md-3 <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view3"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $onepage; ?>" <?php echo $this->options['wpi_single_view'] === 'onepage' ? 'checked="checked"' : ''; ?>>
                        OnePage (<a data-toggle="modal" data-target="#onePageModal">Beispiel</a>)
                    </label>
					<?php echo $this->get_pro_badge(); ?>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="onePageModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="onePageModalLabel">OnePage</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_onePage.png'; ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="radio-SidebarPage"
                     class="radio single-radio col-xs-12 col-md-3 <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view4"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $sidebarpage; ?>" <?php echo $this->options['wpi_single_view'] === 'sidebarpage' ? 'checked="checked"' : ''; ?>>
                        Sidebar Page (<a data-toggle="modal" data-target="#SidebarPageModal">Beispiel</a>)
                    </label>
					<?php echo $this->get_pro_badge(); ?>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="SidebarPageModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="onePageModalLabel">SidebarPage Template</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_sidebarPage.png'; ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	// Single Seite Forms
	public function SingleViewSelectForm() {
		ob_start();

		$this::versionStatus() === true ? $disable_radio_view = '' : $disable_radio_view = 'disabled';
		$this::versionStatus() === true ? $accordion = 'accordion' : $accordion = 'tabs';
		$this::versionStatus() === true ? $onepage = 'onepage' : $onepage = 'tabs';
		$this::versionStatus() === true ? $sidebarpage = 'sidebarpage' : $sidebarpage = 'tabs';
		?>
        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Auswahl Single-Template', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <div id="radio-tabs" class="radio single-radio col-xs-12 col-md-3">
                    <label>
                        <input type="radio" name="wpi_single_view" id="wpi_single_view1"
                               value="tabs" <?php echo $this->options['wpi_single_view'] === 'tabs' ? 'checked="checked"' : ''; ?>>
                        Tabs (<a data-toggle="modal" data-target="#tabsModal">Beispiel</a>)
                    </label>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="tabsModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="tabsModalLabel">Tabs</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_tabs.png' ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="radio-tabs" class="radio single-radio col-xs-12 col-md-3  <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view2"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $accordion; ?>" <?php echo $this->options['wpi_single_view'] === 'accordion' ? 'checked="checked"' : ''; ?>>
                        Accordion (<a data-toggle="modal"
                                      data-target="#accordionModal">Beispiel</a>) <span
                                class="badge">WPIM-Pro</span>
                    </label>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="accordionModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="accordionModalLabel">
                                    Accordion</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_accordion.png' ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="radio-onePage"
                     class="radio single-radio col-xs-12 col-md-3 <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view3"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $onepage; ?>" <?php echo $this->options['wpi_single_view'] === 'onepage' ? 'checked="checked"' : ''; ?>>
                        OnePage (<a data-toggle="modal" data-target="#onePageModal">Beispiel</a>)
                        <span class="badge">WPIM-Pro</span>
                    </label>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="onePageModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="onePageModalLabel">OnePage</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_onePage.png'; ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="radio-SidebarPage"
                     class="radio single-radio col-xs-12 col-md-3 <?php echo $disable_radio_view; ?>">
                    <label class="<?php echo $disable_radio_view; ?>">
                        <input type="radio" name="wpi_single_view" id="wpi_single_view4"
                               class="<?php echo $disable_radio_view; ?>"
                               value="<?php echo $sidebarpage; ?>" <?php echo $this->options['wpi_single_view'] === 'sidebarpage' ? 'checked="checked"' : ''; ?>>
                        Sidebar Page (<a data-toggle="modal" data-target="#SidebarPageModal">Beispiel</a>)
                        <span class="badge">WPIM-Pro</span>
                    </label>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="SidebarPageModal" tabindex="-1" role="dialog"
                     aria-labelledby="meinModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Schließen"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="onePageModalLabel">SidebarPage Template</h4>
                            </div>
                            <div class="modal-body">
                                <img class="img-responsive"
                                     src="<?php echo WPI_PLUGIN_URI . 'images/snap_sidebarPage.png'; ?>"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	public function SingleTabsNames() {
		ob_start();
		$single_tabs = $this->options['wpi_single_view_tabs'];
		?>
        <div class="panel panel-primary radio-div radio-tabs hidden">
            <div class="panel-heading">
                <h4><?php echo __( 'Beschriftung der Tabs', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group col-xs-6">
                    <label for="details"><?php echo __( 'Details', WPI_PLUGIN_NAME ); ?></label>
                    <input type="text" class="form-control" name="wpi_single_view_tabs[details]"
                           id="wpi_single_view_tabs[details]"
                           value="<?php echo esc_html( $single_tabs['details'] ); ?>">
                </div>
                <div class="form-group col-xs-6">
                    <label
                            for="beschreibung"><?php echo __( 'Beschreibung', WPI_PLUGIN_NAME ); ?></label>
                    <input type="text" class="form-control"
                           name="wpi_single_view_tabs[beschreibung]"
                           id="wpi_single_view_tabs[beschreibung]"
                           value="<?php echo esc_html( $single_tabs['beschreibung'] ); ?>">
                </div>
                <div class="form-group col-xs-6">
                    <label for="bilder"><?php echo __( 'Bilder', WPI_PLUGIN_NAME ); ?></label>
                    <input type="text" class="form-control" name="wpi_single_view_tabs[bilder]"
                           id="wpi_single_view_tabs[bilder]"
                           value="<?php echo esc_html( $single_tabs['bilder'] ); ?>">
                </div>
                <div class="form-group col-xs-6">
                    <label
                            for="kontakt"><?php echo __( 'Kontaktperson', WPI_PLUGIN_NAME ); ?></label>
                    <input type="text" class="form-control" name="wpi_single_view_tabs[kontakt]"
                           id="wpi_single_view_tabs[kontakt]"
                           value="<?php echo esc_html( $single_tabs['kontakt'] ); ?>">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	public function SingleOnePagePanels() {
		ob_start();
		?>
        <div class="panel panel-primary radio-div radio-onePage hidden">
            <div class="panel-heading">
                <h4><?php echo __( 'Beschriftung OnePage Panels', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
				<?php
				$panel_headers = array(
					'beschreibung' => 'Objektbeschreibung',
					'details'      => 'Immobiliendetails',
					'kontakt'      => 'Kontaktperson',
					'preise'       => 'Kosten',
					'flaechen'     => 'Flächen',
					'zustand'      => 'Zustand',
					'ausstattung'  => 'Ausstattung',
					'energiepass'  => 'Energiepass',
					'dokumente'    => 'Dokumente',
					'map'          => 'Karte',
				);
				foreach ( $panel_headers as $key => $value ):
					?>
                    <div class="form-group col-xs-6">
                        <label for="<?= $key; ?>"><?php echo __( $value, WPI_PLUGIN_NAME ); ?></label>
                        <input type="text" class="form-control" name="wpi_single_onePage[<?= $key; ?>]"
                               id="wpi_single_onePage[<?= $key; ?>]"
                               value="<?php echo esc_html( $this->options['wpi_single_onePage'][ $key ] ); ?>">
                    </div>
				<?php
				endforeach;

				?>

            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function SingleSidebarPagePanels() {
		$titles = array(
			'details'      => 'Objektdetails',
			'beschreibung' => 'Objektbeschreibung',
			'lage'         => 'Lagedetails',
			'ausstattung'  => 'Ausstattung',
			'dokumente'    => 'Grundrisse',
			'meta'         => 'Erstellt und Aktualisiert',
		);
		ob_start();
		?>

        <div class="panel panel-primary radio-div radio-SidebarPage">
            <div class="panel-heading">
                <h4><?php echo __( 'Einstellungen für Sidebar-Template', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p>Dieses Template erfordert eine Auswahl der aktiven Sidebar. Die Sidebar wird rechts im Template
                        angezeigt.</p>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="wpi_single_sidebar_name"><?php echo __( 'Sidebarauswahl', WPI_PLUGIN_NAME ); ?></label>
                        <select name="wpi_single_sidebar_name" id="wpi_single_sidebar_name" class="form-control">
                            <option placeholder="Ausgewählt"><?= $this->options['wpi_single_sidebar_name']; ?></option>
							<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
                                <option
                                        value="<?php echo $sidebar['id']; ?>">
									<?php echo ucwords( $sidebar['name'] ); ?>
                                </option>
							<?php } ?>
                        </select>
                    </div>
					<?php
					//TODO Erstellen und Registrieren der Sidebars
					?>
                    <!-- <div class="col-sm-4 col-sm-offset-1">
						<label><?php echo __( 'Neue Sidebar anlegen', WPI_PLUGIN_NAME ); ?></label>
						<input class="form-control" type="text"/>
					</div>
					<div class="col-sm-2  col-sm-offset-1">
						<?php submit_button(); ?>
					</div> -->
                </div>
                <div class="row">
                    <h4 class="col-xs-12" for="wpi_single_sidebarPage_titles">
						<?php echo __( 'Beschriftung der Überschriften:', WPI_PLUGIN_NAME ) ?>
                    </h4>
					<?php
					foreach ( $titles as $title => $value ):
						echo '<div class="col-xs-12 col-sm-6">';
						echo '<label for="wpi_single_sidebarPage_titles[' . $title . ']">' . $title . '</label>';
						echo '<input type="text" class="form-control" 
						name="wpi_single_sidebarPage_titles[' . $title . ']" value="' . $value . '" 
						id="wpi_single_sidebarPage_titles[' . $title . ']" value="' . $value . '" />';
						echo '</div>';
					endforeach;
					?>
                </div>
            </div>

        </div>
		<?php
		return ob_get_clean();
	}

	public function SingleSliderForm() {
		ob_start();
		?>
        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Auswahl des Image-Sliders', WPI_PLUGIN_NAME ); ?>
                    <span class="pull-right"><?php echo $this->get_pro_badge(); ?></span></h4>
            </div>
            <div class="panel-body">
				<?php
				$available_sliders = array(
					__( 'Bootstrap Slider (Standard)', WPI_PLUGIN_NAME )     => 'bootstrap',
					__( 'Flexslider Basic', WPI_PLUGIN_NAME )                => 'basic',
					__( 'Flexslider Basic + Navigation', WPI_PLUGIN_NAME )   => 'basic_custom_nav',
					__( 'Flexslider Basic mit Caption', WPI_PLUGIN_NAME )    => 'basic_caption',
					__( 'Flexslider mit Thumbnails', WPI_PLUGIN_NAME )       => 'thumbnails_control_nav',
					__( 'Flexslider mit Thumbnails slide', WPI_PLUGIN_NAME ) => 'thumbnails_slider'
				);

				?>
                <label>
					<?php echo __( 'Wähle einen Slider aus', WPI_PLUGIN_NAME ); ?>
                    <select name="wpi_slider[active_slider]">
                        <option value="<?php echo $this->options['wpi_slider']['active_slider']; ?>"><?php echo array_search( $this->options['wpi_slider']['active_slider'], $available_sliders ); ?></option>
						<?php foreach ( $available_sliders as $name => $value ): ?>
                            <option value="<?= $value; ?>"><?= $name; ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
                <br>&nbsp;
                <!--				<label>-->
                <!--					--><?php //echo __( 'Überschrift im Slider anzeigen', WPI_PLUGIN_NAME ); ?>
                <!--					--><?php //echo self ::ToggleSwitch( 'wpi_slider[title_in]', @$this -> options[ 'wpi_slider' ][ 'title_in' ] ); ?>
                <!--					<small>Derzeit nicht aktiv!</small>-->
                <!--				</label>-->
                <br><label>
					<?php echo __( 'Anzeige in Lightbox zulassen', WPI_PLUGIN_NAME ); ?>
					<?php echo self::ToggleSwitch( 'wpi_slider[lightbox_in]', @$this->options['wpi_slider']['lightbox_in'] ); ?>
                </label>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	public function SingleAvatarForm() {
		ob_start();
		?>
        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Avatar bei Kontaktdaten anzeigen', WPI_PLUGIN_NAME ); ?>
                    <span class="pull-right"><?php echo $this->get_pro_badge(); ?></span></h4>
            </div>
            <div class="panel-body">
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_avatar[active]" id="wpi_avatar[active]1" value="true"
							<?php echo $this->options['wpi_avatar']['active'] == 'true' ? 'checked' : '' ?>>
                        Aktivieren
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_avatar[active]" id="wpi_avatar[active]2" value="false"
							<?php echo $this->options['wpi_avatar']['active'] == 'false' ? 'checked' : '' ?>>
                        Nicht aktivieren
                    </label>
                </div>

                <div class="form-group">
                    <label for="wpi_avatar[avatar_url]" class="col-sm-2 control-label">Avatar URL</label>
                    <div class="col-sm-10">
                        <input type="url" class="form-control" name="wpi_avatar[avatar_url]"
                               id="wpi_avatar[avatar_url]"
                               value="<?php echo $this->options['wpi_avatar']['avatar_url'] ?>">
                    </div>
                </div>
                <div class="clearfix"></div>
                <br/>
                <div class="alert alert-info">
                    Wird die Avatar URL leer gelassen, der Avatar jedoch aktiviert ist, wird versucht ein
                    <a href="https://de.gravatar.com/" target="_blank" class="alert-link">Gravatar</a> aus der Email in
                    der XML darzustellen.
                </div>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Meta-Hardfacts Auswahl Box
	 * @return string
	 */
	public function SingleMetaHardfacts() {
		// Tabellen-Array für Hardfacts
		@$hardfacts = array(
			'kaufpreis'               => __( 'Kaufpreis', WPI_PLUGIN_NAME ),
			'kaltmiete'               => __( 'Kaltmiete', WPI_PLUGIN_NAME ),
			'nebenkosten'             => __( 'Nebenkosten', WPI_PLUGIN_NAME ),
			'warmmiete'               => __( 'Warmmiete', WPI_PLUGIN_NAME ),
			'wohnflaeche'             => __( 'Wohnfläche', WPI_PLUGIN_NAME ),
			'nutzflaeche'             => __( 'Nutzfläche', WPI_PLUGIN_NAME ),
			'grundstuecksflaeche'     => __( 'Grundstücksfläche', WPI_PLUGIN_NAME ),
			'anzahl_zimmer'           => __( 'Anzahl Zimmer', WPI_PLUGIN_NAME ),
			'anzahl_gewerbeeinheiten' => __( 'Anzahl Gewerbeeinheiten', WPI_PLUGIN_NAME ),
			'lagerflaeche'            => __( 'Lagerfläche', WPI_PLUGIN_NAME ),
			'bueroflaeche'            => __( 'Bürofläche', WPI_PLUGIN_NAME ),
			'kellerflaeche'           => __( 'Kellerfläche', WPI_PLUGIN_NAME ),
		);
		$single_hardfacts = $this->options['wpi_single_hardfacts'];

		ob_start();
		?>
        <div class="col-xs-12 col-md-6 list-group hardfacts">
            <table class="form-table list-group-item">
                <tr valign="top row">
                    <td scope="row" class="col-xs-12">
                        <h4 class="text-danger">
							<?php echo __( 'Hardfacts', WPI_PLUGIN_NAME ); ?>
                            <span class="pull-right"><?php echo $this->get_pro_badge(); ?></span>
                        </h4>
                    </td>
                </tr>
                <tr valign="top row">
                    <td>
						<?php if ( ! self::versionStatus() ): ?>
                            <div class="alert alert-danger">
                                Diese Einstellungen haben nur in der Pro-Version ihre Wirkung !
                            </div>
						<?php endif; ?>
                        <fieldset>
                            <label for="selector" class="col-xs-12">
                                <input type="checkbox" class="selector"/>
                                Alles an/abwählen
                            </label>
                            <hr>
							<?php foreach ( $hardfacts as $fl_key => $fl_text ) { ?>
                                <label class="col-xs-12 col-md-6">
                                    <input id="wpi_single_hardfacts[<?= $fl_key; ?>]"
                                           name="wpi_single_hardfacts[<?= $fl_key; ?>]"
                                           type="checkbox"
                                           value="<?php esc_attr_e( $fl_text ); ?>"
                                           class=""
										<?php if ( isset( $single_hardfacts[ $fl_key ] ) ):
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
        </div>

		<?php

		return ob_get_clean();
	}

	/**
	 * Single Meta Kontakt Auswahl-Box
	 */
	public function SingleMetaContact() {
		// Tabellen-Array für Hardfacts
		@$kontakt_items = array(
			'firma'          => __( 'Firma', WPI_PLUGIN_NAME ),
			'name'           => __( 'Name', WPI_PLUGIN_NAME ),
			'vorname'        => __( 'Vorname', WPI_PLUGIN_NAME ),
			'strasse'        => __( 'Strasse', WPI_PLUGIN_NAME ),
			'hausnummer'     => __( 'Hausnummer', WPI_PLUGIN_NAME ),
			'plz'            => __( 'PLZ', WPI_PLUGIN_NAME ),
			'ort'            => __( 'Ort', WPI_PLUGIN_NAME ),
			'email_zentrale' => __( 'Email Zentrale', WPI_PLUGIN_NAME ),
			'email_direkt'   => __( 'Email Direkt', WPI_PLUGIN_NAME ),
			'tel_zentrale'   => __( 'Telefon Zentrale', WPI_PLUGIN_NAME ),
			'tel_durchw'     => __( 'Telefon Durchwahl', WPI_PLUGIN_NAME ),
			'tel_fax'        => __( 'Fax', WPI_PLUGIN_NAME ),
			'tel_handy'      => __( 'Mobil', WPI_PLUGIN_NAME ),
			'postf_plz'      => __( 'Postfach PLZ', WPI_PLUGIN_NAME ),
			'postf_ort'      => __( 'Postfach Ort', WPI_PLUGIN_NAME ),
			'email_privat'   => __( 'Email Privat', WPI_PLUGIN_NAME ),
			'email_sonstige' => __( 'Weitere Email', WPI_PLUGIN_NAME ),
			'email_feedback' => __( 'Feedback', WPI_PLUGIN_NAME ),
			'tel_privat'     => __( 'Telefon Privat', WPI_PLUGIN_NAME ),
			'tel_sonstige'   => __( 'Weitere Rufnummern', WPI_PLUGIN_NAME ),
			'url'            => __( 'Webseite / URL', WPI_PLUGIN_NAME )
		);
		$single_contacts = $this->options['wpi_single_contacts'];

		ob_start();
		?>
        <div class="col-xs-12 col-md-6 list-group contacts">
            <table class="form-table list-group-item">
                <tr valign="top row">
                    <td scope="row" class="col-xs-12">
                        <h4 class="text-danger">
							<?php echo __( 'Kontaktdetails', WPI_PLUGIN_NAME ) ?>
                            <span class="pull-right"><?php echo $this->get_pro_badge(); ?></span>
                        </h4>
                    </td>
                </tr>
                <tr valign="top row">
                    <td>
						<?php if ( ! self::versionStatus() ): ?>
                            <div class="alert alert-danger">
                                Diese Einstellungen haben nur in der Pro-Version ihre Wirkung !
                            </div>
						<?php endif; ?>
                        <fieldset>
                            <label for="selector" class="col-xs-12">
                                <input type="checkbox" class="selector"/>
                                Alles an/abwählen
                            </label>
                            <hr>
							<?php foreach ( $kontakt_items as $cont_key => $cont_text ) { ?>
                                <label class="col-xs-12 col-md-6">
                                    <input id="wpi_single_contacts[<?= $cont_key; ?>]"
                                           name="wpi_single_contacts[<?= $cont_key; ?>]"
                                           type="checkbox"
                                           value="<?php esc_attr_e( $cont_text ); ?>"
                                           class=""
										<?php if ( isset( $single_contacts[ $cont_key ] ) ):
											echo 'checked="checked"';
										else:
											echo '';
										endif;
										?> />
									<?php echo '&nbsp;' . $cont_text ?>
                                </label>
							<?php } ?>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>

		<?php

		return ob_get_clean();
	}

	/**
	 * Single Meta Preise Auswahl Box
	 * @return string
	 */
	public function SingleMetaPreise() {
		// Tabellen-Array für Preise
		@$preise = array
		(
			'kaufpreis'         => __( 'Kaufpreis', WPI_PLUGIN_NAME ),
			'kaltmiete'         => __( 'Kaltmiete', WPI_PLUGIN_NAME ),
			'nettokaltmiete'    => __( 'Nettokaltmiete', WPI_PLUGIN_NAME ),
			'nebenkosten'       => __( 'Nebenkosten', WPI_PLUGIN_NAME ),
			'warmmiete'         => __( 'Warmmiete', WPI_PLUGIN_NAME ),
			'mietpreis_pro_qm'  => __( 'Mietpreis pro m²', WPI_PLUGIN_NAME ),
			'kaufpreis_pro_qm'  => __( 'Kaufpreis pro m²', WPI_PLUGIN_NAME ),
			'mieteinnahmen_ist' => __( 'Jahresnettomiete', WPI_PLUGIN_NAME ),
			'x_fache'           => __( 'Faktor', WPI_PLUGIN_NAME ),
			'kaution'           => __( 'Kaution', WPI_PLUGIN_NAME ),
			'aussen_courtage'   => __( 'Provision', WPI_PLUGIN_NAME )
		);
		$single_preise = $this->options['wpi_single_preise'];

		ob_start();
		?>
        <div id="preise" class="col-xs-12 col-md-6 list-group preise">
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
                            <label for="selector" class="col-xs-12">
                                <input type="checkbox" class="selector"/>
                                Alles an/abwählen
                            </label>
                            <hr>
							<?php foreach ( $preise as $preiskey => $preistext ) { ?>
                                <label class="col-xs-12 col-md-6">
                                    <input id="wpi_single_preise[<?= $preiskey; ?>]"
                                           name="wpi_single_preise[<?= $preiskey; ?>]"
                                           type="checkbox"
                                           value="<?php esc_attr_e( $preistext ); ?>"
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
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Single Meta Flächen Auswahl Box
	 * @return string
	 */
	public function SingleMetaFlaechen() {
		// Tabellen-Array für Flächen
		@$flaechen = array
		(
			'wohnflaeche'             => __( 'Wohnfläche', WPI_PLUGIN_NAME ),
			'nutzflaeche'             => __( 'Nutzfläche', WPI_PLUGIN_NAME ),
			'grundstuecksflaeche'     => __( 'Grundstücksfläche', WPI_PLUGIN_NAME ),
			'anzahl_zimmer'           => __( 'Anzahl Zimmer', WPI_PLUGIN_NAME ),
			'anzahl_schlafzimmer'     => __( 'Anzahl Schlafzimmer', WPI_PLUGIN_NAME ),
			'anzahl_badezimmer'       => __( 'Anzahl Badezimmer', WPI_PLUGIN_NAME ),
			'anzahl_sep_wc'           => __( 'Gäste-WC', WPI_PLUGIN_NAME ),
			'anzahl_stellplaetze'     => __( 'Anzahl Stellplätze', WPI_PLUGIN_NAME ),
			'anzahl_balkone'          => __( 'Anzahl Balkone', WPI_PLUGIN_NAME ),
			'anzahl_terrassen'        => __( 'Anzahl Terrassen', WPI_PLUGIN_NAME ),
			'anzahl_gewerbeeinheiten' => __( 'Anzahl Gewerbeeinheiten', WPI_PLUGIN_NAME ),
			'lagerflaeche'            => __( 'Lagerfläche', WPI_PLUGIN_NAME ),
			'bueroflaeche'            => __( 'Bürofläche', WPI_PLUGIN_NAME ),
			'kellerflaeche'           => __( 'Kellerfläche', WPI_PLUGIN_NAME )
		);
		$single_flaechen = $this->options['wpi_single_flaechen'];


		ob_start();
		?>
        <div class="col-xs-12 col-md-6 list-group flaechen">
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
                            <label for="selector" class="col-xs-12">
                                <input type="checkbox" class="selector"/>
                                Alles an/abwählen
                            </label>
                            <hr>
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
        </div>
		<?php
		return ob_get_clean();
	}


	public function SingleActivateSmartNavigation() {
		ob_start();
		?>

        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Smart Navigation aktivieren', WPI_PLUGIN_NAME ); ?>
                    <span class="pull-right"><?php echo $this->get_pro_badge(); ?></span></h4>
            </div>
            <div class="panel-body">
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_show_smartnav" id="wpi_show_smartnav1" value="true"
							<?php echo $this->options['wpi_show_smartnav'] == 'true' ? 'checked' : '' ?>>
                        Aktivieren
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_show_smartnav" id="wpi_show_smartnav2" value="false"
							<?php echo $this->options['wpi_show_smartnav'] == 'false' ? 'checked' : '' ?>>
                        Nicht aktivieren
                    </label>
                </div>
                <div class="alert alert-info">
                    Die einzelnen Buttons der Navigation können unter <a href="#features" role="tab" data-toggle="tab">Features</a>
                    festgelegt werden.
                </div>
            </div>
        </div>


		<?php
		return ob_get_clean();
	}

	public function SingleActivateArticleNavigation() {
		ob_start();
		?>

        <div class="panel panel-primary single-view">
            <div class="panel-heading">
                <h4><?php echo __( 'Artikel Navigation aktivieren', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_show_article_navigation" id="wpi_show_article_navigation1"
                               value="true"
							<?php echo $this->options['wpi_show_article_navigation'] == 'true' ? 'checked' : '' ?>>
                        Aktivieren
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="wpi_show_article_navigation" id="wpi_show_article_navigation2"
                               value="false"
							<?php echo $this->options['wpi_show_article_navigation'] == 'false' ? 'checked' : '' ?>>
                        Nicht aktivieren
                    </label>
                </div>
                <div class="alert alert-info">
					<?php echo __( 'Mit der Artikel Navigation kann von einer zur nächsten / vorherigen Immobilie bzw. zur Übersicht navigiert werden', WPI_PLUGIN_NAME ); ?>
                </div>
            </div>
        </div>


		<?php
		return ob_get_clean();
	}

	// Shortcodes
	public function ShortcodesSelectPage() {
		$pages = get_pages();
		foreach ( $pages as $page ) {
			$list_pages[] = $page->post_name;
		}
		$option = '';
		ob_start();
		?>
        <select name="wpi_search_page">
            <option><?php echo $this->options['wpi_search_page']; ?></option>
			<?php foreach ( $list_pages as $page ) { ?>
                <option value="<?= $page ?>"><?= $page; ?></option>;
			<?php } ?>
        </select>
		<?php
		return ob_get_clean();
	}

	public static function ToggleSwitch( $name, $checked_statement ) {
		ob_start();
		?>
        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            .switch input {
                display: none;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #428bca;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #428bca;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
        </style>

        <label class="switch">
            <input type="checkbox" name="<?php echo $name; ?>"
				<?php echo $checked_statement !== null ? 'checked="checked"' : '' ?>>
            <span class="slider round"></span>
        </label>
		<?php
		return ob_get_clean();
	}

	// Features
	public function smart_navi_setup() {
		ob_start();
		?>
        <div class="alert alert-info">Wenn das Text / Icon Feld frei gelassen wird, werden diese nicht angezeigt.</div>

		<?php $offsetItem = 0; ?>

		<?php foreach ( $this->smart_nav as $option ) : ?>
            <div class="col-xs-12">
                <strong>Navigations-Link <?= $offsetItem + 1 ?></strong>
                <div class="clearfix"></div>
                <div class="form-group col-md-4">
					<?php //zeigen($option) ?>
                    <label for="beispielFeldName2"> Text / Icon</label>
                    <input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][beschreibung]"
                           value="<?php echo esc_html( $option['beschreibung'] ) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="beispielFeldEmail2"> Title</label>
                    <input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][title]"
                           value="<?php echo esc_html( $option['title'] ) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="beispielFeldEmail2"> Link - Ziel</label>
                    <input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][link]"
                           value="<?php echo esc_html( $option['link'] ) ?>">
                </div>
                <div class="clearfix"></div>
                <hr/>
            </div>
			<?php
			$offsetItem ++;

		endforeach; ?>


		<?php
		return ob_get_clean();
	}

	// Dashboard Widget Immobilien
	static function wpi_dashboard_text() {
		$count_posts = wp_count_posts( 'wpi_immobilie' );
		$terms       = get_terms( array( 'vermarktungsart', 'objekttyp', 'immobiliengruppe' ), array(
			'hide_empty' => false,
		) );
		$count_terms = wp_count_terms( array( 'objekttyp', 'vermarktungsart', 'immobiliengruppe' ) );
		?>
        <style type="text/css">
            .widget-container {
                box-sizing: border-box;
                margin: 0 auto;
                contain: content;
            }

            .admin-links, .post_type-links, .statistik {
                clear: both;
                box-sizing: border-box;
            }

            .post_type-links, .statistik {
                padding-top: 2em;
            }

            .admin-links ul li, .post_type-links ul li, .statistik ul li {
                width: 50%;
                float: left;
                position: relative;
            }

            ul.gesamt li {
                border-bottom: 1px solid #ccc;
                padding-bottom: 1em;
            }

            .badge {
                display: inline-block;
                min-width: 10px;
                padding: 3px 7px;
                font-size: 12px;
                font-weight: bold;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                background-color: #777;
                border-radius: 10px;
            }

            span.count {
                position: absolute;
                left: 150px;
            }
        </style>
        <div class="widget-container">
            <div class="admin-links">
                <h4>WP Immo Manager - Admin-Links</h4>
                <hr>
                <ul>
                    <li><a href="admin.php?page=wpi_general_page"><?php echo __( 'General', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                    <li><a href="admin.php?page=wpi_posttype_page"><?php echo __( 'Post-Type', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                    <li><a href="admin.php?page=wpi_single_page"><?php echo __( 'Single-View', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                    <li><a href="admin.php?page=wpi_list_page"><?php echo __( 'List-View', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                    <li>
                        <a href="admin.php?page=wpi_shortcodes_page"><?php echo __( 'Shortcodes', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                    <li><a href="admin.php?page=wpi_features_page"><?php echo __( 'Features', WPI_PLUGIN_NAME ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="post_type-links">
                <h4>WP Immo Manager - Post-Type Links</h4>
                <hr>
                <ul>
                    <li><a href="edit.php?post_type=wpi_immobilie">Alle Immobilien</a></li>
                    <li><a href="edit-tags.php?taxonomy=vermarktungsart&post_type=wpi_immobilie">Vermarktungsarten</a>
                    </li>
                    <li><a href="edit-tags.php?taxonomy=objekttyp&post_type=wpi_immobilie">Objekttypen</a></li>
                    <li><a href="edit-tags.php?taxonomy=immobiliengruppe&post_type=wpi_immobilie">Immobiliengruppen</a>
                    </li>

                </ul>
            </div>
            <div class="statistik">
                <h4>Statistik</h4>
                <hr>
                <ul class="gesamt">
                    <li><strong>Immobilien gesamt:</strong>
                        <span class="count badge"><?php echo $count_posts->publish; ?></span></li>
                    <li><strong>Taxonomien gesamt:</strong>
                        <span class="count badge"><?php echo $count_terms; ?></span></li>
                </ul>
                <ul>
					<?php

					foreach ( $terms as $term ):
						echo '<li>';
						echo $term->name . ':';
						echo '<span class="count badge">' . $term->count . '</span>';
						echo '</li>';
					endforeach;

					?>
                </ul>
            </div>
        </div>
		<?php echo check_valid_status(); ?>
		<?php

	}

	/**
	 * CSS-Container
	 * @return string
	 */
	public function css_container() {
		ob_start();
		$css = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/css/bootstrap.css';
		?>
        <!-- Laden von Bootstrap css -->
		<?php if ( 'active' === get_option( 'wpi_bootstrap_styles' ) ) { ?>
            <link rel="stylesheet" href="<?php echo $css; ?>"
                  type="text/css" media="all"/>
		<?php } ?>
        <style type="text/css">
            div#sidebar {
                margin-top: 4.5em;
            }
        </style>
		<?php
		return ob_get_clean();
	}

	/**
	 * Updated-Container
	 * @return string
	 */
	public function updated_text() {
		if ( ! isset( $_REQUEST['settings-updated'] ) ) {
			$_REQUEST['settings-updated'] = false;
		}
		if ( false !== $_REQUEST['settings-updated'] ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php echo __( 'Einstellungen gespeichert!', WPI_PLUGIN_NAME ); ?></strong></p>
            </div>
		<?php
		endif;
	}

	/**
	 * Script-Container
	 * @return string
	 */
	public function script_container() {
		$admin = new \wpi\wpi_classes\AdminClass();

		$js = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js';
		?>
        <script type="application/javascript" src="<?= WPI_PLUGIN_URL . 'js/adminpage/js-cookie.js'; ?>"></script>
        <script type="application/javascript" src="<?= WPI_PLUGIN_URL . 'js/adminpage/main.js'; ?>"></script>
		<?php if ( ! $admin->versionStatus() ): ?>
            <div class="klicktipp-widget">
                <script type="text/javascript"
                        src="https://klicktipp.s3.amazonaws.com/userimages/55826/forms/75549/1qnjz1ak1z8z9178.js"></script>
            </div>
		<?php endif; ?>
		<?php // echo check_valid_status(); ?>
		<?php
	}

	/**
	 * Admin Page Container mit Sidebar
	 */
	static function sidebar_container( $content ) {
		global $version;
		$admin = new \wpi\wpi_classes\AdminClass();

		echo $admin->css_container();
		echo $admin->updated_text();
		?>

        <div class="wrap">
            <div class="row">
                <div class="col-md-8">
                    <h2>WP Immo Manager <?php echo $admin::versionName() ?> - Version <?php echo $version; ?></h2>
                    <div class="content">
						<?php echo $content; ?>
                    </div>
                </div>
                <div class="col-md-4">
					<?php echo $admin::wpi_sidebar_content(); ?>
                </div>
            </div>

        </div>

		<?php
		echo $admin->script_container();
	}

	/**
	 * Admin Page Container Full-Width
	 */
	static function fullwidth_container( $content ) {
		global $version;
		$admin = new \wpi\wpi_classes\AdminClass();

		echo $admin->css_container();
		echo $admin->updated_text();
		?>

        <div class="wrap">
            <div class="row">
                <div class="col-xs-12">
                    <h2>WP Immo Manager <?php echo $admin::versionName(); ?> - Version <?php echo $version; ?></h2>
                    <div class="content">
						<?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>

		<?php
		echo $admin->script_container();
	}

	/**
	 * Content für Admin Sidebar Content
	 * @return string
	 */
	static function wpi_sidebar_content() {
		$admin = new \wpi\wpi_classes\AdminClass;
		global $version;

		ob_start();
		?>
        <div id="sidebar">
            <div id="version-details" class="panel panel-primary">
                <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-info-sign"></span>
                    Plugin-Details
                </div>
                <div class="panel-body">
                    <strong>Version: </strong><?= $version; ?>
                    <hr>
                    <p>Du verwendest die <strong><?php echo $admin::versionName(); ?> </strong> Version.</p>
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

			<?php if ( ! $admin::versionStatus() ): ?>
                <div id="spende" class="panel panel-primary">
                    <div class="panel-heading text-center lead"><span class="glyphicon glyphicon-heart"></span> Hilf
                        uns
                    </div>
                    <div class="panel-body">
                        <p>Die Entwicklung kostet viel Zeit. <br>
                            Jeder Euro hilft uns den Service und die Funktionen des Plugins zu verbessern und
                            weiterzuentwickeln.</p>
                        <p>Die Höhe der Spende bleibt dir überlassen.</p>
                        <a target="_blank" href="http://wp-immo-manager.de" class="btn btn-primary col-xs-12">Jetzt
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
		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin Dashboard Page
	 * @return string
	 */
	static function wpi_dashboard_page() {
		$admin = new \wpi\wpi_classes\AdminClass();
		ob_start();
		?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?php echo __( 'Auf einen Blick', WPI_PLUGIN_NAME ) ?></h4>
                    </div>
                    <div class="panel-body">
						<?php echo $admin->wpi_dashboard_text(); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?php echo __( 'Automatische Synchronisation', WPI_PLUGIN_NAME ); ?>
							<?php echo $admin->get_pro_badge(); ?></h4>
                    </div>
                    <div class="panel-body">
						<?php if ( ! $admin::versionStatus() ): ?>
                            <div class="alert alert-danger">
                                <p class="lead"><span class="glyphicon glyphicon-remove"></span> Diese Funktion ist
                                    nur in der PRO-Version verfügbar.</p>
                            </div>
						<?php endif; ?>
                        <div class="<?php echo ! $admin::versionStatus() ? 'hidden' : 'pro_function'; ?>">
                            <div class="alert alert-info" role="alert">
                                <p><?php echo __( 'Beachte die Performance der Seite!!! Wenn nicht unbedingt notwendig, dann stell die Synchronisation auf "Täglich" oder "Halbtäglich". <br>
In Ausnahmefällen kann die Synchronisation auch Manuell durchgeführt werden.', WPI_PLUGIN_NAME ); ?>
                                </p>
                            </div>

							<?php echo $admin->auto_sync_form(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?php echo __( 'Manuelle Synchronisation', WPI_PLUGIN_NAME ); ?>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-4">
                            <form id="form1" name="form1" method="post" action="">
								<?php submit_button( __( 'Immobilien synchronisieren', WPI_PLUGIN_NAME ), 'primary', 'immo_sync' ); ?>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <h4>InfoBox</h4>

                            <p><?php print __( 'Meldungen der Synchronisation werden hier ausgegeben', WPI_PLUGIN_NAME ); ?></p>
							<?php
							if ( isset( $_POST['immo_sync'] ) ):
								$xml_file_array       = wpi_xml_auslesen(); //Funktion definiert in wpi_unzip_functions.php
								$GLOBALS['xml_array'] = wpi_xml_array( $xml_file_array ); //Funktion definiert in wpi_create_posts.php
								wpi_xml_standard();

							endif;
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin General Page
	 * @return string
	 */
	static function wpi_general_page() {

		$admin = new \wpi\wpi_classes\AdminClass();
		global $pro;
		ob_start();
		?>
        <form method="post" action="options.php">
			<?php settings_fields( 'wpi_licence_group' ); ?>
			<?php do_settings_sections( 'wpi_licence_group' ); ?>

            <div class="panel panel-primary" id="general">
                <div class="panel-heading">
                    <h4 class=""><?php echo __( 'Pro-Version aktivieren', WPI_PLUGIN_NAME ); ?></h4>
                </div>
                <div class="panel-body">
                    <div id="proFeatures">
                        <label for="wpi_licence">Lizenzeingabe:</label>
                        <input type="text" name="wpi_licence" value="<?php echo $admin->options['wpi_licence']; ?>">
                        <span
                                class=""><?php echo $pro === true ? '<i class="glyphicon glyphicon-ok text-success"></i>' : '<i class="glyphicon glyphicon-remove text-danger"></i>' ?></span>
                        <label for=""><?php echo __( 'Admin-Email', WPI_PLUGIN_NAME ) ?></label>
                        <input name="wpi_admin"
                               value="<?php echo $admin->options['wpi_admin'] != '' ? $admin->options['wpi_admin'] : get_option( 'admin_email' ); ?>">

                    </div>
					<?php submit_button(); ?>
                    <br>
					<?php if ( ! $pro ):
						echo '<div class="alert alert-danger" role="alert">
<p class="lead">' . __( 'Sie verwenden die Basis-Version, jetzt die <a class="alert-link" href="https://media-store.net/wp-immo-manager-landingpage/" target="_blank">Pro-Version aktivieren</a> um alle Features zu nutzen.', WPI_PLUGIN_NAME ) . '</p>
</div>';
					endif;
					?>
                </div>
            </div>
        </form>

        <form method="post" action="options.php">
			<?php settings_fields( 'wpi_standard_group' ); ?>
			<?php do_settings_sections( 'wpi_standard_group' ); ?>

            <div id="standard" class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class=""><?php echo __( 'Übertragungsstandard', WPI_PLUGIN_NAME ) ?></h4>
                </div>
                <div class="panel-body">
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
                </div>
            </div>

            <div id="bootstrap-styles" class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class=""><?php echo __( 'Bootstrap-Styles deaktivieren', WPI_PLUGIN_NAME ) ?></h4>
                </div>
                <div class="panel-body">

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
					<?php submit_button(); ?>
                </div>
            </div>
        </form>

        <form method="post" action="options.php">
			<?php settings_fields( 'wpi_xmlpath_group' ); ?>
			<?php do_settings_sections( 'wpi_xmlpath_group' ); ?>

            <div id="path" class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class=""><?php echo __( 'Einstellungen Pfade', WPI_PLUGIN_NAME ); ?></h4>
                </div>
                <div class="panel-body">
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
                            <th scope="row">Uploads:</th>
                            <td><input type="text" name="wpi_upload_pfad" size="70"
                                       value="<?php echo esc_attr( get_option( 'wpi_upload_pfad' ) ); ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Uploads_URL:</th>
                            <td><input type="text" name="wpi_upload_url" size="70"
                                       value="<?php echo esc_attr( get_option( 'wpi_upload_url' ) ); ?>"/>
                            </td>
                        </tr>
                    </table>

					<?php submit_button(); ?>
                </div>
            </div>
            <!--Ende Path-->
        </form>
        <!--Ende XML-Path-Form-->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?php echo __( 'Standardeinstellungen laden', WPI_PLUGIN_NAME ); ?></h4>
            </div>
            <div class="panel-body">
                <form method="post">
					<?php submit_button( __( 'Einstellungen zurücksetzen', WPI_PLUGIN_NAME ), 'primary', 'set_defaults' ); ?>
                </form>
            </div>
        </div>
		<?php

		if ( isset( $_POST['set_defaults'] ) ) {
			$admin->set_defaults();
		}

		return ob_get_clean();
	}

	/**
	 * Content für Admin Post-Type Page
	 * @return string
	 */
	static function wpi_posttype_page() {

		ob_start();
		?>
        <div id="post-type">
            <form method="post" action="options.php">
				<?php settings_fields( 'wpi_post_type_group' ); ?>
				<?php do_settings_sections( 'wpi_post_type_group' ); ?>
                <div id="post_type">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class=""><?php echo __( 'Slug für Post-Type "Immobilien"', WPI_PLUGIN_NAME ); ?></h4>
                        </div>
                        <div class="panel-body">
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
							<?php submit_button(); ?>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class=""><?php echo __( 'Titel der Immobilien' ); ?></h4>
                        </div>
                        <div class="panel-body">
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
							<?php submit_button(); ?>
                        </div>
                    </div>
                </div>
                <!--Ende #post_type-->
            </form>

        </div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin Single Page
	 * @return string
	 */
	static function wpi_single_page() {
		$admin = new \wpi\wpi_classes\AdminClass();
		global $pro;

		ob_start();
		?>
        <div id="single">
            <div class="">

                <form method="post" action="options.php">
					<?php settings_fields( 'wpi_post_single_view' ); ?>
					<?php do_settings_sections( 'wpi_post_single_view' ); ?>


					<?php echo $admin->SingleViewSelectForm(); ?>
					<?php submit_button(); ?>

                    <div id="tabnames" class="radio-tabs hidden">
						<?php echo $admin->SingleTabsNames(); ?>
						<?php //submit_button(); ?>
                    </div>

					<?php if ( $admin->options['wpi_pro'] === 'true' ): ?>
                        <div id="pagenames" class="radio-onePage hidden">
							<?php echo $admin->SingleOnePagePanels(); ?>
							<?php //submit_button(); ?>
                        </div>
                        <div id="sidebarTemplate" class="radio-SidebarPage hidden">
							<?php echo $admin->SingleSidebarPagePanels(); ?>
							<?php //submit_button(); ?>
                        </div>
                        <div class="row">
                            <div id="slider" class="col-md-6">
								<?php echo $admin->SingleSliderForm(); ?>
                            </div>
                            <div id="avatar" class="col-md-6">
								<?php echo $admin->SingleAvatarForm(); ?>
                            </div>
                            <div id="activateSmartNavi" class="col-md-6">
								<?php echo $admin->SingleActivateSmartNavigation(); ?>
                            </div>
                        </div>
					<?php endif; ?>
                    <div id="activateArticleNavigation">
						<?php echo $admin->SingleActivateArticleNavigation(); ?>
                    </div>

					<?php submit_button(); ?>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class=""><?php echo __( 'Meta-Daten', WPI_PLUGIN_NAME ) ?></h2>

                        </div>
                        <div class="panel-body">
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

							<?php echo $admin->SingleMetaPreise(); ?>

							<?php echo $admin->SingleMetaFlaechen(); ?>

							<?php echo $admin->SingleMetaHardfacts(); ?>

							<?php echo $admin->SingleMetaContact(); ?>

                            <div class="col-xs-12 col-md-6 list-group">
                                <div class="alert alert-info">
                                    <h4 class="text-danger">
										<?php echo __( 'Hardfacts', WPI_PLUGIN_NAME ); ?>
                                    </h4>
                                    <p>Hardfacts - ist eine Kurz-Übersicht der wichtigsten Angaben, wie Kaufpreis,
                                        Mietpreis, Wohnfläche etc.</p>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 list-group">
                                <div class="alert alert-info">
                                    <h4 class="text-danger">
										<?php echo __( 'Ausstattung / Zustand', WPI_PLUGIN_NAME ); ?>
                                    </h4>
                                    <p>Diese werden nur angezeigt wenn die Informationen auch
                                        verfügbar sind.</p>
                                </div>

                            </div>
							<?php submit_button(); ?>

                        </div>
                    </div>

                    <div class="panel panel-primary energiepass">
                        <div class="panel-heading">
							<?php $epass_texte = get_option( 'wpi_single_epass' ); ?>
                            <h4>
								<?php echo __( 'Texte Energiepass', WPI_PLUGIN_NAME ); ?>
                            </h4>
                        </div>
                        <div class="form-table panel-body">
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
							<?php submit_button(); ?>

                        </div>
                    </div>
                </form>

            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin List Page
	 * @return string
	 */
	static function wpi_list_page() {
		$admin        = new \wpi\wpi_classes\AdminClass();
		$optionsClass = new WpOptionsClass();
		$options      = $optionsClass->optionslist;
		$list_options = $options['wpi_list_options'];
		$list_details = $list_options['wpi_list_detail'];

		ob_start();

		//
		// Variablen für Auswahlliste Excerpt zum deaktivieren weiterer Einstellungen
		//
		if ( $list_options['wpi_list_excerpt'] == 'false' ):
			$detail_disable = '';
			$exc_disable    = 'disabled';
        elseif ( $list_options['wpi_list_excerpt'] == 'true' ):
			update_option( 'wpi_list_options[wpi_list_view_column]', 'excerpt' );
			$detail_disable = 'disabled';
			$exc_disable    = '';
		endif;

		// Array für Auswahlliste Details
		$metakeys = array(
			'kaufpreis'           => __( 'Kaufpreis', WPI_PLUGIN_NAME ),
			'kaltmiete'           => __( 'Kaltmiete', WPI_PLUGIN_NAME ),
			'nettokaltmiete'      => __( 'Nettokaltmiete', WPI_PLUGIN_NAME ),
			'warmmiete'           => __( 'Warmmiete', WPI_PLUGIN_NAME ),
			'nebenkosten'         => __( 'Nebenkosten', WPI_PLUGIN_NAME ),
			'mietpreis_pro_qm'    => __( 'Mietpreis pro m²', WPI_PLUGIN_NAME ),
			'kaufpreis_pro_qm'    => __( 'Kaufpreis pro m²', WPI_PLUGIN_NAME ),
			'mieteinnahmen_ist'   => __( 'Jahresnettomiete', WPI_PLUGIN_NAME ),
			'x_fache'             => __( 'Faktor', WPI_PLUGIN_NAME ),
			'ausen_courtage'      => __( 'Kaution', WPI_PLUGIN_NAME ),
			'wohnflaeche'         => __( 'Wohnfläche', WPI_PLUGIN_NAME ),
			'nutzflaeche'         => __( 'Nutzfläche', WPI_PLUGIN_NAME ),
			'grundstuecksflaeche' => __( 'Grundstücksfläche', WPI_PLUGIN_NAME ),
			'anzahl_zimmer'       => __( 'Anzahl Zimmer', WPI_PLUGIN_NAME ),
			'anzahl_badezimmer'   => __( 'Anzahl Badezimmer', WPI_PLUGIN_NAME ),
			'anzahl_schlafzimmer' => __( 'Anzahl Schlafzimmer', WPI_PLUGIN_NAME ),
			'anzahl_sep_wc'       => __( 'Anzahl Gäste WC', WPI_PLUGIN_NAME ),
			'baujahr'             => __( 'Baujahr', WPI_PLUGIN_NAME ),
		);

		?>
        <div id="liste">
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

					@$list_details = $list_options['wpi_list_detail']; ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>Suche im Template integrieren <?php echo $admin->get_pro_badge(); ?></h4>
                        </div>
                        <div class="panel-body">
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

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><?php echo __( 'Template für Listen-Ansicht', WPI_PLUGIN_NAME ) ?></h4>
                        </div>
                        <div class="panel-body">
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

                            <div class="col-xs-12 list-group-item">
                                <h4><?php echo __( 'Excerpt Einstellungen', WPI_PLUGIN_NAME ) ?></h4>
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
                            <div class="col-xs-12 list-group-item">
                                <h4><?php echo __( 'Listen Einstellungen', WPI_PLUGIN_NAME ) ?></h4>
								<?php $admin->versionStatus() != false ? $list = 'column' : $list = 'table' ?>
								<?php $admin->versionStatus() != false ? $div = 'div' : $div = 'table' ?>
								<?php $admin->versionStatus() != false ? $col = 'two-col-list' : $col = 'table' ?>
								<?php $templates = array(
									$div        => __( 'Als Hardfacts | ' . $admin->get_pro_badge(), WPI_PLUGIN_NAME ),
									$list       => __( 'Als 3-Spalten Liste | ' . $admin->get_pro_badge(), WPI_PLUGIN_NAME ),
									$col        => __( 'Als 2-Spalten Liste (Modern) | ' . $admin->get_pro_badge(), WPI_PLUGIN_NAME ),
									'table'     => __( 'Als Tabelle', WPI_PLUGIN_NAME ),
									'thumbnail' => __( 'Als Thumbnails', WPI_PLUGIN_NAME ),

								); ?>

                                <div class="col-sm-12 <?= $detail_disable; ?>">
                                    <fieldset>
                                        <label>Template-Auswahl für Liste</label><br>
                                        <select name="wpi_list_options[wpi_list_view_column]">
                                            <option value="<?php echo $list_options['wpi_list_view_column']; ?>">
												<?php echo $templates[ $list_options['wpi_list_view_column'] ]; ?>
                                            </option>
											<?php foreach ( $templates as $item => $name ): ?>
                                                <option value="<?php echo $item; ?>">
													<?php echo $name; ?>
                                                </option>
											<?php endforeach; ?>


                                        </select>
                                    </fieldset>
                                    <p>&nbsp;</p>
                                    <!--									<p class="col-sm-3 pull-left">-->
                                    <!--										<label for="list-view">Als Tabelle anzeigen</label>-->
                                    <!--										<input type="radio" name="wpi_list_options[wpi_list_view_column]"-->
                                    <!--										       value="table"-->
                                    <!--											-->
									<?php //echo( $list_options[ 'wpi_list_view_column' ] == 'table' ? 'checked="checked"' : '' ); ?><!-- />-->
                                    <!--									</p>-->
                                    <!--									<p class="col-sm-3 pull-left">-->
                                    <!--										<label for="list-view">Als Thumbnail anzeigen</label>-->
                                    <!--										<input type="radio" name="wpi_list_options[wpi_list_view_column]"-->
                                    <!--										       value="thumbnail"-->
                                    <!--											-->
									<?php //echo( $list_options[ 'wpi_list_view_column' ] == 'thumbnail' ? 'checked="checked"' : '' ); ?><!-- />-->
                                    <!--									</p>-->
                                    <!--									<p class="col-sm-3 pull-left">-->
                                    <!--										<label for="list-view">Als Hardfacts-->
                                    <!--											anzeigen</label>--><?php //echo $admin -> get_pro_badge(); ?>
                                    <!--										<input type="radio" name="wpi_list_options[wpi_list_view_column]"-->
                                    <!--										       value="-->
									<?php //echo $div; ?><!--"-->
                                    <!--											-->
									<?php //echo( $list_options[ 'wpi_list_view_column' ] == 'div' ? 'checked="checked"' : '' ); ?><!-- />-->
                                    <!--									</p>-->
                                    <!--									<p class="col-sm-3">-->
                                    <!--										<label for="column-view">Als Spalten-->
                                    <!--											anzeigen</label>--><?php //echo $admin -> get_pro_badge(); ?>
                                    <!--										<input type="radio" name="wpi_list_options[wpi_list_view_column]"-->
                                    <!--										       value="--><?//= $list; ?><!--"-->
                                    <!--											-->
									<?php //echo( $list_options[ 'wpi_list_view_column' ] == 'column' ? 'checked="checked"' : '' ); ?><!--/>-->
                                    <!--									</p>-->
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
                                                <label for="selector" class="col-xs-12">
                                                    <input type="checkbox" class="selector"/>
                                                    Alles an/abwählen
                                                </label>
                                                <hr>
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
                            <div class="col-xs-12 list-group-item">
                                <h4><?php echo __( 'New - Label', WPI_PLUGIN_NAME ); ?><?php echo $admin->get_pro_badge(); ?></h4>
                                <div class="alert alert-info">
									<?php echo __( 'Wieviel Tage nach Veröffentlichen soll das New - Label sichtbar sein?', WPI_PLUGIN_NAME ) ?>
                                </div>
                                <select name="wpi_list_options[wpi_list_view_new_label]">
                                    <option value="<?php echo $list_options['wpi_list_view_new_label']; ?>"><?php echo $list_options['wpi_list_view_new_label']; ?></option>
                                    <option value="1">1 <?php echo __( 'Tag', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="2">2 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="3">3 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="4">4 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="5">5 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="6">6 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                    <option value="7">7 <?php echo __( 'Tage', WPI_PLUGIN_NAME ); ?></option>
                                </select>
                            </div>
							<?php submit_button(); ?>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><?php echo __( 'Immobiliengruppen', WPI_PLUGIN_NAME ); ?><?php echo $admin->get_pro_badge(); ?></h4>
                        </div>
                        <div class="panel-body">
							<?php echo $admin->List_immogroup_form(); ?>
                        </div>
                    </div>


                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>
								<?php echo __( 'Sidebar in der Listenansicht anzeigen?', WPI_PLUGIN_NAME ); ?>
                            </h4>
                        </div>
                        <div class="panel-body">

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
							<?php submit_button(); ?>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!--"liste"-->

		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin Shortcode Page
	 * @return string
	 */
	static function wpi_shortcodes_page() {
		$admin = new \wpi\wpi_classes\AdminClass();

		ob_start();
		?>
        <div id="shortcodes">
            <h2>Shortcodes</h2>
            <form method="post" action="options.php">
				<?php settings_fields( 'wpi_shortcodes_group' ); ?>
				<?php do_settings_sections( 'wpi_shortcodes_group' ); ?>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?php echo __( 'Seite für Suchergebnisse auswählen', WPI_PLUGIN_NAME ); ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <p>Die Suchergebnisse aus der Umkreissuche bzw. Such-Filter werden hierher
                                umgeleitet!</p>
                        </div>

						<?php echo $admin->ShortcodesSelectPage(); ?>
						<?php submit_button(); ?>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading"><h4>Umkreissuche <?php echo $admin->get_pro_badge(); ?></h4></div>
                    <div class="panel-body">
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4>Search-Filter <?php echo $admin->get_pro_badge(); ?></h4>
                    </div>
                    <div class="panel-body">
                        <pre><code>[search_filter_form]</code></pre>
                        <p>
                            Mit dem Shortcode "Search Filter Form" kann ein erweiterter Such-Filter
                            entweder in einer Sidebar oder aber auf einer Page eingebunden werden.
                        </p>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4>Immobilien Query</h4>
                    </div>
                    <div class="panel-body">
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

		<?php
		return ob_get_clean();
	}

	/**
	 * Content für Admin Features Page
	 * @return string
	 */
	static function wpi_features_page() {
		$admin = new \wpi\wpi_classes\AdminClass();

		ob_start();
		?>
        <div id="features">
            <form method="post" action="options.php">
				<?php settings_fields( 'wpi_features_group' ); ?>
				<?php do_settings_sections( 'wpi_features_group' ); ?>

                <div id="custom_css" class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?php echo __( 'Benutzerdefinierte Styles (CSS)', WPI_PLUGIN_NAME ); ?></h4>
                    </div>
                    <div class="panel-body">
						<textarea rows="10" class="col-xs-12 col-md-8" name="wpi_custom_css">
                                        <?php echo trim( esc_html( get_option( 'wpi_custom_css' ) ) ); ?>
                                    </textarea>
                        <div class="clearfix"></div>
						<?php submit_button(); ?>
                    </div>
                </div>

				<?php if ( ! $admin::versionStatus() ): ?>
                    <div class="panel-primary">
                        <div class="panel-heading">
                            <h4>In der Pro Version sind hier weitere Features
                                verfügbar <?php echo $admin->get_pro_badge(); ?></h4>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>Benutzerdefiniertes HTML / Shortcodes einbindung</li>
                                <li>Smart-Navigation</li>
                                <li>Einbindung benutzerdefinierten Platzhalter-Image</li>
                                <li>Optionen für "Verkauft"</li>
                                <li>Optionen für "Reserviert"</li>
                            </ul>
                        </div>
                    </div>
				<?php endif; ?>

				<?php if ( $admin::versionStatus() ): ?>
                    <div id="custom-html" class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>
								<?php echo __( 'Benutzerdefiniertes HTML / Shortcodes', WPI_PLUGIN_NAME ); ?><?php echo $admin->get_pro_badge(); ?>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-12 col-md-8">
								<textarea rows="5" class="col-xs-12" name="wpi_custom_html">
                                        <?php echo trim( esc_html( get_option( 'wpi_custom_html' ) ) ); ?>
                                        </textarea>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <h4><?php echo __( 'Auswahl des Anzeigebereichs', WPI_PLUGIN_NAME ); ?></h4>
                                <fieldset>
                                    <select name="wpi_html_inject">
                                        <option><?= get_option( 'wpi_html_inject' ); ?></option>
                                        <option value="before_content">Vor dem Content</option>
                                        <option value="after_content">Nach dem Content</option>
                                        <option value="before_title">Vor dem Titel</option>
                                        <option value="after_title">Nach dem Titel</option>
                                        <option value="after_slider">Nach dem Image-Slider</option>
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

                    <div id="smart-navi" class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><?php echo __( 'Smart-Navigation', WPI_PLUGIN_NAME ); ?><?php echo $admin->get_pro_badge(); ?></h4>
                        </div>
                        <div class="panel-body">
							<?php echo $admin->smart_navi_setup(); ?>
                        </div>
                    </div>
                    <div id="top-immobilie" class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>
								<?php echo __( 'Einige Sonder-Features zur Anzeige der Immobilien', WPI_PLUGIN_NAME ); ?><?php echo $admin->get_pro_badge(); ?>
                            </h4>
                        </div>
                        <div class="panel-body">
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
												<?php echo __( 'Option für Verkauft / Vermietet anzeigen', WPI_PLUGIN_NAME ); ?>
                                            </h4>
                                        </tr>
                                        <tr>
                                            <td class="col-xs-1"><input type="checkbox"
                                                                        name="wpi_show_sold"
                                                                        id="wpi_show_sold" value="true"
													<?php echo( get_option( 'wpi_show_sold' ) == 'true' ? 'checked="checked"' : '' ); ?>/>
                                            </td>
                                            <td class="col-xs-9 col-md-4"><input type="text"
                                                                                 name="wpi_sold_text"
                                                                                 id="wpi_sold_text"
                                                                                 placeholder="Gib den Text ein der angezeigt werden soll"
                                                                                 value="<?php echo get_option( 'wpi_sold_text' ); ?>"
                                                                                 style="width: 100%;">
                                            </td>
                                            <td class="col-xs-9 col-md-4"><input type="text"
                                                                                 name="wpi_rented_text"
                                                                                 id="wpi_rented_text"
                                                                                 placeholder="Gib den Text ein der angezeigt werden soll"
                                                                                 value="<?php echo get_option( 'wpi_rented_text' ); ?>"
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
		<?php
		return ob_get_clean();
	}


}