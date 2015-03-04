<?php

require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_interface.php';

class acf_qtranslate_acf_4 implements acf_qtranslate_acf_interface {

	/**
	 * The plugin instance.
	 * @var \acf_qtranslate_plugin
	 */
	protected $plugin;


	/*
	 * Create an instance.
	 * @return void
	 */
	public function __construct($plugin) {
		$this->plugin = $plugin;

		add_filter('acf/format_value_for_api', array($this, 'format_value_for_api'));
		add_action('acf/register_fields',      array($this, 'register_fields'));
		add_action('admin_enqueue_scripts',    array($this, 'admin_enqueue_scripts'));

		$this->monkey_patch_qtranslate();
	}

	/**
	 * Load javascript and stylesheets on admin pages.
	 */
	public function register_fields() {
		require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_4/fields/file.php';
		require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_4/fields/image.php';
		require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_4/fields/text.php';
		require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_4/fields/textarea.php';
		require_once ACF_QTRANSLATE_PLUGIN_DIR . 'src/acf_4/fields/wysiwyg.php';

		new acf_qtranslate_acf_4_text($this->plugin);
		new acf_qtranslate_acf_4_textarea($this->plugin);
		new acf_qtranslate_acf_4_wysiwyg($this->plugin);
		new acf_qtranslate_acf_4_image($this->plugin);
		new acf_qtranslate_acf_4_file($this->plugin);
	}

	/**
	 * Load javascript and stylesheets on admin pages.
	 */
	public function admin_enqueue_scripts() {
		if ($this->get_visible_acf_fields()) {
			wp_enqueue_style('acf_qtranslate_common',  plugins_url('/assets/common.css', ACF_QTRANSLATE_PLUGIN), array('acf-input'));
			wp_enqueue_script('acf_qtranslate_common', plugins_url('/assets/common.js',  ACF_QTRANSLATE_PLUGIN), array('acf-input'));
		}
	}

	/**
	 * This filter is applied to the $value after it is loaded from the db and
	 * before it is returned to the template via functions such as get_field().
	 */
	public function format_value_for_api($value) {
		if (is_string($value)) {
			$value = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
		}
		return $value;
	}

	/**
	 * Get the visible ACF fields.
	 * @return array
	 */
	public function get_visible_acf_fields() {
		global $post, $pagenow, $typenow, $plugin_page;

		$filter = array();
		$visible_fields = array();

		if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
			if ($typenow !== 'acf') {
				$filter['post_id'] = apply_filters('acf/get_post_id', false);
				$filter['post_type'] = $typenow;
			}
		}
		elseif ($pagenow === 'admin.php' && isset($plugin_page)) {
			if ($this->acf_get_options_page($plugin_page)) {
				$filter['post_id'] = apply_filters('acf/get_post_id', false);
			}
		}
		elseif ($pagenow === 'edit-tags.php' && isset($_GET['taxonomy'])) {
			$filter['ef_taxonomy'] = filter_var($_GET['taxonomy'], FILTER_SANITIZE_STRING);
		}
		elseif ($pagenow === 'profile.php') {
			$filter['ef_user'] = get_current_user_id();
		}
		elseif ($pagenow === 'user-edit.php' && isset($_GET['user_id'])) {
			$filter['ef_user'] = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
		}
		elseif ($pagenow === 'user-new.php') {
			$filter['ef_user'] = 'all';
		}
		elseif ($pagenow === 'media.php' || $pagenow === 'upload.php') {
			$filter['post_type'] = 'attachment';
		}

		if (count($filter) === 0) {
			return $visible_fields;
		}

		$supported_field_types = array(
			'email',
			'text',
			'textarea',
		);

		$visible_field_groups = apply_filters('acf/location/match_field_groups', array(), $filter);

		foreach (apply_filters('acf/get_field_groups', array()) as $field_group) {
			if (in_array($field_group['id'], $visible_field_groups)) {
				$fields = apply_filters('acf/field_group/get_fields', array(), $field_group['id']);
				foreach ($fields as $field) {
					if (in_array($field['type'], $supported_field_types)) {
						$visible_fields[] = array('id' => $field['id']);
					}
				}
			}
		}

		return $visible_fields;
	}

	/**
	 * Get details about ACF Options page.
	 */
	public function acf_get_options_page($slug) {
		global $acf_options_page;

		if (is_array($acf_options_page->settings) === false) {
			return false;
		}

		if ($acf_options_page->settings['slug'] === $slug) {
			return array(
				'title'       => $acf_options_page->settings['title'],
				'menu'        => $acf_options_page->settings['menu'],
				'slug'        => $acf_options_page->settings['slug'],
				'capability'  => $acf_options_page->settings['capability'],
				'show_parent' => $acf_options_page->settings['show_parent'],
			);
		}

		foreach ($acf_options_page->settings['pages'] as $page) {
			if ($page['slug'] === $slug) {
				return $page;
			}
		}
	}

	/**
	 * Monkey patches to fix little qTranslate javascript issues.
	 */
	public function monkey_patch_qtranslate() {
		global $q_config;

		// http://www.qianqin.de/qtranslate/forum/viewtopic.php?f=3&t=3497
		if (isset($q_config['js']) && strpos($q_config['js']['qtrans_switch'], 'originalSwitchEditors') === false) {
			$q_config['js']['qtrans_switch'] = "originalSwitchEditors = jQuery.extend(true, {}, switchEditors);\n" . $q_config['js']['qtrans_switch'];
			$q_config['js']['qtrans_switch'] = preg_replace("/(var vta = document\.getElementById\('qtrans_textarea_' \+ id\);)/", "\$1\nif(!vta)return originalSwitchEditors.go(id, lang);", $q_config['js']['qtrans_switch']);
		}

		// https://github.com/funkjedi/acf-qtranslate/issues/2#issuecomment-37612918
		if (isset($q_config['js']) && strpos($q_config['js']['qtrans_hook_on_tinyMCE'], 'ed.editorId.match(/^qtrans_/)') === false) {
			$q_config['js']['qtrans_hook_on_tinyMCE'] = preg_replace("/(qtrans_save\(switchEditors\.pre_wpautop\(o\.content\)\);)/", "if (ed.editorId.match(/^qtrans_/)) \$1", $q_config['js']['qtrans_hook_on_tinyMCE']);
		}
	}
}
