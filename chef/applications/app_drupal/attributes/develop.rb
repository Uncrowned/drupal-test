set["phing"]["shell_timeout"] = 9000
default[:app_drupal][:settings_path] = '/vagrant/share/drupal/settings'
set['drush']['install_method'] = 'git'
set['drush']['gitrep'] = "https://github.com/goruha/drush.git"
set['drush']['version'] = "8.x-6.x"
