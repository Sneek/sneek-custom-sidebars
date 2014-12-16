<?php defined('ABSPATH') or exit;

final class Sneek_Custom_Sidebars
{
    const OPTION_KEY    = 'sneek-custom-sidebars';
    const POST_META_KEY = 'sneek-custom-sidebar';
    const NONCE         = 'sneek-custom-sidebars-nonce';

    public function __construct()
    {
        $this->sidebars = get_option(static::OPTION_KEY, array());

        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('wp_ajax_sneek_add_sidebar', array($this, 'add_sidebar'));
        add_action('wp_ajax_sneek_remove_sidebar', array($this, 'remove_sidebar'));

        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_custom_metabox'));

        add_action('widgets_init', array($this, 'register_custom_sidebars'));
    }

    public function admin_menu()
    {
        add_theme_page('Custom Sidebars', 'Sidebars', 'edit_pages', 'custom-sidebars', [$this, 'render_page']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('sneek.custom-sidebars', SNEEK_CUSTOM_SIDEBARS_URL . 'js/admin-v1.0.0.js', array('jquery'));
    }

    // TODO: Implement a WP-List table class
    public function render_page()
    {
        require_once __DIR__ . '/views/admin-custom-sidebars.php';
    }

    private function sidebar_output($id, $name)
    {
        $out = '<tr>';
        $out .= '<td>';
        $out .= '<strong>' . $name . '</strong> <br />';
        $out .= '<div class="row-actions">';
        $out .= '<span class="delete"><a class="remove-sidebar" data-name="' . $name . '" data-sidebar-id="' . $id . '">Delete</a></span>';
        $out .= '</div>';
        $out .= '</td>';
        $out .= '<td>' . $id . '</td>';
        $out .= '</tr>';

        return $out;
    }

    public function add_sidebar()
    {

        $name = trim($_POST['sidebarName']);
        $id = sanitize_title($name . ' widget area', 'another-widget-area-' . rand(0, 9999));

        $counter = 0;

        $base_id = $id;

        // In case we have two sidebars with the same name
        while (array_key_exists($id, $this->sidebars)) {
            $counter ++;
            $id = $base_id . '-' . $counter;
        }

        if ($counter > 0) {
            $name .= ' ' . $counter;
        }

        // Add it to our array
        $this->sidebars[$id] = $name;

        update_option(static::OPTION_KEY, $this->sidebars);

        $response = [];
        $response['status'] = 'success';
        $response['content'] = $this->sidebar_output($id, $name);

        $this->json($response);
    }

    public function remove_sidebar()
    {
        $response = [];
        $response['status'] = 'failure';
        $id = $_POST['sidebarID'];

        if (array_key_exists($id, $this->sidebars)) {
            unset($this->sidebars[$id]);
            update_option(static::OPTION_KEY, $this->sidebars);

            $response['status'] = 'success';
        }

        $this->json($response);
    }

    private function json($response)
    {
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'sneek-custom-sidebar-metabox',
            'Custom Sidebar',
            array($this, 'render_custom_sidebar_metabox'),
            'page',
            $context = 'side'
        );
    }

    public function render_custom_sidebar_metabox($post)
    {
        global $wp_registered_sidebars;

        $current_sidebar = static::get_custom_metabox($post->ID) ?: '';

        wp_nonce_field(plugin_basename(__FILE__), static::NONCE);

        ?>
        <select id="sneek-custom-sidebar" name="sneek[custom_sidebar]">
            <option value="">Select Custom Sidebar</option>
            <?php foreach ($wp_registered_sidebars as $sidebar) : ?>
                <option value="<?php echo $sidebar['id']; ?>" <?php selected($sidebar['id'], $current_sidebar); ?>>
                    <?php echo $sidebar['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php
    }

    public function save_custom_metabox($post_id)
    {
        /* Autosave? */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        /* Nonce verified? */
        if ( ! isset($_POST[static::NONCE]) or ! wp_verify_nonce($_POST[static::NONCE], plugin_basename(__FILE__))) {
            return false;
        }

        /* Finally, does the user have permission? */
        if ( ! current_user_can("edit_{$_POST['post_type']}", $post_id)) {
            return false;
        }

        $custom_sidebar = $_POST['sneek']['custom_sidebar'];


        update_post_meta($post_id, static::POST_META_KEY, $custom_sidebar);
    }


    public static function get_custom_metabox($post_id)
    {
        return get_post_meta($post_id, static::POST_META_KEY, true);
    }

    public function register_custom_sidebars()
    {
        ksort($this->sidebars);

        foreach ($this->sidebars as $key => $name)
        {
            $args = apply_filters('sneek-custom-sidebar-args-'.$key, apply_filters('sneek-custom-sidebar-args', array(
                'id' => $key,
                'name' => $name . ' Widget Area',
            )));

            register_sidebar($args);
        }
    }
}
