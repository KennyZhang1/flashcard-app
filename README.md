# QuizIt Flashcard Application Setup Guide
QuizIt is a flashcard application project developed by Kenny Zhang for the class CS 4750: Database Systems at the University of Virginia. For this project, the database is hosted locally in phpMyAdmin using XAMPP. Thus, the application is also hosted locally within the XAMPP PHP environment. All of the project files are to be placed within the XAMPP environment (XAMPP/htdocs). To set up, deploy, and run the project:
1.	Download and set up the XAMPP environment (https://www.apachefriends.org/docs/)
2.	Clone the git repo into XAMPP/htdocs
3.	Go to the local phpMyAdmin dashboard (http://localhost)
4.	Set up the database by running the SQL code contained within the backend/flashcard_app.sql file
5.	In phpMyAdmin, create a user account called “app” with a “localhost” host name and take note of the password. Do not give it global privileges.
6.	Copy the password into the backend/connect-db.php file
7.	Set up the proper application permissions to the database by running the SQL code contained within backend/access-control.sql file
8.	Start the app by navigating to (http://localhost/flashcard-app/index.html)
