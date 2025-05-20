<?php
namespace TransferPress\Services;
class TranprPluginsImportService{
    public function importPlugin($zipFile): array {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // Upload the ZIP file to a temporary location
        $uploaded = wp_handle_upload($zipFile, ['test_form' => false]);

        if (!empty($uploaded['error'])) {
            return ['success' => false, 'message' => 'Upload error: ' . $uploaded['error']];
        }

        $zipPath = $uploaded['file'];

        // Prepare the filesystem
        WP_Filesystem();
        $fs = new \WP_Filesystem_Direct(false);

        // Use Plugin_Upgrader to install the plugin with silent skin
        $skin = new \Automatic_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);

        ob_start();
        $install_result = $upgrader->install($zipPath);
        ob_end_clean();

        // Always clean up the uploaded ZIP
        $fs->delete($zipPath);

        if (is_wp_error($install_result)) {
            return ['success' => false, 'message' => 'Plugin installation failed: ' . $install_result->get_error_message()];
        }

        // Get plugin main file path
        $plugin_main_file = $upgrader->plugin_info();

        if (!$plugin_main_file || !file_exists(WP_PLUGIN_DIR . '/' . $plugin_main_file)) {
            return ['success' => false, 'message' => 'Plugin installed, but main file could not be determined.'];
        }

        // Activate the plugin
        $activation_result = activate_plugin($plugin_main_file);

        if (is_wp_error($activation_result)) {
            return [
                'success' => true,
                'message' => 'Plugin installed, but failed to activate: ' . $activation_result->get_error_message(),
                'plugin_url' => admin_url('plugins.php'),
            ];
        }

        return [
            'success' => true,
            'message' => 'Plugin installed and activated successfully.',
            'plugin_url' => admin_url('plugins.php'),
        ];
    }
}
