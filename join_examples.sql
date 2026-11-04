USE movie_streaming;
-- JOIN 1: Show each user's watchlist with movie information.
SELECT CONCAT(u.first_name,' ',u.last_name) AS user_name, m.title, m.genre, m.rating
FROM watchlist w JOIN users u ON w.user_id=u.user_id JOIN movies m ON w.movie_id=m.movie_id;
-- JOIN 2: Show users' subscription plans.
SELECT CONCAT(u.first_name,' ',u.last_name) AS user_name, s.plan_name, s.price, us.start_date
FROM user_subscriptions us JOIN users u ON us.user_id=u.user_id
JOIN subscriptions s ON us.subscription_id=s.subscription_id;
-- JOIN 3: Show users' viewing history.
SELECT u.email, m.title, h.watched_at FROM watch_history h
JOIN users u ON u.user_id=h.user_id JOIN movies m ON m.movie_id=h.movie_id
ORDER BY h.watched_at DESC;
