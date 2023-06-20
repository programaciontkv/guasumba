<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url(); ?>imagenes/<?php echo $this->session->userdata('s_imagen')?>" class="img-circle" alt="User Image" >
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('s_usuario')?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> En linea</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <?php
        $active='';
        $active_sbm='';
          if(!empty($menus)){
            $n=0;
            $gr_mnu='';
            foreach ($menus as $mn ) {
              $n++;
              if($mn->men_id==$actual){
                $active='active';
              }else{
                $active='';
              }
              if($mn->sbm_id==$actual_sbm){
                $active_sbm='active';
              }else{
                $active_sbm='';
              }
              if($gr_mnu!=$mn->men_id && $n!=1){
              ?>        
                      </ul>
                    </li>
              <?php
              }
              if($gr_mnu!=$mn->men_id){
        ?>
              <li class="<?php echo $active?> treeview">
                <a href="#">
                  <i class="fa fa-shield fa-rotate-270"></i> <span><?php echo $mn->men_nombre?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
        <?php
              } 
        ?>          
                  <li class="<?php echo $active_sbm?>"><a href="<?php echo base_url().  strtolower($mn->opc_direccion).$mn->opc_id?>"><i class="fa fa-circle-o"></i> <?php echo $mn->sbm_nombre ?> </a></li>
                  
                  
        <?php
        
              
              $gr_mnu=$mn->men_id;       
        
            }
          }
        ?>
        
        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">