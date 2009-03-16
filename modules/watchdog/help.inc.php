<?php

/**
 * Implementation of hook_help().
 */
function real_watchdog_help($section) {
  switch ($section) {
    case 'admin/help#watchdog':
      $output = '<p>'. t('The watchdog module monitors your system, capturing system events in a log to be reviewed by an authorized individual at a later time. This is useful for site administrators who want a quick overview of activities on their site. The logs also record the sequence of events, so it can be useful for debugging site errors.') .'</p>';
      $output .= '<p>'. t('The watchdog log is simply a list of recorded events containing usage data, performance data, errors, warnings and operational information. Administrators should check the watchdog report on a regular basis to ensure their site is working properly.') .'</p>';
      $output .= '<p>'. t('For more information please read the configuration and customization handbook <a href="@watchdog">Watchdog page</a>.', array('@watchdog' => 'http://drupal.org/handbook/modules/watchdog/')) .'</p>';
      return $output;
    case 'admin/logs':
      return '<p>'. t('The watchdog module monitors your web site, capturing system events in a log to be reviewed by an authorized individual at a later time. The watchdog log is simply a list of recorded events containing usage data, performance data, errors, warnings and operational information. It is vital to check the watchdog report on a regular basis as it is often the only way to tell what is going on.') .'</p>';
  }
}
