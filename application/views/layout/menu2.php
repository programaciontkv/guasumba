<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url(); ?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('s_usuario')?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> En linea</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <?php
          if(!empty($menus)){
            $n=0;
            foreach ($menus as $mn ) {
              $n++;
              if($n==1){
                $active='active';
              }
        ?>
              <li class="<?php echo $active?> treeview">
                <a href="#">
                  <i class="fa fa-gears"></i> <span>Configuraciones</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="active"><a href="<?php echo base_url(); ?>usuario"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                  <li class="active"><a href="<?php echo base_url(); ?>rol"><i class="fa fa-circle-o"></i> Roles</a></li>
                  <li class="active"><a href="<?php echo base_url(); ?>menu"><i class="fa fa-circle-o"></i> Menus</a></li>
                  <li class="active"><a href="<?php echo base_url(); ?>opcion"><i class="fa fa-circle-o"></i> Opciones</a></li>
                </ul>
              </li>
            }
          }
        ?>
        <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li> -->
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">
   
     
    
  

