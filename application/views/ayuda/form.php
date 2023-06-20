
<section class="content-header">
      <h1>
        Manual de Ayuda e Instrucciones
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
                <div class="form-group <?php if(form_error('ayu_codigo')!=''){ echo 'has-error';}?> ">
                  <label>Menu:</label>
                  <!-- <input type="text" class="form-control upper" name="ayu_codigo" id="ayu_codigo" value="<?php if(validation_errors()!=''){ echo set_value('ayu_codigo');}else{ echo $ayuda->ayu_codigo;}?>">-->
                  
                  <select class="form-control" id="ayu_codigo" name="ayu_codigo" onchange="traer_opciones(this)">
                    <option value="0">SELCCIONE</option>
                    <?php
                    foreach ($submenus as $sbm) {
                    ?>
                    <option value="<?php echo $sbm->sbm_id?>"><?php echo $sbm->sbm_nombre?></option>
                    <?php  
                    }
                    ?>   
                  </select>
                  <script type="text/javascript">
                    var sbm='<?php echo $ayuda->ayu_codigo?>';
                    ayu_codigo.value=sbm;
                  </script>
                <?php echo form_error("ayu_codigo","<span class='help-block'>","</span>");?> 
                </div>
                <div class="form-group <?php if(form_error('ayu_descripcion')!=''){ echo 'has-error';}?> ">
                  <label>Seccion:</label>
                  <!-- <input type="text" class="form-control  upper" name="ayu_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('ayu_descripcion');}else{echo $ayuda->ayu_descripcion;}?>">-->
                    <select class="form-control" id="ayu_descripcion" name="ayu_descripcion">
                    <option value="0">SELCCIONE</option>
                    <?php
                    if(!empty($opciones)){
                      foreach ($opciones as $opc) {
                      ?>
                      <option value="<?php echo $opc->opc_id?>"><?php echo $opc->opc_nombre?></option>
                      <?php  
                      }
                    }
                    ?>
                </select>
                <script type="text/javascript">
                  var opc='<?php echo $ayuda->ayu_descripcion?>';
                  ayu_descripcion.value=opc;
                </script>
                  <?php echo form_error("ayu_descripcion","<span class='help-block'>","</span>");?>
                </div> 
                
                <div class="form-group <?php if(form_error('ayu_archivo')!=''){ echo 'has-error';}?> ">
                  <label>Archivo (.pdf 128M max.):</label>
                  <input type="file" name="direccion" id="direccion"  onchange="uploadAjax()">
                  <input type="hidden" name="ayu_archivo" id="ayu_archivo"  onchange="uploadAjax()" value="<?php if(validation_errors()!=''){ echo set_value('ayu_archivo');}else{echo $ayuda->ayu_archivo;}?>">
                  
                  <?php echo form_error("ayu_archivo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('ayu_video')!=''){ echo 'has-error';}?> ">
                  <label>URL video:</label>
                  <input type="text" class="form-control" name="ayu_video" id="ayu_video" value="<?php if(validation_errors()!=''){ echo set_value('ayu_video');} else{ echo $ayuda->ayu_video;}?>" onkeyup="validar()">
                  <?php echo form_error("ayu_video","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="ayu_estado"  id="ayu_estado" class="form-control">
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
                      $est=$ayuda->ayu_estado;
                    }else{
                      $est=set_value('ayu_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    ayu_estado.value=est;
                  </script>
                </div>
            </tfoot>
                <input type="hidden" class="form-control" name="ayu_id" value="<?php echo $ayuda->ayu_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>ayuda" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <style type="text/css">
      #ayu_video{
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
                data.append('ayu_archivo', file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#ayu_codigo').val().length == 0) {
                            alert('Ingrese el codigo');
                            return false;
                        }
                        if ($('#direccion').val().length == 0) {
                            alert('Ingrese un archivo pdf');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_pdf/ayu_archivo/"+ayu_codigo.value.replace('.','-'),
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#ayu_archivo').val(dat[1]);

                        } else {
                            alert(dat[1]);
                            $('#ayu_archivo').val('');
                            $('#direccion').val('');
                        }
                    }
                })
            }
      function traer_opciones(obj){
        $.ajax({
                    beforeSend: function () {
                      if ($('#ayu_codigo').val()== "0") {
                            alert('Seleccione un menu');
                            return false;
                        }
                        
                    },
                    url: base_url+"ayuda/traer_opciones/"+ayu_codigo.value,
                    type: 'POST',
                    success: function (dt) {
                      $('#ayu_descripcion').html(dt);
                    },
                    error: function (dt) {
                      $('#ayu_descripcion').html("<option value='0'>SELECCIONE</option>");
                    }
                })
      }      
    </script>    
  