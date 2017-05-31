<?php

namespace wpscholar\WordPress;

/**
 * Class PostMetaStorage
 *
 * @package wpscholar\WordPress
 *
 * @property string $name
 */
class PostMetaStorage implements FieldStorage {

	/**
	 * Fetch field value from post meta.
	 *
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function fetch( $id, $key ) {
		return get_post_meta( $id, $key, true );
	}

	/**
	 * Save field value to post meta.
	 *
	 * @param int $id
	 * @param mixed $value
	 */
	public function save( $id, $key, $value ) {
		update_post_meta( $id, $key, $value );
	}

	/**
	 * Delete field value from post meta.
	 *
	 * @param int $id
	 */
	public function delete( $id, $key ) {
		delete_post_meta( $id, $key );
	}

}