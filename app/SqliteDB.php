<?php
namespace App;

class SqliteDB
{
    public $path;
    public $dbh;

    function __construct($dbname='users.sqlite')
    {
        $this->path = realpath(__DIR__.'/../'.$dbname);
        $dsn = 'sqlite:' .  $this->path;
        $this->dbh = new \PDO($dsn);
        assert(!is_null($this->dbh));
    }

    public function authenticateUser($username='', $password='')
    {
      if (function_exists('sqlite_escape_string'))
      {
	   $username = sqlite_escape_string($username);
	   $password = sqlite_escape_string($password);
      }
      else if(class_exists('Sqlite3') && method_exists('Sqlite3','escapeString'))
      {
        $username  = \Sqlite3::escapeString($username);
        $password  = \Sqlite3::escapeString($password);
      }
        if (! $this->isNullOrEmpty($username) && ! $this->isNullOrEmpty($password) )
        {
	      $query = "SELECT * FROM users where username='" . $username . "' and password='" . $password . "'";
    	    $result = $this->dbh->query($query)->fetchColumn(); 
	       if( $result === '1' )
	        {
	          return true;
	        }
        }

        return false;
    }

    private function isNullOrEmpty($var)
    {
        return (is_null($var)) || (strlen($var) === 0);
    }
    
    function __destruct()
    {
        unset($this->pdo);
    }
}

//$sql = new SqliteDB();
//echo $sql->authenticateUser('admin','admin');