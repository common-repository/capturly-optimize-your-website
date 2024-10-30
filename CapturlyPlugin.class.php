<?php
class CapturlyPlugin {
    private $pluginFilePath;
    private $optionPrefix = 'capturly-';

    const OPTION_SITE_ID = 'site-id';
    const OPTION_ACCOUNT_ID = 'account-id';

    private $options = [
        self::OPTION_SITE_ID,
        self::OPTION_ACCOUNT_ID,
    ];

    public function __construct()
    {
        $this->pluginFilePath = __FILE__;
    }

    public function activate()
    {

    }

    public function deactivate()
    {
        foreach ($this->options as $option) {
            $this->deleteOption($option);
        }
    }

    public function capturlyAddSettingsMenu()
    {
        global $menu, $submenu;
        $permission = 'edit_pages';

        $topMenu = false;
        foreach ($menu as $key => $item) {
            if ($item[0] === 'Capturly') {
                $topMenu = $item;
                break;
            }
        }
        if ($topMenu === false) {
            add_menu_page(
                'Capturly',
                'Capturly',
                $permission,
                $this->getPluginSlug() . DIRECTORY_SEPARATOR . 'settings.php',
                '',
                $this->getPluginFileUrl('/static/img/capturly-icon.png'),
            );
        } else {
            if (!isset($submenu[ $topMenu[2] ])) {
                add_submenu_page(
                    $topMenu[2],
                    'Capturly',
                    $topMenu[3],
                    $permission,
                    $topMenu[2]
                );
            }
            add_submenu_page(
                $topMenu[2],
                'Capturly',
                'Capturly sessions',
                $permission,
                'Capturly sessions'
            );
        }
    }

    public function getSiteId()
    {
        return $this->getOption(self::OPTION_SITE_ID);
    }

    public function getAccountId()
    {
        return $this->getOption(self::OPTION_ACCOUNT_ID);
    }

    public function registerWebsite($accountId)
    {
        $response = wp_remote_get( 'https://capturly.com/wordpress/validate-site-id?account=' . $accountId . '&url=' . get_site_url());
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode($body, true);

        if ($data['success'] ?? false) {
            return $this->updateOption(self::OPTION_SITE_ID, $data['siteId']) &&
                $this->updateOption(self::OPTION_ACCOUNT_ID, $accountId);
        }


        return [
            'success' => $data['success'],
            'message' => $data['message']
        ];
    }

    private function getOption($name)
    {
        if (!in_array($name, $this->options)) {
            return 'Error, not in options';
        }

        return get_option($this->optionPrefix . $name, null);
    }

    public function deleteOption($name)
    {
        if (!in_array($name, $this->options)) {
            return 'Error, not in options';
        }

        return delete_option($this->optionPrefix . $name);
    }

    public function updateOption($name, $value, $autoLoad = false)
    {
        if (!in_array($name, $this->options)) {
            return 'Error, not in options';
        }

        return update_option($this->optionPrefix. $name, $value, $autoLoad);
    }

    public function isWebsiteConnected()
    {
        return $this->getSiteId() !== null && $this->getAccountId() !== null;
    }

    public function echoTrackingCode()
    {
        if ($this->isWebsiteConnected()) {
            wp_register_script( 'capturly_tracking_code', '',);
            wp_enqueue_script( 'capturly_tracking_code' );
            wp_add_inline_script("capturly_tracking_code", "function trq(){(trq.q=trq.q||[]).push(arguments);}
                trq('account', '" . $this->getAccountId() . "');
                trq('collectorUrl', 'https://collector.capturly.com/collect');
                var _paq=_paq||[];
                _paq.push(['enableLinkTracking']);
                (function() {
                    var u='//capturly.com/';
                    _paq.push(['setTrackerUrl', 'https://collector.capturly.com/track']);
                    _paq.push(['setSiteId', '" . $this->getSiteId() . "']);
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'capturly-track-js.js';
                    s.parentNode.insertBefore(g,s);
                })();");
        }
    }

    public function capturlyAddScripts()
    {
        if (file_exists($this->getPluginDir() . 'static' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'admin-page-settings.css')) {
            wp_enqueue_style('capturly_admin_page_settings_css', $this->getPluginFileUrl('static/css/admin-page-settings.css'));
        }
    }

    public function getPluginSlug()
    {
        return basename($this->getPluginDir());
    }

    public function getPluginFileUrl($file)
    {
        $url = plugins_url($file, $this->pluginFilePath);

        return $url;
    }

    private function getPluginDir()
    {
        return plugin_dir_path($this->pluginFilePath);
    }
}