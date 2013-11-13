include_recipe "app_nginx"
include_recipe "app_php::server"
include_recipe "memcached"
include_recipe "app_drupal::develop"
