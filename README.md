Projet 6 OpenClassrooms - 

Installation

Step 1 : Clone the project.

Step 2 : Run in your console "composer install".

Step 3 : Open the .env file go to the line 32 and change the database connection information. 

Step 4 : Write in your console 
         - "php bin/console doctrine:database:create"
         - "php bin/console doctrine:migrations:migrate"
         
Step 5 : For random initial dataset run in your console :
         - "php bin/console doctrine:fixtures:load"
         
Step 6 : Write your gmail information in the file env. at the line 23
Exemple : MAILER_DSN=gmail+smtp://YourEmail:thePasswordOfTheEmail@default   

Step 7 : Enter your email in the file src/Service/Mail/mailService.php in line 40.
Exemple : ->from('Your Email')
          
Step 8 : Create a user acount using the inscription form the is link in the navbar.   
              
Step 9 : Have fun.

