
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TIVKA | Ingreso al Sistema</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body class="hold-transition login-page" style="background-color: white;">
  
<div class="login-box">
  <a href="" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        
        <center><img src="<?php echo base_url(); ?>imagenes/<?php echo $logo; ?>"  > </center>
      <!-- <a href="#"><b>TIVKA SYSTEMS</b></a> -->
      </a>
  <!-- <div class="login-logo">
    
  </div> -->
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Identifiquese para iniciar sesión</p>
    <?php 
      if($this->session->flashdata('error')){
        ?>
        <div class="alert alert-danger">
          <p><?php echo $this->session->flashdata('error')?></p>
        </div>
        <?php
      }
      ?>
    <form action="<?php echo base_url(); ?>login/ingresar" method="POST">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Usuario" name="usuario">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Contraseña" name="clave">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <br>
    <div class="row">
        <div class="col-md-12">
          <button type="button" onclick="modal()" class="btn btn-success btn-block btn-flat">Descarga de documentos eléctronicos</button>
        </div>
        <!-- /.col -->
      </div>

      <div class="modal fade" id="documentos">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                
                <h4 class="modal-title" style="text-align:center;"><b>Documentos eléctronicos  </b> </h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <b>Ruc/C.I:</b>
                    <input type="text" class="form-control" placeholder="Identificación" value="" name="cliente" id="cliente">
                  </div>
                  <div class="col-md-12">
                   <b> Clave de Acceso: </b>
                    <input type="text" class="form-control" placeholder="Clave " value=""  name="clave" id="clave">

                   
                  </div>
                  
                </div>
                <br>

                  <div class="row">
                    <div class="col-md-6" style="margin-left: 25%;">
                     
                         <button  class="btn btn-success btn-block btn-flat  " onclick="consulta_doc()">Consultar</button>
                  </div>
                  </div>
                  <br>
                  <div class="row">
                      <table class="table table-bordered table-list table-hover" id="det_documentos">
                      
                    </table>
                  
                  </div>
                 
                
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                </div>
                

              </div>
              
            </div>
          </div>
</div>


  <!-- /.login-box-body -->
</div>
</div>


  <footer  style="text-align: center">
    <strong>Copyright &copy; 2022 <a href="https://tikvas.com/" target="_blank" rel="noopener noreferrer" >TIKVASYST S.A.S</a></strong> 
    
    <br>

    <a href="./TÉRMINOS Y CONDICIONES.pdf" target="_blank"> <b>
      Términos y condiciones </b> </a>
      <p> All rights reserved.</p>
 </footer>



<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
  var base_url='<?php echo base_url();?>';
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

  function modal(){
  
       $("#documentos").modal('show');
      var elmtTable = document.getElementById('det_documentos');
      var tableRows = elmtTable.getElementsByTagName('tr');
      var rowCount = tableRows.length;

      for (var x=rowCount-1; x>0; x--) {
      elmtTable.removeChild(tableRows[x]);
      }


  }


  function consulta_doc(){
    var clave   = $('#clave').val();
    var cliente = $('#cliente').val();

     $.ajax({       
                  beforeSend: function () {
                        if ($('#cliente').val().length == 0) {
                              
                              swal("Error!", "Ingrese la identificación!", "error");
                              $('#cliente').css({borderColor: "red"});
                              $('#cliente').focus();
                              return false;
                        }
                        if ($('#clave').val().length == 0) {
                              
                              swal("Error!", "Ingrese la clave !", "error");
                              $('#clave').css({borderColor: "red"});
                              $('#clave').focus();
                              return false;
                        }
                     },
                    url: base_url+"login/documentos/"+clave+"/"+cliente,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function(dt){

                      if(dt!=""){ 
                        $('#det_documentos').html(dt.lista);
                      }    
                  },
                error : function(xhr, status) {
                    
                          swal("Error!", "No existen registros de esta consulta por favor verifique datos!", "error");
                          $('#clave').val('');
                          $('#cliente').val('');
                    }

              });
  }

  
</script>
</body>
</html>



