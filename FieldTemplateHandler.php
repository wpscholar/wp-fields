<?php

namespace wpscholar\WordPress;

/**
 * Class FieldTemplateHandler
 *
 * @package wpscholar\WordPress
 */
class FieldTemplateHandler {

	/**
	 * Instance of this class.
	 *
	 * @var FieldTemplateHandler
	 */
	protected static $_instance;

	/**
	 * @var \Twig_Environment
	 */
	protected $_twig;

	/**
	 * Get an instance of this class.
	 *
	 * @return FieldTemplateHandler
	 */
	public static function getInstance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * FieldTemplateHandler constructor.
	 */
	protected function __construct() {

		$templatePaths = apply_filters( __METHOD__, [ __DIR__ . '/templates' ] );
		$loader = new \Twig_Loader_Filesystem( $templatePaths );
		$twig = new \Twig_Environment( $loader, [ 'debug' => $this->_isDebugMode() ] );

		if ( $this->_isDebugMode() ) {
			$twig->addExtension( new \Twig_Extension_Debug() );
		}

		$this->_twig = $twig;
	}

	/**
	 * Render template
	 *
	 * @param string $template
	 * @param array $data
	 */
	public function render( $template, array $data = [] ) {
		echo $this->asString( $template, $data );
	}

	/**
	 * Return template as string.
	 *
	 * @param string $template
	 * @param array $data
	 *
	 * @return string
	 */
	public function asString( $template, array $data = [] ) {
		return $this->_twig->render( $template, $data );
	}

	/**
	 * Check if we are in debug mode.
	 *
	 * @return bool
	 */
	protected function _isDebugMode() {
		return defined( 'WP_DEBUG' ) && WP_DEBUG ? WP_DEBUG : false;
	}

}
