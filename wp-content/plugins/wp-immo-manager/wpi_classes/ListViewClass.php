<?php
/**
 * User: Media-Store.net
 * Date: 19.09.2016
 * Time: 22:34
 */

namespace wpi\wpi_classes;

class ListViewClass {

    public function __construct() {

        // Laden der Optinen aus DB
        $optionsClass         = new WpOptionsClass();
        $this -> admin        = new AdminClass();
        $this -> options      = $optionsClass -> wpi_get_options();
        $this -> upload_url   = $this -> options[ 'wpi_upload_url' ];
        $this -> list_options = $this -> options[ 'wpi_list_options' ];
        // Info über Versionsstaus
        $admin       = new AdminClass;
        $this -> pro = $admin ::versionStatus();
    }


    public function wrapper_template() {
        ob_start();
        $single_view = new SingleViewClass;

        ?>
        <!-- Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->

        <div id="wpi-primary" class=" wpi container-fluid">
            <div class="row">
                <?php
                if ( $this -> pro != false ) :
                    switch ( $this -> list_options[ 'wpi_list_search' ] ) {
                        case 'searchfilter' :
                            echo do_shortcode( '[search_filter_form]' );
                            break;
                        case 'searchbar' :
                            echo $single_view -> view_searchfield_wpmi();
                            break;
                        default :
                            '';
                            break;
                    }
                endif;
                ?>

                <div class="clearfix"></div>
                <div class="content-div">
                    <?php echo $this -> content_template( $meta = '' ); ?>
                </div>
            </div><!-- row -->
        </div><!-- wpi-primary -->
        <!-- Ende Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->
        <?php
        return ob_get_clean();
    }

    public function content_template() {
        ob_start();

        /**
         * Anzeige einer Sidebar wenn in den Einstellungen aktiviert
         */
        if ( $this -> list_options[ 'wpi_list_sidebar' ] == 'true' ) :
            $content_row = 'col-xs-12 col-md-9';
            $sidebar     = $this -> list_options[ 'wpi_list_sidebar_name' ];
            //$sidebar     = substr( strstr( $sidebar, '-' ), 1 );
            // zeige sidebar
            echo '<div class="aside col-md-3">';
            dynamic_sidebar( $sidebar );
            echo '</div>';
        else :
            $content_row = 'col-xs-12 col-md-12';
        endif;
        ?>
        <!-- Ende Aside -->
        <div id="wpi-main" class="content site-main <?php echo $content_row; ?>" role="main">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'archiv-immobilien' ); ?>>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">

                            <?php

                            global $wp_query;

                            $big = 999999999; // need an unlikely integer

                            $pagination = paginate_links( array(
                                'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                'format'  => '?paged=%#%',
                                'current' => max( 1, get_query_var( 'paged' ) ),
                                'total'   => $wp_query -> max_num_pages,
                                'type'    => 'list',
                            ) );

                            // LOOP
                            if ( $wp_query -> have_posts() ) :

                                while ( $wp_query -> have_posts() ) :

                                    $wp_query -> the_post();
                                    $id   = get_the_ID();
                                    $meta = get_post_meta( get_the_ID() );
                                    $this -> check_immogruppe( $id );
                                    //zeigen( $meta );
                                    switch ( $this -> list_options[ 'wpi_list_view_column' ] ):
                                        case 'column':
                                            echo $this -> list_view_columns( $meta );
                                            break;

                                        case 'thumbnail':
                                            echo $this -> list_view_thumbnail( $meta );
                                            break;

                                        case 'div':
                                            ?>
                                            <div class="content">
                                                <div class="col-md-3">
                                                    <?php echo $this -> list_view_image( $meta ); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <header class="wpi-header">
                                                        <h2>
                                                            <?php echo $this -> new_post_label( $id ); ?>
                                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                        </h2>
                                                    </header>
                                                    <div class="details-panel">
                                                        <?php
                                                        if ( $this -> list_options[ 'wpi_list_excerpt' ] === 'true' ) :
                                                            echo $this -> list_view_excerpt( $meta );
                                                        else :
                                                            echo $this -> list_view_meta( $meta, 'div' );
                                                        endif;
                                                        ?>
                                                    </div>

                                                    <?php
                                                    // Link Button
                                                    echo $this -> list_link_button();
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            break;

                                        case 'two-col-list':
                                            echo $this -> list_view_two_col_list( $meta );
                                            break;

                                        default:
                                            ?>
                                            <div class="content">
                                                <div class="col-xs-12">
                                                    <header class="wpi-header">
                                                        <h2>
                                                            <?php echo $this -> new_post_label( $id ); ?>
                                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                        </h2>
                                                    </header>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php echo $this -> list_view_image( $meta ); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <?php

                                                    if ( $this -> list_options[ 'wpi_list_excerpt' ] === 'true' ) :
                                                        echo $this -> list_view_excerpt( $meta );
                                                    else :
                                                        echo $this -> list_view_meta( $meta );
                                                    endif;
                                                    // Link Button
                                                    echo $this -> list_link_button();
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            break;
                                    endswitch;
                                    ?>

                                <?php

                                endwhile; // end of the loop.

                            endif; // End of IF-Loop

                            wp_reset_postdata();
                            ?>

                        </div><!-- row -->
                    </div><!-- panel-body -->
                </div><!-- panel -->
            </article>

        </div>
        <!-- #main -->

        <div class="clearfix"></div>

        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="text-center">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </nav>

        <?php
        return ob_get_clean();
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function list_view_post( $id, $style = 'table' ) {
        ob_start();
        $meta = get_post_meta( $id );
        $this -> check_immogruppe( $id );
        switch ( $style ):

            case 'column':
                echo $this -> list_view_columns( $meta );
                break;

            case 'thumbnail':
                echo $this -> list_view_thumbnail( $meta );
                break;

            case 'excerpt':
                echo $this -> list_view_excerpt( $meta );
                break;

            case 'div':
                ?>
                <div class="content">
                    <div class="col-md-3">
                        <?php echo $this -> list_view_image( $meta ); ?>
                    </div>
                    <div class="col-md-9">
                        <header class="wpi-header">
                            <h2>
                                <?php echo $this -> new_post_label( $id ); ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                        <div class="details-panel">
                            <?php echo $this -> list_view_meta( $meta, 'div' ); ?>
                        </div>

                        <?php
                        // Link Button
                        echo $this -> list_link_button();
                        ?>
                    </div>
                </div>
                <?php
                break;

            case 'two-col-list':
                echo $this -> list_view_two_col_list( $meta );
                break;

            default:
                ?>
                <div class="content">
                    <div class="col-xs-12">
                        <header class="wpi-header">
                            <h2>
                                <?php echo $this -> new_post_label( $id ); ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                    </div>
                    <div class="col-md-3">
                        <?php echo $this -> list_view_image( $meta ); ?>
                    </div>
                    <div class="col-md-9">
                        <?php

                        if ( $this -> list_options[ 'wpi_list_excerpt' ] === 'true' ) :
                            echo $this -> list_view_excerpt( $meta );
                        else :
                            echo $this -> list_view_meta( $meta );
                        endif;
                        // Link Button
                        echo $this -> list_link_button();
                        ?>
                    </div>
                </div>
                <?php
                break;
        endswitch;

        return ob_get_clean();
    }

    /**
     * @param $meta -> MetdDaten des Posts
     *
     * @return string
     */
    public function list_view_image( $meta = '' ) {
        $single_view = new SingleViewClass;
        $immometa    = ( new ImmoMetaClass( get_the_ID() ) ) -> get();
        $bilder      = $immometa[ 'anhaenge' ];//unserialize( $meta[ 'anhaenge' ][ 0 ] );
        // Auslesen der Kategorien
        $taxonomies = get_the_taxonomies();
        // Objekttypen
        $objekttyp = strstr( $taxonomies[ 'objekttyp' ], ' ' );
        $objekttyp = trim( $objekttyp, '.' );
        // Vermarktungsarten
        $vermarktung = strstr( $taxonomies[ 'vermarktungsart' ], ' ' );
        $vermarktung = trim( $vermarktung, '.' );
        ob_start();

        ?>

        <div>
            <a href="<?php the_permalink(); ?>" class="thumbnail">
                <?php
                //Bilder aus Meta laden
                @$bilder = $single_view -> help_handle_array( $bilder, 'anhang' );
                @$bild = array_values( $bilder[ 'bilder' ][ 0 ] )[ 0 ];
                if ( ! empty( $bild ) ) :
                    $first_image = $bild;
                    ?>
                    <img src="<?php echo $this -> upload_url . $first_image; ?> "/>
                <?php
                else :
                    ?>
                    <img src="<?php echo $this -> options[ 'wpi_img_platzhalter' ]; ?>"/>
                <?php
                endif;
                ?>
            </a>
            <div class="text-center">
                <span class="info-text text-capitalize"><?php echo $objekttyp ?></span>
                <span class="info-text text-capitalize"><?php echo $vermarktung ?></span>
            </div>
            <div class="visible-xs">
                <hr/>
            </div>
        </div>
        <?php echo $this -> list_sold_container( $meta ); ?>

        <?php
        return ob_get_clean();
    }

    /**
     * @param $meta -> MetdDaten des Posts
     *
     * @return string
     */

    public function list_view_thumbnail( $meta ) {
        $bilder = unserialize( $meta[ 'anhaenge' ][ 0 ] );
        // Auslesen der Kategorien
        ob_start();
        $single_view = new SingleViewClass;
        @$bilder = $single_view -> help_handle_array( $bilder, 'anhang' );
        @$bild = array_values( $bilder[ 'bilder' ][ 0 ] )[ 0 ];

        ?>

        <div class="thumbnail thumbnail-style">
            <?php echo $this -> list_sold_container(); ?>
            <?php
            //Bilder aus Meta laden
            if ( ! empty( $bild ) ) :
                ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo $this -> upload_url . $bild; ?> "/>
                </a>
            <?php
            else :
                ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo $this -> options[ 'wpi_img_platzhalter' ]; ?>"/>
                </a>
            <?php
            endif;
            ?>

            <div class="caption">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param $meta -> MetaDaten des Posts
     *
     * @return string
     */
    public function list_view_excerpt( $meta = '' ) {
        ob_start();

        $exc_length = $this -> list_options[ 'wpi_list_excerpt_length' ]; ?>
        <div class="">
            <?php echo wp_trim_words( get_the_excerpt(), $exc_length ); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param $meta -> MetaDaten des Posts
     *
     * @return string
     */
    public function list_view_columns( $meta = '' ) {
        $single_view = new SingleViewClass();
        $immometa    = ( new ImmoMetaClass( get_the_ID() ) ) -> get();

        $id = get_the_ID();
        // Objektdaten als Arrays
        $geodaten = $immometa[ 'geodaten' ];
        $preise   = $single_view -> help_handle_unit( $immometa[ 'preise' ] );
        $flaechen = $single_view -> help_handle_unit( $immometa[ 'flaechen' ] );
        $liste    = $this -> list_details_array( $preise, $flaechen );

        // Setzen der Variable "Preis"
        if ( array_key_exists( 'kaufpreis', $preise ) && ! $this -> is_price_hidden() ):
            $preis = 'Kaufpreis: ' . $preise[ 'kaufpreis' ];
        elseif ( array_key_exists( 'kaltmiete', $preise ) && ! $this -> is_price_hidden() ):
            $preis = 'Kaltmiete: ' . $preise[ 'kaltmiete' ];
        else:
            $preis = '';
        endif;


        // Link zur Single-Seite
        $link = get_permalink();

        ob_start();
        ?>
        <div class="col-sm-6 col-md-4 immo-columns">
            <div class="thumbnail"><?php
                echo $this -> new_post_label( $id );
                if ( ! empty( $preis ) ):
                    echo '<span class="preis">' . $preis . '</span>';
                endif;
                echo $this -> list_view_image( $meta );
                ?>

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
                    <p class="text-center"><a href="<?php echo $link; ?>" class="btn btn-default"
                                              role="button">Ansehen</a>
                    </p>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @param $meta -> MetdDaten des Posts
     *
     * @return string
     */
    public function list_view_meta( $meta = '', $element = 'list' ) {
        $single_view = new SingleViewClass;
        $immometa    = ( new ImmoMetaClass( get_the_ID() ) ) -> get();

        $firma = $immometa[ 'anbieterfirma' ];
        if ( strpos( $firma, ";" ) ) {
            $firma = explode( ';', $firma );
        }
// Objektdaten als Arrays
        $geodaten = $immometa[ 'geodaten' ];//$meta[ 'geodaten' ][ 0 ] );
        $preise   = $single_view -> help_handle_unit( $immometa[ 'preise' ] );
        $flaechen = $single_view -> help_handle_unit( $immometa[ 'flaechen' ] );

        $liste = $this -> list_details_array( $preise, $flaechen );

        ob_start();
        switch ( $element ):
            case 'div':
                ?>
                <div class="div">
                    <div class="ortsangaben row">
                        <div class="col-xs-12">
                            <span class="glyphicon glyphicon-map-marker"></span>
                            <span class="eckdaten_ort">
                                            <?php echo $geodaten[ 'plz' ] . ' ' . $geodaten[ 'ort' ] ?>
                                        </span>
                        </div>
                    </div>
                    <?php
                    // Tabellen-Array ohne leere Werte in die Tabelle schreiben
                    if ( ! empty( $liste ) && array_filter( $liste ) ):
                        array_walk( $liste, 'trim_value' );
                        //zeigen(array_filter($liste));
                        foreach ( array_filter( $liste ) as $key => $wert ) {
                            echo '<div class="hardfacts">';
                            echo '<div class="hardfacts-value">' . $wert . '</div>';
                            echo '<div class="hardfacts-label hidden-xs">' . $key . '</div>';
                            echo '</div>';
                        }
                    endif;

                    ?>
                </div>
                <?php
                break;
            default;
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
                <?php
                break;
        endswitch;

        return ob_get_clean();
    }

    public function list_view_two_col_list( $meta ) {
        if ( ! $this -> pro ):
            return $this -> list_view_thumbnail( $meta );
        endif;;

        ob_start();
        ?>

        <div class="two-col-list-item col-xs-12 col-sm-6">
            <?php echo $this -> list_view_image(); ?>
            <div class="captions-list">
                <div class="title">
                    <h2><?php the_title(); ?></h2>
                </div>
                <div class="price">
                    <?php echo $this -> get_only_price(); ?>
                </div>
                <div class="meta-list">
                    <?php echo $this -> list_view_meta( '', 'div' ) ?>
                </div>
                <?php echo $this -> list_link_button(); ?>
            </div>

        </div>

        <?php
        return ob_get_clean();
    }

    public function list_view_full_image_slider( $meta ) {
        if ( ! $this -> pro ):
            return $this -> list_view_thumbnail( $meta );
        endif;;

        ob_start();
        ?>

        <div class="full-image-slider">

            <?php echo $this -> list_view_image( $meta ); ?>

            <div class="full-image-title">
                <h2><?php the_title(); ?></h2>
            </div>

            <div class="full-image-meta">
                <?php echo $this -> list_view_meta( $meta, 'div' ); ?>
            </div>

        </div>

        <?php
        return ob_get_clean();
    }

    public function list_link_button( $text = '' ) {
        ! empty( $text ) ? $text = $text : $text = __( 'Mehr Details', WPI_PLUGIN_NAME );
        ob_start();
        ?>
        <div class="more pull-right">
            <a href="<?php the_permalink(); ?>">
                <button type="button" class="btn btn-default">
                    <?php echo $text; ?> <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </a>
        </div>
        <!-- more -->

        <?php
        return ob_get_clean();
    }

    public function list_sold_container( $meta = '' ) {
        //empty( $meta ) ? $meta = get_post_meta( get_the_ID() ) : $meta = $meta;
        $meta = ( new ImmoMetaClass( get_the_ID() ) ) -> get();
        ob_start();
        $preise = $meta[ 'preise' ];

        ?>
        <div>
            <?php if ( isset( $meta[ 'topimmo' ] ) && $this -> options[ 'wpi_show_top_immo' ] == 'true' ): ?>
                <div class="topimmo">
                    <img src="<?php echo $this -> options[ 'wpi_top_immo_source' ]; ?>"/>
                </div>
            <?php endif; ?>
            <?php if ( isset( $meta[ 'sold' ] ) && $this -> options[ 'wpi_show_sold' ] == 'true' ): ?>
                <div class="sold-text">
                    <?php if ( array_key_exists( 'kaufpreis', $preise ) ): // Wenn Verkauf?>
                        <p><?php echo $this -> options[ 'wpi_sold_text' ]; ?></p>
                    <?php endif; ?>
                    <?php if ( array_key_exists( 'kaltmiete', $preise ) ): ?>
                        <p><?php echo $this -> options[ 'wpi_rented_text' ]; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ( isset( $meta[ 'reserved' ] ) && $this -> options[ 'wpi_show_reserved' ] == 'true' ): ?>
                <div class="reserved-text">
                    <p><?php echo $this -> options[ 'wpi_reserved_text' ]; ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function list_details_array( $preise, $flaechen ) {

        // Check if the Price is hidden override the $preise-variable
        $this -> is_price_hidden() ? $preise = [] : $preise = $preise;

        @$this -> list_options[ 'wpi_list_detail' ] !== 0 ? $list_details = $this -> list_options[ 'wpi_list_detail' ] : '';
        if ( ! empty( $list_details ) ):
            $liste = array();
            foreach ( $list_details as $listkey => $listvalue ) {
                if ( array_key_exists( $listkey, $preise ) ):
                    $liste[ $listvalue ] = $preise[ $listkey ];
                endif;

                if ( array_key_exists( $listkey, $flaechen ) ):
                    $liste[ $listvalue ] = $flaechen[ $listkey ];
                endif;
            }

            return $liste;
        endif;

        return null;
    }

    /**
     * Function check if the price should be hidden
     *
     * @return bool
     */
    public function is_price_hidden() {

        $meta = get_post_meta( get_the_ID() );

        // IF Meta['sold'] is set $sold
        ! empty( $meta[ 'sold' ] ) ? $sold = $meta[ 'sold' ][ 0 ] : $sold = null;

        // IF Hide_Price on Settings $hide TRUE else FALSE
        $hide = null;
        @$this -> list_options[ 'wpi_immogroup_sold_hide_price' ] == 'on' ? $hide = true : null;
        // check if the price should be hidden
        if ( null !== $sold and true == $hide ):
            return true;
        endif;

        return false;
    }

    public function get_only_price() {

        $single_view = new SingleViewClass();

        return $single_view -> get_only_price();
    }

    /**
     * Check ob eine Immogruppe definiert ist
     * und weist einen Meta-Wert der Immobilie
     * @return string
     */
    public function check_immogruppe( $id ) {
        // Auslesen der Kategorien
        $arg        = array(
            'template'      => __( '%2$l' ),
            'term_template' => '%2$s',
        );
        $taxonomies = get_the_taxonomies( get_post( $id ), $arg );
        @$immogruppe = $taxonomies[ 'immobiliengruppe' ];

        $sold         = get_post_meta( $id, 'sold' );
        $reserved     = get_post_meta( $id, 'reserved' );
        $topimmo      = get_post_meta( $id, 'topimmo' );
        $list_options = get_option( 'wpi_list_options' );
        $zustand      = ( new ImmoMetaClass( $id, 'zustand_angaben' ) ) -> get();
        $status       = @$zustand[ 'verkaufstatus' ][ '@attributes' ][ 'stand' ];

        // für Referenzen
        if ( ! empty( $immogruppe ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_sold' ]
            || $status === 'VERKAUFT' ):
            add_post_meta( $id, 'sold', '1', true );
        elseif ( ! empty( $sold ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_sold' ]
            || $status === 'VERKAUFT' ):
            update_post_meta( $id, 'sold', '1' );
        else:
            delete_post_meta( $id, 'sold' );
        endif;

        // für Reservierte-Objekte
        if ( ! empty( $immogruppe ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_reserved' ]
            || $status === 'RESERVIERT' ):
            add_post_meta( $id, 'reserved', '1', true );
        elseif ( ! empty( $reserved ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_reserved' ]
            || $status === 'RESERVIERT' ):
            update_post_meta( $id, 'reserved', '1' );
        else:
            delete_post_meta( $id, 'reserved' );
        endif;

        // für Top-Objekte
        if ( ! empty( $immogruppe ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_top' ] ):
            add_post_meta( $id, 'topimmo', '1', true );
        elseif ( ! empty( $topimmo ) && trim( $immogruppe ) === $list_options[ 'wpi_immogroup_top' ] ):
            update_post_meta( $id, 'topimmo', '1' );
        else:
            delete_post_meta( $id, 'topimmo' );
        endif;

        return;
    }

    public function new_post_label( $id ) {
        $post         = get_post( $id );
        $current_date = strtotime( date( 'Y-m-d H:m:s' ) );
        $post_time    = strtotime( $post -> post_date );
        $setting      = (int) $this -> options[ 'wpi_list_options' ][ 'wpi_list_view_new_label' ];
        $time_dif     = ( $setting * 24 * 60 * 60 );

        if ( $this -> pro && $current_date - $time_dif <= $post_time ):
            return '<span class="new-post">' . __( 'Neu', WPI_PLUGIN_NAME ) . '</span>';
        else:
            return;
        endif;
    }

}
