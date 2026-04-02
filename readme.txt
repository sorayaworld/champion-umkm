composer create-project laravel/laravel .
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
php artisan install:api

npm create vue@latest .
npm install
npm install axios sweetalert2
npm run dev