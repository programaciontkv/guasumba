<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Export_excel {
	
	function to_excel($data,$file,$titulo,$fec1,$fec2){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='9'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='9' align='center'>". utf8_decode($titulo)."</th></tr>
                <tr>
                    <td colspan='9'>Desde: $fec1 Hasta: $fec2 </td>
                </tr>
            </thead>
		</table>";
		echo $data;
	}

	function to_excel2($data,$file,$titulo){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='6'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='6' align='center'>". utf8_decode($titulo)."</th></tr>
            </thead>
		</table>";
		echo $data;
	}

	function to_excel3($data,$file,$titulo,$fec1,$fec2){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='6'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='6' align='center'>". utf8_decode($titulo)."</th></tr>
                <tr>
                    <td colspan='9'>Al: $fec2 </td>
                </tr>
            </thead>
		</table>";
		echo $data;
	}

	
}
?>