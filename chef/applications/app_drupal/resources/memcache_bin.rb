actions :create, :delete
default_action :create

attribute :name, :kind_of => String, :name_attribute => true
attribute :directory, :kind_of => String, :required => true
attribute :table, :kind_of => String
attribute :cluster, :kind_of => String
