E-Library Management Platform - PHPUnit Test Suite
Project Description
This project is a simple e-library management platform developed in PHP. It provides functionalities such as:

Member Registration
Member Profile Management
Item (Book) Management (Add, Edit, View, and Delete)
Report Handling
This repository includes a PHPUnit test suite to ensure the reliability and correctness of the platform's core functionalities.

Prerequisites
Before running the tests, ensure you have the following installed on your machine:

PHP (version 7.4 or above)
Composer (Dependency Manager for PHP)
PHPUnit (Test framework for PHP)
Installation
Clone the repository:

bash
Copy code
git clone https://github.com/your-username/e-library-management.git
cd e-library-management
Install the project dependencies using Composer:

bash
Copy code
composer install
Set up your database. Use the db.sql file provided in the database/ folder to create the necessary tables.

Configure your database connection in the config.php file to match your environment.

Running the Tests
To run the PHPUnit tests, navigate to the root directory of your project and run the following command:

bash
Copy code
vendor/bin/phpunit
You should see the test output in your terminal, which will display the results of the tests (pass/fail status).

Specific Test Instructions
The PHPUnit test suite is structured to cover the following key features:

Member Management:

Tests for registering a new member
Tests for editing member profiles
Tests for retrieving member details
Book Management:

Tests for adding, editing, viewing, and deleting book details
Validation tests for invalid inputs
Report Handling:

Tests for generating reports on members and books
Example Commands:
Run all tests:

bash
Copy code
vendor/bin/phpunit
Run a specific test file:

bash
Copy code
vendor/bin/phpunit tests/BookManagementTest.php
Code Coverage Report
To generate a code coverage report, ensure Xdebug is installed on your system. Use the following command to generate a report:

bash
Copy code
vendor/bin/phpunit --coverage-html coverage/
After the tests run, you can view the coverage report by opening the coverage/index.html file in a web browser.
