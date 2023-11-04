<?php
/**
 * Class use for basic database operations
 * 
 * @author : Hardeep Singh
 */

class Mysql
{
    public static $queryLog = true;
    public static $conn, $logs;

    private String $host, $user, $database;
    private $port;
    
    public function __construct(String $host, String $user, String $password, String $database, int $port = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->database = $database;
        $this->port = $port;

        self::$conn = mysqli_connect($host, $user, $password, $database, $port);

        if (!self::$conn)
        {
            throw new RuntimeException("Mysql Error : " . mysqli_connect_error());
        }
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getPort()
    {
        return $this->port;
    }
    
    public function query(String $q)
    {
        self::$logs[] = $q;
        
        return mysqli_query(self::$conn, $q);
    }

    public function isTableExist(String $table)
    {
        $q = "SELECT 
                count(1) as c
            FROM 
                information_schema.tables
            WHERE 
                table_schema = '$this->database' AND table_name = '$table'
            LIMIT 1";

        $records = $this->select($q);

        if ($records && $records[0]['c'] > 0)
        {
            return true;
        }

        return false;
    }
    
    public function select(String $q)
    {
        $result = $this->query($q);
        
        $records = array();
        
        while($row = mysqli_fetch_assoc($result))
        {
            $records[] = $row;
        }
        
        return $records;
    }

    public function insert(String $table, Array $record) : int
    {
        $field_list = [];
        $value_list = [];

        foreach($record as $f => $v)
        {
            $field_list[] = "`" . $f . "`";

            if (is_string($v))
            {
                $value_list[] = "'" . $v . "'";
            }
            else
            {
                $value_list[] = $v;
            }
        }

        $field_str = implode(",", $field_list);
        $value_str = implode(",", $value_list);

        $q = "INSERT INTO `$this->database`.`$table`($field_str) VALUES ($value_str);";

        return $this->query($q);
    }

    public function update(String $table, Array $record, Array $where_record) : int
    {
        $set_list = [];
        
        foreach($record as $f => $v)
        {
            $f = "`" . $f . "`";

            if (is_string($v))
            {
                $v = "'" . $v . "'";
            }

            $set_list[] = $f . "=" . $v;
        }

        $where_list = [];
        
        foreach($where_record as $f => $v)
        {
            $f = "`" . $f . "`";

            if (is_string($v))
            {
                $v = "'" . $v . "'";
            }

            $where_list[] = $f . "=" . $v;
        }

        $set_str = implode(",", $set_list);

        $where = "";
        if ($where_list)
        {
            $where = "WHERE " . implode(" AND ", $where_list);
        }
        
        $q = "UPDATE `$this->database`.`$table` SET $set_str $where;";

        return $this->query($q);
    }

    public function delete(String $table, Array $where_record) : int
    {
        $where_list = [];
        
        foreach($where_record as $f => $v)
        {
            $f = "`" . $f . "`";

            if (is_string($v))
            {
                $v = "'" . $v . "'";
            }

            $where_list[] = $f . "=" . $v;
        }

        $where = "";
        if ($where_list)
        {
            $where = "WHERE " . implode(" AND ", $where_list);
        }
        
        $q = "DELETE FROM `$this->database`.`$table` $where;";

        return $this->query($q);
    }
    
    public function transactionBegin()
    {
        $this->query("SET AUTOCOMMIT=0");
        $this->query("START TRANSACTION");
    }
    
    public function transactionCommit()
    {
        $this->query("COMMIT");
    }
    
    public function transactionRollback()
    {
        $this->query("ROLLBACK");
    }
}
