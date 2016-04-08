<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'] = array(
  'class'    => 'DatabaseHook',
  'function' => 'create_if_not_exists',
  'filename' => 'DatabaseHook.php',
  'filepath' => 'hooks',
  'params'   => array()
);

$hook['post_controller_constructor'] = array(
  'class'    => 'ViewHook',
  'function' => 'before',
  'filename' => 'ViewHook.php',
  'filepath' => 'hooks',
  'params'   => array()
);

$hook['post_controller'] = array(
  'class'    => 'ViewHook',
  'function' => 'after',
  'filename' => 'ViewHook.php',
  'filepath' => 'hooks',
  'params'   => array()
);
