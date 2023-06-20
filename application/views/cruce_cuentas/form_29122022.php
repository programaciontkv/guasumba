
<section class="content-header">
      <h1>
        Cruce de Cuentas
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-6">
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
                <table class="table" border="0">
                  <tr>
                    <td class="col-sm-6">
                      <div class="box-body" class="col-sm-12">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading" style="background-color:#d9f6f7"><label>Datos Generales</label></div>
                              <table class="table">
                                <tr>  
                                  <td><label>Fecha:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('fecha')!=''){ echo 'has-error';}?> ">
                                      <input type="date" class="form-control" name="fecha" id="cfecha" value="<?php if(validation_errors()!=''){ echo set_value('fecha');}else{ echo date('Y-m-d');}?>">
                                          <?php echo form_error("fecha","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Identificacion Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_ced_ruc')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_ced_ruc" id="cli_ced_ruc" value="<?php if(validation_errors()!=''){ echo set_value('cli_ced_ruc');}else{ echo $reg_factura->cli_ced_ruc;}?>" readonly>
                                          <?php echo form_error("cli_ced_ruc","<span class='help-block'>","</span>");?>
                                    </div>
                                    <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $reg_factura->cli_id;}?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Nombre Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social');}else{ echo $reg_factura->cli_raz_social;}?>" readonly>
                                          <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>    
                                </tr>
                              </table>
                          </div>
                      </div>    
                    </td> 
                  </tr>
                  <tr>
                    <td class="col-sm-6" >
                      <div class="box-body" class="col-sm-12">
                        <div class="panel panel-default col-sm-12">
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                  <th>Factura por Pagar</th>
                                  <th>Total</th>
                                  <th>Disponible</th>
                                  <th>Saldo</th>
                                </tr>
                              </thead>

                              <tbody>
                                <tr>
                                  <td>
                                     <input class="form-control" type="text" readonly name="reg_numero" id="reg_numero" value="<?php echo $reg_factura->reg_num_documento?>">
                                    <input type="text" hidden name="reg_id" id="reg_id" value="<?php echo $reg_factura->reg_id?>"/>
                                  </td>
                                  <td style="text-align: right;"><?php echo str_replace(',','',number_format($reg_factura->reg_total,$dec))?></td>
                                  <td style="text-align: right;" id="disponible"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                                  <td style="text-align: right;" id="saldo"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                                </tr>
                              </tbody>        
                            </table>
                          </div>
                        </div>
                    </td>
                  </tr> 
                  <tr>
                    <td class="col-sm-6" >
                      <div class="box-body" class="col-sm-12">
                        <div class="panel panel-default col-sm-12">
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                  <th>Facturas por Cobrar</th>
                                  <th>Total</th>
                                  <th>Saldo</th>
                                  <th>Abono</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                
                                $t_valor=0;
                                $t_cobrado=0;
                                $t_saldo=0;
                                $n=0;
                                $fsaldo=0;
                                foreach ($facturas as $factura) {

                                  $n++;
                                  $valor=round($factura->fac_total_valor,$dec);
                                  $cobrado=round($factura->pago,$dec);
                                  $fsaldo=$valor-$cobrado;
                                  if($fsaldo>0){
                                ?>
                                    <tr>
                                       
                                        <td>
                                          <input class="form-control" type="text" readonly name="fac_num<?php echo $n?>" id="fac_num<?php echo $n?>" value=" <?php echo $factura->fac_numero?>">
                                        </td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($valor,$dec))?></td>
                                        <td style="text-align: right;" id="fsaldo<?php echo $n?>"><?php echo str_replace(',','',number_format($fsaldo,$dec))?></td>
                                        <td ><input type="text" class="form-control decimal pago" style="text-align: right;" id="abono<?php echo $n?>" name="abono<?php echo $n?>" value="<?php echo str_replace(',','',number_format(0,$dec))?>" onchange="total(this)" lang="<?php echo $n?>">
                                          <input type="text" hidden name="fac_id<?php echo $n?>" id="fac_id<?php echo $n?>" value="<?php echo $factura->fac_id?>"/>
                                        </td>
                                        
                                    </tr>
                                <?php
                                    $t_valor+=$valor;
                                    $t_saldo+=round($fsaldo,$dec);
                                  }
                                }
                                ?>
                                    
                              </tbody>        
                              <tfoot>
                                <tr style="display: none;">
                                  <td>
                                    <input type="text" name="count" id="count" value="<?php echo $n?>"/>
                                  </td>
                                </tr>  
                                <tr>
                                        
                                        <th>Total Cruce Cuentas</th>
                                        <th colspan="2"></th>
                                        <th style="text-align: right;" id="t_abono" name="t_abono"><?php echo str_replace(',','',number_format(0,$dec))?></th>
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
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
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
 
      function round(value, decimals) {
        return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
      }

     
      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            
      function total(obj) {
            tot = 0;
            sd = 0;
            $('.pago').each(function () {
                f = this.lang;
                sald = $('#fsaldo' + f).html();
                if (parseFloat(sald) < parseFloat($(this).val())) {
                    alert('El abono es mayor al saldo');
                    $(this).val('0');
                } else {
                    tot = tot + round($(this).val(),dec);
                }
            });
            $('#t_abono').html(tot.toFixed(dec));
            
            if(tot>parseFloat($('#disponible').html())){
              alert("El Total Cruce es mayor al valor Disponible");
              $(obj).val('0');
              total();
            }else{
              sd=round($('#disponible').html(),dec)-round(tot,dec);
              $('#saldo').html(sd.toFixed(dec))
            }
      }


          
            function save() {
              var v=0;
              $('.pago').each(function () {
                f = this.lang;
                if($(this).val().length==0){
                  $("#abono"+f).css({borderColor: "red"});
                  $("#abono"+f).focus();
                  v=1;
                }
              });


              if(parseFloat($('#disponible').val())<parseFloat($('#t_abono').html())){
                alert("El Total de Cruce Cuentas es mayor que el valor Disponible del Documento");
                 v=1;
              }
              else if(parseFloat($('#t_abono').html())==0){
                alert("Ingrese al menos un abono");
                 v=1;
              }
              if(v==0){
                $('#frm_save').submit();
              }else{
                return false;
              }
  
            }

            
         
    </script>

  

  


