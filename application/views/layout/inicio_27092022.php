<section class="content">
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
    <!-- ./col -->
    			<div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-red">
			            <div class="inner">
			                <h3><?php echo $facturas->contador?></h3>
			                <p>Facturas</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>factura/nuevo/18" class="small-box-footer">Facturar <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-green">
			            <div class="inner">
			                <h3><?php echo $clientes->contador?></h3>
			                <p>Clientes</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-person-add"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>cliente/5" class="small-box-footer">Ver Clientes y Proveedores <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
    <!-- ./col -->
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-yellow">
			            <div class="inner">
			                <h3><?php echo $productos->contador?></h3>
			                <p>Productos</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-bag"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>producto_comercial/15" class="small-box-footer">Ver Productos <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-blue">
			            <div class="inner">
			                <h3><?php echo $productos->contador?></h3>
			                <p>Inventarios</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-podium"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>inventario/30" class="small-box-footer">Ver Inventario<i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
    <!-- ./col -->
			    
			    
    <!-- ./col -->
		</div>
<!-- /.row -->
	</div>
</section>
