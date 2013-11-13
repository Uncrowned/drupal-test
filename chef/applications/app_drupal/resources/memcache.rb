actions :create, :delete
default_action :create

attribute :name, :kind_of => String, :name_attribute => true
attribute :directory, :kind_of => String, :required => true
attribute :prefix, :kind_of => String
attribute :handler, :kind_of => String
attribute :handler_path, :kind_of => String
attribute :servers, :kind_of => Array, :default => []
attribute :bins, :kind_of => Hash, :default => {}
