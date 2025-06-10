<?php
namespace TransferPress\Hooks\Handlers;

class TranprAdminMenuHandlers {

    public function __construct() {
        $this->init();
    }

    public function init(): void
    {
        add_action('admin_menu', [$this, 'tranpr_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'tranpr_conditionally_enqueue_assets']);
    }

    public function tranpr_conditionally_enqueue_assets($hook): void
    {

        // Only load assets on our plugin page
        if (
            strpos($hook, 'tranpr-export') === false &&
            strpos($hook, 'tranpr-import') === false &&
            strpos($hook, 'tranpr-support') === false
        ) {
            return;
        }
        $this->tranpr_enqueue_assets();
    }

    public function tranpr_admin_menu(): void
    {

        add_menu_page(
            __('Transfer Press', 'transfer-press'),
            __('Transfer Press', 'transfer-press'),
            'manage_options',
            'tranpr-export',
            [$this, 'tranpr_render_export_dashboard'],
            $this->tranpr_get_menu_icon(),
            26
        );
        add_submenu_page(
            "tranpr-export",
            __("Export","transfer-press"),
            __("Export","transfer-press"),
            "manage_options",
            "tranpr-export",
            [$this, 'tranpr_render_export_dashboard']
        );
        add_submenu_page(
            "tranpr-export",
            __("Import","transfer-press"),
            __("Import","transfer-press"),
            "manage_options",
            "tranpr-import",
            [$this, 'tranpr_render_import_dashboard']
        );
        add_submenu_page(
            "tranpr-export",
            __("Support","transfer-press"),
            __("Support","transfer-press"),
            "manage_options",
            "tranpr-support",
            [$this, 'tranpr_render_support_dashboard']
        );
    }

    public function tranpr_get_menu_icon(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" fill="none">
<path d="M7.80152 9.14362H11.3787C12.6375 9.14362 13.6544 10.1649 13.6544 11.4291C13.6544 12.6932 12.6375 13.7145 11.3787 13.7145H2.27574C1.01697 13.7145 0 12.6932 0 11.4291V2.28724C0 1.0231 1.01697 0.00178562 2.27574 0.00178562C3.53451 0.00178562 4.55148 1.0231 4.55148 2.28724V5.94398L5.80314 4.68698C12.0259 -1.56233 22.1102 -1.56233 28.333 4.68698C34.5557 10.9363 34.5557 21.0637 28.333 27.313C22.1102 33.5623 12.0259 33.5623 5.80314 27.313C4.91417 26.4203 4.91417 24.9704 5.80314 24.0777C6.6921 23.1849 8.13577 23.1849 9.02473 24.0777C13.4695 28.5415 20.6737 28.5415 25.1185 24.0777C29.5633 19.6139 29.5633 12.379 25.1185 7.91519C20.6737 3.4514 13.4695 3.4514 9.02473 7.91519L7.80152 9.14362Z" fill="#CFCFDE"/>
<path d="M23.6179 10.1547C23.8948 10.3449 24.0411 10.6751 23.9906 11.0061L22.2895 22.3861C22.2496 22.6514 22.0893 22.8845 21.8547 23.0167C21.6202 23.1489 21.3387 23.1666 21.0895 23.0637L17.8141 21.719L15.9501 23.7527C15.7079 24.0189 15.3255 24.108 14.9887 23.9782C14.6519 23.8484 14.4318 23.524 14.4303 23.1632L14.4205 20.8777C14.4201 20.7684 14.4606 20.6643 14.5341 20.582L19.0946 15.5651C19.2524 15.3922 19.2458 15.1271 19.0811 14.9637C18.9164 14.8004 18.6512 14.7906 18.4796 14.9472L12.9401 19.9108L10.5211 18.7128C10.2307 18.5691 10.0435 18.2801 10.0339 17.9576C10.0244 17.635 10.1926 17.3336 10.4707 17.1711L22.6883 10.1204C22.9801 9.95241 23.341 9.96727 23.6179 10.1547Z" fill="#CFCFDE"/>
</svg>');
    }
    public function tranpr_render_export_dashboard(): void
    {
        include_once TRANPR_PATH. '/app/Views/tranpr-export-admin-vue.php';
    }
    public function tranpr_render_import_dashboard(): void
    {
        include_once TRANPR_PATH. '/app/Views/tranpr-import-admin-vue.php';
    }
    public function tranpr_render_support_dashboard(): void
    {
        include_once TRANPR_PATH. '/app/Views/tranpr-support-admin-vue.php';
    }
    public function tranpr_enqueue_assets(): void
    {

        $dev_server = 'http://localhost:5173';
        $hot_file_path = TRANPR_PATH . '/.hot';
        $is_dev = file_exists($hot_file_path);

        if ($is_dev) {
            // Enqueue Vite HMR client and main entry
            wp_enqueue_script('vite-client', $dev_server . '/@vite/client', [],null, true);
            wp_enqueue_script('tranpr-vite', $dev_server . '/js/main.js',  [], null, true);

            wp_localize_script('tranpr-vite', 'TransferPressSettings', [
                'nonce'   => wp_create_nonce('tranpr_setting'),
                'ajaxurl' => admin_url('admin-ajax.php'),
            ]);
        } else {
            // Prod: Use filetime for cache busting
            $main_js = TRANPR_BUILD_PATH . '/main.js';
            $main_css = TRANPR_BUILD_PATH . '/main.css';

            $js_version = file_exists($main_js) ? filemtime($main_js) : '1.0.0';
            $css_version = file_exists($main_css) ? filemtime($main_css) : '1.0.0';

            wp_enqueue_script('tranpr-main', TRANPR_BUILD_URL . '/main.js', [], $js_version, true);
            wp_enqueue_style('tranpr-style', TRANPR_BUILD_URL . '/main.css',[],$css_version);

            wp_localize_script('tranpr-main', 'TransferPressSettings', [
                'nonce'   => wp_create_nonce('tranpr_setting'),
                'ajaxurl' => admin_url('admin-ajax.php'),
            ]);
        }

        // Optional: Add type="module" for both dev and prod
        add_filter('script_loader_tag', function ($tag, $handle) {
            if (in_array($handle, ['vite-client', 'tranpr-vite', 'tranpr-main'])) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 2);
    }
}
