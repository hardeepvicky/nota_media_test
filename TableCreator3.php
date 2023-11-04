<?php
require_once('Mysql.php');

class TableCreator3
{
    private Mysql $mysql;

    private $table = 'task3';

    private $field_result_enum_values = [
        'normal', 'illegal', 'failed', 'success'
    ];

    public function __construct(Mysql $mysql)
    {
        $this->mysql = $mysql;

        $this->create();

        $this->fill();
    }

    private function create()
    {
        $db = $this->mysql->getDatabase();

        if ( !$this->mysql->isTableExist($this->table) )
        {       
            /**
             * createing table for task3
             */

            $enum_str = "'" . implode("','", $this->field_result_enum_values) . "'";

            $q = "
                create table $this->table
                (
                    id INT NOT NULL AUTO_INCREMENT,
                    script_name VARCHAR(25) NOT NULL,
                    start_time DATETIME NULL,
                    end_time DATETIME NULL,
                    result enum($enum_str),
                
                    PRIMARY KEY ( id )
                );
            ";
    
            $this->mysql->query($q);
        }
    }

    private function fill()
    {
        $records = $this->getRandomData(100);

        foreach($records as $record)
        {
            $rows_insert_count = $this->mysql->insert($this->table, $record);

            if ($rows_insert_count == 0)
            {
                throw new RuntimeException("Fail To Insert Record in $this->table");
            }
        }
    }

    private function getRandomData($n)
    {
        $records = [];

        for($i = 1; $i <= $n; $i++)
        {
            $record = [];

            $record['script_name'] = get_random_string(25);
            $record['start_time'] = get_random_date_time();
            $record['end_time'] = get_random_date_time();
            $record['result'] = $this->field_result_enum_values[array_rand($this->field_result_enum_values)];


            $records[] = $record; 
        }

        return $records;
    }

    public function fetchData()
    {
        return $this->mysql->select("select * from $this->table");
    }
}