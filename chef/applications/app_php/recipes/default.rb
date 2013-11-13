include_recipe "php"
include_recipe "php::module_xml"
package "php-pdo"

settings = {}
node['php']['ini_settings'].map { |p, f| settings[p] = f }
settings['memory_limit'] = -1
settings['max_execution_time'] = 0
settings['max_input_time'] = -1
template "#{node['php']['conf_dir']}/php-cli.ini" do
  cookbook 'php'
  source 'php.ini.erb'
  owner 'root'
  group 'root'
  mode 0644
  variables({:settings => settings})
end

