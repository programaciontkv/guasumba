<section class="content">
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
    <!-- ./col -->
			    <div class="col-lg-4 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-green">
			            <div class="inner">
			                <h3><?php echo $clientes->contador?></h3>
			                <p>Clientes</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-person-add"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>cliente" class="small-box-footer">Ver Clientes <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
    <!-- ./col -->
			    <div class="col-lg-4 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-yellow">
			            <div class="inner">
			                <h3><?php echo $productos->contador?></h3>
			                <p>Productos</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-bag"></i>
			            </div>
			            <a href="#" class="small-box-footer">Ver Productos <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
    <!-- ./col -->
			    <div class="col-lg-4 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-red">
			            <div class="inner">
			                <h3><?php echo $facturas->contador?></h3>
			                <p>Facturas</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            <a href="#" class="small-box-footer">Ver Facturas <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    
    <!-- ./col -->
		</div>
<!-- /.row -->
	</div>
</section>
