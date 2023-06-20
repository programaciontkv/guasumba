<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdf_libro_mayor extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('configuracion_model');
		$this->load->model('rep_contables_model');
		$this->load->model('pdf_libro_mayor_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('cliente_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}



	public function index($desde, $hasta, $opc_id, $cta = '')
	{
		$rst_opc = $this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja = $this->caja_model->lista_una_caja($rst_opc->opc_caja);
		require_once APPPATH . 'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
		$pdf->AddPage('P', 'A4', 0);
		$pdf->AliasNbPages();
		$pdf->AddFont('Calibri-light', ''); //$pdf->SetFont('Calibri-Light', '', 9);
		$pdf->AddFont('Calibri-bold', ''); //$pdf->SetFont('Calibri-bold', '', 9);

		$dc = $this->configuracion_model->lista_una_configuracion('2');
		$dec = $dc->con_valor;
		$emisor = $this->empresa_model->lista_una_empresa($rst_cja->emp_id);
		if (!empty($cta)) {
			//// cambia buscados para encontrar coincidencias
			$txt1 = " and con_concepto_debe like'$cta%'";
			$txt2 = " and con_concepto_haber like '$cta%'";
		} else {
			$txt1 = "";
			$txt2 = "";
		}


		set_time_limit(0);

		$pdf->SetFont('Calibri-light', '', 9);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(190, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(190, 5, $emisor->emp_identificacion, 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(190, 5, $emisor->emp_ciudad . "-" . $emisor->emp_pais, 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(190, 5, utf8_decode("TELÉFONO: ") . $emisor->emp_telefono, 0, 0, 'L');
		$pdf->SetX(0);
		$pdf->Cell(190, 5, $pdf->Image('./imagenes/' . $emisor->emp_logo, 175, 4, 25), 0, 0, 'R');
		$pdf->setY(30);
		$pdf->SetFont('Calibri-bold', '', 14);
		$pdf->Cell(200, 5, "LIBRO MAYOR - GENERAL", 0, 0, 'C');
		$pdf->Ln();
		$pdf->SetFont('Calibri-light', '', 13);
		$pdf->Cell(200, 5, "DE " . $desde . '  AL ' . $hasta, 0, 0, 'C');
		$pdf->Ln();



		$cns = $this->pdf_libro_mayor_model->lista_cuentas_fecha($desde, $hasta, $rst_cja->emp_id, $txt1, $txt2);
		$cuentas = array();
		$n = 0;
		$debe = 0;
		$haber = 0;
		$td = 0;
		$th = 0;
		$total = 0;
		$tot_saldo = 0;
		$cta2 = '';
		foreach ($cns as $rst) {
			$cns_cuentas = $this->pdf_libro_mayor_model->lista_asientos_cuenta_fecha($rst->con_concepto_debe, $desde, $hasta, $rst_cja->emp_id);

			foreach ($cns_cuentas as $rst_cta) {
				$n++;


				if ($rst_cta->tipo == 0) {
					$cuenta = $rst_cta->con_concepto_debe;
					$debe = $rst_cta->con_valor_debe;
					$haber = 0;
				} else {
					$cuenta = $rst_cta->con_concepto_haber;
					$debe = 0;
					$haber = $rst_cta->con_valor_haber;

				}

				$rst_ant = $this->pdf_libro_mayor_model->lista_suma_cuentas_ant($cuenta, $desde, $rst_cja->emp_id);
				if (empty($rst_ant)) {
					$sal = 0;
				} else {
					$sald = $rst_ant->debe - $rst_ant->haber;
				}

				$rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($cuenta);

				if ($cuenta != $cta2 && $n != 1) {
					///$pdf->SetFont('helvetica', 'B', 8);
					$pdf->SetFont('Calibri-bold', '', 10);
					$pdf->Cell(83, 5, "", 'T', 'C');
					$pdf->Cell(50, 5, "TOTAL", 'T', 0, 'L');
					$tot_saldo = $td - $th;
					$pdf->SetFont('Calibri-light', '', 10);
					$pdf->Cell(20, 5, number_format($td, $dec), 'T', 0, 'R');
					$pdf->Cell(20, 5, number_format($th, $dec), 'T', 0, 'R');
					$pdf->Cell(20, 5, number_format($total, $dec), 'T', 0, 'R');
					$pdf->Ln();
					$pdf->Ln();
					$n = 1;
					$td = 0;
					$th = 0;
					$total = 0;
				}
				if ($cuenta != $cta2) {

					$datos = $pdf->getY();
					if ($datos > 250) {
						$pdf->AddPage();
					}
						$pdf->SetFont('Calibri-light', '', 13);
						$pdf->Cell(85, 5, "CODIGO: " . trim($cuenta), 0, 0, 'L');
						$pdf->Ln();
						$pdf->Cell(85, 5, "CUENTA: " . strtoupper($rst_cuentas1->pln_descripcion), 0, 0, 'L');
						$pdf->Ln();

						$pdf->SetFont('Calibri-light', '', 10);
						$pdf->Cell(18, 5, "F. EMISION", 'TB', 0, 'C');
						$pdf->Cell(25, 5, "ASIENTO No", 'TB', 0, 'C');
						$pdf->Cell(40, 5, "CLIENTE/PROVEEDOR", 'TB', 0, 'C');
						$pdf->Cell(50, 5, "CONCEPTO", 'TB', 0, 'C');
						$pdf->Cell(20, 5, "DEBE", 'TB', 0, 'R');
						$pdf->Cell(20, 5, "HABER", 'TB', 0, 'R');
						$pdf->Cell(20, 5, "SALDO", 'TB', 0, 'R');
						$pdf->Ln();
						///aumenta fila saldo inicial
						$pdf->Cell(18, 5, "", '0', 0, 'C');
						$pdf->Cell(25, 5, "", '0', 0, 'C');
						$pdf->Cell(40, 5, "", '0', 0, 'C');
						$pdf->Cell(50, 5, "SALDO INICIAL", '0', 0, 'L');
						$pdf->Cell(20, 5, "", '0', 0, 'R');
						$pdf->Cell(20, 5, "", '0', 0, 'R');
						$pdf->Cell(20, 5, number_format($sald, 2), '0', 0, 'R');
						$pdf->Ln();
					
				}

				$rst_cli = $this->cliente_model->lista_un_cliente($rst_cta->cli_id);
				if (empty($rst_cli)) {
					$cliente = '';
				} else {
					$cliente = $rst_cli->cli_raz_social;
				}
				//$pdf->SetFont('helvetica', '', 8);
				$pdf->SetFont('Calibri-light', '', 10);
				$pdf->Cell(18, 5, $rst_cta->con_fecha_emision, '', 0, 'C');
				$pdf->Cell(25, 5, $rst_cta->con_asiento, '', 0, 'C');
				$pdf->SetFont('Calibri-light', '', 9);
				$pdf->Cell(40, 5, substr($cliente, 0, 22), '', 0, 'L');
				$pdf->Cell(50, 5, substr($rst_cta->con_concepto, 0, 30), '', 0, 'L');
				$pdf->SetFont('Calibri-light', '', 10);
				$pdf->Cell(20, 5, number_format($debe, $dec), '', 0, 'R');
				$valor_d = $debe;
				$td = round($td, $dec) + round($valor_d, $dec);
				$pdf->Cell(20, 5, number_format($haber, $dec), '', 0, 'R');
				$valor_h = round($haber, $dec);
				$th += round($valor_h, $dec);

				///cambio para calcular con saldo inicial
				$total = round($sald, $dec) + (round($total, $dec) - round($valor_h, $dec)) + round($valor_d, $dec);
				$sald = 0;
				$pdf->Cell(20, 5, number_format($total, $dec), '', 0, 'R');
				$pdf->Ln();

				$cta2 = $cuenta;
			}
		}

		$pdf->SetFont('Calibri-bold', '', 10);
		$pdf->Cell(83, 5, "", 'T', 'C');
		$pdf->Cell(50, 5, "TOTAL", 'T', 0, 'L');
		$total = $td - $th;
		$pdf->SetFont('Calibri-light', '', 10);
		$pdf->Cell(20, 5, number_format($td, $dec), 'T', 0, 'R');
		$pdf->Cell(20, 5, number_format($th, $dec), 'T', 0, 'R');
		$pdf->Cell(20, 5, number_format($total, $dec), 'T', 0, 'R');
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();


		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell(20, 5, '', '');
		$pdf->SetFont('Calibri-bold', '', 10);
		$pdf->Cell(40, 5, 'PREPARADO', 'T', 0, 'C');
		$pdf->Cell(20, 5, '', '');
		$pdf->Cell(40, 5, 'REVISADO', 'T', 0, 'C');
		$pdf->Cell(20, 5, '', '');
		$pdf->Cell(40, 5, 'AUTORIZADO', 'T', 0, 'C');



		$pdf->Output('libro_mayor.pdf', 'I');

	}

	public function excel($desde, $hasta, $opc_id, $cta = '')
	{
		$rst_opc = $this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja = $this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$dc = $this->configuracion_model->lista_una_configuracion('2');
		$dec = $dc->con_valor;
		$emisor = $this->empresa_model->lista_una_empresa($rst_cja->emp_id);
		if (!empty($cta)) {
			//// cambia buscados para encontrar coincidencias
			$txt1 = " and con_concepto_debe like'$cta%'";
			$txt2 = " and con_concepto_haber like '$cta%'";
		} else {
			$txt1 = "";
			$txt2 = "";
		}


		set_time_limit(0);

		$data = "<table><tr><td>";
		$data .= "</td></tr>";
		$data .= "<tr><td colspan='8' align='center'><strong>LIBRO MAYOR - GENERAL</strong></td></tr>";
		$data .= "<tr><td>";

		$data .= "<tr><td colspan='8'>PERIODO  DESDE:  $desde AL $hasta</td></tr>";

		$cns = $this->pdf_libro_mayor_model->lista_cuentas_fecha($desde, $hasta, $rst_cja->emp_id, $txt1, $txt2);
		$cuentas = array();
		$n = 0;
		$debe = 0;
		$haber = 0;
		$td = 0;
		$th = 0;
		$total = 0;
		$tot_saldo = 0;
		$cta2 = '';
		foreach ($cns as $rst) {
			$cns_cuentas = $this->pdf_libro_mayor_model->lista_asientos_cuenta_fecha($rst->con_concepto_debe, $desde, $hasta, $rst_cja->emp_id);

			foreach ($cns_cuentas as $rst_cta) {
				$n++;


				if ($rst_cta->tipo == 0) {
					$cuenta = $rst_cta->con_concepto_debe;
					$debe = $rst_cta->con_valor_debe;
					$haber = 0;
				} else {
					$cuenta = $rst_cta->con_concepto_haber;
					$debe = 0;
					$haber = $rst_cta->con_valor_haber;

				}

				$rst_ant = $this->pdf_libro_mayor_model->lista_suma_cuentas_ant($cuenta, $desde, $rst_cja->emp_id);
				if (empty($rst_ant)) {
					$sal = 0;
				} else {
					$sald = $rst_ant->debe - $rst_ant->haber;
				}

				$rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($cuenta);

				if ($cuenta != $cta2 && $n != 1) {
					$tot_saldo = $td - $th;
					$data .= "<tr>
	            			<td colspan='3'></td>
	            			<td><strong>TOTAL</strong></td>
	            			<td><strong>" . number_format($td, $dec) . "</strong></td>
	            			<td><strong>" . number_format($th, $dec) . "</strong></td>
	            			<td><strong>" . number_format($total, $dec) . "</strong></td>
	            		</tr>";
					$data .= "<tr><td><br><br></td></tr>";
					$n = 1;
					$td = 0;
					$th = 0;
					$total = 0;
				}
				if ($cuenta != $cta2) {
					$data .= "<tr>
	            			<td colspan='5'><strong>CODIGO: " . trim($cuenta) . "</td>
	            			</tr>
			        		<tr>
	            			<td colspan='5'><strong>CUENTA: " . strtoupper($rst_cuentas1->pln_descripcion) . "</td>
	            			</tr>
	            			<tr>
	            			<td><strong>F. EMISION</strong></td>
			        		<td><strong>ASIENTO No</strong></td>
			        		<td><strong>CLIENTE/PROVEEDOR</strong></td>
			        		<td><strong>CONCEPTO</strong></td>
			        		<td><strong>DEBE</strong></td>
			        		<td><strong>HABER</strong></td>
			        		<td><strong>SALDO</strong></td>
			        		</tr>
			        		<tr>
	            			<td></td>
	            			<td></td>
	            			<td><strong>SALDO INICIAL</strong></td>
	            			<td></td>
	            			<td></td>
	            			<td></td>
	            			<td><strong>" . number_format($sald, 2) . "</strong></td>
			        		</tr>";
				}

				$rst_cli = $this->cliente_model->lista_un_cliente($rst_cta->cli_id);
				if (empty($rst_cli)) {
					$cliente = '';
				} else {
					$cliente = $rst_cli->cli_raz_social;
				}
				$data .= "<tr>
	            			<td>$rst_cta->con_fecha_emision</td>
	            			<td>$rst_cta->con_asiento</td>
	            			<td>" . substr($cliente, 0, 22) . "</td>
	            			<td>" . substr($rst_cta->con_concepto, 0, 30) . "</td>
	            			<td>" . number_format($debe, $dec) . "</td>
	            			<td>" . number_format($haber, $dec) . "</td>";

				$valor_d = $debe;
				$td = round($td, $dec) + round($valor_d, $dec);
				$valor_h = round($haber, $dec);
				$th += round($valor_h, $dec);

				///cambio para calcular con saldo inicial
				$total = round($sald, $dec) + (round($total, $dec) - round($valor_h, $dec)) + round($valor_d, $dec);
				$sald = 0;
				$data .= "<td>" . number_format($total, $dec) . "</td>
	            		</tr>";

				$cta2 = $cuenta;
			}
		}


		$total = $td - $th;
		$data .= "<tr>
	            	<td colspan='3'></td>
	            	<td><strong>TOTAL</strong></td>
	            	<td><strong>" . number_format($td, $dec) . "</strong></td>
	            	<td><strong>" . number_format($th, $dec) . "</strong></td>
	            	<td><strong>" . number_format($total, $dec) . "</strong></td>
	            </tr>";
		$data .= "<tr><td><br><br></td></tr>";

		$data .= "<tr>
                    <td></td>
                    <td>PREPARADO</td>
                    <td></td>
                    <td>REVISADO</td>
                    <td></td>
                    <td>AUTORIZADO</td>
                </tr>";
		$titulo = '';
		$file = "libro_mayor" . date('Ymd');
		$this->export_excel->to_excel2($data, $file, $titulo);

	}

}