
<section class="content-header">
      <h1>
        Control de Cobros <?php echo $titulo?>
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
                    <td class="col-sm-12">
                      <div class="box-body" class="col-sm-12">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading"><label>Datos Generales</label></div>
                              <table class="table">
                                <tr>
                                  <td><label>Identificacion Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_ced_ruc')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_ced_ruc" id="cli_ced_ruc" value="<?php if(validation_errors()!=''){ echo set_value('cli_ced_ruc');}else{ echo $cheque->cli_ced_ruc;}?>" readonly>
                                          <?php echo form_error("cli_ced_ruc","<span class='help-block'>","</span>");?>
                                    </div>
                                    <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $cheque->cli_id;}?>">
                                  </td>
                                  <td><label>Monto $:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('chq_monto')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="chq_monto" id="chq_monto" value="<?php if(validation_errors()!=''){ echo set_value('chq_monto');}else{ echo str_replace(',','',number_format($cheque->chq_monto,$dec));}?>" readonly>
                                          <?php echo form_error("chq_monto","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Nombre Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social');}else{ echo $cheque->cli_raz_social;}?>" readonly>
                                          <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>    
                                  <td><label>Credito $:</label></td>
                                  <td>
                                    <?php 
                                    $chq_saldo=round($cheque->chq_monto,$dec)-round($cheque->chq_cobro,$dec);
                                    ?>
                                    <div class="form-group <?php if(form_error('chq_cobro')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="chq_cobro" id="chq_cobro" value="<?php if(validation_errors()!=''){ echo set_value('chq_cobro');}else{ echo str_replace(',','',number_format($chq_saldo,$dec));}?>" readonly>
                                          <?php echo form_error("chq_cobro","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Concepto:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('chq_concepto')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="chq_concepto" id="chq_concepto" value="<?php if(validation_errors()!=''){ echo set_value('chq_concepto');}else{ echo $cheque->chq_concepto;}?>" readonly>
                                          <?php echo form_error("chq_concepto","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>    
                                  <td><label>Fecha:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('chq_fecha')!=''){ echo 'has-error';}?> ">
                                      <input type="date" class="form-control" name="chq_fecha" id="chq_fecha" value="<?php if(validation_errors()!=''){ echo set_value('chq_fecha');}else{ echo date('Y-m-d');}?>">
                                          <?php echo form_error("chq_fecha","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                              </table>
                          </div>
                      </div>    
                    </td> 
                  </tr>
                       <td class="col-sm-12" >
                        <div class="box-body" class="col-sm-12">
                          <div class="panel panel-default col-sm-12">
                            <table class="table table-bordered table-striped" id="tbl_detalle">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Fecha</th>
                                  <th>Factura</th>
                                  <th>Fecha Vencimiento</th>
                                  <th>Valor Total</th>
                                  <th>Cobrado</th>
                                  <th>Saldo</th>
                                  <th>Cobro</th>
                                </tr>
                              </thead>

                              <tbody>
                                <?php
                                
                                $t_valor=0;
                                $t_cobrado=0;
                                $t_saldo=0;
                                $n=0;
                                foreach ($facturas as $factura) {
                                  $n++;
                                  $valor=round($factura->fac_total_valor,$dec);
                                  $cobrado=round($factura->pago,$dec);
                                  $saldo=$valor-$cobrado;
                                  if($saldo>0){
                                ?>
                                    <tr>
                                        <td><?php echo $n?></td>
                                        <td><?php echo $factura->fac_fecha_emision?></td>
                                        <td><?php echo $factura->fac_numero?></td>
                                        <td><?php echo $factura->pag_fecha_v?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($valor,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($cobrado,$dec))?></td>
                                        <td style="text-align: right;" id="saldo<?php echo $n?>"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                                        <td style="text-align: right;">
                                          <input type="text" style="text-align: right;" name="cobro<?php echo $n?>" id="cobro<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo number_format(0,$dec)?>" class="decimal cobro" onchange='total()'/>
                                          <input type="text" hidden name="fac_id<?php echo $n?>" id="fac_id<?php echo $n?>" value="<?php echo $factura->fac_id?>"/>
                                        </td>
                                    </tr>
                                <?php
                                    $t_valor+=$valor;
                                    $t_cobrado+=$cobrado;
                                    $t_saldo+=round($saldo,$dec);
                                  }
                                }
                                ?>
                                    
                              </tbody>        
                              <tfoot>
                                <tr style="display: none;">
                                  <td>
                                    <input type="text" name="count" id="count" value="<?php echo $n?>"/>
                                    <input type="text" name="chq_id" id="chq_id" value="<?php echo $cheque->chq_id?>"/>
                                    <input type="text" name="chq_tipo_doc" id="chq_tipo_doc" value="<?php echo $cheque->chq_tipo_doc?>"/>
                                    <input type="text" name="chq_numero" id="chq_numero" value="<?php echo $cheque->chq_numero?>"/>
                                  </td>
                                </tr>  
                                <tr>
                                        <th colspan="3"></th>
                                        <th>Total</th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_valor,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_cobrado,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_saldo,$dec))?></th>
                                        <th style="text-align: right;" id="t_cobro"></th>
                                    </tr>
                              </tfoot>
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
            
      function total() {
            tot = 0;
            $('.cobro').each(function () {
                f = this.lang;
                sald = $('#saldo' + f).html();
                if (parseFloat(sald) < parseFloat($(this).val())) {
                    alert('El cobro es mayor al saldo');
                    $(this).val('0');
                } else {
                    tot = tot + parseFloat($(this).val());
                }
            });
            $('#t_cobro').html(tot.toFixed(dec));
      }


          
            function save(opc) {

              $('.cobro').each(function () {
                f = this.lang;
                sald = $('#saldo' + f).html();
                if ($(this).val().length==0) {
                  $("#cli_codigo").css({borderColor: "red"});
                  $("#cli_codigo").focus();
                  return false;
                } 
              });

              ;
              if(parseFloat($('#chq_cobro').val())<parseFloat($('#t_cobro').html())){
                alert("El Total de Cobro es mayor que el Credito del Documento");
                return false;
              }
                        
              $('#frm_save').submit();   
            }
    </script>

