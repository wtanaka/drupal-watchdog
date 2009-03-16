<?php

function watchdog_form_overview() {
  $names['all'] = t('all messages');
  foreach (_watchdog_get_message_types() as $type) {
    $names[$type] = t('!type messages', array('!type' => t($type)));
  }

  if (empty($_SESSION['watchdog_overview_filter'])) {
    $_SESSION['watchdog_overview_filter'] = 'all';
  }

  $form['filter'] = array(
    '#type' => 'select',
    '#title' => t('Filter by message type'),
    '#options' => $names,
    '#default_value' => $_SESSION['watchdog_overview_filter']
  );
  $form['submit'] = array('#type' => 'submit', '#value' => t('Filter'));
  $form['#redirect'] = FALSE;

  return $form;
}

/**
 * Menu callback; displays a listing of log messages.
 */
function real_watchdog_overview() {
  $icons = array(WATCHDOG_NOTICE  => '',
                 WATCHDOG_WARNING => theme('image', 'misc/watchdog-warning.png', t('warning'), t('warning')),
                 WATCHDOG_ERROR   => theme('image', 'misc/watchdog-error.png', t('error'), t('error')));
  $classes = array(WATCHDOG_NOTICE => 'watchdog-notice', WATCHDOG_WARNING => 'watchdog-warning', WATCHDOG_ERROR => 'watchdog-error');

  $output = drupal_get_form('watchdog_form_overview');

  $header = array(
    ' ',
    array('data' => t('Type'), 'field' => 'w.type'),
    array('data' => t('Date'), 'field' => 'w.wid', 'sort' => 'desc'),
    array('data' => t('Message'), 'field' => 'w.message'),
    array('data' => t('User'), 'field' => 'u.name'),
    array('data' => t('Operations'))
  );

  $sql = "SELECT w.wid, w.uid, w.severity, w.type, w.timestamp, w.message, w.link, u.name FROM {watchdog} w INNER JOIN {users} u ON w.uid = u.uid";
  $tablesort = tablesort_sql($header);
  $type = $_SESSION['watchdog_overview_filter'];
  if ($type != 'all') {
    $result = pager_query($sql ." WHERE w.type = '%s'". $tablesort, 50, 0, NULL, $type);
  }
  else {
    $result = pager_query($sql . $tablesort, 50);
  }

  while ($watchdog = db_fetch_object($result)) {
    $rows[] = array('data' =>
      array(
        // Cells
        $icons[$watchdog->severity],
        t($watchdog->type),
        format_date($watchdog->timestamp, 'small'),
        l(truncate_utf8($watchdog->message, 56, TRUE, TRUE), 'admin/logs/event/'. $watchdog->wid, array(), NULL, NULL, FALSE, TRUE),
        theme('username', $watchdog),
        $watchdog->link,
      ),
      // Attributes for tr
      'class' => "watchdog-". preg_replace('/[^a-z]/i', '-', $watchdog->type) .' '. $classes[$watchdog->severity]
    );
  }

  if (!$rows) {
    $rows[] = array(array('data' => t('No log messages available.'), 'colspan' => 6));
  }

  $output .= theme('table', $header, $rows);
  $output .= theme('pager', NULL, 50, 0);

  return $output;
}


function theme_watchdog_form_overview($form) {
  return '<div class="container-inline">'. drupal_render($form) .'</div>';
}

function watchdog_form_overview_submit($form_id, $form_values) {
  $_SESSION['watchdog_overview_filter'] = $form_values['filter'];
}

function _watchdog_get_message_types() {
  $types = array();

  $result = db_query('SELECT DISTINCT(type) FROM {watchdog} ORDER BY type');
  while ($object = db_fetch_object($result)) {
    $types[] = $object->type;
  }

  return $types;
}
