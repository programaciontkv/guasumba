<section class="content-header">
    <form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post"
        action="<?php echo base_url();?>rep_ventas_por_producto/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>"
        onsubmit="return exportar_excel()">
        <input type="submit" value="EXCEL" class="btn btn-success" />
        <input type="hidden" id="datatodisplay" name="datatodisplay">
    </form>
    <input type="button" value="REPORTE PDF" class="btn btn-warning" onclick="validar()" style="float:right;" />
    <h1>
        Comisiones de ventas
    </h1>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box box-body">

            <div class="row">

                <div class="col-md-10">
                    <form id='frm_buscar' action="<?php echo $buscar;?>" method="post">

                        <table width="100%">
                            <tr>

                                <td><label>Vendedores:</label></td>
                                <td>

                                    <select name="vnd_id" id="vnd_id" class="form-control">
                                        <option value="0">Seleccione</option>

                                        <?php
                                if(!empty($vendedores)){
                                foreach ($vendedores as $vnd) {
                                ?>
                                        <option value="<?php echo $vnd->vnd_id?>"><?php echo $vnd->vnd_nombre?>
                                        </option>
                                        <?php
                                }
                                }
                                ?>
                                    </select>

                                </td>
                                <td></td>


                                <td><label>Tipo:</label></td>
                                <td>
                                    <select name="tipo" id="tipo" class="form-control">
                                        <option value="1">TIEMPO</option>
                                        <option value="2">VALOR</option>
                                    </select>
                                </td>

                                <td><label>Desde:</label></td>
                                <td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px"
                                        value='<?php echo $fec1?>' /></td>
                                <td><label>Hasta:</label></td>
                                <td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px"
                                        value='<?php echo $fec2?>' /></td>
                                <td>
                                    <!-- <button class="btn btn-info" onclick="buscar()"><span class="fa fa-search"></span> Buscar</button> -->
                                    <input type="button" value="Buscar" onclick="buscar()" class="btn btn-info">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">

                    <table id='tbl_list' class='table table-bordered table-list table-hover'>

                    </table>
                </div>
            </div>
        </div>
    </div>


</section>

<style>
#encabezado {
    background: #3C8DBC !important;

    align-content: center !important;
}

#tbl_list td {
    border-color: #ffffff;
    border-right: 1px solid #d7d7d7 !important;
    border-left: 1px solid #d7d7d7 !important;
}

#tbl_list tr:nth-child(2n-1) td {
    background: #DFDFDF !important;

}

.subtotal {
    /*background: #A2CADF;*/
    background: #4682b4;
    color: #FFFFFF;
    font-weight: bolder;
    font-size: 14px;
}

.total {
    background: #3e5f8a;
    color: #FFFFFF;
    font-weight: bolder;
    font-size: 14px;
}

.local {
    font-weight: bolder;
}

.number {
    text-align: right;
}
</style>
<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
</script>
<script src="<?php echo base_url(); ?>/assets/bower_components/accounting/accounting.js"></script>
<script>
var base_url = '<?php echo base_url();?>';
var opc = '<?php echo $permisos->opc_id?>';
var dec = 2;

function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

function buscar() {
    var tipo = $('#tipo').val();
    var url = '';

    switch (tipo) {
        case '1': //tiempo
            url = "com_tiempo/" + $('#fec1').val() + "/" + $('#fec2').val() + "/" + $('#vnd_id').val();
            break;
        case '2': //valor

            url = "com_valor/" + $('#fec1').val() + "/" + $('#fec2').val() + "/" + $('#vnd_id').val();
            break;
    }


    $.ajax({
        beforeSend: function() {


            if ($('#vnd_id').val() == 0) {
                  alert('Seleccione un vendedor');
                  return false;
            }
        },

        url: base_url + "rep_comisiones/" + url,
        type: 'JSON',
        dataType: 'JSON',
        success: function(dt) {
            $('#tbl_list').html(dt.detalle);
            calculos();
        }
    });
}

function calculos() {
    var n = 0;

    $('.enc').each(function() {
        n++;
        tc = 0;
        $('.cnt' + n).each(function() {
            tc += round($(this).html().replace(/,/g, ''), dec);
        });
        vc = accounting.formatMoney(tc, '', dec, ',', '.');
        $('#tv_cnt' + n).html(vc);

        tv = 0;
        $('.val' + n).each(function() {
            tv += round($(this).html().replace(/,/g, ''), dec);
        });
        vv = accounting.formatMoney(tv, '', dec, ',', '.');
        $('#tv_val' + n).html(vv);

    });

    var thc = 0;
    $('.th_cnt').each(function() {
        thc += round($(this).html().replace(/,/g, ''), dec);
    });
    hc = accounting.formatMoney(thc, '', dec, ',', '.');
    $('#tv_cnt').html(hc);

    var thv = 0;
    $('.th_val').each(function() {
        thv += round($(this).html().replace(/,/g, ''), dec);
    });
    hv = accounting.formatMoney(thv, '', dec, ',', '.');
    $('#tv_val').html(hv);

}

function validar() {

    // $.ajax({

    // 	url: base_url+"rep_ventas_por_producto/show_frame/"+opc+"/"+$('#empresa').val()+"/"+$('#fec1').val()+"/"+$('#fec2').val()+"/"+$('#tipo').val()+"/"+$('#txt').val(),
    // 	// type: 'JSON',
    // 	// dataType: 'JSON',
    // 	success: function (dt) {
    // 	$('#detalle').html(dt.detalle);
    // 	calculos();
    // 	}
    // 	});

    url = base_url + "rep_ventas_por_producto/show_frame/" + opc + "/" + $('#empresa').val() + "/" + $('#fec1').val() +
        "/" + $('#fec2').val() + "/" + $('#tipo').val() + "/" + $('#txt').val();
    $('#frm_buscar').attr('action', url);
    $('#frm_buscar').submit();
}


///



var x = 0;
$('.item').each(function() {
    x++;
    var y = 0;
    $('.periodo').each(function() {
        y++;

        tcob = 0;
        $('.cob' + x + y).each(function() {
            tcob = (tcob * 1) + ($(this).html().replace(/,/g, '') * 1);
        });
        $('#cob' + x + y).html(accounting.formatMoney(tcob, "", 2, ",", "."));

        tcmp = 0;
        $('.cmp' + x + y).each(function() {
            tcmp = (tcmp * 1) + ($(this).html().replace(/,/g, '') * 1);
        });
        $('#cmp' + x + y).html(accounting.formatMoney(tcmp, "", 2, ",", "."));


        /////
        tcob = 0;
        $('.co' + x + y).each(function() {
            tcob = (tcob * 1) + ($(this).html().replace(/,/g, '') * 1);
        });
        $('#co' + x + y).html(accounting.formatMoney(tcob, "", 2, ",", "."));

        tcmp = 0;
        $('.cm' + x + y).each(function() {
            tcmp = (tcmp * 1) + ($(this).html().replace(/,/g, '') * 1);
        });
        $('#cm' + x + y).html(accounting.formatMoney(tcmp, "", 2, ",", "."));
    });

});


var y = 0;
$('.periodo').each(function() {
    y++;
    var x = 0;
    var tt_cob = 0;
    var tt_cmp = 0;
    $('.item').each(function() {
        x++;
        tt_cob = (tt_cob * 1) + ($('#cob' + x + y).html().replace(/,/g, '') * 1);
        tt_cmp = (tt_cmp * 1) + ($('#cmp' + x + y).html().replace(/,/g, '') * 1);
    });
    $('#tt_cob' + x + y).html(accounting.formatMoney(tt_cob, "", 2, ",", "."));
    $('#tt_cmp' + x + y).html(accounting.formatMoney(tt_cmp, "", 2, ",", "."));
});
</script>