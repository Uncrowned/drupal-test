api = 2
core = 6.x

;--------------------
; Build Core
;--------------------

projects[pressflow][type] = "core"
projects[pressflow][download][type] = "get"
projects[pressflow][download][url] = "http://files.pressflow.org/pressflow-6-current.tar.gz"
; https://github.com/pressflow/6/pull/50
projects[pressflow][patch][] = "http://github.com/damienmckenna/6/commit/2589f15727109892466d52b3c0acdb462e2d426b.patch"


projects[solution][type] = "module"
projects[solution][subdir] = "contrib"
projects[solution][download][type] = "git"
projects[solution][download][url] = "https://github.com/goruha/DrupalSolution6.git"
projects[solution][download][branch] = master

projects[drush_migrate][subdir] = "contrib"
projects[drush_migrate][type] = "module"
projects[drush_migrate][download][type] = "git"
projects[drush_migrate][download][url] = "git://git.drupal.org/project/drush_migrate.git"
projects[drush_migrate][download][revision] = "15599fe0dcb311043bbeef2746504517f17d453f"
;https://drupal.org/node/1418612
projects[drush_migrate][patch][] = "http://drupal.org/files/1418612-drush_migrate_install-1.patch"
;https://drupal.org/node/1389732
projects[drush_migrate][patch][] = "http://drupal.org/files/1389732-drush_migrate_s_error-1.patch"
