## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Required

- Composer
- PHP ^7.3
- MySql

## Setup Project

- Clone GitHub repo for this project
- cd into your project
- Install Composer Dependencies { composer install }
- Create a copy of .env.example { cp .env.example .env }
- Generate an app encryption key { php artisan key:generate }
- In the .env file, add database information
- Migrate the database { php artisan migrate }
- JWT authentication implemented you need to generate JWT KEY { php artisan jwt:secret }

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
