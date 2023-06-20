
<section class="content-header">
      <!-- <h1>
        Categorias y Clasificaciones
      </h1> -->
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
                <div class="form-group <?php if(form_error('tps_tipo')!=''){ echo 'has-error';}?>">
                  <label>Categoria (Nivel 1 ) :</label>
                  <select name="tps_tipo"  id="tps_tipo" class="form-control" onchange="traer_familias()">
                    <option value="">SELECCIONE</option>
                    <?php
                        if(!empty($categorias)){
                          foreach ($categorias as $categoria) {
                    ?>
                        <option value='<?php echo $categoria->cat_id?>'><?php echo $categoria->cat_descripcion?></option>
                    <?php        
                          }
                        }
                    ?>
                  </select>
                  <?php
                        if(validation_errors()!='0'){
                              $cat=$tipo->tps_tipo;
                            }else{
                              $cat=set_value('tps_tipo');
                        }
                   ?>
                   <script type="text/javascript">
                    

                      var cat='<?php echo $cat?>';
                      tps_tipo.value=cat;
                    
                  </script>     
                  <?php echo form_error("tps_tipo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group">
                  <label>Sub niveles:</label>
                  
                              <?php
                              if(validation_errors()){ 
                                if(set_value('tps_relacion')=='1'){
                                      $sel_ct1='checked';
                                      $sel_ct2='';
                                      $hid_ct1='hidden';
                                    }else{
                                      $sel_ct1='';
                                      $sel_ct2='checked';
                                      $hid_ct1='';
                                    }
                              }else{  
                                if($tipo->tps_relacion=='1'){
                                      $sel_ct1='checked';
                                      $sel_ct2='';
                                      $hid_ct1='hidden';
                                    }else{
                                      $sel_ct1='';
                                      $sel_ct2='checked';
                                      $hid_ct1='';
                                    }
                              }      
                              ?>
                  </br>            
                  <label>Familia (Sub Nivel 1 ):</label> 
                  <input type="radio" name="tps_relacion" id="tps_relacion1" <?php echo $sel_ct1?>  value='1'>
                  <br>
                  <label>Tipo (Sub Nivel 2 ):</label>
                  <input type="radio" name="tps_relacion" id="tps_relacion2" <?php echo $sel_ct2?> value='2'>
                            
                </div>
                <div id="familia" class="form-group <?php if(form_error('tps_familia')!=''){ echo 'has-error';}?>" <?php echo $hid_ct1?> >
                  <label>Familia</label>
                  <select name="tps_familia" id="tps_familia" class="form-control">
                    <option value="0">SELECCIONE</option>
                    <?php
                        if(!empty($familias)){
                          foreach ($familias as $familia) {
                            if($familia->tps_id==$tipo->tps_familia){
                              $select='selected';
                            }else{
                              $select='';
                            }
                    ?>
                        <option value='<?php echo $familia->tps_id?>' <?php echo $select?>><?php echo $familia->tps_nombre?></option>
                    <?php        
                          }
                        }
                    ?>
                  </select>
                  <?php
                        if(validation_errors()!=''){
                              $fam=$tipo->tps_familia;
                            }else{
                              $fam=set_value('tps_familia');
                        }
                  ?>      
                  <script type="text/javascript">
                    window.onload = function () {
                               
                      var fam='<?php echo $fam?>';
                      tps_familia.value=fam;
                    }
                  </script>
                  <?php echo form_error("tps_familia","<span class='help-block'>","</span>");?>
                </div>
                
                <div class="form-group <?php if(form_error('tps_nombre')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" onchange="g_siglas()" id="tps_nombre" name="tps_nombre" value="<?php if(validation_errors()!=''){ echo set_value('tps_nombre');}else{ echo $tipo->tps_nombre;}?>">
                  <?php echo form_error("tps_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tps_siglas')!=''){ echo 'has-error';}?>">
                  <label>Siglas:</label>
                  <input readonly type="text" class="form-control" id="tps_siglas" name="tps_siglas" value="<?php if(validation_errors()!=''){ echo set_value('tps_siglas');}else{ echo $tipo->tps_siglas;}?>">
                  <?php echo form_error("tps_siglas","<span class='help-block'>","</span>");?>
                </div>
                <!-- <div class="form-group <?php if(form_error('tps_densidad')!=''){ echo 'has-error';}?>">
                  <label>Densidad:</label>
                  <input type="text" class="form-control decimal" name="tps_densidad" value="<?php if(validation_errors()!=''){ echo set_value('tps_densidad');}else{ echo $tipo->tps_densidad;}?>">
                  <?php echo form_error("tps_densidad","<span class='help-block'>","</span>");?>
                </div> -->
                <div class="form-group ">
                              <label>Estado</label>
                              <select name="tps_estado" id="tps_estado" class="form-control">
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
                                if(validation_errors()!='0'){
                                  $est=$tipo->tps_estado;
                                }else{
                                  $est=set_value('tps_estado');
                                }
                              ?>
                              <script type="text/javascript">
                                window.onload = function () {
                                     var est='<?php echo $est;?>';

                                      tps_estado.value=est;
                                    }
                                
                              
                            </script>
                            </div>
                <input type="hidden" class="form-control" name="tps_id" value="<?php echo $tipo->tps_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'tipo/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';
      
      function g_siglas(){
        var dato = $('#tps_nombre').val();
            dat0 = dato.substr(0,3);
        $('#tps_siglas').val(dato.toLowerCase());
      }

      function mostrar(){
        if($('#tps_relacion1').prop('checked')==true){
          $('#familia').prop('hidden',true);
        }else{
          $('#familia').prop('hidden',false);
        }
      }

      function traer_familias() {
        uri=base_url+'tipo/traer_familias/'+$('#tps_tipo').val();
        $.ajax({
                url: uri,
                type: 'POST',
                success: function(dt){
                   $('#tps_familia').html(dt);
                } 
              });
      }
    </script>