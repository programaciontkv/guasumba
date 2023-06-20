    
<section class="content">
 <table width="100%" >
    <!-- <tr id="btn_imprimir">
        <td >
            <input type="button" value="IMPRIMIR" class="btn btn-danger" onclick="imprimir()" />
        </td>
    </tr> -->
    <tr>
        
        <td width="100%">
            <table id="encabezado1" width="100%">
                <tr>
                    <td rowspan="4" width="80px;">
                        <img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>" width="150px" height="80px">
                    </td>
                    <th class="titulo" width="70%"><?php echo $empresa->emi_nombre?></th>
                    <td rowspan="4" width="250px;"></td>
                </tr>
                <tr>
                    <th><?php echo $empresa->emi_dir_establecimiento_emisor?></th>
                </tr>
                <tr>
                    <th><?php echo $empresa->emi_ciudad .' - '. $empresa->emi_pais ?></th>
                </tr>
                 <tr>
                    <th><?php echo $empresa->emp_identificacion?></th>
                </tr>    
                <tr>
                    <td colspan="2"><br><br></td>
                </tr>  
            </table>
        </td>
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
        font-size: 14px;
       /* font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        font-family: 'Source Sans Pro';


       
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

    /*.btn {
     border-radius:3px;
     -webkit-box-shadow:none;
     box-shadow:none;
     border:1px solid transparent;
     background-color: #f39c12;
     border-color: #e08e0b;
     color:#ffffff;
     padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    }*/

   

</style>
<script type="text/javascript">
   window.onload = function () {
      window.print();
    }
    
</script>

         

