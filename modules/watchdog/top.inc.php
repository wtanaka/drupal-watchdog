<?php

/**
 * Menu callback; generic function to display a page of the most frequent
 * watchdog events of a specified type.
 */
function real_watchdog_top($type) {

  $header = array(
    array('data' => t('Count'), 'field' => 'count', 'sort' => 'desc'),
    array('data' => t('Message'), 'field' => 'message')
  );

  $result = pager_query("SELECT COUNT(wid) AS count, message FROM {watchdog} WHERE type = '%s' GROUP BY message ". tablesort_sql($header), 30, 0, "SELECT COUNT(DISTINCT(message)) FROM {watchdog} WHERE type = '%s'", $type);

  while ($watchdog = db_fetch_object($result)) {
    $rows[] = array($watchdog->count, truncate_utf8($watchdog->message, 56, TRUE, TRUE));
  }

  if (!$rows) {
    $rows[] = array(array('data' => t('No log messages available.'), 'colspan' => 2));
  }

  $output  = theme('table', $header, $rows);
  $output .= theme('pager', NULL, 30, 0);

  return $output;
}
