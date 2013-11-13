# Install with command
# $ vagrant plugin install vagrant-hostsupdater
require 'vagrant-hostsupdater'
require 'optparse'
require 'yaml'

environments = YAML.load_file('environments.yml')

active_environment = "local"
OptionParser.new do |opts|
  opts.on("-e [TYPE]", "--environment [TYPE]", environments.keys, "Environment #{environments.keys.join(', ')}") do |v|
    if v then
      active_environment = v
    end
  end
end.parse!

environment = environments[active_environment]

 Vagrant.configure("2") do |config|

   environment.each do |machinename, options|
    config.vm.define [active_environment, machinename].join('__') do |machine|
       machine.vm.box = "centos6.3_x86_64"
       machine.vm.box_url = "https://s3.amazonaws.com/itmat-public/centos-6.3-chef-10.14.2.box"

       machine.hostsupdater.aliases = options["hosts"]
       machine.vm.network :private_network, ip: options["ip"]

       machine.vm.synced_folder ".", "/vagrant", :nfs => true, :windows__nfs_options => ["-exec"]
       # machine.vm.synced_folder "./share/sphinx_indexes", "/indexes", :nfs => true, :windows__nfs_options => ["-exec"]

       machine.vm.provision :chef_solo do |chef|
         chef.cookbooks_path = ["cookbooks", "chef/applications", "chef/configurations", "chef/roles"]
         chef.data_bags_path = ["chef/data_bags"]
         options["configs"].each do |conf|
           chef.add_recipe conf
         end
       end

       machine.vm.provider :virtualbox do |vb|
         vb.customize ["modifyvm", :id, "--memory", "1024"]
         vb.customize ["modifyvm", :id, "--cpus", "1"]
       end
    end
   end
end
