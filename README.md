## Flight Advisor API

1. [Start](#start)
2. [Usage](#usage)

## 1. Start

-Clone repository  
-Run `composer install`  
-Copy `.env.example` to `.env`  
-Run `php artisan key:generate`  
-Fill in MySQL, Redis and Neo4j database credentials  
-Run `php artisan migrate:fresh --seed` (This command will create dummy administrator account and cities)  
-Run `php artisan queue:work`  

## 2. Usage

Administrator account

```
Username: administrator
Password: password
```

Stateless HTTP authentication is used. Authorisation Basic header with base64 encoded `username:password` should be added to each request except for registration. Administrator example: `Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==`

Postman examples -> exported file `flight-advisor-api` or [JSON](https://www.postman.com/collections/64c0532bc11d7080aa4c).

Requests:

Register:

```
POST /api/register
Headers:
-Accept: application/json
Required parameters: first_name, last_name, username and password
```

Create city:

```
POST /api/cities
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Required parameters: name, country, description
```

Get / Search cities:

```
GET /api/cities
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Optional parameters: search, comments
```

Import airports:

```
POST /api/airports/import
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Required parameters: file
```

Import routes:

```
POST /api/routes/import
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Required parameters: file
```

Create comment:

```
POST /api/comments
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Requred parameters: city_id, description
```

Edit comment:

```
PUT /api/comments/{comment_id}
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Required parameters: description
```

Delete comment:

```
DELETE /api/comments/{comment_id}
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
```

Find cheapest flight:

```
POST /api/cheapest-flight
Headers:
-Accept: application/json
-Authorization: Basic YWRtaW5pc3RyYXRvcjpwYXNzd29yZA==
Required parameters: from_city_id, to_city_id
```