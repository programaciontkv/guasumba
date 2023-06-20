  <div id="principal">
      <div id="img">
      <div id="producto">
        <label ><?php echo $producto->mp_d?></label>
      </div>
      <div id="precios">
          <label id="precios">P.V.P $<?php echo $producto->mp_e?> </label>
      </div>
    </div>
  </div>
  <div id="principal2">
     <div id="imagen">
        <center> <img src="<?php echo base_url();?>barcodes/<?php strtoupper($producto->mp_c)?>.png" alt="" width="250px" height="65px"> </center>
      </div>
       <div id="cod"> 
          <label> <?php echo $producto->mp_c;?> </label>
       </div>
  </div>
 
  
  <style type="text/css">
    body{
       background-color: white;
       margin-top: -50px;
       margin-left: -50px;
       margin-bottom: -50px;
       margin-right: -50px;
    }
    #principal{
     position: relative;
     height: auto;
     width: 100%;
     background-color: #e2e2e2;
    }
    #principal2{
       position: relative;
    }
    #img{
    margin-left: 10px;
    margin-top:   20px;
    margin-right: 10px;
    margin-bottom: 10px;
    }
    #producto{
      text-align: left;
      font-size: 15px;
     
      border-radius: 10px;
    }
    #imagen{
      margin-top: 10px;
      position: absolute;
    }
    #precios{
      text-align: right;
      float: right;
      font-size: 20px;
      font-weight: bold;
      margin-top: 20px;
  
    }
    #cod{
      text-align: center;
      font-size: 10px;
      margin-top: 75px;

    }
  </style>
