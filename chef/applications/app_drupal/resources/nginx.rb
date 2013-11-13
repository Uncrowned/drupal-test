actions :create, :delete

attribute :host
attribute :aliases
attribute :docroot
attribute :drupal_file_dir

def initialize(*args)
  super
  @action = :create
end
