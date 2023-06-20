<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Tivka | Estandar V4 </title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <style>
      
      @media (max-width: 767px) {
        .hidden-mobile {
          display: none;
        }
        .h-mobile {
          display: none;
        }
        .show-mobile {
          visibility:visible; !important;

        }
        }

      @media (min-width: 767px) {
        .hidde-icono {
          display: none;
        }
        .show-mobile {
          display: none;
           visibility:hidden; !important;
        }

       
      }
    </style>
  <script type="text/javascript">
      $(document).ready(function($){
        var ventana_ancho = $(window).width();
        if(ventana_ancho<=915){
          $('#cuerpo').attr('class','hold-transition skin-blue sidebar-mini sidebar-collapse');
        }
      })
    </script>
</head>
<body id="cuerpo" class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url();?>/inicio" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>TKV</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $this->session->userdata('s_emp')?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      

      <div class="navbar-collapse pull-left collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <?php
            if(!empty($sbmopciones)){
            foreach ($sbmopciones as $sbmo) {
               if($sbmo->opc_id==$actual_opc){
                  $active_opc='active';
                }else{
                  $active_opc='';
                }
                
            ?>
              <li class="<?php echo $active_opc?>"><a href="<?php echo base_url().  strtolower($sbmo->opc_direccion).$sbmo->opc_id?>"><?php echo $sbmo->opc_nombre?></a></li>
            <?php
            }
          }
            ?>
            
            
          </ul>
          
        </div>
        <div class="navbar-static-top dropdown hidde-icono" style="float:left !important;">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top: 10px;">
            <i class="fa fa-angle-down"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
                if(!empty($sbmopciones)){
                foreach ($sbmopciones as $sbmo) {
                   if($sbmo->opc_id==$actual_opc){
                      $active_opc='active';
                    }else{
                      $active_opc='';
                    }
                    
                ?>
                  <li class="user-header <?php echo $active_opc?>"><a href="<?php echo base_url().  strtolower($sbmo->opc_direccion).$sbmo->opc_id?>"><?php echo $sbmo->opc_nombre?></a></li>
                <?php
                }
              }
                ?>
            
        </div>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url(); ?>imagenes/<?php echo $this->session->userdata('s_imagen')?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('s_usuario')?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url(); ?>imagenes/<?php echo $this->session->userdata('s_imagen')?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('s_usuario')?>
                  
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url(); ?>usuario/perfil/<?php echo $this->session->userdata('s_idusuario')?>" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url(); ?>login/salir" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
      

      
    </nav>
  </header>