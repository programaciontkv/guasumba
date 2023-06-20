<section class="content" style="margin-top:-30px">
    <table width="100%">
        <tr>
            <td colspan="2">
                <table width="100%" style="margin-right: 0px;">
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td width="15%"><img src="<?php echo base_url() . 'imagenes/' . $retencion->emp_logo ?>"
                                width="130px" height="70px"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>

            <td width="100%">
                <table id="encabezado2" width="135%">

                    <tr>
                        <td style="width:20%"></td>
                        <td style="width:auto;border-collapse: separate;" class="titulo">COMPROBANTE DE RETENCION</td>
                    </tr>
                    <tr>
                        <td></td>


                        <td style="width:auto;text-align: right;" class="sub_titulo">
                            <?php echo utf8_encode('N°: ') ?>
                            <label class="sub_titulo" style="color:red">
                                No.
                                <?php echo $retencion->rgr_numero ?>
                            </label>
                        </td>
                        <td style="width:10%"></td>
                        <td style="width:10%"></td>
                        <td style="width:5%"></td>
                    </tr>


                </table>
            </td>
        </tr>
        <tr>
            <td width="48%" valign="bottom">
                <table id="encabezado1" width="110%">
                    <tr>
                        <td>
                            <?php echo utf8_encode('Fecha de Emisión:') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo $retencion->rgr_fecha_emision ?>
                            </label> </td>
                        <td>
                            <?php echo utf8_encode('Número de Autorización:') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo $retencion->rgr_autorizacion ?>
                            </label></td>
                    </tr>


                    <tr>
                        <td>
                            <?php echo utf8_encode('Razón social:') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo $retencion->cli_raz_social ?>
                            </label> </td>
                        <td>
                            <?php echo utf8_encode('Cédula / Ruc:') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo $retencion->cli_ced_ruc ?>
                            </label> </td>
                    </tr>


                    <tr>
                        <td>
                            <?php echo utf8_encode('Teléfono:') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo ucwords(strtolower($retencion->cli_telefono)) ?>
                            </label></td>
                        <td>Email:</td>
                        <td><label style="font-weight: normal;">
                                <?php echo strtolower($retencion->cli_email) ?>
                            </label></td>
                    </tr>

                    <tr>
                        <td>
                            <?php echo utf8_encode('Dirección') ?>
                        </td>
                        <td><label style="font-weight: normal;">
                                <?php echo trim(ucwords(strtolower($retencion->cli_calle_prin))) ?>
                            </label></td>
                    </tr>


                </table>
            </td>
        <tr>
            <td><br></td>
        </tr>

        </tr>
        <tr>

            <td colspan="7" style="width:auto;border-collapse: separate; text-align: center;"><strong>DETALLE DEL
                    REGISTRO</strong> </td>

        </tr>

        <tr>
            <td colspan="2">
                <table id="detalle" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">Comprobante</th>
                            <th >
                                <?php echo utf8_encode('Número') ?>
                            </th>
                            <th>Ejercicio Fiscal</th>
                            <th style="width:70px">
                                <?php echo utf8_encode('Base Imponible para la Retención') ?>
                            </th>
                            <th style="width:70px">Impuesto</th>
                            <th style="width:70px">
                                <?php echo utf8_encode('% Porcentaje de Retención') ?>
                            </th>
                            <th style="width:70px">$ Valor Retenido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dec = $dec->con_valor;
                        $dcc = $dcc->con_valor;
                        $t = 0;
                        foreach ($cns_det as $det) {
                            if ($det->drr_tipo_impuesto = 'IVA') {
                                $imp = 'IVA';
                            } else if ($det->drr_tipo_impuesto = 'IR') {
                                $imp = 'RENTA';
                            } else if ($det->drr_tipo_impuesto = 'IC') {
                                $imp = 'ICE';
                            }
                            ?>
                            <tr>
                                <td width="50px">FACTURA</td>
                                <td >
                                    <?php echo $retencion->rgr_num_comp_retiene ?>
                                </td>
                                <td>
                                    <?php echo $det->drr_ejercicio_fiscal ?>
                                </td>
                                <td class="numerico">
                                    <?php echo number_format($det->drr_base_imponible, $dcc) ?>
                                </td>
                                <td>
                                    <?php echo $imp ?>
                                </td>
                                <td class="numerico">
                                    <?php echo number_format($det->drr_procentaje_retencion, $dec) ?>
                                </td>
                                <td class="numerico">
                                    <?php echo number_format($det->drr_valor, $dec) ?>
                                </td>
                            </tr>
                            <?php
                            $t += $det->drr_valor;
                        }
                        ?>
                        <tr>
                            <td class="numerico" colspan="6">TOTAL</td>
                            <td class="numerico">
                                <?php echo number_format($t, $dec) ?>
                            </td>

                        </tr>
                    </tbody>

                </table>
            </td>
        </tr>
        <tr>

            <td colspan="7" style="width:auto;border-collapse: separate; text-align: center;"><strong>ASIENTO CONTABLE
                </strong> </td>

        </tr>
        <tr>
            <td></td>
        </tr>

        <tr>
            <td colspan="2">
                <table id="detalle" width="100%">

                    <tr>
                        <th>No</th>
                        <th style="width:10%">Código</th>
                        <th style="width:30%">Cuenta</th>
                        <th>Concepto</th>
                        <th>Debe</th>
                        <th>Haber</th>
                    </tr>

                    <tbody>
                        <?php

                        $cuentas = array();
                        $asiento_model = new asiento_model();
                        $plan_cuentas_model = new Plan_cuentas_model();
                        $cns_cuentas = $asiento_model->asiento_reg_fac_detalle($retencion->rgr_id);

                        foreach ($cns_cuentas as $rst_cuentas) {
                            if (!empty($rst_cuentas->con_concepto_debe)) {
                                array_push($cuentas, $rst_cuentas->con_concepto_debe . '&' . $rst_cuentas->con_id . '&0');
                            }
                            if (!empty($rst_cuentas->con_concepto_haber)) {
                                array_push($cuentas, $rst_cuentas->con_concepto_haber . '&' . $rst_cuentas->con_id . '&1');
                            }
                        }
                        //Eliminar Duplicados del Array
                        $n = 0;
                        $j = 1;
                        $td = 0;
                        $th = 0;

                        while ($n < count($cuentas)) {
                            $cta = explode('&', $cuentas[$n]);
                            $rst_cuentas1 = $plan_cuentas_model->lista_un_plan_cuentas_codigo($cta[0]);
                            $vdebe = 0;
                            $vhaber = 0;
                            if ($cta[2] == 0) {
                                $rst_v = $asiento_model->listar_asientos_debe($asientos->con_asiento, $cta[0], $cta[1]);
                                $vdebe = $rst_v->con_valor_debe;
                                $vhaber = 0;
                            } else {
                                $rst_v = $asiento_model->listar_asientos_haber($asientos->con_asiento, $cta[0], $cta[1]);
                                $vdebe = 0;
                                $vhaber = $rst_v->con_valor_haber;
                            }
                            $n++;
                            $j++;
                            $td += round($vdebe, $dec);
                            $th += round($vhaber, $dec);

                            ?>
                            <tr>
                                <td>
                                    <?php echo $n ?>
                                </td>
                                <td style="width:10%">
                                    <?php echo $rst_cuentas1->pln_codigo ?>
                                </td>
                                <td style="width:30%">
                                    <?php echo $rst_cuentas1->pln_descripcion ?>
                                </td>
                                <td>
                                    <?php echo $rst_v->con_concepto ?>
                                </td>
                                <td class="numerico">
                                    <?php echo number_format($vdebe, $dec) ?>
                                </td>
                                <td class="numerico">
                                    <?php echo number_format($vhaber, $dec) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                        <tr>
                            <td class="numerico" colspan="4">TOTAL</td>
                            <td class="numerico">
                                <?php echo number_format($td, $dec) ?>
                            </td>
                            <td class="numerico">
                                <?php echo number_format($th, $dec) ?>
                            </td>
                        </tr>
                    </tbody>


                </table>



            </td>
        </tr>
    </table>

    <style type="text/css">
        *,
        label {
            font-size: 15px;
            /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
            /* font-family:"Calibri ligth";*/
            /* font-family: 'Source Sans Pro';*/
            /*        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; */
            margin-left: 6px;
            margin-right: 20px;
            justify-content: right;

        }



        .numerico {
            text-align: right;
        }

        #encabezado3 {
            border-top: 1px solid;
            border-bottom: 1px solid;
            text-align: left;
        }

        /*#detalle{
        border-collapse: collapse;
    }*/

        #encabezado2 tr,
        #encabezado2 th,
        #encabezado2 td {
            font-weight: bold;
            justify-content: right;

        }



        #encabezado1 td,
        #encabezado1 th {
            text-align: left;
            font-size: 12px;
            font-weight: bold;


        }

        #encabezado1 {
            border-top: 1px solid;
            border-bottom: 1px solid;
            text-align: left;
        }

        #encabezado3 td,
        #encabezado3 th {
            text-align: left;
            font-size: 12px;

        }

        #detalle td,
        #detalle th {
            /*border: 1px solid;
        border-color: #ffffff;
         background:#d7d7d7; */
            border-right: 2px solid #d7d7d7 !important;
            border-top: 2px solid #d7d7d7 !important;
            border-bottom: 2px solid #d7d7d7 !important;
            border-left: 2px solid #d7d7d7 !important;

        }

        #detalle tr:nth-child(2n-1) td,
        #detalle tr:nth-child(2n-1) th {
            background: #DFDFDF !important;

        }

        #info td,
        #info th,
        #info tr {
            border: none;

            border-right: 2px solid #ffffff !important;
            border-top: 2px solid #ffffff !important;
            border-bottom: 2px solid #ffffff !important;
            border-left: 2px solid #ffffff !important;

        }

        #info {
            background: white !important;
        }

        #pagos {
            border-top: 1px solid;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
        }

        .mensaje {
            color: #828282;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            justify-content: right;
            font-weight: bolder;
        }
    </style>