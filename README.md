# Project UTB / AP4TW

## Technology stack

### Backend

- Nette 3.1
- Doctrine 2

### Frontend

- Webpack 5
- Bootstrap 4
- Font awesome

### PaaS (_Platform as s services_)

- Heroku (App hosting)

## Start application

### Minimum requirements

- PHP 7.4
- MySQL 5.7
- NodeJS 15
- npm 7

### Install wizard

1. Configure Doctrine in `config/local.neon`
2. Install dependencies
    1. Backend: `composer install`
    2. Frontend: `npm install`
3. Run Webpack `npm scripts run`