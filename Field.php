<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementNode;
use wpscholar\Elements\EnclosingElement;

/**
 * Class Field
 *
 * @package wpscholar\WordPress
 *
 * @property string $name
 * @property mixed $value
 */
abstract class Field {

	/**
	 * Field element
	 *
	 * @var ElementNode
	 */
	public $el;

	/**
	 * Label text
	 *
	 * @var string
	 */
	public $label = '';

	/**
	 * Label element
	 *
	 * @var EnclosingElement
	 */
	public $labelEl;

	/**
	 * Field name
	 *
	 * Set during construction, cannot be altered externally
	 *
	 * @var string
	 */
	protected $_name;

	/**
	 * Sanitization callback
	 *
	 * @var callable
	 */
	public $sanitize = 'sanitize_text_field';

	/**
	 * Storage engine
	 *
	 * @var FieldStorage
	 */
	protected $_storage;

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
	 * @param string $label
	 * @param array $args
	 */
	public function __construct( $name, $label = '', array $args = [] ) {
		$storageType    = isset( $args['storage_type'] ) ? $args['storage_type'] : 'post_meta';
		$this->_name    = $name;
		$this->label    = $label;
		$this->_storage = FieldStorageFactory::create( $storageType );
		$this->_setUp( $args );
	}

	/**
	 * Setup field
	 *
	 * @param array $args
	 */
	abstract protected function _setUp( array $args );

	/**
	 * Fetch field value from storage engine
	 *
	 * @param int $id
	 *
	 * @returns mixed
	 */
	public function fetch( $id ) {
		return $this->_storage->fetch( $id, $this->name );
	}

	/**
	 * Save field value to storage engine
	 *
	 * @param int $id
	 * @param mixed $value
	 */
	public function save( $id, $value ) {
		if ( ! is_callable( $this->sanitize ) ) {
			throw new \InvalidArgumentException( 'Invalid field sanitization callback' );
		}
		$this->_storage->save( $id, $this->name, call_user_func( $this->sanitize, $value ) );
	}

	/**
	 * Delete field value from storage engine
	 *
	 * @param int $id
	 */
	public function delete( $id ) {
		$this->_storage->delete( $id, $this->name );
	}

	/**
	 * Get label HTML
	 *
	 * @return string
	 */
	abstract public function getLabel();

	/**
	 * Get field HTML
	 *
	 * @return string
	 */
	abstract public function getField();

	/**
	 * Get label and field HTML
	 *
	 * @return string
	 */
	public function __toString() {
		return "{$this->getLabel()} {$this->getField()}";
	}

	/**
	 * Render field
	 */
	public function render() {
		echo $this->__toString();
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

}