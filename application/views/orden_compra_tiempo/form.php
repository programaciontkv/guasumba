
<section class="content-header">
      <h1>
        Configuracion de Tiempo de Caducidad de Orden Compra
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
                <div class="form-group <?php if(form_error('tie_tipo')!=''){ echo 'has-error';}?> ">
                  <label>Tiempo:</label>
                  <select name="tie_tipo"  id="tie_tipo" class="form-control">
                    <option value="">SELECCIONE</option>
                    <option value="1">DIAS</option>
                    <option value="2">MESES</option>
                    
                  </select>
                  <?php 
                    if(validation_errors()==''){
                      $tip=$tiempo->tie_tipo;
                    }else{
                      $tip=set_value('tie_tipo');
                    }
                  ?>
                  <script type="text/javascript">
                    var tip='<?php echo $tip;?>';
                    tie_tipo.value=tip;
                  </script>
                  <?php echo form_error("tie_tipo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tie_cantidad')!=''){ echo 'has-error';}?> ">
                  <label>Cantidad:</label>
                  <input type="numbrer" class="form-control numerico" name="tie_cantidad" value="<?php if(validation_errors()!=''){ echo set_value('tie_cantidad');}else{ echo $tiempo->tie_cantidad;}?>">
                  <?php echo form_error("tie_cantidad","<span class='help-block'>","</span>");?>
                </div>   
                
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="tie_estado"  id="tie_estado" class="form-control">
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
                      $est=$tiempo->tie_estado;
                    }else{
                      $est=set_value('tie_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    tie_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="tie_id" value="<?php echo $tiempo->tie_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>orden_compra_tiempo/<?php echo $opc_id?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
    
      <!-- /.row -->
    </section>
