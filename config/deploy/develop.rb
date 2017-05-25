role :app, %w{54.87.193.65}

set :stage, :develop
set :deploy_to, '/home/forge/staging.api.wirelessanalytics.com/'
set :branch, 'master'
set :keep_releases, 3
set :log_level, :debug

set :ssh_options, {
  user: 'forge'
}

set :tmp_dir, '/home/forge/staging.api.wirelessanalytics.com/tmp'

namespace :composer do

    desc "Running Composer Install"
    task :install do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute "cd #{release_path} && composer install --no-scripts --prefer-source --optimize-autoloader"
            end
        end
    end

end

namespace :deploy do
  after :published, "ops:put_env"
  after :published, "composer:install"
  after :published, "laravel:permissions"
  after :published, "laravel:migrate"
  after :published, "laravel:seed"
  after :published, "laravel:optimize"
  # after :published, "ops:reset_app"
end
