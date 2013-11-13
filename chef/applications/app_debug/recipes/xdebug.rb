if platform?(%w{debian ubuntu})
  package "php5-xdebug"
elsif platform?(%w{centos redhat fedora amazon scientific})
  php_pear "xdebug" do
    action :install
  end
end

template "#{node['php']['ext_conf_dir']}/xdebug.ini" do
  mode "0644"
  source "xdebug.erb"
  variables(
    :params => node['xdebug']
  )
  notifies :restart, "service[php5-fpm]", :delayed
end

service 'php5-fpm' do
  service_name 'php-fpm' if platform_family?('rhel', 'fedora')
	action :nothing
end


if platform_family?('debian', 'rhel')
  include_recipe 'iptables'
  iptables_rule 'port_xdebug' do
    enable true
  end
end
