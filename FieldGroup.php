<?php

namespace wpscholar\WordPress;

/**
 * Class FieldGroup
 *
 * @package wpscholar\WordPress
 */
class FieldGroup implements \IteratorAggregate {

	/**
	 * Field collection
	 *
	 * @var array
	 */
	protected $_fields = [];

	/**
	 * Check if field exists in group.
	 *
	 * @param string $field_name
	 *
	 * @return bool
	 */
	public function hasField( $field_name ) {
		return isset( $this->_fields[ $field_name ] );
	}

	/**
	 * Get a field from the group by name
	 *
	 * @param string $field_name
	 *
	 * @return mixed
	 */
	public function getField( $field_name ) {
		$field = null;
		if ( $this->hasField( $field_name ) ) {
			$field = $this->_fields[ $field_name ];
		}

		return $field;
	}

	/**
	 * Add a field to the group
	 *
	 * @param Field $field
	 */
	public function addField( Field $field ) {
		$this->_fields[ $field->name ] = $field;
	}

	/**
	 * Remove a field from the group by name
	 *
	 * @param string $field_name
	 */
	public function removeField( $field_name ) {
		if ( $this->hasField( $field_name ) ) {
			unset( $this->_fields[ $field_name ] );
		}
	}

	/**
	 * Setup iterator for looping through fields
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->_fields );
	}

}