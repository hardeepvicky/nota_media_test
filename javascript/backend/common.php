<?php
$json_file = "data.json";

function dump($arg)
{
    $callBy = debug_backtrace()[0];
    echo "<pre>";
    echo "<b>" . $callBy['file'] . "</b> At Line : " . $callBy['line'];
    echo "<br/>";
    
    if (is_string($arg))
    {
        echo htmlspecialchars($arg);
    }
    else
    {
        print_r($arg);
    }
    
    echo "</pre>";
}


function get_random_name()
{
    $firstname = array(
        'Johnathon',
        'Anthony',
        'Erasmo',
        'Raleigh',
        'Nancie',
        'Tama',
        'Camellia',
        'Augustine',
        'Christeen',
        'Luz',
        'Diego',
        'Lyndia',
        'Thomas',
        'Georgianna',
        'Leigha',
        'Alejandro',
        'Marquis',
        'Joan',
        'Stephania',
        'Elroy',
        'Zonia',
        'Buffy',
        'Sharie',
        'Blythe',
        'Gaylene',
        'Elida',
        'Randy',
        'Margarete',
        'Margarett',
        'Dion',
        'Tomi',
        'Arden',
        'Clora',
        'Laine',
        'Becki',
        'Margherita',
        'Bong',
        'Jeanice',
        'Qiana',
        'Lawanda',
        'Rebecka',
        'Maribel',
        'Tami',
        'Yuri',
        'Michele',
        'Rubi',
        'Larisa',
        'Lloyd',
        'Tyisha',
        'Samatha',
    );

    $lastname = array(
        'Mischke',
        'Serna',
        'Pingree',
        'Mcnaught',
        'Pepper',
        'Schildgen',
        'Mongold',
        'Wrona',
        'Geddes',
        'Lanz',
        'Fetzer',
        'Schroeder',
        'Block',
        'Mayoral',
        'Fleishman',
        'Roberie',
        'Latson',
        'Lupo',
        'Motsinger',
        'Drews',
        'Coby',
        'Redner',
        'Culton',
        'Howe',
        'Stoval',
        'Michaud',
        'Mote',
        'Menjivar',
        'Wiers',
        'Paris',
        'Grisby',
        'Noren',
        'Damron',
        'Kazmierczak',
        'Haslett',
        'Guillemette',
        'Buresh',
        'Center',
        'Kucera',
        'Catt',
        'Badon',
        'Grumbles',
        'Antes',
        'Byron',
        'Volkman',
        'Klemp',
        'Pekar',
        'Pecora',
        'Schewe',
        'Ramage',
    );

    $name = $firstname[rand(0, count($firstname) - 1)];
    $name .= ' ';
    $name .= $lastname[rand(0, count($lastname) - 1)];

    return $name;
}

function get_random_date_time()
{
    $int= mt_rand(1262055681,1262055681);

    $string = date("Y-m-d H:i:s",$int);

    return $string;
}

function objToArray($obj)
{
    $arr = array();
    if (gettype($obj) == "object")
    {
        $arr = objToArray(get_object_vars($obj));
    } 
    else if (gettype($obj) == "array")
    {
        foreach ($obj as $k => $v)
        {
            $arr[$k] = objToArray($v);
        }
    } 
    else
    {
        $arr = $obj;
    }

    return $arr;
}

function json_get_records()
{
    if (file_exists($GLOBALS['json_file']))
    {
        return objToArray(json_decode(file_get_contents($GLOBALS['json_file'])));
    }
    else
    {
        json_put_records([]);
    }

    return [];
}

function json_put_records(Array $records)
{
    $result = file_put_contents($GLOBALS['json_file'], json_encode($records));

    if ($result === false)
    {
        throw new Exception("Fail To Write Json");
    }
}

function json_insert(Array $save_record)
{
    if (!isset($save_record['name']))
    {
        die("name is not found in save_record");
    }

    $records = json_get_records();

    $save_record['id'] = count($records) + 1;

    $save_record['created'] = $save_record['updated'] = date('d-M-Y H:i:s');

    $records[] = $save_record;

    json_put_records($records);

    return true;
}

function json_update(Array $save_record)
{
    $records = json_get_records();

    $is_updated = false;
    foreach($records as $k => $record)
    {
        if ($record['id'] == $save_record['id'])
        {
            $is_updated = true;
            $save_record['updated'] = date('d-M-Y H:i:s');
            $records[$k] = array_merge($records[$k], $save_record);
        }
    }

    if ($is_updated)
    {
        json_put_records($records);
    }

    return $is_updated;
}

function json_delete(int $id)
{
    $records = json_get_records();

    $is_deleted = false;
    foreach($records as $k => $record)
    {
        if ($record["id"] == $id)
        {
            $is_deleted = true;
            unset($records[$k]);
        }
    }

    if ($is_deleted)
    {
        $records = array_values($records);
        json_put_records($records);
    }

    return $is_deleted;
}