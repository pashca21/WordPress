<?php
/**
 * Created: Media-Store.net
 * Date: 30.11.2017
 * Time: 22:20
 */

namespace wpi\wpi_classes\components;


class FlexSliderClass {

	/**
	 * @var string
	 * Type of Slider-Mode
	 * available modes --> basic, basic_custom_nav, basic_caption,
	 *                     thumbnails_control_nav, thumbnails_slider
	 */
	public $mode;

	/**
	 * @var array
	 * A array of Images to show in Slider
	 */
	public $img;

	/**
	 * @var array
	 * available $args (optional) = title, lightbox, caption, sold_tag
	 */
	public $args;

	/**
	 * FlexSliderClass constructor.
	 *
	 * @param $mode
	 * @param $img
	 */
	public function __construct( $mode, $img, $args = array() ) {
		//TODO evtl. noch Übergabe des Titels, Caption und Verkauft-HTML an slider

		$this -> mode = (string) $mode;
		$this -> img  = (array) $img;
		$this -> args = (array) $args;

		add_action( 'wp_enqueue_scripts', array( $this -> add_scripts() ) );

	}

	/**
	 * @return string
	 */
	public function __toString() {
		//switch trough the $mode-param
		switch ( $this -> mode ):

			case 'basic_custom_nav':
				return $this -> view_basic_custom_nav();
				break;
			case 'basic_caption':
				return $this -> view_basic_caption();
				break;
			case 'thumbnails_control_nav':
				return $this -> view_thumbnails_control_nav();
				break;
			case 'thumbnails_slider':
				return $this -> view_thumbnails_slider();
				break;

			default :
				return $this -> view_basic_slider_html();
				break;

		endswitch;

	}

	/**
	 *  Enqueue all the Styles and JS-Scripts
	 */
	private function add_scripts() {
		wp_enqueue_style( 'flexslider_css', WPI_PLUGIN_URL . 'vendors/woocommerce-FlexSlider/flexslider.css', false, '2.6' );
		wp_enqueue_style( 'flexslider_custom_css', WPI_PLUGIN_URL . 'scss/components/flexslider.css', false, '1.0' );

		wp_enqueue_script( 'flexslider_js', WPI_PLUGIN_URL . 'vendors/woocommerce-FlexSlider/jquery.flexslider-min.js', array( 'jquery' ), 2.6, true );
		switch ( $this -> mode ):

			case 'basic_custom_nav':
				wp_add_inline_script( 'flexslider_js', $this -> js_basic_custom_nav() );
				break;
			case 'basic_caption':
				wp_add_inline_script( 'flexslider_js', $this -> js_basic_slider() );
				break;
			case 'thumbnails_control_nav':
				wp_add_inline_script( 'flexslider_js', $this -> js_thumbnails_control_nav() );
				break;
			case 'thumbnails_slider':
				wp_add_inline_script( 'flexslider_js', $this -> js_thumbnails_slider() );
				break;

			default :
				wp_add_inline_script( 'flexslider_js', $this -> js_basic_slider() );
				break;

		endswitch;
	}

	private function is_lightbox( $link, $str ) {
		if ( isset( $this -> args[ 'lightbox' ] ) ):
			new FancyboxClass();

			return '<a href="' . $link . ' " data-fancybox="gallary">' . $str . '</a>';
		else:
			return $str;
		endif;
	}

	/**
	 * @return string
	 * JS-Code for Basic-Slider
	 */
	private function js_basic_slider() {
		ob_start();
		?>
		jQuery(document).ready(function ($) {
		$(".flexslider").flexslider({
		animation: "slide"
		});
		});
		<?php
		return ob_get_clean();
	}

	private function js_basic_custom_nav() {
		ob_start();
		?>
		jQuery(document).ready(function ($) {
		$('.flexslider').flexslider({
		animation: "slide",
		controlsContainer: $(".custom-controls-container"),
		customDirectionNav: $(".custom-navigation a")
		});
		});
		<?php
		return ob_get_clean();
	}

	private function js_thumbnails_control_nav() {
		ob_start();
		?>
		jQuery(document).ready(function ($) {

		$('.flexslider').flexslider({
		animation: "slide",
		controlNav: "thumbnails"
		});

		if ($('.flex-control-thumbs').length) {
		var liAnzahl = ($('.slides li').length - 2);
		var liWidth = 100 / (liAnzahl) + '%';

		$('.flex-control-thumbs li').css({
		'width': liWidth
		});
		$('.flex-control-thumbs li img').css({
		'width': '100%',
		'height': '50px'
		});
		$(console.log(liAnzahl));
		}

		});
		<?php
		return ob_get_clean();
	}

	private function js_thumbnails_slider() {
		ob_start();
		?>

		jQuery(document).ready(function ($) {

		$('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 210,
		itemMargin: 5,
		asNavFor: '#slider'
		});

		$('#slider').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		sync: "#carousel"
		});

		$('#carousel .slides img').css('max-height', '140px');

		});

		<?php
		return ob_get_clean();

	}

	/**
	 * @return string
	 * View-Template of Basic Slider
	 */
	private function view_basic_slider_html() {
		ob_start();
		if ( ! empty( $this -> args[ 'sold_tag' ] ) ):
			echo $this -> args[ 'sold_tag' ];
		endif;
		?>
		<div class="flexslider">
			<ul class="slides">
				<?php
				if ( is_array( $this -> img ) ):
					foreach ( $this -> img as $img ):
						foreach ( $img as $name => $src ):
							?>
							<li>
								<?php echo $this -> is_lightbox( WPI_UPLOAD_URL . $src, '<img src="' . WPI_UPLOAD_URL . $src . '" alt="' . $name . '"/>' ) ?>
							</li>
						<?php
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</div>

		<?php

		return ob_get_clean();
	}

	/**
	 * @return string
	 * View-Template of Custom_nav Navigation
	 */
	private function view_basic_custom_nav() {
		return
			$this -> view_basic_slider_html() .
			'<div class="custom-navigation">
  <a href="#" class="flex-prev">Prev</a>
  <div class="custom-controls-container"></div>
  <a href="#" class="flex-next">Next</a>
</div>';
	}

	/**
	 * @return string
	 * View-Template for Basic Slider with Caption
	 */
	private function view_basic_caption() {
		ob_start();
		if ( ! empty( $this -> args[ 'sold_tag' ] ) ):
			echo $this -> args[ 'sold_tag' ];
		endif;
		?>
		<div class="flexslider">
			<ul class="slides">
				<?php
				if ( is_array( $this -> img ) ):
					foreach ( $this -> img as $img ):
						foreach ( $img as $name => $src ):
							?>
							<li>
								<?php echo $this -> is_lightbox(
									WPI_UPLOAD_URL . $src,
									'<img src="' . WPI_UPLOAD_URL . $src . '" alt="' . $name . '"/> <p class="flex-caption">' . $name . '</p>' ) ?>
							</li>
						<?php
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</div>

		<?php

		return ob_get_clean();
	}

	private function view_thumbnails_control_nav() {
		ob_start();
		if ( ! empty( $this -> args[ 'sold_tag' ] ) ):
			echo $this -> args[ 'sold_tag' ];
		endif;
		?>
		<div class="flexslider">
			<ul class="slides">
				<?php
				if ( is_array( $this -> img ) ):
					foreach ( $this -> img as $img ):
						foreach ( $img as $name => $src ):
							?>
							<li data-thumb="<?= WPI_UPLOAD_URL . $src; ?>">
								<?php echo $this -> is_lightbox(
									WPI_UPLOAD_URL . $src,
									'<img src="' . WPI_UPLOAD_URL . $src . '" alt="' . $name . '"/>' ) ?>
							</li>
						<?php
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</div>

		<?php

		return ob_get_clean();

	}

	private function view_thumbnails_slider() {
		ob_start();
		if ( ! empty( $this -> args[ 'sold_tag' ] ) ):
			echo $this -> args[ 'sold_tag' ];
		endif;
		?>
		<div id="slider" class="flexslider">
			<ul class="slides">
				<?php
				if ( is_array( $this -> img ) ):
					foreach ( $this -> img as $img ):
						foreach ( $img as $name => $src ):
							?>
							<li>
								<?php echo $this -> is_lightbox(
									WPI_UPLOAD_URL . $src,
									'<img src="' . WPI_UPLOAD_URL . $src . '" alt="' . $name . '"/>' ) ?>
							</li>
						<?php
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</div>
		<div id="carousel" class="flexslider">
			<ul class="slides">
				<?php
				if ( is_array( $this -> img ) ):
					foreach ( $this -> img as $img ):
						foreach ( $img as $name => $src ):
							?>
							<li>
								<img src="<?= WPI_UPLOAD_URL . $src; ?>" alt="<?= $name; ?>"/>
							</li>
						<?php
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</div>
		<?php

		return ob_get_clean();

	}

}