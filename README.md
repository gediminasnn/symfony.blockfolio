# Blockfolio - Cryptocurrency Asset Management Application

This Cryptocurrency Asset Management Application is built with Symfony. It allows users to manage their cryptocurrency assets through a RESTful API. Users can perform CRUD operations on their assets and calculate the total value in USD based on real-time exchange rates.

## Prerequisites

Before proceeding with the setup, ensure you have the following installed on your machine:

- **Docker**
- **Docker Compose**

## Setup

1. **Clone the Repository**
   
   First, clone the repository to your local machine. Open a terminal and run the following command:
   
   ```bash
   git clone git@github.com:gediminasnn/symfony.blockfolio.git
   ```

2. **Navigate to the Project Directory**
   
   Change directory to the application root:
   
   ```bash
   cd symfony.blockfolio
   ```

3. **Start the Docker Containers**
   
   Use Docker Compose to start the Docker containers. Run the following command in your terminal:
   
   ```bash
   docker-compose up -d
   ```
   This command builds and starts all containers needed for the application. The -d flag runs the containers in the background.

4. **Generate JWT Keys**
   
   The application uses JWT for authentication, so you need to generate the public and private keys:
   
   ```bash
   docker-compose exec php php bin/console lexik:jwt:generate-keypair
   ```

5. **Run Database Migrations**
   
   After the containers are up, run the database migrations to set up the schema:
   
   ```bash
   docker-compose exec php bin/console doctrine:migrations:migrate
   ```

6. **Run Database Fixtures**
   
   Populate the database with initial data by running the fixtures:
   
   ```bash
   docker-compose exec php bin/console doctrine:fixtures:load
   ```

7. **(Optional) Create Test Database**
   
   To set up the test database, execute the following commands:
   
   ```bash
   docker-compose exec php bin/console doctrine:database:create --env=test
   docker-compose exec php bin/console doctrine:migrations:migrate --env=test
   ```
   This command creates the necessary database for running tests.

8. **(Optional) Run Tests**
   
   Ensure that your Docker containers are still up and running. Open a new terminal window or tab and execute the following command:
   
   ```bash
   docker-compose exec php bin/phpunit
   ```
   This command will run all the tests located in the `tests` directory of your application.

By completing these steps, you will have fully set up your Asset Management Application on your local development environment, ensuring it is ready for further development, testing, or deployment.

## API Documentation

The Asset Management Application provides the following RESTful API endpoints:

### Authentication

#### Generate JWT Token

**Request:**
```http
POST /api/login_check
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
    "token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

Use this token in the `Authorization` header for subsequent requests:
```http
Authorization: Bearer <your_token>
```

### Assets

#### List assets

**Request:**
```http
GET /api/assets
Authorization: Bearer <your_token>
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

[
    {
        "id": 1,
        "label": "binance",
        "currency": "bitcoin",
        "value": 0.123
    },
    {
        "id": 2,
        "label": "usb stick",
        "currency": "ethereum",
        "value": 0.321
    }
]
```

#### Create Asset

**Request:**
```http
POST /api/assets
Content-Type: application/json
Authorization: Bearer <your_token>

{
    "label": "usb stick",
    "currency": "ethereum",
    "value": 0.321
}
```

**Response:**
```http
HTTP/1.1 201 Created
Content-Type: application/json

{
    "id": 2,
    "label": "usb stick",
    "currency": "ethereum",
    "value": 0.321
}

```

#### Show Asset

**Request:**
```http
GET /api/assets/{id}
Authorization: Bearer <your_token>
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
    "id": 1,
    "label": "binance",
    "currency": "bitcoin",
    "value": 0.123
}
```

#### Update Asset

**Request:**
```http
PUT /api/assets/{id}
Content-Type: application/json
Authorization: Bearer <your_token>

{
    "label": "coinbase",
    "value": 0.223
}
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
    "id": 1,
    "label": "coinbase",
    "currency": "bitcoin",
    "value": 0.223
}
```

#### Delete Asset

**Request:**
```http
DELETE /api/assets/{id}
Authorization: Bearer <your_token>
```

**Response:**
```http
HTTP/1.1 204 No Content
```

#### Calculate Total Asset Value in USD

**Request:**
```http
GET /api/assets/values
Authorization: Bearer <your_token>
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
    "assets": [
        {
            "id": 1,
            "label": "coinbase",
            "currency": "bitcoin",
            "value": 0.223,
            "value_in_usd": 20653.10
        },
        {
            "id": 2,
            "label": "usb stick",
            "currency": "ethereum",
            "value": 0.321,
            "value_in_usd": 1130.44
        }
    ],
    "total_value_usd": 21783.54
}
```
**Note:** The `value_in_usd` and `total_value_usd` are calculated based on real-time exchange rates fetched from the CoinGecko API.

## Environment Variables

The application requires some environment variables to be set.

-   `COINGECKO_API_TOKEN`: API token for accessing the CoinGecko API.

## License

This project is licensed under the MIT License.

----------

By following this guide, you should have the Asset Management Application up and running locally. If you encounter any issues, please check your Docker and Docker Compose installations and ensure all environment variables are correctly set.

Feel free to contribute to the project by submitting pull requests or opening issues.
