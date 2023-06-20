<section class="content" style="margin-top:-30px" class="page-break">
    <!-- <td><img src="<?php echo base_url() . 'imagenes/logo_empresa.jpg' ?>" width="250px" height="100px"></td> -->
    <table width="100%">
        <tr>
            <td colspan="2">
                <table width="100%" style="margin-right: -10px;">
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td width="10%"><img src="<?php echo base_url() . 'imagenes/' . $nota->emp_logo ?>"
                                width="130px" height="70px"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <table id="encabezado2" width="180%">

        <tr>

            <td style="width:15%"></td>
            <td style="width:auto;border-collapse: separate;" class="titulo">COMPROBANTE DE NOTA DE CREDITO
            </td>
        </tr>
        <tr>
            <td></td>


            <td style="width:auto;text-align: right;" class="sub_titulo">
                <?php echo utf8_encode('N°: ') ?>
                <label class="sub_titulo" style="color:red">
                    <?php echo $nota->rnc_numero ?>
                </label>
            </td>
            <td style="width:10%"></td>
            <td style="width:15%"></td>
            <td style="width:15%"></td>
        </tr>


    </table>

    <table class="encabezados" width="105%">
        <tr>
            <td width="180px">
                <?php echo utf8_encode('Fecha de Emisión: ') ?>
            </td>

            <td width="140px"><label style="font-weight: normal;">
                    <?php echo $nota->rnc_fecha_emision ?>
                </label></td>
                <td width="170px">
                <?php echo utf8_encode('Comprobante que se modifica:') ?>
            </td>
            <td width="170px">
                <label style="font-weight: normal;">
                    <?php echo $nota->rnc_num_comp_modifica ?>
                </label>
            </td>
        </tr>


        <tr>
            
            <td width="240px">
                <?php echo utf8_encode('Fecha emisión-Comprobante a modificar:') ?>
            </td>
            <td>
                <label style="font-weight: normal;">
                    <?php echo $nota->rnc_fecha_emi_comp ?>
            </td>
            </label>
            <td>
        </tr>


        <tr>

            <td>
                <?php echo utf8_encode('Razón de modificación: ') ?>
            </td>
            <td colspan="2" width="240px">
                <label style="font-weight: normal;">
                    <?php echo ucfirst(strtolower(utf8_decode($nota->rnc_motivo))) ?>
                </label>
            </td>

        </tr>
        <tr>
        <td width="100px">
                <?php echo utf8_encode('Número de Autorización:') ?>
            </td>
        </tr>
        <tr>
        <td width="100px" style="font-weight: normal; font-size:9">
                
            <?php echo $nota->rnc_autorizacion ?>
               
            </td>
        </tr>

        <tr>
            <td>
                <?php echo utf8_encode('Asiento Contable:  ') ?>
            </td>

            <td class="digito"><label style="font-weight: normal;">
                    <?php echo $asientos->con_asiento ?>
                </label> </td>

        </tr>
        <tr>
            <td></td>

        </tr>
    </table>
    

    <table class="encabezados_2" width="110%">
        <tr>
            <td width="180px">
                <?php echo utf8_encode('Razón social:') ?>
            </td>
            <td width="140px"><label style="font-weight: normal;">
                    <?php echo $nota->cli_raz_social ?>
                </label> </td>
            <td width="100px">
                <?php echo utf8_encode('Cédula / Ruc:') ?>
            </td>
            <td><label style="font-weight: normal;">
                    <?php echo $nota->cli_ced_ruc ?>
                </label> </td>
        </tr>

        <tr>

            <td width="100px">
                <?php echo utf8_encode('Teléfono:') ?>
            </td>
            <td><label style="font-weight: normal;">
                    <?php echo ucwords(strtolower($nota->cli_telefono)) ?>
                </label></td>
            <td>Email:</td>
            <td><label style="font-weight: normal;">
                    <?php echo strtolower($nota->cli_email) ?>
                </label></td>
        </tr>

        <tr>
            <td width="180px">
                <?php echo utf8_encode('Dirección') ?>
            </td>
            <td><label style="font-weight: normal;">
                    <?php echo trim(ucwords(strtolower($nota->cli_calle_prin))) ?>
                </label></td>
        </tr>

    </table>

    <table id="detalle" width="100%">
        <thead>
            <tr>
                <th style="width:10px">Codigo Principal</th>
                <th style="width:10px">Cantidad</th>
                <th style="width:30px">Descripcion</th>
                <th style="width:10px">Precio Unitario</th>
                <th style="width:10px">Descuento</th>
                <th style="width:10px">Precio Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dec = $dec->con_valor;
            $dcc = $dcc->con_valor;
            foreach ($cns_det as $det) {
                ?>
                <tr>
                    <td style="width:10px">
                        <?php echo $det->pro_codigo ?>
                    </td>
                    <td style="width:10px" class="numerico">
                        <?php echo number_format($det->cantidad, $dcc) ?>
                    </td>
                    <td style="width:30px">
                        <?php echo $det->pro_descripcion ?>
                    </td>
                    <td style="width:10px" class="numerico">
                        <?php echo number_format($det->pro_precio, $dec) ?>
                    </td>
                    <td style="width:10px" class="numerico">
                        <?php echo number_format($det->pro_descuento, $dec) ?>
                    </td>
                    <td style="width:10px" class="numerico">
                        <?php echo number_format($det->precio_tot, $dec) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tbody>
            <tr>
                <td id="info" colspan="3" rowspan="8" valign="top">
                </td>
                <th colspan="2">Subtotal 12%</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_subtotal12, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Subtotal IVA 0%</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_subtotal0, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Subtotal excento de IVA</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_subtotal_ex_iva, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Subtotal no objeto de IVA</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_subtotal_no_iva, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Subtotal sin impuestos</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_subtotal, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Descuento</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_total_descuento, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">IVA 12%</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_total_iva, $dec) ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">Valor Total</th>
                <td class="numerico">
                    <?php echo number_format($nota->rnc_total_valor, $dec) ?>
                </td>
            </tr>


        </tbody>
    </table>


    <p colspan="1" style="width:auto;border-collapse: separate; text-align: center;"><strong>ASIENTO CONTABLE
        </strong> </p>


    <table id="detalle" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th style="width:10%">Codigo</th>
                <th style="width:30%">Cuenta</th>
                <th>Concepto</th>
                <th>Debe</th>
                <th>Haber</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $cuentas = array();
            $asiento_model = new asiento_model();
            $plan_cuentas_model = new Plan_cuentas_model();
            $cns_cuentas = $asiento_model->asiento_reg_fac_detalle($nota->rnc_id);

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





    <style type="text/css">
        *,
        label {
            font-size: 13px;
            /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
            /* font-family:"Calibri ligth";*/
            /* font-family: 'Source Sans Pro';*/
            font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
            margin-left: 6px;
            margin-right: 20px;
            justify-content: right;

        }

        th,
        td {
            padding-top: -5px;
            padding-bottom: 2px;
            padding-left: 3px;
            padding-right: 4px;
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

        #encabezado3 td,
        #encabezado3 th {
            text-align: left;
            font-size: 12px;

        }

        .encabezados {
            border-top: 1px solid;
            border-bottom: 1px solid;
            text-align: left;
            font-weight: bold;
            justify-content: right;
            font-size: 10;
        }
        .encabezados_2 {
            
            border-bottom: 1px solid;
            text-align: left;
            font-weight: bold;
            justify-content: right;
            font-size: 10;
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