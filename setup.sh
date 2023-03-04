composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
./vendor/bin/sail artisan jwt:secret
./vendor/bin/sail artisan migrate --seed
