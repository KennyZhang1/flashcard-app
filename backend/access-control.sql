-- database level security for flashcard app database

-- app should not be able to delete users
GRANT SELECT, INSERT, UPDATE, REFERENCES ON flashcard_app.user_main TO 'app'@'localhost';

-- app should not be able to directly change the nicknames, friends, or mastery table or requests after they are sent
GRANT SELECT, INSERT, DELETE, REFERENCES ON flashcard_app.user_friend_names TO 'app'@'localhost';
GRANT SELECT, INSERT, DELETE, REFERENCES ON flashcard_app.user_nicknames TO 'app'@'localhost';
GRANT SELECT, INSERT, DELETE, REFERENCES ON flashcard_app.user_masters TO 'app'@'localhost';
GRANT SELECT, INSERT, DELETE, REFERENCES ON flashcard_app.request TO 'app'@'localhost';

-- app should be able to perform all operations on cards and decks
GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES ON flashcard_app.card TO 'app'@'localhost'; 
GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES ON flashcard_app.deck TO 'app'@'localhost';

-- app should be able to perform all operations on quizzes and questions
-- app should not be able to change tests table directly
GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES ON flashcard_app.question TO 'app'@'localhost'
GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES ON flashcard_app.quiz TO 'app'@'localhost';
GRANT SELECT, INSERT, DELETE, REFERENCES ON flashcard_app.tests TO 'app'@'localhost';