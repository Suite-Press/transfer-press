<?php
namespace TransferPress\Http\Controllers;

use TransferPress\Models\PluginsFilesExport;
use TransferPress\Services\PluginsFilesExportService;
use TransferPress\Services\Helpers\FileSystemHelper;
class PluginsFilesExportController{
    public function __construct(){
        add_action('wp_ajax_plugins_transfer_press_list', [$this, 'getPluginList']);
        add_action('wp_ajax_plugins_transfer_press_export', [$this, 'getExportPlugin']);
        add_action('wp_ajax_plugins_transfer_press_details', [$this, 'getPluginDetails']);
    }
    public function getPluginList(): void
    {
        $model = new PluginsFilesExport();
        wp_send_json($model->getInstalledPlugins());
    }
    public function getPluginDetails(): void {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }
        check_ajax_referer('wp_transfer_press_setting', 'nonce');
//        $slug = sanitize_text_field($_GET['plugin'] ?? '');
        $slug = isset($_GET['plugin']) ? sanitize_text_field(wp_unslash($_GET['plugin'])) : '';
        if (!$slug) {
            wp_send_json_error(['message' => 'Missing plugin slug']);
        }

        $model = new PluginsFilesExport();
        $details = $model->getPluginDetails($slug);

        if (empty($details)) {
            wp_send_json_error(['message' => 'Plugin not found']);
        }

        wp_send_json_success(['plugin' => $details]);
    }
    public function getExportPlugin(): void
    {
        check_ajax_referer('wp_transfer_press_setting', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

//        $plugin = sanitize_text_field($_POST['plugin']);
        $plugin = isset($_POST['plugin']) ? sanitize_text_field(wp_unslash($_POST['plugin'])) : '';
        if (!$plugin) {
            wp_send_json_error(['message' => 'Missing plugin slug.']);
        }

        $service = new PluginsFilesExportService();
        $fileUrl = $service->exportPluginFiles($plugin);
        if (!$fileUrl) {
            wp_send_json_error(['message' => 'Failed to generate ZIP.']);
        }

        $uploadDir = wp_upload_dir();
        $filePath = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $fileUrl);

        $fileSystem = FileSystemHelper::getFilesystem();

        if (!file_exists($filePath) || !is_readable($filePath)) {
            wp_send_json_error(['message' => 'ZIP file not found or not readable.']);
        }

        // Prevent any output before file streaming
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        flush();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe: Outputting raw file content for ZIP download
        echo $fileSystem->get_contents($filePath);
        exit;
    }
}
