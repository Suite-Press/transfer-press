<?php
namespace TransferPress;

use TransferPress\Hooks\Handlers\AdminMenuHandlers;
use TransferPress\Hooks\Handlers\RestApiHandlers;
use TransferPress\Http\Controllers\PluginsFilesExportController;
use TransferPress\Http\Controllers\PluginsFilesImportController;

class App {

    public function __construct() {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        $this->defineConstant();

       //Load Classes
       (new AdminMenuHandlers())->init();
        new PluginsFilesExportController();
        new PluginsFilesImportController();
    }
    private function defineConstant()
    {
        define( 'TRANSFER_PRESS', 'plugins-migrator' );
        define( 'TRANSFER_PRESS_PATH', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
        define( 'TRANSFER_PRESS_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
        define( 'TRANSFER_PRESS_BUILD_PATH', TRANSFER_PRESS_PATH . '/public/assets' );
        define( 'TRANSFER_PRESS_BUILD_URL', TRANSFER_PRESS_URL . '/public/assets' );
        define( 'TRANSFER_PRESS_VERSION', '1.0.0' );
    }
}
