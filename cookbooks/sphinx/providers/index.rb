action :create do

    conf_path = "#{node[:sphinx][:source][:install_path]}/conf.d/#{new_resource.name}_index.txt"
    data_path = "#{node[:sphinx][:source][:install_path]}/data/#{new_resource.name}_index"

    if !new_resource.service_name.empty?
      service "#{new_resource.service_name}" do
        action :nothing
        subscribes :restart,  "template[#{conf_path}]"
      end
    end

    a = template conf_path do
        cookbook "sphinx"
        source "index.erb"
        owner node['sphinx']['user']
        group node['sphinx']['group']
        mode 00755
        variables :new_resource => new_resource,
                  :data_path => data_path
    end

    new_resource.updated_by_last_action(a.updated_by_last_action?)
end


action :reindex do
    execute "Reindexing #{new_resource.name}" do
        rotate = new_resource.rotate ? "--rotate" : ""
        config = "--config #{node[:sphinx][:source][:install_path]}/sphinx.conf"
        command "indexer #{config} #{rotate} #{new_resource.name}"
    end

    new_resource.updated_by_last_action(true)
end

action :delete do

    conf_path = "#{node[:sphinx][:source][:install_path]}/conf.d/#{new_resource.name}_index.txt"

    execute "Deleting #{new_resource.name}" do
        command "rm #{conf_path}"
    end

    new_resource.updated_by_last_action(a.updated_by_last_action?)
end
