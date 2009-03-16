<?php

/**
 * Menu callback; displays details about a log message.
 */
function real_watchdog_event($id) {
  $severity = array(WATCHDOG_NOTICE => t('notice'), WATCHDOG_WARNING => t('warning'), WATCHDOG_ERROR => t('error'));
  $output = '';
  $result = db_query('SELECT w.*, u.name, u.uid FROM {watchdog} w INNER JOIN {users} u ON w.uid = u.uid WHERE w.wid = %d', $id);
  if ($watchdog = db_fetch_object($result)) {
    $rows = array(
      array(
        array('data' => t('Type'), 'header' => TRUE),
        t($watchdog->type),
      ),
      array(
        array('data' => t('Date'), 'header' => TRUE),
        format_date($watchdog->timestamp, 'large'),
      ),
      array(
        array('data' => t('User'), 'header' => TRUE),
        theme('username', $watchdog),
      ),
      array(
        array('data' => t('Location'), 'header' => TRUE),
        l($watchdog->location, $watchdog->location),
      ),
      array(
        array('data' => t('Referrer'), 'header' => TRUE),
        l($watchdog->referer, $watchdog->referer),
      ),
      array(
        array('data' => t('Message'), 'header' => TRUE),
        $watchdog->message,
      ),
      array(
        array('data' => t('Severity'), 'header' => TRUE),
        $severity[$watchdog->severity],
      ),
      array(
        array('data' => t('Hostname'), 'header' => TRUE),
        $watchdog->hostname,
      ),
    );
    $attributes = array('class' => 'watchdog-event');
    $output = theme('table', array(), $rows, $attributes);
  }
  return $output;
}
