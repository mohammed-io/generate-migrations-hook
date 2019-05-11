# generate-migrations-hook

This is a simple hook for Laravel Voyager, its job to generate a migration after each database schema change.

I like using Laravel Voyager, it really handles all the headache of creating the CRUD UI of the application,
because I use Voyager for most of my small projects, there's one thing bothers me when it comes to using it, **database**!

Because it updates the database in place, so there's no migration created, this is an issue if you made change and want to deploy it to your remote host, that's why I tried to make this little hook to handle the automatic creation of migrations.

## Todo
[ ] Write tests

[ ] Minor refactoring and code clean up

## Limitations
- No `down()` migration, only `up()`
- The file and class names are not very detailed.
- It generates a migration even if no changed happened to the table.

## Contribution

Your PR all welcome :)