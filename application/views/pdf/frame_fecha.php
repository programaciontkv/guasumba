    <style>
.container {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.responsive-iframe {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 100%;
  height: 100%;
  border: none;
}
</style>

    <section class="content-header">
        <h1 class="pull-left"><?php echo $titulo?></h1>
        <h1 class="pull-right">
           <form action="<?php echo $regresar;?>" method="post">
            <div hidden>
              <input type="text" id='txt' name='txt' class="form-control" value='<?php echo $txt?>'/>
              <input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' />
              <input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' />
              <input type="text" id='estado' name='estado' class="form-control"  value='<?php echo $estado?>' />
              <input type="text" id='tipo' name='tipo' class="form-control"  value='<?php echo $tipo?>' />
              <input type="text" id='familia' name='familia' class="form-control"  value='<?php echo $familia?>' />
              <input type="text" id='tip' name='tip' class="form-control"  value='<?php echo $tip?>' />
              <input type="text" id='detalle' name='detalle' class="form-control"  value='<?php echo $detalle?>' />
              <input type="text" id='vencer' name='vencer' class="form-control"  value='<?php echo $vencer?>' />
              <input type="text" id='vencido' name='vencido' class="form-control"  value='<?php echo $vencido?>' />
              <input type="text" id='pagado' name='pagado' class="form-control"  value='<?php echo $pagado?>' />

            </div>  
              <button type="submit" class="btn btn-default">Regresar</button>
          </form>


        </h1>
    </section>
    <div class="container">
    <iframe class="responsive-iframe" src='<?php echo base_url().$direccion?>'  frameborder="0">
                      
                        </iframe> 
    
</div>
