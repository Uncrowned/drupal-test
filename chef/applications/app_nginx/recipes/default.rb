include_recipe "nginx"
nginx_site "default" do
  enable false
end
if platform_family?('debian', 'rhel')
  include_recipe 'iptables'
  iptables_rule 'port_http' do
    enable true
  end
end
