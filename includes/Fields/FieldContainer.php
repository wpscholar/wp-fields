<?php

namespace wpscholar\WordPressFields\Fields;

/**
 * Class FieldContainer
 *
 * @package wpscholar\Fields\Fields
 */
class FieldContainer implements \IteratorAggregate {

	/**
	 * Field collection
	 *
	 * @var array
	 */
	protected $_fields = [];

	/**
	 * Check if field exists in container.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasField( $name ) {
		return isset( $this->_fields[ $name ] );
	}

	/**
	 * Get a field from the container by name
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getField( $name ) {
		$field = null;
		if ( $this->hasField( $name ) ) {
			$field = $this->_fields[ $name ];
		}

		return $field;
	}

	/**
	 * Add a field to the container
	 *
	 * @param Field $field
	 */
	public function addField( Field $field ) {
		$this->_fields[ $field->name ] = $field;
	}

	/**
	 * Add multiple fields to the container
	 *
	 * @param Field[] $fields
	 */
	public function addFields( array $fields ) {
		foreach ( $fields as $field ) {
			$this->addField( $field );
		}
	}

	/**
	 * Remove a field from the container by name
	 *
	 * @param string $name
	 */
	public function removeField( $name ) {
		if ( $this->hasField( $name ) ) {
			unset( $this->_fields[ $name ] );
		}
	}

	/**
	 * Remove all fields from the container
	 */
	public function removeAllFields() {
		$this->_fields = [];
	}

	/**
	 * Setup iterator for looping through fields
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->_fields );
	}

	/**
	 * Converts the field container, including all contained fields, to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		return implode(
			PHP_EOL,
			array_map( function ( $field ) {
				return "{$field}";
			}, $this->_fields )
		);
	}

}
