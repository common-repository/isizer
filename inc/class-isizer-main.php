<?php

class Isizer_Main
{

	/**
	 * Plugin settings.
	 *
	 * @var Isizer_Settings
	 */
	private $settings;


	/**
	 * Isizer_Main constructor.
	 *
	 * @param Isizer_Settings $settings Plugin settings.
	 */
	public function __construct($settings = null)
	{

		$this->settings = $settings;
		if ( ! $this->settings) {
			$this->settings = new Isizer_Settings();
		}
		$this->init();
	}


	/**
	 * Init class.
	 */
	public function init()
	{

		$this->init_hooks();
	}


	/**
	 * Init class hooks.
	 */
	public function init_hooks()
	{

		add_action('wp_ajax_isize_remove_size', array($this, 'ajax_remove_size'));

		//remove image size
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'), 999);
		add_action('after_setup_theme', array($this, 'remove_custom_image_sizes'), 999);

	}


	/**
	 * Remove custom image sizes
	 */
	public function remove_custom_image_sizes()
	{

		foreach ($this->settings->removed_sizes as $item) {
			remove_image_size($item);
		}

	}


	/**
	 * Remove default image sizes
	 *
	 * @param $sizes
	 *
	 * @return array
	 */
	public function remove_default_image_sizes($sizes)
	{

		foreach ($this->settings->removed_sizes as $item) {
			if (in_array($item, $this->settings->default_sizes)) {
				unset($sizes[$item]);
			}
		}

		return $sizes;

	}


	/**
	 * Remove image size via ajax method
	 */
	public function ajax_remove_size()
	{

		$removed_sizes = $this->settings->removed_sizes;

		$new_size_name = sanitize_text_field( $_POST['size_name'] );

		if ( ! $new_size_name) {
			wp_send_json_error('no image size name');
		}

		//add new image size to option
		$removed_sizes[] = $new_size_name;

		$updated = update_option('isizer_removed_sizes', $removed_sizes, true);

		if ($updated) {
			wp_send_json_success(__('Size removed succesfully', 'isizer'));
		} else {
			wp_send_json_error(__('Error is corrupted', 'isizer'));
		}

	}

}
