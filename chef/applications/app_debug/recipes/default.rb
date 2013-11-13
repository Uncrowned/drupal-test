include_recipe "phpmyadmin"
include_recipe "app_debug::xdebug"

app_nginx_vhost 'pma.#{node['fqdn']}' do
  variables 'server_name' => 'pma.#{node['fqdn']}',
        'root' => "#{node['phpmyadmin']['home']}",
        'access_log' => "#{node['nginx']['log_dir']}/pma.access.log",
        'php_fpm_socket' => "unix:#{node['phpmyadmin']['socket']}"
end
