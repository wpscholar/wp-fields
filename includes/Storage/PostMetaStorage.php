<?php

namespace wpscholar\WordPressFields\Storage;

/**
 * Class PostMetaStorage
 *
 * @package wpscholar\WordPressFields\Storage
 */
class PostMetaStorage implements FieldStorage {

	/**
	 * Fetch field value from post meta.
	 *
	 * @param int $id
	 * @param string $key
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
	 * @param string $key
	 * @param mixed $value
	 */
	public function save( $id, $key, $value ) {
		update_post_meta( $id, $key, $value );
	}

	/**
	 * Delete field value from post meta.
	 *
	 * @param int $id
	 * @param string $key
	 */
	public function delete( $id, $key ) {
		delete_post_meta( $id, $key );
	}

}