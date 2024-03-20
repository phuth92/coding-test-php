## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- Docker
- Docker Compose

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication

TODO: pls summarize how to check "Authentication" bahavior
#### Dummy Data
```
bin/cake bake seed Users
```
#### User Postman to test
##### For login
```
URL API: http://localhost:34251/users/login
Method: Post
```
```
Body: 
{
    "email": "ivc.phuth@gmail.com",
    "password": "password"
}
```
##### For logout
```
URL API: http://localhost:34251/users/login.json
Method: Post
```

### Token
```
Add token to Authorization in Postman.
Choose Bearer Token: Bearer {token}
```

### Article Management

TODO: pls summarize how to check "Article Management" bahavior
#### Retrieve All Articles 
```
URL API: http://localhost:34251/articles.json
Method: GET
```

#### Retrieve a Single Article 
```
URL API: http://localhost:34251/articles/{id}.json
Method: GET
```

#### Retrieve a Single Article 
```
URL API: http://localhost:34251/articles/{id}.json
Method: GET
```

#### Create an Article 
```
URL API: http://localhost:34251/articles
Method: POST
```
```
Body: 
{
    "title": "this is title 222",
    "body": "today is body"
}
```


#### Update an Article 
```
URL API: http://localhost:34251/articles/{id}.json
Method: PUT
```
```
Body: 
{
    "title": "this is title 222",
    "body": "today is body"
}
```

#### Delete an Article 
```
URL API: http://localhost:34251/articles/{id}.json
Method: DELETE
```

### Like Feature

TODO: pls summarize how to check "Like Feature" bahavior

#### Like an Articles
```
URL API: http://localhost:34251/likes/{articleId}.json
Method: POST
```

#### Count likes an Articles
```
URL API: http://localhost:34251/articles/{articleId}/like_count.json
Method: GET
```