
<section class="content-header">
      <h1>
        Cierre de Caja <?php echo $titulo?>
      </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $dec=$dec->con_valor;
      if($this->session->flashdata('error')){
      ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
        </div>
          <?php
          }
          ?>
        <div class="box box-primary">
        <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="box-body" >
            <table class="table col-sm-6" border="0">
              <tr>
                <td class="col-sm-5">
                  <div class="box-body">
                  <div class="panel panel-success col-sm-8">
                  <table class="table">
                    <tr>
                      <td><label>Fecha:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_fecha')!=''){ echo 'has-error';}?> ">
                        <input type="date" class="form-control" name="cie_fecha" id="cie_fecha" value="<?php if(validation_errors()!=''){ echo set_value('cie_fecha');}else{ echo $cierre_caja->cie_fecha;}?>" readonly>
                                  <?php echo form_error("cie_fecha","<span class='help-block'>","</span>");?>
                        <input type="hidden" class="form-control" name="cie_secuencial" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('cie_secuencial');}else{ echo $cierre_caja->cie_secuencial;}?>">
                        <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $cierre_caja->emp_id;}?>">
                        <input type="hidden" class="form-control" name="cie_punto_emision" id="cie_punto_emision" value="<?php if(validation_errors()!=''){ echo set_value('cie_punto_emision');}else{ echo $cierre_caja->cie_punto_emision;}?>">
                        </div>
                      </td>
                      <td><label>Hora:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_hora')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_hora" id="cie_hora" value="<?php if(validation_errors()!=''){ echo set_value('cie_hora');}else{ echo $cierre_caja->cie_hora;}?>" readonly>
                        <?php echo form_error("cie_hora","<span class='help-block'>","</span>");?>
                        </div>
                      </td>  
                    </tr>
                    <tr>
                      <td><label>Usuario:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_usuario')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_usuario" id="cie_usuario" value="<?php if(validation_errors()!=''){ echo set_value('cie_usuario');}else{ echo $cierre_caja->usu_person;}?>" readonly>
                        <?php echo form_error("cie_usuario","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Almacen:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_punto_emision')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_punto_emision" id="cie_punto_emision" value="<?php if(validation_errors()!=''){ echo set_value('cie_punto_emision');}else{ echo $cierre_caja->emi_nombre;}?>" readonly>
                        <?php echo form_error("cie_punto_emision","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Facturas Emitidas:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_fac_emitidas')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_fac_emitidas" id="cie_fac_emitidas" value="<?php if(validation_errors()!=''){ echo set_value('cie_fac_emitidas');}else{ echo $cierre_caja->cie_fac_emitidas;}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_fac_emitidas","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Productos Facturados:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_productos_facturados')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_productos_facturados" id="cie_productos_facturados" value="<?php if(validation_errors()!=''){ echo set_value('cie_productos_facturados');}else{ echo $cierre_caja->cie_productos_facturados;}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_productos_facturados","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Subtotal:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_subtotal')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_subtotal" id="cie_subtotal" value="<?php if(validation_errors()!=''){ echo set_value('cie_subtotal');}else{ echo str_replace(',','',number_format($cierre_caja->cie_subtotal,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_subtotal","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Descuento:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_descuento')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_descuento" id="cie_descuento" value="<?php if(validation_errors()!=''){ echo set_value('cie_descuento');}else{ echo str_replace(',','',number_format($cierre_caja->cie_descuento,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_descuento","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>IVA:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_iva')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_iva" id="cie_iva" value="<?php if(validation_errors()!=''){ echo set_value('cie_iva');}else{ echo str_replace(',','',number_format($cierre_caja->cie_iva,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_iva","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Total:</label></td>
                      <td>
                        <div class="form-group">
                        <?php
                          $total=(round($cierre_caja->cie_subtotal,$dec)-round($cierre_caja->cie_descuento,$dec))+round($cierre_caja->cie_iva,$dec);
                        ?>
                        <input type="text" class="form-control" name="total" id="total" value="<?php echo str_replace(',','',number_format($total,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Total Facturas:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_total_facturas')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_total_facturas" id="cie_total_facturas" value="<?php if(validation_errors()!=''){ echo set_value('cie_total_facturas');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_facturas,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_total_facturas","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Total Notas de Credito:</label></td>
                      <td>
                        <div class="form-group <?php if(form_error('cie_total_notas_credito')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_total_notas_credito" id="cie_total_notas_credito" value="<?php if(validation_errors()!=''){ echo set_value('cie_total_notas_credito');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_notas_credito,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_total_notas_credito","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                    <tr>
                      <td><label>Total en Caja:</label></td>
                      <td>
                        <?php
                        $total_caja=round($cierre_caja->cie_total_facturas,$dec)-round($cierre_caja->cie_total_notas_credito,$dec);
                        ?>
                        <div class="form-group <?php if(form_error('cie_total_caja')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cie_total_caja" id="cie_total_caja" value="<?php if(validation_errors()!=''){ echo set_value('cie_total_caja');}else{ echo str_replace(',','',number_format($total_caja,$dec));}?>" readonly style="text-align:right">
                        <?php echo form_error("cie_total_caja","<span class='help-block'>","</span>");?>
                        </div>
                      </td> 
                    </tr>
                  </table>
                  </div>
                  </div>
              </tr>    
              <tr>
                <td class="col-sm-5">
                  <div class="box-body">
                  <div class="panel panel-success col-sm-8">
                  <div class="panel panel-heading"><label>Formas de Pago</label></div>
                  <table class="table">
                    <tr>
                      <td><label>Tipo Pago</label></td>
                      <td><label>Valor</label></td>
                      <td></td>
                      <td><label>Confirmar</label></td>
                    </tr>
                    <tr>
                      <td><label>1 Tarjeta de Credito</label></td>
                      <td>
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_tarjeta_credito" id="cie_total_tarjeta_credito" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_tarjeta_credito,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="tc" id="tc" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_nc')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_nc" id="cie_camb_nc" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_nc');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_tarjeta_credito,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_nc","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>2 Tarjeta de Debito</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_tarjeta_debito" id="cie_total_tarjeta_debito" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_tarjeta_debito,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="td" id="td" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_tc')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_tc" id="cie_camb_tc" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_tc');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_tarjeta_debito,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_tc","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>3 Cheque</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_cheque" id="cie_total_cheque" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_cheque,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="cheque" id="cheque" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_cheque')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_cheque" id="cie_camb_cheque" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_cheque');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_cheque,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_cheque","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>4 Efectivo</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="total_efectivo" id="total_efectivo" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_efectivo,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="efec" id="efec" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_efectivo')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_efectivo" id="cie_camb_efectivo" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_efectivo');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_efectivo,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_efectivo","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>5 Certificados</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_certificados" id="cie_total_certificados" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_certificados,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="cert" id="cert" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_certif')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_certif" id="cie_camb_certif" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_certif');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_certificados,$dec));}?>" readonly  style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_certif","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>6 Transferencias</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_bonos" id="cie_total_bonos" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_bonos,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="bono" id="bono" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_bonos')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_bonos" id="cie_camb_bonos" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_bonos');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_bonos,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_bonos","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>7 Retencion</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_retencion" id="cie_total_retencion" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_retencion,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="ret" id="ret" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_ret')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_ret" id="cie_camb_ret" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_ret');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_retencion,$dec));}?>" readonly style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_ret","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>8 Nota Credito</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_not_credito" id="cie_total_not_credito" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_not_credito,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="nt_cre" id="nt_cre" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_not_cre')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_not_cre" id="cie_camb_not_cre" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_not_cre');}else{ echo str_replace(',','',number_format($cierre_caja->cie_total_not_credito,$dec));}?>" readonly  style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_not_cre","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>9 Credito</label></td>
                      <td >
                        <div class="form-group">
                        <input type="text" class="form-control" name="cie_total_credito" id="cie_total_credito" value="<?php echo str_replace(',','',number_format($cierre_caja->cie_total_credito,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                        <input type="checkbox" name="cred" id="cred" onclick="activar(this.form)">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('cie_camb_credito')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="cie_camb_credito" id="cie_camb_credito" value="<?php if(validation_errors()!=''){ echo set_value('cie_camb_credito');}else{ echo str_replace(',','',number_format($cierre_caja->cie_camb_credito,$dec));}?>" readonly  style="text-align:right" onchange="calculo()">
                        <?php echo form_error("cie_camb_credito","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><label>Total en Caja</label></td>
                      <td >
                        <?php 
                        $total_forpagos=round($cierre_caja->cie_total_tarjeta_credito,$dec)+round($cierre_caja->cie_total_tarjeta_debito,$dec)+round($cierre_caja->cie_total_cheque,$dec)+round($cierre_caja->cie_total_efectivo,$dec)+round($cierre_caja->cie_total_certificados,$dec)+round($cierre_caja->cie_total_bonos,$dec)+round($cierre_caja->cie_total_retencion,$dec)+round($cierre_caja->cie_total_not_credito,$dec)+round($cierre_caja->cie_camb_not_cre,$dec);
                        ?>
                        <div class="form-group">
                        <input type="text" class="form-control" name="total_forpagos" id="total_forpagos" value="<?php echo str_replace(',','',number_format($total_forpagos,$dec));?>" readonly style="text-align:right">
                        </div>
                      </td>
                      <td style="text-align: right;">
                      </td>
                      <td >
                        <div class="form-group <?php if(form_error('total_forpagos2')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control decimal" name="total_forpagos2" id="total_forpagos2" value="<?php if(validation_errors()!=''){ echo set_value('total_forpagos2');}else{ echo str_replace(',','',number_format($total_forpagos,$dec));}?>" readonly  style="text-align:right">
                        <?php echo form_error("total_forpagos2","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                  </table>
                  </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <input type="hidden" class="form-control" name="cie_id" value="<?php echo $cierre_caja->cie_id?>">
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
            <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
          </div>
        </form>
      </div>
      </div>
      <!-- /.row -->
    </section>
    

    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }

      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 4px !important;
        margin-top: 4px !important;
        padding-bottom: 4px !important;
        padding-top: 4px !important;
      }
      
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
      }
    </style>
    <script >

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }

      function activar(form) {
                if (form.tc.checked == true) {
                    $('#cie_camb_nc').attr('readonly',false);
                } else {
                    $('#cie_camb_nc').attr('readonly',true);
                }
                if (form.td.checked == true) {
                  $('#cie_camb_tc').attr('readonly',false);
                } else {
                    $('#cie_camb_tc').attr('readonly',true);
                }
                if (form.cheque.checked == true) {
                  $('#cie_camb_cheque').attr('readonly',false);
                } else {
                    $('#cie_camb_cheque').attr('readonly',true);
                }
                if (form.efec.checked == true) {
                  $('#cie_camb_efectivo').attr('readonly',false);
                } else {
                    $('#cie_camb_efectivo').attr('readonly',true);
                }
                if (form.cert.checked == true) {
                  $('#cie_camb_certif').attr('readonly',false);
                } else {
                    $('#cie_camb_certif').attr('readonly',true);
                }
                if (form.bono.checked == true) {
                  $('#cie_camb_bonos').attr('readonly',false);
                } else {
                    $('#cie_camb_bonos').attr('readonly',true);
                }
                if (form.ret.checked == true) {
                  $('#cie_camb_ret').attr('readonly',false);
                } else {
                    $('#cie_camb_ret').attr('readonly',true);
                }
                if (form.nt_cre.checked == true) {
                  $('#cie_camb_not_cre').attr('readonly',false);
                } else {
                  $('#cie_camb_not_cre').attr('readonly',true);
                }
                if (form.cred.checked == true) {
                  $('#cie_camb_credito').attr('readonly',false);
                } else {
                  $('#cie_camb_credito').attr('readonly',true);
                }
            }

            

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }

            function calculo() {
                nc = $('#cie_camb_nc').val().replace(',', '');
                nd = $('#cie_camb_tc').val().replace(',', '');
                che = $('#cie_camb_cheque').val().replace(',', '');
                efec = $('#cie_camb_efectivo').val().replace(',', '');
                cert = $('#cie_camb_certif').val().replace(',', '');
                bon = $('#cie_camb_bonos').val().replace(',', '');
                ret = $('#cie_camb_ret').val().replace(',', '');
                notc = $('#cie_camb_not_cre').val().replace(',', '');
                cre = $('#cie_camb_credito').val().replace(',', '');
                sumatot = 0;
                sumatot = round(nc,dec) + round(nd,dec) + round(che,dec) + round(efec,dec) + round(cert,dec) + round(bon,dec) + round(ret,dec) + round(notc,dec) + round(cre,dec);

                $('#total_forpagos2').val(sumatot.toFixed(dec)).replace(',', '');
            }

            function save() {
                        if (cie_camb_nc.value.length == 0) {
                            $("#cie_camb_nc").css({borderColor: "red"});
                            $("#cie_camb_nc").focus();
                            return false;
                        } else if (cie_camb_tc.value.length == 0) {
                            $("#cie_camb_tc").css({borderColor: "red"});
                            $("#cie_camb_tc").focus();
                            return false;
                        } else if (cie_camb_cheque.value.length == 0) {
                            $("#cie_camb_cheque").css({borderColor: "red"});
                            $("#cie_camb_cheque").focus();
                            return false;
                        } else if (cie_camb_efectivo.value.length == 0) {
                            $("#cie_camb_efectivo").css({borderColor: "red"});
                            $("#cie_camb_efectivo").focus();
                            return false;
                        } else if (cie_camb_certif.value.length == 0) {
                            $("#cie_camb_certif").css({borderColor: "red"});
                            $("#cie_camb_certif").focus();
                            return false;
                        } else if (cie_camb_bonos.value.length == 0) {
                            $("#cie_camb_bonos").css({borderColor: "red"});
                            $("#cie_camb_bonos").focus();
                            return false;
                        } else if (cie_camb_ret.value.length == 0) {
                            $("#cie_camb_ret").css({borderColor: "red"});
                            $("#cie_camb_ret").focus();
                            return false;
                        } else if (cie_camb_not_cre.value.length == 0) {
                            $("#cie_camb_not_cre").css({borderColor: "red"});
                            $("#cie_camb_not_cre").focus();
                            return false;
                        } else if (cie_camb_credito.value.length == 0) {
                            $("#cie_camb_credito").css({borderColor: "red"});
                            $("#cie_camb_credito").focus();
                            return false;
                        }
                        
                     $('#frm_save').submit();   
               }   
    </script>

