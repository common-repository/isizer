<?php

class Isizer_Settings
{

	/**
	 * Option page.
	 *
	 * @var string
	 */
	const PAGE = 'isizer';


	/**
	 * Admin screen id.
	 *
	 * @var string
	 */
	const SCREEN_ID = 'settings_page_isizer';


	/**
	 * Option group.
	 *
	 * @var string
	 */
	const OPTION_GROUP = 'isizer_group';


	/**
	 * Default image sizes.
	 *
	 * @var array
	 */
	public $default_sizes = array('thumbnail', 'medium', 'medium_large', 'large');

	/**
	 * Plugin options.
	 *
	 * @var array
	 */
	protected $all_sizes;

	/**
	 * Removed image sizes by plugin.
	 *
	 * @var array
	 */
	public $removed_sizes;

	/**
	 * Added image sizes by plugin.
	 *
	 * @var array
	 */
	public $added_sizes;


	/**
	 * Cyr_To_Lat_Settings constructor.
	 */
	public function __construct()
	{

		add_action('plugins_loaded', array($this, 'init'));

	}


	/**
	 * Init plugin.
	 */
	public function init()
	{

		$this->load_removed_sizes();
		$this->load_added_sizes();
		$this->load_plugin_textdomain();
		$this->init_hooks();

	}


	/**
	 * Load removed sizes
	 */

	private function load_removed_sizes()
	{

		$removed             = is_array(get_option('isizer_removed_sizes')) ? get_option('isizer_removed_sizes') : array();
		$this->removed_sizes = $removed;

	}


	/**
	 * Load addes sizes
	 */

	private function load_added_sizes()
	{

		$added             = is_array(get_option('isizer_added_sizes')) ? get_option('isizer_added_sizes') : array();
		$this->added_sizes = $added;
	}


	/**
	 * Init class hooks.
	 */
	public function init_hooks()
	{

		add_filter('plugin_action_links_' . plugin_basename(ISIZER_FILE), array($this, 'add_settings_link',), 10, 4);
		add_action('after_setup_theme', array($this, 'get_image_sizes'), 10000);
		add_action('admin_menu', array($this, 'add_settings_page'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}


	/**
	 * Add settings page to the menu.
	 */
	public function add_settings_page()
	{

		$parent_slug = 'options-general.php';
		$page_title  = __('Isizer', 'isizer');
		$menu_title  = __('Isizer', 'isizer');
		$capability  = 'manage_options';
		$slug        = self::PAGE;
		$callback    = array($this, 'ctl_settings_page');
		add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $slug, $callback);
	}


	/**
	 * Settings page.
	 */
	public function ctl_settings_page()
	{

		if ( ! $this->is_ctl_options_screen()) {
			return;
		}

		?>
		<div class="wrap">
			<h2><?php echo __('Registered image sizes', 'isizer'); ?></h2>
			<div class="isizer-preview-box">


				<?php foreach ($this->all_sizes as $key => $value): ?>

					<div
						class="isizer-size-preview<?php if (in_array($key, $this->removed_sizes)) {
							echo ' removed';
						} ?>">
						<span><?php echo $key . ' <br><br>' . $value['width'] . 'x' . $value['height']; ?></span>
						<div class="isizer-remove" data-name="<?php echo $key; ?>">x</div>
					</div>
				<?php endforeach; ?>

				<div class="isizer-add isizer-size-preview">
					<a
						href="/?TB_inline&width=300&height=220&inlineId=isizer_new_modal" title="New image size"
						class="dashicons-before dashicons-plus-alt thickbox"> <?php echo __('Add new', 'isizer'); ?></a>
				</div>


			</div>

			<div id="isizer_new_modal" style="display:none;">
				<div class="isizer-modal-content">
					<div class="isizer-input-wrap">
						<input type="text" name="isize_new_name" id="isize_new_name" placeholder="<?php echo __('New image size name', 'isizer'); ?>">
					</div>
					<div class="isizer-input-group">
						<input type="text" name="isize_new_width" id="isize_size_width" placeholder="<?php echo __('Image width', 'isizer'); ?>">
						<input type="text" name="isize_new_height" id="isize_size_height" placeholder="<?php echo __('Image height', 'isizer'); ?>">
					</div>

					<div class="isizer-input-group">
						<label class="switch"><input type="checkbox" id="isize_size_crop">
							<div class="slider round"></div>
						</label>
						<button id="isizer_send_new_size"><?php echo __('Add new size', 'isizer') ?></button>
					</div>
				</div>
			</div>

		</div>
		<?php

	}


	/**
	 * Load plugin text domain.
	 */
	public function load_plugin_textdomain()
	{

		//TODO Сгенерировать языковые файлы
		load_plugin_textdomain('isizer', false, dirname(plugin_basename(ISIZER_FILE)) . '/languages/');
	}


	/**
	 * Enqueue class scripts.
	 */
	public function admin_enqueue_scripts()
	{

		//TODO сделать проверку на текущий экран и только там подключать
		wp_enqueue_script('isizer-script', ISIZER_URL . '/scr/js/isizer.js', array(), ISIZER_VERSION, true);
		wp_enqueue_style('isizer-style', ISIZER_URL . '/scr/css/isizer.css', array(), ISIZER_VERSION);

		add_thickbox();
	}


	/**
	 * Add link to plugin setting page on plugins page.
	 *
	 * @param array  $actions     An array of plugin action links. By default this can include 'activate',
	 *                            'deactivate', and 'delete'. With Multisite active this can also include
	 *                            'network_active' and 'network_only' items.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                            'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @return array|mixed Plugin links
	 */
	public function add_settings_link($actions, $plugin_file, $plugin_data, $context)
	{

		$ctl_actions = array(
			'settings' => '<a href="' . admin_url('options-general.php?page=' . self::PAGE) . '" aria-label="' . esc_attr__('View ISIZER page', 'isizer') . '">' .
			              esc_html__('Settings', 'isizer') . '</a>',
		);

		return array_merge($ctl_actions, $actions);
	}


	/**
	 * Is current admin screen the plugin options screen.
	 *
	 * @return bool
	 */
	protected function is_ctl_options_screen()
	{

		$current_screen = get_current_screen();

		return $current_screen && ('options' === $current_screen->id || self::SCREEN_ID === $current_screen->id);
	}

	/**
	 * Get all the registered image sizes along with their dimensions
	 *
	 * @return array $image_sizes The image sizes
	 * @global array $_wp_additional_image_sizes
	 *
	 */

	/**
	 * Get size information for all currently-registered image sizes.
	 *
	 * @uses   get_intermediate_image_sizes()
	 * @global $_wp_additional_image_sizes
	 */
	public function get_image_sizes()
	{

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach (get_intermediate_image_sizes() as $_size) {
			if (in_array($_size, $this->default_sizes)) {
				$sizes[$_size]['width']  = get_option("{$_size}_size_w");
				$sizes[$_size]['height'] = get_option("{$_size}_size_h");
				$sizes[$_size]['crop']   = (bool)get_option("{$_size}_crop");
			} elseif (isset($_wp_additional_image_sizes[$_size])) {
				$sizes[$_size] = array(
					'width'  => $_wp_additional_image_sizes[$_size]['width'],
					'height' => $_wp_additional_image_sizes[$_size]['height'],
					'crop'   => $_wp_additional_image_sizes[$_size]['crop'],
				);
			}
		}

		$this->all_sizes = $sizes;
	}

}
