<?php
  //Основано на мативах функции theme в Drupal

include_once "lib/template.php";
function theme() {
  $args = func_get_args();
  $hook = array_shift($args);
  
  static $template=null;
  if (!$template) $template = new Template("theme/default/");

  $template->set_vars(Array(), true); // clear vars
  foreach ($args as $vals) $template->set_vars($vals);

  return $template->fetch($hook.".tpl.php");

}
?>