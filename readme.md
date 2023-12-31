## About The Project

This web application is a security guard scheduler for a 24/7 bank.

## Functionalities

- Roster a security guard to the schedule
- Remove a security guard roster from the schedule
- View all the security guards' schedule in a tabular form
- Add a security guard
- Delete a security guard
- Show individual guard schedules

## Specifications

- Guards cannot work longer than 12 hours in a day
- Guards cannot work less than 3.5 hours in a day
- Show the guard schedule for the next 3 days
- Show an indicator of the days which there is no guard rostered throughout the 24 hours of that day

## Software Limitations

- The user can only set a schedule in a 30-minute interval

## Unit Tests
- Uses HTTP tests for unit testing. The following are the pages with unit tests:
  - Welcome page
  - Guard Page
  - Scheduler Page
  - Individual Guard Schedule Page

## Technologies

- PHP
- Laravel
- Boostrap
- jQuery
- MySQL
