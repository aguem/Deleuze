<?php
$pwd = 'aiman';
$uid = 1;
 
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
 
$hash = user_hash_password($pwd);
echo db_query("UPDATE users SET pass = :hash WHERE uid = :uid", array(':hash'=> $hash, ':uid'=> $uid)) ? "Mot de passe change en '$pwd'" : 'Erreur lors du changement';
?>
