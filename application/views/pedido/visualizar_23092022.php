	
<form id="frm_save" role="form" action="<?php echo $action_est?>" method="post" autocomplete="off" enctype="multipart/form-data">
     <?php 
     if( $pedido->ped_estado == 7){
     ?>         	
     <input type="radio" name="ped_estado" id="ped_estado1" value="17" >Suspendido<br>
     <input type="radio" name="ped_estado" id="ped_estado2" value="18" >Caducado<br>
     <?php 
     } 
     if( $pedido->ped_estado == 19 || $pedido->ped_estado == 15 || $pedido->ped_estado == 17){
     ?>             
     <input type="radio" name="ped_estado" id="ped_estado3" value="07" >Pendiente<br>
     <input type="radio" name="ped_estado" id="ped_estado2" value="18" >Caducado<br>
     <?php 
     }
     ?>
     
     <input type="hidden" name="ped_id" id="ped_id" value="<?php echo $pedido->ped_id?>">
</form>

