<?php 
// For getting output with sleep
header( 'Content-type: text/html; charset=utf-8' );
header("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
set_time_limit(0);
ob_implicit_flush(1);

if (isset($_GET['gene'])) {
    //row1
    $gene = $_GET['gene'];
    $cluster = $_GET['cluster'];
    $color = $_GET['color'];
    $int_type = $_GET['int_type'];
    //row2
    $experiments = $_GET['experiments'];
    $publications = $_GET['publications'];
    $methods = $_GET['methods'];
    $method_types = $_GET['method_types'];
    //row3
    $process = $_GET['process'];
    $compartment = $_GET['compartment'];
    $expression = $_GET['expression'];
    //row4
    $max_nodes = $_GET['max_nodes'];
    $filter_condition = $_GET['filter_condition'];
    //other
    $unique_str = $_GET['unique_str'];

    if (isset($_GET['excel_flag'])) {
        $excel_flag = $_GET['excel_flag'];
    }
    else {
        $excel_flag = 0;
    }
    if (isset($_GET['filter_flag'])) {
        $filter_flag = $_GET['filter_flag'];
    }
    else {
        $filter_flag = 1; // default to filtering
    }
}

$excel_link = $_GET['excel_link'];

$array_of_vars = [$gene,$cluster,$color,$int_type,
$experiments,$publications,$methods,$method_types,
$process,$compartment,str_replace(array("(",")"),"",$expression), // Note we remove brackets here due to errors
$max_nodes,str_replace(" ","_",$filter_condition),
$excel_flag,$filter_flag,$unique_str];

$command = 'python ' . $_SERVER["DOCUMENT_ROOT"] . '/cgi-bin/gen_visualization.py ';
foreach ($array_of_vars as $var) {
    $command = $command . $var . " ";
}
$command = $command . " 2>&1";
echo "<br/><br/>We will execute this command:<br/>" . $command;


exec($command, $out, $status);

echo "<br/><br/>Done executing command.<br/><br/>";

// print output
echo "Python returned the following output.<br/>";
foreach($out as $result) {
    if (strlen($result) > 1) {
        // if traceback in $result add bootstrap alert around the coming text until end of loop
        echo $result . "<br/>";
    }
}

if($status == 0) {
    echo "<br/><br/>Opening Excel file now.<br/>";
    sleep(2);
    $javascript = <<<EOT
<script>
    window.location.href = "{$excel_link}";
</script>
EOT;
}
else {
    echo '<p>Something went wrong in the Excel file generation!</p>';
}

echo $javascript;
?>