<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
     Pedidos Móvil
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          $inven=$inven->con_valor;
          $cprec=$cprec->con_valor;
          $cdesc=$cdesc->con_valor;
          $m_pag=$m_pag->con_valor;
          $m_prec=$m_prec->con_valor;
          if($inven==0){
            $hid_inv='hidden';
            $col_obs='4';
          }else{
            $hid_inv='hidden';
            $col_obs='5';
          }
          if($mod==0){
            $readonly="";
            $disabled="";
          }else{
            $readonly="readonly";
            $disabled="disabled";
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
                      <td class="col-sm-4">
                        <div class="box-body">
                        
                              </td>
                          </tr>
                          <tr>
                      <td width="25%" colspan="4" >
                        <div class="box-body">
                        <div class="panel panel-success col-sm-10">
                        <div class="panel panel-heading"><label>Seleccione Cliente</label></div>
                        <br>
                        <?php
                        if($aut!=1){

                        $readonly="";
                        $disabled="";
                        $hidden="";
                        ?>
                          <div class="row" >
                               <div class="col-sm-4">
                                 <input style="width:105px" type="button" class="btn btn-primary" onclick="verificar_cedula('9999999999999')"  name="" value="Consumidor final ">

                              </div>

                              <div class="col-sm-4">
                                <input style="width:105px" type="button" class="btn btn-success" onclick="datos()"  name="" value="Con Datos">
                              </div>

                              <div class="col-sm-4">
                                <input style="width:105px" type="button" class="btn btn-info" onclick="pas()"  name="" value="Extranjero">
                              </div>
                            </div>
                            <?php
                          }else{
                            $readonly="readonly";
                            $disabled="disabled";
                            $hidden  ="hidden";
                            
                             
                          }?>
<br>
                        <table class="table">
                          <tr>
                              <td><label id="tipo_cliente">RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_ruc_cc_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ped_ruc_cc_cliente" id="ped_ruc_cc_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_ruc_cc_cliente');}else{ echo $pedido->ped_ruc_cc_cliente;}?>"  onchange="buscar_clientes(this)" <?php echo $readonly?>>
                                  <?php echo form_error("ped_ruc_cc_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td hidden><label>Pasaporte:</label>
                                <input type="checkbox" name="pasaporte" onclick="extra()" id="pasaporte" <?php echo $disabled?>>
                                 <input type="hidden"  name="pas_aux" id="pas_aux" value="0">
                            </td>
                          </tr>
                          <tr>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('ped_nom_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="ped_nom_cliente" id="ped_nom_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_nom_cliente');}else{ echo $pedido->ped_nom_cliente;}?>" <?php echo $readonly?>>
                                    <?php echo form_error("ped_nom_cliente","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $pedido->cli_id;}?>" >
                                <input type="hidden" class="form-control" name="tipo_cliente" id="tipo_cliente" value="<?php if(validation_errors()!=''){ echo set_value('tipo_cliente');}else{ echo $pedido->tipo_cliente;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('ped_dir_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="ped_dir_cliente" id="ped_dir_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_dir_cliente');}else{ echo $pedido->ped_dir_cliente;}?>" <?php echo $readonly?>>
                                <?php echo form_error("ped_dir_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_tel_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ped_tel_cliente" id="ped_tel_cliente" value="<?php if(validation_errors('ped_tel_cliente')!=''){ echo set_value('ped_tel_cliente');}else{ echo $pedido->ped_tel_cliente;}?>" <?php echo $readonly?>>
                                      <?php echo form_error("ped_tel_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="ped_email_cliente" id="ped_email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_email_cliente');}else{ echo $pedido->ped_email_cliente;}?>" <?php echo $readonly?>>
                                  <?php echo form_error("ped_email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                            <!-- <tr>
                              <td><label>Parroquia:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_parroquia_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ped_parroquia_cliente" id="ped_parroquia_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_parroquia_cliente');}else{ echo $pedido->ped_parroquia_cliente;}?>" <?php echo $readonly?>>
                                  <?php echo form_error("ped_parroquia_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            <tr> -->
                              <td><label>Ciudad:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_ciu_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ped_ciu_cliente" id="ped_ciu_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_ciu_cliente');}else{ echo $pedido->ped_ciu_cliente;}?>" <?php echo $readonly?>>
                                  <?php echo form_error("ped_ciu_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Pais:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ped_pais_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ped_pais_cliente" id="ped_pais_cliente" value="<?php if(validation_errors()!=''){ echo set_value('ped_pais_cliente');}else{ echo $pedido->ped_pais_cliente;}?>" <?php echo $readonly?>>
                                  <?php echo form_error("ped_pais_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                          </table>
                          </div>
                          </div>
                          </td>
                          <td width="20%" colspan="3">

                          <div class="box-body">
                           <!--  <div class="panel panel-default col-sm-12"> -->
                              <table class="table ">
                                <tr class="row">
                                <td class="col-md-12">

                                    <label>Fecha Emision:</label>
                                    <div class="form-group <?php echo !empty(form_error('ped_femision'))? 'has-error' : '';?> ">
                                    <input type="date" class="form-control" name="ped_femision" id="ped_femision" value="<?php echo !empty(validation_errors())? set_value('ped_femision') :  $pedido->ped_femision;?>" readonly>
                                    <?php echo form_error("ped_femision","<span class='help-block'>","</span>");?>
                                    </div>
                                    <label>Fecha Entrega:</label>
                                    <div class="form-group <?php echo !empty(form_error('ped_fentrega'))? 'has-error' : '';?> ">
                                    <input type="date" class="form-control" name="ped_fentrega" id="ped_fentrega" value="<?php echo !empty(validation_errors())? set_value('ped_fentrega') :  $pedido->ped_fentrega;?>" <?php echo $disabled?>>
                                    <?php echo form_error("ped_fentrega","<span class='help-block'>","</span>");?>
                                    </div>

                                  <label>Local:</label>
                                  <div class="form-group  <?php echo !empty(form_error('ped_local'))? 'has-error' : '';?>">
                                    <input type="hidden" name="bod_cli" id="bod_cli" value="0">
                                    <select name="ped_local"  id="ped_local" class="form-control" onchange="traer_emisor()" <?php echo $disabled?>>
                                      <option value="">SELECCIONE</option>
                                       <?php
                                      if(!empty($locales)){
                                        foreach ($locales as $local) {
                                      ?>
                                      <option value="<?php echo $local->emi_id?>"><?php echo $local->emi_nombre?></option>
                                      <?php
                                        }
                                      }
                                    ?>
                                    </select>
                                    <script type="text/javascript">
                                      var est='<?php echo $pedido->ped_local;?>';
                                      ped_local.value=est;
                                    </script>
                                     <?php echo form_error("ped_local","<span class='help-block'>","</span>");?>
                                  </div>
                                  <label>Vendedor:</label>
                                  <div class="form-group <?php echo !empty(form_error('ped_vendedor'))? 'has-error' : '';?> ">
                                    <select name="ped_vendedor"  id="ped_vendedor" class="form-control" <?php echo $disabled?>>
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
                                      var vnd='<?php echo $pedido->ped_vendedor;?>';
                                      ped_vendedor.value=vnd;
                                    </script>
                                    <?php echo form_error("ped_vendedor","<span class='help-block'>","</span>");?>
                                  </div>

                                </td>
                              
                              </tr>
                              </table>
                            <!-- </div> -->
                          
                          <br>
                          <br>


                          <div class="panel panel-default col-sm-14">


                          <div class="panel panel-heading">
                            

                            <label>Pagos</label></div>
                          <!-- <table class="table table-bordered table-striped" id="tbl_pagos">
                            <thead>
                              <tr>
                                <th>%</th>
                                <th>Dias</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              
                              if ($cns_pag == '') {
                                $cnt_pagos=1;
                              ?>
                              <tr>
                                <td>
                                  <input type="text" id="pag_porcentage1" name="pag_porcentage1" class="form-control itm" size="35px" lang="1" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')" onchange="calculo_pagos()" value="100">
                                  
                                </td>
                                <td><input type="text" id="pag_dias1" name="pag_dias1" class="form-control" size="25px" lang="1" onkeyup="this.value = this.value.replace(/[^0-9]/, '')" value="1">
                                  
                                </td>
                                <td onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                              </tr>
                              <?php 
                                  
                              }else{ 
                                
                                $m=0;
                                $cnt_pagos=0;
                                foreach($cns_pag as $rst_pag) {
                                  $m++;
                                 ?>
                                <tr>
                                <td>
                                  <input type="text" id="pag_porcentage<?php echo $m?>" name="pag_porcentage<?php echo $m?>" class="form-control itm" size="35px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_porcentage?>" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')" onchange="calculo_pagos()"<?php echo $readonly?>>
                                </td>  
                                <td>  
                                  <input type="text" id="pag_dias<?php echo $m?>" name="pag_dias<?php echo $m?>" class="form-control" size="10px" lang="1" value="<?php echo $rst_pag->pag_dias ?>" onkeyup="this.value = this.value.replace(/[^0-9]/, '')" <?php echo $readonly?>>
                                </td>
                                <?php 
                                if($mod==0){
                                  ?>
                                  <td onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                <?php
                                  }
                                ?>
                              </tr>
                              <?php
                                  $cnt_pagos++;
                                 }
                              }
                              ?>
                            </tbody>
                            <tfoot>
                            <tr>
                              <?php 
                                if($mod==0){
                                  ?>
                                  <td align="left" ><span name="add" id="add" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos',1)"> </span></td>
                                <?php
                                  }
                                ?>
                            </tr>
                            </tfoot>
                          </table> -->


                          <table class="table table-bordered table-striped" id="tbl_pagos">
                            <thead>
                              <tr>
                                <th>Forma</th>
                               <!--  <th>Documento</th> -->
                                <th>Plazo</th>
                                <th>Cantidad</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $faltante=0;
                              $cnt_pagos=1;
                              if ($cns_pag == '') {
                              ?>
                              <tr>
                                <td width="190">
                                  <!-- <input type="text" id="pag_descripcion1" name="pag_descripcion1"  class="form-control itm" size="35px" lang="1" list="list_formas" onchange="traer_forma(this),busqueda_ntscre(this)"> -->

                              <div class="form-group" >
                                  <select id="pag_descripcion1"  name="pag_descripcion1" lang="1"  class="form-control itm" onchange="traer_forma(this)">
                                  <option value="">SELECCIONE</option>

                                        <?php 
                                        if(!empty($formas_pago)){
                                        foreach ($formas_pago as $fpg) {
                                        ?>
                                        <option value="<?php echo $fpg->fpg_descripcion?>"><?php echo $fpg->fpg_descripcion?></option>
                                        <?php
                                        }
                                        }
                                        ?>
                                  </select>
                              </div>
                                  <input type="hidden" id="pag_forma1" name="pag_forma1" class="form-control" size="10px" lang="1">
                                  <input type="hidden" id="pag_tipo1" name="pag_tipo1" class="form-control" size="10px" lang="1">
                                </td>
                                <!-- <td ><input type="text" id="pag_documento1" name="pag_documento1" class="form-control" size="25px" lang="1" list="list_notas" onchange="load_notas_credito(this)">
                                  <input type="text" id="id_nota_credito1" name="id_nota_credito1" value="0" class="form-control" size="10px" lang="1">
                                  <input type="text" id="val_nt_cre1" name="val_nt_cre1" class="form-control" size="10px" lang="1">
                                </td> -->
                                <td width="150">
                                  <select name="pag_plazo1" id="pag_plazo1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select>

                                </td>
                                <td><input type="text" id="pag_cantidad1" name="pag_cantidad1" class="form-control" size="10px" lang="1" onchange="calculo_pagos(this)" onkeyup='validar_decimal(this)' readonly></td>
                                <td>
                                  
                                </td>
                                <?php 
                                  if($m_pag==0){
                                  ?>
        <!-- <td align="center" ><span name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos','1')" lang="1"> </span></td> -->

        <td><input type="button" name="add1" id="add1" onclick="validar('#tbl_pagos','1')" lang="1" class="btn btn-primary " value="Agregar">
            </td>
                                  <td onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                <?php 
                                  }
                                }else{ 
                                $m=0;
                                $cnt_pagos=0;
                                $faltante=0; 
                                $fal=0; 
                               
                                foreach($cns_pag as $rst_pag){ 
                                  $m++;
                                  $y=$m-1;
                                  if ($rst_pag->pag_tipo == '1') {
                                      $read_cnt='';
                                      $dis_contado='';
                                  } else if ($rst_pag->pag_tipo == '2') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else if ($rst_pag->pag_tipo == '3') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else if ($rst_pag->pag_tipo == '9') {
                                      $read_cnt='';
                                      $dis_contado='';
                                  } else if ($rst_pag->pag_tipo > '3') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else {
                                      $read_cnt='readonly';
                                      $dis_contado='disabled';
                                  }
                                ?>
                                <tr>
                                <td>
                                 <!--  <input type="text"  id="pag_descripcion<?php echo $m?>" name="pag_descripcion<?php echo $m?>" class="form-control itm" size="35px" lang="<?php echo $m?>" list="list_formas" onchange="traer_forma(this),busqueda_ntscre(this)" value="<?php echo $rst_pag->fpg_descripcion?>"> -->

                                  <div class="form-group" >
                                  <select <?php echo $disabled ?> id="pag_descripcion<?php echo $m?>"  name="pag_descripcion<?php echo $m?>" lang="<?php echo $m?>"  class="form-control itm" onchange="traer_forma(this)">
                                  <option value="">SELECCIONE</option>

                                        <?php 
                                        if(!empty($formas_pago)){
                                        foreach ($formas_pago as $fpg) {
                                        ?>
                                        <option value="<?php echo $fpg->fpg_descripcion?>"><?php echo $fpg->fpg_descripcion?></option>
                                        <?php
                                        }
                                        }
                                        ?>
                                  </select>

                                  <script>
                                    pag_descripcion<?php echo $m?>.value='<?php echo $rst_pag->fpg_descripcion?>';

                                  </script>
                              </div>

                                  <input type="hidden" id="pag_forma<?php echo $m?>" name="pag_forma<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_forma?>">
                                  <input type="hidden" id="pag_tipo<?php echo $m?>" name="pag_tipo<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_tipo?>">
                                </td>
                                <!-- <td><input type="text" id="pag_documento<?php echo $m?>" name="pag_documento<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" list="list_notas" onchange="load_notas_credito(this)" value="<?php echo $rst_pag->chq_numero?>">
                                  <input type="hidden" id="id_nota_credito<?php echo $m?>" name="id_nota_credito<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_id_chq?>">
                                  <input type="hidden" id="val_nt_cre<?php echo $m?>" name="val_nt_cre<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>">
                                </td> -->
                                <td>
                                  <select <?php echo $disabled ?> name="pag_plazo<?php echo $m?>" id="pag_plazo<?php echo $m?>" class="form-control" lang="1" <?php echo $dis_contado?>>
                                    <option value='0'>SELECCIONE</option>
                                  </select>
                                  <script>

                                    var pcont="<?php echo $rst_pag->contado?>";


                                    pag_plazo<?php echo $m?>.innerHTML=pcont;  
                                      pag_plazo<?php echo $m?>.value='<?php echo $rst_pag->pag_contado?>';
                                    
                                   
                                   
                                  </script>
                                </td>
                                <td><input <?php echo $readonly ?> type="text" id="pag_cantidad<?php echo $m?>" name="pag_cantidad<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" onchange="calculo_pagos(this)" <?php echo $read_cnt?> value="<?php echo str_replace(',', '', number_format($rst_pag->pag_cant, $dec))?>"></td>

                                <?php 
                                  if($m_pag==0){
                                  ?>
                     <!--  <td align="center" ><span name="add<?php echo $m?>" id="add<?php echo $m?>" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos','1')" lang="<?php echo $m?>"> </span></td> -->
                     <?php
                     if($m>1){
                      $ocultar='hidden';
                     }else{
                      $ocultar='button';

                     }

                     ?>

                      <td <?php echo $hidden ?> ><input type="<?php echo $ocultar ?>" name="add<?php echo $m?>" id="add<?php echo $m?>" onclick="validar('#tbl_pagos','1')" lang="<?php echo $m?>" class="btn btn-primary " value="Agregar">
            </td>
                                  <td <?php echo $hidden ?> onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                              </tr>
                              <?php
                                  }
                                  $fal+=round($rst_pag->pag_cant,$dec);
                                  $cnt_pagos++;
                                } 
                                $faltante=round($pedido->ped_total,$dec)-round($fal,$dec);
                              }   
                                ?>
                            </tbody>
                            <tfoot>
                            <tr>
                              <td colspan="2"><label>Faltante</label></td>
                              <td><input type="text" id="faltante" name="faltante" class="form-control" size="10px" readonly value='<?php echo str_replace(',', '', number_format($faltante,2))?>'></td>
                            </tr>
                            </tfoot>
                          </table>
                          </div>
                          </div>
                          </td> 
                      </tr>
                      <tr>
                       <td class="col-sm-12" colspan="8">
                          <?php
                          if($m_prec==0){
                            $hid_prec="";
                          }else{
                            $hid_prec="display:none;";
                          }
                          ?>
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th hidden>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th hidden>Unidad</th>
                                <th <?php echo $hid_inv?>>Inventario</th>
                                <th>Cantidad</th>
                                <th>Precio<select id="fprecio" style="width:50px; <?php echo $hid_prec?>" onchange="load_precios()" <?php echo $disabled ?>>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select></th>
                                <th>Desc.%</th>
                                <th hidden>Desc.$</th>
                                <th>IVA</th>
                                <th hidden>det_val_ice%</th>
                                <th hidden>det_val_ice $</th>
                                <th>Val.Total</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <?php 
                              $cnt_detalle=0;
                                    if($cprec==0){
                                      $r_precio='readonly';
                                    }else{
                                      $r_precio='';
                                    }
                                    if($cdesc==0){
                                      $r_descuento='readonly';
                                    }else{
                                      $r_descuento='';
                                    }
                              if($mod==0){
                            ?>
                            <tbody>
                               
                                    <tr>
                                     
                                      
                                        <td colspan="1">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="det_cod_producto" name="det_cod_producto"  value="" lang="1"    list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="det_descripcion" name="det_descripcion"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                            <input type="hidden"  id="pro_ids" name="pro_ids" lang="1"/>
                                            
                                        </td>
                                        <td hidden>
                                          <input type ="text" size="7" id="det_unidad" name="det_unidad"  value="" lang="1" readonly class="form-control" />
                                        </td>
                                        <td <?php echo $hid_inv?>>
                                          <input type ="text" size="7" style="text-align:right" id="inventario" name="inventario" value="" lang="1" readonly class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="det_cantidad" name="det_cantidad"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" />
                                        </td>
                                        <td >
                                          <input type ="text" size="7" style="text-align:right" id="det_vunit" name="det_vunit" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal" <?php echo $r_precio?>/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="det_descuento_porcentaje" name="det_descuento_porcentaje"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" <?php echo $r_descuento?>/>
                                        </td>
                                        <td hidden>
                                          <input type ="text" size="7"  style="text-align:right" id="det_descuento_moneda" name="det_descuento_moneda"  value="" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type="text" id="det_impuesto" style="text-align:right" name="det_impuesto" size="5" value="" readonly class="form-control decimal"/>
                                        </td>
                                        <td hidden><input type="text" name="det_val_ice" id="det_val_ice" size="5" value="0" readonly lang="1" /></td>
                                        <td hidden><input type="text" name="det_p_ice" id="det_p_ice" size="5" value="0" readonly lang="1"/>
                                            <input type=""  name="det_cod_ice" id="det_cod_ice" size="5" value="0" readonly lang="1"/>
                                        </td>
                                        <td>
                                            <input type ="text" size="9" style="text-align:right" id="det_total" name="det_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>

                                        <!-- <td align="center" ><span name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1"> </span></td>
                                        <td onclick="elimina_fila(this, '#tbl_detalle','0')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td> -->
                                    </tr>
                                </tbody> 
                                <?php
                                }
                                ?>       
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        ?>
                                        <tr>
                                             <td hidden id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center" class="itm"><?PHP echo $n ?></td>
                                            <td ><input type="text" id="det_cod_producto<?PHP echo $n ?>" name="det_cod_producto<?PHP echo $n ?>" lang="<?PHP echo $n ?>" class="form-control" value="<?php echo $rst_det->det_cod_producto ?>" readonly></td>
                                            <td ><input type="text" id="det_descripcion<?PHP echo $n ?>" name="det_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>" class="form-control" value="<?php echo $rst_det->det_descripcion ?>" readonly>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                                
                                            </td>
                                            <td hidden><input type="text" id="det_unidad<?PHP echo $n ?>" name="det_unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>" class="form-control" value="<?PHP echo $rst_det->det_unidad ?>" readonly></td>
                                            <td hidden><input type="text" id="inventario<?PHP echo $n ?>" name="inventario<?PHP echo $n ?>" lang="<?PHP echo $n ?>" <?php echo $hid_inv?>  style="text-align:right" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->inventario, $dcc)) ?>" readonly></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'det_cantidad' . $n ?>" name="<?php echo 'det_cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->det_cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')" <?php echo $readonly?> /></td>
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'det_vunit' . $n ?>" name="<?php echo 'det_vunit' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->det_vunit, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" <?php echo $r_precio?> <?php echo $readonly?>/></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'det_descuento_porcentaje' . $n ?>" name="<?php echo 'det_descuento_porcentaje' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->det_descuento_porcentaje, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)"  <?php echo $r_descuento?> <?php echo $readonly?>/>
                                            </td>
                                            <td hidden>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'det_descuento_moneda' . $n ?>" name="<?php echo 'det_descuento_moneda' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->det_descuento_moneda, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            <td><input type="text" id="<?php echo 'det_impuesto' . $n ?>" name="<?php echo 'det_impuesto' . $n ?>" size="5" style="text-align:right" class="form-control" value="<?php echo $rst_det->det_impuesto ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'det_val_ice' . $n ?>" name="<?php echo 'det_val_ice' . $n ?>" size="5" value="<?php echo str_replace(',', '', number_format($rst_det->det_val_ice, $dec)) ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'det_p_ice' . $n ?>" name="<?php echo 'det_p_ice' . $n ?>" size="5" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->det_p_ice, $dec)) ?>" readonly lang="<?php echo $n ?>"/>
                                                <input type="hidden" id="<?php echo 'det_cod_ice' . $n ?>" name="<?php echo 'det_cod_ice' . $n ?>" size="5" class="form-control" value="<?php echo $rst_det->det_cod_ice ?>" lang="<?PHP echo $n ?>"readonly />
                                            </td>
                                            <td>
                                                <input type ="text" size="9" style="text-align:right" class="form-control" id="<?php echo 'det_total' . $n ?>" name="<?php echo 'det_total' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->det_total, $dec)) ?>" readonly lang="<?PHP echo $n ?>"/>
                                                
                                            </td>
                                            <?php
                                            if($mod==0){
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

                                    <td valign="top" rowspan="11" colspan="<?php echo $col_obs?>">
                                      <textarea id="ped_observacion" name="ped_observacion" style="height: 30px; width: 90%;"  onkeydown="return enter(event)" <?php echo $disabled?>><?php echo $pedido->ped_observacion ?></textarea>
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="ped_sbt12" name="ped_sbt12" value="<?php echo str_replace(',', '', number_format($pedido->ped_sbt12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                   <?php 
                                   $total0= floatval($pedido->ped_sbt0)  + floatval($pedido->ped_sbt_excento) + floatval($pedido->ped_sbt_noiva)
                                  ?>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal_1" name="subtotal0" value="<?php echo str_replace(',', '', number_format($total0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="ped_sbt0" name="ped_sbt0" value="<?php echo str_replace(',', '', number_format($pedido->ped_sbt0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal Excento de det_impuesto:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_sbt_excento" name="ped_sbt_excento" value="<?php echo str_replace(',', '', number_format($pedido->ped_sbt_excento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal no objeto de det_impuesto:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_sbt_noiva" name="ped_sbt_noiva" value="<?php echo str_replace(',', '', number_format($pedido->ped_sbt_noiva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_sbt" name="ped_sbt" value="<?php echo str_replace(',', '', number_format($pedido->ped_sbt, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_tdescuento" name="ped_tdescuento" value="<?php echo str_replace(',', '', number_format($pedido->ped_tdescuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_ice" name="ped_ice" value="<?php echo str_replace(',', '', number_format($pedido->ped_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="ped_iva12" name="ped_iva12" value="<?php echo str_replace(',', '', number_format($pedido->ped_iva12, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Propina:</td>
                                    <td><input type="text" class="form-control" id="ped_propina" name="ped_propina" value="<?php echo str_replace(',', '', number_format($pedido->ped_propina, $dec)) ?>"  style="text-align:right" onchange="calculo()" <?php echo $readonly?>/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="ped_total" name="ped_total" value="<?php echo str_replace(',', '', number_format($pedido->ped_total, $dec)) ?>" readonly />
                                        
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
                                
                <input type="hidden" class="form-control" name="ped_id" value="<?php echo $pedido->ped_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="count_pagos" name="count_pagos" value="<?php echo $cnt_pagos?>">
              <div class="box-footer">
                <?php
                if($mod==0){
                ?>
                  <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <?php
                }else{
                ?>
                <a href='<?php echo base_url()."autorizacion_pedido/actualizar/$pedido->ped_id/$opc_id/13"?>' class="btn btn-success">Aprobar</a>
                <a href='<?php echo base_url()."autorizacion_pedido/actualizar/$pedido->ped_id/$opc_id/14"?>' class="btn btn-danger">Rechazar</a>
                <?php
                }
                ?>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <div class="modal fade" id="clientes">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="limpiar_cliente()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align:center;">Clientes y proveedores</h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered table-striped" id="det_clientes">
                 
                  
                </table>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                </div>
                

              </div>
              
            </div>
          </div>
</div>
    <!-- <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
      
    </datalist> -->


    <style type="text/css">
     
      .panel{
        margin: 0.5px !important;
        /*margin-top: 0px !important;*/
        padding: 0.5px !important;
        /*padding-top: 0px !important;*/

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
        margin-right: 0px !important;
        margin-left:  0px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }
      

      .fa-plus{
        width: 20px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }

      .sel{
        width: 100px !important;
        
      }
    </style>
    <script >

      var base_url='<?php echo base_url();?>';
      var inven='<?php echo $inven;?>';
      var ctr_inv='<?php echo $ctrl_inv;?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var m_pag='<?php echo $m_pag;?>';
      window.onload = function () {
      var mensaje ='<?php echo $mensaje;?>';

        swal("", mensaje, "info");
      
    }

      function extra(){

        if ($('#pasaporte').prop('checked') == true) {
       
          $('#pas_aux').val(1);
          $('#ped_pais_cliente').val('');
          $('#ped_ciu_cliente').val('');
        }else{
         
          $('#pas_aux').val(0);
          $('#ped_pais_cliente').val('Ecuador');
          $('#ped_ciu_cliente').val('Quito');
        }


      }
      function load_notas_credito(obj) {
              n=obj.lang;
              var vl='';
              if($('#pag_tipo'+n).val()=='8'){
                $('#tbl_pagos .itm').each(function () {
                  if($('#pag_descripcion' + this.lang).val()!=null){
                    pro = $('#id_nota_credito' + this.lang).val();
                    pro2 = $('#pag_documento' + n).val();
                    $('#pag_descripcion' + n).css({borderColor: ""});
                    vl=pro2;
                    if (pro2 == pro) {
                      vl='';
                      //alert('Documento ya ingresado');
                      swal("Error!", "Documento ya ingresado.!", "error");
                      $('#pag_descripcion' + n).val('');
                      $('#pag_forma' + n).val(0);
                      $('#pag_tipo' + n).val(0);
                      $('#pag_documento' + n).val('');
                      $('#id_nota_credito' + n).val('0');
                      $('#val_nt_cre' + n).val('');
                      $('#pag_cantidad' + n).val('0');
                      $('#pag_cantidad' + n).attr('readonly', true);
                        return false;
                    }
                  }
                });
                if(vl!=''){
                $.ajax({
                      beforeSend: function () {
                        if ($('#pag_documento'+n).val().length == 0) {
                              //alert('Ingrese numero de documento');
                              swal("Error!", "Ingrese numero de documento.!", "error");
                              return false;
                        }
                      },
                      url: base_url+"factura/load_nota/"+vl,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                        if (dt != '1') {
                            $('#pag_documento' + n).val(dt.chq_numero);
                            $('#pag_cantidad' + n).val(dt.chq_valor);
                            $('#id_nota_credito' + n).val(dt.chq_id);
                            $('#val_nt_cre' + n).val(dt.chq_valor);
                            $('#pag_cantidad' + n).focus();
                            $('#pag_documento' + n).attr('readonly', true);
                            calculo_pagos($('#pag_cantidad' + n));
                        }else {
                            //alert('Documento no existe');
                             swal("Error!", "Documento no existe.!", "error");
                            $('#pag_descripcion' + n).val('');
                            $('#pag_forma' + n).val('0');
                            $('#pag_tipo' + n).val('0');
                            $('#pag_documento' + n).val('');
                            $('#id_nota_credito' + n).val('0');
                            $('#val_nt_cre' + n).val('');
                            $('#pag_cantidad' + n).val(0);
                            $('#pag_cantidad' + n).attr('readonly', true);
                            $('#pag_documento' + n).attr('readonly', false);
                            $('#list_notas').html('');
                            calculo_pagos($('#pag_cantidad' + n));
                        }
                        
                    }
                  }); 
                }else{
                  calculo_pagos($('#pag_cantidad' + n));
                }   
              }
            }

      // function calculo_pagos(obj){
          
        
      //         if(obj!=null){
      //           l=obj.lang;
      //         }
      //         tv=round($('#ped_total').val(),dec);
      //         var tr = $('#tbl_pagos').find("tbody tr:last");
      //         var a = tr.find("input").attr("lang");
      //         i = parseInt(a);
      //         var n = 0;
      //         var pg_ac=0;
      //           while(n<i){
      //             n++;
      //             if($('#pag_forma'+n).val()!=null){
      //               cnt=$('#pag_cantidad'+n).val();
      //               if(cnt.length==0){
      //                 cnt=0;
      //               }else{
      //                 cnt=round(cnt,dec);
      //               }
      //               ///verificar valor nota credito
      //               if($('#pag_tipo'+n).val()==8){
      //                 val_nc=$('#val_nt_cre'+n).val();
      //                 if(val_nc.length==0){
      //                   val_nc=0;
      //                 }else{
      //                   val_nc=round(val_nc,dec);
      //                 }

      //                 if(cnt>val_nc){
      //                    alert('Pago es mayor al del documento $: ' + parseFloat(val_nc).toFixed(dec));
      //                       $('#pag_cantidad' + n).val('0');
      //                       $('#pag_cantidad' + n).focus();
      //                       cnt=0;
      //                 }
      //               }
      //               pg_ac+=cnt;

      //             }

      //           }

      //           //falt=tv-pg_ac;
      //           falt=parseFloat (tv)- parseFloat(round (pg_ac,dec));
                
      //           if(falt<0){
      //             falt=tv-pg_ac+round($('#pag_cantidad'+l).val(),dec);
      //             $('#pag_cantidad'+l).val('0')
      //             $('#faltante').val(falt.toFixed(dec));   
      //           }else{
      //             $('#faltante').val(falt.toFixed(dec));  
      //           }


                
      //       }
             
      function ultimo_pago(x){
        
              flt=$('#faltante').val();
              $('#pag_cantidad'+x).val(flt);
              $('#faltante').val('0'); 
            }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            
            function traer_cliente(valor){
              var id = valor;
              $.ajax({
                    beforeSend: function () {
                      if ($('#ped_ruc_cc_cliente').val().length == 0) {
                            //alert('Ingrese dato');
                            swal("Error!", "Ingrese dato.!", "error");
                            $('#ped_nom_cliente').focus();
                            $('#cli_id').val('0');
                            $('#ped_nom_cliente').val('');
                            $('#ped_tel_cliente').val('');
                            $('#ped_dir_cliente').val('');
                            $('#ped_ciu_cliente').val('');
                            $('#ped_email_cliente').val('');
                            $('#ped_pais_cliente').val('Ecuador');
                            $('#ped_parroquia_cliente').val('');

                            $('#tipo_cliente').val('0');
                            $('#pag_descripcion1').val('CREDITO');
                            $('#pag_documento1').val('');
                            $('#pag_contado1').val('0');
                            $('#pag_cantidad1').val('0');
                            $('#pag_forma1').val('0');
                            $('#pag_tipo1').val('0');
                            $('#id_nota_credito1').val('0');
                            $('#val_nt_cre1').val('0');
                            return false;
                      }
                    },
                    url: base_url+"pedido/traer_cliente/"+id,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#ped_nom_cliente').val(dt.cli_raz_social);
                          $('#ped_tel_cliente').val(dt.cli_telefono);
                          $('#ped_dir_cliente').val(dt.cli_calle_prin);
                          $('#ped_ciu_cliente').val(dt.cli_canton);
                          $('#ped_email_cliente').val(dt.cli_email);
                          $('#ped_ruc_cc_cliente').val(dt.cli_ced_ruc);
                          $('#ped_pais_cliente').val(dt.cli_pais);
                          $('#ped_parroquia_cliente').val(dt.cli_parroquia);
                          $('#tipo_cliente').val(dt.tipo_cliente);

                          $('#pag_descripcion1').val('CREDITO');
                          $('#pag_documento1').val('');
                          $('#pag_contado1').val('0');
                          $('#pag_forma1').val('0');
                          $('#pag_cantidad1').val('0');
                          $('#pag_tipo1').val('0');
                          $('#id_nota_credito1').val('0');
                          $('#val_nt_cre1').val('0');
                          validar_bodega();
                          traer_forma(1);
                           $("#clientes").modal('hide');
                        }else{
                          //alert('Cliente no existe \n Se creará uno nuevo');
                           swal("Error!", "Cliente no existe. Se creará uno nuevo.!", "error");
                          $('#ped_nom_cliente').focus();
                            $('#cli_id').val('0');
                            $('#ped_nom_cliente').val('');
                            $('#ped_tel_cliente').val('');
                            $('#ped_dir_cliente').val('');
                            //$('#ped_ciu_cliente').val('');
                            $('#ped_email_cliente').val('');
                            //$('#ped_pais_cliente').val('');
                            $('#ped_parroquia_cliente').val('');
                            $('#tipo_cliente').val('0');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          //alert('Cliente no existe \n Se creará uno nuevo');
                           swal("Error!", "Cliente no existe. Se creará uno nuevo.!", "error");
                          $('#ped_nom_cliente').focus();
                            $('#cli_id').val('0');
                            $('#ped_nom_cliente').val('');
                            $('#ped_tel_cliente').val('');
                            $('#ped_dir_cliente').val('');
                            //$('#ped_ciu_cliente').val('');
                            $('#ped_email_cliente').val('');
                            //$('#ped_pais_cliente').val('');
                            $('#ped_parroquia_cliente').val('');
                            $('#tipo_cliente').val('0');
                            traer_forma(1);
                    }
                    });    
               
            }

            function validar(table, opc){

              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              if(opc==0){
                if($('#det_cantidad').val().length!=0 &&  parseFloat($('#det_cantidad').val())>0 && $('#det_vunit').val().length!=0 &&  parseFloat($('#det_vunit').val())>0 && $('#det_descuento_porcentaje').val().length!=0 && $('#det_cod_producto').val().length!=0){
                  clona_detalle(table);
                }else{
                   $('#det_cantidad').css({borderColor: "red"});
                   $('#det_cantidad').focus();
                }
              }else{

                 if($('#pag_forma'+a1).val()!='0' &&  parseFloat($('#pag_cantidad'+a1).val())>0 && $('#pag_cantidad'+a1).val().length!=0){

                  clona_fila(table,opc);
                }
              }

              


            }

            function busqueda_ntscre(obj) {
                if (obj.lang != null) {
                    s = obj.lang;
                } else {
                    s = obj;
                }
                nc = obj.value;
                ruc_cli = $('#identificacion').val();
                if (ruc_cli != '') {
                    if (nc == 8) {
                      $.ajax({
                      beforeSend: function () {
                        if ($('#pag_descripcion'+n).val().length == 0) {
                              //alert('Ingrese Forma de pago');
                               swal("Error!", "Ingrese Forma de pago.!", "error");
                              return false;
                        }
                      },
                      url: base_url+"pedido/buscar_notas/"+$('#cli_id').val(),
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                              if (dt != '') {
                                  $('#list_notas').html(dt.lista);
                                  $('#pag_documento' + s).focus();
                              } else {
                                  //alert('El Cliente no tiene Documentos \n En esta opcion');
                                  swal("Error!", "El Cliente no tiene Documentos. En esta opcion.!", "error");
                                  $('#pag_documento' + s).val('');
                                  $('#id_nota_credito' + s).val('0');
                                  $('#val_nt_cre' + s).val('');
                                  $('#pag_descripcion' + s).val('');
                                  $('#pag_forma' + s).val(0);
                                  $('#pag_tipo' + s).val(0);
                                  $('#pag_descripcion' + s).focus();
                                  $('#pag_cantidad' + s).val('0');
                                  $('#pag_cantidad' + s).attr('readonly', true);
                                  $('#pag_documento' + s).attr('readonly', false);
                                  calculo_pagos($('#pag_cantidad' + s));
                              }
                          },
                        error : function(xhr, status) {
                              //alert('El Cliente no tiene Documentos \n En esta opcion');
                               swal("Error!", "El Cliente no tiene Documentos. En esta opcion.!", "error");
                                  $('#pag_documento' + s).val('');
                                  $('#id_nota_credito' + s).val('0');
                                  $('#val_nt_cre' + s).val('');
                                  $('#pag_descripcion' + s).val('');
                                  $('#pag_forma' + s).val(0);
                                  $('#pag_tipo' + s).val(0);
                                  $('#pag_descripcion' + s).focus();
                                  $('#pag_cantidad' + s).val('0');
                                  $('#pag_cantidad' + s).attr('readonly', true);
                                  $('#pag_documento' + s).attr('readonly', false);
                                  $('#list_notas').html('');
                                  calculo_pagos($('#pag_cantidad' + s));
                        }
                      });  
                    } else {
                        $('#pag_documento' + s).attr('readonly', false);
                        $('#pag_documento' + s).val('');
                        $('#id_nota_credito' + s).val('0');
                        $('#val_nt_cre' + s).val('');
                        $('#list_notas').html('');
                    }
                } else {
                    //alert('Debe elejir un cliente');
                     swal("Error!", "Debe elejir un cliente.!", "error");
                    $('#pag_descripcion' + s).val('');
                    $('#pag_forma' + s).val(0);
                    $('#pag_tipo' + s).val(0);
                    $('#pag_cantidad' + s).attr('readonly', true);
                    $('#identificacion').focus();
                    $('#pag_documento' + s).val('');
                    $('#id_nota_credito' + s).val('0');
                    $('#val_nt_cre' + s).val('');
                    $('#list_notas').html('');
                }
            }
            

            function clona_fila(table,opc) {
                
                  var tr = $(table).find("tbody tr:last").clone();
                  tr.find("input,select").attr("name", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      return parts[1] + ++parts[2];
                  }).attr("id", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      x = ++parts[2];
                      this.lang = x;
                      var parent = $(this).parents();
                      $(parent[1]).css('background-color', 'transparent');
                      if (parts[1] == 'item') {
                          this.value = x;
                      } else if (parts[1] == 'pag_forma') {
                          this.value = '0';
                      } else if (parts[1] == 'pag_contado') {
                          this.value = '0';
                      }
                      else if (parts[1] == 'pag_descripcion') {
                          this.value = '';
                      }
                      else if(parts[1] == 'add'){
                       
                       this.type="hidden";
                      } 
                      else{
                          this.value ='0';
                      }
                      return parts[1] + x;
                  });
                  $(table).find("tbody tr:last").after(tr);
                  if(opc==0){
                    $('#count_detalle').val(x);
                  }else{
                    $('#count_pagos').val(x);
                    $('#pag_documento' + x).attr('readonly', false);
                    ultimo_pago(x);
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
                            cant = round($('#det_cantidad' + n).val(),dcc) + round(det_cantidad.value,dcc);
                            $('#det_cantidad' + n).val(cant.toFixed(dcc));
                            $('#det_vunit' + n).val(det_vunit.value);
                            $('#det_descuento_porcentaje' + n).val(det_descuento_porcentaje.value);
                            $('#inventario' + n).html(inventario.value);
                            
                        }
                    }
                }
                var cprec='<?php echo $cprec?>';
                var cdesc='<?php echo $cdesc?>';
                if(cprec==0){
                  r_precio='readonly';
                }else{
                  r_precio='';
                }
                if(cdesc==0){
                  r_descuento='readonly';
                }else{
                  r_descuento='';
                }
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center' hidden >"
                                        +
                                          i+
                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                          "<input type ='hidden' name='pro_ids"+i+"' id='pro_ids"+i+"' lang='"+i+"' value='"+pro_ids.value+"'/>"+
                                          
                                        "</td>"+
                                        "<td  ><input type ='text' class='form-control ' id='det_cod_producto"+i+"' name='det_cod_producto"+i+"' lang='"+i+"' value='"+det_cod_producto.value+"' readonly/></td>"+
                                        "<td ><input type ='text' class='form-control ' id='det_descripcion"+i+"' name='det_descripcion"+i+"' lang='"+i+"' value='"+det_descripcion.value+"' readonly/></td>"+
                                        "<td hidden><input type ='text' class='form-control ' id='det_unidad"+i+"' name='det_unidad"+i+"' lang='"+i+"' value='"+det_unidad.value+"' readonly/></td>"+
                                        "<td hidden><input type ='text' class='form-control ' id='inventario"+i+"' lang='"+i+"' align='right' value='"+inventario.value+"' readonly/></td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='det_cantidad"+i+"' name='det_cantidad"+i+"' lang='"+i+"' onchange='calculo()'  value='"+det_cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type ='text' size='7' style='text-align:right' id='det_vunit"+i+"' name='det_vunit"+i+"' onchange='calculo()' value='"+det_vunit.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' "+r_precio+" />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_descuento_porcentaje"+i+"' name='det_descuento_porcentaje"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' value='"+det_descuento_porcentaje.value+"' onkeyup='validar_decimal(this)' "+r_descuento+"/>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_descuento_moneda"+i+"' name='det_descuento_moneda"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+det_descuento_moneda.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_impuesto"+i+"' name='det_impuesto"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+$('#det_impuesto').val()+"' />"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_p_ice"+i+"' name='det_p_ice"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+det_p_ice.value+"' onkeyup='validar_decimal(this)'/>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_cod_ice"+i+"' name='det_cod_ice"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+det_cod_ice.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_val_ice"+i+"' name='det_val_ice"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+det_val_ice.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type ='text' size='7'  style='text-align:right' id='det_total"+i+"' name='det_total"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+det_total.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                det_descripcion.value = '';
                det_cod_producto.value = '';
                pro_aux.value = '';
                pro_ids.value = '';
                det_unidad.value = '';
                inventario.value = '';
                det_cantidad.value = '';
                det_vunit.value = '';
                det_impuesto.value = '';
                det_descuento_porcentaje.value = '';
                det_descuento_moneda.value = '';
                det_val_ice.value = '';
                det_cod_ice.value = '';
                det_p_ice.value = '';
                det_total.value = '';
                $('#det_cantidad').css({borderColor: ""});
                $('#det_cod_producto').focus();
                calculo();
                
            }

            function elimina_fila(obj, tbl,op) {
                
                  itm = $(tbl + ' .itm').length;
                  if (itm > 1) {
                      var parent = $(obj).parents();
                      $(parent[0]).remove();
                  } else {
                      //alert('No puede eliminar todas las filas');
                       swal("Error!", "No puede eliminar todas las filas.!", "error");
                  }
                  calculo_pagos();
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }

            //algoritmo digito verificado CC//
            // function verificar_cedula(obj) {
                
            //     i = obj.value.trim().length;
            //     c = obj.value.trim();

            //     var s = 0;
            //     if ($('#pasaporte').prop('checked') == false) {

            //       if($('#ped_ruc_cc_cliente').val().length==13 || $('#ped_ruc_cc_cliente').val().length==10){
            //         if (!isNaN(c)) {
            //             n = 0;
            //             while (n < 9) {
            //                 r = n % 2;
            //                 if (r == 0) {
            //                     m = 2;
            //                 } else {
            //                     m = 1;
            //                 }
            //                 ml = (c.substr(n, 1) * 1) * m;

            //                 if (ml > 9) {
            //                     ml = (ml.toString().substr(0, 1) * 1) + (ml.toString().substr(1, 1) * 1);
            //                 }
            //                 s += ml;
            //                 n++;
            //             }
            //             d = s % 10;
            //             if (d == 0) {
            //                 t = 0;
            //             } else {
            //                 t = 10 - d;
            //             }
            //             if (t.toString() == c.substr(9, 1) && (i==10 ||i==13)) {
            //                 traer_cliente(obj);
            //             } else {
            //                 alert('RUC/CC incorrecto');
            //                 $(obj).val('');
            //             }
            //         } else {
            //             traer_cliente(obj);
            //         }
            //       }else{
            //          traer_cliente(obj);
            //       }
            //     } else {
            //         //           alert('ESTA INGRESANDO UN PASAPORTE, SI NO ES CORRECTO FAVOR REVISAR');
            //         traer_cliente(obj);
            //     }

            // }


            function verificar_cedula(cedula) {
              
              //   if(obj != '9999999999'){
              //     i = obj.value.trim().length;
              //     c = obj.value.trim();
              // }else{
                
                
                c =cedula;
                i = 13;
                
                if (cedula=='9999999999999') {
                  $('#ped_ruc_cc_cliente').val('9999999999999');
                }
              // }

                //  i = obj.value.trim().length;
                // c = obj.value.trim();

        var s = 0;
        if ($('#pasaporte').prop('checked') == false) {


          if($('#ped_ruc_cc_cliente').val().length<10 || verificar_entero(cedula) != true || $('#ped_ruc_cc_cliente').val().length>13 )
            {
              
             // alert('RUC/CC incorrecto');
                 swal("Error!", "Cedula/RUC incorrecto.!", "error");
             
              limpiar_cliente();
            }

              else{

                          if($('#ped_ruc_cc_cliente').val().length==13 || $('#ped_ruc_cc_cliente').val().length==10){
                            if (!isNaN(c)) {
                                n = 0;
                                while (n < 9) {
                                    r = n % 2;
                                    if (r == 0) {
                                        m = 2;
                                    } else {
                                        m = 1;
                                    }
                                    ml = (c.substr(n, 1) * 1) * m;

                                    if (ml > 9) {
                                        ml = (ml.toString().substr(0, 1) * 1) + (ml.toString().substr(1, 1) * 1);
                                    }
                                    s += ml;
                                    n++;
                                }
                                d = s % 10;
                                if (d == 0) {
                                    t = 0;
                                } else {
                                    t = 10 - d;
                                }
                                if (t.toString() == c.substr(9, 1) && (i==10 ||i==13)) {
                                    traer_cliente(cedula);
                                } else {
                                    //alert('RUC/CC incorrecto');
                                       swal("Error!", "Cedula/RUC incorrecto.!", "error");
                                  limpiar_cliente();

                                }
                            } else {
                                 traer_cliente(cedula);
                            }
                          }else{
                              traer_cliente(cedula);
                          }
                  } 

              }

                else {
                 
                    traer_cliente(cedula);
                }
              
               

            }

            function buscar_clientes(){

              $.ajax({
                    beforeSend: function () {
                      // if ($('#identificacion').val().length == 0) {
                      //       alert('Ingrese dato');
                           
                      //       return false;
                      // }
                    },
                    url: base_url+"pedido/buscar_cliente/"+ped_ruc_cc_cliente.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){

                        $('#det_clientes').html(dt.lista);
                        $("#clientes").modal('show');
                        }else{
                          verificar_cedula($('#ped_ruc_cc_cliente').val());
                        }
                      },
                  error : function(xhr, status) {
                    
                          //alert('Cliente no existe \n Se creará uno nuevo');
                          $('#ped_ruc_cc_cliente').focus();
                           verificar_cedula($('#ped_ruc_cc_cliente').val());
                    }
                    }); 
      }

            function verificar_entero(cedula) {
             cedula = parseInt(cedula);
             if(Number.isInteger(cedula)) {
              return true;
             }else{
               return false;
             }
            }
            function pas(){
                 if ($('#pasaporte').prop('checked') == true) {
                    $('#pasaporte').attr('checked', false);
                    // $('#tipo_cliente').html('RUC/CI:');
                }else{
                  $('#pasaporte').attr('checked', true);
                  
                  $('#tipo_cliente').html('Pasaporte');
                  limpiar_cliente();

                   $('#ped_ruc_cc_cliente').focus();
                  if($('#ped_ruc_cc_cliente').val()=='9999999999999'){

                    limpiar_cliente();
                    }
                }
      }


            function limpiar_cliente(){

            $('#ped_ruc_cc_cliente').focus();
            $('#cli_id').val('0');
            $('#ped_ruc_cc_cliente').val('');
            $('#ped_nom_cliente').val('');
            $('#ped_tel_cliente').val('');
            $('#ped_dir_cliente').val('');
            $('#ped_email_cliente').val('');
            $('#ped_parroquia_cliente').val('');
            $('#tipo_cliente').val('0');
      }  

            function traer_emisor(){
               vl = $('#ped_local').val();
               
                $.ajax({
                  beforeSend: function () {
                      if ($('#ped_local').val().length == 0) {
                            //alert('Seleccione un local');
                            swal("Error!", "Seleccione un local.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"pedido/traer_emisor/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') { 
                        $('#bod_cli').val(dt.emi_cod_cli);
                      }else{
                        $('#bod_cli').val('0');
                      }
                    }
                  });
                validar_bodega();
            }

            function validar_bodega(){
              if( $('#bod_cli').val()!="0" && $('#cli_id').val()!="0"){
                if( $('#bod_cli').val()== $('#cli_id').val()){
                  //alert("Seleccione Cliente diferente al local");
                  swal("Error!", "Seleccione Cliente diferente al local.!", "error");
                            $('#ped_ruc_cc_cliente').focus();
                            $('#cli_id').val('0');
                            $('#ped_ruc_cc_cliente').val('');
                            $('#ped_nom_cliente').val('');
                            $('#ped_tel_cliente').val('');
                            $('#ped_dir_cliente').val('');
                            $('#ped_ciu_cliente').val('');
                            $('#ped_email_cliente').val('');
                            $('#ped_pais_cliente').val('');
                            $('#ped_parroquia_cliente').val('');
                            $('#tipo_cliente').val('0');
                }  
              }

            }
            function datos(){
            $('#ped_ruc_cc_cliente').focus();
            $('#tipo_cliente').html('RUC/CI:');
            limpiar_cliente();

            if ($('#pasaporte').prop('checked') == true) {
              $('#pasaporte').attr('checked', false);
            }

            }

            function load_producto(j) {
              
                vl = $('#det_cod_producto').val();
               
                $.ajax({
                  beforeSend: function () {
                      if ($('#det_cod_producto').val().length == 0) {
                            //alert('Ingrese un producto');
                            swal("Error!", "Ingrese un producto.!", "error");
                            return false;
                      }else if ($('#ped_local').val().length == 0) {
                            //alert('Seleccione un local');
                             swal("Error!", "Seleccione un local.!", "error");
                            $('#det_cod_producto').val('');
                            $('#det_cod_producto').focus();
                            return false;
                      }
                    },
                    url: base_url+"pedido/load_producto/"+vl+"/"+inven+"/"+ctr_inv+"/"+ped_local.value+"/0",
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        $('#det_cod_producto').val(dt.pro_codigo);
                        $('#det_descripcion').val(dt.pro_descripcion);
                        if(dt.pro_iva==''){
                          $('#det_impuesto').val('12');
                        }else{
                          $('#det_impuesto').val(dt.pro_iva);
                        }
                        $('#pro_aux').val(dt.pro_id);
                        $('#pro_ids').val(dt.ids);
                        $('#det_cantidad').val('1');
                        $('#det_unidad').val(dt.pro_unidad);
                       
                        
                        if($('#fprecio').val()=='1'){
                            if (dt.pro_precio == null) {
                                $('#det_vunit').val('0');
                              } else {
                                $('#det_vunit').val(parseFloat(dt.pro_precio).toFixed(dec));
                              }
                            }else{
                              if (dt.pro_precio2 == null) {
                                $('#det_vunit').val('0');
                              } else {
                                $('#det_vunit').val(parseFloat(dt.pro_precio2).toFixed(dec));
                              }
                        }

                        if (dt.pro_descuento == '') {
                            $('#det_descuento_porcentaje').val(0);
                        } else {
                            $('#det_descuento_porcentaje').val(parseFloat(dt.pro_descuento).toFixed(dec));
                        }
                        if (inven == 0) {
                            if (dt.inventario == '') {
                                $('#inventario').val('0');
                            } else {
                                $('#inventario').val(parseFloat(dt.inventario).toFixed(dcc));
                            }
                        }else{
                          
                          $('#inventario').val('0');
                        }
                         validar('#tbl_detalle','0');


                        // if (dt.det_p_ice== '') {
                            $('#det_val_ice').val('0');
                            $('#det_p_ice').val('0');
                        // } else {
                        //     $('#ice').val('0');
                        //     $('#det_p_ice').val(parseFloat(dt.det_p_ice).toFixed(dec));

                        // }

                        // if (dt.ice_cod == '') {
                            $('#det_cod_ice').val('0');
                        // } else {
                        //     $('#ice_cod').val(dt.ice_cod);
                        // }

                        $('#det_cod_producto').focus();
                      }else{
                        $('#det_cod_producto').val('');
                        $('#pro_referencia').val('');
                        $('#det_cantidad').val('');
                        $('#det_impuesto').val('0');
                        $('#pro_aux').val('');
                        $('#pro_ids').val('');
                        $('#det_vunit').val('0');
                        $('#det_descuento_porcentaje').val('0');
                        if (inven == 0) {
                            $('#inventario').val('0');
                        }
                        $('#det_val_ice').val('0');
                        $('#det_p_ice').val('0');
                        $('#det_cod_ice').val('0');
                        $('#det_cod_producto').focus();
                      }
                      calculo('1');
                    }
                  });
                
              }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }
            // function traer_forma(obj){
              
            // if(obj==1){
            //   n=1;
            // }else{
            //   n=obj.lang;
            // }

            //   $.ajax({
            //         beforeSend: function () {
            //           if ($('#pag_descripcion'+n).val().length == 0) {
            //                 alert('Ingrese Forma de pago');
            //                 return false;
            //           }
            //         },
            //         url: base_url+"pedido/traer_forma/"+$('#pag_descripcion'+n).val(),
            //         type: 'JSON',
            //         dataType: 'JSON',
            //         success: function (dt) {
            //             if(dt!=""){
            //               $('#pag_forma'+n).val(dt.fpg_id);
            //               $('#pag_descripcion'+n).val(dt.fpg_descripcion);
            //               $('#pag_contado'+n).html(dt.lista);
            //               $('#pag_tipo'+n).val(dt.fpg_tipo);

            //             }else{
            //               alert('Forma de pago no existe');
            //               $('#pag_forma'+n).val('');
            //               $('#pag_descripcion'+n).val('');
            //               $('#pag_tipo'+n).val('0');

            //               $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
            //             } 
            //            habilitar(obj); 
            //         },
            //         error : function(xhr, status) {
            //               alert('Forma de pago no existe');
            //               $('#pag_forma'+n).val('');
            //               $('#pag_descripcion'+n).val('');
            //               $('#pag_tipo'+n).val('0');
            //               $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
            //               habilitar(obj);
            //         }
            //         });    
            // }

            function traer_forma(obj){

            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
            objeto=obj;


              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                            //alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_forma/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){
                        


                          $('#pag_forma'+n).val(dt.fpg_id);
                          $('#pag_descripcion'+n).val(dt.fpg_descripcion);
                          $('#pag_tipo'+n).val(dt.fpg_tipo);

                          traer_plazo(obj);
                          
                        }else{

                          ///alert('Forma de pago no existe');
                            swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');

                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');
                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            }
            function traer_plazo(obj){
            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                           // alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_plazo/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){

                          $('#pag_plazo'+n).html(dt.lista)
                                                     
                        }else{
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");

                          $('#pag_plazo_aux').html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                         
                          $('#pag_plazo_aux').html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            }
             function habilitar(obj) {
                if (obj.lang != null) {
                    s = obj.lang;
                } else {
                    s = obj;
                }

                if ($('#pag_tipo' + s).val() == '1') {
                    $('#pag_cantidad' + s).attr('readonly', false);
                    $('#pag_plazo' + s).attr('disabled', false);
                    $('#pag_contado' + s).focus();
                } else if ($('#pag_tipo' + s).val() == '2') {
                    $('#pag_cantidad' + s).attr('readonly', false);
                    $('#pag_plazo' + s).attr('disabled', true);
                    $('#pag_cantidad' + s).focus();
                } else if ($('#pag_tipo' + s).val() == '3') {
                    $('#pag_plazo' + s).attr('disabled', true);
                    $('#pag_plazo' + s).val('0');
                    $('#pag_cantidad' + s).attr('readonly', false);
                    $('#pag_cantidad' + s).focus();
                } else if ($('#pag_tipo' + s).val() == '9') {
                    $('#pag_plazo' + s).attr('disabled', false);
                    $('#pag_cantidad' + s).attr('readonly', false);
                    $('#pag_plazo' + s).focus();
                } else if ($('#pag_tipo' + s).val() > '3') {
                    $('#pag_plazo' + s).attr('disabled', true);
                    $('#pag_plazo' + s).val('0');
                    $('#pag_cantidad' + s).attr('readonly', false);
                    $('#pag_cantidad' + s).focus();
                } else {
                    $('#pag_plazo' + s).attr('disabled', true);
                    $('#pag_plazo' + s).val('0');
                    $('#pag_cantidad' + s).attr('readonly', true);
                }
            } 


            function calculo_encabezado() {
                
                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

              
                        cnt = $('#det_cantidad').val().replace(',', '');

                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#det_vunit').val().replace(',', '');
                        d = $('#det_descuento_porcentaje').val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#det_p_ice').val().replace(',', '');
                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#det_descuento_moneda').val(dsc.toFixed(dec));
                        $('#det_total').val(vt.toFixed(dec));
                        ob = $('#det_impuesto').val();
                        val = $('#det_total').val().replace(',', '');
                        d = $('#det_descuento_moneda').val().replace(',', '');
                        $('#det_val_ice').val(pic.toFixed(dec));
                        
                                
            }     


            function calculo(obj) {
             

                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);
               
                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;
                var pic =0;

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                      
                        ob = 0;
                        val = 0;
                        val2 = 0;
                        d = 0;
                        cnt = 0;
                        pr = 0;
                        d = 0;
                        vtp = 0;
                        vt = 0;
                        ic = 0;
                        ib = 0;
                        dsc= 0;
                        uni=0;
                    } else {
                        cnt = $('#det_cantidad' + n).val().replace(',', '');

                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#det_vunit' + n).val().replace(',', '');
                        d = $('#det_descuento_porcentaje' + n).val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#det_p_ice' + n).val().replace(',', '');

                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#det_descuento_moneda' + n).val(dsc.toFixed(dec));
                        $('#det_total' + n).val(vt.toFixed(dec));
                        ob = $('#det_impuesto' + n).val();
                        val = $('#det_total' + n).val().replace(',', '');
                        d = $('#det_descuento_moneda' + n).val().replace(',', '');
                        $('#det_val_ice' + n).val(pic.toFixed(dec));
                       

                    }


                    tdsc = (round(tdsc,dec) * 1) + (round(d,dec) * 1);
                    tice = (round(tice,dec) * 1) + (round(pic,dec) * 1);

                    if (ob == '14') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 14 / 100);
                    }

                    if (ob == '12') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 12 / 100);
                    }
                    if (ob == '0') {
                        t0 = (round(t0,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'EX') {
                        tex = (round(tex,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'NO') {
                        tno = (round(tno,dec) * 1 + round(vt,dec) * 1);
                    }

                }

                sub = round(t12,dec) + round(t0,dec) + round(tex,dec) + round(tno,dec);
                sub1 = round(t0,dec) + round(tex,dec) + round(tno,dec);
                prop = $('#ped_propina').val().replace(',', '');
                gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1 + round(prop,dec) * 1);
                 // alert(t12+'--'+t0+'--'+tex+'--'+tno+'--'+sub+'--'+tdsc+'--'+tiva+'--'+tice+'--'+gtot);
                $('#ped_sbt12').val(t12.toFixed(dec));
                $('#ped_sbt0').val(t0.toFixed(dec));
                $('#subtotal_1').val(sub1.toFixed(dec));
                $('#ped_sbt_excento').val(tex.toFixed(dec));
                $('#ped_sbt_noiva').val(tno.toFixed(dec));
                $('#ped_sbt').val(sub.toFixed(dec));
                $('#ped_tdescuento').val(tdsc.toFixed(dec));
                $('#ped_iva12').val(tiva.toFixed(dec));
                $('#ped_ice').val(tice.toFixed(dec));
                $('#ped_total').val(gtot.toFixed(dec));

                pag_cantidad1.value = gtot.toFixed(dec);
                calculo_pagos();
            }     

            function calculo_pagos(obj){
              if(obj!=null){
                l=obj.lang;
              }
              tv=round($('#ped_total').val(),dec);
              var tr = $('#tbl_pagos').find("tbody tr:last");
              var a = tr.find("input").attr("lang");
              i = parseInt(a);
              var n = 0;
              var pg_ac=0;
                while(n<i){
                  n++;
                  if($('#pag_forma'+n).val()!=null){
                    cnt=$('#pag_cantidad'+n).val();
                    if(cnt.length==0){
                      cnt=0;
                    }else{
                      cnt=round(cnt,dec);
                    }
                    ///verificar valor nota credito
                    // if($('#pag_tipo'+n).val()==8){
                    //   val_nc=$('#val_nt_cre'+n).val();
                    //   if(val_nc.length==0){
                    //     val_nc=0;
                    //   }else{
                    //     val_nc=round(val_nc,dec);
                    //   }

                    //   if(cnt>val_nc){
                    //      alert('Pago es mayor al del documento $: ' + parseFloat(val_nc).toFixed(dec));
                    //         $('#pag_cantidad' + n).val('0');
                    //         $('#pag_cantidad' + n).focus();
                    //         cnt=0;
                    //   }
                    // }
                    pg_ac+=cnt;

                  }
                }
                falt=tv-pg_ac;
                if(falt<0){
                  falt=tv-pg_ac+round($('#pag_cantidad'+n).val(),dec);
                  $('#pag_cantidad'+n).val('0')
                  $('#faltante').val(falt.toFixed(dec));   
                }else{
                  $('#faltante').val(falt.toFixed(dec));  
                }
                
            }

            function save() {
                        if (ped_femision.value.length == 0) {
                            $("#ped_femision").css({borderColor: "red"});
                            $("#ped_femision").focus();
                            return false;
                        } else if (ped_local.value.length == 0) {
                            $("#ped_local").css({borderColor: "red"});
                            $("#ped_local").focus();
                            return false;
                        } else if (ped_vendedor.value.length == 0) {
                            $("#ped_vendedor").css({borderColor: "red"});
                            $("#ped_vendedor").focus();
                             swal("Error!", "Seleccione un vendedor!", "error");
                            return false;
                        } else if (ped_ruc_cc_cliente.value.length == 0) {
                            $("#ped_ruc_cc_cliente").css({borderColor: "red"});
                            $("#ped_ruc_cc_cliente").focus();
                            return false;
                        } else if (ped_nom_cliente .value.length == 0) {
                            $("#ped_nom_cliente ").css({borderColor: "red"});
                            $("#ped_nom_cliente ").focus();
                            return false;
                        } else if (ped_dir_cliente.value.length == 0) {
                            $("#ped_dir_cliente").css({borderColor: "red"});
                            $("#ped_dir_cliente").focus();
                            return false;
                        } else if (ped_tel_cliente.value.length == 0) {
                            $("#ped_tel_cliente").css({borderColor: "red"});
                            $("#ped_tel_cliente").focus();
                            return false;
                        } else if (ped_email_cliente.value.length == 0) {
                            $("#ped_email_cliente").css({borderColor: "red"});
                            $("#ped_email_cliente").focus();
                            return false;
                        } 
                        // else if (ped_parroquia_cliente.value.length == 0) {
                        //     $("#ped_parroquia_cliente").css({borderColor: "red"});
                        //     $("#ped_parroquia_cliente").focus();
                        //     return false;
                        // } 
                        else if (ped_ciu_cliente.value.length == 0) {
                            $("#ped_ciu_cliente").css({borderColor: "red"});
                            $("#ped_ciu_cliente").focus();
                            return false;
                        } 
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        if(a==null){
                          //alert("Ingrese Detalle");
                          swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#det_cod_producto' + n).val() != null) {
                                    if ($('#det_cod_producto' + n).val().length == 0) {
                                        $('#det_cod_producto' + n).css({borderColor: "red"});
                                        $('#det_cod_producto' + n).focus();
                                        return false;
                                    } else if ($('#det_cantidad' + n).val().length == 0 || parseFloat($('#det_cantidad' + n).val()) == 0) {
                                        $('#det_cantidad' + n).css({borderColor: "red"});
                                        $('#det_cantidad' + n).focus();
                                        return false;
                                    } else if ($('#det_descuento_porcentaje' + n).val().length == 0) {
                                        $('#det_descuento_porcentaje' + n).css({borderColor: "red"});
                                        $('#det_descuento_porcentaje' + n).focus();
                                        return false;
                                    } else if ($('#det_vunit' + n).val().length == 0 || parseFloat($('#det_vunit' + n).val()) == 0) {
                                        $('#det_vunit' + n).css({borderColor: "red"});
                                        $('#det_vunit' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }
                        if ($('#ped_total').val() > 200 && $('#ped_nom_cliente').val() == 'CONSUMIDOR FINAL') {
                            //alert('PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200');
                             swal("Error!", "PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200.!", "error");
                            return false;
                        }
                        if ($('#faltante').val() !=0 ) {
                            //alert('PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200');
                             swal("Error!", "Existe un faltante.!", "error");
                            $('#faltante').css({borderColor: "red"});
                            $('#faltante').focus();
                            return false;
                        }
                        if ($('#ped_vendedor').val() == '0' || $('#ped_vendedor').val() == '') {
                            $('#ped_vendedor').css({borderColor: "red"});
                            $('#ped_vendedor').focus();
                           //alert('Vendedor no existe');
                            swal("Error!", "Vendedor no existe.!", "error");
                            return false;
                        }

                        var tr2 = $('#tbl_pagos').find("tbody tr:last");
                        a2 = tr2.find("input").attr("lang");
                        i2 = parseInt(a2);
                        j = 0;
                        while (j < i2) {
                            j++;
                          if ($('#pag_cantidad' + j).val() !=null) {  
                                if ($('#pag_descripcion' + j).val().length == 0) {
                                    $('#pag_descripcion' + j).css({borderColor: "red"});
                                    $('#pag_descripcion' + j).focus();
                                    return false;
                                }
                                if ($('#pag_cantidad' + j).val() == 0) {
                                    $('#pag_cantidad' + j).css({borderColor: "red"});
                                    $('#pag_cantidad' + j).focus();
                                    return false;
                                }
                                if ($('#pag_tipo' + j).val() == '7' && $('#pag_documento' + j).val().length == 0) {
                                    $('#pag_documento' + j).focus();
                                    $('#pag_documento' + j).css({borderColor: "red"});
                                    return false;
                                }
                                
                              if ($('#pag_tipo' + j).val() == '9' ) {
                                if ($('#pag_plazo' + j).val() == '0' && ($('#pag_plazo' + j).attr('disabled') != 'disabled')) {
                                    $('#pag_plazo' + j).css({borderColor: "red"});
                                    $('#pag_plazo' + j).focus();
                                      swal("Error!", "Seleccione un plazo.!", "error");
                                    return false;
                                }
                                }
                                
                                
                            }


                        }

                        
                     $('#frm_save').submit();   
               }  

               

               function load_precios(){

                // var tr = $('#lista').find("tr:last");
                // a = tr.find("input").attr("lang");
                // i = parseInt(a);
                        
                // if(i==null){
                //     j=0;
                // }else{
                //     j=parseInt(a);
                // }
                // var precio1=0;
                // var precio2=0; 
                // if (j > 0) {
                //     n=0;
                //     while (n < j) {
                //       n++;
                        
                      if ($('#pro_aux').val() !=null) {
                        vl=$('#pro_aux').val();
                        

                        $.ajax({
                          url: base_url+"pedido/load_producto/"+vl+"/"+inven+"/"+ctr_inv+"/"+ped_local.value+"/"+n,
                          type: 'JSON',
                          dataType: 'JSON',
                          success: function (dt) {
                            if($('#fprecio').val()=='1'){
                              if (dt.pro_precio == null) {
                                $('#det_vunit').val('0');
                              } else {
                                $('#det_vunit').val(parseFloat(dt.pro_precio).toFixed(dec));
                              }
                            }else{
                              if (dt.pro_precio2 == null) {
                                $('#det_vunit').val('0');
                              } else {
                                  $('#det_vunit').val(parseFloat(dt.pro_precio2).toFixed(dec));
                              }
                            }
                            calculo();
                          }
                        });    
                      }
                //     }
                // }
            }
 
    </script>
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
    <datalist id="productos">
      <?php 
        if(!empty($cns_productos)){
          foreach ($cns_productos as $rst_pro) {
      ?>
        <option value="<?php echo $rst_pro->mp_d?>"><?php echo $rst_pro->mp_c .' '.$rst_pro->mp_d?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    <datalist id="list_formas">
      <?php 
        if(!empty($formas_pago)){
          foreach ($formas_pago as $fpg) {
      ?>
            <option value="<?php echo $fpg->fpg_descripcion?>"><?php echo $fpg->fpg_descripcion?></option>
      <?php
          }
        }
      ?>
    </datalist>

    <datalist id="list_notas">
    </datalist>
