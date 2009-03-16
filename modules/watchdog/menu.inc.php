<?php
function watchdog_menu_may_cache(&$items)
{
  $items[] = array('path' => 'admin/logs/watchdog', 'title' => t('Recent log entries'),
    'description' => t('View events that have recently been logged.'),
    'callback' => 'watchdog_overview',
    'weight' => -1);
  $items[] = array('path' => 'admin/logs/page-not-found', 'title' => t("Top 'page not found' errors"),
    'description' => t("View 'page not found' errors (404s)."),
    'callback' => 'watchdog_top',
    'callback arguments' => array('page not found'));
  $items[] = array('path' => 'admin/logs/access-denied', 'title' => t("Top 'access denied' errors"),
    'description' => t("View 'access denied' errors (403s)."),
    'callback' => 'watchdog_top',
    'callback arguments' => array('access denied'));
  $items[] = array('path' => 'admin/logs/event', 'title' => t('Details'),
    'callback' => 'watchdog_event',
    'type' => MENU_CALLBACK);
}
