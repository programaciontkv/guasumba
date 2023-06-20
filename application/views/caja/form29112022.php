
<section class="content-header">
      <h1>
        Cajas
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
                <div class="form-group <?php if(form_error('emi_id')!=''){ echo 'has-error';}?> ">
                  <label>Punto Emision:</label>
                  <select name="emi_id"  id="emi_id" class="form-control">
                    <option value="">SELECCIONE</option>
                    <?php
                    if(!empty($emisores)){
                      foreach ($emisores as $emisor) {
                    ?>
                    <option value="<?php echo $emisor->emi_id?>"><?php echo $emisor->emp_nombre.' '.$emisor->emi_nombre?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <?php 
                    if(validation_errors()==''){
                      $emi=$caja->emi_id;
                    }else{
                      $emi=set_value('emi_id');
                    }
                  ?>
                  <script type="text/javascript">
                    var emi='<?php echo $emi;?>';
                    emi_id.value=emi;
                  </script>
                  <?php echo form_error("emi_id","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('cja_codigo')!=''){ echo 'has-error';}?> ">
                  <label>Codigo Caja:</label>
                  <input type="text" class="form-control numerico" name="cja_codigo" value="<?php if(validation_errors()!=''){ echo set_value('cja_codigo');}else{ echo $caja->cja_codigo;}?>">
                  <?php echo form_error("cja_codigo","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('cja_nombre')!=''){ echo 'has-error';}?> ">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="cja_nombre" value="<?php if(validation_errors()!=''){ echo set_value('cja_nombre');}else{ echo $caja->cja_nombre;}?>">
                  <?php echo form_error("cja_nombre","<span class='help-block'>","</span>");?>
                </div>
               <div class="form-group <?php if(form_error('cja_sec_factura')!=''){ echo 'has-error';}?> ">
                  <label>Secuencial Inicia Factura:</label>
                  <input type="text" class="form-control numerico" name="cja_sec_factura" value="<?php if(validation_errors()!=''){ echo set_value('cja_sec_factura');}else{ echo   $caja->cja_sec_factura;}?>">
                  <?php echo form_error("cja_sec_factura","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('cja_sec_nota_credito')!=''){ echo 'has-error';}?> ">
                  <label>Secuencial Inicia Nota Credito:</label>
                  <input type="text" class="form-control numerico" name="cja_sec_nota_credito" value="<?php if(validation_errors()!=''){ echo set_value('cja_sec_nota_credito');}else{ echo $caja->cja_sec_nota_credito;}?>">
                  <?php echo form_error("cja_sec_nota_credito","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('cja_sec_nota_debito')!=''){ echo 'has-error';}?> ">
                  <label>Secuencial Inicia Nota Debito:</label>
                  <input type="text" class="form-control numerico" name="cja_sec_nota_debito" value="<?php if(validation_errors()!=''){ echo set_value('cja_sec_nota_debito');}else{ echo $caja->cja_sec_nota_debito;}?>">
                  <?php echo form_error("cja_sec_nota_debito","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('cja_sec_guia')!=''){ echo 'has-error';}?> ">
                  <label>Secuencial Inicia Guia:</label>
                  <input type="text" class="form-control numerico" name="cja_sec_guia" value="<?php if(validation_errors()!=''){ echo set_value('cja_sec_guia');}else{ echo $caja->cja_sec_guia;}?>">
                  <?php echo form_error("cja_sec_guia","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('cja_sec_retencion')!=''){ echo 'has-error';}?> ">
                  <label>Secuencial Inicia Retencioan:</label>
                  <input type="text" class="form-control numerico" name="cja_sec_retencion" value="<?php if(validation_errors()!=''){ echo set_value('cja_sec_retencion');}else{ echo $caja->cja_sec_retencion;}?>">
                  <?php echo form_error("cja_sec_retencion","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="cja_estado"  id="cja_estado" class="form-control">
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
                      $est=$caja->cja_estado;
                    }else{
                      $est=set_value('cja_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    cja_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="cja_id" value="<?php echo $caja->cja_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>caja" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
    
      <!-- /.row -->
    </section>
