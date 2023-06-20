
<section class="content-header">
      <h1>
        Cuentas por Pagar <?php echo $titulo?>
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
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
                <table class="table col-sm-12" border="0">
                  <tr>
                    <td class="col-sm-6">
                      <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading"><label>Datos Generales</label></div>
                              <table class="table">
                                <tr>
                                  <td><label>Codigo Proveedor:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_codigo')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_codigo" id="cli_codigo" value="<?php if(validation_errors()!=''){ echo set_value('cli_codigo');}else{ echo $cliente->cli_codigo;}?>" readonly>
                                          <?php echo form_error("cli_codigo","<span class='help-block'>","</span>");?>
                                    </div>
                                    <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $ctasxpag->emp_id;}?>">
                                    <input type="hidden" class="form-control" name="reg_id" id="reg_id" value="<?php if(validation_errors()!=''){ echo set_value('reg_id');}else{ echo $ctasxpag->reg_id;}?>">
                                    <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $cliente->cli_id;}?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Nombre Proveedor:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social');}else{ echo $cliente->cli_raz_social;}?>" readonly>
                                          <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>    
                      </td>
                      <td class="col-sm-6">
                        <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading"><label>Cruce de Cuentas <input type="checkbox" name="cruce" id="cruce" onclick="habilitar(1)"></label></div>
                            <table class="table">
                              
                              <tr class="cruce" style="display: none">  
                                <td><label>Factura</label></td>
                                <td>
                                    <div class="form-group <?php if(form_error('fac_numero')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control documento" name="fac_numero" id="fac_numero" value="<?php if(validation_errors()!=''){ echo set_value('fac_numero');}else{ echo $ctasxpag->fac_numero;}?>" onchange="traer_factura()" maxlength="17">
                                    <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="">
                                    <input type="hidden" class="form-control" name="numero" id="numero" value="<?php echo $ctasxpag->numero?>">
                                    </div>
                                </td>
                              </tr>
                              <tr class="cruce" style="display: none">  
                                <td><label>Valor Factura</label></td>
                                <td>
                                    <div class="form-group <?php if(form_error('fac_total_valor')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="fac_total_valor" id="fac_total_valor" value="<?php if(validation_errors()!=''){ echo set_value('fac_total_valor');}else{ echo $ctasxpag->fac_total_valor;}?>" readonly>
                                    <?php echo form_error("fac_total_valor","<span class='help-block'>","</span>");?>
                                    </div>
                                </td>
                              </tr>
                              <tr class="cruce" style="display: none">  
                                <td><label>Valor a Pagar</label></td>
                                <td>
                                    <div class="form-group <?php if(form_error('valor_cruce')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="valor_cruce" id="valor_cruce" value="<?php if(validation_errors()!=''){ echo set_value('valor_cruce');}else{ echo $ctasxpag->valor_cruce;}?>" onchange="validar(this)">
                                    <?php echo form_error("valor_cruce","<span class='help-block'>","</span>");?>
                                    </div>
                                </td>
                              </tr>
                              <tr class="cruce" style="display: none">  
                                <td colspan="2">
                                    <button type="button" class="btn btn-primary" onclick="save(2)">Aceptar</button>
                                    <a href="<?php echo $cancelar2;?>" class="btn btn-default">Cancelar</a>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                       <td class="col-sm-12" colspan="2">
                        <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                            <table class="table table-bordered table-striped" id="tbl_detalle">
                              <thead>
                                <tr>
                                  <th>Fecha</th>
                                  <th>Forma de Pago</th>
                                  <th>Documento</th>
                                  <th>Concepto</th>
                                  <th>Debito</th>
                                  <th>Credito</th>
                                  <th>Saldo</th>
                                  <th>Saldo Vencido</th>
                                </tr>
                              </thead>

                              <tbody>
                                <?php
                                $saldo=0;
                                $sal_ven=0;
                                $t_debito=0;
                                $t_credito=0;
                                $n=0;
                                foreach ($cns_pag as $pag) {
                                  $n++;
                                  $saldo+=round($pag->pag_valor,$dec);
                                  if($saldo_vencido->pag_fecha_v<date('Y-m-d')){
                                    $sal_ven=round($saldo_vencido->reg_total,$dec)-round($saldo_vencido->pago,$dec);
                                  }else{
                                    $sal_ven=0;
                                  }
                                  $debito=round($pag->pag_valor,$dec);
                                  $cerdito=0;
                                ?>
                                    <tr>
                                        <td><?php echo $pag->pag_fecha_v?></td>
                                        <td></td>
                                        <td><?php echo $pag->reg_num_documento?></td>
                                        <td>FACTURACION EN VENTAS</td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($debito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo number_format($credito,$dec)?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                                        <td style="text-align: right;">
                                            <?php 
                                            if($n==1){
                                              echo str_replace(',','',number_format($sal_ven,$dec));
                                            }
                                            
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                $t_debito+=$debito;
                                $t_credito+=$credito;
                                }
                                ?>
                                <?php
                                
                                foreach ($cns_det as $det) {
                                  
                                  $debito=0;
                                  $credito=round($det->ctp_monto,$dec);
                                  $saldo=round($saldo,$dec)-round($credito,$dec);
                                  switch ($det->ctp_forma_pago) {
                                    case '1': $fp="TARJETA DE CREDITO"; break;
                                    case '2': $fp="TARJETA DE DEBITO"; break;
                                    case '3': $fp="CHEQUE"; break;
                                    case '4': $fp="EFECTIVO"; break;
                                    case '5': $fp="CERTIFICADOS"; break;
                                    case '6': $fp="TRANSFERENCIA"; break;
                                    case '7': $fp="RETENCION"; break;
                                    case '8': $fp="NOTA CREDITO"; break;
                                    case '9': $fp="CREDITO"; break;
                                    case '10': $fp="CRUCE DE CUENTAS"; break;
                                  }

                                ?>
                                    <tr>
                                        <td><?php echo $det->ctp_fecha_pago?></td>
                                        <td><?php echo $fp?></td>
                                        <td><?php echo $det->num_documento?></td>
                                        <td><?php echo $det->ctp_concepto?></td>
                                        <td style="text-align: right;"><?php echo number_format($debito,$dec)?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($credito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                                        <td style="text-align: right;"><?php echo number_format(0,$dec)?></td>
                                    </tr>
                                <?php
                                $t_debito+=$debito;
                                $t_credito+=$credito;
                                }
                                ?>    
                              </tbody>        
                              <tfoot>
                                <tr>
                                        <th colspan="3"></th>
                                        <th>Total</th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_debito,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_credito,$dec))?></th>
                                        <th id="saldo" style="text-align: right;"><?php echo str_replace(',','',number_format($saldo,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($sal_ven,$dec))?></th>
                                    </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </td>
                    </tr> 
                    <tr>
                       <td class="col-sm-12" colspan="2">
                        <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading"><label>Registrar Pagos</label></div>
                            <table class="table table-bordered table-striped" id="tbl_detalle">
                              <thead>
                                <tr>
                                  <th>Fecha</th>
                                  <th>Forma de Pago</th>
                                  <th>Documento</th>
                                  <th>Concepto</th>
                                  <th>Valor $</th>
                                </tr>
                              </thead>

                              <tbody>
                                    <tr>
                                        <td>
                                          <div class="form-group <?php if(form_error('ctp_fecha_pago')!=''){ echo 'has-error';}?> ">
                                          <input type="date" class="form-control" name="ctp_fecha_pago" id="ctp_fecha_pago" value="<?php if(validation_errors()!=''){ echo set_value('ctp_fecha_pago');}else{ echo $ctasxpag->ctp_fecha_pago;}?>">
                                          <?php echo form_error("ctp_fecha_pago","<span class='help-block'>","</span>");?>
                                          </div>
                                        </td>
                                        <td>
                                          <div class="form-group <?php if(form_error('ctp_forma_pago')!=''){ echo 'has-error';}?> ">
                                            <?php
                                                if(validation_errors()){
                                                      $tipo=$ctasxpag->ctp_forma_pago;
                                                    }else{
                                                      $tipo=set_value('ctp_forma_pago');
                                                }
                                             ?>   
                                            <select name="ctp_forma_pago" id="ctp_forma_pago" class="form-control">
                                              <option value="0">SELECCIONE</option>
                                              <option value="1">TARJETA DE CREDITO</option>
                                              <option value="2">TARJETA DE DEBITO</option>
                                              <option value="3">CHEQUE</option>
                                              <option value="4">EFECTIVO</option>
                                              <option value="5">CERTIFICADOS</option>
                                              <option value="6">TRANSFERENCIA</option>
                                              <option value="7">RETENCION</option>
                                              <option value="8">NOTA CREDITO</option>
                                            </select>
                                          <script type="text/javascript">
                                              var tipo='<?php echo $tipo?>';
                                              ctp_forma_pago.value=tipo;
                                          </script>
                                          <?php echo form_error("ctp_forma_pago","<span class='help-block'>","</span>");?>
                                          </div>
                                        </td>
                                        <td>
                                          <div class="form-group <?php if(form_error('num_documento')!=''){ echo 'has-error';}?> ">
                                          <input type="text" class="form-control" name="num_documento" id="num_documento" value="<?php if(validation_errors()!=''){ echo set_value('num_documento');}else{ echo $ctasxpag->num_documento;}?>">
                                          <?php echo form_error("num_documento","<span class='help-block'>","</span>");?>
                                          </div>
                                        </td>
                                        <td>
                                          <div class="form-group <?php if(form_error('ctp_concepto')!=''){ echo 'has-error';}?> ">
                                          <input type="text" class="form-control" name="ctp_concepto" id="ctp_concepto" value="<?php if(validation_errors()!=''){ echo set_value('ctp_concepto');}else{ echo $ctasxpag->ctp_concepto;}?>">
                                          <?php echo form_error("ctp_concepto","<span class='help-block'>","</span>");?>
                                          </div>
                                        </td>
                                        <td>
                                          <div class="form-group <?php if(form_error('ctp_monto')!=''){ echo 'has-error';}?> ">
                                          <input type="text" class="form-control decimal" name="ctp_monto" id="ctp_monto" value="<?php if(validation_errors()!=''){ echo set_value('ctp_monto');}else{ echo $ctasxpag->ctp_monto;}?>" onkeyup='validar_decimal(this)' style='text-align:right' onchange="validar(this)">
                                          <?php echo form_error("ctp_monto","<span class='help-block'>","</span>");?>
                                          </div>
                                        </td>
                                    </tr>
                              </tbody>        
                            </table>
                          </div>
                        </div>
                      </td>
                    </tr> 
                  </table>
              </div>
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save(1)">Guardar</button>
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
    <script>

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';
      

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            

          function traer_factura() {
            // alert(base_url+"ctasxpagar/traer_factura/"+$('#fac_numero').val().trim()+"/"+cli_id.value+"/"+emp_id.value);
              $.ajax({
                  beforeSend: function () {
                      if ($('#fac_numero').val().length == 0) {
                            alert('Ingrese una factura');
                            return false;
                      }
                    },
                  url: base_url+"ctasxpagar/traer_factura/"+$('#fac_numero').val().trim()+"/"+cli_id.value+"/"+emp_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) {
                    if (dt.length!= 0) {
                            $('#fac_numero').val(dt.fac_numero);
                            $('#fac_total_valor').val(dt.fac_total_valor);
                            $('#fac_id').val(dt.fac_id);
                    }else{
                        alert('Numero no existe en Registro de Facturas');
                        $('#fac_numero').val('');
                        $('#fac_total_valor').val('');
                        $('#reg_id').val('');
                    }
                  },
                  error : function(xhr, status) {
                     alert('Numero no existe en Registro de Facturas');
                        $('#fac_numero').val('');
                        $('#fac_total_valor').val('');
                        $('#reg_id').val('');
                  }
                })
            } 


            function habilitar(op) {
              var action1="<?php echo $action;?>";
              var action2="<?php echo $action2;?>";
              if($('#cruce').prop('checked')==true){
                op=1;
              }else{
                op=2;
              }
                if (op==1) {
                    $('.cruce').attr('style','');
                    $('#cruce').attr('checked',true);
                    $('#frm_save').attr('action', action2);
                } else {
                    $('.cruce').attr('style','display:none');
                    $('#cruce').attr('checked',false);
                    $('#frm_save').attr('action', action1);
                    $('#fac_numero').val('');
                    $('#fac_total_valor').val('');
                    $('#fac_id').val('');
                    $('#valor_cruce').val('');
                }
            }  

            
            function validar(obj){
              if (parseFloat($('#saldo').html())<parseFloat($(obj).val())) {
                alert("No se puede registrar el pago porque \nel valor es mayor que el saldo");
                $(obj).val('');
                $(obj).focus();
              }
            }
             

            function save(opc) {
                        if (cli_codigo.value.length == 0) {
                            $("#cli_codigo").css({borderColor: "red"});
                            $("#cli_codigo").focus();
                            return false;
                        } else if (cli_raz_social.value.length == 0) {
                            $("#cli_raz_social").css({borderColor: "red"});
                            $("#cli_raz_social").focus();
                            return false;
                        } else if (ctp_fecha_pago.value.length == 0) {
                            $("#ctp_fecha_pago").css({borderColor: "red"});
                            $("#ctp_fecha_pago").focus();
                            return false;
                        }

                        if(opc==1){
                          if ($('#ctp_forma_pago').val() == "0") {
                              $("#ctp_forma_pago").css({borderColor: "red"});
                              $("#ctp_forma_pago").focus();
                              return false;
                          } else if (ctp_concepto.value.length == 0) {
                              $("#ctp_concepto").css({borderColor: "red"});
                              $("#ctp_concepto").focus();
                              return false;
                          } else if (ctp_monto.value.length == 0 || parseFloat($('#ctp_monto').val())==0) {
                              $("#ctp_monto").css({borderColor: "red"});
                              $("#ctp_monto").focus();
                              return false;
                          }
                        }else{
                          if ($('#fac_numero').val() == "") {
                              $("#fac_numero").css({borderColor: "red"});
                              $("#fac_numero").focus();
                              return false;
                          } else if (valor_cruce.value.length == 0 || parseFloat($('#valor_cruce').val())==0) {
                              $("#valor_cruce").css({borderColor: "red"});
                              $("#valor_cruce").focus();
                              return false;
                          }
                        }    
                        
                     $('#frm_save').submit();   
                 
            }
    </script>

 <!-- ////modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Contro de Cobros</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
              <thead>
                  <th>Seleccione</th>
                  <th>Fecha</th>
                  <th>Numero</th>
                  <th>Valor</th>
              </thead>
              <tbody id="det_ventas"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
