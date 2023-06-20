
<section class="content-header">
  <h1>
    Seguimiento de Ordenes de Compra
  </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $dec = $dec->con_valor;
      $dcc = $dcc->con_valor;
      if ($orden->orc_factura!='' ) {
       $dn=explode("-",$orden->orc_factura);
       $read='readonly';
       $disab='disabled';
       $style1='display:none';
       $style2='';
      }else{
        $dn[0]='';
        $dn[1]='';
        $dn[2]='';
        $read='';
        $disab='';
        $style1='';
        $style2='display:none';
      }
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
                <table class="table">
                  <tr>
                    <td>
                      <input type="hidden" name="txt" id="txt" value="<?php echo $txt?>">
                      <input type="hidden" name="fec1" id="fec1" value="<?php echo $fec1?>">
                      <input type="hidden" name="fec2" id="fec2" value="<?php echo $fec2?>">
                      <input type="hidden" name="estado" id="estado" value="<?php echo $est?>">
                    </td>
                  </tr>
                  <tr>
                    <td><label>No.Orden:</label></td>
                    <td><?php echo $orden->orc_codigo?></td>
                  </tr>
                  <tr>
                    <td><label>Fecha de Orden:</label></td>
                    <td><?php echo $orden->orc_fecha?></td>
                    <td><label>Fecha de Entrega:</label></td>
                    <td><?php echo $orden->orc_fecha_entrega?></td>
                    <td><label>Direccion de Entrega:</label></td>
                    <td><?php echo $orden->orc_direccion_entrega?></td>
                  </tr>
                  <tr>

                    <td><label>Condicion de Pago:</label></td>
                    <td><?php echo $orden->orc_condicion_pago?></td>
                    <td><label>Concepto:</label></td>
                    <td><?php echo $orden->orc_concepto;?></td> 
                    <td><label>Proveedor:</label></td>
                    <td><?php echo $orden->cli_raz_social;?>
                      <input type="hidden" name="cli_id" id="cli_id" value="<?php echo $orden->cli_id?>">
                    </td>  
                </tr>
                <tr>
                      <td><label>Tipo Documento:</label></td>
                      <td>
                        <div class="form-group ">
                          <select name="orc_tip_doc"  id="orc_tip_doc" class="form-control" onchange="doc_duplicado()" style="width: 200px;" <?php echo $disab?>>
                            <option value="0">SELECCIONE</option>
                              <?php
                            if(!empty($tipo_documentos)){
                              foreach ($tipo_documentos as $tp_dc) {
                            ?>
                            <option value="<?php echo $tp_dc->tdc_id?>"><?php echo $tp_dc->tdc_codigo .' - '. $tp_dc->tdc_descripcion?></option>
                            <?php
                              }
                            }

                            if(empty($orden->orc_tip_doc)){
                              $td=0;
                            }else{
                              $td=$orden->orc_tip_doc;
                            }
                            ?>
                          </select>
                          <script type="text/javascript">
                            
                            var tipodoc='<?php echo $orden->orc_tip_doc;?>';
                                    orc_tip_doc.value=tipodoc;
                          </script>
                          <?php echo form_error("orc_tip_doc","<span class='help-block'>","</span>");?>
                        </div>
                      </td>    
                      <td><label>Numero Documento:</label></td>
                      
                      <td width="240px">
                        <div class="form-group <?php if(form_error('orc_factura')!=''){ echo 'has-error';}?>">
                          
                        <div class="row">
                          <div class="col-xs-3" style="margin-left:0px;margin-right: 0px;padding-right: 0px;padding-left: 0px; width:50px;" >
                            <input type="text" class="form-control" id="orc_factura0" name="orc_factura0" maxlength="3" value="<?php echo $dn[0] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" <?php echo $read ?> />
                          </div>
                          <div class="col-xs-1" style="margin-left:0px;margin-right: 0px;padding-right: 0px;padding-left: 0px; text-align: center;"> - </div> 
                          <div class="col-xs-3" style="margin-left:0px;margin-right: 0px;padding-right: 0px;padding-left: 0px; ">
                            <input type="text"  class="form-control" id="orc_factura1" name="orc_factura1" maxlength="3" value="<?php echo $dn[1] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" <?php echo $read ?>/>
                          </div>
                          <div class="col-xs-1" style="margin-left:0px;margin-right: 0px;padding-right: 0px;padding-left: 0px; text-align: center;"> - </div> 
                          <div class="col-xs-4" style="margin-left:0px;margin-right: 0px;padding-right: 0px;padding-left: 0px;">
                            <input type="text"  class="form-control" id="orc_factura2" name="orc_factura2" maxlength="9" value="<?php echo $dn[2] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 1)" <?php echo $read ?> />
                          </div>
                      </div>  
                      <?php echo form_error("orc_factura","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                    <td>
                      <button type="button" class="btn btn-success" onclick="confirmar()" style="<?php echo $style1?>">Confirmar</button>
                    </td>
                </tr> 
              </table>
            </td>
          </tr>    
          <tr>
           <td class="col-sm-12">
            <div class="box-body">
              <div class="panel panel-default col-sm-12">

                <table class="table table-bordered table-striped" id="tbl_detalle">
                  <thead>
                    <tr>
                      <th style="width: 70px">Item</th>
                      <th>Codigo</th>
                      <th>Descripcion</th>
                      <th>Unidad</th>
                      <th style="width: 100px">Solicitado</th>
                      <th style="width: 100px">Entregado</th>
                      <th style="width: 100px">Saldo</th>
                      <th>Etiquetas</th>
                    </tr>
                  </thead>
                    
                  <tbody id='lista'>
                    <?php
                    $count_det=0;
                    if(!empty($detalle)){
                      $n=0;
                      foreach ($detalle as $det) {
                        $n++;
                        $cantidad=$det->orc_det_cant*1.10;
                        $saldo=round($cantidad,$dec)-round($det->unidad,$dec);  
                    ?>
                    <tr >
                      <td id="item<?php echo $n?>" align="center" lang="<?php echo $n?>">
                        <?php echo $n?>
                        <input type ="hidden"  id="pro_id<?php echo $n?>" name="pro_id<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo $det->mp_id?>"/>
                      </td>
                      <td id="pro_codigo<?php echo $n?>" name="pro_codigo<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_c?></td>
                      <td id="pro_descripcion<?php echo $n?>" name="pro_descripcion<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_d?></td>
                      <td id="pro_uni<?php echo $n?>" name="pro_uni<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_q?></td>

                      <td align="right"><?php echo str_replace(',','',number_format($cantidad,$dec))?></td>
                      <td align="right"><?php echo str_replace(',','',number_format($det->unidad,$dec))?></td>
                      <td align="right"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
                      <td align="center">
                        <?php
                        if(round($saldo,$dec)>0){
                        ?>
                        <a href="#" class="btn btn-danger" onclick="envio('<?php echo $det->orc_det_id?>',1)" title='Ingresar Etiquetas' style="<?php echo $style2?>"> <span class="fa fa-barcode"></span></a>
                        <?php
                        }
                        ?>
                      </td>
                    </tr>
                    <?php    
                      }
                      $count_det=$n;
                    }
                    ?>
                  </tbody>
                  
                </table>
              </div>
            </div>
          </td>
        </tr> 


      </table>
    </div>
    <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $count_det?>">
    <input type="hidden" class="form-control" id="orc_id" name="orc_id" value="<?php echo $orden->orc_id?>">
    <div class="box-footer">
      <button type="button" class="btn btn-primary" onclick="envio('<?php echo $orden->orc_id?>',2)">Guardar</button>
      <a href="#" class="btn btn-default" onclick="envio('<?php echo $orden->orc_id?>',2)">Cancelar</a>
    </div>

  </form>
</div>

</div>

  <script>
      var dec='<?php echo $dec?>';
      var dcc='<?php echo $dcc?>';
      var base_url='<?php echo base_url();?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }

      function envio(id,opc){
        if(opc==0){
          url='<?php echo $action?>';
        }else if(opc==1){
          url="<?php echo base_url();?>orden_compra_seguimiento/etiquetas/"+id+"/<?php echo $opc_id?>";
        }else if(opc==2){
          url="<?php echo base_url();?>orden_compra_seguimiento/<?php echo $opc_id?>";
        }
        
        $('#frm_save').attr('action',url);
        $('#frm_save').submit();
      }

      function completar_ceros(obj, v) {
        o = obj.value;
        val = parseFloat(o);
        if (v == 0) {
          if (val == 0) {
                      //alert("Numero incorrecto");
                      swal("", "Número incorrecto", "info"); 
                      $(obj).val('');
                    } else if (val > 0 && val < 10) {
                      txt = '00';
                    } else if (val >= 10 && val < 100) {
                      txt = '0';
                    } else if (val >= 100 && val < 1000) {
                      txt = '';
                    }
                    $(obj).val(txt + val);
                  } else {
                    if (val > 0 && val < 10) {
                      txt = '00000000';
                    } else if (val >= 10 && val < 100) {
                      txt = '0000000';
                    } else if (val >= 100 && val < 1000) {
                      txt = '000000';
                    } else if (val >= 1000 && val < 10000) {
                      txt = '00000';
                    } else if (val >= 10000 && val < 100000) {
                      txt = '0000';
                    } else if (val >= 100000 && val < 1000000) {
                      txt = '000';
                    } else if (val >= 1000000 && val < 10000000) {
                      txt = '00';
                    } else if (val >= 10000000 && val < 100000000) {
                      txt = '0';
                    } else if (val >= 100000000 && val < 1000000000) {
                      txt = '';
                    }
                    $(obj).val(txt + val);

                    if (val == 0 || o.length == 0) {
                      //alert("Numero incorrecto");
                      swal("", "Número incorrecto", "info"); 
                      $(obj).val('');
                      return false;
                    }else{
                      num_doc = $('#orc_factura0').val()+'-'+$('#orc_factura1').val()+'-'+$('#orc_factura2').val();
                      $('#orc_factura').val(num_doc);

                    }
                  }
                  doc_duplicado()
                }

                function doc_duplicado(){
                  num_doc = $('#orc_factura0').val()+"-"+$('#orc_factura1').val()+"-"+$('#orc_factura2').val();
                  tip_doc = $('#orc_tip_doc').val();
                  if (num_doc.length = 17 && cli_id.value.length > 0 && tip_doc != 0) {
                    $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"orden_compra_seguimiento/doc_duplicado/"+cli_id.value+"/"+num_doc+"/"+tip_doc,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                        if(dt!=""){
                          swal("", "EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Facturas", "info");  
                          $('#orc_factura0').val('');
                          $('#orc_factura1').val('');
                          $('#orc_factura2').val('');
                        } 
                      }
                    });
                  }          
                }      

                function confirmar(){
                  if ($('#orc_tip_doc').val()== '0') {
                    $("#orc_tip_doc").css({borderColor: "red"});
                    $("#orc_tip_doc").focus();
                    return false;
                  }else if ($('#orc_factura0').val().length== '0') {
                    $("#orc_factura0").css({borderColor: "red"});
                    $("#orc_factura0").focus();
                    return false;
                  }else if ($('#orc_factura1').val().length== '0') {
                    $("#orc_factura1").css({borderColor: "red"});
                    $("#orc_factura1").focus();
                    return false;
                  }else if ($('#orc_factura2').val().length== '0') {
                    $("#orc_factura2").css({borderColor: "red"});
                    $("#orc_factura2").focus();
                    return false;
                  }
                  $('#frm_save').submit();

                }

              </script>
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
      margin-bottom: 3px !important;
      margin-top: 3px !important;
      padding-bottom: 3px !important;
      padding-top: 3px !important;
    }

    .totales{
      font-size: 16px !important;
      text-align: right !important;
    }
  </style>
  <!-- /.row -->
</section>
