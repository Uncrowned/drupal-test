actions :create, :delete
default_action :create

attribute :name, :kind_of => String, :name_attribute => true
attribute :directory, :kind_of => String, :required => true
attribute :driver, :kind_of => String
attribute :user, :kind_of => String
attribute :password, :kind_of => String
attribute :host, :kind_of => String
attribute :port, :kind_of => [Integer, String]
attribute :db_name, :kind_of => String

