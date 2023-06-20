</div>
<!-- style="display: none" -->
<table border="1" id="tbl2" style="display: none">
    <thead></thead>            
</table>
<footer  class="main-footer" style="text-align: center" style="text-align: center">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="https://tikvas.com/" target="_blank" rel="noopener noreferrer" >TIKVASYST S.A.S</a></strong> 
    
    <br>

    <a href="../TÉRMINOS Y CONDICIONES.pdf" target="_blank"> <b>
      Términos y condiciones </b> </a>
      <p> All rights reserved.</p>
 </footer>

 </div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url(); ?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
<!-- DataTables --> 
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<style type="text/css">
    input[type="text"] {
        text-transform: uppercase;
    }

    textarea{
        text-transform: uppercase;  
    }

    input[type="email"] {
        text-transform: lowercase;
    }

    select, input, textarea, td{
        font-size: 12px !important;
    }

    input[readonly]{
        background:#E2E2E2 !important; 
       
    }
</style>
<script>
var base_url='<?php echo base_url();?>';
var modulo="<?php echo $modulo;?>";
$(document).ready(function () {
       

        $('.upper').change(function(){
            this.value=this.value.toUpperCase();
        });

        $('textarea').keyup(function () {
            this.value = (this.value + '').replace(/[^0-9.A-Za-z-_/\s]/g, '');
        });

        $('textarea').change(function(){
            this.value=this.value.toUpperCase();
        });

        $('.decimal').keyup(function () {
            this.value = (this.value + '').replace(/[^0-9.]/g, '');
        });

        $('.numerico').keyup(function () {
            this.value = (this.value + '').replace(/[^0-9]/g, '');
        });

        $('.documento').keyup(function () {
            this.value = (this.value + '').replace(/[^0-9-]/g, '');
        });

        

        switch(modulo){
            case 'pedido_factura':
                validar_inventario_det();
            break;
            case 'producto':
                cambio_unidad();
            break;
            case 'materiaprima':
                load_tipo();
            break;
            case 'bancos_tarjetas':
                mostrar();
            break;
            case 'reg_factura':
                calculo();
            break;
        }
        
});

  $(function () {
    

    $('.btn-remove').on('click',function(e){
        e.preventDefault();
        var r=confirm('¿Desea eliminar el registro?');
        if(r==true){
            var ruta=$(this).attr('href');
            $.ajax({
                url: ruta,
                type: 'POST',
                success: function (resp) {
                    window.location.href= base_url+resp;
                }
            })
        }else{
            return false;
        }
    });

    $('.btn-anular').on('click',function(e){
        e.preventDefault();
        var r=confirm('¿Desea anular el registro?');
        if(r==true){
            var ruta=$(this).attr('href');
            $.ajax({
                url: ruta,
                type: 'POST',
                success: function (resp) {
                        window.location.href= base_url+resp;
                }
            })
        }else{
            return false;
        }
    });

    $('.btn-anular-comp').on('click',function(e){
        e.preventDefault();
        var r=confirm('¿Desea anular el registro?');
        if(r==true){
            var ruta=$(this).attr('href');
            $.ajax({
                url: ruta,
                type: 'JSON',
                dataType: 'JSON',
                success: function (resp) {
                    
                    if(resp.estado==1){
                        alert(resp.sms);
                    }else{
                        window.location.href= base_url+resp.url;
                    }
                }
            })
        }else{
            return false;
        }
    });

    
    $('.btn-view').on('click',function(){
        // var id=$(this).val();
        var ruta=$(this).val();
        $.ajax({
            url: ruta,
            type: 'POST',
            success: function(resp){
                // alert(resp);
                $('#modal-default .modal-body').html(resp);
            } 
        });
    })

    
    $('#tbl_list').DataTable({
    	"language":{
    		"lengthMenu":"Mostrar _MENU_ registros por pagina",
    		"zeroRecords":"No se encuentran resultados en su busqueda",
    		"searchPlaceholder":"Buscar registros",
    		"info":"Mostrando registros de _START_ al _END_ de un total de _TOTAL_ registros",
    		"infoEmpty":"No existen registros",
    		"infoFiltered":"(Filtrando un total de _MAX_ registros)",
    		"search":"Buscar",
    		"paginate":{
    					"first":"Primero",
    					"last":"Ultimo",
    					"next":"Siguiente",
    					"previous":"Anterior"
    		},
        "scrollY": true,
        "scrollX": true
    	}
    })
  })

  function exportar_excel() {

                $("#datatodisplay").val("");
                $("#tbl2 thead").html("");
                $("#tbl2 tbody").html("");
                $("#tbl2 tfoot").html("");
                $("#tbl2").append($("#tbl_list thead").eq(0).clone()).html();
                $("#tbl2").append($("#tbl_list tbody").clone()).html();
                $("#tbl2").append($("#tbl_list tfoot").clone()).html();
                $("#tbl2 .imagen").html("");
                $("#datatodisplay").val($("<div>").append($("#tbl2").eq(0).clone()).html());
                return true;
            }
</script>
</body>
</html>

