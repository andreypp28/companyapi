set :application, 'clean-api'
set :repo_url, 'git@github.com:WirelessAnalytics/clean-api.git'

set :rails_env, -> { fetch(:stage) }
set :log_level, :debug
set :linked_files, %w{.env}
set :linked_dirs, %w{storage/logs}
set :file_permissions_paths, ["storage/logs", "storage/framework"]
set :file_permissions_users, ["www-data","deploy"]


# Slack Notifications
set :slackistrano, {
    klass: Slackistrano::CustomMessaging,
    team: "wirelessanalytics",
   webhook: "https://hooks.slack.com/services/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",
   token: "xoxp-2153660998-7383443462-7449051621-4e69f4",
    channel:'#dev-systems'
}


# Devops commands
namespace :ops do

    desc 'reset PHP FPM'
    task :reset_app do
        on roles(:app), in: :sequence, wait: 1 do
            execute "sudo service php7-fpm restart"
            execute "sudo service nginx restart"
        end
    end

    desc 'Copy ENV specific files to servers.'
    task :put_env  do
        on roles(:app), in: :sequence, wait: 1 do
            upload! ".env.#{fetch :rails_env}", "#{deploy_to}/shared/.env"
        end
    end
end

# Composer
namespace :composer do

    desc "Running Composer self-update"
    task :update do
        on roles(:app), in: :sequence, wait: 5 do
            execute :composer, "self-update"
        end
    end

end

# Laravel
namespace :laravel do

    desc "Setup Laravel folder permissions"
    task :permissions do
        on roles(:app), in: :sequence, wait: 5 do
            execute :chmod, "u+x #{release_path}/artisan"
            execute :chmod, "-R 777 #{release_path}/storage"
        end
    end

    desc "Run Laravel Artisan migrate task."
    task :migrate do
        on roles(:app), in: :sequence, wait: 5 do
            execute "cd #{release_path} && php artisan migrate --force"
        end
    end

    desc "Run Laravel Artisan seed task."
    task :seed do
        on roles(:app), in: :sequence, wait: 1 do
            execute "cd #{release_path} && php artisan db:seed"
        end
    end

    desc "Run Laravel Artisan seed task."
    task :cleanup do
        on roles(:app), in: :sequence, wait: 1 do
            execute "rm #{release_path}/storage/framework/compiled.php"
        end
    end

    desc "Optimize Laravel Class Loader"
    task :optimize do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute "cd #{release_path} && php artisan cache:clear"
            end
        end
    end


    desc "Optimize Laravel Class Loader"
    task :restart_queue do
       on roles(:app), in: :sequence, wait: 1 do
           execute "sudo supervisorctl restart clean_queue:"
       end
    end


end
