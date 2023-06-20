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
           <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px" href="<?php echo base_url().$regresar?>">
           Regresar</a>
        </h1>
    </section>
    <div class="container">
    <iframe class="responsive-iframe" src='<?php echo base_url().$direccion?>'  frameborder="0">
                      
                        </iframe> 
    
</div>
