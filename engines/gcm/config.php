<?php
/**
 * Database config variables
 */

if($_SERVER['SERVER_NAME'] == 'localhost')
{
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "arifchasan");
	define("DB_DATABASE", "sinergi46");
}
else
{
	define("DB_HOST", "localhost");
	define("DB_USER", "sinergi46");
	define("DB_PASSWORD", "Synergy46@2018!");
	define("DB_DATABASE", "sinergi46-dev");
}


/*
 * Google API Key
 */
   define("GOOGLE_API_KEY", "AAAAZtVkdWE:APA91bGMq_3NFALP9B5SoPpvMejDzZTJLh6VuET9_HX16V5jy596ddgIj-rodm3cEj_1D8KCrsb3MX78-L9ZuSWJuXZpbVERb7nK2u2xV0Q6KAkvlZgimE2FFFH37zMUlYujlVO-39CkW7GRoLGsZkXNY2cZm3UHow"); 
?>
