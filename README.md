# StreamZone — PHP/MySQL Project

StreamZone is a movie streaming database project built with PHP, MySQL, HTML, CSS, and JavaScript. It uses PHP forms and small JavaScript enhancements instead of an API and includes six pages: Home, Movies, Plans, Watchlist, History, and Account.

## MAMP Setup

1. Place the `movie-streaming` folder in `/Applications/MAMP/htdocs/`.
2. Start Apache and MySQL in MAMP.
3. Open `http://localhost:8888/movie-streaming/index.php`.
4. Import `database/movie_streaming.sql` only if the database doesn't already exist. **Importing will erase existing data.**
5. Update `db.php` if your MySQL settings differ from the default MAMP settings.

## Features

Users can register, log in, browse movies, manage watchlists, track watch history, and select subscription plans. The application uses PHP sessions, password hashing, prepared statements, and SQL JOINs to manage data securely.

## Project Files

- `index.php` — Homepage and dashboard
- `movies.php` — Browse and search movies
- `plans.php` — Subscription plans
- `watchlist.php` — Saved movies
- `history.php` — Watch history
- `account.php` — Login and registration
- `db.php` — MySQL connection
- `includes/` — Shared page layout
- `style.css` — Website design
- `database/` — SQL database and JOIN queries


## JavaScript Features

`script.js` provides instant filtering of loaded movies, confirmation before deleting history or removing watchlist entries, and registration password length feedback. Forms still submit to PHP, and PHP performs all database changes.
