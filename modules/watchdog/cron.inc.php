<?php

/**
 * Implementation of hook_cron().
 *
 * Remove expired log messages and flood control events.
 */
function real_watchdog_cron() {
  db_query('DELETE FROM {watchdog} WHERE timestamp < %d', time() - variable_get('watchdog_clear', 604800));
  db_query('DELETE FROM {flood} WHERE timestamp < %d', time() - 3600);
}
