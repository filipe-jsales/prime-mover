<?php

use  GreenMainframe\GMMoverFramework\classes\GMMover ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverControlPanel ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverSettings ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverBackupManagement ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverDeleteUtilities ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverBackupDirectorySize ;
use  GreenMainframe\GMMoverFramework\advance\PrimeMoverTroubleshooting ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverTroubleshootingMarkup ;
use  GreenMainframe\GMMoverFramework\advance\PrimeMoverUploadSettings ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverUploadSettingMarkup ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverReset ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverPanelValidationUtilities ;
use  GreenMainframe\GMMoverFramework\utilities\PrimeMoverSettingsMarkups ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayCustomDirSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayExcludedPluginsSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayExcludedUploadSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayMaintenanceSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplaySecuritySettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayDropBoxSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayEncryptionSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayGDriveSettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplaySettings ;
use  GreenMainframe\GMMoverFramework\app\PrimeMoverDisplayRunTimeSettings ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( defined( 'PRIME_MOVER_PANEL_PLUGINPATH' ) ) {
    return;
}
define( 'PRIME_MOVER_PANEL_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'PRIME_MOVER_PANEL_VERSION', '1.8.0' );
define( 'PRIME_MOVER_PANEL_MAINPLUGIN_FILE', __FILE__ );
define( 'PRIME_MOVER_PANEL_PLUGINBASENAME', plugin_basename( PRIME_MOVER_PANEL_MAINPLUGIN_FILE ) );
if ( !defined( 'PRIME_MOVER_DROPBOX_UPLOAD_CHUNK' ) ) {
    define( 'PRIME_MOVER_DROPBOX_UPLOAD_CHUNK', 1048576 );
}
if ( !defined( 'PRIME_MOVER_GDRIVE_UPLOAD_CHUNK' ) ) {
    define( 'PRIME_MOVER_GDRIVE_UPLOAD_CHUNK', 1048576 );
}
if ( !defined( 'PRIME_MOVER_GDRIVE_DOWNLOAD_CHUNK' ) ) {
    define( 'PRIME_MOVER_GDRIVE_DOWNLOAD_CHUNK', 1048576 );
}
include PRIME_MOVER_PANEL_PLUGINPATH . '/PrimeMoverPanelLoader.php';
add_action(
    'prime_mover_load_module_apps',
    'loadPrimeMoverControlPanel',
    25,
    2
);
function loadPrimeMoverControlPanel( GMMover $prime_mover, array $utilities )
{
    if ( empty($utilities['freemius_integration']) ) {
        return;
    }
    $freemius_integration = $utilities['freemius_integration'];
    $system_authorization = $prime_mover->getSystemAuthorization();
    $settings_markup = new PrimeMoverSettingsMarkups( $prime_mover, $utilities );
    $prime_mover_settings = new PrimeMoverSettings(
        $prime_mover,
        $system_authorization,
        $utilities,
        $settings_markup
    );
    $prime_mover_settings->initHooks();
    $delete_utilities = new PrimeMoverDeleteUtilities(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings
    );
    $backupdir_size = new PrimeMoverBackupDirectorySize(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings
    );
    $backup_management = new PrimeMoverBackupManagement(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings,
        $delete_utilities,
        $backupdir_size
    );
    $backup_management->initHooks();
    $prime_mover_custom_dir_settings = new PrimeMoverDisplayCustomDirSettings( $prime_mover_settings );
    $prime_mover_excludedplugin_settings = new PrimeMoverDisplayExcludedPluginsSettings( $prime_mover_settings );
    $prime_mover_excludeduploads_settings = new PrimeMoverDisplayExcludedUploadSettings( $prime_mover_settings );
    $prime_mover_display_maintenance_settings = new PrimeMoverDisplayMaintenanceSettings( $prime_mover_settings );
    $prime_mover_display_security_settings = new PrimeMoverDisplaySecuritySettings( $prime_mover_settings );
    $prime_mover_display_dropbox_settings = new PrimeMoverDisplayDropBoxSettings( $prime_mover_settings );
    $prime_mover_display_encryption_settings = new PrimeMoverDisplayEncryptionSettings( $prime_mover_settings );
    $prime_mover_display_gdrive_settings = new PrimeMoverDisplayGDriveSettings( $prime_mover_settings );
    $prime_mover_runtime_setings = new PrimeMoverDisplayRunTimeSettings( $prime_mover_settings );
    $prime_mover_runtime_setings->initHooks();
    $prime_mover_display_settings = new PrimeMoverDisplaySettings(
        $prime_mover_custom_dir_settings,
        $prime_mover_excludedplugin_settings,
        $prime_mover_excludeduploads_settings,
        $prime_mover_display_maintenance_settings,
        $prime_mover_display_security_settings,
        $prime_mover_display_dropbox_settings,
        $prime_mover_display_encryption_settings,
        $prime_mover_display_gdrive_settings,
        $prime_mover_runtime_setings
    );
    $prime_mover_display_settings->initHooks();
    $troubleshooting_markup = new PrimeMoverTroubleshootingMarkup(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings
    );
    $troubleshooting = new PrimeMoverTroubleshooting(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings,
        $troubleshooting_markup
    );
    $troubleshooting->initHooks();
    $upload_setting_markup = new PrimeMoverUploadSettingMarkup(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings
    );
    $upload_settings = new PrimeMoverUploadSettings(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings,
        $upload_setting_markup
    );
    $upload_settings->initHooks();
    $reset_setting = new PrimeMoverReset(
        $prime_mover,
        $system_authorization,
        $utilities,
        $prime_mover_settings
    );
    $reset_setting->initHooks();
    $validation_utilities = new PrimeMoverPanelValidationUtilities( $prime_mover, $utilities, $troubleshooting );
    $validation_utilities->initHooks();
    $prime_mover_panel = new PrimeMoverControlPanel( $prime_mover, $system_authorization, $utilities );
    $prime_mover_panel->initHooks();
    $panel_resources = [
        'prime_mover'          => $prime_mover,
        'authorization'        => $system_authorization,
        'freemius_integration' => $freemius_integration,
        'utilities'            => $utilities,
        'settings'             => $prime_mover_settings,
        'backup_management'    => $backup_management,
    ];
    do_action( 'prime_mover_panel_loaded', $panel_resources );
}
