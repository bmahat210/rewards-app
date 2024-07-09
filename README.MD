# Symfony Rewards Application

This is a Symfony Rewards application that allows users to register, login, and manage rewards. It includes CRUD operations for rewards, a RESTful API, and integration with a third-party API to fetch stock data.

## Features

- User Authentication and Authorization
  - Register and login functionality
  - Secure password handling
- CRUD Operations for Rewards
  - Create, Read, Update, and Delete rewards
  - Form handling with validation
- RESTful API
  - Endpoints for listing, creating, updating, and deleting rewards
- Third-Party API Integration
  - Fetch stock data using Alpha Vantage API

## Requirements

- PHP
- Composer
- Symfony CLI
- Doctrine ORM

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/rewards-app.git
   cd rewards-app

2. **Install dependencies
    ```bash
    composer install

3. **Set up the database
    Update your .env file with your database credentials, then run:
    ```bash
    php bin/console doctrine:migrations:migrate

## Running the application

1. Run the Symfony local server
    ```bash
    symfony server:start

2. Access the application
Open your browser and go to http://127.0.0.1:8000.

## Usage
1. User Authentication
    - Register a new user by navigating to /register.
    - Login at /login.
    - Logout by clicking the logout button when logged in.

2. Rewards CRUD
    - List all rewards: /rewards
    - Create a new reward: /rewards/new
    - Edit a reward: /rewards/{id}/edit
    - Delete a reward by clicking the delete button in the list view.

## Running Tests
1. Create a db with _test suffix
2. Set the db url without the suffix to the phpunit.xml file in the root folder.
3. Run all tests
    ```bash
    php bin/phpunit

## REST API Endpoints

1. List Rewards:
  ```bash
  GET /api/rewards
  ```

2. Create Reward:
  ```bash
  POST /api/rewards
  ```

- Body (JSON):
  ```json
  {
    "name": "Reward Name",
    "description": "Reward Description"
  }
  ```

3. Show Reward:
  ```bash
  GET /api/rewards/{id}
  ```

4. Update Reward:
  ```bash
  PUT /api/rewards/{id}
  ```

  - Body (JSON):
  ```json
  {
    "name": "Updated Reward Name",
    "description": "Updated Reward Description"
  }
  ```

5. Delete Reward:
  ```bash
  DELETE /api/rewards/{id}
  ```

## Preview
- Login Screen
![Alt text](/screenshots/login.png?raw=true "Login Screen")
- Create Rewards Screen
![Alt text](/screenshots/create.png?raw=true "Create Rewards Screen")
- List Rewards Screen
![Alt text](/screenshots/list.png?raw=true "List Rewards Screen")
