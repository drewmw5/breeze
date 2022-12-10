# drewmw5/breeze

## Introduction

This package adds the option to install the default Laravel Breeze React scaffolding, but with all necessary types added so you can begin adding/changing the default scaffolding in Typescript!

## Installation

### 1. Navigate to your Laravel Project's directory and require the package via composer

```bash
composer require drewmw5/breeze
```

### 2. Execute the following command

```bash
php artisan breeze:install react-ts
```

### 3. Done!

(Note: Ensure that `npm i`, `npm run build`, and `php artisan route:cache` have run. I run this from a docker-compose file, so I need to manually execute them. Otherwise, the package should automatically handle these commands.)

## Notes

If you've found this repo helpful, consider leaving a star! If you'd like to submit a PR, please feel free to do so. Thank you!
