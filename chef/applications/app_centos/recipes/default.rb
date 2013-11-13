if platform_family?('debian', 'rhel')
  include_recipe 'iptables'
  iptables_rule 'port_ssh' do
    enable true
  end
end
include_recipe "yum"
include_recipe "yumrepo"
include_recipe "build-essential"
include_recipe "cron"
include_recipe "zsh"
include_recipe "git"
include_recipe "subversion"
include_recipe "vim"
include_recipe "htop"
include_recipe "iotop"
include_recipe "nano"
include_recipe "zip"
package "mc"
include_recipe "git"
include_recipe "subversion"
