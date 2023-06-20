
<section class="content-header">
      <h1>
        Bancos, Tarjetas y Plazos
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

                
                <div class="form-group <?php if(form_error('btr_descripcion')!=''){ echo 'has-error';}?> ">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" name="btr_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('btr_descripcion');}else{ echo $banco_tarjeta->btr_descripcion;}?>">
                  <?php echo form_error("btr_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('btr_tipo')!=''){ echo 'has-error';}?> ">
                  <label>Tipo:</label>
                  <?php

                        if(validation_errors() != ' ' ){
                              $tipo=$banco_tarjeta->btr_tipo;
                            }else{
                              $tipo=set_value('btr_tipo');
                        }
                     ?>   
                  <select name="btr_tipo" id="btr_tipo" class="form-control" onchange="mostrar()">
                    <option value="">SELECCIONE</option>
                    <option value="0">BANCO</option>
                    <option value="1">TARJETA</option>
                    <option value="2">PLAZO</option>
                  </select>
                  <div id="div_dias" class="form-group <?php if(form_error('btr_dias')!=''){ echo 'has-error';}?> " style="display: none;">
                  <label>Valor en Dias:</label>
                  <input type="text" class="form-control numerico" name="btr_dias" value="<?php if(validation_errors()!=''){ echo set_value('btr_dias');}else{ echo $banco_tarjeta->btr_dias;}?>">
                  <?php echo form_error("btr_dias","<span class='help-block'>","</span>");?>
                </div>
                  <script type="text/javascript">
                    window.onload = function () {
                       var tipo='<?php echo $tipo?>';
                      btr_tipo.value=tipo;
                    }
                     
                  </script>
                  <?php echo form_error("btr_tipo","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('btr_tipo')!=''){ echo 'has-error';}?> ">
                  <label>Forma de Pago:</label>
                  <?php
                        if(validation_errors()!=' '){
                              $forma=$banco_tarjeta->btr_forma;
                            }else{
                              $forma=set_value('btr_forma');
                        }
                     ?>   
                  <select name="btr_forma" id="btr_forma" class="form-control">
                    <option value="0">SELECCIONE</option>
                    <option value="1">TARJETA DE CREDITO</option>
                    <option value="2">TARJETA DE DEBITO</option>
                    <option value="3">CHEQUE</option>
                    <option value="4">EFECTIVO</option>
                    <option value="5">CERTIFICADOS</option>
                    <option value="6">TRANSFERENCIA</option>
                    <option value="7">RETENCION</option>
                    <option value="8">NOTA CREDITO</option>
                    <option value="9">CREDITO</option>
                  </select>
                  <script type="text/javascript">
                      var forma='<?php echo $forma?>';

                      btr_forma.value=forma;
                  </script>
                  <?php echo form_error("btr_forma","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="btr_estado"  id="btr_estado" class="form-control">
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
                    if(validation_errors()!=' '){
                      $est=$banco_tarjeta->btr_estado;
                    }else{
                      $est=set_value('btr_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    btr_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="btr_id" value="<?php echo $banco_tarjeta->btr_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'bancos_tarjetas/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
<script>
 function mostrar(){
    if($('#btr_tipo').val()=='2'){
      $('#div_dias').prop('style','display:block');
    }
 } 
</script>