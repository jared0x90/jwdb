<?

/******************************************************************************

Jared De Blander <jared@deblander.org>

Overview
=======================================
A helper class for using MySQL/MariaDB prepared statements.


Setup
=======================================
1. Create envoirnment variables for connection or optionally specify them at
   object creation. The enviornemnt variables are as follows:

    JWDB_HOST
    JWDB_DB
    JWDB_USER
    JWDB_PASS

Use
=======================================


Code formatting
=======================================
4 Spaces
Open curly braces on same line as their use begins
Close curly braces on a line by themself.

******************************************************************************/

class jwdb(){

    private $db;
    private $statements = array();

    public function __construct($host = Null, $dbname = Null, $user = Null, $pass = Null){

        if($host != Null) $host = $_ENV['JWDB_HOST'];
        if($dbname != Null) $host = $_ENV['JWDB_DB'];
        if($user != Null) $host = $_ENV['JWDB_USER'];
        if($pass != Null) $host = $_ENV['JWDB_PASS'];

        $this->db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }

    public function bind_named_array_values($statement_name_or_id, &$array_of_values){
        foreach($array_of_values as $key=>$value){
            $this->statements[$statement_name_or_id]->bindValue(':'.$key, $value);
        }
    }

    # Create a named entry in our statements array
    public function create_update_statement($statement_name, $sql){

        $this->statements[$statement_name] = $this->db->prepare($sql);
    }

    # Create a statement with a uniqid and return the uniqid reference
    public function create_unique_statement($sql){
        $token = uniqid();
        $this->statements[$token] = $this->db->prepare($sql);
        return $token;
    }

    public function execute_statement($statement_name_or_id){
        return $this->statements[$statement_name_or_id]->execute();
    }
}
