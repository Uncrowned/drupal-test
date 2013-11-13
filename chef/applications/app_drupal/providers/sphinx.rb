action :create do
  template "#{new_resource.directory}/#{new_resource.name}.settings" do
    source "sphinx_settings.erb"
    cookbook 'app_drupal'
    mode 0744
    variables(
      :name => new_resource.name,
      :host => new_resource.host,
      :port => new_resource.port
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
