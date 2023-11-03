<?php
/**
 * Created by Media-Store.net.
 * User: Artur
 * Date: 18.01.2018
 * Time: 23:57
 */

namespace wpi\wpi_classes\components;


class BootstrapCarouselClass {

	/**
	 * @var $content Array
	 * This Var should be an array of Contents to Slide
	 */
	public $content;

	/**
	 * @var $args
	 * Settings soon
	 */
	public $args;

	public function __construct( $content, $args = '' ) {
		$this -> content = (array) $content;
		$this -> args    = $args;
	}

	public function __toString() {
		return $this -> CarouselHtml();
	}

	private function CarouselHtml() {
		$element_id = 'carousel-' . time();

		ob_start();
		?>
		<div id="<?php echo $element_id ?>" class="carousel slide" data-ride="carousel">
			<?php
			if ( count( $this -> content ) > 0 ):
				$i = 0;
				$j = 0;
				?>
				<!-- Positionsanzeiger -->
				<ol class="carousel-indicators">
					<?php foreach ( $this -> content as $num ) : ?>
						<li data-target="#<?php echo $element_id ?>" data-slide-to="<?php echo $i ?>"
						    class="<?php echo $i == 0 ? 'active' : ''; ?>"></li>
						<?php
						$i ++;
					endforeach;
					?>
				</ol>

				<!-- Verpackung für die Elemente -->
				<div class="carousel-inner" role="listbox">
					<?php foreach ( $this -> content as $cont ): ?>
						<div class="item <?php echo $j == 0 ? 'active' : ''; ?>">
							<?php echo $cont; ?>
						</div>
						<?php
						$j ++;
					endforeach;
					?>
				</div>

				<!-- Schalter -->
				<a class="left carousel-control" href="#<?php echo $element_id ?>" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Zurück</span>
				</a>
				<a class="right carousel-control" href="#<?php echo $element_id ?>" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Weiter</span>
				</a>

			<?php
			endif;
			?>

		</div>
		<?php
		return ob_get_clean();
	}
}