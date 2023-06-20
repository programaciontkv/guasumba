
<section class="content-header">
     
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
                <div class="form-group <?php if(form_error('por_siglas')!=''){ echo 'has-error';}?> ">
                  <label>Tipo:</label>
                  <?php
                        if(validation_errors()==''){
                              $tipo=$impuesto->por_siglas;
                            }else{
                              $tipo=set_value('por_siglas');
                        }
                     ?>   
                  <select name="por_siglas" id="por_siglas" class="form-control">
                    <option value="">SELECCIONE</option>
                    <option value="IR">IMPUESTO A LA RENTA</option>
                    <option value="IV">IVA</option>
                    <option value="IC">ICE</option>
                    <option value="IBR">IRBPN</option>
                    <option value="ID">SALIDA DE DIVISAS</option>
                  </select>
                  <script type="text/javascript">
                      var tipo='<?php echo $tipo?>';
                      por_siglas.value=tipo;
                  </script>
                  <?php echo form_error("por_siglas","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('por_codigo')!=''){ echo 'has-error';}?> ">
                  <label>Codigo:</label>
                  <input type="text" class="form-control" name="por_codigo" value="<?php if(validation_errors()!=''){ echo set_value('por_codigo');}else{ echo $impuesto->por_codigo;}?>">
                  <?php echo form_error("por_codigo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('por_cod_ats')!=''){ echo 'has-error';}?> ">
                  <label>Codigo ATS:</label>
                  <input type="text" class="form-control" name="por_cod_ats" value="<?php if(validation_errors()!=''){ echo set_value('por_cod_ats');}else{ echo $impuesto->por_cod_ats;}?>">
                  <?php echo form_error("por_cod_ats","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('por_descripcion')!=''){ echo 'has-error';}?> ">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" name="por_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('por_descripcion');}else{ echo $impuesto->por_descripcion;}?>">
                  <?php echo form_error("por_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('cta_id')!=''){ echo 'has-error';}?> ">
                  <label>Codigo Cuenta:</label>
                  <input type="text" class="form-control" name="pln_codigo" id="pln_codigo" value="<?php if(validation_errors()!=''){ echo set_value('pln_codigo');}else{ echo $impuesto->pln_codigo;}?>" list="list_cuentas" onchange="load_cuenta()">
                  <input type="text" name="cta_id" id="cta_id" value="<?php if(validation_errors()!=''){ echo set_value('cta_id');}else{ echo $impuesto->cta_id;}?>" hidden>
                  <?php echo form_error("cta_id","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Descripcion Cuenta:</label>
                  <input type="text" class="form-control" name="pln_descripcion" id="pln_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('pln_descripcion');}else{ echo $impuesto->pln_descripcion;}?>" readonly>
                </div>
                <div class="form-group <?php if(form_error('por_descripcion')!=''){ echo 'has-error';}?> ">
                  <label>Porcentje:</label>
                  <input type="text" class="form-control" name="por_porcentage" value="<?php if(validation_errors()!=''){ echo set_value('por_porcentage');}else{ echo $impuesto->por_porcentage;}?>">
                  <?php echo form_error("por_porcentage","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="por_estado"  id="por_estado" class="form-control">
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
                    if(validation_errors()!=" "){
                      $est=$impuesto->por_estado;
                    }else{
                      $est=set_value('por_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    por_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="por_id" value="<?php echo $impuesto->por_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>impuesto/<?php echo $opc_id?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
<datalist id="list_cuentas">
  <?php
  if(!empty($cuentas)){
    foreach ($cuentas as $cuenta) {
  ?>
    <option value="<?php echo $cuenta->pln_id?>"><?php echo $cuenta->pln_codigo.' '.$cuenta->pln_descripcion?></option>
  <?php
    }
  }
  ?>
</datalist>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';

      function load_cuenta(){
        uri=base_url+'impuesto/traer_cuenta/'+$('#pln_codigo').val();
        $.ajax({
                url: uri,
                type: 'POST',
                success: function(dt){
                  dat=dt.split('&&');
                   $('#cta_id').val(dat[0]);
                   $('#pln_codigo').val(dat[1]);
                   $('#pln_descripcion').val(dat[2]);
                } 
              });
      }
    </script>