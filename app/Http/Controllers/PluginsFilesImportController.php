<?php
namespace TransferPress\Http\Controllers;

use TransferPress\Services\PluginsFilesImportService;
class PluginsFilesImportController {
    public function __construct(){
        add_action('wp_ajax_plugins_transfer_press_import', [$this, 'handlePluginsImport']);
    }
    public function handlePluginsImport(): void
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }
        check_ajax_referer('wp_transfer_press_setting', 'nonce');

        if (empty($_FILES['plugin_zip']) || !isset($_FILES['plugin_zip']['tmp_name'])) {
            wp_send_json_error(['message' => 'No valid file uploaded.']);
        }

        $file = $_FILES['plugin_zip'];

        // Validate file type (optional but recommended)
        $filetype = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
        if ($filetype['ext'] !== 'zip' || $filetype['type'] !== 'application/zip') {
            wp_send_json_error(['message' => 'Only ZIP files are allowed.']);
        }

        $service = new PluginsFilesImportService();
        $result = $service->importPlugin($file);

        if ($result['success']) {
            wp_send_json_success([
                'message' => $result['message'],
                'plugin_url' => $result['plugin_url']
            ]);
        } else {
            wp_send_json_error([
                'message' => $result['message']
            ]);
        }
    }
}
