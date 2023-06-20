<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
     <!--  <h1>
        Guias de Remision 
      </h1> -->
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
          <div class="box box-primary" style="margin-left:-4.5%">
            <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body" >
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-12">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel panel-heading"><label>Datos Generales</label></div>
                        <table class="table">
                          <tr>
                               <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('gui_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="gui_fecha_emision" id="gui_fecha_emision" value="<?php if(validation_errors()){ echo set_value('gui_fecha_emision');}else{ echo $guia->gui_fecha_emision;}?>" readonly>
                                  <?php echo form_error("gui_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()){ echo  set_value('emp_id');}else{ echo $guia->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()){ echo  set_value('emi_id');}else{ echo $guia->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()){ echo  set_value('cja_id');}else{ echo $guia->cja_id;}?>">
                                <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="<?php if(validation_errors()){ echo  set_value('fac_id');}else{ echo $guia->fac_id;}?>">
                                </div>
                              </td>
                              <td hidden><label>Usuario</label></td>
                              <td hidden >
                                <div class="form-group ">
                                  <select name="vnd_id"  id="vnd_id" class="form-control">
                                    <option value="">SELECCIONE</option>
                                     <?php
                                    if(!empty($vendedores)){
                                      foreach ($vendedores as $vendedor) {
                                    ?>
                                    <option value="<?php echo $vendedor->vnd_id?>"><?php echo $vendedor->vnd_nombre?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var vnd='<?php echo $guia->vnd_id;?>';
                                    vnd_id.value=vnd;
                                  </script>
                                </div>
                              </td> 
                              <td><label>Fecha Inicio Traslado:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('gui_fecha_inicio')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="gui_fecha_inicio" id="gui_fecha_inicio" value="<?php if(validation_errors()){ echo  set_value('gui_fecha_inicio');}else{ echo $guia->gui_fecha_inicio;}?>" readonly>
                                  <?php echo form_error("gui_fecha_inicio","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Fin Traslado:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('gui_fecha_fin')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="gui_fecha_fin" id="gui_fecha_fin" value="<?php if(validation_errors()){ echo  set_value('gui_fecha_fin');}else{ echo $guia->gui_fecha_fin;}?>" onchange="validar_fecha()">
                                  <?php echo form_error("gui_fecha_fin","<span class='help-block'>","</span>");?>
                                </div>
                              </td>   
                          </tr>
                          <tr>
                            <td><label>Tipo Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_denominacion_comp')!=''){ echo 'has-error';}?> ">
                                  <select id="gui_denominacion_comp" name="gui_denominacion_comp" class="form-control" onchange="denominacion()">
                                    <option value='1'>FACTURA</option>
                                    <option value='0'>SIN FACTURA</option>
                                  </select>
                                  <script type="text/javascript">
                                    var deno='<?php echo $guia->gui_denominacion_comp?>';
                                    gui_denominacion_comp.value=deno;
                                  </script>
                                </div>
                              </td>
                          </tr>
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_num_comprobante')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_num_comprobante" id="gui_num_comprobante" value="<?php if(validation_errors()){ echo  set_value('gui_num_comprobante');}else{ echo $guia->gui_num_comprobante;}?>" onchange="num_factura(this)" maxlength="17">
                                  <?php echo form_error("gui_num_comprobante","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Autorizacion Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_aut_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_aut_comp" id="gui_aut_comp" value="<?php if(validation_errors()){ echo  set_value('gui_aut_comp');}else{ echo $guia->gui_aut_comp;}?>">
                                  <?php echo form_error("gui_aut_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_fecha_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="date" class="form-control" name="gui_fecha_comp" id="gui_fecha_comp" value="<?php if(validation_errors()){ echo  set_value('gui_fecha_comp');}else{ echo $guia->gui_fecha_comp;}?>" readonly>
                                  <?php echo form_error("gui_fecha_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()){ echo  set_value('identificacion');}else{ echo  $guia->gui_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()){ echo set_value('nombre');}else{ echo $guia->gui_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()){ echo  set_value('cli_id');}else{ echo $guia->cli_id;}?>" >
                              </td>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){echo set_value('telefono_cliente');}else{ echo $guia->cli_telefono;}?>" readonly>
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                          </tr>
                          <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()){ echo  set_value('email_cliente');}else{ echo $guia->cli_email;}?>" readonly>
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                              <td><label>Declaracion Aduanera No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_doc_aduanero')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_doc_aduanero" id="gui_doc_aduanero" value="<?php if(validation_errors()){ echo  set_value('gui_doc_aduanero');}else{ echo $guia->gui_doc_aduanero;}?>" >
                                  <?php echo form_error("gui_doc_aduanero","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                              <td><label>Cod. Establecimiento Destino:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_cod_establecimiento')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_cod_establecimiento" id="gui_cod_establecimiento" value="<?php if(validation_errors()){ echo  set_value('gui_cod_establecimiento');}else{ echo $guia->gui_cod_establecimiento;}?>" >
                                  <?php echo form_error("gui_cod_establecimiento","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                            <tr>
                              <td><label>Motivo de Traslado:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_motivo_traslado')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_motivo_traslado" id="gui_motivo_traslado" value="<?php if(validation_errors()){ echo  set_value('gui_motivo_traslado');}else{ echo $guia->gui_motivo_traslado;}?>" >
                                  <?php echo form_error("gui_motivo_traslado","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Punto de Partida:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_punto_partida')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_punto_partida" id="gui_punto_partida" value="<?php if(validation_errors()){ echo  set_value('gui_punto_partida');}else{ echo $guia->gui_punto_partida;}?>" >
                                  <?php echo form_error("gui_punto_partida","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Destino:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_destino')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="gui_destino" id="gui_destino" value="<?php if(validation_errors()){ echo  set_value('gui_destino');}else{ echo $guia->gui_destino;}?>">
                                  <?php echo form_error("gui_destino","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                            <tr>
                              <td><label>CI/RUC Transportista:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('gui_identificacion_transp')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="gui_identificacion_transp" id="gui_identificacion_transp" value="<?php if(validation_errors()){ echo  set_value('gui_identificacion_transp');}else{ echo $guia->gui_identificacion_transp;}?>" list="list_transportistas" onchange='traer_transportista(this)'>
                                  <?php echo form_error("gui_identificacion_transp","<span class='help-block'>","</span>");?>
                                  <input type="hidden" class="form-control" name="tra_id" id="tra_id" value="<?php if(validation_errors()){ echo  set_value('tra_id');}else{ echo $guia->tra_id;}?>" >
                                   <!--  <button type="button" class="btn btn-info btn-view" value="<?php echo base_url();?>guia_remision/n_trans/<?php echo $op_id?>" data-toggle="modal" title="Agregar proveedor" data-target="#trans" ><span class="fa fa-plus"> Agregar Transportista </span>
                                  </button> -->
                                  </div>
                                
                              </td> 
                              <td><label>Nombre Transportista:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('tra_razon_social')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="tra_razon_social" id="tra_razon_social" value="<?php if(validation_errors()){ echo  set_value('tra_razon_social');}else{ echo $guia->tra_razon_social;}?>">
                                  <?php echo form_error("tra_razon_social","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                              <td><label>Placa:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('tra_placa')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="tra_placa" id="tra_placa" value="<?php if(validation_errors()){ echo  set_value('tra_placa');}else{ echo $guia->tra_placa;}?>">
                                  <?php echo form_error("tra_placa","<span class='help-block'>","</span>");?>
                                  </div>
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
                                <th>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th>Solicitado</th>
                                <th>Entregado</th>
                                <th>Saldo</th>
                                <th>Cantidad</th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                              if($guia->fac_id==0){
                                $cnt_detalle=0;
                              ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"   maxlength="16" list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_referencia" name="pro_referencia"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="unidad" name="unidad"  value="" lang="1" readonly class="form-control" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidadf" name="cantidadf"  value="" lang="1" class="form-control decimal" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="entregado" name="entregado" value="" lang="1" class="form-control decimal" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="saldo" name="saldo"  value="" lang="1" class="form-control decimal" readonly/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" class="form-control decimal" />
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>
                                    </tr>
                              <?php 
                                }
                              ?>    
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        ?>
                                        <tr>
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidadf' . $n ?>" name="<?php echo 'cantidadf' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidadf, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'entregado' . $n ?>" name="<?php echo 'entregado' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->entregado, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'saldo' . $n ?>" name="<?php echo 'saldo' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->saldo, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="validar_cantfactura(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  /></td>
                                            <?php
                                            if($guia->gui_denominacion_comp==0){
                                            ?>
                                            <td onclick="elimina_fila_det(this)" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                        $cnt_detalle++;
                                    }
                                  }
                                ?>
                                </tbody>
                            <tfoot>
                                <tr>
                                    <td><label>Observaciones:</label></td>
                                </tr>
                                <tr>

                                    <td valign="top" rowspan="11" colspan="8">
                                      <textarea id="gui_observacion" name="gui_observacion" class="form-control"  onkeydown="return enter(event)" style="height:80px!important ;"><?php echo $guia->gui_observacion ?> </textarea>
                                    </td>    
                                </tr>

                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="gui_id" value="<?php echo $guia->gui_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer" style="margin-left:5%">
                <button type="button"  class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
    </datalist>
     <datalist id="list_transportistas">
      <?php 
        if(!empty($cns_transportistas)){
          foreach ($cns_transportistas as $rst_tra) {
      ?>
        <option value="<?php echo $rst_tra->tra_id?>"><?php echo $rst_tra->tra_identificacion .' '.$rst_tra->tra_razon_social?></option>
      <?php 
          }
        }
      ?>
    </datalist>
    <datalist id="productos">
      <?php 
        if(!empty($cns_productos)){
          foreach ($cns_productos as $rst_pro) {
      ?>
        <option value="<?php echo $rst_pro->mp_c?>"><?php echo $rst_pro->mp_c .' '.$rst_pro->mp_d?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    <!-- ////modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Facturas</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
              <thead>
                  <th>Seleccione</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Numero</th>
                  <th>CI/RUC</th>
                  <th>Cliente</th>
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
 <!--  /////////////////// -->

 <div class="modal fade" id="trans" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title" style="text-align:center;">Agregar Nuevo Transportista </h3>
 
           <div class="modal-body">
          
        </div>

       <!--  <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
 -->
      
    </div>
  </div>
  
</div>
</div>


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
      var dcc='<?php echo $dcc;?>';
      
      
      function n_trans(){
       
          
          if (tra_identificacion.value.length == 0) {
              $("#tra_identificacion").css({borderColor: "red"});
              $("#tra_identificacion").focus();
              return false;
          } else if (tra_razon_social2.value.length == 0) {
              $("#tra_razon_social2").css({borderColor: "red"});
              $("#tra_razon_social2").focus();
              return false;
          } else if (tra_placa2.value.length == 0) {
              $("#tra_placa2").css({borderColor: "red"});
              $("#tra_placa2").focus();
              return false;
          } else if (tra_direccion.value.length == 0) {
              $("#tra_direccion").css({borderColor: "red"});
              $("#tra_direccion").focus();
              return false;
          }else if (tra_telefono.value.length == 0) {
              $("#tra_telefono").css({borderColor: "red"});
              $("#tra_telefono").focus();
              return false;
          }else if (tra_email.value.length == 0) {
              $("#tra_email").css({borderColor: "red"});
              $("#tra_email").focus();
              return false;
          }else if (tra_estado.value.length == 0) {
              $("#tra_estado").css({borderColor: "red"});
              $("#tra_estado").focus();
              return false;
          }

           $('#frm_trans').submit();   
                        
                   
       }

      function validar_fecha(){
        fecha1=$('#gui_fecha_inicio').val();
        fecha2=$('#gui_fecha_fin').val();
        if(fecha2<fecha1){
          //alert('Fecha Fin Traslado es menor a la Fecha de Inicio Traslado');
          swal("Error!", "Fecha Fin Traslado es menor a la Fecha de Inicio Traslado.!", "error");
          $('#gui_fecha_fin').val('');
        }
      } 

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#identificacion').val().length == 0) {
                            //alert('Ingrese dato');
                             swal("Error!", "Ingrese dato.!", "error");
                            $('#identificacion').focus();
                            $('#identificacion').val('');
                            $('#cli_id').val('0');
                            $('#nombre').val('');
                            $('#telefono_cliente').val('');
                            $('#email_cliente').val('');
                            $('#gui_destino').val('');
                            return false;
                      }
                    },
                    url: base_url+"guia_remision/traer_cliente/"+identificacion.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#nombre').val(dt.cli_raz_social);
                          $('#telefono_cliente').val(dt.cli_telefono);
                          $('#email_cliente').val(dt.cli_email);
                          $('#identificacion').val(dt.cli_ced_ruc);
                          $('#gui_destino').val(dt.cli_calle_prin);
                        }else{
                         // alert('Cliente no existe');
                            swal("Error!", "Cliente no existe.!", "error");
                          $('#identificacion').focus();
                          $('#identificacion').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#email_cliente').val('');
                          $('#gui_destino').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          //alert('Cliente no existe');
                          swal("Error!", "Cliente no existe.!", "error");
                          $('#identificacion').focus();
                          $('#identificacion').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#email_cliente').val('');
                          $('#gui_destino').val('');
                    }
                    });    
            }

            function traer_transportista(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#gui_identificacion_transp').val().length == 0) {
                            //alert('Ingrese dato');
                            swal("Error!", "Ingrese dato.!", "error");
                            $('#gui_identificacion_transp').focus();
                            $('#gui_identificacion_transp').val('');
                            $('#tra_id').val('0');
                            $('#tra_razon_social').val('');
                            $('#tra_placa').val('');
                            return false;
                      }
                    },
                    url: base_url+"guia_remision/traer_transportista/"+gui_identificacion_transp.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#tra_id').val(dt.tra_id);
                          $('#tra_razon_social').val(dt.tra_razon_social);
                          $('#tra_placa').val(dt.tra_placa);
                          $('#gui_identificacion_transp').val(dt.tra_identificacion);
                        }else{
                          //alert('Transportista no existe');
                          swal("Error!", "Transportista no existe.!", "error");
                          $('#gui_identificacion_transp').focus();
                          $('#gui_identificacion_transp').val('');
                          $('#tra_id').val('0');
                          $('#tra_razon_social').val('');
                          $('#tra_placa').val('');
                         
                        } 
                        
                    },
                    error : function(xhr, status) {
                          //alert('Transportista no existe se creara un nuevo');
                          swal("Error!", "Transportista no existe se creara un nuevo.!", "error");
                          // $('#gui_identificacion_transp').focus();
                          // $('#gui_identificacion_transp').val('');
                          $('#tra_id').val('0');
                          $('#tra_razon_social').val('');
                          $('#tra_placa').val('');
                        
                    }
                    });    
            }

            function limpiar_guia(){
                    $('#fac_id').val('0');
                    $('#gui_num_comprobante').val('');
                    $('#gui_aut_comp').val('');
                    $('#identificacion').val('');
                    $('#nombre').val('');
                    $('#telefono_cliente').val('');
                    $('#email_cliente').val('');
                    $('#lista').html('');
                    $('#cli_id').val('');
                    $('#identificacion').attr('readonly', false);
                    $('#nombre').attr('readonly', true);
                    $('#telefono_cliente').attr('readonly', true);
                    $('#email_cliente').attr('readonly', true);
                    a = '"';
                    var tr = "<tr>"+
                                        "<td colspan='2'>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='pro_descripcion' name='pro_descripcion'  value='' lang='1'   maxlength='16' list='productos' onchange='load_producto(this.lang)'  />"+
                                        "</td>"+
                                        "<td>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='pro_referencia' name='pro_referencia'   value='' lang='1' readonly style='width:300px;' />"+
                                            "<input type='hidden'  id='pro_aux' name='pro_aux' lang='1'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' id='unidad' name='unidad'  value='' lang='1' readonly class='form-control' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidadf' name='cantidadf'  value='' lang='1'  class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='entregado' name='entregado'  value='' lang='1' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='saldo' name='saldo'  value='' lang='1' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidad' name='cantidad'  value='' lang='1' class='form-control decimal' />"+
                                        "</td>"+
                                        "<td align='center' ><input  type='button' name='add1' id='add1' class='btn btn-primary fa fa-plus' onclick='validar("+a+"#tbl_detalle"+a+",0)' lang='1' value='+'/> </td>"+
                                    "</tr>";
                    $('#lista_encabezado').html(tr);
                    $('#identificacion').focus();
            }

            function denominacion(){
                if($('#gui_denominacion_comp').val()=='0'){
                    $('#gui_num_comprobante').val('');
                    $('#gui_aut_comp').val('');
                    $('#gui_num_comprobante').attr('readonly',true);
                    $('#gui_aut_comp').attr('readonly',true);
                    $('#identificacion').attr('readonly',false);
                }else{
                    $('#gui_num_comprobante').attr('readonly',false);
                    $('#gui_aut_comp').attr('readonly',false);
                    $('#identificacion').attr('readonly',true);
                }

                limpiar_guia();
           
            }

            function num_factura(obj) {
                nfac = obj.value;
                dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('fac_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    //alert('No cumple con la estructura ejem: 000-000-000000000');
                    swal("Error!", "No cumple con la estructura ejem: 000-000-000000000.!", "error");
                    limpiar_guia();                    
                } else {
                    load_factura(obj);
                }
            }

            function traer_facturas(obj) {
              
              $.ajax({
                  beforeSend: function () {
                      if ($('#gui_num_comprobante').val().length == 0) {
                            //alert('Ingrese una factura');
                            swal("Error!", "Ingrese una factura.!", "error");
                            return false;
                      }
                    },
                  url: base_url+"guia_remision/traer_facturas/"+$('#gui_num_comprobante').val()+"/"+emi_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) { 
                    i=dt.length;
                    if(i>0){
                        n=0;
                        var tr="";
                        while(n<i){
                            tr+="<tr>"+
                                "<td><input type='checkbox' onclick='load_factura("+dt[n]['fac_id']+")'></td>"+
                                "<td>"+dt[n]['fac_fecha_emision']+"</td>"+
                                "<td>FACTURA</td>"+
                                "<td>"+dt[n]['fac_numero']+"</td>"+
                                "<td>"+dt[n]['cli_ced_ruc']+"</td>"+
                                "<td>"+dt[n]['cli_raz_social']+"</td>"+
                                "</tr>";
                                n++;
                        }
                        $('#det_ventas').html(tr);
                        $("#myModal").modal();
                    }else{
                        //alert('No existe Factura \n Se creara Guia de Remision sin Factura');
                        swal("Error!", "No existe Factura \n Se creara Guia de Remision sin Factura.!", "error");
                        limpiar_guia();
                    }
                  }
                })
            }        

            function load_factura(vl) {
              // $("#myModal").modal('hide');
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"guia_remision/load_factura/"+vl.value+"/"+dec+"/"+dcc,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt != '') {
                                $('#fac_id').val(dt.fac_id);
                                $('#gui_fecha_comp').val(dt.fac_fecha_emision);
                                $('#gui_aut_comp').val(dt.fac_autorizacion);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#gui_destino').val(dt.cli_calle_prin);
                                $('#telefono_cliente').val(dt.cli_telefono);
                                $('#email_cliente').val(dt.cli_email);
                                $('#cli_id').val(dt.cli_id);
                                $('#identificacion').attr('readonly', true);
                                $('#nombre').attr('readonly', true);
                                $('#telefono_cliente').attr('readonly', true);
                                $('#email_cliente').attr('readonly', true);
                                $('#lista').html(dt.detalle);
                                $('#count_detalle').val(dt.cnt_detalle);
                                $('#lista_encabezado').html('');
                            } else {
                                limpiar_guia();
                            }
                        },
                    error : function(xhr, status) {
                          //alert('No existe Factura');
                            swal("Error!", "No existe Factura.!", "error");
                          limpiar_guia();
                    }    
                })
                
            }

            function load_producto(j) {
              
                vl = $('#pro_descripcion').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#pro_descripcion').val().length == 0) {
                            //alert('Ingrese un producto');
                             swal("Error!", "Ingrese un producto.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"guia_remision/load_producto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        $('#pro_descripcion').val(dt.pro_codigo);
                        $('#pro_referencia').val(dt.pro_descripcion);
                        $('#pro_aux').val(dt.pro_id);
                        $('#cantidad').val('');
                        $('#cantidadf').val('0');
                        $('#entregado').val('0');
                        $('#saldo').val('0');
                        $('#unidad').val(dt.pro_unidad);
                        $('#cantidad').focus();
                      }else{
                        $('#pro_descripcion').val('');
                        $('#pro_referencia').val('');
                        $('#cantidad').val('');
                        $('#cantidadf').val('');
                        $('#entregado').val('');
                        $('#saldo').val('');
                        $('#pro_aux').val('');
                        $('#pro_descripcion').focus();
                      }
                    }
                  });
                
              }

            function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0 && $('#pro_descripcion').val().length!=0){
                  clona_detalle(table);
                }
            }
            

            
            function clona_detalle(table,opc) {
                d = 0;
                n = 0;
                ap = '"';
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                if(a==null){
                    j=0;
                }else{
                    j=parseInt(a);
                }
                if (j > 0) {
                    while (n < j) {
                        n++;
                        if ($('#pro_aux' + n).val() == pro_aux.value) {
                            d = 1;
                            cant = round($('#cantidad' + n).val(),dcc) + round(cantidad.value,dcc);
                            $('#cantidad' + n).val(cant.toFixed(dcc));
                            $('#cantidadf' + n).val(cantidadf.value);
                            $('#entregado' + n).val(entregado.value);
                            $('#saldo' + n).html(saldo.value);
                            
                        }
                    }
                }
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                        "</td>"+
                                        "<td id='pro_descripcion"+i+"' lang='"+i+"'>"+pro_descripcion.value+"</td>"+
                                        "<td id='pro_referencia"+i+"' lang='"+i+"'>"+pro_referencia.value+"</td>"+
                                        "<td id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidadf"+i+"' name='cantidadf"+i+"' lang='"+i+"' value='"+cantidadf.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='entregado"+i+"' name='entregado"+i+"' lang='"+i+"' value='"+entregado.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='saldo"+i+"' name='saldo"+i+"' lang='"+i+"' value='"+saldo.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"' onchange='validar_inventario_det()'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                pro_referencia.value = '';
                pro_descripcion.value = '';
                pro_aux.value = '';
                unidad.value = '';
                cantidad.value = '';
                cantidadf.value = '';
                entregado.value = '';
                saldo.value = '';
                $('#cantidad').css({borderColor: ""});
                $('#pro_descripcion').focus();
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  
            }
 
            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }

            function validar_cantfactura(obj){
                        n=obj.lang;
                        if(fac_id.value!=0){
                          entr=$('#entregado'+n).val();
                          n=obj.lang;
                          cnt=parseFloat($('#cantidad'+n).val())+parseFloat($('#entregado'+n).val());
                          if (parseFloat($('#cantidadf'+n).val()) < cnt) {
                            //alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE LA CANTIDAD SOLICITADA');
                             swal("Error!", "NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE LA CANTIDAD SOLICITADA.!", "error");
                            $('#cantidad'+n).val('');
                            $('#cantidad'+n).focus();
                            $('#cantidad'+n).css({borderColor: "red"});
                            $('#entregado'+n).val(entr);
                          }else{
                            $('#cantidad'+n).css({borderColor: ""});
                          }
                        }
            }

            function save() {
                        if($('#gui_denominacion_comp').val()=='1'){
                         if (gui_num_comprobante.value.length == 0) {
                              $("#gui_num_comprobante").css({borderColor: "red"});
                              $("#gui_num_comprobante").focus();
                              return false;
                          } else if (gui_aut_comp.value.length == 0) {
                              $("#gui_aut_comp").css({borderColor: "red"});
                              $("#gui_aut_comp").focus();
                              return false;
                          }
                        }

                        if (gui_fecha_emision.value.length == 0) {
                            $("#gui_fecha_emision").css({borderColor: "red"});
                            $("#gui_fecha_emision").focus();
                            return false;
                        } else if (gui_fecha_inicio.value.length == 0) {
                            $("#gui_fecha_inicio").css({borderColor: "red"});
                            $("#gui_fecha_inicio").focus();
                            return false;
                        } else if (gui_fecha_fin.value.length == 0) {
                            $("#gui_fecha_fin").css({borderColor: "red"});
                            $("#gui_fecha_fin").focus();
                            return false;
                        } else if (gui_fecha_comp.value.length == 0) {
                            $("#gui_fecha_comp").css({borderColor: "red"});
                            $("#gui_fecha_comp").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
                            return false;
                        } else if (gui_motivo_traslado.value.length == 0) {
                            $("#gui_motivo_traslado").css({borderColor: "red"});
                            $("#gui_motivo_traslado").focus();
                            return false;
                        } else if (gui_punto_partida.value.length == 0) {
                            $("#gui_punto_partida").css({borderColor: "red"});
                            $("#gui_punto_partida").focus();
                            return false;
                        } else if (gui_destino.value.length == 0) {
                            $("#gui_destino").css({borderColor: "red"});
                            $("#gui_destino").focus();
                            return false;
                        } else if (gui_cod_establecimiento.value.length == 0) {
                            $("#gui_cod_establecimiento").css({borderColor: "red"});
                            $("#gui_cod_establecimiento").focus();
                            return false;
                        }
                        else if (gui_identificacion_transp.value.length == 0) {
                            $("#gui_identificacion_transp").css({borderColor: "red"});
                            $("#gui_identificacion_transp").focus();
                            return false;
                        }
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          //alert("Ingrese Detalle");
                           swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#pro_descripcion' + n).html() != null && parseFloat($('#cantidad' + n).val())>0) {
                                  k++;
                                    if ($('#pro_descripcion' + n).html().length == 0) {
                                        $('#pro_descripcion' + n).css({borderColor: "red"});
                                        $('#pro_descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } 
                                }
                            }
                        }

                        if(k==0){
                          //alert('No se puede Guardar Guia de Remision con cantidades en 0');
                          swal("Error!", "No se puede Guardar Guia de Remision con cantidades en 0.!", "error");
                          return false;
                        }
                        
                        if ($('#vnd_id').val() == 0 || $('#vnd_id').val() == '') {
                            $('#vnd_id').css({borderColor: "red"});
                            $('#vnd_id').focus();
                            swal("Error!", "Su usuario no es vendedor.!", "error");
                            return false;
                        }
                        
                     $('#frm_save').submit();   
               }  

    function enter(e) {
                var char = e.which;
                if (char == 13) {
                    return false;
                }
    } 
    </script>

