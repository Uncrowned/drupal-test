actions :create, :delete, :reindex
default_action :reindex

attribute :name, :kind_of => String, :name_attribute => true, :required => true

attribute :service_name, :kind_of => String, :default => ''
attribute :source, :kind_of => String
attribute :params, :kind_of => Hash, :required => true, :default => {}

attribute :rt_field, :kind_of => Array, :default => {}
attribute :rt_attr_uint, :kind_of => Array, :default => {}
attribute :rt_attr_bool, :kind_of => Array, :default => {}
attribute :rt_attr_bigint, :kind_of => Array, :default => {}
attribute :rt_attr_float, :kind_of => Array, :default => {}
attribute :rt_attr_multi, :kind_of => Array, :default => {}
attribute :rt_attr_multi_64, :kind_of => Array, :default => {}
attribute :rt_attr_timestamp, :kind_of => Array, :default => {}
attribute :rt_attr_string, :kind_of => Array, :default => {}
attribute :rt_attr_json, :kind_of => Array, :default => {}

attribute :rotate, :kind_of => [TrueClass, FalseClass], :default => false
