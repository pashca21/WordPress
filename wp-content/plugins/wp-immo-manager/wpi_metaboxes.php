<?php
/**
 * METABOXES Top-Immobilien
 *
 */

function setfields()
{
    $felder = array(
        'Top Immobilie' => array(
            'title' => 'Top Immobilie', //Hier Titel eingeben
            'id' => 'topimmo', // hier passende ID eingeben
            'description' => 'Häckchen setzen, um Immobilie als Top-Immobilie zu markieren', // Hier die Beschreibung eingeben
            'type' => 'checkbox', // Was fÃ¼r eine Art Input Feld soll es sein? z.B. "text"
            'posttype' => 'wpi_immobilie', // Zu welchem Seitentyp soll die Metabox hinzugefÃ¼gt werden? z.B. "page" oder "post"
            'loc' => 'side', // Wo soll die Metabox platziert werden? z.B. "side"
            'prio' => 'high'// Die Priorität der Metabox
        ),
        'Verkauft' => array(
            'title' => 'Verkauft', //Hier Titel eingeben
            'id' => 'sold', // hier passende ID eingeben
            'description' => 'Häckchen setzen, um Immobilie als Verkauft zu markieren', // Hier die Beschreibung eingeben
            'type' => 'checkbox', // Was fÃ¼r eine Art Input Feld soll es sein? z.B. "text"
            'posttype' => 'wpi_immobilie', // Zu welchem Seitentyp soll die Metabox hinzugefÃ¼gt werden? z.B. "page" oder "post"
            'loc' => 'side', // Wo soll die Metabox platziert werden? z.B. "side"
            'prio' => 'high'// Die Priorität der Metabox
        ),
        'Reserviert' => array(
            'title' => 'Reserviert', //Hier Titel eingeben
            'id' => 'reserved', // hier passende ID eingeben
            'description' => 'Häckchen setzen, um Immobilie als Reserviert zu markieren', // Hier die Beschreibung eingeben
            'type' => 'checkbox', // Was fÃ¼r eine Art Input Feld soll es sein? z.B. "text"
            'posttype' => 'wpi_immobilie', // Zu welchem Seitentyp soll die Metabox hinzugefÃ¼gt werden? z.B. "page" oder "post"
            'loc' => 'side', // Wo soll die Metabox platziert werden? z.B. "side"
            'prio' => 'high'// Die Priorität der Metabox
        )
    );
    return $felder;
}

/*
*
* FESTLEGEN, DASS DIE METABOXEN WIRKLICH NUR
* IN DEN BEARBEITUNGSMASKEN ERSCHEINEN
*
*/
add_action('load-post.php', 'metabox_setup');
add_action('load-post-new.php', 'metabox_setup');


/* METABOX SETUP FUNCTION */
function metabox_setup()
{
    add_action('add_meta_boxes', 'metabox_init');
    add_action('save_post', 'meta_save', 10, 2);
}

/*
*
* METABOX INIT FUNCTION ZUR
* INITIALISIERUNG DER METABOX
*
*/
function metabox_init()
{
    $felder = setfields();
    foreach ($felder as $feld):
        add_meta_box(
            $feld['id'], // eindeutiger Name der Box
            esc_html__($feld['title'], $feld['id'] . '_title'),    // Titel der Box
            'metabox_markup', // Callback Funktion für die Ausgabe der Box, wird später noch benötigt.
            $feld['posttype'],    // Seitentyp
            $feld['loc'],    // Wo soll die Box angezeigt werden? (normal, advanced und side stehen zur Verfügung)
            $feld['prio'],    // Priorität
            array(
                'name' => $feld['id'],
                'title' => $feld['title'],
                'description' => $feld['description'],
                'type' => $feld['type']
            )
        );
    endforeach;
}


/*
*
* FÜGE DER METABOX DAS CUSTOM FIELD
* HINZU UND MACHE SIE SICHTBAR
*
*/
function metabox_markup($object, $box)
{

    wp_nonce_field(basename(__FILE__), 'metabox_nonce');
    if ($box['args']['type'] == 'text') { ?>
        <p>
            <label
                for="<?php echo $box['args']['name']; ?>-field"><?php _e($box['args']['description'], $box['args']['name'] . '_desc'); ?></label>
            <br/>
            <input class="<?php echo $box['args']['name']; ?>-field" type="<?php echo $box['args']['type']; ?>"
                   name="<?php echo $box['args']['name']; ?>" id="<?php echo $box['args']['name']; ?>-field"
                   value="<?php echo esc_attr(get_post_meta($object->ID, $box['args']['name'], true)); ?>" size="30"/>
        </p>
    <?php } elseif ($box['args']['type'] == 'checkbox') {
        if (esc_attr(get_post_meta($object->ID, $box['args']['name'], true)) == 1) {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        ?>

        <p>
            <label
                for="<?php echo $box['args']['name']; ?>-field"><?php _e($box['args']['description'], $box['args']['name'] . '_desc'); ?></label>
            <br/>
            <input class="<?php echo $box['args']['name']; ?>-field" type="<?php echo $box['args']['type']; ?>"
                   name="<?php echo $box['args']['name']; ?>" id="<?php echo $box['args']['name']; ?>-field" value="1"
                   size="30" <?php echo $checked; ?> /> <?php echo ' '.$box['args']['title']; ?>
        </p>
    <?php } elseif ($box['args']['type'] == 'radio') {
        if (esc_attr(get_post_meta($object->ID, $box['args']['name'], true)) == 1) {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        ?>

        <p>
            <label
                for="<?php echo $box['args']['name']; ?>-field"><?php _e($box['args']['description'], $box['args']['name'] . '_desc'); ?></label>
            <br/>
            <input class="<?php echo $box['args']['name']; ?>-field" type="<?php echo $box['args']['type']; ?>"
                   name="<?php echo $box['args']['name']; ?>" id="<?php echo $box['args']['name']; ?>-field" value="1"
                   size="30" <?php echo $checked; ?> /> <?php echo $box['args']['name'] . '?'; ?>
        </p>
    <?php }
}


/*
*
* FUNKTION FÜR DIE SPEICHERUNG DER BENUTZERDEFINIERTEN FELDER
* BEARBEITUNG DURCH EINZELNE ÜBERPRÜFUNGEN UND FILTERUNGEN ZUM ABSPEICHERN.
*
*/
function meta_save($post_id, $post)
{

    /* HOLE DIE FESTGELEGTEN FELDER REIN */
    $felder = setfields();

    /* ÜBERPRÜFE WP_NONCE VOR DER RESTLICHEN AUSFÜHRUNG DER FUNKTION */
    if (!isset($_POST['metabox_nonce']) || !wp_verify_nonce($_POST['metabox_nonce'], basename(__FILE__)))
        return $post_id;

    /* PRÜFE WELCHER SEITENTYP GENUTZT WIRD */
    $post_type = get_post_type_object($post->post_type);

    /* PRÜFE, OB DER AKTUELL ANGEMELDETE BENUTZER DIE ERFORDERLICHEN RECHTE BESITZT */
    if (!current_user_can($post_type->cap->edit_post, $post_id))
        return $post_id;

    /* EMPFANGE DEN INHALT DES BENUTZERDEFINIERTEN FELDES UND SPEICHERE ES ALS HTML-KLASSE AB */
    $i = 0;
    $new_meta_value = array();
    foreach ($felder as $feld):
        $new_meta_value[$i] = (isset($_POST[$feld['id']]) ? sanitize_html_class($_POST[$feld['id']]) : '');
        $i++;
    endforeach;

    /* GEBE DEN SCHLÜSSEL DES BENUTZERDEFINIERTEN FELDES EIN (NAME DES INPUT FELDES) */
    $i = 0;
    $meta_key = array();
    foreach ($felder as $feld):
        $meta_key[$i] = $feld['id'];
        $i++;
    endforeach;

    /* ERHALTE DEN WERT DES ZUVOR EINGEGEBEN SCHHLÜSSELS */
    $i = 0;
    $meta_value = array();
    foreach ($felder as $feld):
        $meta_value[$i] = get_post_meta($post_id, $meta_key[$i], true);
        $i++;
    endforeach;

    $i = 0;
    foreach ($felder as $feld):
        /* WENN KEINE BISHERIGEN WERTE ZU DEM SCHLÜSSEL EXISTIERTEN, FÜGE DEN NEU EINGEGEBENEN EIN. */
        if ($new_meta_value[$i] && '' == $meta_value[$i])
            add_post_meta($post_id, $meta_key[$i], $new_meta_value[$i], true);

        /* WENN EIN EXISTIERENDER WERT EMPFANGEN WURDE, PRÜFE OB DER VON DEM NEUEN WERT ABWEICHT, FALLS JA, AKTUALISIERE DEN WERT */
        elseif ($new_meta_value[$i] && $new_meta_value[$i] != $meta_value[$i])
            update_post_meta($post_id, $meta_key[$i], $new_meta_value[$i]);

        /* WENN KEIN NEUER WERT FESTGELEGT WURDE, ABER EIN ALTER EXISTIERTE, LÖSCHE DIESEN ALTEN */
        elseif ('' == $new_meta_value[$i] && $meta_value[$i])
            delete_post_meta($post_id, $meta_key[$i], $meta_value[$i]);
        $i++;
    endforeach;
}

?>