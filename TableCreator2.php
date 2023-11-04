<?php
require_once('Mysql.php');

class TableCreator2
{
    private Mysql $mysql;

    private $table = 'wiki_sections';

    public function __construct(Mysql $mysql)
    {
        $this->mysql = $mysql;

        $this->create();
    }

    public function getTable()
    {
        return $this->table;
    }

    private function create()
    {
        $db = $this->mysql->getDatabase();

        if ( !$this->mysql->isTableExist($this->table) )
        {       
            /**
             * createing table for task2
             */

            $q = "
                create table $this->table
                (
                    id INT NOT NULL AUTO_INCREMENT,
                    title VARCHAR(230) NOT NULL,
                    date_created DATETIME NULL,                    
                    url VARCHAR(240) NULL UNIQUE,
                    picture VARCHAR(256) NULL UNIQUE,
                    
                    PRIMARY KEY ( id )
                );
            ";
    
            $this->mysql->query($q);
        }
    }

    public function getRandomRecord()
    {
        $record = [];

        $record['title'] = get_random_string(25);        
        $record['date_created'] = get_random_date_time();
        $record['url'] = get_random_string(25);
        $record['picture'] = get_random_string(25);

        return $record;
    }

    public function fetchData()
    {
        return $this->mysql->select("select * from $this->table");
    }
}