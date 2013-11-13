template "#{node['mysql']['conf_dir']}/my.cnf" do
  cookbook 'app_mysql'
  action :nothing
end
include_recipe "mysql::server"
