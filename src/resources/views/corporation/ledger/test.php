<?PHP

$st = mktime(0, 0, 0, 4, 1, 2016);
$et = mktime(0, 0, 0, 5, 0, 2016);

echo date("h:i:sa m-d-Y", $st);
echo "\n";
echo date("h:i:sa m-d-Y", $et);
echo "\n";

?>
