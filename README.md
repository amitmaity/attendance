# Web Attendance in Drupal 8

This project provides an interface to apply for attendance.

It is the perfect solution for distributed teams and or companies allowing remote work.

Employees can request attendance, Team Leads and Admin can approve them.

It sends a attendance approval link to your slack channel for easy approval.

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) 
for your setup.

After that you can clone the project:

```
git clone https://github.com/amitmaity/attendance.git
```

Install the dependencies with composer install

```
cd attendance
composer install
```

Then install the drupal site and revert the config