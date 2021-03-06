<?php

/**
 * Implementation of hook_user_default_roles().
 */
function features_deonline_roles_user_default_roles() {
  $roles = array();

  // Exported role: admin
  $roles['admin'] = array(
    'name' => 'admin',
  );

  // Exported role: anonymous user
  $roles['anonymous user'] = array(
    'name' => 'anonymous user',
  );

  // Exported role: authenticated user
  $roles['authenticated user'] = array(
    'name' => 'authenticated user',
  );

  // Exported role: sudo
  $roles['sudo'] = array(
    'name' => 'sudo',
  );

  // Exported role: teacher
  $roles['teacher'] = array(
    'name' => 'teacher',
  );

  return $roles;
}
