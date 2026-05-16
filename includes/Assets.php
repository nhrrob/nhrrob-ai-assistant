<?php
namespace Nhrada\AIDeveloperAssistant;

if (!defined('ABSPATH'))
    exit;

/**
 * Assets handler class
 */
class Assets
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_footer', [$this, 'output_custom_js'], 99);
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_admin_assets($hook)
    {
        if ('toplevel_page_nhrada-settings' !== $hook) {
            return;
        }

        $asset_file = NHRADA_PLUGIN_DIR . 'admin/build/index.asset.php';
        if (!file_exists($asset_file)) {
            return;
        }

        $assets = require $asset_file;

        wp_enqueue_script(
            'nhrada-app',
            NHRADA_URL . 'admin/build/index.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );

        wp_enqueue_style(
            'nhrada-app-css',
            NHRADA_URL . 'admin/build/style-index.css',
            [],
            $assets['version']
        );
    }

    /**
     * Output user-stored custom JS in the frontend footer.
     * Uses direct output rather than wp_enqueue_script because the code
     * is dynamic DB content, not a static file with a URL.
     *
     * @return void
     */
    public function output_custom_js()
    {
        $js = get_option('nhrada_custom_js', '');
        if (!empty($js)) {
            echo "<script type='text/javascript'>\n" . $js . "\n</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
        }
    }
}
