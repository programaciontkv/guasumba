
<section class="content-header">
      <h1>
        Cuentas
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
                <div class="form-group <?php if(form_error('pln_codigo')!=''){ echo 'has-error';}?>">
                  <label>Codigo:</label>
                  <input type="text" class="form-control decimal" name="pln_codigo" id="pln_codigo" value="<?php if(validation_errors()!=''){ echo set_value('pln_codigo');}else{ echo $cuenta->pln_codigo;}?>" onchange="verificar_cuenta()">
                  <?php echo form_error("pln_codigo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('pln_descripcion')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="pln_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('pln_descripcion');}else{ echo $cuenta->pln_descripcion;}?>">
                  <?php echo form_error("pln_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                    <label>Tipo</label>
                    <select name="pln_tipo" id="pln_tipo" class="form-control">
                      <option value="1">MOVIMIENTO</option>
                      <option value="0">SUMATORIA</option>
                    </select>
                    <?php 
                      if(validation_errors()==''){
                        $tip=$cuenta->pln_tipo;
                      }else{
                        $tip=set_value('pln_tipo');
                      }
                    ?>
                    <script type="text/javascript">
                    var tip='<?php echo $tip;?>';
                    pln_tipo.value=tip;
                  </script>
                </div>
                <div class="form-group ">
                    <label>Operacion</label>
                    <select name="pln_operacion" id="pln_operacion" class="form-control">
                      <option value="0">SUMA</option>
                      <option value="1">RESTA</option>
                    </select>
                    <?php 
                      if(validation_errors()==''){
                        $ope=$cuenta->pln_operacion;
                      }else{
                        $ope=set_value('pln_operacion');
                      }
                    ?>
                    <script type="text/javascript">
                    var ope='<?php echo $ope;?>';
                    pln_operacion.value=ope;
                  </script>
                </div>
                <div class="form-group <?php if(form_error('pln_obs')!=''){ echo 'has-error';}?>">
                  <label>Observaciones:</label>
                  <input type="text" class="form-control" name="pln_obs" value="<?php if(validation_errors()!=''){ echo set_value('pln_obs');}else{ echo $cuenta->pln_obs;}?>">
                  <?php echo form_error("pln_obs","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="pln_estado" id="pln_estado" class="form-control">
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
                        $est=$cuenta->pln_estado;
                      }else{
                        $est=set_value('pln_estado');
                      }                 
                      ?>  
                      <script type="text/javascript">
                        var est='<?php echo $est;?>'
                        pln_estado.value=est;
                      </script>
                </div>

                <input type="hidden" class="form-control" name="pln_id" value="<?php echo $cuenta->pln_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'plan_cuentas/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';

      function verificar_cuenta(){
        

        if ($('#pln_codigo').val() !=null) {
          
          vl=$('#pln_codigo').val();
          v=0;
          
          if(vl.length<=14){
            var filter = /\w(\.\w{2})(\.\w{2})(\.\w{2})(\.\w{3})/;  
            resp='Formato 0.00.00.00.000';
          }

          if(vl.length<=11){
            var filter = /\w(\.\w{2})(\.\w{2})(\.\w{2})./;  
            resp='Formato 0.00.00.00.';
          }

          if(vl.length<=8){
            var filter = /\w(\.\w{2})(\.\w{2})./;  
            resp='Formato 0.00.00.';
          }

          if(vl.length<=5){
            var filter = /\w(\.\w{2})./;  
            resp='Formato 0.00.';
          } 

          if(vl.length<=2){
            var filter = /\w./; 
            resp='Formato 0.'; 
          }

          if(vl.length==2){
            var filter = /\w/; 
            resp='Formato 0.'; 
          } 

          if(vl.length==2){
            if(vl.substr(1,1)!='.'){
               v=1;
            }
          }

          if(vl.length==5){
            if(vl.substr(4,1)!='.'){
               v=1;
            }
          }

          if(vl.length==8){
            if(vl.substr(7,1)!='.'){
               v=1; 
            }
          }

          if(vl.length==11){
            if(vl.substr(10,1)!='.'){
               v=1; 
            }
          }
           
          
          if (!filter.test(vl)){
              alert('Ingrese con el siguiente formato: '+resp);
              $('#pln_codigo').val('');
              return false;
          }

          if(v==1){
            alert('Ingrese con el siguiente formato: '+resp);
            $('#pln_codigo').val('');
            return false; 
          }

          $.ajax({
                url: base_url+"plan_cuentas/traer_cuenta/"+$('#pln_codigo').val(),
                type: 'JSON',
                dataType: 'JSON',
                success: function (dt) {
                  if(dt.resp=='0'){
                    if(dt.cuenta!=''){
                      alert('Primero cree la cuenta: '+ dt.cuenta);
                      $('#pln_codigo').val('');  
                    }
                  }
                }    
          });
        }   
      }
    </script>