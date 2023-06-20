<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Clientes y Proveedores
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
          <div class="box box-primary" >
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">
                <div class="panel panel-default col-sm-12">
                  <table class="table col-sm-12">
                      <tr>
                          <td>
                            <div class="form-group <?php if(form_error('opc_nombre')!=''){ echo 'has-error';}?>">
                              <label>Fecha Ingreso:</label>
                              <input type="date" class="form-control" name="cli_fecha" value="<?php if(validation_errors()!=''){ echo set_value('cli_fecha');}else{ echo $cliente->cli_fecha;}?>">
                              <?php echo form_error("cli_fecha","<span class='help-block'>","</span>");?>
                            </div>
                          </td>
                          <td>  
                            <div class="form-group <?php if(form_error('cli_tipo')!=''){ echo 'has-error';}?>">
                              <label>Tipo:</label>
                                <select name="cli_tipo" id="cli_tipo" class="form-control"  onchange="cod()">
                                <option value="">SELECCIONE</option>
                                <option value="0">CLIENTE</option>
                                <option value="1">PROVEEDOR</option>
                                <option value="2">CLIENTE Y PROVEEDOR</option>
                              </select>
                              <?php 
                                  if(validation_errors()==''){
                                    $tip=$cliente->cli_tipo;
                                  }else{
                                    $tip=set_value('cli_tipo');
                                  }
                                ?>
                              <?php echo form_error("cli_tipo","<span class='help-block'>","</span>");?>
                            </div>
                          </td>
                          <td>  
                            <div class="form-group ">
                              <?php 
                             


                              if(validation_errors()==''){
                                if($cliente->cli_categoria=='0'){
                                      $sel_ct1='checked';
                                      $sel_ct2='';
                                      $hid_ct1='hidden';
                                      $hid_ct2='';
                                    }else{
                                      $sel_ct1='';
                                      $sel_ct2='checked';
                                      $hid_ct1='';
                                      $hid_ct2='hidden';
                                    }
                              }else{
                                if(set_value('cli_categoria')=='0'){
                                      $sel_ct1='checked';
                                      $sel_ct2='';
                                      $hid_ct1='hidden';
                                      $hid_ct2='';
                                    }else{
                                      $sel_ct1='';
                                      $sel_ct2='checked';
                                      $hid_ct1='';
                                      $hid_ct2='hidden';
                                    }

                              }
                              ?>
                              <label>Natural:</label> 
                              <input type="radio" name="cli_categoria" id="cli_categoria1" <?php echo $sel_ct1?> onclick="cod()" value='0'>
                            </br>
                              <label>Juridica:</label>
                              <input type="radio" name="cli_categoria" id="cli_categoria2" <?php echo $sel_ct2?> onclick="cod()" value='1'>
                            </div>
                          </td>
                          <td hidden>    
                            <div class="form-group  <?php if(form_error('cli_codigo')!=''){ echo 'has-error';}?>">
                              <label>Codigo:</label>
                              <input type="text" class="form-control" name="cli_codigo" id="cli_codigo" value="<?php if(validation_errors()!=''){ echo set_value('cli_codigo') ;}else{ echo  $cliente->cli_codigo;}?>" readonly>
                              <?php echo form_error("cli_codigo","<span class='help-block'>","</span>");?>
                            </div>
                          </td>
                          <td hidden>    
                            <div class="form-group ">
                              <label>Estado</label>
                              <select name="cli_estado" id="cli_estado" class="form-control">
                                 <?php
                                  if(!empty($estados)){
                                    foreach ($estados as $estado) {
                                  ?>
                                  <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
                                  <?php
                                    }
                                  }
                                ?>
                              </select>
                              <?php 
                                  if(validation_errors()==''){
                                    $est=$cliente->cli_estado;
                                  }else{
                                    $est=set_value('cli_estado');
                                  }
                                ?>
                              <script type="text/javascript">
                              var est='<?php echo $est;?>';
                              cli_estado.value=est;
                            </script>
                            </div>
                          </td>
                          <td>   
                            <div class="form-group ">
                              <label>Cliente:</label>
                              <select name="cli_tipo_cliente" id="cli_tipo_cliente" class="form-control">
                                <option value="0">NACIONAL</option>
                                <option value="1">EXTRANJERO</option>
                              </select>
                              <?php 
                                  if(validation_errors()==''){
                                    $tp_cl=$cliente->cli_tipo_cliente;
                                  }else{
                                    $tp_cl=set_value('cli_tipo_cliente');
                                  }
                                ?>
                              <script type="text/javascript">
                              var tp_cl='<?php echo $tp_cl;?>';
                              cli_tipo_cliente.value=tp_cl;
                              </script>
                            </div>
                          </td>
                      </tr>                          
                  </table>
                </div>
                <div class="panel panel-default col-sm-12">
                <div class="panel-heading">DATOS GENERALES</div>
                        <table class="table col-sm-12">
                            <tr>
                              <td <?php echo $hid_ct2?> id="cli_apellidos">       
                                  <div class="form-group <?php if(form_error('cli_apellidos')!=''){ echo 'has-error';}?>" >
                                    <label>Apellidos:</label>
                                    <input type="text" class="form-control" name="cli_apellidos"  value="<?php if(validation_errors()!=''){ echo set_value('cli_apellidos');}else{ echo $cliente->cli_apellidos;}?>">
                                    <?php echo form_error("cli_apellidos","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                              <td <?php echo $hid_ct2?> id="cli_nombres">      
                                  <div class="form-group <?php if(form_error('cli_nombres')!=''){ echo 'has-error';}?>" >
                                    <label>Nombres:</label>
                                    <input type="text" class="form-control" name="cli_nombres"  value="<?php if(validation_errors()!=''){ echo set_value('cli_nombres') ;}else{ echo  $cliente->cli_nombres;}?>">
                                    <?php echo form_error("cli_nombres","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                              <td> 
                                  <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?>">
                                    <label>Razon Social</label>
                                    <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social') ;}else{ echo   $cliente->cli_raz_social;}?>">
                                    <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                              <td <?php echo $hid_ct1?> id="cli_nom_comercial"> 
                                  <div class="form-group <?php if(form_error('cli_nom_comercial')!=''){ echo 'has-error';}?>" >
                                    <label>Nombre Comercial</label>
                                    <input type="text" class="form-control" name="cli_nom_comercial"  value="<?php if(validation_errors()!=''){ echo set_value('cli_nom_comercial');}else{ echo  $cliente->cli_nom_comercial;}?>">
                                    <?php echo form_error("cli_nom_comercial","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                              <td> 
                                  <div class="form-group <?php if(form_error('cli_ced_ruc')!=''){ echo 'has-error';}?>">
                                    <label>Cedula/RUC</label>
                                    <input type="text" class="form-control" name="cli_ced_ruc" id="cli_ced_ruc" value="<?php if(validation_errors()!=''){ echo set_value('cli_ced_ruc');}else{ echo $cliente->cli_ced_ruc;}?>" onchange="verificar_cedula(this)">
                                    <?php echo form_error("cli_ced_ruc","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                              
                            </tr> 
                        </table>
                </div> 
               <!--  <div class="panel panel-default col-sm-6">
                        <div class="panel-heading">VENDEDOR</div>
                          <table class="table col-sm-12">
                            <tr>
                              <td>      
                                  <div class="form-group <?php if(form_error('cli_nacionalidad')!=''){ echo 'has-error';}?>">
                                    <label>Vendedor:</label>
                                    <select name="cli_nacionalidad" id="cli_nacionalidad" class="form-control">
                                      <option value="">SELECCIONE</option>
                                      <?php
                                          if(!empty($vendedores)){
                                            foreach ($vendedores as $vendedor) {
                                              if($vendedor->vnd_nombre==$cliente->cli_nacionalidad){
                                                $select='selected';
                                              }else{
                                                $select='';
                                              }
                                      ?>
                                          <option value='<?php echo $vendedor->vnd_nombre?>'><?php echo $vendedor->vnd_nombre?></option>
                                      <?php        
                                            }
                                          }
                                      ?>
                                    </select>
                                    <?php 
                                      if(validation_errors()==''){
                                        $nac=$cliente->cli_nacionalidad;
                                      }else{
                                        $nac=set_value('cli_nacionalidad');
                                      }
                                    ?>
                                  <script type="text/javascript">
                                  var nac='<?php echo $nac;?>';
                                  cli_nacionalidad.value=nac;
                                  </script>
                                    <?php echo form_error("cli_nacionalidad","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                               </tr> 
                          </table>
                        </div> -->
                        <!-- <div class="panel panel-default col-sm-6">
                        <div class="panel-heading">REFERENCIA BANCARIA</div>
                          <table class="table col-sm-12">
                            <tr>
                              <td>      
                                  <div class="form-group">
                                    <label>Cuenta:</label>
                                    <input type="text" class="form-control" name="cli_refb_tip_cuenta2" value="<?php if(validation_errors()!=''){ echo set_value('cli_refb_tip_cuenta2');}else{ echo $cliente->cli_refb_tip_cuenta2;}?>">
                                  </div>
                                </td>
                               </tr> 
                          </table>
                        </div> -->
                        <div class="panel panel-default col-sm-12">
                        <div class="panel-heading">DIRECCION DE RESIDENCIA</div>
                          <table class="table col-sm-12">
                            <tr>
                               <td>      
                                  <div class="form-group <?php if(form_error('cli_pais')!=''){ echo 'has-error';}?>">
                                    <label>Pais:</label>
                                    <input type="text" class="form-control" name="cli_pais" value="<?php if(validation_errors()!=''){ echo set_value('cli_pais');}else{ echo $cliente->cli_pais;}?>">
                                    <?php echo form_error("cli_pais","<span class='help-block'>","</span>");?>
                                  </div>
                                </td> 
                                <!-- <td>      
                                  <div class="form-group <?php if(form_error('cli_provincia')!=''){ echo 'has-error';}?>">
                                    <label>Provincia:</label>
                                    <input type="text" class="form-control" name="cli_provincia" value="<?php if(validation_errors()!=''){ echo set_value('cli_provincia');}else{ echo $cliente->cli_provincia;}?>">
                                    <?php echo form_error("cli_provincia","<span class='help-block'>","</span>");?>
                                  </div>
                                </td> -->
                                <td>      
                                  <div class="form-group <?php if(form_error('cli_canton')!=''){ echo 'has-error';}?>">
                                    <label>Ciudad:</label>
                                    <input type="text" class="form-control" name="cli_canton" value="<?php if(validation_errors()!=''){ echo set_value('cli_canton');}else{ echo  $cliente->cli_canton;}?>">
                                    <?php echo form_error("cli_canton","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                 <td>      
                                  <div class="form-group <?php if(form_error('cli_parroquia')!=''){ echo 'has-error';}?>">
                                    <label>Sector:</label>
                                    <input type="text" class="form-control" name="cli_parroquia" value="<?php if(validation_errors()!=''){ echo set_value('cli_parroquia');}else{ echo $cliente->cli_parroquia;}?>">
                                    <?php echo form_error("cli_parroquia","<span class='help-block'>","</span>");?>
                                  </div>
                                </td> 
                              </tr>
                              <tr>
                              
                                <td>      
                                  <div width="50" class="form-group  <?php if(form_error('cli_calle_prin')!=''){ echo 'has-error';}?>">
                                    <label>Calle principal:</label>
                                    <input type="text" class="form-control"  name="cli_calle_prin" value="<?php if(validation_errors()!=''){ echo set_value('cli_calle_prin');}else{ echo $cliente->cli_calle_prin;}?>">
                                    <?php echo form_error("cli_calle_prin","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <!-- <td>      
                                  <div class="form-group">
                                    <label>Numeracion:</label>
                                    <input type="text" class="form-control" name="cli_numeracion" value="<?php if(validation_errors()!=''){ echo set_value('cli_numeracion');}else{ echo $cliente->cli_numeracion;}?>">
                                  </div>
                                </td>
                                <td>      
                                  <div class="form-group">
                                    <label>Calle Secundaria:</label>
                                    <input type="text" class="form-control" name="cli_calle_sec" value="<?php if(validation_errors()!=''){ echo set_value('cli_calle_sec');}else{ echo $cliente->cli_calle_sec;}?>">
                                  </div>
                                </td>
                                <td>      
                                  <div class="form-group">
                                    <label>Referencia:</label>
                                    <input type="text" class="form-control" name="cli_referencia" value="<?php if(validation_errors()!=''){ echo set_value('cli_referencia');}else{ echo $cliente->cli_referencia;}?>">
                                  </div>
                                </td> -->
                               </tr>  
                               
                          </table>
                        </div> 
                        <div class="panel panel-default col-sm-12">
                        <div class="panel-heading">Contactos</div>
                        <table class="table col-sm-12">
                        <tr>
                                <td>      
                                  <div class="form-group <?php if(form_error('cli_telefono')!=''){ echo 'has-error';}?>">
                                    <label>Telefono:</label>
                                    <input type="text" class="form-control" name="cli_telefono" value="<?php if(validation_errors()!=''){ echo set_value('cli_telefono');}else{ echo $cliente->cli_telefono;}?>">
                                    <?php echo form_error("cli_telefono","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>      
                                  <div class="form-group <?php if(form_error('cli_email')!=''){ echo 'has-error';}?>">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" id="cli_email" name="cli_email" value="<?php if(validation_errors()!=''){ echo set_value('cli_email');}else{ echo $cliente->cli_email;}?>">
                                    <?php echo form_error("cli_email","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>      
                                  <div class="form-group <?php if(form_error('cli_rep_email')!=''){ echo 'has-error';}?>">
                                    <label>Email cobranza:</label>
                                    <input type="email" class="form-control" id="cli_rep_email" name="cli_rep_email" value="<?php if(validation_errors()!=''){ echo set_value('cli_rep_email');}else{ echo $cliente->cli_rep_email;}?>">
                                    <?php echo form_error("cli_rep_email","<span class='help-block'>","</span>");?>
                                    <input type="checkbox" id='copia' name='copia' onclick="extra()" /><label>Copiar email</label> 
                                  </div>
                                </td>
                               </tr>  
                        </table>
                        </div> 



              <input type="hidden" class="form-control" name="cli_id" value="<?php echo $cliente->cli_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>cliente/<?php echo $opc_id?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         </div>
        </div>
      <!-- /.row -->
    </section>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';
      var tip='<?php echo $tip;?>';
      var id ='<?php echo $cliente->cli_id?>';
      cli_tipo.value=tip;
      window.onload = function () {
      cod();
      categoria();
      if(id.length !=0){
        var email = $('#cli_email').val();
        var email_co = $('#cli_rep_email').val();
        if (email==email_co) {
        $('#copia').attr('checked', true);
        $('#cli_rep_email').attr('readonly', true);
        }
      }else{

      }

      
    }
    function extra(){

        if ($('#copia').prop('checked') == true) {
          var email = $('#cli_email').val();
          $('#cli_rep_email').val(email);
          $('#cli_rep_email').attr('readonly', true);

          }else{
          $('#cli_rep_email').val('');
          $('#cli_rep_email').attr('readonly', false);

          }

      }
      function cod() {
                if ($('#cli_tipo').val() == '0') {
                    a = 'C';
                } else if ($('#cli_tipo').val() == '1') {
                    a = 'P';
                } else if ($('#cli_tipo').val() == '2') {
                    a = 'CP';
                }
                if ($('#cli_categoria1').prop('checked') == true) {
                    b = 'N';
                }
                else if ($('#cli_categoria2').prop('checked') == true) {
                    b = 'J';
                }
                if ($('#cli_tipo').val() == "") {
                    $('#cli_codigo').val('');
                } else {

                    codigo(a, b);
                }
            }

            function codigo(a, b) {
                uri=base_url+'cliente/crear_codigo/'+a+'/'+b;
                $.ajax({
                url: uri,
                type: 'POST',
                success: function(dt){
                   $('#cli_codigo').val(dt);
                } 
              });
              categoria();
            }

            function categoria() {
                if ($('#cli_categoria1').prop('checked') == true) {
                    $('#cli_nombres').prop('hidden',true);
                    $('#cli_apellidos').prop('hidden',true);
                    $('#cli_nom_comercial').prop('hidden',true);
                    // $('#referencia_bancaria').hide();
                }
                else if ($('#cli_categoria2').prop('checked') == true) {
                    $('#cli_nombres').prop('hidden',true);
                    $('#cli_apellidos').prop('hidden',true);
                    $('#cli_nom_comercial').prop('hidden',false);
                    // $('#referencia_bancaria').prop('hidden',true);
                }  
            }

            function verificar_cedula(obj) {
                
                i = obj.value.trim().length;
                c = obj.value.trim();

                var s = 0;
                var vf= 0;
                
                    if (!isNaN(c) && c!='9999999999') {
                        if((i==10 || i==13) && parseFloat(c.substr(2, 1))<6){
                            ///ruc natural o cedula
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
                                load_cliente(obj);
                            } else {
                                vf=1;
                            }
                        
                        }else if(c.substr(2, 1)=='6' && (i==13)){
                            ////ruc digito 6 publicas
                            digitos=Array(3,2,7,6,5,4,3,2);
                            n = 0;
                            while (n < 7) {
                                ml = (c.substr(n, 1) * 1) * digitos[n];
                                s += ml;
                                n++;
                            }

                            dv=s%11;
                            if(dv==0){
                                t=0;
                            }else{
                                t=11-dv;
                            }
                            if (t.toString() == c.substr(8, 1) ) {
                                load_cliente(obj);
                            } else {
                                vf=1;
                            }
                        }else if(c.substr(2, 1)=='9' && (i==13)){
                            ////ruc digito 9 extranjeras o sin cedula
                            digitos=Array(4,3,2,7,6,5,4,3,2);
                            n = 0;
                            while (n < 9) {
                                ml = (c.substr(n, 1) * 1) * digitos[n];
                                s += ml;
                                n++;
                            }
                            dv=s%11;
                            if(dv==0){
                                t=0;
                            }else{
                                t=11-dv;
                            }
                            
                            if (t.toString() == c.substr(9, 1) ) {
                           
                            } else {
                                vf=1;
                            }

                        }else{
                            vf=1;
                        }
                        if(vf==1){
                            // alert('Cedula/RUC incorrecto');
                             swal("Error!", "Cedula/RUC incorrecto.!", "error");

                            $(obj).val('');
                        }
                    } 
                
            }

    </script>