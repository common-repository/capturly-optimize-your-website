<?php
$capturly_AccountId = isset($_POST['capturly-account-id']) ? sanitize_text_field($_POST['capturly-account-id']) : null;
$capturly_ErrorMessage = '';
if ($capturly_AccountId !== null) {
    if (strlen($capturly_AccountId) !== 24) {
        $capturly_ErrorMessage = "Incorrect website id form!";
    } else if (($response = $capturlyPlugin->registerWebsite($capturly_AccountId)) !== true) {
        $capturly_ErrorMessage = $response['message'];
    }
}

$capturly_disconnect = isset($_POST['disconnect']) ? sanitize_text_field($_POST['disconnect']) : 0;
if ($capturly_disconnect === '1') {
    if ($capturlyPlugin->getSiteId() !== null) {
        $capturlyPlugin->deleteOption($capturlyPlugin::OPTION_SITE_ID);
        $capturlyPlugin->deleteOption($capturlyPlugin::OPTION_ACCOUNT_ID);
    } else {
        $capturly_ErrorMessage = "Site is already disconnected!";
    }
}


/**
$step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : null;
if ($step == 'account_type') {
    $selectedAccountType = $_POST['account_type'];
}
*/
?>

<div class="capturly-container setup">
    <?php if ($capturly_ErrorMessage !== '') { ?>
        <div class="flash">
            <div class="capturly-error">
                <?= $capturly_ErrorMessage ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($capturlyPlugin->isWebsiteConnected()) { ?>
        <div class="title row">
            <h3 class="roboto" style="font-weight: normal">Your website is connected!</h3>
        </div>
        
        <div class="row title">
            <img src="<?= $capturlyPlugin->getPluginFileUrl('/static/img/connected.svg') ?>" alt="" width="150">
        </div>

        <div class="row" style="text-align: center; margin-top: 50px">
            Keep in mind that <a href="https://capturly.com">Capturly</a> might need a little time to process and visualize the data, so take a moment to explore its features while your insights are being prepared.
        </div>

        <form action="<?php echo admin_url('admin.php?page='.$capturlyPlugin->getPluginSlug().'/settings.php&tab=setup'); ?>" method="POST">
            <input type="hidden" value="1" name="disconnect">

            <div class="row" style="text-align: right">
                <button type="submit" class="btn btn-gray">Disconnect</button>
            </div>
        </form>
    <?php } else { ?>
        <div class="row">
            <p class="roboto info-title">Enter your unique Capturly website ID to connect your WordPress website and Capturly accounts.</p>
        </div>

        <form action="<?php echo admin_url('admin.php?page='.$capturlyPlugin->getPluginSlug().'/settings.php&tab=setup'); ?>" method="POST" style="margin-top: 50px; margin-bottom: 50px">
            <div class="row text-center">
                <div class="row">
                    <input required="required" size="24" type="text" id="account_id" placeholder="Website ID" name="capturly-account-id" value="">
                </div>
            </div>

            <div class="row text-center">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

        <hr>

        <div class="row" style="display: flex; justify-content: center; align-items: center">
            <img src="<?= $capturlyPlugin->getPluginFileUrl('/static/img/info-icon.png') ?>" alt="" width="50" height="50">
            <div>
                <div class="roboto" style="margin-left: 15px; font-size: 14px">
                    Don’t find your website ID? In your Capturly account, go to the Settings, then select your website from the left menu. At the top of the page, you will find your website ID.
                </div>
            </div>
        </div>

    <?php } ?>



</div>
