
<section class="content-header">
      <h1>
        Empresa
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
          if($usu_sesion != 1){
            $readonly="readonly";
            $disabled="disabled";
          }else{
             $readonly="";
             $disabled="";
          }
          ?>
          <div class="box box-primary">
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">
                <div class="form-group <?php if(form_error('emp_identificacion')!=''){ echo 'has-error';}?> col-md-6">
                  <label>RUC:</label>
                  <input <?php echo $readonly ?> type="text" class="form-control" name="emp_identificacion" id="emp_identificacion" value="<?php if(validation_errors()!=''){ echo set_value('emp_identificacion');}else{ echo $empresa->emp_identificacion;}?>" onchange="verificar_cedula(this)">
                  <?php echo form_error("emp_identificacion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emp_nombre')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Razon Social:</label>
                  <input <?php echo $readonly ?> type="text" class="form-control" name="emp_nombre" value="<?php if(validation_errors()!=''){ echo set_value('emp_nombre');}else{ echo $empresa->emp_nombre;}?>">
                  <?php echo form_error("emp_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emp_pais')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Pais:</label>
                  <input type="text" class="form-control" name="emp_pais" value="<?php if(validation_errors()!=''){ echo set_value('emp_pais');}else{ echo  $empresa->emp_pais;}?>">
                  <?php echo form_error("emp_pais","<span class='help-block'>","</span>");?>
                </div>  
                <div class="form-group <?php if(form_error('emp_ciudad')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Ciudad:</label>
                  <input type="text" class="form-control" name="emp_ciudad" value="<?php if(validation_errors()!=''){ echo set_value('emp_ciudad');}else{ echo $empresa->emp_ciudad;}?>">
                  <?php echo form_error("emp_ciudad","<span class='help-block'>","</span>");?>
                </div>  
                <div class="form-group <?php if(form_error('emp_direccion')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Direccion Matriz:</label>
                  <input type="text" class="form-control" name="emp_direccion" value="<?php if(validation_errors()!=''){ echo set_value('emp_direccion');}else{ echo $empresa->emp_direccion;}?>">
                  <?php echo form_error("emp_direccion","<span class='help-block'>","</span>");?>
                </div>  
                <div class="form-group <?php if(form_error('emp_telefono')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Telefono:</label>
                  <input type="text" class="form-control" name="emp_telefono" value="<?php if(validation_errors()!=''){ echo set_value('emp_telefono');}else{ echo $empresa->emp_telefono;}?>">
                  <?php echo form_error("emp_telefono","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emp_email')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="emp_email" value="<?php if(validation_errors()!=''){ echo set_value('emp_email');}else{ echo $empresa->emp_email;}?>">
                  <?php echo form_error("emp_email","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emp_contribuyente_especial')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Codigo Tributario:</label>
                  <input type="text" class="form-control numerico" name="emp_contribuyente_especial" value="<?php if(validation_errors()!=''){ echo set_value('emp_contribuyente_especial');}else{ echo $empresa->emp_contribuyente_especial;}?>">
                  <?php echo form_error("emp_contribuyente_especial","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group col-md-6">
                  <label>Obligado a lleva Contabilidad:</label>
                  <br>
                  <?php
                    if($empresa->emp_obligado_llevar_contabilidad=='SI'){
                      $chk1='checked';
                      $chk2='';
                    }else{
                      $chk1='';
                      $chk2='checked';
                    }
                  ?>
                  <input type="radio" name="emp_obligado_llevar_contabilidad" value="SI" <?php echo $chk1?>>SI
                  <input type="radio" name="emp_obligado_llevar_contabilidad" value="NO" <?php echo $chk2?>>NO
                </div> 
                <div class="form-group col-md-6 <?php if(form_error('emp_leyenda_sri')!=''){ echo 'has-error';}?>">
                  <label>Leyenda SRI:</label>
                  <br>
                  <?php
                    if($empresa->emp_leyenda=='0'){
                      $chk_l1='checked';
                      $chk_l2='';
                    } else {
                      $chk_l1='';
                      $chk_l2='checked';
                    }
                  ?>
                  <input type="radio" name="emp_leyenda" id="emp_leyenda2" value="1" <?php echo $chk_l2?> onclick="habilitar()">Si
                  <input type="radio" name="emp_leyenda" id="emp_leyenda1" value="0" <?php echo $chk_l1?> onclick="habilitar()">No
                  <textarea id="emp_leyenda_sri" name="emp_leyenda_sri" class="form-control"  onkeydown="return enter(event)" style="height:80px!important ;" disabled><?php echo $empresa->emp_leyenda_sri ?> </textarea>
                  <?php echo form_error("emp_leyenda_sri","<span class='help-block'>","</span>");?>
                </div>
                
                
                <div class="form-group <?php if(form_error('emp_logo')!=''){ echo 'has-error';}?> col-md-6">
                  <label>Logo:</label>
                </br>
                <?php 
                      if(validation_errors()==''){
                          $imagen=$empresa->emp_logo;
                        }else{
                          $imagen=set_value('emp_logo');
                        }
                ?>
                  <img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$imagen ?>" width="250px" height="200px" class="form-control">
                  <input type="text" hidden name="emp_logo" id="emp_logo" value="<?php if(validation_errors()!=''){ echo set_value('emp_logo');}else{ echo $empresa->emp_logo;}?>" onchange="uploadAjax()">
                  <input type="file" name="direccion" id="direccion"  onchange="uploadAjax()">
                  <?php echo form_error("emp_logo","<span class='help-block'>","</span>");?>
                </div>

                <div class="form-group col-md-6">
                  <label>Estado</label>
                  <select <?php echo $disabled ?>  name="emp_estado"  id="emp_estado" class="form-control">
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
                      $est=$empresa->emp_estado;
                    }else{
                      $est=set_value('emp_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    emp_estado.value=est;
                  </script>
                </div>  
                <input type="hidden" class="form-control" name="emp_id" value="<?php echo $empresa->emp_id?>">
              <div class="box-footer col-md-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>empresa" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
<script type="text/javascript">
      var base_url='<?php echo base_url();?>'
      function uploadAjax() {
                var nom = 'direccion';
                var inputFileImage = document.getElementById(nom);
                var file = inputFileImage.files[0];
                var data = new FormData();
                data.append('emp_logo', file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#emp_identificacion').val().length == 0) {
                            alert('Ingrese el RUC de la Empresa');
                            return false;
                        }
                        if ($('#direccion').val().length == 0) {
                            alert('Ingrese una imagen');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_imagen/emp_logo/"+emp_identificacion.value,
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#emp_logo').val(dat[1]);
                            $('#fotografia').attr('src', base_url+'imagenes/'+dat[1]);
                        } else {
                            alert(dat[1]);
                            $('#emp_logo').val('');
                            $('#direccion').val('');
                            $('#fotografia').prop('src','');
                        }
                    }
                })
            }

            function verificar_cedula(obj) {
                
                i = obj.value.trim().length;
                c = obj.value.trim();

                var s = 0;
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
                        
                        } else {
                            alert('RUC/CC incorrecto');
                            $(obj).val('');
                        }
                    } else {
                        
                    }
            }
    function habilitar(){
      if($('#emp_leyenda1').prop('checked')==false){
        $('#emp_leyenda_sri').attr('disabled',false);  
      }else{
        $('#emp_leyenda_sri').attr('disabled',true);
        $('#emp_leyenda_sri').val('');  
      }
      
    }
            
 </script>