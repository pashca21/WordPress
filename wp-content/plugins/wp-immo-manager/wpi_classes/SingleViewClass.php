<?php
/**
 * User: Media-Store.net
 * Date: 13.09.2016
 * Time: 09:35
 */

namespace wpi\wpi_classes;

use wpi\wpi_classes\components\FancyboxClass;
use wpi\wpi_classes\components\FlexSliderClass;
use wpi\wpi_classes\components\LightboxClass;

class SingleViewClass {

    public $single_preise;
    public $sigle_contact_fields;

    function __construct() {

        // auslesen der globalen Post Meta
        global $post;
        $this->post = $post;
        $this->meta = get_post_meta( $post->ID );

        // Auslesen der Kategorien
        $taxonomies = get_the_taxonomies();
        // Objekttypen
        $objekttyp = strstr( $taxonomies['objekttyp'], ' ' );
        $objekttyp = trim( $objekttyp, '.' );
        // Vermarktungsarten
        $vermarktung = strstr( $taxonomies['vermarktungsart'], ' ' );
        $vermarktung = trim( $vermarktung, '.' );
        /**
         * Firmenangaben -> if serialized
         *
         * @return array
         * else
         * @return string
         */
        $firma = @$this->meta['anbieterfirma'][0];
        if ( strpos( $firma, ";" ) ) {
            $firma = explode( ';', $firma );
        }
        // Laden der Optinen aus DB
        $optionsClass               = new WpOptionsClass();
        $this->options              = $optionsClass->wpi_get_options();
        $this->single_preise        = get_option( 'wpi_single_preise' );
        $this->single_flaechen      = get_option( 'wpi_single_flaechen' );
        $this->single_hardfacts     = get_option( 'wpi_single_hardfacts' );
        $this->sigle_contact_fields = get_option( 'wpi_single_contacts' );
        $this->html_inject          = get_option( 'wpi_html_inject' );
        $this->html                 = get_option( 'wpi_custom_html' );
        $this->uploadUrl            = get_option( 'wpi_upload_url' );
        $this->html_inject          = get_option( 'wpi_html_inject' );
        $this->html                 = get_option( 'wpi_custom_html' );
        $this->tabs                 = get_option( 'wpi_single_view_tabs' );
        $this->smart_nav            = get_option( 'wpi_smartnav' );

        // Info über Versionsstaus
        $admin     = new AdminClass();
        $this->pro = $admin::versionStatus();

        $this->objekttyp             = $objekttyp;
        $this->vermarktung           = $vermarktung;
        $this->anbieterkennung       = @$this->meta['anbieterkennung'][0];
        $this->firma                 = $firma;
        $this->objektkategorie_array = unserialize( $this->meta['objektkategorie'][0] );
        $this->geodaten              = unserialize( $this->meta['geodaten'][0] );
        $this->kontaktperson         = unserialize( $this->meta['kontaktperson'][0] );
        $this->preise                = unserialize( $this->meta['preise'][0] );
        $this->flaechen              = unserialize( $this->meta['flaechen'][0] );
        $this->ausstattung           = unserialize( $this->meta['ausstattung'][0] );
        $this->zustand_angaben       = unserialize( $this->meta['zustand_angaben'][0] );
        $this->anhaenge              = unserialize( $this->meta['anhaenge'][0] );
        $this->freitexte             = unserialize( $this->meta['freitexte'][0] );
        $this->verwaltung_objekt     = unserialize( $this->meta['verwaltung_objekt'][0] );
        $this->verwaltung_techn      = unserialize( $this->meta['verwaltung_techn'][0] );
        $this->anhang                = $this->help_handle_array( $this->anhaenge, 'anhang' );
        $this->bilder                = @$this->anhang['bilder'];

        // Gravatar
        if ( isset( $this->kontaktperson['email_zentrale'] ) ):
            $grav_hash = md5( $this->kontaktperson['email_zentrale'] );
        elseif ( isset( $this->kontaktperson['email_direkt'] ) ):
            $grav_hash = md5( $this->kontaktperson['email_direkt'] );
        endif;
        $this->gravatar = 'https://www.gravatar.com/avatar/' . $grav_hash . '?s=200&d=mm';


        // Überprüfung ob die Optionen für Preise und Flächen aus DB in den Meta vorhanden sind
        $this->preis          = array();
        $this->flaeche        = array();
        $this->hardfacts      = array();
        $this->contact_fields = array();

        if ( ! empty( $this->single_preise ) ):
            foreach ( $this->single_preise as $preis_key => $preis_value ) {
                if ( array_key_exists( $preis_key, $this->preise ) ):
                    $this->preis[ $preis_value ] = $this->preise[ $preis_key ];
                endif;
            }
        endif;

        if ( ! empty( $this->single_flaechen ) ):
            foreach ( $this->single_flaechen as $fl_key => $fl_value ) {
                if ( array_key_exists( $fl_key, $this->flaechen ) ):
                    $this->flaeche[ $fl_value ] = $this->flaechen[ $fl_key ];
                endif;
            }
        endif;

        if ( ! empty( $this->single_hardfacts ) ):
            foreach ( $this->single_hardfacts as $fl_key => $fl_value ) {
                if ( array_key_exists( $fl_key, $this->preise ) && ! $this->is_price_hidden() ):
                    $this->hardfacts[ $fl_value ] = $this->preise[ $fl_key ];
                endif;
                if ( array_key_exists( $fl_key, $this->flaechen ) ):
                    $this->hardfacts[ $fl_value ] = $this->flaechen[ $fl_key ];
                endif;
            }
        endif;

        if ( ! empty( $this->sigle_contact_fields ) ):
            foreach ( $this->sigle_contact_fields as $fl_key => $fl_value ) {
                if ( array_key_exists( $fl_key, $this->kontaktperson ) ):
                    $this->contact_fields[ $fl_key ] = $this->kontaktperson[ $fl_key ];
                endif;
            }
        endif;


    }

    /**
     * Templates...
     * Tabs, Accordion, OnePage, SidebarPage
     *
     * @return string
     */
    public function SingleTabs() {
        ob_start();
        ?>
        <style type="text/css">
            .printing {
                display: inline-block;
                float: left;
            }

            .main-title h2 {
                clear: none;
                margin-left: 2em;
                margin-bottom: 2em;
            }

            span.eckdaten_ort, span.eckdaten_strasse, span.value {
                font-weight: bolder;
            }

            .meta {
                padding-left: 2em;
            }

            .wpi .table td {
                width: 50%;
            }

            ul.list-unstyled.energiewerte li {
                width: 50%;
                float: left;
                line-height: 2;
            }

            ul.list-unstyled.energiewerte li:nth-child(even) {
                font-weight: bold;
            }
        </style>
        <section id="top" <?php post_class( 'wpi single-immobilie-tabs' ); ?> >

            <?php if ( $this->options['wpi_show_smartnav'] == 'true' ): ?>
                <div class="row">
                    <div class="smart-navi col-xs-12">
                        <?php echo $this->smart_navigation(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div id="wpi-main" class="site-main col-xs-12" role="main">
                <div class="custom-html custom-before-content">
                    <?php echo $this->get_custom_html( 'before_content' ); ?>
                </div>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="row">
                        <header class="wpi-header">
                            <?php echo $this->single_title_container(); ?>
                        </header>
                    </div>
                    <!-- .entry-header -->
                    <div class="row">
                        <div id="media-slider" class="imageslider col-md-8">

                            <?php echo $this->get_active_slider(); ?>

                            <div class="details-panel">
                                <?php echo $this->hardfacts_panel(); ?>
                            </div>
                        </div>
                        <!-- Ende Media Imageslider -->
                        <div id="eckdaten" class="col-md-4">
                            <?php echo $this->eckdaten(); ?>
                            <?php echo $this->get_modified_meta(); ?>
                        </div><!-- ende Eckdaten -->
                        <div class="custom-html custom-after-slider">
                            <?php echo $this->get_custom_html( 'after_slider' ); ?>
                        </div>
                    </div>

                    <div id="wpi-tabs" role="tabpanel" class="row"><?php
                        $tabs = get_option( 'wpi_single_view_tabs' );
                        ?>
                        <!-- Tabs-Navs -->
                        <ul id="tablist" class="nav nav-tabs nav-justified" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#details" role="tab" data-toggle="tab"><?php echo $tabs['details']; ?></a>
                            </li>
                            <li role="presentation">
                                <a href="#beschreibung-tab" role="tab"
                                   data-toggle="tab"><?php echo $tabs['beschreibung']; ?></a>
                            </li>
                            <li role="presentation">
                                <a href="#doku" role="tab" data-toggle="tab"><?php echo $tabs['bilder']; ?></a>
                            </li>
                            <li role="presentation">
                                <a href="#kontaktdaten" role="tab"
                                   data-toggle="tab"><?php echo $tabs['kontakt']; ?></a>
                            </li>
                        </ul>

                        <!-- Tab-Inhalte -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="details">
                                <div class="objektdetails panel panel-default">
                                    <div class="panel-body">
                                        <?php echo $this->tax_anbieter_panel(); ?>
                                    </div>
                                </div><!-- .objektdetails -->

                                <?php if ( ! empty( $this->preis ) && ! $this->is_price_hidden() ): ?>
                                    <div class="preise panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= __( 'Preise', WPI_PLUGIN_NAME ); ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php echo $this->preis_panel( 'table' ); ?>
                                        </div>
                                    </div>
                                    <!-- Div .Preise-->
                                <?php endif; ?>

                                <?php if ( ! empty( $this->flaeche ) ): ?>
                                    <div class="flaechen panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= __( 'Flächen', WPI_PLUGIN_NAME ); ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php echo $this->flaechen_panel(); ?>
                                        </div>
                                    </div>
                                    <!-- Div .Flaechen -->
                                <?php endif; ?>

                                <?php if ( ! empty( $this->ausstattung ) ): ?>
                                    <div class="ausstattung panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= __( 'Ausstattung', WPI_PLUGIN_NAME ); ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php echo $this->ausstattungs_panel(); ?>
                                        </div>
                                    </div>
                                    <!-- Div .Ausstattung -->
                                <?php endif; ?>

                                <?php if ( ! empty( $this->zustand_angaben ) ): ?>
                                    <div class="zustand panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= __( 'Objektzustand / Energiepass', WPI_PLUGIN_NAME ); ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php echo $this->zustands_panel(); ?>
                                            <?php echo $this->energiepass(); ?>
                                        </div>
                                    </div>
                                    <!-- Div .Zustand -->
                                <?php endif; ?>

                                <div class="custom-html custom-details-div">
                                    <?php echo $this->get_custom_html( 'details' ); ?>
                                </div>
                            </div>
                            <!-- Ende Details Panel -->

                            <div role="tabpanel" class="tab-pane fade" id="beschreibung-tab">
                                <div id="beschreibung"><?php echo $this->wpim_post_content(); ?></div>
                            </div>
                            <!-- Ende Beschreibung -->

                            <div role="tabpanel" class="tab-pane fade" id="doku">

                                <?php echo $this->get_documents(); ?>

                            </div>
                            <!-- Ende #doku -->

                            <div role="tabpanel" class="tab-pane fade" id="kontaktdaten">
                                <div class="col-xs-12 col-md-6">
                                    <?php echo $this->get_kontakt(); ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <?php if ( @$this->options['wpi_avatar']['active'] === 'true' ): ?>
                                        <div class="avatar-div text-center">
                                            <img alt="Gravatar"
                                                 src="<?php echo ! empty( $this->options['wpi_avatar']['avatar_url'] ) ? $this->options['wpi_avatar']['avatar_url'] : $this->gravatar; ?>"
                                                 class="avatar">
                                        </div>
                                    <?php endif; ?>
                                    <div class="firma">
                                        <?php echo $this->get_firma(); ?>
                                    </div>
                                </div>
                                <div class="custom-html custom-kontakt-div">
                                    <?php echo $this->get_custom_html( 'kontaktperson' ); ?>
                                </div>
                            </div>
                            <!-- Ende Kontaktdaten -->
                        </div>
                        <!-- Ende Tab-content -->
                    </div>
                    <!-- Ende Tab-Panel -->

                    <?php
                    if ( $this->options['wpi_show_article_navigation'] == 'true' ) {
                        echo '<div class="immo-nav">';
                        echo $this->view_article_navigation();
                        echo '</div>';
                    }
                    ?>

                </article>
                <div class="custom-html custom-after-content">
                    <?php echo $this->get_custom_html( 'after_content' ); ?>
                </div>
            </div>
            <!-- #main -->

        </section>
        <?php
        return ob_get_clean();
    }


    public function SingleAccordion() {
        ob_start();
        ?>
        <style type="text/css">
            .printing {
                display: inline-block;
                float: left;
            }

            .main-title h2 {
                clear: none;
                margin-left: 2em;
                margin-bottom: 2em;
            }

            span.eckdaten_ort, span.eckdaten_strasse, span.value {
                font-weight: bolder;
            }

            .meta {
                padding-left: 2em;
            }

            .wpi .table td {
                width: 50%;
            }

            ul.list-unstyled.energiewerte li {
                width: 50%;
                float: left;
                line-height: 2;
            }

            ul.list-unstyled.energiewerte li:nth-child(even) {
                font-weight: bold;
            }

            nav.navigation.immo-navigation .btn-group {
                padding: 50px 0;
            }
        </style>

        <section id="top" <?php post_class( 'wpi single-immobilie-accordion' ); ?> >

            <?php if ( $this->options['wpi_show_smartnav'] == 'true' ): ?>
                <div class="row">
                    <div class="smart-navi col-xs-12">
                        <?php echo $this->smart_navigation(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div id="wpi-main" class="site-main col-xs-12" role="main">
                <div class="custom-html custom-before-content">
                    <?php echo $this->get_custom_html( 'before_content' ); ?>
                </div>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="row">
                        <header class="wpi-header">
                            <?php echo $this->single_title_container(); ?>
                        </header>
                    </div>
                    <!-- .entry-header -->
                    <div class="row">
                        <div id="media-slider" class="imageslider col-md-8">
                            <?php echo $this->get_active_slider(); ?>
                            <div class="details-panel">
                                <?php echo $this->hardfacts_panel(); ?>
                            </div>
                        </div>
                        <!-- Ende Media Imageslider -->
                        <div id="eckdaten" class="col-md-4">
                            <?php echo $this->eckdaten(); ?>
                            <?php echo $this->get_modified_meta(); ?>
                        </div><!-- ende Eckdaten -->
                        <div class="custom-html custom-after-slider">
                            <?php echo $this->get_custom_html( 'after_slider' ); ?>
                        </div>
                    </div>

                    <div class="panel-group" id="wpi-accordion" role="tablist" aria-multiselectable="true">
                        <?php $tabs = get_option( 'wpi_single_view_tabs' ); ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="details">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#wpi-accordion"
                                       href="#collapseEins"
                                       aria-expanded="true" aria-controls"collapseEins">
                                    <?php echo $tabs['details']; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseEins" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="details">
                                <div class="panel-body">

                                    <div class="objektdetails panel panel-default">
                                        <div class="panel-body">
                                            <?php echo $this->tax_anbieter_panel(); ?>
                                        </div>
                                    </div><!-- .objektdetails -->

                                    <?php if ( ! empty( $this->preis ) && ! $this->is_price_hidden() ): ?>
                                        <div class="preise panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?= __( 'Preise', WPI_PLUGIN_NAME ); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <?php echo $this->preis_panel( 'table' ); ?>
                                            </div>
                                        </div>
                                        <!-- Div .Preise-->
                                    <?php endif; ?>

                                    <?php if ( ! empty( $this->flaeche ) ): ?>
                                        <div class="flaechen panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?= __( 'Flächen', WPI_PLUGIN_NAME ); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <?php echo $this->flaechen_panel(); ?>
                                            </div>
                                        </div>
                                        <!-- Div .Flaechen -->
                                    <?php endif; ?>

                                    <?php if ( ! empty( $this->ausstattung ) ): ?>
                                        <div class="ausstattung panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?= __( 'Ausstattung', WPI_PLUGIN_NAME ); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <?php echo $this->ausstattungs_panel(); ?>
                                            </div>
                                        </div>
                                        <!-- Div .Ausstattung -->
                                    <?php endif; ?>

                                    <?php if ( ! empty( $this->zustand_angaben ) ): ?>
                                        <div class="zustand panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?= __( 'Objektzustand / Energiepass', WPI_PLUGIN_NAME ); ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <?php echo $this->zustands_panel(); ?>
                                                <?php echo $this->energiepass(); ?>
                                            </div>
                                        </div>
                                        <!-- Div .Zustand -->
                                    <?php endif; ?>

                                    <div class="custom-html custom-details-div">
                                        <?php echo $this->get_custom_html( 'details' ); ?>
                                    </div>
                                </div>
                            </div><!-- panel 1 -->

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="objektbeschreibung">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#wpi-accordion"
                                           href="#collapseZwei" aria-expanded="false" aria-controls"collapseZwei">
                                        <?php echo $tabs['beschreibung']; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseZwei" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="objektbeschreibung">
                                    <div class="panel-body">
                                        <div id="beschreibung"><?php echo $this->wpim_post_content(); ?></div>
                                    </div>
                                </div>
                            </div><!-- panel 2 -->

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="doku">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#wpi-accordion"
                                           href="#collapseDrei" aria-expanded="false" aria-controls"collapseDrei">
                                        <?php echo $tabs['bilder']; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseDrei" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="doku">
                                    <div class="panel-body">
                                        <?php echo $this->get_documents(); ?>
                                    </div>
                                </div>
                            </div><!-- panel 3 -->

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="überschriftVier">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#wpi-accordion"
                                           href="#collapseVier" aria-expanded="false" aria-controls"collapseDrei">
                                        <?php echo $tabs['kontakt']; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseVier" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="überschriftVier">
                                    <div class="col-xs-12 col-md-6">
                                        <?php echo $this->get_kontakt(); ?>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <?php if ( @$this->options['wpi_avatar']['active'] === 'true' ): ?>
                                            <div class="avatar-div text-center">
                                                <img alt="Gravatar"
                                                     src="<?php echo ! empty( $this->options['wpi_avatar']['avatar_url'] ) ? $this->options['wpi_avatar']['avatar_url'] : $this->gravatar; ?>"
                                                     class="avatar">
                                            </div>
                                        <?php endif; ?>
                                        <div class="firma">
                                            <?php echo $this->get_firma(); ?>
                                        </div>
                                    </div>
                                    <div class="custom-html custom-kontakt-div">
                                        <?php echo $this->get_custom_html( 'kontaktperson' ); ?>
                                    </div>
                                </div>
                            </div><!-- Kontakt-Panel -->

                        </div><!-- Ende wpi-accordion -->

                        <?php
                        if ( $this->options['wpi_show_article_navigation'] == 'true' ) {
                            echo '<div class="immo-nav">';
                            echo $this->view_article_navigation();
                            echo '</div>';
                        }
                        ?>
                </article>
                <div class="custom-html custom-after-content">
                    <?php echo $this->get_custom_html( 'after_content' ); ?>
                </div>
            </div>
        </section>


        <?php
        return ob_get_clean();
    }

    /**
     * Function to Render the OnePage View
     *
     * @return string
     */
    public function onePage() {
        ob_start();
        ?>
        <style type="text/css">
            .printing {
                display: inline-block;
                float: left;
            }

            .main-title h2 {
                clear: none;
                margin-left: 2em;
                margin-bottom: 2em;
            }

            span.eckdaten_ort, span.eckdaten_strasse, span.value {
                font-weight: bolder;
            }

            .meta {
                padding-left: 2em;
            }
        </style>
        <section id="top" <?php post_class( 'wpi single-immobilie-onepage' ); ?>>

            <?php if ( $this->options['wpi_show_smartnav'] == 'true' ): ?>
                <div class="row">
                    <div class="smart-navi col-xs-12">
                        <?php echo $this->smart_navigation(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div id="wpi-main" class="site-main col-xs-12" role="main">

                <div class="custom-html custom-before-content">
                    <?php echo $this->get_custom_html( 'before_content' ); ?>
                </div>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <?php echo $this->get_active_slider(); ?>
                            <div class="details-panel">
                                <?php echo $this->hardfacts_panel(); ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <?php echo $this->eckdaten(); ?>
                            <?php echo $this->get_modified_meta(); ?>
                        </div>
                        <div class="custom-html custom-after-slider">
                            <?php echo $this->get_custom_html( 'after_slider' ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <header class="wpi-header">
                            <?php echo $this->single_title_container(); ?>
                        </header>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['beschreibung'], WPI_PLUGIN_NAME ); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="beschreibung"><?php echo @$this->get_freitext( 'objektbeschreibung' ); ?></div>
                                    <?php $arg = $this->get_freitext( 'lage' ); ?>
                                    <?php if ( ! empty( $arg ) ): ?>
                                        <h3 class="lage"><?= __( 'Lage', WPI_PLUGIN_NAME ); ?></h3>
                                        <div class="lage"><?php echo @$this->get_freitext( 'lage' ); ?></div>
                                    <?php endif; ?>
                                    <?php $arg = $this->get_freitext( 'austatt_beschr' ); ?>
                                    <?php if ( ! empty( $arg ) ): ?>
                                        <h3 class="ausstattungsbeschreibung"><?= __( 'Ausstattungsbeschreibung', WPI_PLUGIN_NAME ); ?></h3>
                                        <div class="ausstattung"><?php
                                            echo @$this->get_freitext( 'austatt_beschr' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div id="modal-button" class="text-right">
                                        <!-- Button, der das Modal aufruft -->
                                        <button type="button" class="btn btn-default btn-lg" data-toggle="modal"
                                                data-target="#post">
                                            Ganze Beschreibung ansehen
                                        </button>
                                    </div>
                                    <div class="custom-html custom-beschreibung-div">
                                        <?php echo $this->get_custom_html( 'beschreibung' ); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Objektbeschreibung -->
                            <div id="kontakt" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['kontakt'], WPI_PLUGIN_NAME ); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-xs-12 col-md-6">
                                        <?php echo $this->get_kontakt(); ?>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <?php if ( $this->options['wpi_avatar']['active'] === 'true' ): ?>
                                            <div class="avatar-div text-center">
                                                <img alt="Gravatar"
                                                     src="<?php echo ! empty( $this->options['wpi_avatar']['avatar_url'] ) ? $this->options['wpi_avatar']['avatar_url'] : $this->gravatar; ?>"
                                                     class="avatar">
                                            </div>
                                        <?php endif; ?>
                                        <div class="firma">
                                            <?php echo $this->get_firma(); ?>
                                        </div>
                                    </div>
                                    <div class="custom-html custom-kontakt-div">
                                        <?php echo $this->get_custom_html( 'kontaktperson' ); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- kontakt -->
                            <?php $arg = $this->ausstattungs_panel(); ?>
                            <?php if ( ! empty( $arg ) ): ?>
                                <div id="ausstattung" class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['ausstattung'], WPI_PLUGIN_NAME ); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $this->ausstattungs_panel(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                        <!-- hauptcontent -->
                        <div id="details" class="col-xs-12 col-md-4">
                            <div id="immo-art" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['details'], WPI_PLUGIN_NAME ); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $this->tax_anbieter_panel(); ?>
                                    <div class="custom-details-div"><?php
                                        if ( '' != $this->html_inject && $this->html_inject === 'details' ):
                                            echo do_shortcode( $this->html );
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php $arg = $this->preis_panel(); ?>
                            <?php if ( ! empty( $arg ) ): ?>
                                <div id="preise" class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['preise'], WPI_PLUGIN_NAME ); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $this->preis_panel(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php $arg = $this->flaechen_panel(); ?>
                            <?php if ( ! empty( $arg ) ): ?>
                                <div id="flaechen" class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['flaechen'], WPI_PLUGIN_NAME ); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $this->flaechen_panel(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php $arg = $this->zustands_panel(); ?>
                            <?php if ( ! empty( $arg ) ): ?>
                                <div id="zustand" class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['zustand'], WPI_PLUGIN_NAME ); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $this->zustands_panel(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ( $this->options['wpi_pro'] === 'true' ): ?>
                                <div id="energiepass" class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['energiepass'], WPI_PLUGIN_NAME ); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $this->energiepass(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id="dokumente" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['dokumente'], WPI_PLUGIN_NAME ); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $this->get_documents(); ?>
                                </div>
                            </div>
                            <div id="map" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= __( $this->options['wpi_single_onePage']['map'], WPI_PLUGIN_NAME ); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $this->get_map(); ?>
                                </div>
                            </div>
                        </div>
                        <!-- Side Content -->
                    </div>
                    <?php
                    if ( $this->options['wpi_show_article_navigation'] == 'true' ) {
                        echo '<div class="immo-nav">';
                        echo $this->view_article_navigation();
                        echo '</div>';
                    }
                    ?>
                </article>
                <div class="custom-html custom-after-content">
                    <?php echo $this->get_custom_html( 'after_content' ); ?>
                </div>
            </div>

        </section>

        <section id="modals">
            <!-- Modal Post -->
            <div class="modal fade" id="post" tabindex="-1" role="dialog" aria-labelledby="postLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label=">hließen"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="postLabel"><?php echo $this->wpim_post_title(); ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="meta">
                                <em><?php echo __( 'Erstellt am: ', WPI_PLUGIN_NAME ) . $this->post->post_date; ?> |
                                    <?php echo __( 'Zuletzt geändert: ', WPI_PLUGIN_NAME ) . $this->post->post_modified; ?></em>
                            </div>
                            <p>&nbsp;</p>
                            <div><?php echo $this->wpim_post_content(); ?></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Modal Post -->
            <!-- Modal Search -->
            <div class="modal fade" id="searchModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h3>Volltextsuche:</h3>
                            <p><em>Suche nach einem Kreis, Stadt, Ort etc.</em></p>
                            <?php echo $this->view_searchfield_wpmi(); ?><br/><br/>
                            <h3>Umkreissuche:</h3>
                            <P><em>Suche nach einer Postleitzahl und Entfernung.</em></P>
                            <?php echo do_shortcode( '[umkreissuche]' ); ?>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </section>

        <?php
        return ob_get_clean();
    }

    /**
     * Function to Render the SidebarPage View
     *
     * @return string
     */
    public function sidebarPage() {
        $sidebar = $this->options['wpi_single_sidebar_name'];
        ob_start();
        ?>
        <style type="text/css">
            #media .carousel-inner img {
                width: 100%;
                max-height: 100%;
                margin: 0 auto;
            }

            .details-panel {
                margin: 20px 0 20px;
            }

            .title {
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-flex-wrap: nowrap;
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap;
                -ms-align-items: center;
                -webkit-align-items: center;
                -ms-flex-align: center;
                align-items: center;
                margin: 0 0 31px;
            }

            .title .custom-html {
                width: auto;
            }

            .main-title {
                display: inline-flex;
            }

            h2.title-heading-left {
                font-size: 18px;
                font-family: "PT Sans", Arial, Helvetica, sans-serif;
                font-weight: 400;
                line-height: 1.5;
                letter-spacing: 0px;
                padding-right: 8px;
                text-align: left;
                color: #333333;
                margin: 1em 0;
            }

            .title-sep-container {
                position: relative;
                height: 6px;
                -ms-flex-grow: 1;
                -webkit-flex-grow: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
            }

            .title-sep {
                position: relative;
                width: 100%;
                border: 0 solid #E7E6E6;
                box-sizing: content-box;
            }

            .title-sep.sep-double {
                height: 6px;
                border-bottom-width: 1px;
                border-top-width: 1px;
                border-color: #e0dede;
            }

            .taxdetails table, .flaechen table, .preise table {
                margin-bottom: -2px;
            }

            .description-container h3 {
                display: none;
            }

            .map-container iframe {
                height: 300px;
            }
        </style>
        <section id="top" <?php post_class( 'wpi single-immobilie-sidebarpage' ); ?>>
            <?php if ( $this->options['wpi_show_smartnav'] == 'true' ): ?>
                <div class="row">
                    <div class="smart-navi col-xs-12">
                        <?php echo $this->smart_navigation(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="custom-html custom-before-content">
                    <?php echo $this->get_custom_html( 'before_content' ); ?>
                </div>
            </div>

            <div id="title-container" class="row">
                <div class="title">
                    <?php echo $this->single_title_container(); ?>
                </div>
            </div>

            <div id="wpi-main" class="site-main col-xs-12 col-md-8" role="main">

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div id="imageslider" class="row">
                        <div class="col-xs-12">
                            <?php echo $this->get_active_slider(); ?>
                        </div>
                    </div>

                    <div id="hardfactspanel" class="row">
                        <div class="col-xs-12 details-panel">
                            <?php echo $this->hardfacts_panel(); ?>
                        </div>
                    </div>

                    <div class="custom-html custom-after-slider row">
                        <?php echo $this->get_custom_html( 'after_slider' ); ?>
                    </div>

                    <div id="details-panel" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['details'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="details-container col-xs-12">
                            <?php echo $this->tax_anbieter_panel(); ?>
                            <?php echo $this->preis_panel(); ?>
                            <?php echo $this->flaechen_panel(); ?>
                            <?php echo $this->zustands_panel(); ?>
                        </div>
                        <div class="custom-html custom-details-div">
                            <?php echo $this->get_custom_html( 'details' ); ?>
                        </div>
                    </div>
                    <div id="content-container" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['beschreibung'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="description-container col-xs-12">
                            <?php echo $this->wpim_post_content(); ?>
                        </div>
                    </div>
                    <div id="map-container" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['lage'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="map-container col-xs-12">
                            <?php echo $this->get_map(); ?>
                        </div>
                    </div>
                    <div id="ausstattungs-container" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['ausstattung'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="ausstatt-container col-xs-12">
                            <?php echo $this->ausstattungs_panel(); ?>
                        </div>
                        <div class="custom-html custom-kontakt-div">
                            <?php echo $this->get_custom_html( 'kontaktperson' ); ?>
                        </div>
                    </div>
                    <div id="documents-container" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['dokumente'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="doku-container col-xs-12">
                            <?php echo $this->get_documents(); ?>
                        </div>
                    </div>

                    <div id="meta-container" class="row">
                        <div class="col-xs-12 title">
                            <h2 class="title-heading-left"><?php echo $this->options['wpi_single_sidebarPage_titles']['meta'] ?></h2>
                            <div class="title-sep-container">
                                <div class="title-sep sep-double"></div>
                            </div>
                        </div>
                        <div class="meta-container col-xs-12">
                            <?php echo $this->get_modified_meta(); ?>
                        </div>
                    </div>

                </article>
                <div class="custom-html custom-after-content">
                    <?php echo $this->get_custom_html( 'after_content' ); ?>
                </div>
            </div>
            <div id="wpi-sidebar" class="col-xs-12 col-md-4" role="complementary">
                <?php if ( is_active_sidebar( $sidebar ) ) { ?>
                    <ul id="sidebar">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </ul>
                <?php } ?>
            </div>
            <?php
            if ( $this->options['wpi_show_article_navigation'] == 'true' ) {
                echo '<div class="immo-nav">';
                echo $this->view_article_navigation();
                echo '</div>';
            }
            ?>
        </section>

        <section id="modals">
            <?php echo $this->search_modal_html(); ?>
        </section>
        <script type="application/javascript">
            jQuery(function ($) {

                var objNr = $('.objekt-nr-value').html();

                // hier den Namen des Feldes ihres Formulars übernehmen
                // bei vergabe einer ID kann es auch mit $('input#ihre-ID') angesprochen werden.

                $('input[name="objekt_id"]').val(objNr);

            })
        </script>

        <?php
        return ob_get_clean();
    }


    /**
     * Searchfield HTML
     *
     * @return string
     */
    public
    function view_searchfield_wpmi() {
        ob_start(); ?>
        <form class="search form-inline" role="search" action="<?php echo home_url( '/' ); ?>" method="get">
            <div class="input form-group">
                <label>
                    <span class="screen-reader-text"><?php echo _x( 'Suche:', 'label' ) ?></span>
                </label>
                <input type="search" class="form-control searchfield"
                       placeholder="<?= __( 'Suche...', WPI_PLUGIN_NAME ); ?>"
                       value="<?php echo get_search_query() ?>" name="s"
                       title="<?php echo esc_attr_x( 'Suche:', 'label' ) ?>"/>
            </div>
            <div class="submit form-group">
                <button type="submit"
                        class="btn btn-default searchbutton"><?= __( 'Los', WPI_PLUGIN_NAME ); ?></button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Article Navigation Buttons
     *
     * @return string
     */
    public
    function view_article_navigation() {
        $link_to_immo  = get_post_type_archive_link( 'wpi_immobilie' );
        $button_middle = '<a href="' . $link_to_immo . ' ">';
        $button_middle .= __( 'Immobilien-Übersicht', WPI_PLUGIN_NAME );
        $button_middle .= '</a>';

        ob_start();
        ?>
        <div class="article-navigation">
            <nav class="navigation immo-navigation" role="navigation">
                <div class="btn-group col-xs-12 text-center">
                    <div class="btn btn-default col-xs-4 btn-down">
                        <?php previous_post_link( '%link', '%title' ); ?>
                    </div>
                    <div class="btn btn-default col-xs-4 btn-overview">
                        <?php echo $button_middle; ?>
                    </div>
                    <div class="btn btn-default col-xs-4 btn-up">
                        <?php next_post_link( '%link', '%title' ); ?>
                    </div>
                </div>
            </nav>
            <!-- Loop-Navigation -->
        </div><!-- article-navigation -->

        <?php
        return ob_get_clean();
    }

    /**
     * Get the create and modified Meta of Post
     *
     * @return string
     */
    public function get_modified_meta() {
        return '
<div class="meta">
<em>Erstellt am: ' . $this->post->post_date . ' <br/>
Zuletzt geändert: ' . $this->post->post_modified . '
</em>
</div>';
    }

    /**
     * Imageslider HTML
     *
     * @return string
     */
    public
    function imageslider() {
        ob_start();
        echo $this->single_sold_container( $this->meta );
        ?>

        <div id="media" class="imageslider"><?php

            if ( $this->bilder ): ?>
                <div id="image-carousel"
                     class="carousel slide"
                     data-ride="carousel">
                <!-- Positionsanzeiger -->
                <ol class="carousel-indicators">
                    <?php
                    for (
                        $i = 0;
                        $i < count( $this->bilder );
                        $i ++
                    ) {
                        foreach ( $this->bilder[ $i ] as $alt => $pfad ) {
                            echo '<li data-target="#image-carousel" data-slide-to="' . $pfad . '"></li>';
                        }
                    }
                    ?>
                </ol>

                <!-- Verpackung für die Elemente -->
                <div class="carousel-inner" role="listbox"><?php
                    for ( $j = 0; $j < count( $this->bilder ); $j ++ ) {
                        foreach ( $this->bilder[ $j ] as $alt => $pfad ) {
                            $imgHtml = '<img src="' . $this->uploadUrl . $pfad . '" alt="' . $alt . '">';
                            ! empty( $alt ) ? $alt : $alt = '';
                            if ( $j === 0 ):
                                $str = '<div class="item active">';
                            else:
                                $str = '<div class="item">';
                            endif;

                            $str .= $this->is_lightbox(
                                $this->uploadUrl . $pfad,
                                $imgHtml
                            );
                            $str .= '<div class="carousel-caption">';
                            $str .= $alt;
                            $str .= '</div> </div>';
                            echo $str;

                        }
                    }
                    ?>
                </div>

                <!-- Schalter -->
                <a class="left carousel-control" href="#image-carousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Zurück</span>
                </a>
                <a class="right carousel-control" href="#image-carousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Weiter</span>
                </a>
                </div><?php
            else:
                echo '<img src="' . get_option( 'wpi_img_platzhalter' ) . '"/>';
            endif;
            ?>
        </div>
        <!-- Ende Media Imageslider -->
        <?php
        return ob_get_clean();
    }

    //TODO Flexslider as a class

    /**
     * Eckdaten HTML
     *
     * @return string
     */

    public
    function eckdaten() {
        ob_start();
        ?>
        <div id="eckdaten" class="eckdaten">
            <ul class="list-unstyled eckdaten">
                <li>
                    <span
                            class="eckdaten_ort"><?php echo $this->geodaten['plz'] . ' ' . $this->geodaten['ort'] ?></span>
                </li><?php
                if ( 'true' == $this->verwaltung_objekt['objektadresse_freigeben'] || '1' == $this->verwaltung_objekt['objektadresse_freigeben'] ):
                    ?>
                    <li>
                        <span
                                class="eckdaten_strasse"><?php echo ucfirst( @$this->geodaten['strasse'] ) . ' ' . @$this->geodaten['hausnummer']; ?></span>
                    </li>
                <?php
                endif;
                if ( ! empty( $this->preis ) && ! $this->is_price_hidden() ):
                    $preis = $this->help_handle_unit( $this->preis );
                    // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                    foreach ( array_filter( $preis ) as $key => $wert ) {
                        echo '<li>' . $key;
                        echo '<span class="price value">' . $wert . '</span></li>';
                    }
                endif;
                if ( ! empty( $this->zustand_angaben['baujahr'] ) ):
                    echo '<li>' . __( "Baujahr", WPI_PLUGIN_NAME ) . ' <span class="baujahr value"> ' . $this->zustand_angaben['baujahr'] . '</span></li>';
                endif;
                if ( ! empty( $this->flaechen['wohnflaeche'] ) ):
                    $wohnflaeche = $this->help_handle_unit( array( 'wohnflaeche' => $this->flaechen['wohnflaeche'] ) );
                    echo '<li>' . __( "Wohnfläche", WPI_PLUGIN_NAME ) . ' <span class="wohnflaeche value">' . $wohnflaeche['wohnflaeche'] . '</span></li>';
                endif;
                if ( ! empty( $this->flaechen['grundstuecksflaeche'] ) ):
                    $grund = $this->help_handle_unit( array( 'grundstuecksflaeche' => $this->flaechen['grundstuecksflaeche'] ) );
                    echo '<li>' . __( "Grundstück", WPI_PLUGIN_NAME ) . ' <span class="grundsteuck value">' . $grund['grundstuecksflaeche'] . '</span></li>';
                endif;
                if ( ! empty( $this->flaechen['anzahl_zimmer'] ) ):
                    echo '<li>' . __( "Anzahl Zimmer", WPI_PLUGIN_NAME ) . ' <span class="zimmerzahl value">' . number_format( $this->flaechen['anzahl_zimmer'], 0, ",", "" ) . '</span></li>';
                endif;
                if ( ! empty( $this->verwaltung_techn["objektnr_extern"] ) ):
                    echo '<li>' . __( "Objektnummer", WPI_PLUGIN_NAME ) . ' <span class="objektnummer value">' . $this->verwaltung_techn["objektnr_extern"] . '</span></li>';
                endif;
                ?>
            </ul>
        </div><!-- ende Eckdaten -->
        <?php
        return ob_get_clean();
    }

    /**
     * Taxonomie und Anbieter Details Panel
     *
     * @return string
     */

    public
    function tax_anbieter_panel() {
        ob_start();
        ?>

        <div class="taxdetails">

            <table class="table">
                <tr>
                    <td class="objekt-nr"><?= __( 'Objekt / Online - Nr.', WPI_PLUGIN_NAME ); ?></td>
                    <td class="objekt-nr-value"><?php echo $this->verwaltung_techn["objektnr_extern"] ?></td>
                </tr>
                <tr>
                    <td><?= __( 'Vermarktung', WPI_PLUGIN_NAME ); ?></td>
                    <td><?php echo $this->vermarktung ?></td>
                </tr>
                <tr>
                    <td><?= __( 'Objektart', WPI_PLUGIN_NAME ); ?></td>
                    <td><?php echo $this->objekttyp ?></td>
                </tr>
            </table>

        </div><!-- .objektdetails -->

        <?php
        return ob_get_clean();
    }

    /**
     * Preise Panel
     *
     * @param table , list, div
     *
     * @return string
     */

    public function preis_panel( $element = 'table' ) {
        ob_start();

        if ( ! empty( $this->preis ) && ! $this->is_price_hidden() ):
            $preis = array_filter( $this->help_handle_unit( $this->preis ) );
            switch ( $element ):
                case 'list':
                    ?>
                    <div class="preise list">
                        <?php
                        // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                        foreach ( array_filter( $preis ) as $key => $wert ) {
                            echo '<ul>';
                            echo '<li class="list-value">' . $wert . '</li>';
                            echo '<li class="list-label">' . $key . '</li>';
                            echo '</ul>';

                        }
                        ?>
                    </div>
                    <!-- Div .Preise-->

                    <?php
                    break;

                case 'div';
                    ?>
                    <div class="preise div">
                        <?php
                        // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                        foreach ( array_filter( $preis ) as $key => $wert ) {
                            echo '<div>';
                            echo '<div class="div-value">' . $wert . '</div>';
                            echo '<div class="div-label">' . $key . '</div>';
                            echo '</div>';

                        }
                        ?>
                    </div>
                    <!-- Div .Preise-->

                    <?php
                    break;

                default:
                    ?>
                    <div class="preise">
                        <table class="table">
                            <?php
                            // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                            foreach ( array_filter( $preis ) as $key => $wert ) {
                                echo '<tr>';

                                echo '<td class="table-label">' . $key . '</td>';
                                echo '<td class="table-value">' . $wert . '</td>';

                                echo '</tr>';

                            }
                            ?>
                        </table>
                    </div>
                    <!-- Div .Preise-->
                    <?php
                    break;
            endswitch;
        endif;

        return ob_get_clean();
    }

    /**
     * Flächen Panel
     *
     * @param table , list, div
     *
     * @return string
     */

    public
    function flaechen_panel(
        $element = 'table'
    ) {
        ob_start();

        if ( ! empty( $this->flaeche ) ):
            $flaechen = array_filter( $this->help_handle_unit( $this->flaeche ) );
            ?>
            <div class="flaechen">
                <?php
                switch ( $element ):
                    case 'list':

                        // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                        foreach ( $flaechen as $fl_key => $fl_wert ) {
                            echo '<ul>';
                            echo '<li class="list-value">' . $fl_wert . '</li>';
                            echo '<li class="list-label">' . $fl_key . '</li>';
                            echo '</ul>';
                        }

                        break;

                    case 'div':

                        // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                        foreach ( $flaechen as $fl_key => $fl_wert ) {
                            echo '<div>';
                            echo '<div class="div-value">' . $fl_wert . '</div>';
                            echo '<div class="div-label">' . $fl_key . '</div>';
                            echo '</div>';
                        }

                        break;
                    default:
                        ?>
                        <table class="table">
                            <?php
                            // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                            foreach ( $flaechen as $fl_key => $fl_wert ) {
                                echo '<tr>';
                                echo '<td class="table-label">' . $fl_key . '</td>';
                                echo '<td class="table-value">' . $fl_wert . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                        <?php break; ?>
                    <?php endswitch; ?>
            </div>
            <!-- Div .Flaechen -->
        <?php endif;

        return ob_get_clean();
    }

    public
    function hardfacts_panel() {
        ob_start();
        if ( $this->pro ):
            if ( ! empty( $this->hardfacts ) ):
                $hardfacts = $this->help_handle_unit( $this->hardfacts );

                foreach ( array_filter( $hardfacts ) as $key => $wert ) {
                    echo '<div class="hardfacts">';
                    echo '<div class="hardfacts-value">' . $wert . '</div>';
                    echo '<div class="hardfacts-label">' . $key . ' </div>';
                    echo '</div>';
                }
            endif;
        endif;

        return ob_get_clean();
    }

    /**
     * Ausstattungs Panel
     *
     * @return string
     */

    public
    function ausstattungs_panel() {
        ob_start();

        if ( ! empty( $this->ausstattung ) ): ?>
            <div class="ausstattung">
                <table class="table">
                    <?php
                    $ausstatt = $this->help_handle_array( $this->ausstattung, 'ausstattung' );
                    // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                    foreach ( array_filter( $ausstatt ) as $aus_key => $aus_wert ) {
                        echo '<tr>';
                        echo '<td>' . ucfirst( $aus_key ) . '</td>';
                        echo '<td>' . $aus_wert . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            <!-- Div .Ausstattung -->
        <?php endif;

        return ob_get_clean();
    }

    /**
     * Zustand Panel
     *
     * @return string
     */

    public
    function zustands_panel() {
        ob_start();

        $zustand = $this->help_handle_array( $this->zustand_angaben, 'zustand' );

        if ( ! empty( $zustand ) ): ?>
            <div class="zustand">

                <table class="table"><?php
                    if ( array_key_exists( 'baujahr', $zustand ) ):
                        echo '<tr><td>' . __( "Baujahr", WPI_PLUGIN_NAME ) . '</td>';
                        echo "<td>" . $zustand['baujahr'] . "</td></tr>";
                    endif;
                    if ( array_key_exists( 'zustand', $zustand ) ):
                        echo '<tr><td>' . __( "Zustand", WPI_PLUGIN_NAME ) . '</td>';
                        echo "<td>" . $zustand['zustand'] . "</td></tr>";
                    endif;
                    if ( array_key_exists( 'letztemodernisierung', $zustand ) ):
                        echo '<tr><td>' . __( "Letzte Modernisierung", WPI_PLUGIN_NAME ) . '</td>';
                        echo "<td>" . $zustand['letztemodernisierung'] . "</td></tr>";
                    endif;
                    ?>
                </table>

            </div>
            <!-- Div .Zustand -->
        <?php endif;

        return ob_get_clean();
    }

    /**
     * Energiepass Panel
     *
     * @return string
     */

    public
    function energiepass() {
        if ( ! $this->pro ) {
            echo $this->options['wpi_single_epass']['nicht_vorhanden'];

            return;
        }

        ob_start();

        $zustand = $this->help_handle_array( $this->zustand_angaben, 'zustand' );

        if ( array_key_exists( 'energiepass', $zustand ) ):
            echo '<ul class="list-unstyled energiewerte">';
            if ( is_array( $zustand['energiepass'] ) ):
                foreach ( $zustand['energiepass'] as $en_key => $en_value ) {
                    empty( $en_value ) ? $en_value = 'n.A.' : $en_value = $en_value;
                    echo '<li class="li-label">';
                    echo ucfirst( $en_key );
                    echo '</li>';
                    echo '<li class="li-value">' . $en_value . '</li>';
                }
            else:
                echo '<li>' . $zustand['energiepass'] . '</li>';
            endif;
            echo '</ul>';
        endif;

        return ob_get_clean();
    }

    /**
     * Content Panel
     *
     * @return string
     */

    public
    function wpim_post_content() {
        ob_start();
        ?>

        <div id="beschreibung"><?php echo $this->post->post_content; ?></div>
        <div class="custom-html custom-beschreibung-div">
            <?php echo $this->get_custom_html( 'beschreibung' ); ?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * Post Excerpt
     *
     * @return string
     */

    public
    function wpim_post_excerpt() {
        ob_start();
        ?>
        <div id="excerpt"><?php echo $this->post->post_excerpt; ?></div>
        <?php
        return ob_get_clean();
    }

    /**
     * Post Title
     *
     * @return string
     */

    public
    function wpim_post_title() {
        ob_start();

        echo $this->post->post_title;

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function single_title_container() {
        ob_start();
        ?>
        <div class="custom-html custom-before-title"><?php echo $this->get_custom_html( 'before_title' ); ?></div>
        <?php echo $this->get_printing_icon(); ?>
        <div class="main-title"><h2><?php echo $this->wpim_post_title(); ?></h2></div>
        <div class="custom-html custom-after-title"><?php echo $this->get_custom_html( 'after_title' ); ?></div>
        <?php
        return ob_get_clean();
    }

    /**
     * Objekt-ID
     *
     * @return string
     */
    public function get_objekt_id() {
        return $this->verwaltung_techn['objektnr_extern'];
    }

    public function get_only_price() {

        $preismeta = new ImmoMetaClass( get_the_ID(), 'preise' );
        $preise    = $this->help_handle_unit( $preismeta->get() );

        if ( isset( $preise['kaufpreis'] ) ):
            return $preise['kaufpreis'];
        elseif ( isset( $preise['kaltmiete'] ) ):
            return $preise['kaltmiete'];
        elseif ( isset( $preise['warmmiete'] ) ):
            return $preise['warmmiete'];
        endif;
    }

    /**
     * Ortsanagben
     *
     * @return array
     */
    public function get_ort() {

        @$ort[0] = $this->geodaten['plz'];
        @$ort[1] = $this->geodaten['ort'];
        @$ort[2] = $this->geodaten['strasse'];

        return $ort;
    }

    public function get_image_thumbnails() {
        ob_start();
        $i = 0;
        echo '<ol>';

        foreach ( $this->bilder as $bild ) {
            foreach ( $bild as $alt => $src ) {
                ?>
                <li data-target="#image-carousel" data-slide-to="<?php echo $i; ?>" class="thumbnail">
                    <img src="<?php echo $this->uploadUrl . $src; ?>" alt="<?php echo $alt ?>"/>
                </li>
                <?php
                $i ++;
            }
        }
        echo '</ol>';

        return ob_get_clean();
    }

    /**
     * Freitext
     *
     * @param name
     *
     * @return string
     */

    public
    function get_freitext(
        $name
    ) {
        ob_start();

        if ( ! empty( $this->freitexte[ $name ] ) ):
            echo nl2br( $this->freitexte[ $name ] );
        endif;

        return ob_get_clean();
    }

    /**
     * Dokumente falls welche vorhanden
     *
     * @return string
     */

    public
    function get_documents() {
        ob_start();

        if ( @$this->anhang['dokumente'] > 0 ): ?>
            <div class="dokumente">

            <ul class="list-unstyled"><?php
                for ( $i = 0; $i < count( $this->anhang['dokumente'] ); $i ++ ) {
                    foreach ( $this->anhang['dokumente'][ $i ] as $name => $link ) {
                        $offset = 1;
                        $ext    = '<span class="glyphicon glyphicon-new-window"></span>';
                        if ( $name != '' ):
                            $name = $name;
                        else:
                            $name = __( 'Dokument', WPI_PLUGIN_NAME ) . $offset;
                            $offset ++;
                        endif;
                        echo '<li><a target="_blank" href="' . $this->uploadUrl . $link . '">' . $ext . ' ' . $name . '</a></li>';
                    }
                }
                ?>
            </ul>
            </div><?php
        else:
            echo '<p>' . __( "Keine Dokumente vorhanden", WPI_PLUGIN_NAME ) . '</p>';
            ?>
        <?php endif; ?>
        <div class="custom-html custom-doku-div">
            <?php echo $this->get_custom_html( 'dokumente' ); ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Firmenangaben / Anbieter
     *
     * @return string
     */

    public
    function get_firma() {
        ob_start();

        if ( ! empty( $this->firma ) ):
            ?>
            <div id="firma"><?php

            // Abfrage wenn Firma ein String ist
            if ( ! is_array( $this->firma ) ) {
                echo $this->firma;
            } else {
                $count = 1;
                echo '<ul class="list-unstyled">';
                foreach ( $this->firma as $value ) {
                    echo '<li id="li-' . $count . '">' . $value . '</li>';
                    $count ++;
                }
                echo '</ul>';
            }
            ?>

            </div><?php
        endif;

        return ob_get_clean();
    }

    /**
     * Kontaktperson / Anbieter
     *
     * @return string
     */

    public
    function get_kontakt() {
        ob_start();

        if ( ! empty( $this->contact_fields ) ):

            ?>
            <div id="ansprechpartner">

            <?php
            if ( $this->pro ):
                $kontakt = $this->help_handle_array( $this->contact_fields, 'kontakt' );
            else:
                $kontakt = $this->help_handle_array( $this->kontaktperson, 'kontakt' );
            endif;
            ?>
            <dl class="dl-horizontal"><?php
                foreach ( $kontakt as $bez => $value ) {
                    echo '<dt>' . ucfirst( $bez ) . '</dt>';
                    echo '<dd>' . $value . '</dd>';
                }
                ?>

            </dl>

            </div><?php
        endif;

        return ob_get_clean();
    }

    /**
     * Custom HTML Content
     *
     * @param Place to Inject HTML
     *
     * @return string
     */

    public
    function get_custom_html(
        $placement
    ) {
        ob_start();

        if ( '' != $this->html_inject && $this->html_inject === $placement ):
            echo do_shortcode( $this->html );
        endif;

        return ob_get_clean();
    }

    public function get_printing_icon() {
        return '<div class="printing">
			<a onclick="window.print()" href="javascript:void(0);" title="Drucken"><i class="glyphicon glyphicon-print"></i></a>
		</div>';
    }

    /**
     * @return string|FlexSliderClass
     */
    public function get_active_slider() {
        $args = array();

        $args['sold_tag'] = $this->single_sold_container( $this->meta );

        if ( isset( $this->options['wpi_slider']['lightbox_in'] ) ):
            $args['lightbox'] = 1;
        endif;

        if ( ! $this->pro || $this->options['wpi_slider']['active_slider'] === 'bootstrap' ):
            return $this->imageslider();
        else:
            $slider_mode = $this->options['wpi_slider']['active_slider'];

            return new FlexSliderClass( $slider_mode, $this->bilder, $args );
        endif;
    }

    /**
     * @param $link
     * @param $str
     *
     * @return string
     */
    private function is_lightbox( $link, $str ) {
        if ( isset( $this->options['wpi_slider']['lightbox_in'] ) ):
            new FancyboxClass();

            return '<a href="' . $link . ' " data-fancybox="gallary">' . $str . '</a>';
        else:
            return $str;
        endif;
    }

    /**
     * Artikel Navigation
     *
     * @return string
     */

    public
    function article_navigation() {
        ob_start();

        $link_to_immo  = get_post_type_archive_link( "wpi_immobilie" );
        $button_middle = '<a href="' . $link_to_immo . ' ">';
        $button_middle .= __( "Immobilien-Übersicht", WPI_PLUGIN_NAME );
        $button_middle .= '</a>';

        ?>
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
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Seiten Schnellnavigation
     *
     * @return string
     */

    public
    function smart_navigation() {
        ob_start();
        ?>
        <nav id="smart-navigation" class="navbar navbar-default alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="container-fluid">
                <!-- Titel und Schalter werden für eine bessere mobile Ansicht zusammengefasst -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#smart-navi" aria-expanded="false">
                        <span class="sr-only">Navigation ein-/ausblenden</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Alle Navigationslinks, Formulare und anderer Inhalt werden hier zusammengefasst und können dann ein- und ausgeblendet werden -->
                <div class="collapse navbar-collapse" id="smart-navi">
                    <ul class="nav navbar-nav">
                        <?php
                        foreach ( $this->smart_nav as $navItem ) :
                            if ( ! empty( $navItem['beschreibung'] ) ): ?>
                                <li><a title="<?= $navItem['title']; ?>" href="<?= $navItem['link']; ?>">
                                        <?= $navItem['beschreibung']; ?> <span
                                                class="visible-xs"><?= $navItem['title']; ?></span>
                                    </a>
                                </li>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li><a role="button" href="#" data-toggle="modal" data-target="#searchModal">
                                <i class="fa fa-search" aria-hidden="true"></i></a></li>
                        <span class="visible-xs">Suche</span>
                    </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <?php
        return ob_get_clean();
    }

    /**
     * Google Maps Karte
     *
     * @return string
     */

    public
    function get_map() {
        if ( ! $this->pro ) {
            return 'In der Free-Version nicht verfügbar';
        }
        ob_start();
        ! empty( $this->geodaten['strasse'] ) ? $strasse = $this->geodaten['strasse'] : $strasse = '';
        ! empty( $this->geodaten['ort'] ) ? $ort = $this->geodaten['ort'] : $ort = '';
        ?>
        <iframe
                width="100%"
                height="auto"
                frameborder="0" style="border:0"
                src="https://www.google.com/maps/embed/v1/search?key=AIzaSyDAHVKFbW-cflVB7Ve212yJBLAeoKXJLJw
    &q=<?= $strasse; ?>,<?= $ort; ?>" allowfullscreen>
        </iframe>
        <?php
        return ob_get_clean();
    }

    public function single_sold_container( $meta ) {
        ob_start();
        //$preise  = unserialize( $meta[ 'preise' ][ 0 ] );
        $preise = ( new ImmoMetaClass( get_the_ID(), 'preise' ) )->get();
        ?>
        <div>
            <?php if ( isset( $meta['topimmo'] ) && $this->options['wpi_show_top_immo'] == 'true' ): ?>
                <div class="topimmo">
                    <img src="<?php echo $this->options['wpi_top_immo_source']; ?>"/>
                </div>
            <?php endif; ?>
            <?php if ( isset( $meta['sold'] ) && $this->options['wpi_show_sold'] == 'true' ): ?>
                <div class="sold-text">
                    <?php if ( array_key_exists( 'kaufpreis', $preise ) ): // Wenn Verkauf?>
                        <p><?php echo $this->options['wpi_sold_text']; ?></p>
                    <?php endif; ?>
                    <?php if ( array_key_exists( 'kaltmiete', $preise ) ): ?>
                        <p><?php echo $this->options['wpi_rented_text']; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ( isset( $meta['reserved'] ) && $this->options['wpi_show_reserved'] == 'true' ): ?>
                <div class="reserved-text">
                    <p><?php echo $this->options['wpi_reserved_text']; ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public
    function search_modal_html() {
        ob_start();
        ?>
        <!-- Modal Search -->
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Volltextsuche:</h3>
                        <p><em>Suche nach einem Kreis, Stadt, Ort etc.</em></p>
                        <?php echo $this->view_searchfield_wpmi(); ?><br/><br/>
                        <h3>Umkreissuche:</h3>
                        <p><em>Suche nach einer Postleitzahl und Entfernung.</em></p>
                        <?php echo do_shortcode( '[umkreissuche]' ); ?><br/><br/>
                        <h3>Such-Filter</h3>
                        <p><em>Suche nach Ort und Immobilienart</em></p>
                        <?php echo do_shortcode( '[search_filter_form]' ); ?>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php
    }


// Helper Functions

    /**
     * Function check if the price should be hidden
     *
     * @return bool
     */
    public function is_price_hidden() {

        $meta = get_post_meta( get_the_ID() );

        // IF Meta['sold'] is set $sold
        ! empty( $meta['sold'] ) ? $sold = $meta['sold'][0] : $sold = null;

        // IF Hide_Price on Settings $hide TRUE else FALSE
        $hide = null;
        @$this->options['wpi_list_options']['wpi_immogroup_sold_hide_price'] == 'on' ? $hide = true : null;
        // check if the price should be hidden
        if ( null !== $sold and true == $hide ):
            return true;
        endif;

        return false;
    }


    /**
     * @param $arg  = array to handle
     * @param $arg2 = argument to handle
     *
     * @return array rendered
     */

    public
    function help_handle_array(
        $arg, $arg2
    ) {
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
                if ( @$array[ $key1 ] != null && $key1 != 'user_defined_simplefield' ) {
                    if ( ! is_array( @$array[ $key1 ] ) ) {
                        $key1 = str_replace( '_', '-', $key1 );
                        @$new_array[ $key1 ] = is_string( $value1 ) ? ucfirst( strtolower( $value1 ) ) : '';
                        //@$new_array[ $key1 ] = is_array( $value1 ) ? implode(', ', $value1) : '';
                    } else {
                        foreach ( $array[ $key1 ] as $key2 => $value2 ) {
                            unset( $values );
                            if ( is_array( $array[ $key1 ][ $key2 ] ) ):
                                foreach ( $array[ $key1 ][ $key2 ] as $key3 => $value3 ) {
                                    if ( is_array( $array[ $key1 ][ $key2 ][$key3] ) ):
                                        foreach ($array[ $key1 ][ $key2 ][$key3] as $key4 => $value4) {
                                            $values[] = $this->help_handle_string_to_umlaute( ucfirst( strtolower( $key4 ) ) );
                                        }
                                    else:
                                        $values[] = $this->help_handle_string_to_umlaute( ucfirst( strtolower( $key3 ) ) );
                                    endif;
                                }
                            endif;
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
                'energieverbrauchkennwert' => __( 'Energiekennwert' ),
                'mitwarmwasser'            => __( 'Mit Warmwasser' ),
                'endenergiebedarf'         => __( 'Energiebedarf' ),
                'primaerenergietraeger'    => __( 'Energieträger' ),
                'stromwert'                => __( 'Stromwert' ),
                'waermewert'               => __( 'Wärmewert' ),
                'wertklasse'               => __( 'Energieeffizienzklasse' ),
                'baujahr'                  => __( 'Baujahr' ),
                'ausstelldatum'            => __( 'Ausstelldatum' ),
                'jahrgang'                 => __( 'Jahrgang des Energieausweises' ),
                'gebaeudeart'              => __( 'Gebäudeart' ),
                'epasstext'                => __( 'Hinweistext' )
            );

            foreach ( $array as $key1 => $value1 ) {

                if ( ! is_array( $array[ $key1 ] ) ) {
                    $new_array[ $key1 ] = $value1;
                } else {
                    foreach ( $array[ $key1 ] as $key2 => $value2 ) {
// Wenn Attribute vorhanden
                        if ( is_array( $array[ $key1 ] ) && array_key_exists( '@attributes', $array[ $key1 ] ) ) {
                            foreach ( $array[ $key1 ]['@attributes'] as $key3 => $value3 ) {
                                unset( $values );
                                $values[] = $this->help_handle_string_to_umlaute( $value3 );
                            }
                            $new_array[ $key1 ] = implode( ', ', $values );
                        } else {
                            @$new_array[ $key1 ][ $key2 ] = $this->help_handle_string_to_umlaute( $value2 );
                        }
                    }
                }
            }
            //zeigen( $new_array[ 'energiepass' ][ 'jahrgang' ] );
            /**
             * Anpassung der Energieausweisdaten...
             **/
// Value für "epart"
            $einheit = ' kWh/(m²*a)';
            switch ( @$new_array['energiepass']['epart'] ) {
                case 'Verbrauch':
                    $new_array['energiepass']['epart'] = __( 'Verbrauchsausweis' );
                    if ( @$new_array['energiepass']['energieverbrauchkennwert'] > 0 ) {
                        $new_array['energiepass']['energieverbrauchkennwert'] = $new_array['energiepass']['energieverbrauchkennwert'] . $einheit;
                    }
                    if ( @$new_array['energiepass']['mitwarmwasser'] === 'True' ) {
                        $new_array['energiepass']['mitwarmwasser'] = __( 'Energieverbrauch für Warmwasser enthalten' );
                    } elseif ( @$new_array['energiepass']['mitwarmwasser'] === 'False' ) {
                        $new_array['energiepass']['mitwarmwasser'] = __( 'Energieverbrauch für Warmwasser nicht enthalten' );
                    } else {
                        unset( $new_array['energiepass']['mitwarmwasser'] );
                    }
                    break;
                case 'Bedarf':
                    $new_array['energiepass']['epart'] = __( 'Bedarfsausweis' );
                    if ( @$new_array['energiepass']['endenergiebedarf'] > 0 ) {
                        @$new_array['energiepass']['endenergiebedarf'] = $new_array['energiepass']['endenergiebedarf'] . $einheit;
                    }
                    if ( @$new_array['energiepass']['energieverbrauchkennwert'] > 0 ) {
                        $new_array['energiepass']['energieverbrauchkennwert'] = $new_array['energiepass']['energieverbrauchkennwert'] . $einheit;
                    }
                    if ( @$new_array['energiepass']['mitwarmwasser'] === 'True' ) {
                        $new_array['energiepass']['mitwarmwasser'] = __( 'Energieverbrauch für Warmwasser enthalten' );
                    }
                    break;
            }
// Value für "ART"
            switch ( @$new_array['energiepass']['art'] ) {
                case 'Verbrauch':
                    $new_array['energiepass']['art'] = __( 'Verbrauchsausweis' );
                    if ( @$new_array['energiepass']['energieverbrauchkennwert'] > 0 ) {
                        $new_array['energiepass']['energieverbrauchkennwert'] = $new_array['energiepass']['energieverbrauchkennwert'] . $einheit;
                    }
                    if ( @$new_array['energiepass']['mitwarmwasser'] === 'True' ) {
                        $new_array['energiepass']['mitwarmwasser'] = __( 'Energieverbrauch für Warmwasser enthalten' );
                    } elseif ( @$new_array['energiepass']['mitwarmwasser'] === 'False' ) {
                        $new_array['energiepass']['mitwarmwasser'] = __( 'Energieverbrauch für Warmwasser nicht enthalten' );
                    } else {
                        unset( $new_array['energiepass']['mitwarmwasser'] );
                    }
                    break;
                case 'Bedarf':
                    $new_array['energiepass']['art'] = __( 'Bedarfsausweis' );
                    if ( @$new_array['energiepass']['endenergiebedarf'] > 0 ) {
                        @$new_array['energiepass']['endenergiebedarf'] = $new_array['energiepass']['endenergiebedarf'] . $einheit;
                    }
                    /*if (@$new_array['energiepass']['mitwarmwasser'] > 0 || @$new_array['energiepass']['mitwarmwasser'] === 'true') {
                    $new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
                    }*/
                    break;
            }
// Value für Gültig
            if ( @$new_array['energiepass']['gueltig_bis'] ) {
                $gueltig                                 = $new_array['energiepass']['gueltig_bis'];
                $format                                  = 'd.m.Y';
                $datum                                   = strtotime( $gueltig );
                $new_array['energiepass']['gueltig_bis'] = date( $format, $datum );
            }

// Value für Ausstelldatum
            if ( @$new_array['energiepass']['ausstelldatum'] ) {
                $ausstell                                  = $new_array['energiepass']['ausstelldatum'];
                $format                                    = 'd.m.Y';
                $datum                                     = strtotime( $ausstell );
                $new_array['energiepass']['ausstelldatum'] = date( $format, $datum );

            }
// Value für "gebaeudeart"
            switch ( @$new_array['energiepass']['gebaeudeart'] ) {
                case 'Wohn':
                    $new_array['energiepass']['gebaeudeart'] = __( 'Wohngebäude' );
                    break;
                case 'Nichtwohn':
                    $new_array['energiepass']['gebaeudeart'] = __( 'Nichtwohngebäude' );
                    break;
            }

            // Options-Texte
            $epass_texte = $this->options['wpi_single_epass'];
            // Wenn kein Energiepass übergeben wurde
            if ( ! @$new_array['energiepass'] || @$new_array['energiepass']['jahrgang'] === 'Ohne' ) {
                $new_array['energiepass'] = $epass_texte['nicht_vorhanden'];
            }
            // Wenn Energieausweis nicht erforderlich z.B. bei Denkmalschutz
            if ( @$new_array['enrgiepass']['jahrgang'] === 'Nicht nötig' ) {
                $new_array['energiepass']['jahrgang'] = $epass_texte['nicht_benoetigt'];
                //zeigen( $new_array[ 'energiepass' ][ 'jahrgang' ] );
            } // Bei übergebenem Energieausweis anpassen der Values


// Austauschen der Schlüssel für Energieausweisdaten
            if ( is_array( @$new_array['energiepass'] ) ) {
                foreach ( $epart_keys as $epart_key => $epart_value ) {
                    if ( array_key_exists( $epart_key, $new_array['energiepass'] ) ) {
                        $new_array['energiepass'][ $epart_value ] = $new_array['energiepass'][ $epart_key ];
                        unset( $new_array['energiepass'][ $epart_key ] );
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

            if ( isset( $array['anhang'] ) ):
                if ( ! is_array_assoc( $array['anhang'] ) ):
// Wenn mehrere Anhänge als Array verfügbar
                    foreach ( $array['anhang'] as $key => $item2 ) {

// Vorauswahl Anhangtitel, ist einer vorhanden anwenden sonst String 'bild' setzen;
                        empty( $array['anhang'][ $key ]['anhangtitel'] ) ? $anhangtitel = 'bild' : $anhangtitel = $array['anhang'][ $key ]['anhangtitel'];


                        foreach ( $bildformate as $formvalue ) {
// Bilder
                            if ( ( @$array['anhang'][ $key ]['format'] ) && strtolower( @$array['anhang'][ $key ]['format'] ) == $formvalue ) {
                                @$new_array['bilder'][] = array(
                                    $anhangtitel =>
                                        strtolower( $array['anhang'][ $key ]['daten']['pfad'] )
                                );
                            }
                        }

// Dokumente
                        if ( ( @$array['anhang'][ $key ]['format'] ) && strtolower( @$array['anhang'][ $key ]['format'] ) == $dokumentformate ) {
                            @$new_array['dokumente'][] = array(
                                $array['anhang'][ $key ]['anhangtitel'] =>
                                    strtolower( $array['anhang'][ $key ]['daten']['pfad'] )
                            );
                        }

                    }
                else:
// Wenn nur ein Anhang
                    foreach ( $bildformate as $formvalue ) {
// Bilder
                        if ( ( @$array['anhang']['format'] ) && strtolower( @$array['anhang']['format'] ) === $formvalue ) {
                            @$new_array['bilder'][] = array(
                                $array['OriginalDateiname'] =>
                                    strtolower( $array['anhang']['daten']['pfad'] )
                            );
                        }
// Dokumente
                        if ( ( @$array['anhang']['format'] ) && strtolower( @$array['anhang']['format'] ) === $dokumentformate ) {
                            @$new_array['dokumente'][] = array(
                                $array['anhang']['anhangtitel'] =>
                                    strtolower( $array['anhang']['daten']['pfad'] )
                            );
                        }
                    }

                endif;
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


    /**
     * Help_handle_unit function
     * Fügt die Einheit wie € und m² zu den Values in gemischten Arrays
     *
     * @param $arg_array
     *
     * @return array rendered
     */

    public
    function help_handle_unit(
        $arg_array
    ) {
        $price_units_array = array(
            'kaufpreis',
            'kaltmiete',
            'warmmiete',
            'nebenkosten',
            'kaution',
            'provision',
            'kaufpreis_pro_qm',
            'kaufpreis pro m²',
            'mietpreis_pro_qm',
            'mietpreis pro m²',
            'jahresnettomiete',
            'mieteinnahmen_ist'
        );
        $area_units_array  = array(
            'wohnfläche',
            'nutzfläche',
            'grundstücksfläche',
            'grundstückfläche',
            'lagerfläche',
            'bürofläche',
            'kellerfläche',
            'wohnflaeche',
            'grundstuecksflaeche',
            'grundstueckflaeche',
            'nutzflaeche',
        );
        @$price_unit = $this->preise['waehrung']['@attributes']['iso_waehrung'];
        $area_unit    = 'm²';
        $return_array = array();
        foreach ( $arg_array as $key => $value ) {
            if ( is_numeric( $value ) ):
                if ( in_array( strtolower( $key ), $price_units_array ) ):
                    $return_array[ $key ] = number_format( $value, 2, ",", "." ) . ' ' . $this->convert_unit_science( $price_unit );
                elseif ( in_array( strtolower( $key ), $area_units_array ) ):
                    $return_array[ $key ] = number_format( $value, 1, ",", "." ) . ' ' . $area_unit;
                else:
                    $return_array[ $key ] = number_format( $value, 0, ",", "." );
                endif;
            else:
                $return_array[ $key ] = $value;
                // Tax-Fixing
                if ( strtolower( $key ) == 'provision' && $value != 'k.A.' ):
                    $return_array [ $key ] = $value . $this->help_handle_tax();
                endif;
            endif;

        }

        return $return_array;
    }

    public function help_handle_tax() {
        isset( $this->preise['zzg_mehrwertsteuer'] ) ? $tax = $this->preise['zzg_mehrwertsteuer'] : $tax = '';

        if ( $tax == 'true' || $tax == '1' ):
            return __( '<span class="inkl-tax"> zzgl. MwSt. </span>', WPI_PLUGIN_NAME );
        elseif ( $tax == 'false' || $tax == '0' ):
            return __( '<span class="inkl-tax"> inkl. MwSt. </span>', WPI_PLUGIN_NAME );
        else:
            return;
        endif;
    }

    public function help_handle_string_to_umlaute( $string ) {
        $search  = array( 'ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', '_' );
        $replace = array( 'ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', ' ' );

        return str_replace( $search, $replace, $string );
    }

    /**
     * convert_unit_scince
     * konvertiert die Währungswörter zu Zeichen
     *
     * @param string
     *
     * @return string converted
     */
    public
    function convert_unit_science(
        $arg
    ) {
        switch ( $arg ):
            case 'EUR':
                return '€';
                break;
            default:
                return $arg;
                break;
        endswitch;
    }

    /**
     * @param $array
     *
     * @return bool
     * Funktion prüft ob ein Array assoziativ ist
     */
    private
    function is_array_assoc(
        $array
    ) {
        foreach ( $array as $key => $value ) {
            if ( is_integer( $key ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $arrayToChange
     * @param $arrayTexte
     *
     * @return $new_array
     * Funktion tauscht die Texte der ArrayKeys gegen andere Texte
     */
    private
    function changeKeyNames(
        $arrayToChange, $arrayTexte
    ) {
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

}
