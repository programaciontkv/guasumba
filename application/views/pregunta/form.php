
<section class="content-header">
      <h1>
        Preguntas Frecuentes
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
                <div class="form-group <?php if(form_error('pre_menu')!=''){ echo 'has-error';}?> ">
                  <label>Menu:</label>
                   <input type="text" class="form-control upper" name="pre_menu" id="pre_menu" value="<?php if(validation_errors()!=''){ echo set_value('pre_menu');}else{ echo $pregunta->pre_menu;}?>">
                  
                  
                <?php echo form_error("pre_menu","<span class='help-block'>","</span>");?> 
                </div>
                <div class="form-group <?php if(form_error('pre_seccion')!=''){ echo 'has-error';}?> ">
                  <label>Seccion:</label>
                   <input type="text" class="form-control  upper" name="pre_seccion" value="<?php if(validation_errors()!=''){ echo set_value('pre_seccion');}else{echo $pregunta->pre_seccion;}?>">
                    
                  <?php echo form_error("pre_seccion","<span class='help-block'>","</span>");?>
                </div> 
                
                <div class="form-group <?php if(form_error('pre_archivo')!=''){ echo 'has-error';}?> ">
                  <label>Archivo (.pdf 128M max.):</label>
                  <input type="file" name="direccion" id="direccion"  onchange="uploadAjax()">
                  <input type="hidden" name="pre_archivo" id="pre_archivo"  onchange="uploadAjax()" value="<?php if(validation_errors()!=''){ echo set_value('pre_archivo');}else{echo $pregunta->pre_archivo;}?>">
                  
                  <?php echo form_error("pre_archivo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('pre_video')!=''){ echo 'has-error';}?> ">
                  <label>URL video:</label>
                  <input type="text" class="form-control" name="pre_video" id="pre_video" value="<?php if(validation_errors()!=''){ echo set_value('pre_video');} else{ echo $pregunta->pre_video;}?>" onkeyup="validar()">
                  <?php echo form_error("pre_video","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="pre_estado"  id="pre_estado" class="form-control">
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
                      $est=$pregunta->pre_estado;
                    }else{
                      $est=set_value('pre_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    ayu_estado.value=est;
                  </script>
                </div>
            </tfoot>
                <input type="hidden" class="form-control" name="pre_id" value="<?php echo $pregunta->pre_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>pregunta" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <style type="text/css">
      #pre_video{
        text-transform: none !important;
      }
    </style>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';
      
      function uploadAjax() {
                var nom = 'direccion';
                var inputFileImage = document.getElementById(nom);
                var file = inputFileImage.files[0];
                var data = new FormData();
                data.append('pre_archivo', file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#pre_menu').val().length == 0) {
                            alert('Ingrese el codigo');
                            return false;
                        }
                        if ($('#direccion').val().length == 0) {
                            alert('Ingrese un archivo pdf');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_pdf/pre_archivo/"+pre_menu.value.replace('.','-'),
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#pre_archivo').val(dat[1]);

                        } else {
                            alert(dat[1]);
                            $('#pre_archivo').val('');
                            $('#direccion').val('');
                        }
                    }
                })
            }
      
    </script>    
  