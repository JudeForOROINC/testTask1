# testTask1
My first github repo for test task

How to start : instruction

1) set up ubuntu.
2) prepare enviroment to made sure that symfony works
3) create project folder like symfonytesktask
4) from console come to that folder and create a new symfony project
>symfony new TestTask 2.3
Important!!! name of folder must be TestTask.
5) set up database to made sure that you may see symfony hallo page
6) clone git storage:
  use gir command to clone like 
  >git clone url
  use "url" from this git page.
7) come in folder TestTask1 , that must apire, if (6) success, and copy folders app and src with file composer.json  
copy to folder TestTask
7a) go to into folder TestTask - its you project root directory. all other action from that folder.
8) run composer to update all needed.
>composer update
9) force your database (must be an mysql).
> php app/console doctrine:scheme:update --force
10) load fixtures with data:
> php app/console doctrine:fixtures:load --fixtures=./src/Magecore/Bundle/TestTaskBundle/DataFixtures/ORM/Test
for test data or 
> php app/console doctrine:fixtures:load --fixtures=./src/Magecore/Bundle/TestTaskBundle/DataFixtures/ORM/Dev
for dev data.


11) start server from that folder like 
> php -S localhost:8800
12) go to your browser to page http://localhost:8800/web and if you see hallo page - be happy.


How to run tests:
1) go to root fonder in console (see 7a).
2) run command 
>phpunit -c app --testsuite "Project Unit Test Suite" --coverage-html=tstu 
to run unit tests
3) run command
>phpunit -c app --testsuite "Project Test Suite" --coverage-html=tst 
to run functional tests

How to create new user:
1) go to root fonder in console (see 7a).
2) run command 
>php app/console my:user:create --help
to see help
2) run command 
>php app/console my:user:create name mail pass "Full Name of User" --super-admin
to create new super-user.
to create symply user do not use option --super-admin




 
