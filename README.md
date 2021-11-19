# login-test

Test database created and administered with xampp and PhpMyAdmin respectively, and is included in "database/km_site.sql"

To login as administrator (no functional difference from regular users, apart from a shorter password) use username "admin", password "admin"

List of users:

    Anders
    andeby10

	Højben
	højben20
	
	Andersine
	andershøjben
	
	Gearløs
	gearløs10
	
	splorp
	splorp10

Suggestions for improvement:
- Add a way for admins to designate other users as admins. Add a moderator group?
- Add "last_login" column to DB to be updated upon login.
  - This will, for instance, allow for a function to see currently active users (check who has logged in in the past 5 minutes).
- More interactive frontend. Instead of using redirects for creating a user and logging in, one might open a bootstrap modal.
- Proper MVC structure.
- Improve session handling to avoid identical session cookies being added to response header.
