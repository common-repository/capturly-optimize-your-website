<?php
/*
Plugin Name: Capturly - Optimize your website
Plugin Title: Capturly - Optimize your website
Plugin URI: https://wordpress.org/plugins/capturly-optimize-your-website/
Description: <a href="https://capturly.com/" target="_blank">Capturly</a> is a software tool for studying user behavior on your website. Among other features it allows you to record your users real-time, meaning a new level of customer analyzing. Create your Capturly account <a href="https://capturly.com/signup" target="_blank">here</a>, and paste in your website id <a href="admin.php?page=capturly-optimize-your-website/settings.php">here</a>.
Tags: analytics, optimization, CRO, visitors analytics, website, heatmap, clickmap, recording, session recording, session replay, hotjar, crazy egg, mouseflow, inspectlet, formisimo, scroll heatmap, click heatmap, playback, UX, user experience, UI, user interface, usability
Author: Capturly.com <hello@capturly.com>
Author URI: https://www.capturly.com/
Contributors: capturly
License: GPLv2 or later
Version: 2.0.1
Text Domain: capturly-optimize-your-website
Donate link: https://capturly.com/pricing
*/
/*
Copyright 2023 Capturly Inc (email: hello@capturly.com)
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once plugin_dir_path( __FILE__ ) . 'CapturlyPlugin.class.php';
$capturlyPlugin = new CapturlyPlugin();
register_activation_hook(__FILE__, [ $capturlyPlugin, 'activate' ]);
register_deactivation_hook(__FILE__, [ $capturlyPlugin, 'deactivate' ]);
add_action('admin_menu', [ $capturlyPlugin, 'capturlyAddSettingsMenu' ], 10);
add_action('admin_enqueue_scripts', [ $capturlyPlugin, 'capturlyAddScripts' ]);
add_action( 'wp_enqueue_scripts', [ $capturlyPlugin, 'echoTrackingCode' ] );