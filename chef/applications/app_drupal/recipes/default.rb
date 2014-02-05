app_drupal_nginx 'sciencescape' do
  action :create
  host 'sciencescape'
  aliases node[:app_sciencescape][:host][:aliases]
  docroot node[:app_sciencescape][:host][:docroot]
  drupal_file_dir '/sites/default/files'
end
