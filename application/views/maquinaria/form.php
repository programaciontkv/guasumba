
<section class="content-header">
      <h1>
        Maquinarias
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
                  <label>Tipo</label>
                  <select name="ids" id="ids" class="form-control">
                    <option value="">SELECCIONE</option>
                    <?php
                        if(!empty($tipos)){
                          foreach ($tipos as $tipo) {
                    ?>
                        <option value='<?php echo $tipo->ids?>'><?php echo $tipo->tipo?></option>
                    <?php        
                          }
                        }
                    ?>
                  </select>
                  <?php 
                    if(empty(validation_errors())){
                      $tip=$maquinaria->ids;
                    }else{
                      $tip=set_value('ids');
                    }
                  ?>
                  <script type="text/javascript">
                  var tip='<?php echo $tip;?>';
                  ids.value=tip;
                  </script>
                </div>

                <div class="form-group <?php echo !empty(form_error('maq_a'))? 'has-error' : '';?>">
                  <label>Codigo:</label>
                  <input type="text" class="form-control" name="maq_a" value="<?php echo !empty(validation_errors())? set_value('maq_a') :  $maquinaria->maq_a;?>">
                  <?php echo form_error("maq_a","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php echo !empty(form_error('maq_b'))? 'has-error' : '';?>">
                  <label>Referencia:</label>
                  <input type="text" class="form-control" name="maq_b" value="<?php echo !empty(validation_errors())? set_value('maq_b') :  $maquinaria->maq_b;?>">
                  <?php echo form_error("maq_b","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php echo !empty(form_error('maq_c'))? 'has-error' : '';?>">
                  <label>Velocidad:</label>
                  <input type="text" class="form-control" name="maq_c" value="<?php echo !empty(validation_errors())? set_value('maq_c') :  $maquinaria->maq_c;?>">
                  <?php echo form_error("maq_c","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php echo !empty(form_error('maq_d'))? 'has-error' : '';?>">
                  <label>Capacidad:</label>
                  <input type="text" class="form-control" name="maq_d" value="<?php echo !empty(validation_errors())? set_value('maq_d') :  $maquinaria->maq_d;?>">
                  <?php echo form_error("maq_d","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                              <label>Estado</label>
                              <select name="maq_estado" id="maq_estado" class="form-control">
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
                                if(empty(validation_errors())){
                                  $est=$maquinaria->maq_estado;
                                }else{
                                  $est=set_value('maq_estado');
                                }
                              ?>
                              <script type="text/javascript">
                              var est='<?php echo $est;?>';
                              maq_estado.value=est;
                            </script>
                </div>
                <input type="hidden" class="form-control" name="id" value="<?php echo $maquinaria->id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>maquinaria" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>