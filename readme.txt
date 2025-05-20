=== Transfer Press ===
Contributors: suitepress
Donate link: https://suitepress.org/donate-transfer-press
Tags: plugin migration, plugin transfer, plugin import, plugin export, plugin download
Requires at least: 5.2
Tested up to: 6.8
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Transfer Press allows you to export and import plugin files from one WordPress site to another, easily and efficiently.

== Description ==

**Transfer Press** is a powerful plugin migration tool that lets you quickly export, clone, or transfer plugin files between WordPress sites. Whether you're moving plugins from site A to site B or simply backing up plugin files for later use, TransferPress simplifies the process with an intuitive UI. No need to manually download files via FTP or dig into your filesystem. This tool helps developers and site owners save time during migrations or staging setups.

== Features ==

* Export/download installed plugin files as ZIP archives.
* Import plugin ZIP files and activate them in one click.
* Intuitive interface for both export and import sections.
* Progress indicators and toast notifications for a smoother UX.
* Works with both single and multisite installations.

== Frequently Asked Questions ==

= How can I export plugins? =
Navigate to the **Transfer Press > Export** section. Select the plugin you want to export from the dropdown, and click "Export." A ZIP file will be generated and downloaded automatically.

= Can I import plugins? =
Yes! Head to the **Transfer Press > Import** section, upload a plugin ZIP file, and click "Import." The plugin will be installed and optionally activated.

= Does the plugin handle plugin activation during import? =
Yes, plugins can be activated after import if they are not already active.

= Is there any file type restriction during import? =
Yes. Only valid ZIP archives containing plugins are accepted.

= Where can I get support? =
For support, email: **suitepress24@gmail.com**, WordPress.org plugins support area.

== Screenshots ==

1. Export plugin screen.
2. Import plugin screen.
3. Plugin selection and progress feedback.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/transfer-press` directory, or install directly from the WordPress Plugin Directory.
2. Activate the plugin via the **Plugins** screen.
3. Go to **Transfer Press > Export** to download plugin files.
4. Go to **Transfer Press > Import** to upload and install plugin files.

== Usage ==

1. After activation, navigate to the **Transfer Press** menu in the WordPress dashboard.
2. Choose a plugin from the dropdown list to export, and click the **Export** button.
3. To import, upload a valid plugin ZIP file in the import section and click **Import**.
4. Once the import is complete, the plugin will be available on your site.

== Changelog ==

= 1.0.0 – April 19, 2024 =
* Initial release
* Added plugin export functionality
* Added plugin import and activation
* UI improvements with progress and success notifications

== Upgrade Notice ==

= 1.0.0 =
First release. Export and import WordPress plugins seamlessly between sites.

== License ==

This plugin is licensed under the **GPLv2 or later**. See [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html) for details.

== Contributing ==

We welcome contributions!
Please fork the GitHub repository, make your changes, and submit a pull request.

== Source Code ==
The source code for the minified JavaScript and CSS files used; is available in the GitHub repository:
https://github.com/Suite-Press/transfer-press

== Requirements ==

- **Node.js**: Version `18`
- **Composer**: Installed globally
- **NPM**: Installed globally

## Installation Steps

1. **Install PHP dependencies**
   Run:
   ```terminal
   composer install
   ```
2. **Dump autoload files**
   Run:
   ```terminal
   composer dump-autoload
   ```
3. **Install Node modules**
   Run:
   ```terminal
   npm install
   ```
4. **Run Dev Mode**
   Run:
   ```terminal
   npm run dev
   ```
5. **Run Build Mode**
   Run:
   ```terminal
   npm run build
   ```
