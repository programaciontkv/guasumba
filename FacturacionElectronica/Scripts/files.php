<?php
$output_dir = "/var/www/FacturacionElectronica/Scripts/archivos/";
if (isset($_FILES["archivo"])) {
    $ret = array();
    $error = $_FILES["archivo"]["error"];
    if (!is_array($_FILES["archivo"]["name"])) { //single file
        $fileName = $_FILES["archivo"]["name"];
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $output_dir . $fileName);
        $ret[] = $fileName;
    } else {  //Multiple files, file[]
        $fileCount = count($_FILES["archivo"]["name"]);
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES["archivo"]["name"][$i];
            move_uploaded_file($_FILES["archivo"]["tmp_name"][$i], $output_dir . $fileName);
            $ret[] = $fileName;
        }
    }
    $rt1 = str_replace('["', '', json_encode($ret));
    echo str_replace('"]', '', $rt1);
}
?>