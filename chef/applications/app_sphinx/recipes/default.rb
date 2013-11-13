include_recipe "sphinx"

directory "/var/log/sphinx" do
  action :create
  mode "666"
  owner "root"
  group "root"
end
