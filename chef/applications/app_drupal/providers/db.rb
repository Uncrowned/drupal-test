action :create do
  template "#{new_resource.directory}/#{new_resource.name}.settings" do
    source "db_settings.erb"
    cookbook 'app_drupal'
    mode 0744
    variables(
      :name => new_resource.name,
      :driver => new_resource.driver,
      :user => new_resource.user,
      :password => new_resource.password,
      :host => new_resource.host,
      :port => new_resource.port,
      :db_name => new_resource.db_name
    )
  end
  new_resource.updated_by_last_action(true)
end

action :delete do
  file "#{new_resource.directory}/#{new_resource.name}.settings" do
    action :delete
  end
  new_resource.updated_by_last_action(true)
end
