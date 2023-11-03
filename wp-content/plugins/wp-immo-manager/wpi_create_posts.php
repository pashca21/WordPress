<?php
/**************************************
 ******** XML zu Array Funktion
 *************************************/
function xmlstring2array( $string ) {
    $xml = simplexml_load_file( $string, 'SimpleXMLElement', LIBXML_NOCDATA );
    $namespace = $xml->getNamespaces(true);
    //zeigen($namespace);
    $array = json_decode( json_encode( $xml ), true );

    return $array;
}

/***************************************/
/* Auslesen der XML-Files in ein Array */
/***************************************/

function wpi_xml_array( $arg5 ) {
    $xmlfiles = $arg5;

    foreach ( $xmlfiles as $file_name ) {
        if ( $xmlstring[] = xmlstring2array( WPI_TEMP_DIR . $file_name ) ):
            unlink( WPI_TEMP_DIR . $file_name );
        endif;
        if ( ! $xmlstring ):
            exit( 'Datei konnte nicht eingelesen werden.' );
        endif;
    }
    if ( ! empty( $xmlstring ) ):
        //wpi_deleteFilesFromDirectory(WPI_TEMP_DIR);

        $arg6 = $xmlstring;

        return $arg6;
    endif;

}

/*******************************/
/** Abfrage des XML-Standards **/
/*******************************/

function wpi_xml_standard() {
    $standard = get_option( 'wpi_standard' );

    if ( isset( $standard ) && ( $standard === 'IS24' ) ):
        wpi_xml_is24();
    elseif ( $standard == 'OpenImmo' ):
        wpi_xml_openimmo();
    else:
        exit();
    endif;

}

/***************************************/
/** Erstellen, Ändern und Löschen der **/
/****** Immobilien im Format IS24 ******/
/***************************************/
function wpi_xml_is24() {
    global $wpdb;
    global $table_prefix;
    $fehler = 0;

    $xml_array = $GLOBALS[ 'xml_array' ];

    switch ( isset( $xml_array -> Anbieter ) ) {
        case ( $xml_array -> Anbieter -> WohnungMiete ):
            foreach ( $xml_array -> Anbieter -> WohnungMiete as $mietwohnung ) {
                $sqid = 'SELECT ID FROM ' . $table_prefix . 'posts WHERE post_title = "' . $mietwohnung -> Adresse[ "Ort" ] . '-' . $mietwohnung[ "Ueberschrift" ] . '"';
                $id   = $wpdb -> get_results( $sqid );
                if ( $id ):
                    $id    = $id[ 0 ] -> ID;
                    $up_id = array(
                        'ID'           => $id,
                        'post_content' => '<h3>Objektbeschreibung</h3><p>' . $mietwohnung -> Objektbeschreibung . '</p>
								        <h3>Lage</h3><p>' . $mietwohnung -> Lage . '</p>
								        <h3>Ausstattung</h3><p>' . $mietwohnung -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'    => '' . $mietwohnung[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'   => '' . $mietwohnung -> Adresse[ 'Ort' ] . '-' . $mietwohnung[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'  => 'publish'
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                    );
                endif;
                if ( $mietwohnung[ 'Importmodus' ] == 'aktualisieren' ) {
                    $immo  = array(
                        'ID'             => '',
                        //[ <post id> ] Are you updating an existing post?
                        'post_content'   => '<h3>Objektbeschreibung</h3><p>' . $mietwohnung -> Objektbeschreibung . '</p>
									        <h3>Lage</h3><p>' . $mietwohnung -> Lage . '</p>
									        <h3>Ausstattung</h3><p>' . $mietwohnung -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'      => '' . $mietwohnung[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'     => '' . $mietwohnung -> Adresse[ 'Ort' ] . '-' . $mietwohnung[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'    => 'publish',
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                        'post_type'      => 'wpi_immobilie',
                        //[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] Default 'post'.
//  			'post_author'    => '',//[ <user ID> ] The user ID number of the author. Default is the current user ID.
//  			'ping_status'    => '',//[ 'closed' | 'open' ] Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//  			'post_parent'    => '',//[ <post ID> ] Sets the parent of the new post, if any. Default 0.
//  			'menu_order'     => '',//[ <order> ] If new post is a page, sets the order in which it should appear in supported menus. Default 0.
//  			'to_ping'        => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
//  			'pinged'         => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//  			'post_password'  => '',//[ <string> ] Password for post, if any. Default empty string.
//  			'guid'           => '',// Skip this and let Wordpress handle it, usually.
//  			'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
                        'post_excerpt'   => $mietwohnung -> Objektbeschreibung,
                        //[ <string> ] For all your post excerpt needs.
//  			            'post_date'     => date('Y-M-d h:i:s'),//[ Y-m-d H:i:s ] The time post was made.
//  			'post_date_gmt'  => '',//[ Y-m-d H:i:s ] The time post was made, in GMT.
                        'comment_status' => 'closed',
                        //[ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
                        'post_category'  => '',
                        //[ array(<category id>, ...) ] Default empty.
                        'tags_input'     => '',
                        //[ '<tag>, <tag>, ...' | array ] Default empty.
                        'tax_input'      => array( 'objekttyp' => 'Wohnung' ),
                        //[ array( <taxonomy> => <array | string> ) ] For custom taxonomies. Default empty.
                        'page_template'  => ''
                        //[ <string> ] Default empty.
                    );
                    $error = false;
                    if ( empty( $id ) ) {
                        $post_id = wp_insert_post( $immo, $error );
                        wp_set_object_terms( $post_id, 'Wohnung', 'objekttyp', false );
                        wp_set_object_terms( $post_id, 'Miete', 'vermarktungsart', false );
                        if ( ! $post_id ) {
                            $fehler ++;
                        }

                        add_post_meta( $post_id, "Beschreibung", "" . $mietwohnung -> Objektbeschreibung . "" );            //Beschreibung
                        add_post_meta( $post_id, "Lage", "" . $mietwohnung -> Lage . "" );                                  //Lage
                        add_post_meta( $post_id, "Ausstattung", "" . $mietwohnung -> Ausstattung . "" );                    //Ausstattung

                        add_post_meta( $post_id, "HeizkostenInWarmmieteEnthalten",
                            "" . $mietwohnung -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        add_post_meta( $post_id, "Nebenkosten", "" . $mietwohnung -> Mietpreise[ 'Nebenkosten' ] . "" );      //Nebenkosten
                        add_post_meta( $post_id, "Kaution", "" . $mietwohnung -> Mietpreise[ 'Kaution' ] . "" );              //Kaution
                        add_post_meta( $post_id, "Warmmiete", "" . $mietwohnung -> Mietpreise[ 'Warmmiete' ] . "" );          //Warmmiete
                        add_post_meta( $post_id, "Kaltmiete", "" . $mietwohnung -> Mietpreise[ 'Kaltmiete' ] . "" );          //Kaltmiete
                        add_post_meta( $post_id, "BefeuerungsArt", "" . $mietwohnung -> BefeuerungsArt . "" );              //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $mietwohnung -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $mietwohnung -> Kontaktperson[ 'Vorname' ] . " "
                            . $mietwohnung -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $mietwohnung -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $mietwohnung -> Kontaktperson[ 'EMail' ] . "";
                        add_post_meta( $post_id, 'kontakt', $kontakt );
                        $adressdruck = "" . $mietwohnung[ 'Adressdruck' ] . "";
                        add_post_meta( $post_id, 'zeige_adresse', $adressdruck );                                         //Adressdruck?
                        $strasse = "" . $mietwohnung -> Adresse[ 'Strasse' ] . " " . $mietwohnung -> Adresse[ 'Hausnummer' ] . "";
                        add_post_meta( $post_id, 'strasse', $strasse );                                                   //Strasse
                        $ort = "" . $mietwohnung -> Adresse[ 'Postleitzahl' ] . " " . $mietwohnung -> Adresse[ 'Ort' ] . "";
                        add_post_meta( $post_id, 'ort', $ort );                                                           //Ort
                        add_post_meta( $post_id, "Zimmer", "" . $mietwohnung[ 'Zimmer' ] . "" );                            //Zimmer
                        add_post_meta( $post_id, "AnzahlSchlafzimmer", "" . $mietwohnung[ 'AnzahlSchafzimmer' ] . "" );     //AnzahlSchlafzimmer
                        add_post_meta( $post_id, "AnzahlBadezimmer", "" . $mietwohnung[ 'AnzahlBadezimmer' ] . "" );        //AnzahlBadezimmer
                        add_post_meta( $post_id, "Wohnflaeche", "" . $mietwohnung[ 'Wohnflaeche' ] . "" );                  //Wohnfläche
                        add_post_meta( $post_id, "Nutzflaeche", "" . $mietwohnung[ 'Nutzflaeche' ] . "" );                  //Nutzfläche
                        add_post_meta( $post_id, "Etage", "" . $mietwohnung[ 'Etage' ] . "" );                              //Etage
                        add_post_meta( $post_id, "Etagenzahl", "" . $mietwohnung[ 'Etagenzahl' ] . "" );                    //Etagenzahl
                        add_post_meta( $post_id, "WohnungKategorie", "" . $mietwohnung[ 'WohnungKategorie' ] . "" );        //WohnKategorie
                        add_post_meta( $post_id, "FreiAb", "" . $mietwohnung[ 'FreiAb' ] . "" );                            //FreiAb
                        add_post_meta( $post_id, "Ausstattungsqualitaet", "" . $mietwohnung[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        add_post_meta( $post_id, "GaesteWC", "" . $mietwohnung[ 'GaesteWC' ] . "" );                        //GästeWC
                        add_post_meta( $post_id, "Gartenbenutzung", "" . $mietwohnung[ 'Gartenbenutzung' ] . "" );          //Gartenbenutzung
                        add_post_meta( $post_id, "BetreutesWohnen", "" . $mietwohnung[ 'BetreutesWohnen' ] . "" );          //BetreutesWohnen
                        add_post_meta( $post_id, "Foerderung", "" . $mietwohnung[ 'Foerderung' ] . "" );                    //Foerderung
                        add_post_meta( $post_id, "Rollstuhlgerecht", "" . $mietwohnung[ 'Rollstuhlgerecht' ] . "" );        //Rollstuhlgerecht
                        add_post_meta( $post_id, "Einbaukueche", "" . $mietwohnung[ 'Einbaukueche' ] . "" );                //Einbaukueche
                        add_post_meta( $post_id, "Provisionspflichtig", "" . $mietwohnung[ 'Provisionspflichtig' ] . "" );  //Prov.Pflicht
                        add_post_meta( $post_id, "Objektzustand", "" . $mietwohnung[ 'Objektzustand' ] . "" );              //Objektzustand
                        add_post_meta( $post_id, "Barrierefrei", "" . $mietwohnung[ 'Barrierefrei' ] . "" );                //Barrierefrei
                        add_post_meta( $post_id, "Provisionshinweis", "" . $mietwohnung[ 'Provisionshinweis' ] . "" );      //Prov. Hinweis
                        add_post_meta( $post_id, "Provision", "" . $mietwohnung[ 'Provision' ] . "" );                      //Provision
                        add_post_meta( $post_id, "Keller", "" . $mietwohnung[ 'Keller' ] . "" );                            //Keller
                        add_post_meta( $post_id, "BalkonTerrasse", "" . $mietwohnung[ 'BalkonTerrasse' ] . "" );            //BalkonTerrasse
                        add_post_meta( $post_id, "Haustiere", "" . $mietwohnung[ 'Haustiere' ] . "" );                      //Haustiere
                        add_post_meta( $post_id, "Aufzug", "" . $mietwohnung[ 'Aufzug' ] . "" ); //Aufzug
                        $bilder = array();
                        foreach ( $mietwohnung -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            add_post_meta( $post_id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;
                        echo 'Immobilie wurde hinzugefügt<br />';
                    }//Ende if-!isset ID
                    else {
                        $update = wp_update_post( $up_id );
                        if ( $update == 0 ) {
                            $fehler ++;
                        }
                        update_post_meta( $id, "Beschreibung", "" . $mietwohnung -> Objektbeschreibung . "" );              //Beschreibung
                        update_post_meta( $id, "Lage", "" . $mietwohnung -> Lage . "" );                                    //Lage
                        update_post_meta( $id, "Ausstattung", "" . $mietwohnung -> Ausstattung . "" );                      //Ausstattung

                        update_post_meta( $id, "HeizkostenInWarmmieteEnthalten",
                            "" . $mietwohnung -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        update_post_meta( $id, "Nebenkosten", "" . $mietwohnung -> Mietpreise[ 'Nebenkosten' ] . "" );        //Nebenkosten
                        update_post_meta( $id, "Kaution", "" . $mietwohnung -> Mietpreise[ 'Kaution' ] . "" );                //Kaution
                        update_post_meta( $id, "Warmmiete", "" . $mietwohnung -> Mietpreise[ 'Warmmiete' ] . "" );            //Warmmiete
                        update_post_meta( $id, "Kaltmiete", "" . $mietwohnung -> Mietpreise[ 'Kaltmiete' ] . "" );            //Kaltmiete
                        update_post_meta( $id, "BefeuerungsArt", "" . $mietwohnung -> BefeuerungsArt . "" );                //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $mietwohnung -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $mietwohnung -> Kontaktperson[ 'Vorname' ] . " "
                            . $mietwohnung -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $mietwohnung -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $mietwohnung -> Kontaktperson[ 'EMail' ] . "";
                        update_post_meta( $id, 'kontakt', $kontakt );
                        $adressdruck = "" . $mietwohnung[ 'Adressdruck' ] . "";
                        update_post_meta( $id, 'zeige_adresse', $adressdruck );                                           //Adressdruck?
                        $strasse = "" . $mietwohnung -> Adresse[ 'Strasse' ] . " " . $mietwohnung -> Adresse[ 'Hausnummer' ] . "";
                        update_post_meta( $id, 'strasse', $strasse );                                                     //Strasse
                        $ort = "" . $mietwohnung -> Adresse[ 'Postleitzahl' ] . " " . $mietwohnung -> Adresse[ 'Ort' ] . "";
                        update_post_meta( $id, 'ort', $ort );                                                             //Ort
                        update_post_meta( $id, "Zimmer", "" . $mietwohnung[ 'Zimmer' ] . "" );                              //Zimmer
                        update_post_meta( $id, "AnzahlSchlafzimmer", "" . $mietwohnung[ 'AnzahlSchafzimmer' ] . "" );       //AnzahlSchlafzimmer
                        update_post_meta( $id, "AnzahlBadezimmer", "" . $mietwohnung[ 'AnzahlBadezimmer' ] . "" );          //AnzahlBadezimmer
                        update_post_meta( $id, "Wohnflaeche", "" . $mietwohnung[ 'Wohnflaeche' ] . "" );                    //Wohnfläche
                        update_post_meta( $id, "Nutzflaeche", "" . $mietwohnung[ 'Nutzflaeche' ] . "" );                    //Nutzfläche
                        update_post_meta( $id, "Etage", "" . $mietwohnung[ 'Etage' ] . "" );                                //Etage
                        update_post_meta( $id, "Etagenzahl", "" . $mietwohnung[ 'Etagenzahl' ] . "" );                      //Etagenzahl
                        update_post_meta( $id, "WohnungKategorie", "" . $mietwohnung[ 'WohnungKategorie' ] . "" );          //WohnKategorie
                        update_post_meta( $id, "FreiAb", "" . $mietwohnung[ 'FreiAb' ] . "" );                              //FreiAb
                        update_post_meta( $id, "Ausstattungsqualitaet", "" . $mietwohnung[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        update_post_meta( $id, "GaesteWC", "" . $mietwohnung[ 'GaesteWC' ] . "" );                          //GästeWC
                        update_post_meta( $id, "Gartenbenutzung", "" . $mietwohnung[ 'Gartenbenutzung' ] . "" );            //Gartenbenutzung
                        update_post_meta( $id, "BetreutesWohnen", "" . $mietwohnung[ 'BetreutesWohnen' ] . "" );            //BetreutesWohnen
                        update_post_meta( $id, "Foerderung", "" . $mietwohnung[ 'Foerderung' ] . "" );                      //Foerderung
                        update_post_meta( $id, "Rollstuhlgerecht", "" . $mietwohnung[ 'Rollstuhlgerecht' ] . "" );          //Rollstuhlgerecht
                        update_post_meta( $id, "Einbaukueche", "" . $mietwohnung[ 'Einbaukueche' ] . "" );                  //Einbaukueche
                        update_post_meta( $id, "Provisionspflichtig", "" . $mietwohnung[ 'Provisionspflichtig' ] . "" );    //Prov.Pflicht
                        update_post_meta( $id, "Objektzustand", "" . $mietwohnung[ 'Objektzustand' ] . "" );                //Objektzustand
                        update_post_meta( $id, "Barrierefrei", "" . $mietwohnung[ 'Barrierefrei' ] . "" );                  //Barrierefrei
                        update_post_meta( $id, "Provisionshinweis", "" . $mietwohnung[ 'Provisionshinweis' ] . "" );        //Prov. Hinweis
                        update_post_meta( $id, "Provision", "" . $mietwohnung[ 'Provision' ] . "" );                        //Provision
                        update_post_meta( $id, "Keller", "" . $mietwohnung[ 'Keller' ] . "" );                              //Keller
                        update_post_meta( $id, "BalkonTerrasse", "" . $mietwohnung[ 'BalkonTerrasse' ] . "" );              //BalkonTerrasse
                        update_post_meta( $id, "Haustiere", "" . $mietwohnung[ 'Haustiere' ] . "" );                        //Haustiere
                        update_post_meta( $id, "Aufzug", "" . $mietwohnung[ 'Aufzug' ] . "" );                              //Aufzug
                        foreach ( $mietwohnung -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            update_post_meta( $id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;                              //Bilder
                        /*
                                        // Prepare an array of post data for the attachment.
                                        $filename = $bilder['0'];
                                        $filetype = wp_check_filetype( basename( $filename ), null );
                                        $attachment = array(
                                            'guid'           => $filename,
                                            'post_mime_type' => $filetype['type'],
                                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                            'post_content'   => '',
                                            'post_status'    => 'inherit'
                                        );

                                        // Update the attachment.
                                        $attach_id = wp_insert_attachment( $attachment, $filename, $id );
                                        wp_update_attachment_metadata( $attach_id,  $attach_data );

                        */
                        echo 'Die Immobilie ' . $id . ' wurde aktualisiert! <br />';
                    }
                } //Ende IF Importmodus Aktualisieren
                elseif ( $mietwohnung[ 'Importmodus' ] == 'loeschen' ) {
                    $delete = wp_delete_post( $id );
                    if ( ! $delete ) {
                        $fehler ++;
                    }
                    delete_post_meta( $id, "Beschreibung", "" . $mietwohnung -> Objektbeschreibung . "" );                  //Beschreibung
                    delete_post_meta( $id, "Lage", "" . $mietwohnung -> Lage . "" );                                        //Lage
                    delete_post_meta( $id, "Ausstattung", "" . $mietwohnung -> Lage . "" );                                 //Ausstattung


                    delete_post_meta( $id, "HeizkostenInWarmmieteEnthalten" );                                            //Heizkosten enthalten?
                    delete_post_meta( $id, "Nebenkosten" );                                                               //Nebenkosten
                    delete_post_meta( $id, "Kaution" );                                                                   //Kaution
                    delete_post_meta( $id, "Warmmiete" );                                                                 //Warmmiete
                    delete_post_meta( $id, "Kaltmiete" );                                                                 //Kaltmiete
                    delete_post_meta( $id, "BefeuerungsArt" );                                                            //Befeuerungsart
                    delete_post_meta( $id, 'kontakt' );                                                                   //Kontakt
                    delete_post_meta( $id, 'zeige_adresse' );                                                             //Adressdruck?
                    delete_post_meta( $id, 'strasse' );                                                                   //Strasse
                    delete_post_meta( $id, 'ort' );                                                                       //Ort
                    delete_post_meta( $id, "Zimmer" );                                                                    //Zimmer
                    delete_post_meta( $id, "AnzahlSchlafzimmer" );                                                        //AnzahlSchlafzimmer
                    delete_post_meta( $id, "AnzahlBadezimmer" );                                                          //AnzahlBadezimmer
                    delete_post_meta( $id, "Wohnflaeche" );                                                               //Wohnfläche
                    delete_post_meta( $id, "Nutzflaeche" );                                                               //Nutzfläche
                    delete_post_meta( $id, "Etage" );                                                                     //Etage
                    delete_post_meta( $id, "Etagenzahl" );                                                                //Etagenzahl
                    delete_post_meta( $id, "WohnungKategorie" );                                                          //WohnKategorie
                    delete_post_meta( $id, "FreiAb" );                                                                    //FreiAb
                    delete_post_meta( $id, "Ausstattungsqualitaet" );                                                     //Qualität
                    delete_post_meta( $id, "GaesteWC" );                                                                  //GästeWC
                    delete_post_meta( $id, "Gartenbenutzung" );                                                           //Gartenbenutzung
                    delete_post_meta( $id, "BetreutesWohnen" );                                                           //BetreutesWohnen
                    delete_post_meta( $id, "Foerderung" );                                                                //Foerderung
                    delete_post_meta( $id, "Rollstuhlgerecht" );                                                          //Rollstuhlgerecht
                    delete_post_meta( $id, "Einbaukueche" );                                                              //Einbaukueche
                    delete_post_meta( $id, "Provisionspflichtig" );                                                       //Prov.Pflicht
                    delete_post_meta( $id, "Objektzustand" );                                                             //Objektzustand
                    delete_post_meta( $id, "Barrierefrei" );                                                              //Barrierefrei
                    delete_post_meta( $id, "Provisionshinweis" );                                                         //Prov. Hinweis
                    delete_post_meta( $id, "Provision" );                                                                 //Provision
                    delete_post_meta( $id, "Keller" );                                                                    //Keller
                    delete_post_meta( $id, "BalkonTerrasse" );                                                            //BalkonTerrasse
                    delete_post_meta( $id, "Haustiere" );                                                                 //Haustiere
                    delete_post_meta( $id, "Aufzug" );                                                                    //Aufzug

                    //Bilder löschen
                    foreach ( $mietwohnung -> MultimediaAnhang as $bild ) {
                        if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                            unlink( WPI_UPLOAD_URL . $bild[ 'Dateiname' ] . "" );
                        } //Ende IF $bild
                    } //Ende foreach MultimediaAnhang

                    echo 'Die Immobilie ' . $id . ' erfolgreich gelöscht<br />';
                }
            } // Ende Foreach -- Mietwohnung
        // break;
        case ( $xml_array -> Anbieter -> WohnungKauf ):
            foreach ( $xml_array -> Anbieter -> WohnungKauf as $kaufwohnung ) {
                $sqid = 'SELECT ID FROM ' . $table_prefix . 'posts WHERE post_title = "' . $kaufwohnung -> Adresse[ "Ort" ] . '-' . $kaufwohnung[ "Ueberschrift" ] . '"';
                $id   = $wpdb -> get_results( $sqid );
                if ( $id ):
                    $id    = $id[ 0 ] -> ID;
                    $up_id = array(
                        'ID'           => $id,
                        'post_content' => '<h3>Objektbeschreibung</h3><p>' . $kaufwohnung -> Objektbeschreibung . '</p>
								        <h3>Lage</h3><p>' . $kaufwohnung -> Lage . '</p>
								        <h3>Ausstattung</h3><p>' . $kaufwohnung -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'    => '' . $kaufwohnung[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'   => '' . $kaufwohnung -> Adresse[ 'Ort' ] . '-' . $kaufwohnung[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'  => 'publish'
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                    );
                endif;
                if ( $kaufwohnung[ 'Importmodus' ] == 'aktualisieren' ) {
                    $immo  = array(
                        'ID'             => '',
                        //[ <post id> ] Are you updating an existing post?
                        'post_content'   => '<h3>Objektbeschreibung</h3><p>' . $kaufwohnung -> Objektbeschreibung . '</p>
									        <h3>Lage</h3><p>' . $kaufwohnung -> Lage . '</p>
									        <h3>Ausstattung</h3><p>' . $kaufwohnung -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'      => '' . $kaufwohnung[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'     => '' . $kaufwohnung -> Adresse[ 'Ort' ] . '-' . $kaufwohnung[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'    => 'publish',
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                        'post_type'      => 'wpi_immobilie',
                        //[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] Default 'post'.
//  			'post_author'    => '',//[ <user ID> ] The user ID number of the author. Default is the current user ID.
//  			'ping_status'    => '',//[ 'closed' | 'open' ] Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//  			'post_parent'    => '',//[ <post ID> ] Sets the parent of the new post, if any. Default 0.
//  			'menu_order'     => '',//[ <order> ] If new post is a page, sets the order in which it should appear in supported menus. Default 0.
//  			'to_ping'        => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
//  			'pinged'         => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//  			'post_password'  => '',//[ <string> ] Password for post, if any. Default empty string.
//  			'guid'           => '',// Skip this and let Wordpress handle it, usually.
//  			'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
                        'post_excerpt'   => $kaufwohnung -> Objektbeschreibung,
                        //[ <string> ] For all your post excerpt needs.
//  			            'post_date'     => date('Y-M-d h:i:s'),//[ Y-m-d H:i:s ] The time post was made.
//  			'post_date_gmt'  => '',//[ Y-m-d H:i:s ] The time post was made, in GMT.
                        'comment_status' => 'closed',
                        //[ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
                        'post_category'  => '',
                        //[ array(<category id>, ...) ] Default empty.
                        'tags_input'     => '',
                        //[ '<tag>, <tag>, ...' | array ] Default empty.
                        'tax_input'      => array( 'objekttyp' => 'Wohnung' ),
                        //[ array( <taxonomy> => <array | string> ) ] For custom taxonomies. Default empty.
                        'page_template'  => ''
                        //[ <string> ] Default empty.
                    );
                    $error = false;
                    if ( empty( $id ) ) {
                        $post_id = wp_insert_post( $immo, $error );
                        wp_set_object_terms( $post_id, 'Wohnung', 'objekttyp', false );
                        wp_set_object_terms( $post_id, 'Kauf', 'vermarktungsart', false );
                        if ( ! $post_id ) {
                            $fehler ++;
                        }

                        add_post_meta( $post_id, "Beschreibung", "" . $kaufwohnung -> Objektbeschreibung . "" );            //Beschreibung
                        add_post_meta( $post_id, "Lage", "" . $kaufwohnung -> Lage . "" );                                  //Lage
                        add_post_meta( $post_id, "Ausstattung", "" . $kaufwohnung -> Ausstattung . "" );                    //Ausstattung

                        add_post_meta( $post_id, "HeizkostenInWarmmieteEnthalten",
                            "" . $kaufwohnung -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        add_post_meta( $post_id, "Nebenkosten", "" . $kaufwohnung -> Mietpreise[ 'Nebenkosten' ] . "" );      //Nebenkosten
                        add_post_meta( $post_id, "Kaution", "" . $kaufwohnung -> Mietpreise[ 'Kaution' ] . "" );              //Kaution
                        add_post_meta( $post_id, "Warmmiete", "" . $kaufwohnung -> Mietpreise[ 'Warmmiete' ] . "" );          //Warmmiete
                        add_post_meta( $post_id, "Kaltmiete", "" . $kaufwohnung -> Mietpreise[ 'Kaltmiete' ] . "" );          //Kaltmiete
                        add_post_meta( $post_id, "BefeuerungsArt", "" . $kaufwohnung -> BefeuerungsArt . "" );              //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $kaufwohnung -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $kaufwohnung -> Kontaktperson[ 'Vorname' ] . " "
                            . $kaufwohnung -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $kaufwohnung -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $kaufwohnung -> Kontaktperson[ 'EMail' ] . "";
                        add_post_meta( $post_id, 'kontakt', $kontakt );
                        $adressdruck = "" . $kaufwohnung[ 'Adressdruck' ] . "";
                        add_post_meta( $post_id, 'zeige_adresse', $adressdruck );                                         //Adressdruck?
                        $strasse = "" . $kaufwohnung -> Adresse[ 'Strasse' ] . " " . $kaufwohnung -> Adresse[ 'Hausnummer' ] . "";
                        add_post_meta( $post_id, 'strasse', $strasse );                                                   //Strasse
                        $ort = "" . $kaufwohnung -> Adresse[ 'Postleitzahl' ] . " " . $kaufwohnung -> Adresse[ 'Ort' ] . "";
                        add_post_meta( $post_id, 'ort', $ort );                                                           //Ort
                        add_post_meta( $post_id, "Zimmer", "" . $kaufwohnung[ 'Zimmer' ] . "" );                            //Zimmer
                        add_post_meta( $post_id, "AnzahlSchlafzimmer", "" . $kaufwohnung[ 'AnzahlSchafzimmer' ] . "" );     //AnzahlSchlafzimmer
                        add_post_meta( $post_id, "AnzahlBadezimmer", "" . $kaufwohnung[ 'AnzahlBadezimmer' ] . "" );        //AnzahlBadezimmer
                        add_post_meta( $post_id, "Wohnflaeche", "" . $kaufwohnung[ 'Wohnflaeche' ] . "" );                  //Wohnfläche
                        add_post_meta( $post_id, "Nutzflaeche", "" . $kaufwohnung[ 'Nutzflaeche' ] . "" );                  //Nutzfläche
                        add_post_meta( $post_id, "Etage", "" . $kaufwohnung[ 'Etage' ] . "" );                              //Etage
                        add_post_meta( $post_id, "Etagenzahl", "" . $kaufwohnung[ 'Etagenzahl' ] . "" );                    //Etagenzahl
                        add_post_meta( $post_id, "WohnungKategorie", "" . $kaufwohnung[ 'WohnungKategorie' ] . "" );        //WohnKategorie
                        add_post_meta( $post_id, "FreiAb", "" . $kaufwohnung[ 'FreiAb' ] . "" );                            //FreiAb
                        add_post_meta( $post_id, "Ausstattungsqualitaet", "" . $kaufwohnung[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        add_post_meta( $post_id, "GaesteWC", "" . $kaufwohnung[ 'GaesteWC' ] . "" );                        //GästeWC
                        add_post_meta( $post_id, "Gartenbenutzung", "" . $kaufwohnung[ 'Gartenbenutzung' ] . "" );          //Gartenbenutzung
                        add_post_meta( $post_id, "BetreutesWohnen", "" . $kaufwohnung[ 'BetreutesWohnen' ] . "" );          //BetreutesWohnen
                        add_post_meta( $post_id, "Foerderung", "" . $kaufwohnung[ 'Foerderung' ] . "" );                    //Foerderung
                        add_post_meta( $post_id, "Rollstuhlgerecht", "" . $kaufwohnung[ 'Rollstuhlgerecht' ] . "" );        //Rollstuhlgerecht
                        add_post_meta( $post_id, "Einbaukueche", "" . $kaufwohnung[ 'Einbaukueche' ] . "" );                //Einbaukueche
                        add_post_meta( $post_id, "Provisionspflichtig", "" . $kaufwohnung[ 'Provisionspflichtig' ] . "" );  //Prov.Pflicht
                        add_post_meta( $post_id, "Objektzustand", "" . $kaufwohnung[ 'Objektzustand' ] . "" );              //Objektzustand
                        add_post_meta( $post_id, "Barrierefrei", "" . $kaufwohnung[ 'Barrierefrei' ] . "" );                //Barrierefrei
                        add_post_meta( $post_id, "Provisionshinweis", "" . $kaufwohnung[ 'Provisionshinweis' ] . "" );      //Prov. Hinweis
                        add_post_meta( $post_id, "Provision", "" . $kaufwohnung[ 'Provision' ] . "" );                      //Provision
                        add_post_meta( $post_id, "Keller", "" . $kaufwohnung[ 'Keller' ] . "" );                            //Keller
                        add_post_meta( $post_id, "BalkonTerrasse", "" . $kaufwohnung[ 'BalkonTerrasse' ] . "" );            //BalkonTerrasse
                        add_post_meta( $post_id, "Haustiere", "" . $kaufwohnung[ 'Haustiere' ] . "" );                      //Haustiere
                        add_post_meta( $post_id, "Aufzug", "" . $kaufwohnung[ 'Aufzug' ] . "" );                            //Aufzug
                        foreach ( $kaufwohnung -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            add_post_meta( $post_id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;
                        echo 'Immobilie wurde hinzugefügt<br />';
                    }//Ende if-!isset ID
                    else {
                        $update = wp_update_post( $up_id );
                        if ( $update == 0 ) {
                            $fehler ++;
                        }
                        update_post_meta( $id, "Beschreibung", "" . $kaufwohnung -> Objektbeschreibung . "" );              //Beschreibung
                        update_post_meta( $id, "Lage", "" . $kaufwohnung -> Lage . "" );                                    //Lage
                        update_post_meta( $id, "Ausstattung", "" . $kaufwohnung -> Ausstattung . "" );                      //Ausstattung

                        update_post_meta( $id, "HeizkostenInWarmmieteEnthalten",
                            "" . $kaufwohnung -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        update_post_meta( $id, "Nebenkosten", "" . $kaufwohnung -> Mietpreise[ 'Nebenkosten' ] . "" );        //Nebenkosten
                        update_post_meta( $id, "Kaution", "" . $kaufwohnung -> Mietpreise[ 'Kaution' ] . "" );                //Kaution
                        update_post_meta( $id, "Warmmiete", "" . $kaufwohnung -> Mietpreise[ 'Warmmiete' ] . "" );            //Warmmiete
                        update_post_meta( $id, "Kaltmiete", "" . $kaufwohnung -> Mietpreise[ 'Kaltmiete' ] . "" );            //Kaltmiete
                        update_post_meta( $id, "BefeuerungsArt", "" . $kaufwohnung -> BefeuerungsArt . "" );                //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $kaufwohnung -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $kaufwohnung -> Kontaktperson[ 'Vorname' ] . " "
                            . $kaufwohnung -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $kaufwohnung -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $kaufwohnung -> Kontaktperson[ 'EMail' ] . "";
                        update_post_meta( $id, 'kontakt', $kontakt );
                        $adressdruck = "" . $kaufwohnung[ 'Adressdruck' ] . "";
                        update_post_meta( $id, 'zeige_adresse', $adressdruck );                                           //Adressdruck?
                        $strasse = "" . $kaufwohnung -> Adresse[ 'Strasse' ] . " " . $kaufwohnung -> Adresse[ 'Hausnummer' ] . "";
                        update_post_meta( $id, 'strasse', $strasse );                                                     //Strasse
                        $ort = "" . $kaufwohnung -> Adresse[ 'Postleitzahl' ] . " " . $kaufwohnung -> Adresse[ 'Ort' ] . "";
                        update_post_meta( $id, 'ort', $ort );                                                             //Ort
                        update_post_meta( $id, "Zimmer", "" . $kaufwohnung[ 'Zimmer' ] . "" );                              //Zimmer
                        update_post_meta( $id, "AnzahlSchlafzimmer", "" . $kaufwohnung[ 'AnzahlSchafzimmer' ] . "" );       //AnzahlSchlafzimmer
                        update_post_meta( $id, "AnzahlBadezimmer", "" . $kaufwohnung[ 'AnzahlBadezimmer' ] . "" );          //AnzahlBadezimmer
                        update_post_meta( $id, "Wohnflaeche", "" . $kaufwohnung[ 'Wohnflaeche' ] . "" );                    //Wohnfläche
                        update_post_meta( $id, "Nutzflaeche", "" . $kaufwohnung[ 'Nutzflaeche' ] . "" );                    //Nutzfläche
                        update_post_meta( $id, "Etage", "" . $kaufwohnung[ 'Etage' ] . "" );                                //Etage
                        update_post_meta( $id, "Etagenzahl", "" . $kaufwohnung[ 'Etagenzahl' ] . "" );                      //Etagenzahl
                        update_post_meta( $id, "WohnungKategorie", "" . $kaufwohnung[ 'WohnungKategorie' ] . "" );          //WohnKategorie
                        update_post_meta( $id, "FreiAb", "" . $kaufwohnung[ 'FreiAb' ] . "" );                              //FreiAb
                        update_post_meta( $id, "Ausstattungsqualitaet", "" . $kaufwohnung[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        update_post_meta( $id, "GaesteWC", "" . $kaufwohnung[ 'GaesteWC' ] . "" );                          //GästeWC
                        update_post_meta( $id, "Gartenbenutzung", "" . $kaufwohnung[ 'Gartenbenutzung' ] . "" );            //Gartenbenutzung
                        update_post_meta( $id, "BetreutesWohnen", "" . $kaufwohnung[ 'BetreutesWohnen' ] . "" );            //BetreutesWohnen
                        update_post_meta( $id, "Foerderung", "" . $kaufwohnung[ 'Foerderung' ] . "" );                      //Foerderung
                        update_post_meta( $id, "Rollstuhlgerecht", "" . $kaufwohnung[ 'Rollstuhlgerecht' ] . "" );          //Rollstuhlgerecht
                        update_post_meta( $id, "Einbaukueche", "" . $kaufwohnung[ 'Einbaukueche' ] . "" );                  //Einbaukueche
                        update_post_meta( $id, "Provisionspflichtig", "" . $kaufwohnung[ 'Provisionspflichtig' ] . "" );    //Prov.Pflicht
                        update_post_meta( $id, "Objektzustand", "" . $kaufwohnung[ 'Objektzustand' ] . "" );                //Objektzustand
                        update_post_meta( $id, "Barrierefrei", "" . $kaufwohnung[ 'Barrierefrei' ] . "" );                  //Barrierefrei
                        update_post_meta( $id, "Provisionshinweis", "" . $kaufwohnung[ 'Provisionshinweis' ] . "" );        //Prov. Hinweis
                        update_post_meta( $id, "Provision", "" . $kaufwohnung[ 'Provision' ] . "" );                        //Provision
                        update_post_meta( $id, "Keller", "" . $kaufwohnung[ 'Keller' ] . "" );                              //Keller
                        update_post_meta( $id, "BalkonTerrasse", "" . $kaufwohnung[ 'BalkonTerrasse' ] . "" );              //BalkonTerrasse
                        update_post_meta( $id, "Haustiere", "" . $kaufwohnung[ 'Haustiere' ] . "" );                        //Haustiere
                        update_post_meta( $id, "Aufzug", "" . $kaufwohnung[ 'Aufzug' ] . "" );                              //Aufzug
                        foreach ( $kaufwohnung -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            update_post_meta( $id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;                              //Bilder
                        /*
                                        // Prepare an array of post data for the attachment.
                                        $filename = $bilder['0'];
                                        $filetype = wp_check_filetype( basename( $filename ), null );
                                        $attachment = array(
                                            'guid'           => $filename,
                                            'post_mime_type' => $filetype['type'],
                                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                            'post_content'   => '',
                                            'post_status'    => 'inherit'
                                        );

                                        // Update the attachment.
                                        $attach_id = wp_insert_attachment( $attachment, $filename, $id );
                                        wp_update_attachment_metadata( $attach_id,  $attach_data );

                        */
                        echo 'Die Immobilie ' . $id . ' wurde aktualisiert! <br />';
                    }
                } //Ende IF Importmodus Aktualisieren
                elseif ( $kaufwohnung[ 'Importmodus' ] == 'loeschen' ) {
                    $delete = wp_delete_post( $id );
                    if ( ! $delete ) {
                        $fehler ++;
                    }
                    delete_post_meta( $id, "Beschreibung", "" . $kaufwohnung -> Objektbeschreibung . "" );                  //Beschreibung
                    delete_post_meta( $id, "Lage", "" . $kaufwohnung -> Lage . "" );                                        //Lage
                    delete_post_meta( $id, "Ausstattung", "" . $kaufwohnung -> Lage . "" );                                 //Ausstattung


                    delete_post_meta( $id, "HeizkostenInWarmmieteEnthalten" );                                            //Heizkosten enthalten?
                    delete_post_meta( $id, "Nebenkosten" );                                                               //Nebenkosten
                    delete_post_meta( $id, "Kaution" );                                                                   //Kaution
                    delete_post_meta( $id, "Warmmiete" );                                                                 //Warmmiete
                    delete_post_meta( $id, "Kaltmiete" );                                                                 //Kaltmiete
                    delete_post_meta( $id, "BefeuerungsArt" );                                                            //Befeuerungsart
                    delete_post_meta( $id, 'kontakt' );                                                                   //Kontakt
                    delete_post_meta( $id, 'zeige_adresse' );                                                             //Adressdruck?
                    delete_post_meta( $id, 'strasse' );                                                                   //Strasse
                    delete_post_meta( $id, 'ort' );                                                                       //Ort
                    delete_post_meta( $id, "Zimmer" );                                                                    //Zimmer
                    delete_post_meta( $id, "AnzahlSchlafzimmer" );                                                        //AnzahlSchlafzimmer
                    delete_post_meta( $id, "AnzahlBadezimmer" );                                                          //AnzahlBadezimmer
                    delete_post_meta( $id, "Wohnflaeche" );                                                               //Wohnfläche
                    delete_post_meta( $id, "Nutzflaeche" );                                                               //Nutzfläche
                    delete_post_meta( $id, "Etage" );                                                                     //Etage
                    delete_post_meta( $id, "Etagenzahl" );                                                                //Etagenzahl
                    delete_post_meta( $id, "WohnungKategorie" );                                                          //WohnKategorie
                    delete_post_meta( $id, "FreiAb" );                                                                    //FreiAb
                    delete_post_meta( $id, "Ausstattungsqualitaet" );                                                     //Qualität
                    delete_post_meta( $id, "GaesteWC" );                                                                  //GästeWC
                    delete_post_meta( $id, "Gartenbenutzung" );                                                           //Gartenbenutzung
                    delete_post_meta( $id, "BetreutesWohnen" );                                                           //BetreutesWohnen
                    delete_post_meta( $id, "Foerderung" );                                                                //Foerderung
                    delete_post_meta( $id, "Rollstuhlgerecht" );                                                          //Rollstuhlgerecht
                    delete_post_meta( $id, "Einbaukueche" );                                                              //Einbaukueche
                    delete_post_meta( $id, "Provisionspflichtig" );                                                       //Prov.Pflicht
                    delete_post_meta( $id, "Objektzustand" );                                                             //Objektzustand
                    delete_post_meta( $id, "Barrierefrei" );                                                              //Barrierefrei
                    delete_post_meta( $id, "Provisionshinweis" );                                                         //Prov. Hinweis
                    delete_post_meta( $id, "Provision" );                                                                 //Provision
                    delete_post_meta( $id, "Keller" );                                                                    //Keller
                    delete_post_meta( $id, "BalkonTerrasse" );                                                            //BalkonTerrasse
                    delete_post_meta( $id, "Haustiere" );                                                                 //Haustiere
                    delete_post_meta( $id, "Aufzug" );                                                                    //Aufzug

                    //Bilder löschen
                    foreach ( $kaufwohnung -> MultimediaAnhang as $bild ) {
                        if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                            unlink( WPI_UPLOAD_URL . $bild[ 'Dateiname' ] . "" );
                        } //Ende IF $bild
                    } //Ende foreach MultimediaAnhang

                    echo 'Die Immobilie ' . $id . ' erfolgreich gelöscht<br />';
                }
            } // Ende Foreach -- Kaufwohnung
        // break;
        case ( $xml_array -> Anbieter -> HausMiete ):
            foreach ( $xml_array -> Anbieter -> HausMiete as $miethaus ) {
                $sqid = 'SELECT ID FROM ' . $table_prefix . 'posts WHERE post_title = "' . $miethaus -> Adresse[ "Ort" ] . '-' . $miethaus[ "Ueberschrift" ] . '"';
                $id   = $wpdb -> get_results( $sqid );
                if ( $id ):
                    $id    = $id[ 0 ] -> ID;
                    $up_id = array(
                        'ID'           => $id,
                        'post_content' => '<h3>Objektbeschreibung</h3><p>' . $miethaus -> Objektbeschreibung . '</p>
								        <h3>Lage</h3><p>' . $miethaus -> Lage . '</p>
								        <h3>Ausstattung</h3><p>' . $miethaus -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'    => '' . $miethaus[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'   => '' . $miethaus -> Adresse[ 'Ort' ] . '-' . $miethaus[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'  => 'publish'
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                    );
                endif;
                if ( $miethaus[ 'Importmodus' ] == 'aktualisieren' ) {
                    $immo  = array(
                        'ID'             => '',
                        //[ <post id> ] Are you updating an existing post?
                        'post_content'   => '<h3>Objektbeschreibung</h3><p>' . $miethaus -> Objektbeschreibung . '</p>
									        <h3>Lage</h3><p>' . $miethaus -> Lage . '</p>
									        <h3>Ausstattung</h3><p>' . $miethaus -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'      => '' . $miethaus[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'     => '' . $miethaus -> Adresse[ 'Ort' ] . '-' . $miethaus[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'    => 'publish',
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                        'post_type'      => 'wpi_immobilie',
                        //[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] Default 'post'.
//  			'post_author'    => '',//[ <user ID> ] The user ID number of the author. Default is the current user ID.
//  			'ping_status'    => '',//[ 'closed' | 'open' ] Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//  			'post_parent'    => '',//[ <post ID> ] Sets the parent of the new post, if any. Default 0.
//  			'menu_order'     => '',//[ <order> ] If new post is a page, sets the order in which it should appear in supported menus. Default 0.
//  			'to_ping'        => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
//  			'pinged'         => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//  			'post_password'  => '',//[ <string> ] Password for post, if any. Default empty string.
//  			'guid'           => '',// Skip this and let Wordpress handle it, usually.
//  			'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
                        'post_excerpt'   => $miethaus -> Objektbeschreibung,
                        //[ <string> ] For all your post excerpt needs.
//  			            'post_date'     => date('Y-M-d h:i:s'),//[ Y-m-d H:i:s ] The time post was made.
//  			'post_date_gmt'  => '',//[ Y-m-d H:i:s ] The time post was made, in GMT.
                        'comment_status' => 'closed',
                        //[ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
                        'post_category'  => '',
                        //[ array(<category id>, ...) ] Default empty.
                        'tags_input'     => '',
                        //[ '<tag>, <tag>, ...' | array ] Default empty.
                        'tax_input'      => array( 'objekttyp' => 'Wohnung' ),
                        //[ array( <taxonomy> => <array | string> ) ] For custom taxonomies. Default empty.
                        'page_template'  => ''
                        //[ <string> ] Default empty.
                    );
                    $error = false;
                    if ( empty( $id ) ) {
                        $post_id = wp_insert_post( $immo, $error );
                        wp_set_object_terms( $post_id, 'Haus', 'objekttyp', false );
                        wp_set_object_terms( $post_id, 'Miete', 'vermarktungsart', false );
                        if ( ! $post_id ) {
                            $fehler ++;
                        }

                        add_post_meta( $post_id, "Beschreibung", "" . $miethaus -> Objektbeschreibung . "" );            //Beschreibung
                        add_post_meta( $post_id, "Lage", "" . $miethaus -> Lage . "" );                                  //Lage
                        add_post_meta( $post_id, "Ausstattung", "" . $miethaus -> Ausstattung . "" );                    //Ausstattung

                        add_post_meta( $post_id, "HeizkostenInWarmmieteEnthalten",
                            "" . $miethaus -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        add_post_meta( $post_id, "Nebenkosten", "" . $miethaus -> Mietpreise[ 'Nebenkosten' ] . "" );      //Nebenkosten
                        add_post_meta( $post_id, "Kaution", "" . $miethaus -> Mietpreise[ 'Kaution' ] . "" );              //Kaution
                        add_post_meta( $post_id, "Warmmiete", "" . $miethaus -> Mietpreise[ 'Warmmiete' ] . "" );          //Warmmiete
                        add_post_meta( $post_id, "Kaltmiete", "" . $miethaus -> Mietpreise[ 'Kaltmiete' ] . "" );          //Kaltmiete
                        add_post_meta( $post_id, "BefeuerungsArt", "" . $miethaus -> BefeuerungsArt . "" );              //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $miethaus -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $miethaus -> Kontaktperson[ 'Vorname' ] . " "
                            . $miethaus -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $miethaus -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $miethaus -> Kontaktperson[ 'EMail' ] . "";
                        add_post_meta( $post_id, 'kontakt', $kontakt );
                        $adressdruck = "" . $miethaus[ 'Adressdruck' ] . "";
                        add_post_meta( $post_id, 'zeige_adresse', $adressdruck );                                         //Adressdruck?
                        $strasse = "" . $miethaus -> Adresse[ 'Strasse' ] . " " . $miethaus -> Adresse[ 'Hausnummer' ] . "";
                        add_post_meta( $post_id, 'strasse', $strasse );                                                   //Strasse
                        $ort = "" . $miethaus -> Adresse[ 'Postleitzahl' ] . " " . $miethaus -> Adresse[ 'Ort' ] . "";
                        add_post_meta( $post_id, 'ort', $ort );                                                           //Ort
                        add_post_meta( $post_id, "Zimmer", "" . $miethaus[ 'Zimmer' ] . "" );                            //Zimmer
                        add_post_meta( $post_id, "AnzahlSchlafzimmer", "" . $miethaus[ 'AnzahlSchafzimmer' ] . "" );     //AnzahlSchlafzimmer
                        add_post_meta( $post_id, "AnzahlBadezimmer", "" . $miethaus[ 'AnzahlBadezimmer' ] . "" );        //AnzahlBadezimmer
                        add_post_meta( $post_id, "Wohnflaeche", "" . $miethaus[ 'Wohnflaeche' ] . "" );                  //Wohnfläche
                        add_post_meta( $post_id, "Nutzflaeche", "" . $miethaus[ 'Nutzflaeche' ] . "" );                  //Nutzfläche
                        add_post_meta( $post_id, "Etage", "" . $miethaus[ 'Etage' ] . "" );                              //Etage
                        add_post_meta( $post_id, "Etagenzahl", "" . $miethaus[ 'Etagenzahl' ] . "" );                    //Etagenzahl
                        add_post_meta( $post_id, "WohnungKategorie", "" . $miethaus[ 'WohnungKategorie' ] . "" );        //WohnKategorie
                        add_post_meta( $post_id, "FreiAb", "" . $miethaus[ 'FreiAb' ] . "" );                            //FreiAb
                        add_post_meta( $post_id, "Ausstattungsqualitaet", "" . $miethaus[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        add_post_meta( $post_id, "GaesteWC", "" . $miethaus[ 'GaesteWC' ] . "" );                        //GästeWC
                        add_post_meta( $post_id, "Gartenbenutzung", "" . $miethaus[ 'Gartenbenutzung' ] . "" );          //Gartenbenutzung
                        add_post_meta( $post_id, "BetreutesWohnen", "" . $miethaus[ 'BetreutesWohnen' ] . "" );          //BetreutesWohnen
                        add_post_meta( $post_id, "Foerderung", "" . $miethaus[ 'Foerderung' ] . "" );                    //Foerderung
                        add_post_meta( $post_id, "Rollstuhlgerecht", "" . $miethaus[ 'Rollstuhlgerecht' ] . "" );        //Rollstuhlgerecht
                        add_post_meta( $post_id, "Einbaukueche", "" . $miethaus[ 'Einbaukueche' ] . "" );                //Einbaukueche
                        add_post_meta( $post_id, "Provisionspflichtig", "" . $miethaus[ 'Provisionspflichtig' ] . "" );  //Prov.Pflicht
                        add_post_meta( $post_id, "Objektzustand", "" . $miethaus[ 'Objektzustand' ] . "" );              //Objektzustand
                        add_post_meta( $post_id, "Barrierefrei", "" . $miethaus[ 'Barrierefrei' ] . "" );                //Barrierefrei
                        add_post_meta( $post_id, "Provisionshinweis", "" . $miethaus[ 'Provisionshinweis' ] . "" );      //Prov. Hinweis
                        add_post_meta( $post_id, "Provision", "" . $miethaus[ 'Provision' ] . "" );                      //Provision
                        add_post_meta( $post_id, "Keller", "" . $miethaus[ 'Keller' ] . "" );                            //Keller
                        add_post_meta( $post_id, "BalkonTerrasse", "" . $miethaus[ 'BalkonTerrasse' ] . "" );            //BalkonTerrasse
                        add_post_meta( $post_id, "Haustiere", "" . $miethaus[ 'Haustiere' ] . "" );                      //Haustiere
                        add_post_meta( $post_id, "Aufzug", "" . $miethaus[ 'Aufzug' ] . "" );                            //Aufzug
                        foreach ( $miethaus -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            add_post_meta( $post_id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;
                        echo 'Immobilie wurde hinzugefügt<br />';
                    }//Ende if-!isset ID
                    else {
                        $update = wp_update_post( $up_id );
                        if ( $update == 0 ) {
                            $fehler ++;
                        }
                        update_post_meta( $id, "Beschreibung", "" . $miethaus -> Objektbeschreibung . "" );              //Beschreibung
                        update_post_meta( $id, "Lage", "" . $miethaus -> Lage . "" );                                    //Lage
                        update_post_meta( $id, "Ausstattung", "" . $miethaus -> Ausstattung . "" );                      //Ausstattung

                        update_post_meta( $id, "HeizkostenInWarmmieteEnthalten",
                            "" . $miethaus -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        update_post_meta( $id, "Nebenkosten", "" . $miethaus -> Mietpreise[ 'Nebenkosten' ] . "" );        //Nebenkosten
                        update_post_meta( $id, "Kaution", "" . $miethaus -> Mietpreise[ 'Kaution' ] . "" );                //Kaution
                        update_post_meta( $id, "Warmmiete", "" . $miethaus -> Mietpreise[ 'Warmmiete' ] . "" );            //Warmmiete
                        update_post_meta( $id, "Kaltmiete", "" . $miethaus -> Mietpreise[ 'Kaltmiete' ] . "" );            //Kaltmiete
                        update_post_meta( $id, "BefeuerungsArt", "" . $miethaus -> BefeuerungsArt . "" );                //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $miethaus -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $miethaus -> Kontaktperson[ 'Vorname' ] . " "
                            . $miethaus -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $miethaus -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $miethaus -> Kontaktperson[ 'EMail' ] . "";
                        update_post_meta( $id, 'kontakt', $kontakt );
                        $adressdruck = "" . $miethaus[ 'Adressdruck' ] . "";
                        update_post_meta( $id, 'zeige_adresse', $adressdruck );                                           //Adressdruck?
                        $strasse = "" . $miethaus -> Adresse[ 'Strasse' ] . " " . $miethaus -> Adresse[ 'Hausnummer' ] . "";
                        update_post_meta( $id, 'strasse', $strasse );                                                     //Strasse
                        $ort = "" . $miethaus -> Adresse[ 'Postleitzahl' ] . " " . $miethaus -> Adresse[ 'Ort' ] . "";
                        update_post_meta( $id, 'ort', $ort );                                                             //Ort
                        update_post_meta( $id, "Zimmer", "" . $miethaus[ 'Zimmer' ] . "" );                              //Zimmer
                        update_post_meta( $id, "AnzahlSchlafzimmer", "" . $miethaus[ 'AnzahlSchafzimmer' ] . "" );       //AnzahlSchlafzimmer
                        update_post_meta( $id, "AnzahlBadezimmer", "" . $miethaus[ 'AnzahlBadezimmer' ] . "" );          //AnzahlBadezimmer
                        update_post_meta( $id, "Wohnflaeche", "" . $miethaus[ 'Wohnflaeche' ] . "" );                    //Wohnfläche
                        update_post_meta( $id, "Nutzflaeche", "" . $miethaus[ 'Nutzflaeche' ] . "" );                    //Nutzfläche
                        update_post_meta( $id, "Etage", "" . $miethaus[ 'Etage' ] . "" );                                //Etage
                        update_post_meta( $id, "Etagenzahl", "" . $miethaus[ 'Etagenzahl' ] . "" );                      //Etagenzahl
                        update_post_meta( $id, "WohnungKategorie", "" . $miethaus[ 'WohnungKategorie' ] . "" );          //WohnKategorie
                        update_post_meta( $id, "FreiAb", "" . $miethaus[ 'FreiAb' ] . "" );                              //FreiAb
                        update_post_meta( $id, "Ausstattungsqualitaet", "" . $miethaus[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        update_post_meta( $id, "GaesteWC", "" . $miethaus[ 'GaesteWC' ] . "" );                          //GästeWC
                        update_post_meta( $id, "Gartenbenutzung", "" . $miethaus[ 'Gartenbenutzung' ] . "" );            //Gartenbenutzung
                        update_post_meta( $id, "BetreutesWohnen", "" . $miethaus[ 'BetreutesWohnen' ] . "" );            //BetreutesWohnen
                        update_post_meta( $id, "Foerderung", "" . $miethaus[ 'Foerderung' ] . "" );                      //Foerderung
                        update_post_meta( $id, "Rollstuhlgerecht", "" . $miethaus[ 'Rollstuhlgerecht' ] . "" );          //Rollstuhlgerecht
                        update_post_meta( $id, "Einbaukueche", "" . $miethaus[ 'Einbaukueche' ] . "" );                  //Einbaukueche
                        update_post_meta( $id, "Provisionspflichtig", "" . $miethaus[ 'Provisionspflichtig' ] . "" );    //Prov.Pflicht
                        update_post_meta( $id, "Objektzustand", "" . $miethaus[ 'Objektzustand' ] . "" );                //Objektzustand
                        update_post_meta( $id, "Barrierefrei", "" . $miethaus[ 'Barrierefrei' ] . "" );                  //Barrierefrei
                        update_post_meta( $id, "Provisionshinweis", "" . $miethaus[ 'Provisionshinweis' ] . "" );        //Prov. Hinweis
                        update_post_meta( $id, "Provision", "" . $miethaus[ 'Provision' ] . "" );                        //Provision
                        update_post_meta( $id, "Keller", "" . $miethaus[ 'Keller' ] . "" );                              //Keller
                        update_post_meta( $id, "BalkonTerrasse", "" . $miethaus[ 'BalkonTerrasse' ] . "" );              //BalkonTerrasse
                        update_post_meta( $id, "Haustiere", "" . $miethaus[ 'Haustiere' ] . "" );                        //Haustiere
                        update_post_meta( $id, "Aufzug", "" . $miethaus[ 'Aufzug' ] . "" );                              //Aufzug
                        foreach ( $miethaus -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            update_post_meta( $id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;                              //Bilder
                        /*
                                        // Prepare an array of post data for the attachment.
                                        $filename = $bilder['0'];
                                        $filetype = wp_check_filetype( basename( $filename ), null );
                                        $attachment = array(
                                            'guid'           => $filename,
                                            'post_mime_type' => $filetype['type'],
                                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                            'post_content'   => '',
                                            'post_status'    => 'inherit'
                                        );

                                        // Update the attachment.
                                        $attach_id = wp_insert_attachment( $attachment, $filename, $id );
                                        wp_update_attachment_metadata( $attach_id,  $attach_data );

                        */
                        echo 'Die Immobilie ' . $id . ' wurde aktualisiert! <br />';
                    }
                } //Ende IF Importmodus Aktualisieren
                elseif ( $miethaus[ 'Importmodus' ] == 'loeschen' ) {
                    $delete = wp_delete_post( $id );
                    if ( ! $delete ) {
                        $fehler ++;
                    }
                    delete_post_meta( $id, "Beschreibung", "" . $miethaus -> Objektbeschreibung . "" );                  //Beschreibung
                    delete_post_meta( $id, "Lage", "" . $miethaus -> Lage . "" );                                        //Lage
                    delete_post_meta( $id, "Ausstattung", "" . $miethaus -> Lage . "" );                                 //Ausstattung


                    delete_post_meta( $id, "HeizkostenInWarmmieteEnthalten" );                                            //Heizkosten enthalten?
                    delete_post_meta( $id, "Nebenkosten" );                                                               //Nebenkosten
                    delete_post_meta( $id, "Kaution" );                                                                   //Kaution
                    delete_post_meta( $id, "Warmmiete" );                                                                 //Warmmiete
                    delete_post_meta( $id, "Kaltmiete" );                                                                 //Kaltmiete
                    delete_post_meta( $id, "BefeuerungsArt" );                                                            //Befeuerungsart
                    delete_post_meta( $id, 'kontakt' );                                                                   //Kontakt
                    delete_post_meta( $id, 'zeige_adresse' );                                                             //Adressdruck?
                    delete_post_meta( $id, 'strasse' );                                                                   //Strasse
                    delete_post_meta( $id, 'ort' );                                                                       //Ort
                    delete_post_meta( $id, "Zimmer" );                                                                    //Zimmer
                    delete_post_meta( $id, "AnzahlSchlafzimmer" );                                                        //AnzahlSchlafzimmer
                    delete_post_meta( $id, "AnzahlBadezimmer" );                                                          //AnzahlBadezimmer
                    delete_post_meta( $id, "Wohnflaeche" );                                                               //Wohnfläche
                    delete_post_meta( $id, "Nutzflaeche" );                                                               //Nutzfläche
                    delete_post_meta( $id, "Etage" );                                                                     //Etage
                    delete_post_meta( $id, "Etagenzahl" );                                                                //Etagenzahl
                    delete_post_meta( $id, "WohnungKategorie" );                                                          //WohnKategorie
                    delete_post_meta( $id, "FreiAb" );                                                                    //FreiAb
                    delete_post_meta( $id, "Ausstattungsqualitaet" );                                                     //Qualität
                    delete_post_meta( $id, "GaesteWC" );                                                                  //GästeWC
                    delete_post_meta( $id, "Gartenbenutzung" );                                                           //Gartenbenutzung
                    delete_post_meta( $id, "BetreutesWohnen" );                                                           //BetreutesWohnen
                    delete_post_meta( $id, "Foerderung" );                                                                //Foerderung
                    delete_post_meta( $id, "Rollstuhlgerecht" );                                                          //Rollstuhlgerecht
                    delete_post_meta( $id, "Einbaukueche" );                                                              //Einbaukueche
                    delete_post_meta( $id, "Provisionspflichtig" );                                                       //Prov.Pflicht
                    delete_post_meta( $id, "Objektzustand" );                                                             //Objektzustand
                    delete_post_meta( $id, "Barrierefrei" );                                                              //Barrierefrei
                    delete_post_meta( $id, "Provisionshinweis" );                                                         //Prov. Hinweis
                    delete_post_meta( $id, "Provision" );                                                                 //Provision
                    delete_post_meta( $id, "Keller" );                                                                    //Keller
                    delete_post_meta( $id, "BalkonTerrasse" );                                                            //BalkonTerrasse
                    delete_post_meta( $id, "Haustiere" );                                                                 //Haustiere
                    delete_post_meta( $id, "Aufzug" );                                                                    //Aufzug

                    //Bilder löschen
                    foreach ( $miethaus -> MultimediaAnhang as $bild ) {
                        if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                            unlink( WPI_UPLOAD_URL . $bild[ 'Dateiname' ] . "" );
                        } //Ende IF $bild
                    } //Ende foreach MultimediaAnhang

                    echo 'Die Immobilie ' . $id . ' erfolgreich gelöscht<br />';
                }
            } // Ende Foreach -- MietHaus
        // break;
        case ( $xml_array -> Anbieter -> HausKauf ):
            foreach ( $xml_array -> Anbieter -> HausKauf as $kaufhaus ) {
                $sqid = 'SELECT ID FROM ' . $table_prefix . 'posts WHERE post_title = "' . $kaufhaus -> Adresse[ "Ort" ] . '-' . $kaufhaus[ "Ueberschrift" ] . '"';
                $id   = $wpdb -> get_results( $sqid );
                if ( $id ):
                    $id    = $id[ 0 ] -> ID;
                    $up_id = array(
                        'ID'           => $id,
                        'post_content' => '<h3>Objektbeschreibung</h3><p>' . $kaufhaus -> Objektbeschreibung . '</p>
								        <h3>Lage</h3><p>' . $kaufhaus -> Lage . '</p>
								        <h3>Ausstattung</h3><p>' . $kaufhaus -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'    => '' . $kaufhaus[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'   => '' . $kaufhaus -> Adresse[ 'Ort' ] . '-' . $kaufhaus[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'  => 'publish'
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                    );
                endif;
                if ( $kaufhaus[ 'Importmodus' ] == 'aktualisieren' ) {
                    $immo  = array(
                        'ID'             => '',
                        //[ <post id> ] Are you updating an existing post?
                        'post_content'   => '<h3>Objektbeschreibung</h3><p>' . $kaufhaus -> Objektbeschreibung . '</p>
									        <h3>Lage</h3><p>' . $kaufhaus -> Lage . '</p>
									        <h3>Ausstattung</h3><p>' . $kaufhaus -> Ausstattung . '</p>',
                        //[ <string> ] // The full text of the post.
                        'post_name'      => '' . $kaufhaus[ 'AnbieterObjektId' ] . '',
                        //[ <string> ] // The name (slug) for your post
                        'post_title'     => '' . $kaufhaus -> Adresse[ 'Ort' ] . '-' . $kaufhaus[ 'Ueberschrift' ] . '',
                        //[ <string> ] // The title of your post.
                        'post_status'    => 'publish',
                        //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                        'post_type'      => 'wpi_immobilie',
                        //[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] Default 'post'.
//  			'post_author'    => '',//[ <user ID> ] The user ID number of the author. Default is the current user ID.
//  			'ping_status'    => '',//[ 'closed' | 'open' ] Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//  			'post_parent'    => '',//[ <post ID> ] Sets the parent of the new post, if any. Default 0.
//  			'menu_order'     => '',//[ <order> ] If new post is a page, sets the order in which it should appear in supported menus. Default 0.
//  			'to_ping'        => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
//  			'pinged'         => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//  			'post_password'  => '',//[ <string> ] Password for post, if any. Default empty string.
//  			'guid'           => '',// Skip this and let Wordpress handle it, usually.
//  			'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
                        'post_excerpt'   => $kaufhaus -> Objektbeschreibung,
                        //[ <string> ] For all your post excerpt needs.
//  			            'post_date'     => date('Y-M-d h:i:s'),//[ Y-m-d H:i:s ] The time post was made.
//  			'post_date_gmt'  => '',//[ Y-m-d H:i:s ] The time post was made, in GMT.
                        'comment_status' => 'closed',
                        //[ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
                        'post_category'  => '',
                        //[ array(<category id>, ...) ] Default empty.
                        'tags_input'     => '',
                        //[ '<tag>, <tag>, ...' | array ] Default empty.
                        'tax_input'      => array( 'objekttyp' => 'Wohnung' ),
                        //[ array( <taxonomy> => <array | string> ) ] For custom taxonomies. Default empty.
                        'page_template'  => ''
                        //[ <string> ] Default empty.
                    );
                    $error = false;
                    if ( empty( $id ) ) {
                        $post_id = wp_insert_post( $immo, $error );
                        wp_set_object_terms( $post_id, 'Haus', 'objekttyp', false );
                        wp_set_object_terms( $post_id, 'Kauf', 'vermarktungsart', false );
                        if ( ! $post_id ) {
                            $fehler ++;
                        }

                        add_post_meta( $post_id, "Beschreibung", $kaufhaus -> Objektbeschreibung ? $kaufhaus -> Objektbeschreibung : '' );            //Beschreibung
                        add_post_meta( $post_id, "Lage", $kaufhaus -> Lage ? $kaufhaus -> Lage : '' );                                  //Lage
                        add_post_meta( $post_id, "Ausstattung", $kaufhaus -> Ausstattung ? $kaufhaus -> Ausstattung : '' );                    //Ausstattung

                        add_post_meta( $post_id, "HeizkostenInWarmmieteEnthalten",
                            $kaufhaus -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] ? $kaufhaus -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] : '' );                      //Heizkosten enthalten?

                        add_post_meta( $post_id, "Nebenkosten", $kaufhaus -> Mietpreise[ 'Nebenkosten' ] ? $kaufhaus -> Mietpreise[ 'Nebenkosten' ] : '' );      //Nebenkosten
                        add_post_meta( $post_id, "Kaution", $kaufhaus -> Mietpreise[ 'Kaution' ] ? $kaufhaus -> Mietpreise[ 'Kaution' ] : '' );              //Kaution
                        add_post_meta( $post_id, "Warmmiete", $kaufhaus -> Mietpreise[ 'Warmmiete' ] ? $kaufhaus -> Mietpreise[ 'Warmmiete' ] : '' );          //Warmmiete
                        add_post_meta( $post_id, "Kaltmiete", $kaufhaus -> Mietpreise[ 'Kaltmiete' ] ? $kaufhaus -> Mietpreise[ 'Kaltmiete' ] : '' );          //Kaltmiete
                        add_post_meta( $post_id, "BefeuerungsArt", $kaufhaus -> BefeuerungsArt ? $kaufhaus -> BefeuerungsArt : '' );              //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $kaufhaus -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $kaufhaus -> Kontaktperson[ 'Vorname' ] . " "
                            . $kaufhaus -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $kaufhaus -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $kaufhaus -> Kontaktperson[ 'EMail' ] . "";
                        add_post_meta( $post_id, 'kontakt', $kontakt );
                        $adressdruck = $kaufhaus[ 'Adressdruck' ] ? $kaufhaus[ 'Adressdruck' ] : '';
                        add_post_meta( $post_id, 'zeige_adresse', $adressdruck );                                         //Adressdruck?
                        $strasse = "" . $kaufhaus -> Adresse[ 'Strasse' ] . " " . $kaufhaus -> Adresse[ 'Hausnummer' ] . "";
                        add_post_meta( $post_id, 'strasse', $strasse );                                                   //Strasse
                        $ort = "" . $kaufhaus -> Adresse[ 'Postleitzahl' ] . " " . $kaufhaus -> Adresse[ 'Ort' ] . "";
                        add_post_meta( $post_id, 'ort', $ort );
                        //Ort
                        add_post_meta( $post_id, "Zimmer", $kaufhaus[ 'Zimmer' ] ? $kaufhaus[ 'Zimmer' ] : '' );                            //Zimmer
                        add_post_meta( $post_id, "AnzahlSchlafzimmer", $kaufhaus[ 'AnzahlSchafzimmer' ] ? $kaufhaus[ 'AnzahlSchafzimmer' ] : '' );     //AnzahlSchlafzimmer
                        add_post_meta( $post_id, "AnzahlBadezimmer", $kaufhaus[ 'AnzahlBadezimmer' ] ? $kaufhaus[ 'AnzahlBadezimmer' ] : '' );        //AnzahlBadezimmer
                        add_post_meta( $post_id, "Wohnflaeche", $kaufhaus[ 'Wohnflaeche' ] ? $kaufhaus[ 'Wohnflaeche' ] : '' );                  //Wohnfläche
                        add_post_meta( $post_id, "Nutzflaeche", $kaufhaus[ 'Nutzflaeche' ] ? $kaufhaus[ 'Nutzflaeche' ] : '' );                  //Nutzfläche
                        add_post_meta( $post_id, "Etage", "" . $kaufhaus[ 'Etage' ] . "" );                              //Etage
                        add_post_meta( $post_id, "Etagenzahl", "" . $kaufhaus[ 'Etagenzahl' ] . "" );                    //Etagenzahl
                        add_post_meta( $post_id, "WohnungKategorie", "" . $kaufhaus[ 'WohnungKategorie' ] . "" );        //WohnKategorie
                        add_post_meta( $post_id, "FreiAb", "" . $kaufhaus[ 'FreiAb' ] . "" );                            //FreiAb
                        add_post_meta( $post_id, "Ausstattungsqualitaet", "" . $kaufhaus[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        add_post_meta( $post_id, "GaesteWC", "" . $kaufhaus[ 'GaesteWC' ] . "" );                        //GästeWC
                        add_post_meta( $post_id, "Gartenbenutzung", "" . $kaufhaus[ 'Gartenbenutzung' ] . "" );          //Gartenbenutzung
                        add_post_meta( $post_id, "BetreutesWohnen", "" . $kaufhaus[ 'BetreutesWohnen' ] . "" );          //BetreutesWohnen
                        add_post_meta( $post_id, "Foerderung", "" . $kaufhaus[ 'Foerderung' ] . "" );                    //Foerderung
                        add_post_meta( $post_id, "Rollstuhlgerecht", "" . $kaufhaus[ 'Rollstuhlgerecht' ] . "" );        //Rollstuhlgerecht
                        add_post_meta( $post_id, "Einbaukueche", "" . $kaufhaus[ 'Einbaukueche' ] . "" );                //Einbaukueche
                        add_post_meta( $post_id, "Provisionspflichtig", "" . $kaufhaus[ 'Provisionspflichtig' ] . "" );  //Prov.Pflicht
                        add_post_meta( $post_id, "Objektzustand", "" . $kaufhaus[ 'Objektzustand' ] . "" );              //Objektzustand
                        add_post_meta( $post_id, "Barrierefrei", "" . $kaufhaus[ 'Barrierefrei' ] . "" );                //Barrierefrei
                        add_post_meta( $post_id, "Provisionshinweis", "" . $kaufhaus[ 'Provisionshinweis' ] . "" );      //Prov. Hinweis
                        add_post_meta( $post_id, "Provision", "" . $kaufhaus[ 'Provision' ] . "" );                      //Provision
                        add_post_meta( $post_id, "Keller", "" . $kaufhaus[ 'Keller' ] . "" );                            //Keller
                        add_post_meta( $post_id, "BalkonTerrasse", "" . $kaufhaus[ 'BalkonTerrasse' ] . "" );            //BalkonTerrasse
                        add_post_meta( $post_id, "Haustiere", "" . $kaufhaus[ 'Haustiere' ] . "" );                      //Haustiere
                        add_post_meta( $post_id, "Aufzug", "" . $kaufhaus[ 'Aufzug' ] . "" );                            //Aufzug
                        foreach ( $kaufhaus -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            add_post_meta( $post_id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;
                        echo 'Immobilie wurde hinzugefügt<br />';
                    }//Ende if-!isset ID
                    else {
                        $update = wp_update_post( $up_id );
                        if ( $update == 0 ) {
                            $fehler ++;
                        }
                        update_post_meta( $id, "Beschreibung", "" . $kaufhaus -> Objektbeschreibung . "" );              //Beschreibung
                        update_post_meta( $id, "Lage", "" . $kaufhaus -> Lage . "" );                                    //Lage
                        update_post_meta( $id, "Ausstattung", "" . $kaufhaus -> Ausstattung . "" );                      //Ausstattung

                        update_post_meta( $id, "HeizkostenInWarmmieteEnthalten",
                            "" . $kaufhaus -> Mietpreise[ 'HeizkostenInWarmmieteEnthalten' ] . "" );                      //Heizkosten enthalten?
                        update_post_meta( $id, "Nebenkosten", "" . $kaufhaus -> Mietpreise[ 'Nebenkosten' ] . "" );        //Nebenkosten
                        update_post_meta( $id, "Kaution", "" . $kaufhaus -> Mietpreise[ 'Kaution' ] . "" );                //Kaution
                        update_post_meta( $id, "Warmmiete", "" . $kaufhaus -> Mietpreise[ 'Warmmiete' ] . "" );            //Warmmiete
                        update_post_meta( $id, "Kaltmiete", "" . $kaufhaus -> Mietpreise[ 'Kaltmiete' ] . "" );            //Kaltmiete
                        update_post_meta( $id, "BefeuerungsArt", "" . $kaufhaus -> BefeuerungsArt . "" );                //Befeuerungsart
                        // Kontaktinformationen als Meta														        //Kontaktinfo
                        $kontakt = "Anrede: " . $kaufhaus -> Kontaktperson[ 'Anrede' ] . "
													Name: " . $kaufhaus -> Kontaktperson[ 'Vorname' ] . " "
                            . $kaufhaus -> Kontaktperson[ 'Nachname' ] . "
													Tel: " . $kaufhaus -> Kontaktperson[ 'Mobiltelefon' ] . "
													Email: " . $kaufhaus -> Kontaktperson[ 'EMail' ] . "";
                        update_post_meta( $id, 'kontakt', $kontakt );
                        $adressdruck = "" . $kaufhaus[ 'Adressdruck' ] . "";
                        update_post_meta( $id, 'zeige_adresse', $adressdruck );                                           //Adressdruck?
                        $strasse = "" . $kaufhaus -> Adresse[ 'Strasse' ] . " " . $kaufhaus -> Adresse[ 'Hausnummer' ] . "";
                        update_post_meta( $id, 'strasse', $strasse );                                                     //Strasse
                        $ort = "" . $kaufhaus -> Adresse[ 'Postleitzahl' ] . " " . $kaufhaus -> Adresse[ 'Ort' ] . "";
                        update_post_meta( $id, 'ort', $ort );                                                             //Ort
                        update_post_meta( $id, "Zimmer", "" . $kaufhaus[ 'Zimmer' ] . "" );                              //Zimmer
                        update_post_meta( $id, "AnzahlSchlafzimmer", "" . $kaufhaus[ 'AnzahlSchafzimmer' ] . "" );       //AnzahlSchlafzimmer
                        update_post_meta( $id, "AnzahlBadezimmer", "" . $kaufhaus[ 'AnzahlBadezimmer' ] . "" );          //AnzahlBadezimmer
                        update_post_meta( $id, "Wohnflaeche", "" . $kaufhaus[ 'Wohnflaeche' ] . "" );                    //Wohnfläche
                        update_post_meta( $id, "Nutzflaeche", "" . $kaufhaus[ 'Nutzflaeche' ] . "" );                    //Nutzfläche
                        update_post_meta( $id, "Etage", "" . $kaufhaus[ 'Etage' ] . "" );                                //Etage
                        update_post_meta( $id, "Etagenzahl", "" . $kaufhaus[ 'Etagenzahl' ] . "" );                      //Etagenzahl
                        update_post_meta( $id, "WohnungKategorie", "" . $kaufhaus[ 'WohnungKategorie' ] . "" );          //WohnKategorie
                        update_post_meta( $id, "FreiAb", "" . $kaufhaus[ 'FreiAb' ] . "" );                              //FreiAb
                        update_post_meta( $id, "Ausstattungsqualitaet", "" . $kaufhaus[ 'Ausstattungsqualitaet' ] . "" );//Qualität
                        update_post_meta( $id, "GaesteWC", "" . $kaufhaus[ 'GaesteWC' ] . "" );                          //GästeWC
                        update_post_meta( $id, "Gartenbenutzung", "" . $kaufhaus[ 'Gartenbenutzung' ] . "" );            //Gartenbenutzung
                        update_post_meta( $id, "BetreutesWohnen", "" . $kaufhaus[ 'BetreutesWohnen' ] . "" );            //BetreutesWohnen
                        update_post_meta( $id, "Foerderung", "" . $kaufhaus[ 'Foerderung' ] . "" );                      //Foerderung
                        update_post_meta( $id, "Rollstuhlgerecht", "" . $kaufhaus[ 'Rollstuhlgerecht' ] . "" );          //Rollstuhlgerecht
                        update_post_meta( $id, "Einbaukueche", "" . $kaufhaus[ 'Einbaukueche' ] . "" );                  //Einbaukueche
                        update_post_meta( $id, "Provisionspflichtig", "" . $kaufhaus[ 'Provisionspflichtig' ] . "" );    //Prov.Pflicht
                        update_post_meta( $id, "Objektzustand", "" . $kaufhaus[ 'Objektzustand' ] . "" );                //Objektzustand
                        update_post_meta( $id, "Barrierefrei", "" . $kaufhaus[ 'Barrierefrei' ] . "" );                  //Barrierefrei
                        update_post_meta( $id, "Provisionshinweis", "" . $kaufhaus[ 'Provisionshinweis' ] . "" );        //Prov. Hinweis
                        update_post_meta( $id, "Provision", "" . $kaufhaus[ 'Provision' ] . "" );                        //Provision
                        update_post_meta( $id, "Keller", "" . $kaufhaus[ 'Keller' ] . "" );                              //Keller
                        update_post_meta( $id, "BalkonTerrasse", "" . $kaufhaus[ 'BalkonTerrasse' ] . "" );              //BalkonTerrasse
                        update_post_meta( $id, "Haustiere", "" . $kaufhaus[ 'Haustiere' ] . "" );                        //Haustiere
                        update_post_meta( $id, "Aufzug", "" . $kaufhaus[ 'Aufzug' ] . "" );                              //Aufzug
                        foreach ( $kaufhaus -> MultimediaAnhang as $bild ) {
                            if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                                $img      = true;
                                $bilder[] = "" . $bild[ 'Dateiname' ] . "";
                            } //Ende IF $bild
                        } //Ende foreach MultimediaAnhang
                        if ( $img != false ):
                            $bilder = implode( '; ', $bilder );
                            update_post_meta( $id, "Bilder", $bilder );                                                 //Bilder
                            unset( $bilder );
                        endif;                              //Bilder
                        /*
                                        // Prepare an array of post data for the attachment.
                                        $filename = $bilder['0'];
                                        $filetype = wp_check_filetype( basename( $filename ), null );
                                        $attachment = array(
                                            'guid'           => $filename,
                                            'post_mime_type' => $filetype['type'],
                                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                            'post_content'   => '',
                                            'post_status'    => 'inherit'
                                        );

                                        // Update the attachment.
                                        $attach_id = wp_insert_attachment( $attachment, $filename, $id );
                                        wp_update_attachment_metadata( $attach_id,  $attach_data );

                        */
                        echo 'Die Immobilie ' . $id . ' wurde aktualisiert! <br />';
                    }
                } //Ende IF Importmodus Aktualisieren
                elseif ( $kaufhaus[ 'Importmodus' ] == 'loeschen' ) {
                    $delete = wp_delete_post( $id );
                    if ( ! $delete ) {
                        $fehler ++;
                    }
                    delete_post_meta( $id, "Beschreibung", "" . $kaufhaus -> Objektbeschreibung . "" );                  //Beschreibung
                    delete_post_meta( $id, "Lage", "" . $kaufhaus -> Lage . "" );                                        //Lage
                    delete_post_meta( $id, "Ausstattung", "" . $kaufhaus -> Lage . "" );                                 //Ausstattung


                    delete_post_meta( $id, "HeizkostenInWarmmieteEnthalten" );                                            //Heizkosten enthalten?
                    delete_post_meta( $id, "Nebenkosten" );                                                               //Nebenkosten
                    delete_post_meta( $id, "Kaution" );                                                                   //Kaution
                    delete_post_meta( $id, "Warmmiete" );                                                                 //Warmmiete
                    delete_post_meta( $id, "Kaltmiete" );                                                                 //Kaltmiete
                    delete_post_meta( $id, "BefeuerungsArt" );                                                            //Befeuerungsart
                    delete_post_meta( $id, 'kontakt' );                                                                   //Kontakt
                    delete_post_meta( $id, 'zeige_adresse' );                                                             //Adressdruck?
                    delete_post_meta( $id, 'strasse' );                                                                   //Strasse
                    delete_post_meta( $id, 'ort' );                                                                       //Ort
                    delete_post_meta( $id, "Zimmer" );                                                                    //Zimmer
                    delete_post_meta( $id, "AnzahlSchlafzimmer" );                                                        //AnzahlSchlafzimmer
                    delete_post_meta( $id, "AnzahlBadezimmer" );                                                          //AnzahlBadezimmer
                    delete_post_meta( $id, "Wohnflaeche" );                                                               //Wohnfläche
                    delete_post_meta( $id, "Nutzflaeche" );                                                               //Nutzfläche
                    delete_post_meta( $id, "Etage" );                                                                     //Etage
                    delete_post_meta( $id, "Etagenzahl" );                                                                //Etagenzahl
                    delete_post_meta( $id, "WohnungKategorie" );                                                          //WohnKategorie
                    delete_post_meta( $id, "FreiAb" );                                                                    //FreiAb
                    delete_post_meta( $id, "Ausstattungsqualitaet" );                                                     //Qualität
                    delete_post_meta( $id, "GaesteWC" );                                                                  //GästeWC
                    delete_post_meta( $id, "Gartenbenutzung" );                                                           //Gartenbenutzung
                    delete_post_meta( $id, "BetreutesWohnen" );                                                           //BetreutesWohnen
                    delete_post_meta( $id, "Foerderung" );                                                                //Foerderung
                    delete_post_meta( $id, "Rollstuhlgerecht" );                                                          //Rollstuhlgerecht
                    delete_post_meta( $id, "Einbaukueche" );                                                              //Einbaukueche
                    delete_post_meta( $id, "Provisionspflichtig" );                                                       //Prov.Pflicht
                    delete_post_meta( $id, "Objektzustand" );                                                             //Objektzustand
                    delete_post_meta( $id, "Barrierefrei" );                                                              //Barrierefrei
                    delete_post_meta( $id, "Provisionshinweis" );                                                         //Prov. Hinweis
                    delete_post_meta( $id, "Provision" );                                                                 //Provision
                    delete_post_meta( $id, "Keller" );                                                                    //Keller
                    delete_post_meta( $id, "BalkonTerrasse" );                                                            //BalkonTerrasse
                    delete_post_meta( $id, "Haustiere" );                                                                 //Haustiere
                    delete_post_meta( $id, "Aufzug" );                                                                    //Aufzug

                    //Bilder löschen
                    foreach ( $kaufhaus -> MultimediaAnhang as $bild ) {
                        if ( $bild[ 'AnhangArt' ] == 'bild' ) {
                            unlink( WPI_UPLOAD_URL . $bild[ 'Dateiname' ] . "" );
                        } //Ende IF $bild
                    } //Ende foreach MultimediaAnhang

                    echo 'Die Immobilie ' . $id . ' erfolgreich gelöscht<br />';
                }
            } // Ende Foreach -- KaufHaus
        // break;
        default:
            break;
    }

}

/***************************************/
/** Erstellen, Ändern und Löschen der **/
/**** Immobilien im OpenImmo Format ****/
/***************************************/
function wpi_xml_openimmo() {

    //TODO registrierung der Meta siehe register_meta
    global $wpdb;
    global $table_prefix;
    $fehler = 0;

    $xml_array = $GLOBALS[ 'xml_array' ];
    if ( $xml_array ):
        foreach ( $xml_array as $xml ) {
            // Globale Daten für alle Immobilien
            @$anbieterkennung = $xml[ 'anbieter' ][ 'anbieternr' ];
            @$anbieterfirma = $xml[ 'anbieter' ][ 'firma' ];
            // Alle Immobilien im Array
            $immobilie = $xml[ 'anbieter' ][ 'immobilie' ];

            // Schleife durch das Immobilienarray
            foreach ( $immobilie as $objekt ) {

                /************************************************************
                 *************Festlegen der Variablen und Arrays ************
                 ***********************************************************/

                // Falls übertragung nur einer einzelnen Immobilie
                if ( ! array_key_exists( '0', $immobilie ) ) {
                    $objekt = $immobilie;
                }

                // Objektkategorie
                @$objekt_kategorie = $objekt[ 'objektkategorie' ];
                @$objekt_kategorie_string = serialize( array_filter( $objekt_kategorie ) );

                // Geografische Daten
                @$geo = array_filter( $objekt[ 'geo' ] );
                @$geo_string = serialize( $geo );

                // Kontaktperson als Serialisiertes Array zum String
                @$kontaktperson = $objekt[ 'kontaktperson' ];
                @$kontaktperson_string = serialize( array_filter( $kontaktperson ) );

                // Preise gefiltert und serialisiert
                @$preise = array_filter( $objekt[ 'preise' ] );
                @$preise_string = serialize( $preise );

                // Flaechen gefilter und serialisiert
                @$flaechen = array_filter( $objekt[ 'flaechen' ] );
                @$flaechen_string = serialize( $flaechen );

                @$ausstattung = $objekt[ 'ausstattung' ];
                @$ausstattung_string = serialize( $ausstattung );

                @$zustand = $objekt[ 'zustand_angaben' ];
                @$zustand_string = serialize( $zustand );

                @$anhaenge = $objekt[ 'anhaenge' ];
                @$anhaenge_string = serialize( $anhaenge );

                @$freitexte = $objekt[ 'freitexte' ];
                @$freitexte_string = serialize( $freitexte );

                @$objektverwaltung = $objekt[ 'verwaltung_objekt' ];
                @$objektverwaltung_string = serialize( $objektverwaltung );

                @$verwaltung = $objekt[ 'verwaltung_techn' ];
                @$verwaltung_string = serialize( $verwaltung );


                // Nutzungsart
                @$objekttyp = $objekt_kategorie[ 'nutzungsart' ][ '@attributes' ];
                if ( array_search( 'true', $objekttyp ) ):
                    @$nutzungsart = array_search( 'true', $objekttyp );
                elseif ( array_search( '1', $objekttyp ) ):
                    @$nutzungsart = array_search( '1', $objekttyp );
                endif;

                // Vermarktungsart
                @$vermarktung = $objekt_kategorie[ 'vermarktungsart' ][ '@attributes' ];
                if ( strtolower( array_search( 'true', $vermarktung ) ) ):
                    @$vermarktungsart = strtolower( array_search( 'true', $vermarktung ) );
                elseif ( array_search( '1', $vermarktung ) ):
                    @$vermarktungsart = array_search( '1', $vermarktung );
                endif;

                // Objektart
                @$objektart = $objekt_kategorie[ 'objektart' ];
                @$objektart = array_keys( $objektart );

                // Immobiliengruppe
                @$immogruppe = $objekt[ 'verwaltung_objekt' ][ 'gruppennummer' ];


                // Befeuerungsart
                if ( isset( $objekt -> ausstattung -> befeuerung ) ):
                    // Befeuerungsart
                    $befeuerung = (array) $objekt -> ausstattung -> befeuerung;
                    $befeuerung = $befeuerung[ '@attributes' ];
                    $befeuerung = array_search( 'true', $befeuerung );
                endif;
                $befeuerungsart = @$befeuerung ? : '';

                // Heizungsart
                if ( isset( $objekt -> ausstattung -> heizungsart ) ):
                    // Heizungsart
                    $heizung = (array) $objekt -> ausstattung -> heizungsart;
                    $heizung = $heizung[ '@attributes' ];
                    $heizung = array_search( 'true', $heizung );
                endif;
                $heizungsart = @$heizung ? : '';

                if ( isset( $objekt -> ausstattung -> bad ) ):
                    // Badausstattung
                    $bad = (array) $objekt -> ausstattung -> bad;
                    $bad = $bad[ '@attributes' ];
                    $bad = array_keys( $bad );
                endif;
                //$badausstatt = @implode( ', ', $bad ) ? : '';

                if ( isset( $objekt -> ausstattung -> boden ) ):
                    // Boden
                    $boden = (array) $objekt -> ausstattung -> boden;
                    $boden = $boden[ '@attributes' ];
                    $boden = array_keys( $boden );
                endif;
                //$bodenart = @implode( ', ', $boden ) ? : '';

                if ( isset( $objekt -> ausstattung -> stellplatzart ) ):
                    // Stellplatz
                    $stellplatz = (array) $objekt -> ausstattung -> stellplatzart;
                    $stellplatz = $stellplatz[ '@attributes' ];
                    $stellplatz = array_keys( $stellplatz );
                endif;
                //$stellplatzart = @implode( ', ', $stellplatz ) ? : '';

                if ( isset( $objekt -> ausstattung -> dachform ) ):
                    // Dachform
                    $dach = (array) $objekt -> ausstattung -> dachform;
                    $dach = $dach[ '@attributes' ];
                    $dach = array_keys( $dach );
                endif;
                //$dachform = @implode( ', ', $dach ) ? : '';

                if ( isset( $objekt -> ausstattung -> bauweise ) ):
                    // Bauweise
                    $bau = (array) $objekt -> ausstattung -> bauweise;
                    $bau = $bau[ '@attributes' ];
                    $bau = array_keys( $bau );
                endif;
                //$bauweise = @implode( ', ', $bau ) ? : '';

                // POST bezogene Variablen

                // Content
                $postContent = '';
                foreach ( $objekt[ 'freitexte' ] as $key => $value ) {
                    if ( ! is_array( $objekt[ 'freitexte' ][ $key ] ) ) {
                        $postContent .= '<div id="' . $key . '"><h3>' . ucfirst( $key ) . '</h3></div>';
                        $postContent .= '<p>' . nl2br( $value ) . '</p>';
                    } elseif ( is_array( $objekt[ 'freitexte' ][ $key ] ) ) {
                        $postContent .= '<div id="' . $key . '">';
                        foreach ( $objekt[ 'freitexte' ][ $key ] as $value ) {
                            $postContent .= '<p>' . nl2br( $value ) . '</p>';
                        }
                        $postContent .= '</div>';
                    }
                }
                // Title

                $postName = esc_attr( $objekt[ 'verwaltung_techn' ][ 'objektnr_extern' ] . '-' . $objekt[ 'geo' ][ 'ort' ] );
                if ( get_option( 'wpi_place_to_title' ) === 'true' ) {
                    $postTitle = esc_html( $objekt[ 'geo' ][ 'ort' ] . '-' . $objekt[ 'freitexte' ][ 'objekttitel' ] );
                } else {
                    $postTitle = esc_html( $objekt[ 'freitexte' ][ 'objekttitel' ] );
                }
                $postExcerpt = esc_html( $objekt[ 'freitexte' ][ 'objektbeschreibung' ] ) ? : '';

                // Verarbeitung der Immobilie
                switch ( isset( $objekt ) ) {

                    default:
                        /*Anlegen der Immobilie*/
                        // Falls Immobilie bereits existiert ID auslesen
                        //$sqid = 'SELECT ID FROM ' . $table_prefix . 'posts WHERE post_title = "' . $postTitle . '"';
                        //$id   = $wpdb -> get_results( $sqid );

                        // Falls Immobilie bereits existiert ID auslesen
                        $sqid = $wpdb -> prepare(
                            'SELECT post_id FROM `' . $table_prefix . 'postmeta` WHERE `meta_key` LIKE %s AND `meta_value` = %s', 'wpi_objectnr',
                            esc_attr( $objekt[ 'verwaltung_techn' ][ 'objektnr_extern' ] ) );
                        $id   = $wpdb -> get_results( $sqid );
                        //@$id = $id[ 0 ];

                        if ( $id ):
                            //zeigen($id);
                            $id    = $id[ 0 ] -> post_id;
                            $up_id = array(
                                'ID'           => $id,
                                'post_content' => $postContent,
                                //[ <string> ] // The full text of the post.
                                'post_name'    => $postName,
                                //[ <string> ] // The name (slug) for your post
                                'post_title'   => $postTitle,
                                //[ <string> ] // The title of your post.
                                'post_status'  => 'publish',
                                //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                            );
                        endif;
                        // Anlegen bzw. Ändern der Immobilie
                        if ( $objekt[ 'verwaltung_techn' ][ 'aktion' ][ '@attributes' ][ 'aktionart' ] == 'CHANGE' ) {

                            $immo = array(
                                'ID'             => '',
                                //[ <post id> ] Are you updating an existing post?
                                'post_content'   => $postContent,
                                //[ <string> ] // The full text of the post.
                                'post_name'      => $postName,
                                //[ <string> ] // The name (slug) for your post
                                'post_title'     => $postTitle,
                                //[ <string> ] // The title of your post.
                                'post_status'    => 'publish',
                                //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // 					Default 'draft'.
                                'post_type'      => 'wpi_immobilie',
                                //[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] Default 'post'.
//  			                'post_author'    => '',//[ <user ID> ] The user ID number of the author. Default is the current user ID.
//  			                'ping_status'    => '',//[ 'closed' | 'open' ] Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//  			                'post_parent'    => '',//[ <post ID> ] Sets the parent of the new post, if any. Default 0.
//  			                'menu_order'     => '',//[ <order> ] If new post is a page, sets the order in which it should appear in supported menus. Default 0.
//  			                'to_ping'        => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
//  			                'pinged'         => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//  			                'post_password'  => '',//[ <string> ] Password for post, if any. Default empty string.
//  			                'guid'           => '',// Skip this and let Wordpress handle it, usually.
//  			                'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
                                'post_excerpt'   => $postExcerpt,
                                //[ <string> ] For all your post excerpt needs.
//  			                'post_date'     => date('Y-M-d h:i:s'),//[ Y-m-d H:i:s ] The time post was made.
//  			                'post_date_gmt'  => '',//[ Y-m-d H:i:s ] The time post was made, in GMT.
                                'comment_status' => 'closed',
                                //[ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
                                'post_category'  => '',
                                //[ array(<category id>, ...) ] Default empty.
                                'tags_input'     => '',
                                //[ '<tag>, <tag>, ...' | array ] Default empty.
                                //'tax_input' => array('objekttyp' => $objektart), //[ array( <taxonomy> => <array | string> ) ] For custom taxonomies. Default empty.
                                'page_template'  => '',
                                //[ <string> ] Default empty.
                            );

                            $error = false;

                            if ( empty( $id ) ) {

                                $post_id = wp_insert_post( $immo, $error );

                                wp_set_object_terms( $post_id, $objektart, 'objekttyp', false );
                                wp_set_object_terms( $post_id, $vermarktungsart, 'vermarktungsart', false );
                                if ( ! empty( $immogruppe ) && get_option( 'wpi_pro' ) == 'true' ):
                                    wp_set_object_terms( $post_id, $immogruppe, 'immobiliengruppe', false );
                                endif;
                                if ( ! $post_id ) {
                                    $fehler ++;
                                }

                                // Versteckte Post-Meta
                                add_post_meta( $post_id, "wpi_objectnr", esc_attr( $objekt[ 'verwaltung_techn' ][ 'objektnr_extern' ] ) );
                                add_post_meta( $post_id, "anbieterkennung", $anbieterkennung );
                                add_post_meta( $post_id, "anbieterfirma", $anbieterfirma );
                                add_post_meta( $post_id, "objektkategorie", $objekt_kategorie );
                                add_post_meta( $post_id, "geodaten", $geo );
                                add_post_meta( $post_id, "kontaktperson", $kontaktperson );
                                add_post_meta( $post_id, "preise", $preise );
                                add_post_meta( $post_id, "flaechen", $flaechen );
                                add_post_meta( $post_id, "ausstattung", $ausstattung );
                                add_post_meta( $post_id, "zustand_angaben", $zustand );
                                add_post_meta( $post_id, "anhaenge", $anhaenge );
                                add_post_meta( $post_id, "freitexte", $freitexte );
                                add_post_meta( $post_id, "verwaltung_objekt", $objektverwaltung );
                                add_post_meta( $post_id, "verwaltung_techn", $verwaltung );

                                echo 'Immobilie wurde hinzugefügt<br />';
                            } //Ende if-!isset ID
                            else {

                                wp_set_object_terms( $id, $objektart, 'objekttyp', false );
                                wp_set_object_terms( $id, $vermarktungsart, 'vermarktungsart', false );
                                if ( ! empty( $immogruppe ) && get_option( 'wpi_pro' ) == 'true' ):
                                    wp_set_object_terms( $id, $immogruppe, 'immobiliengruppe', false );
                                endif;


                                $update = wp_update_post( $up_id );


                                // Versteckte Post-Meta
                                update_post_meta( $id, "wpi_objectnr", esc_attr( $objekt[ 'verwaltung_techn' ][ 'objektnr_extern' ] ) );
                                update_post_meta( $id, "anbieterkennung", $anbieterkennung );
                                update_post_meta( $id, "anbieterfirma", $anbieterfirma );
                                update_post_meta( $id, "objektkategorie", $objekt_kategorie );
                                update_post_meta( $id, "geodaten", $geo );
                                update_post_meta( $id, "kontaktperson", $kontaktperson );
                                update_post_meta( $id, "preise", $preise );
                                update_post_meta( $id, "flaechen", $flaechen );
                                update_post_meta( $id, "ausstattung", $ausstattung );
                                update_post_meta( $id, "zustand_angaben", $zustand );
                                update_post_meta( $id, "anhaenge", $anhaenge );
                                update_post_meta( $id, "freitexte", $freitexte );
                                update_post_meta( $id, "verwaltung_objekt", $objektverwaltung );
                                update_post_meta( $id, "verwaltung_techn", $verwaltung );

                                /*
                                                // Prepare an array of post data for the attachment.
                                                $filename = $bilder['0'];
                                                $filetype = wp_check_filetype( basename( $filename ), null );
                                                $attachment = array(
                                                    'guid'           => $filename,
                                                    'post_mime_type' => $filetype['type'],
                                                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                                    'post_content'   => '',
                                                    'post_status'    => 'inherit'
                                                );

                                                // Update the attachment.
                                                $attach_id = wp_insert_attachment( $attachment, $filename, $id );
                                                wp_update_attachment_metadata( $attach_id,  $attach_data );

                                */
                                echo 'Die Immobilie ' . $id . ' wurde aktualisiert! <br />';

                            }
                        } //Ende IF Importmodus Aktualisieren
                        elseif ( $objekt[ 'verwaltung_techn' ][ 'aktion' ][ '@attributes' ][ 'aktionart' ] == 'DELETE' ) {
                            $delete = wp_delete_post( $id );
                            if ( ! $delete ) {
                                $fehler ++;
                            }
                            // Versteckte Post-Meta löschen
                            delete_post_meta( $id, "wpi_objectnr" );
                            delete_post_meta( $id, "anbieterkennung" );
                            delete_post_meta( $id, "anbieterfirma" );
                            delete_post_meta( $id, "objektkategorie" );
                            delete_post_meta( $id, "geodaten" );
                            delete_post_meta( $id, "kontaktperson" );
                            delete_post_meta( $id, "preise" );
                            delete_post_meta( $id, "flaechen" );
                            delete_post_meta( $id, "ausstattung" );
                            delete_post_meta( $id, "zustand_angaben" );
                            delete_post_meta( $id, "anhaenge" );
                            delete_post_meta( $id, "freitexte" );
                            delete_post_meta( $id, "verwaltung_objekt" );
                            delete_post_meta( $id, "verwaltung_techn" ); //Zulieferung
                            is_array( $id ) ? $id = '' : $id = $id;
                            echo 'Die Immobilie ' . strval( $id ) . ' erfolgreich gelöscht<br />';


                        } // Ende Aktionsart DELETE

                        break;
                } // Ende switch
            }// Ende foreach-Schleife $objekt
        }
    endif;
} // Ende Funktion OpenImmo
