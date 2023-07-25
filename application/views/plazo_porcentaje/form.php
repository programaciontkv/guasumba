<section class="content-header">
    <h1>
        Plazos y porcentaje
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

                        <div class="form-group <?php if(form_error('btr_tipo')!=''){ echo 'has-error';}?> ">
                            <label>Tipo:</label>
                            
                            <select name="pcv_tipo" id="pcv_tipo" class="form-control" >
                                <option value="">SELECCIONE</option>
                                <option value="1">TIEMPO</option>
                                <option value="2">VALOR</option>

                            </select>

                            <?php

                        if(validation_errors() == '' ){
                              $tipo=$plazo_porcentaje->pcv_tipo;
                            }else{
                              $tipo=set_value('pcv_tipo');
                             
                        }
                       
                     ?>
                            <div class="form-group <?php if(form_error('pcv_desde')!=''){ echo 'has-error';}?> ">
                                <label>Desde:</label>
                                <input type="text" class="form-control numerico" name="pcv_desde"
                                    value="<?php if(validation_errors()!=''){ echo set_value('pcv_desde');}else{ echo $plazo_porcentaje->pcv_desde;}?>">
                                <?php echo form_error("pcv_desde","<span class='help-block'>","</span>");?>
                            </div>

                            <div class="form-group <?php if(form_error('pcv_hasta')!=''){ echo 'has-error';}?> ">
                                <label>Hasta:</label>
                                <input type="text" class="form-control numerico" name="pcv_hasta"
                                    value="<?php if(validation_errors()!=''){ echo set_value('pcv_hasta');}else{ echo $plazo_porcentaje->pcv_hasta;}?>">
                                <?php echo form_error("pcv_hasta","<span class='help-block'>","</span>");?>
                            </div>

                            <div class="form-group <?php if(form_error('pcv_porcentaje')!=''){ echo 'has-error';}?> ">
                                <label>Porcentaje:</label>
                                <input type="text" class="form-control numerico" name="pcv_porcentaje"
                                    value="<?php if(validation_errors()!=''){ echo set_value('pcv_porcentaje');}else{ echo $plazo_porcentaje->pcv_porcentaje;}?>">
                                <?php echo form_error("pcv_porcentaje","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php if(form_error('pcv_categoria')!=''){ echo 'has-error';}?> ">
                                <label>Categoria:</label>
                                <input type="text" class="form-control " name="pcv_categoria"
                                    value="<?php if(validation_errors()!=''){ echo set_value('pcv_categoria');}else{ echo $plazo_porcentaje->pcv_categoria;}?>">
                                <?php echo form_error("pcv_categoria","<span class='help-block'>","</span>");?>
                            </div>
                            <script type="text/javascript">
                            window.onload = function() {
                                var tipo = '<?php echo $tipo?>';
                                pcv_tipo.value = tipo;
                               
                            }
                            </script>
                            <?php echo form_error("pcv_tipo","<span class='help-block'>","</span>");?>
                        </div>

                        <div class="form-group ">
                            <label>Estado</label>
                            <select name="pcv_estado" id="pcv_estado" class="form-control">
                                <?php
                    if(!empty($estados)){
                      foreach ($estados as $estado) {
                    ?>
                                <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?>
                                </option>
                                <?php
                      }
                    }
                  ?>
                            </select>
                            <?php 
                    if(validation_errors()!=' '){
                      $est=$plazo_porcentaje->pcv_estado;
                    }else{
                      $est=set_value('pcv_estado');
                    }
                  ?>
                            <script type="text/javascript">
                            var est = '<?php echo $est;?>';
                            pcv_estado.value = est;
                            </script>
                        </div>

                        <input type="hidden" class="form-control" name="pcv_id"
                            value="<?php echo $plazo_porcentaje->pcv_id?>">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="<?php echo base_url().'bancos_tarjetas/';echo $opc_id ?>"
                                class="btn btn-default">Cancelar</a>
                        </div>

                </form>
            </div>

        </div>
        <!-- /.row -->
</section>