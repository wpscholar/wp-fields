<?php

namespace wpscholar\WordPress;

/**
 * Trait StorageEngine
 *
 * @package wpscholar\WordPress
 */
trait FieldStorageEngine {

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
	protected $_sanitize = 'sanitize_text_field';

	/**
	 * Storage engine
	 *
	 * @var FieldStorage
	 */
	protected $_storage;

	/**
	 * Fetch field value from storage engine
	 *
	 * @param int $id
	 *
	 * @returns mixed
	 */
	public function fetch( $id ) {
		return $this->_storage->fetch( $id, $this->_name );
	}

	/**
	 * Save field value to storage engine
	 *
	 * @param int $id
	 * @param mixed $value
	 */
	public function save( $id, $value ) {
		if ( ! is_callable( $this->_sanitize ) ) {
			throw new \InvalidArgumentException( 'Invalid field sanitization callback' );
		}
		$this->_storage->save( $id, $this->_name, call_user_func( $this->_sanitize, $value ) );
	}

	/**
	 * Delete field value from storage engine
	 *
	 * @param int $id
	 */
	public function delete( $id ) {
		$this->_storage->delete( $id, $this->_name );
	}

	/**
	 * Set the storage engine
	 *
	 * @param string|null $engine
	 */
	protected function setStorageEngine( $engine = null ) {
		$this->_storage = FieldStorageFactory::create( $engine );
	}

}