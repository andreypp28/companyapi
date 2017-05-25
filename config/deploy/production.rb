role :app, %w{10.1.20.24}

set :stage, :production
set :deploy_to, '/home/deploy/webapps/clean/api'
set :branch, "env/prod"
set :keep_releases, 4
set :log_level, :debug

#require custom config
# require './config/myconfig.rb'

set :ssh_options, {
  user: 'deploy'
}

set :tmp_dir, '/home/deploy/tmp'

namespace :composer do

    desc "Running Composer Install"
    task :install do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute "cd #{release_path} && composer install --no-dev --no-scripts --prefer-source --optimize-autoloader"
            end
        end
    end

end

namespace :deploy do

  before "deploy:updated", "deploy:set_permissions:acl"
  after :published, "ops:put_env"

  after :published, "composer:install"
  after :published, "laravel:permissions"
  # after :published, "laravel:migrate"
  
  after :published, "ops:reset_app"
end
