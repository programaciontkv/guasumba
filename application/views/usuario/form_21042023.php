
<section class="content-header">
      <h1>
        Usuarios
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
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">
                
                <div class="form-group ">
                  <label>Rol:</label>
                  <select class="form-control" name="rol_id" id="rol_id">
                    <?php
                      if(!empty($roles)){
                        foreach ($roles as $rol) {
                    ?>
                        <option value="<?php echo $rol->rol_id?>"><?php echo $rol->rol_nombre?></option>  
                    <?php
                        }
                      }

                      if(validation_errors()==''){
                            $rl=$usuario->rol_id;
                      }else{
                            $est=set_value('rol_id');
                      }
                    ?>
                  </select>
                  <script type="text/javascript">
                      var rol='<?php echo $rl?>';
                      rol_id.value=rol;
                    </script>
                </div>

                <div class="form-group <?php if(form_error('usu_person')!=''){echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="usu_person" placeholder="Nombre" value="<?php if(validation_errors()!=''){ echo set_value('usu_person');}else{ echo $usuario->usu_person;}?>">
                  <?php echo form_error("usu_person","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('usu_login')!=''){echo 'has-error';}?>">
                  <label>Usuario:</label>
                  <input type="text" class="form-control" name="usu_login" id="usu_login" placeholder="Usuario" value="<?php if(validation_errors()!=''){ echo set_value('usu_login');}else{echo  $usuario->usu_login;}?>">
                  <?php echo form_error("usu_login","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('usu_imagen')!=''){echo 'has-error';}?>">
                  <label>Imagen:</label>
                </br>
                <?php 
                      if(validation_errors()==''){
                          $imagen=$usuario->usu_imagen;
                        }else{
                          $imagen=set_value('usu_imagen');
                        }
                ?>
                  <img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$imagen ?>" width="250px" height="200px" class="form-control">
                  <input type="text" hidden name="usu_imagen" id="usu_imagen" value="<?php if(validation_errors()!=''){ echo set_value('usu_imagen');}else{ echo $usuario->usu_imagen;}?>" onchange="uploadAjax()">
                  <input type="file" name="direccion" id="direccion"  onchange="uploadAjax()">
                  <?php echo form_error("usu_imagen","<span class='help-block'>","</span>");?>
                </div>
                
                <div class="form-group <?php if(form_error('usu_pass')!=''){echo 'has-error';}?>">
                  <label>Contraseña:</label>
                  <input type="password" class="form-control" name="usu_pass" placeholder="Contraseña">
                  <?php echo form_error("usu_pass","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('usu_pass2')!=''){echo 'has-error';}?>">
                  <label>Confirmar Contraseña:</label>
                  <input type="password" class="form-control" name="usu_pass2" placeholder="Confirmar Contraseña" value="<?php echo set_value('usu_pass');?>">
                  <?php echo form_error("usu_pass2","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">

                  <label>Estado</label>
                  <select name="usu_estado" id="usu_estado" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $est=$usuario->usu_estado;
                        }else{
                          $est=set_value('usu_estado');
                        }    
                    if(!empty($estados)){
                      foreach ($estados as $estado) {
                    ?>
                    <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <script type="text/javascript">
                      var est='<?php echo $est?>';
                      usu_estado.value=est;
                    </script>
                </div>
                <input type="hidden" class="form-control" name="usu_id" value="<?php echo $usuario->usu_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'usuario/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
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
                data.append('usu_imagen', file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#usu_login').val().length == 0) {
                            alert('Ingrese el usuario');
                            return false;
                        }
                        if ($('#direccion').val().length == 0) {
                            alert('Ingrese una imagen');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_imagen/usu_imagen/"+usu_login.value,
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#usu_imagen').val(dat[1]);
                            $('#fotografia').attr('src', base_url+'imagenes/'+dat[1]);
                        } else {
                            alert(dat[1]);
                            $('#usu_imagen').val('');
                            $('#direccion').val('');
                            $('#fotografia').prop('src','');
                        }
                    }
                })
            }
    </script>