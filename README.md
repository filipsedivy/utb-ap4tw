# Project UTB / AP4TW

[<img src="https://img.shields.io/badge/utb--ap4tw.herokuapp.com-open%20application-brightgreen">](https://utb-ap4tw.herokuapp.com) [![Static Analysis](https://github.com/filipsedivy/utb-ap4tw/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/filipsedivy/utb-ap4tw/actions/workflows/static-analysis.yml) [![PHP_CodeSniffer](https://github.com/filipsedivy/utb-ap4tw/actions/workflows/code-style.yml/badge.svg)](https://github.com/filipsedivy/utb-ap4tw/actions/workflows/code-style.yml)

## Project description

CRM system for managing customers, employees, task, notes and files.

## Technology stack

### Backend

- Nette 3.1
- Doctrine 2

### Frontend

- Webpack 5
- Bootstrap 4
- Font awesome

### PaaS (_Platform as services_)

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