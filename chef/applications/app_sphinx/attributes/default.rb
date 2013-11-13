set[:sphinx][:use_stemmer] = true
set[:sphinx][:install_path] = '/usr'
set[:sphinx][:binary_path] = '/usr/bin'
set[:sphinx][:use_mysql] = true
set[:sphinx][:source][:retrieve_method]  = 'svn'
set[:sphinx][:source][:revision] = "4150";
set[:sphinx][:extra_configure_flags] = [
  '--host=x86_64-unknown-linux-gnu',
  '--build=x86_64-unknown-linux-gnu',
  '--program-prefix=',
  '--exec-prefix=/usr',
  '--sbindir=/usr/sbin',
  '--sysconfdir=/etc',
  '--datadir=/usr/share',
  '--includedir=/usr/include',
  '--libdir=/usr/lib64',
  '--libexecdir=/usr/libexec',
  '--localstatedir=/var',
  '--sharedstatedir=/var/lib',
  '--mandir=/usr/share/man',
  '--infodir=/usr/share/info',
  '--sysconfdir=/etc/sphinx',
  '--without-unixodbc',
  '--with-iconv',
  '--enable-id64',
  'build_alias=x86_64-unknown-linux-gnu',
  'host_alias=x86_64-unknown-linux-gnu',
  'CFLAGS=-O2',
  'CXXFLAGS=-O2']
set[:sphinx][:searchd][:listen] = ["9366", "9306:mysql41"]
default[:sphinx][:searchd][:max_packet_size] = "128M"
