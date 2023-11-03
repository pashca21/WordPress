<?php
/**
 * Template zur Anzeige der Immobilien als Single
 *
 */

use wpi\wpi_classes\ListViewClass;

$list_view = new ListViewClass;

?>

<?php get_header(); ?>

<?php echo $list_view -> wrapper_template() ?>

<?php // echo check_valid_status(); ?>

<?php get_footer(); ?>