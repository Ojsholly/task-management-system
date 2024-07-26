## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

The API documentation can be found at the following url: https://documenter.getpostman.com/view/22569385/2sA3kYjfa7

### Prerequisites
- [PHP](https://secure.php.net/downloads.php) >= 8.2
- [Composer](https://getcomposer.org/)

### Installation
1. Clone the repository
```
    git clone https://github.com/Ojsholly/task-management-system.git
```

2. Install dependencies
```
    composer install
```

3. Create the project environment file
```
   cp .env.example .env
```

4. Generate a new application key
```  
    php artisan key:generate
```
5. Add database credentials.
```  
    Add mailing and database credentials to the .env file.
```

6. Seed the Database
```  
    php artisan migrate:fresh --seed
```

### Deployment
The project can be hosted on any VPS via the following steps:
1. Install PHP and Composer on the VPS.
2. Clone the repository.
3. Install dependencies.
4. Create the project environment file.
5. Generate a new application key.
6. Add the database credentials.
7. Seed the Database.
8Configure the web server to serve the project.


### Testing
The project includes a PHPUnit test suite. To run the tests, execute the following command:
```
 php artisan test
```

