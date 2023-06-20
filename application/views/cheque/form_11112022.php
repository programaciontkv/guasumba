
<section class="content-header">
      <h1>
        Control de Cobros <?php echo $titulo?>
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
                <table class="table">
                  <tr>
                    <td><label>Fecha de Recepcion:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('chq_recepcion')!=''){ echo 'has-error';}?> ">
                        <input type="date" class="form-control" name="chq_recepcion" id="chq_recepcion" value="<?php if(validation_errors()!=''){ echo set_value('chq_recepcion');}else{ echo $cheque->chq_recepcion;}?>">
                        <?php echo form_error("chq_recepcion","<span class='help-block'>","</span>");?>
                        <input type="hidden"  class="form-control" name="chq_id" id="chq_id" value="<?php if(validation_errors()!=''){ echo set_value('chq_id');}else{ echo $cheque->chq_id;}?>">
                        <input type="hidden"  class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $cheque->emp_id;}?>">
                      </div>
                    </td>
                  </tr>
                  <tr>  
                    <td><label>Fecha de Documento:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('chq_fecha')!=''){ echo 'has-error';}?> ">
                        <input type="date" class="form-control" name="chq_fecha" id="chq_fecha" value="<?php if(validation_errors()!=''){ echo set_value('chq_fecha');}else{ echo $cheque->chq_fecha;}?>">
                        <?php echo form_error("chq_fecha","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Tipo Documento:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('chq_tipo_doc')!=''){ echo 'has-error';}?> ">
                        <?php
                        if(validation_errors()==''){
                          $tipo=$cheque->chq_tipo_doc;
                        }else{
                          $tipo=set_value('chq_tipo_doc');
                        }
                        
                        ?>   
                        
                        <select name="chq_tipo_doc" id="chq_tipo_doc" class="form-control">
                          <option value="">SELECCIONE</option>
                          <option value="1">TARJETA DE CREDITO</option>
                          <option value="2">TARJETA DE DEBITO</option>
                          <option value="3">CHEQUE A LA FECHA</option>
                          <option value="10">CHEQUE POSTFECHADO</option>
                          <option value="4">EFECTIVO</option>
                          <option value="5">CERTIFICADOS</option>
                          <option value="6">TRANSFERENCIA</option>
                          
                        </select>
                        <script type="text/javascript">
                          var tipo="<?php echo $tipo?>";
                          chq_tipo_doc.value=tipo;
                        </script>
                        <?php echo form_error("chq_tipo_doc","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Nombre Cliente:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social');}else{ echo $cheque->cli_raz_social;}?>" list="clientes" onchange="traer_cliente()">
                        <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $cheque->cli_id;}?>">
                        <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Descripcion del Documento:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('chq_nombre')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="chq_nombre" id="chq_nombre" value="<?php if(validation_errors()!=''){ echo set_value('chq_nombre');}else{ echo $cheque->chq_nombre;}?>">
                        <?php echo form_error("chq_nombre","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Concepto:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('chq_concepto')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="chq_concepto" id="chq_concepto" value="<?php if(validation_errors()!=''){ echo set_value('chq_concepto');}else{ echo $cheque->chq_concepto;}?>">
                        <?php echo form_error("chq_concepto","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Banco:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('chq_banco')!=''){ echo 'has-error';}?> ">
                        <?php
                        if(validation_errors()!=''){
                          $banco=$cheque->chq_banco;
                        }else{
                          $banco=set_value('chq_banco');
                        }
                        ?>   
                        
                        <select name="chq_banco" id="chq_banco" class="form-control">
                          <option value="">SELECCIONE</option>
                                     <?php
                                    if(!empty($bancos)){
                                      foreach ($bancos as $banco) {
                                    ?>
                                    <option value="<?php echo $banco->btr_descripcion?>"><?php echo $banco->btr_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                        </select>
                        <script type="text/javascript">
                          var banco='<?php echo $banco?>';
                          chq_banco.value=banco;
                        </script>
                        <?php echo form_error("chq_banco","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>No.Documento:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('chq_numero')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="chq_numero" id="chq_numero" value="<?php if(validation_errors()!=''){ echo set_value('chq_numero');}else{ echo $cheque->chq_numero;}?>">
                        <?php echo form_error("chq_numero","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>    
                    <td><label>Monto $:</label></td>
                    <td>  
                      <div class="form-group <?php if(form_error('chq_monto')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="chq_monto" id="chq_monto" value="<?php if(validation_errors()!=''){ echo set_value('chq_monto');}else{ echo $cheque->chq_monto;}?>">
                        <?php echo form_error("chq_monto","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><label>Estado Cobro:</label></td>
                    <td>
                        <select name="chq_estado"  id="chq_estado" class="form-control">
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
                            $est=$cheque->chq_estado;
                          }else{
                            $est=set_value('chq_estado');
                          }
                        ?>
                        <script type="text/javascript">
                          var est='<?php echo $est;?>';
                          chq_estado.value=est;
                        </script>
                    </td>
                  </tr>
                  <tr>
                    <td><label>Estado Cheque:</label></td>
                    <td>
                      <select name="chq_estado_cheque"  id="chq_estado_cheque" class="form-control">
                        <option value="10">CAJA</option>
                        <option value="11">DEPOSITADO</option>
                        <option value="12">REBOTADO</option>
                        <option value="3">ANULADO</option>
                      </select>
                      <?php 
                        if(validation_errors()==''){
                          $est=$cheque->chq_estado_cheque;
                        }else{
                          $est=set_value('chq_estado_cheque');
                        }
                      ?>
                      <script type="text/javascript">
                      var ch_est='<?php echo $est;?>';
                      chq_estado_cheque.value=ch_est;
                      </script>
                      </td>
                  </tr>
                                
                </table>      
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>

    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 5px !important;
        margin-top: 5px !important;
        padding-bottom: 5px !important;
        padding-top: 5px !important;
      }
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
      }
    </style>
    
    <script>

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            

          function traer_cliente() {
              $.ajax({
                  beforeSend: function () {
                      if ($('#cli_raz_social').val().length == 0) {
                            alert('Ingrese un cliente');
                            return false;
                      }
                    },
                  url: base_url+"cheque/traer_cliente/"+$('#cli_raz_social').val(),
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) {
                    if (dt.length!= 0) {
                            $('#cli_raz_social').val(dt.cli_raz_social);
                            $('#cli_id').val(dt.cli_id);
                    }else{
                        alert('No existe Cliente');
                        $('#cli_raz_social').val('');
                        $('#cli_id').val('');
                    }
                  },
                  error : function(xhr, status) {
                     alert('No existe Cliente');
                        $('#cli_raz_social').val('');
                        $('#cli_id').val('');
                  }
                })
            } 


            
    </script>
<datalist id="clientes">
  <?php 
  if(!empty($clientes)){
    foreach ($clientes as $cliente) {
  ?>
    <option value="<?php echo $cliente->cli_id?>"><?php echo $cliente->cli_raz_social?></option>
  <?php
    }
  }
  ?>
</datalist>
