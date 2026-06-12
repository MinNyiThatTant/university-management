# create laravel 
```bush
composer create-project laravel/laravel university-management
```

# create migration files
```bush
php artisan make:migration create_students_table
php artisan make:migration create_courses_table
php artisan make:migration create_enrollments_table
```
# create Models
```bash
php artisan make:model Student
php artisan make:model Course
php artisan make:model Enrollment
```

# create controllers
```bash
php artisan make:controller StudentController --resource
php artisan make:controller CourseController --resource
php artisan make:controller DashboardController
```