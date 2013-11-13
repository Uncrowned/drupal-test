action :create do
  template "#{new_resource.directory}/#{new_resource.name}.settings" do
    source "memcache_settings.erb"
    cookbook 'app_drupal'
    mode 0744
    variables(
      :name => new_resource.name,
      :prefix => new_resource.prefix,
      :handler => new_resource.handler,
      :handler_path => new_resource.handler_path,
      :servers => new_resource.servers,
      :bins => new_resource.bins
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
