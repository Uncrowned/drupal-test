def initialize(*args)
  super
  @action = :create
end

action :create do
  app_nginx_vhost new_resource.host do
    variables 'server_name' => new_resource.aliases.join(' '),
            'root' => new_resource.docroot,
            'access_log' => "#{node['nginx']['log_dir']}/#{new_resource.name}.access.log",
            'drupal_file_dir' => new_resource.drupal_file_dir,
            'php_fpm_socket' => "unix:/var/run/php5-#{new_resource.name}-fpm.sock"
  end

  # Define a pool
  php_fpm new_resource.name do
    action :add
    user 'nginx'
    group 'nginx'
    socket true
    socket_path "/var/run/php5-#{new_resource.name}-fpm.sock"
    socket_perms "0666"
    start_servers 2
    min_spare_servers 2
    max_spare_servers 8
    max_children 8
    terminate_timeout (node['php']['ini_settings']['max_execution_time'].to_i + 20)
    value_overrides({
     :error_log => "#{node['php']['fpm_log_dir']}/#{new_resource.name}.log"
    })
  end

  service 'php5-fpm' do
    service_name 'php-fpm' if platform_family?('rhel', 'fedora')
	  action :nothing
  end
  new_resource.updated_by_last_action(true)
end

action :delete do
  app_nginx_vhost new_resource.host do
    action :disable
  end

  php_fpm new_resource.name do
    action :remove
  end
  new_resource.updated_by_last_action(true)
end
