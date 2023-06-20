<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Pedidos de Bodega <?php echo $titulo?>
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
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
                              <td><label>Fecha de Transferencia:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('mov_fecha_trans')!=''){ echo 'has-error';}?> ">
                                <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $movimiento->emp_id;?>">
                                <input type="hidden" name="emi_id" id="emi_id" value="<?php echo $movimiento->emi_id;?>">
                                <input type="text" class="form-control" name="mov_fecha_trans" id="mov_fecha_trans" value="<?php if(validation_errors()!=''){ echo set_value('mov_fecha_trans');}else{ echo $movimiento->mov_fecha_trans;}?>">
                                  <?php echo form_error("mov_fecha_trans","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha de Pedido:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('ped_fecha')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="ped_fecha" id="ped_fecha" value="<?php if(validation_errors()!=''){ echo set_value('ped_fecha');}else{ echo $movimiento->ped_fecha;}?>" readonly>
                                  <?php echo form_error("ped_fecha","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              
                          </tr>
                          <tr>
                              <td><label>Transaccion:</label></td>
                              <td>
                                <div class="form-group ">
                                  <select name="trs_id"  id="trs_id" class="form-control" disabled >
                                    <option value="">SELECCIONE</option>
                                     <?php
                                    if(!empty($transacciones)){
                                      foreach ($transacciones as $transaccion) {
                                    ?>
                                    <option value="<?php echo $transaccion->trs_id?>"><?php echo $transaccion->trs_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var trans='<?php echo $movimiento->trs_id?>';
                                    trs_id.value=trans;
                                  </script>
                                </div>
                              </td> 
                              <td><label>Origen:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('emi_nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="emi_nombre" id="emi_nombre" value="<?php if(validation_errors()!=''){ echo set_value('emi_nombre');}else{ echo $movimiento->emi_nombre;}?>" readonly>
                                <input type ="hidden" id="cli_origen" name="cli_origen"  value="<?php echo $movimiento->cli_origen?>"/>
                                  <?php echo form_error("emi_nombre","<span class='help-block'>","</span>");?>

                                </div>
                              </td>
                              <td><label>Destino:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('cli_nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="cli_nombre" id="cli_nombre" value="<?php if(validation_errors()!=''){ echo set_value('cli_nombre');}else{ echo $movimiento->cli_nombre;}?>" readonly>
                                  <?php echo form_error("cli_nombre","<span class='help-block'>","</span>");?>
                                  <input type ="hidden" id="cli_id" name="cli_id" value="<?php echo $movimiento->cli_id?>"/>
                                  <input type ="hidden" id="emi_destino" name="emi_destino"   value="<?php echo $movimiento->emi_destino?>"/></td>
                                </div>
                              </td>   
                            </table>
                          </tr>    
                    </tr>
                    <tr>
                       <td class="col-sm-12">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Pedido</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Inv.Origen</th>
                                <th>Inv.Destino</th>
                                <th>Solicitado</th>
                                <th>Entregado</th>
                                <th>Saldo</th>
                                <th>Cantidad</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if(!empty($cns_det)){
                                $n=0;
                                foreach ($cns_det as $rst_det) {
                                  $n++;
                              ?>
                                    <tr>
                                        <td align="center">
                                          <input type ="hidden"  id="ped_id<?php echo $n?>" name="ped_id<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo $rst_det->ped_id?>"/>
                                          <input type ="hidden"  id="pro_id<?php echo $n?>" name="pro_id<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo $rst_det->pro_id?>"/>
                                        
                                            <input style="text-align:left " type="text" style="width:  300px;" class="form-control" id="ped_num_registro<?php echo $n?>" name="ped_num_registro<?php echo $n?>"  lang="<?php echo $n?>" readonly value="<?php echo $rst_det->ped_num_registro?>"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type="text" style="width:  300px;" class="form-control" id="pro_codigo<?php echo $n?>" name="pro_codigo<?php echo $n?>"  lang="<?php echo $n?>" readonly value="<?php echo $rst_det->pro_codigo?>"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_descripcion<?php echo $n?>" name="pro_descripcion<?php echo $n?>" lang="<?php echo $n?>" readonly value="<?php echo $rst_det->pro_descripcion?>"  />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="inventario<?php echo $n?>" name="inventario<?php echo $n?>" lang="<?php echo $n?>" readonly class="form-control inv<?php echo $rst_det->pro_id ?>" value="<?php echo str_replace(',','',number_format($rst_det->inventario,$dcc))?>"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="inv_dest<?php echo $n?>" name="inv_dest<?php echo $n?>" lang="<?php echo $n?>" readonly class="form-control" value="<?php echo str_replace(',','',number_format($rst_det->inv_dest,$dcc))?>"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="solicitado<?php echo $n?>" name="solicitado<?php echo $n?>" lang="<?php echo $n?>" class="form-control decimal" readonly value="<?php echo str_replace(',','',number_format($rst_det->solicitado,$dcc))?>"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="entregado<?php echo $n?>" name="entregado<?php echo $n?>" lang="<?php echo $n?>" class="form-control decimal" readonly value="<?php echo str_replace(',','',number_format($rst_det->entregado,$dcc))?>"/>
                                        </td>
                                        <td >
                                          <input type ="text" size="7" style="text-align:right" id="saldo<?php echo $n?>" name="saldo<?php echo $n?>" lang="<?php echo $n?>"  class="form-control decimal" value="<?php echo str_replace(',','',number_format($rst_det->cantidad,$dcc))?>" readonly/>
                                        </td>
                                        <td >
                                          <input type ="text" size="7" style="text-align:right" id="mov_cantidad<?php echo $n?>" name="mov_cantidad<?php echo $n?>" lang="<?php echo $n?>" onchange="clonar(this.lang)" class="form-control decimal cnt<?php echo $rst_det->pro_id ?>" value="0" />
                                        </td>
                                        
                                    </tr>
                                    

                                <?php
                                  }
                                } 
                                ?>
                                </tbody>
                                <tbody id="lista2" hidden>

                                </tbody>
                            
                          </table>

                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $n?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar?>" class="btn btn-default">Cancelar</a>
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

      
            function total() {
                var tr = $('#lista').find("tr:last");
                a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                sum = 0;
                su = 0;
                st = 0;
                while (n < i) {
                    n++;
                  if($('#mov_cantidad'+n).val()!=null){  
                    if ($('#mov_cantidad' + n).val().length == 0) {
                        can = 0;
                    } else {
                        can = $('#mov_cantidad' + n).val();
                    }
                    if ($('#mov_cost_unit' + n).val().length == 0) {
                        u = 0;
                    } else {
                        u = $('#mov_cost_unit' + n).val();
                    }
                    if ($('#mov_cost_tot' + n).val().length == 0) {
                        t = 0;
                    } else {
                        t = $('#mov_cost_tot' + n).val();
                    }
                    sum = sum + round(can,dcc);
                    su = su + round(u,dec);
                    st = st + round(t,dec);
                  }
                }

                $('#total').html(sum.toFixed(dcc));
                $('#tu').html(su.toFixed(dec));
                $('#tt').html(st.toFixed(dec));
            } 
         
            function validar_inventario(l,j) {
                        
              //incentario
              if (parseFloat($('#inv_inventario'+j).html()) < parseFloat($('#inv_cantidad'+j).html())) {
                alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                $('#mov_cantidad'+l).val('0');
                $('#mov_cantidad'+l).focus();
                $('#mov_cantidad'+l).css({borderColor: "red"});
                clonar(l);
              }

              //saldo

              if (parseFloat($('#saldo'+l).val()) < parseFloat($('#mov_cantidad'+l).val())) {
                alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL SALDO');
                $('#mov_cantidad'+l).val('0');
                $('#mov_cantidad'+l).focus();
                $('#mov_cantidad'+l).css({borderColor: "red"});
                clonar(l);
              }
                          
            }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            } 

            function clonar(l) {
                d = 0;
                n = 0;
                ap = '"';
                var tr = $('#lista2').find("tr:last");
                var a = tr.find("td").attr("lang");
                if(a==null){
                    j=0;
                }else{
                    j=parseInt(a);
                }
                if (j > 0) {
                    while (n < j) {
                        n++;
                        if ($('#inv_id' + n).html() == $('#pro_id' + l).val()) {
                            d = 1;
                            cant=0;
                            $('.cnt'+$('#pro_id' + l).val()).each(function () {
                                cant+= round(this.value,dcc);
                            });
                            
                            $('#inv_cantidad' + n).html(cant.toFixed(dcc));
                            i=n;
                        }
                    }
                }
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td lang='"+i+"' name='inv_id"+i+"' id='inv_id"+i+"'>"+$('#pro_id' + l).val()+
                                        "</td>"+
                                        "<td id='inv_codigo"+i+"' lang='"+i+"'>"+$('#pro_codigo' + l).val()+"</td>"+
                                        "<td id='inv_inventario"+i+"' lang='"+i+"' align='right'>"+$('#inventario' + l).val()+"</td>"+
                                        "<td id='inv_cantidad"+i+"' lang='"+i+"' align='right'>"+$('#mov_cantidad' + l).val()+"</td>"+
                                        "<td>"
                                    "</tr>";
                    $('#lista2').append(fila);
                }

                validar_inventario(l,i);
               
            }


            function save(){
              if (mov_fecha_trans.value.length == 0) {
                $("#mov_fecha_trans").css({borderColor: "red"});
                $("#mov_fecha_trans").focus();
                return false;
              }else if (trs_id.value.length == 0) {
                $("#trs_id").css({borderColor: "red"});
                $("#trs_id").focus();
                return false;
              }else if (cli_nombre.value.length == 0) {
                $("#cli_nombre").css({borderColor: "red"});
                $("#cli_nombre").focus();
                return false;
              }else if (cli_id.value.length == 0) {
                $("#cli_nombre").css({borderColor: "red"});
                $("#cli_nombre").focus();
                return false;
              }else if (emi_nombre.value.length == 0) {
                $("#emi_nombre").css({borderColor: "red"});
                $("#emi_nombre").focus();
                return false;
              }else if (emi_id.value.length == 0) {
                $("#emi_nombre").css({borderColor: "red"});
                $("#emi_nombre").focus();
                return false;
              }
              var tr = $('#tbl_detalle').find("tbody tr:last");
                  a = tr.find("input").attr("lang");
                  i = parseInt(a);
                  n = 0;
                  while (n < i) {
                    n++;
                    if($('#mov_cantidad'+n).val()!=null || parseFloat($('#mov_cantidad'+n).val())!=0){
                      if($('#mov_cantidad'+n).val().length==0){
                        $("#mov_cantidad"+n).css({borderColor: "red"});
                        $("#mov_cantidad"+n).focus();
                        return false;
                      }
                    }
                  }  

              frm_save.submit();
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
