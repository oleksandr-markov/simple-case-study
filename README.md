# Simple Laravel API with Job Queue, Database, and Event Handling

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/10.x)

Clone the repository

    git clone git@github.com:oleksandr-markov/casestudy.git

Switch to the repo folder

    cd casestudy

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:oleksandr-markov/casestudy.git
    cd casestudy
    composer install
    cp .env.example .env
    php artisan key:generate

**Make sure you set the correct database connection information before running the migrations**

    php artisan migrate
    php artisan serve

---

## API Endpoint Docs
### URL
`POST /api/submit`

### Headers:
```text
Content-Type: application/json
Accept: application/json
```


### Request Body
```json
{ 
    "name": "string|required|max:255", 
    "email": "string|required|email", 
    "message": "string|required" 
}
```

#### Sample Request
```json
{ 
    "name": "John Doe", 
    "email": "john.doe@example.com", 
    "message": "This is a test message."
}
```

### Responses
**Successful response (202 Accepted)**

### Errors
1. **Validation error (422 Unprocessable Entity)**
```json
{ 
    "message": "The given data was invalid.", 
    "errors": {
        "field_name": [ "Error message" ]
    } 
}
```

2. **Job execution error (500 Internal Server Error)**
```json
{
    "message": "Failed to process submission.", 
    "error": "Detailed error message", 
    "data": {
        "name": "John Doe", 
        "email": "john.doe@example.com", 
        "message": "This is a test message."
    }
}
``` 
