<?php

namespace wpscholar\WordPress;

/**
 * Class Field
 *
 * @package wpscholar\WordPress
 *
 * @property string $name
 * @property mixed $value
 */
abstract class Field {

	use FieldStorageEngine;

	/**
	 * Field data
	 *
	 * Set during construction, cannot be altered externally
	 *
	 * @var array
	 */
	protected $_data = [];

	/**
	 * Field name
	 *
	 * Set during construction, cannot be altered externally
	 *
	 * @var string
	 */
	protected $_name;

	/**
	 * Field value
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 * Field constructor.
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function __construct( $name, array $args = [] ) {
		$this->_name = $name;
		$this->_data = $args;
		$this->value = $this->getData( 'value', '' );
		$this->setStorageEngine( $this->getData( 'storage' ) );
	}

	/**
	 * Fetch value from data object.
	 *
	 * @param string|array $key Passing an array allows you to fetch nested data
	 * @param mixed $default
	 *
	 * @returns mixed
	 */
	public function getData( $key, $default = null ) {

		$value = $default;

		if ( is_string( $key ) ) {
			if ( isset( $this->_data[ $key ] ) ) {
				$value = $this->_data[ $key ];
			}
		}

		if ( is_array( $key ) ) {
			$value    = $this->_data;
			$segments = $key;
			foreach ( $segments as $segment ) {
				if ( isset( $value[ $segment ] ) ) {
					$value = $value[ $segment ];
				} else {
					$value = $default;
					break;
				}
			}
		}

		return $value;
	}

	/**
	 * Render field
	 */
	public function render() {
		echo $this->__toString();
	}

	/**
	 * Helper function for applying a label to a field
	 *
	 * @param string $field
	 * @param string $label
	 * @param string $labelPosition Can be either 'before' or 'after'
	 *
	 * @return string
	 */
	protected function _applyLabel( $field, $label, $labelPosition = 'before' ) {

		$templateHandler = FieldTemplateHandler::getInstance();

		return $templateHandler->asString( 'label.twig', [
			'field'         => $field,
			'label'         => esc_html( $label ),
			'labelPosition' => $labelPosition,
		] );
	}

	/**
	 * Wrap field with before and after markup.
	 *
	 * @param string $render
	 *
	 * @return string
	 */
	protected function _wrap( $render ) {
		return $this->getData( 'before' ) . $render . $this->getData( 'after' );
	}

	/**
	 * Get field name
	 *
	 * @return string
	 */
	protected function _get_name() {
		return $this->_name;
	}

	/**
	 * Get field value
	 *
	 * @return mixed
	 */
	protected function _get_value() {
		return $this->_value;
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value
	 */
	protected function _set_value( $value ) {
		$this->_value = $value;
	}

	/**
	 * Getter function.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		$value  = null;
		$method = "_get_{$property}";
		if ( method_exists( $this, $method ) && is_callable( [ $this, $method ] ) ) {
			$value = $this->$method();
		}

		return $value;
	}

	/**
	 * Setter function.
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set( $property, $value ) {
		$method = "_set_{$property}";
		if ( method_exists( $this, $method ) && is_callable( [ $this, $method ] ) ) {
			$this->$method( $value );
		}
	}

	/**
	 * Get label and field HTML
	 *
	 * @return string
	 */
	abstract public function __toString();

}
