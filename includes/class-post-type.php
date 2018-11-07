<?php
/**
 * Register custom post type
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom post type to store subscribers.
 *
 * @class Simple_Contact_Form\Includes\Post_Type
 * @since 1.0.0
 */
class Post_Type {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'init', [ $this, 'register_post_type' ] );

		// Manage actions and views.
		add_filter( 'post_row_actions', [ $this, 'remove_row_actions' ], 10, 2 );
		add_filter( 'views_edit-simple-contact-form', [ $this, 'remove_views' ] );
		add_filter( 'bulk_actions-edit-simple-contact-form', [ $this, 'remove_bulk_actions' ] );

		// Manage table columns.
		add_filter( 'list_table_primary_column', [ $this, 'add_column_action' ], 10, 2 );
		add_filter( 'manage_edit-simple-contact-form_columns', [ $this, 'manage_columns' ] );
		add_filter( 'manage_edit-simple-contact-form_sortable_columns', [ $this, 'manage_sortable_columns' ] );
		add_action( 'manage_simple-contact-form_posts_custom_column', [ $this, 'manage_columns_html' ], 10, 2 );

	}

	/**
	 * Register simple-contact-form post type
	 * To store subscriber emails
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_post_type() {

		$labels = [
			'name'               => __( 'Simple Contact Form &#8208; Subscribers', 'simple-contact-form' ),
			'menu_name'          => __( 'SCF Subscribers', 'simple-contact-form' ),
			'singular_name'      => __( 'Subscriber', 'simple-contact-form' ),
			'search_items'       => __( 'Search subscribers', 'simple-contact-form' ),
			'not_found'          => __( 'No subscribers found', 'simple-contact-form' ),
			'not_found_in_trash' => __( 'No subscribers found in Trash', 'simple-contact-form' ),
		];

		$capabilities = [
			'read_post'          => false,
			'create_posts'       => false,
			'publish_posts'      => false,
			'read_private_posts' => false,
			'edit_post'          => 'manage_options',
			'edit_posts'         => 'manage_options',
			'delete_post'        => 'manage_options',
			'delete_posts'       => 'manage_options',
		];

		$args = [
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-email-alt',
			'query_var'           => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_admin_bar'   => false,
			'hierarchical'        => false,
			'has_archive'         => false,
			'capabilities'        => $capabilities,
			'rewrite'             => false,
			'supports'            => false,
		];

		register_post_type( 'simple-contact-form', $args );

	}

	/**
	 * Remove edit, quick edit and view row actions.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $actions An array of row action links.
	 * @param WP_Post $post The post object.
	 * @return array
	 */
	public function remove_row_actions( $actions, $post ) {

		if ( 'simple-contact-form' !== $post->post_type ) {
			return $actions;
		}

		unset(
			$actions['edit'],
			$actions['inline hide-if-no-js']
		);

		return $actions;

	}

	/**
	 * Remove published view link button.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $views Inline view buttons.
	 * @return array
	 */
	public function remove_views( $views ) {

		unset( $views['publish'] );
		return $views;

	}

	/**
	 * Remove edit bulk action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $actions Bulk actions.
	 * @return array
	 */
	public function remove_bulk_actions( $actions ) {

		unset( $actions['edit'] );
		return $actions;

	}

	/**
	 * Add inline actions to primary column (_email).
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $default Column name default for the specific list table, e.g. 'name'.
	 * @param string $context Screen ID for specific list table, e.g. 'plugins'.
	 * @return string
	 */
	public function add_column_action( $default, $context ) {

		if ( 'edit-simple-contact-form' === $context ) {
			$default = '_email';
		}

		return $default;

	}

	/**
	 * Set custom table columns
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $columns Holds table columns.
	 * @return array
	 */
	public function manage_columns( $columns ) {

		return [
			'cb'     => '<input type="checkbox"/>',
			'_email' => __( 'Email', 'simple-contact-form' ),
			'_name'  => __( 'Name', 'simple-contact-form' ),
			'_date'  => __( 'Registered On', 'simple-contact-form' ),
		];

	}

	/**
	 * Set custom sortable table columns
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $columns Holds table columns.
	 * @return array
	 */
	public function manage_sortable_columns( $columns ) {

		return array_merge(
			$columns,
			[
				'_email' => 'title',
				'_name'  => 'name',
				'_date'  => 'date',
			]
		);

	}

	/**
	 * Rander html in custom columns
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $column_name The name of the column to display.
	 * @param integer $post_id     The current post ID.
	 */
	public function manage_columns_html( $column_name, $post_id ) {

		switch ( $column_name ) {
			case '_email':
				$email = get_the_title();
				echo '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>';
				break;
			case '_name':
				$name = get_post_meta( $post_id, '_scf_name', true );
				echo esc_html( $name );
				break;
			case '_date':
				$date = get_the_date();
				$time = get_the_time( __( 'Y/m/d g:i:s a', 'simple-contact-form' ) );
				echo '<abbr title="' . esc_attr( $time ) . '">' . esc_html( $date ) . '</abbr>';
				break;
		}

	}
}
