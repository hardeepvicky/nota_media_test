<?php
require_once('config.php');
require_once('functions.php');
require_once('Mysql.php');
require_once('TableCreator2.php');

define('SHOW_HTML_TO_XML_CONVERSION_ERROR', FALSE);

$mysql = new Mysql(MysqlConfig::host, MysqlConfig::user, MysqlConfig::password, MysqlConfig::database, MysqlConfig::port);

$table_creater = new TableCreator2($mysql);
$table = $table_creater->getTable();

    /**
     * Dummy Statment check everything is working fine so add some random data and then delete them
     * comment out this following statement to check 
     */
    
    /**
    *
    $mysql->insert($table, $table_creater->getRandomRecord());

    $records = $table_creater->fetchData();

    // $mysql->delete($table, []);

    
    echo "Total Records : " . count($records);

    dump($records);

    exit;
    */

    /**
     * following code to check html to xml conversion
     */

     /**
      * 
    $html = <<< HEREDOC
        <!DOCTYPE html>
        <body>
            <div>
                <figure class="table ">
                    <figcaption>
                        <p class="table_number"></p>
                        <p class="table_title" epub:type="title"></p>
                    </figcaption>
                    <table class="code ">
                        <tr>
                            <td width="50">
                                <img alt="" height="239" src="http://example.com/image.png" width="272">
                            </td>
                        </tr>
                    </table>
                </figure>
            </div>
        </body>
        HEREDOC;

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $errors = libxml_get_errors();
        
        echo "Errors While Parsing HTML";
        dump($errors);

        dump($dom->saveXml($dom));

        exit;
    */

//$html = file_get_contents('https://www.wikipedia.org/');

$html = file_get_contents("wikipedia.html");

//dump($html);

$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($html);


if (SHOW_HTML_TO_XML_CONVERSION_ERROR)
{
    $errors = libxml_get_errors();
    if ( $errors )
    {
        echo "Errors While Parsing HTML";
        dump($errors); exit;
    }
}

$domxpath = new DOMXPath($dom);

$node_list = $dom->getElementsByTagName("title");

$page_title = $node_list[0]->nodeValue;

//dump($page_title);

$save_records = [];

$node_list = $dom->getElementsByTagName("a");
$href_list = [];
foreach($node_list as $node)
{
    $href = $node->getAttribute("href");

    if (strpos($href, "/") !== false)
    {
        $href_list[] = $href;
    }
}

$href_list = array_unique($href_list);
dump("Url found count : " . count($href_list));
//dump($href_list);


foreach($href_list as $href)
{
    $save_records[] = [
        'title' => $page_title,
        'url' => $href
    ];
}

$node_list = $dom->getElementsByTagName("img");
$img_src_list = [];
foreach($node_list as $node)
{
    $src = $node->getAttribute("src");
    if (strpos($src, "/") !== false)
    {
        $img_src_list[] = $src;
    }
}

$img_src_list = array_unique($img_src_list);

dump("Img found count : " . count($img_src_list));
//dump($img_src_list);


foreach($img_src_list as $img)
{
    $save_records[] = [
        'title' => $page_title,
        'picture' => $img
    ];
}

//delete all before insert
$mysql->delete($table, []);

//dump($save_records);

$insert_count = 0;
foreach($save_records as $record)
{
    $record['date_created'] = date('Y-m-d H:i:s');

    $rows_insert_count = $mysql->insert($table, $record);

    if ($rows_insert_count == 0)
    {
        throw new RuntimeException("Fail To Insert Record in $table");
    }

    $insert_count += $rows_insert_count;
}

echo "<br/>Save Record Count : " . $insert_count;

$records = $table_creater->fetchData();

echo "<br/>Fetch Record Count : " . count($records);

dump($records);