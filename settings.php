<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (!current_user_can('edit_pages')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

// old tracking code replace, backward compatibility
$options = get_option('capturly_options');
if ($options && $options['capturly_tracking_code']) {
    preg_match("/'setSiteId',\s*'(\d+)']/", $options['capturly_tracking_code'], $matchesSiteId);
    preg_match("/'account',\s*'t-([^']{24})'/", $options['capturly_tracking_code'], $matchesAccountId);

    if (!empty($matchesSiteId) && !empty($matchesAccountId)) {
        $siteId = $matchesSiteId[1];
        $accountId = $matchesAccountId[1];

        delete_option('capturly_options');

        $capturlyPlugin->updateOption($capturlyPlugin::OPTION_SITE_ID, $siteId);
        $capturlyPlugin->updateOption($capturlyPlugin::OPTION_ACCOUNT_ID, $accountId);
    }
}

$tabs = [];
$tabs['Installation'] = 'setup';

$selectedTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'setup';

?>
<div class="capturly-settings-page">
    <h1 class="logo-container">
        <img src="<?= $capturlyPlugin->getPluginFileUrl('/static/img/logo.png') ?>" width="150">
    </h1>

    <div class="capturly-subtitle">
        Understand user behavior & increase your conversion rates
    </div>

    <div class="tab-wrapper">
        <?php foreach ($tabs as $title => $action) { ?>
            <a class="nav-tab <?= $selectedTab == $action ? 'nav-tab-active' : '' ?>" href="<?php echo admin_url('admin.php?page='.$capturlyPlugin->getPluginSlug().'/settings.php&tab='. esc_attr($action)); ?>"><?= $title ?></a>
        <?php } ?>
    </div>

    <div>
    <?php include(plugin_dir_path(__FILE__ ) . 'tabs' . DIRECTORY_SEPARATOR . $selectedTab . '.php'); ?>
    </div>
</div>


<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&display=swap" rel="stylesheet">
