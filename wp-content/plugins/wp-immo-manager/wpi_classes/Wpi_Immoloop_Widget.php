<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 30.10.2017
 * Time: 11:00
 */

namespace wpi\wpi_classes;

class Wpi_Immoloop_Widget extends \WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent ::__construct(
			'immoloop', // Base ID
			esc_html__( 'WPI Immobilien', WPI_PLUGIN_NAME ), // Name
			array( 'description' => esc_html__( 'Immobilien Loop', WPI_PLUGIN_NAME ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		echo $args[ 'before_widget' ];
		if ( ! empty( $instance[ 'title' ] ) ) {
			echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
		}
		$id              = ! empty( $instance[ 'id' ] ) ? ' id=' . $instance[ 'id' ] : '';
		$anzahl          = ! empty( $instance[ 'anzahl' ] ) ? ' anzahl=' . $instance[ 'anzahl' ] : '';
		$order           = ! empty( $instance[ 'order' ] ) ? ' order=' . $instance[ 'order' ] : '';
		$orderby         = ! empty( $instance[ 'orderby' ] ) ? ' orderby=' . $instance[ 'orderby' ] : '';
		$objekttyp       = ! empty( $instance[ 'objekttyp' ] ) ? ' objekttyp=' . $instance[ 'objekttyp' ] : '';
		$vermarktungsart = ! empty( $instance[ 'vermarktungsart' ] ) ? ' vermarktung=' . $instance[ 'vermarktungsart' ] : '';
		$immogruppe      = ! empty( $instance[ 'immogruppe' ] ) ? ' immogruppe=' . $instance[ 'immogruppe' ] : '';
		$relation        = ! empty( $instance[ 'relation' ] ) ? ' relation=' . $instance[ 'anzahl' ] : '';
		$style           = ! empty( $instance[ 'style' ] ) ? ' style=' . $instance[ 'style' ] : '';
		$paginated       = ! empty( $instance[ 'paginated' ] ) ? ' paginated=' . $instance[ 'paginated' ] : '';

		if ( ! empty( $id ) ):
			$str = '[immobilien' . $id . $columns . ']';
		else:
			$str = '[immobilien' . $anzahl . $order . $orderby . $objekttyp . $vermarktungsart . $immogruppe . $relation . $style . $paginated . ']';
		endif;

		echo '<div id="widget-immobilien" ';
		echo do_shortcode( $str );
		echo '</div>';
		echo $args[ 'after_widget' ];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title           = ! empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$id              = ! empty( $instance[ 'id' ] ) ? $instance[ 'id' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$anzahl          = ! empty( $instance[ 'anzahl' ] ) ? $instance[ 'anzahl' ] : esc_html__( '3', WPI_PLUGIN_NAME );
		$order           = ! empty( $instance[ 'order' ] ) ? $instance[ 'order' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$orderby         = ! empty( $instance[ 'orderby' ] ) ? $instance[ 'orderby' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$objekttyp       = ! empty( $instance[ 'objekttyp' ] ) ? $instance[ 'objekttyp' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$vermarktungsart = ! empty( $instance[ 'vermarktungsart' ] ) ? $instance[ 'vermarktungsart' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$immogruppe      = ! empty( $instance[ 'immogruppe' ] ) ? $instance[ 'immogruppe' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$relation        = ! empty( $instance[ 'relation' ] ) ? $instance[ 'relation' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$style           = ! empty( $instance[ 'style' ] ) ? $instance[ 'style' ] : esc_html__( '', WPI_PLUGIN_NAME );
		$paginated       = ! empty( $instance[ 'paginated' ] ) ? $instance[ 'paginated' ] : esc_html__( '', WPI_PLUGIN_NAME );

		$objekttyp_array   = get_terms( array(
			'taxonomy'   => 'objekttyp',
			'hide_empty' => true,
		) );
		$vermarktung_array = get_terms( array(
			'taxonomy'   => 'vermarktungsart',
			'hide_empty' => true,
		) );
		$immogruppe_array = get_terms( array(
			'taxonomy'   => 'immobiliengruppe',
			'hide_empty' => true,
		) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', WPI_PLUGIN_NAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this -> get_field_name( 'title' ) ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<hr>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'id' ) ); ?>">
				<?php esc_attr_e( 'Immobilien ID: (Bei angabe einer ID wird nur diese angezeigt!)', WPI_PLUGIN_NAME ); ?>
			</label>
			<input id="<?php echo esc_attr( $this -> get_field_id( 'id' ) ); ?>"
			       class="widefat"
			       name="<?php echo esc_attr( $this -> get_field_name( 'id' ) ); ?>" type="text"
			       value="<?php echo esc_attr( $id ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'anzahl' ) ); ?>"><?php esc_attr_e( 'Anzahl der Immobilien:', WPI_PLUGIN_NAME ); ?></label>
			<input id="<?php echo esc_attr( $this -> get_field_id( 'anzahl' ) ); ?>"
			       class="widefat"
			       name="<?php echo esc_attr( $this -> get_field_name( 'anzahl' ) ); ?>" type="number"
			       value="<?php echo esc_attr( $anzahl ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'Reihenfolge:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'order' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'order' ) ); ?>">
				<option value="<?php echo esc_attr( $order ); ?>"><?php echo esc_attr( $order ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<option value="DESC"><?php esc_attr_e( 'Absteigend', WPI_PLUGIN_NAME ); ?></option>
				<option value="ASC"><?php esc_attr_e( 'Aufsteigend', WPI_PLUGIN_NAME ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'orderby' ) ); ?>"><?php esc_attr_e( 'Sortieren nach:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'orderby' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'orderby' ) ); ?>">
				<option value="<?php echo esc_attr( $orderby ); ?>"><?php echo esc_attr( $orderby ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<option value="ID"><?php esc_attr_e( 'ID (Standard)', WPI_PLUGIN_NAME ); ?></option>
				<option value="title"><?php esc_attr_e( 'Titel', WPI_PLUGIN_NAME ); ?></option>
				<option value="author"><?php esc_attr_e( 'Author', WPI_PLUGIN_NAME ); ?></option>
				<option value="date"><?php esc_attr_e( 'Datum', WPI_PLUGIN_NAME ); ?></option>
				<option value="rand"><?php esc_attr_e( 'Zufällig', WPI_PLUGIN_NAME ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'objekttyp' ) ); ?>"><?php esc_attr_e( 'Objekttyp:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'objekttyp' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'objekttyp' ) ); ?>">
				<option value="<?php echo esc_attr( $objekttyp ); ?>"><?php echo esc_attr( $objekttyp ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<?php foreach ( $objekttyp_array as $objekttyp ) : ?>
					<option value="<?php echo $objekttyp -> slug ?>"><?php echo $objekttyp -> name ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'vermarktungsart' ) ); ?>"><?php esc_attr_e( 'Vermarktungsart:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'vermarktungsart' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'vermarktungsart' ) ); ?>">
				<option value="<?php echo esc_attr( $vermarktungsart ); ?>"><?php echo esc_attr( $vermarktungsart ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<?php foreach ( $vermarktung_array as $vermarktung ) : ?>
					<option value="<?php echo $vermarktung -> slug ?>"><?php echo $vermarktung -> name ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'immogruppe' ) ); ?>"><?php esc_attr_e( 'Immobiliengruppe:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'immogruppe' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'immogruppe' ) ); ?>">
				<option value="<?php echo esc_attr( $immogruppe ); ?>"><?php echo esc_attr( $immogruppe ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<?php foreach ( $immogruppe_array as $gruppe ) : ?>
					<option value="<?php echo $gruppe -> slug ?>"><?php echo $gruppe -> name ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'relation' ) ); ?>"><?php esc_attr_e( 'Verknüpfung des Loops:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'relation' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'relation' ) ); ?>">
				<option value="<?php echo esc_attr( $relation ); ?>"><?php echo esc_attr( $relation ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<option value="OR"><?php esc_attr_e( 'ODER-Verknüpfung', WPI_PLUGIN_NAME ); ?></option>
				<option value="AND"><?php esc_attr_e( 'UND-Verknüpfung', WPI_PLUGIN_NAME ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'style' ) ); ?>"><?php esc_attr_e( 'Style:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'style' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'style' ) ); ?>">
				<option value="<?php echo esc_attr( $style ); ?>"><?php echo esc_attr( $style ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<option value="table"><?php esc_attr_e( 'Tabelle', WPI_PLUGIN_NAME ); ?></option>
				<option value="div"><?php esc_attr_e( 'Hardfacts', WPI_PLUGIN_NAME ); ?></option>
				<option value="column"><?php esc_attr_e( 'Spalten', WPI_PLUGIN_NAME ); ?></option>
				<option value="two-col-list"><?php esc_attr_e( '2-Spalten', WPI_PLUGIN_NAME ); ?></option>
				<option value="thumbnail"><?php esc_attr_e( 'Thumbnail (Empfohlen für Sidebar)', WPI_PLUGIN_NAME ); ?></option>
				v
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this -> get_field_id( 'paginated' ) ); ?>"><?php esc_attr_e( 'Pagination einblenden:', WPI_PLUGIN_NAME ); ?></label>
			<select class="widefat"
			        id="<?php echo esc_attr( $this -> get_field_id( 'paginated' ) ); ?>"
			        name="<?php echo esc_attr( $this -> get_field_name( 'paginated' ) ); ?>">
				<option value="<?php echo esc_attr( $paginated ); ?>"><?php echo esc_attr( $paginated ); ?></option>
				<option value=""><?php esc_attr_e( 'Ohne Auswahl', WPI_PLUGIN_NAME ); ?></option>
				<option value="true"><?php esc_attr_e( 'Ja', WPI_PLUGIN_NAME ); ?></option>
				<option value="false"><?php esc_attr_e( 'Nein (Standard)', WPI_PLUGIN_NAME ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance[ 'title' ]           = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
		$instance[ 'id' ]              = ( ! empty( $new_instance[ 'id' ] ) ) ? strip_tags( $new_instance[ 'id' ] ) : '';
		$instance[ 'anzahl' ]          = ( ! empty( $new_instance[ 'anzahl' ] ) ) ? strip_tags( $new_instance[ 'anzahl' ] ) : '';
		$instance[ 'order' ]           = ( ! empty( $new_instance[ 'order' ] ) ) ? strip_tags( $new_instance[ 'order' ] ) : '';
		$instance[ 'orderby' ]         = ( ! empty( $new_instance[ 'orderby' ] ) ) ? strip_tags( $new_instance[ 'orderby' ] ) : '';
		$instance[ 'objekttyp' ]       = ( ! empty( $new_instance[ 'objekttyp' ] ) ) ? strip_tags( $new_instance[ 'objekttyp' ] ) : '';
		$instance[ 'vermarktungsart' ] = ( ! empty( $new_instance[ 'vermarktungsart' ] ) ) ? strip_tags( $new_instance[ 'vermarktungsart' ] ) : '';
		$instance[ 'immogruppe' ]      = ( ! empty( $new_instance[ 'immogruppe' ] ) ) ? strip_tags( $new_instance[ 'immogruppe' ] ) : '';
		$instance[ 'relation' ]        = ( ! empty( $new_instance[ 'relation' ] ) ) ? strip_tags( $new_instance[ 'relation' ] ) : '';
		$instance[ 'style' ]           = ( ! empty( $new_instance[ 'style' ] ) ) ? strip_tags( $new_instance[ 'style' ] ) : '';
		$instance[ 'paginated' ]       = ( ! empty( $new_instance[ 'paginated' ] ) ) ? strip_tags( $new_instance[ 'paginated' ] ) : 'false';

		return $instance;
	}
}