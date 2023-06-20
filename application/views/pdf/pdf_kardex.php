    
<section class="content">
 <table width="100%" >

    <tr>
        <td colspan="3" width="100%" >
            <table class="encabezado3"  width="100%" >
                <tr><td><?php echo $empresa->emp_nombre; ?></td> 
                    <td></td>
                    <td></td>
                    <td></td>
                <td rowspan="5" width="20%"><img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>"  width="130px" height="70px"></td>
                 </tr>
                <tr><td><?php echo $empresa->emp_identificacion; ?> </td>  </tr>
                <tr><td><?php echo $empresa->emi_ciudad."-".$empresa->emi_pais; ?> </td>  </tr>
                <tr><td><?php echo "TELEFONO: " . $empresa->emi_telefono ?> </td>  </tr>
              
                
            </table>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo">KARDEX</th>
                </tr>    
                <tr>
                    <td><strong>Desde:</strong> <?php echo $fecha1?> <strong>Hasta: </strong> <?php echo $fecha2?></td>
                </tr>  
                 <tr>
                    <td><br><br></td>
                </tr>    
            </table>
        </td>
    </tr>         
    
    <tr>
        <td>   
                    <?php echo $kardex?>
                </td>
            </tr>
        </table>            
<style type="text/css">
    *{
       /* font-size: 14px;
       /* font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        font-family: 'calibri';


       */
    }
    

    .numerico {
        text-align: right;
    }


    #encabezado1{
        border: none;
        text-align: center;
    }


    #detalle td, #detalle th{
        border-collapse: collapse;
        
    }

    .titulo{
        font-size: 15px !important;
        font-weight: bolder !important;
    }

    .tr_familia{
        background: #1B4D75 !important;
        color:#ffffff !important;
        font-weight: bolder !important;
    }

    .tr_tipo{
        background: #2A76B5 !important;
        color:#ffffff !important;
        font-weight: bolder !important;
    }

    .total{
        background: #081B38 !important;
        color:#ffffff !important;
        font-weight: bolder !important;
    }


</style>

<script type="text/javascript">
   window.onload = function () {
      window.print();
    }
    
</script>
         

