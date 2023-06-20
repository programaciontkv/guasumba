
<section class="content-header">
      <h1>
        Credencialesde Firma Electronica
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-6">
          <?php 
          $dt=explode('&',$configuracion);
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
                <div class="form-group <?php if(form_error('con_credencial_alias')!=''){ echo 'has-error';}?> ">
                  <label>Nombre de la Empresa:<font class="sms">(Sin espacios ni caracteres especiales)</font></label>
                  <input type="text" class="form-control" name="con_credencial_alias" value="<?php if(validation_errors()=!''){ echo set_value('con_credencial_alias');}else{ echo $dt[0];}?>">
                  <?php echo form_error("con_credencial_alias","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('con_clave_certificado')!=''){ echo 'has-error';}?> ">
                  <label>Clave de Firma digital:</label>
                  <input type="password" class="form-control" name="con_clave_certificado" value="<?php if(validation_errors()=!''){ echo set_value('con_clave_certificado');}?>">
                  <?php echo form_error("con_clave_certificado","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('con_credencial_p12_val')!=''){ echo 'has-error';}?> ">
                  <label>Archivo de Firma (.p12):</label>
                  <div id="con_credencial_p12" class="btn btn-success">Subir</div>
                  <input type="text" class="form-control" name="con_credencial_p12_val" value="<?php echo if(validation_errors()=!''){ echo set_value('con_credencial_p12_val');}else{echo  $dt[1];}?>" readonly style="font-size:10px;height:20px;border:none">
                  <?php echo form_error("con_credencial_p12_val","<span class='help-block'>","</span>");?>
                </div>
            </tfoot>
                <input type="hidden" class="form-control" name="con_id" value="<?php echo $con_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>configuracion" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
        <link href="../FacturacionElectronica/Scripts/uploadfile.min.css" rel="stylesheet">
        <script src="../FacturacionElectronica/Scripts/jquery.min.js"></script>
        <script src="../FacturacionElectronica/Scripts/jquery.uploadfile.min.js"></script>
        
        <script>

            var cr=13;
            var base_url='<?php echo base_url();?>';
            function upload(){
            // $("#con_credencial_p12").uploadFile({
            alert('ok');
                    url: base_url+'FacturacionElectronica/Scripts/files.php',
                    fileName: "archivo",
                    autoUpload: true,
                    showDelete: false,
                    showDone: false,
                    allowedTypes: "p12",
                    dragDrop: false,
                    onSuccess: function (files, data, xhr) {
                        con_credencial_p12_val.value = data;
                    }
              }
                // });

            function save(cr) {
                alert(cr);
                var data = Array(
                        con_credencial_alias.value.toUpperCase(),
                        con_clave_certificado.value,
                        con_credencial_p12_val.value );

                if (con_credencial_alias.value.length == 0) {
                    $('#con_credencial_alias').css({'border': 'solid 1px red'});
                    $('#con_credencial_alias').focus();
                } else if (con_clave_certificado.value.length == 0) {
                    $('#con_clave_certificado').css({'border': 'solid 1px red'});
                    $('#con_clave_certificado').focus();
                } else if (con_credencial_p12_val.value.length == 0) {
                    $('#con_credencial_p12_val').css({'border': 'solid 1px red'});
                    $('#con_credencial_p12_val').focus();
                } else {
                    $.post(base_url+"FacturacionElectronica/Scripts/accions.php", {op:cr, 'data[]': data},
                    function (dt) {
                        if(dt==0){
                            alert('Registro de Credenciales Exitoso');
                        }else{
                            alert(dt);
                        }
                    });

                }
            }
            

        </script>