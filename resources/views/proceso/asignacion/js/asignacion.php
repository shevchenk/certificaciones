<script type="text/javascript">
    var TI=0;
    var TA=0;
$(document).ready(function() {
    $(".fecha").datetimepicker({
        format: "yyyy-mm-dd",
        language: 'es',
        showMeridian: false,
        time:false,
        minView:2,
        startView:2,
        autoclose: true,
        container : 'body',
        todayBtn: false
    });

    $('#spn_fecha_ini').on('click', function(){
        $('#txt_fecha_inicial').focus();
    });
    $('#spn_fecha_fin').on('click', function(){
        $('#txt_fecha_final').focus();
    });
    
    $("#TablePae").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "autoWidth": false
    });

    AjaxVisita.CargarEmpresas(SlctCargarEmpresas);
    
    function DataToFilter(){
        var fecha_inicial = $('#txt_fecha_ini').val();
        var fecha_final = $('#txt_fecha_fin').val();
        var empresa = $('#slct_empresas').val();
        var r=true;
        if ( fecha_inicial=="" || fecha_final=="" || empresa=='') {
            r=false;
            swal("Mensaje", "Por favor seleccione todos los campos!")
        }
        return r;
    }
    
    $("#btn_generar").click(function (){
        var r = DataToFilter();
        if( r ){
            AjaxVisita.Cargar(HTMLCargarVisita);
        }
    });

    $("#slct_trabajador").on('hidden.bs.select', function(e, clickedIndex, isSelected, previousValue){
        var html='';
        var tra=$(this).val();
        var val2=$("#nro_asignacion").val();
        $("#tb_asignar").html('');
        for (var i = 0; i < $(this).val().length ; i++) {
            html='<tr>';
            html+=   '<td>'+$("#slct_trabajador option[value='"+tra[i]+"']").text()+'</td>';
            html+=   '<td><input name="txt_asig_trabajador[]" class="form-control asig_trabajador" type="text" value="'+val2+'"></td>';
            html+='</tr>';
            $("#tb_asignar").append(html);
        }
    });
});

SlctCargarEmpresas=function(result){
    var html="<option value=''>.::Seleccione::.</option>";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.empresa+"</option>";
    });
    $("#AsignacionForm #slct_empresas").html(html); 
    $("#AsignacionForm #slct_empresas").selectpicker('refresh');
}

AsignaNro=function(val){
    if(val==2){
        var val=$("#nro_asignacion").val()*1;
        $("#tb_asignar input.asig_trabajador").val(val);
    }
    else{
        var val=$("#r_nro_asignacion").val()*1;
        $("#tb_asignar input.rasig_trabajador").val(val);
    }
}

Guardar=function(){
    if( $.trim($("#slct_trabajador").val())=='' ){
        msjG.mensaje('warning','Seleccione los trabajadores',3000);
    }
    else{
        r=true;
        TI=0; TA=0;
        $("#TableVisita input[type='checkbox']:checked").each(function(key, value){
            TI+= $(this).attr('data-cant')*1;
        });
        $("#tb_asignar input").each(function(key, value){
            TA+= $(this).val()*1;
        });

        
        if(TA>TI){
            r=false;
            msjG.mensaje('warning','('+TA+') El total de asignaciones no puede ser mayor a ('+TI+') el total interesados seleccionados',8000);
        }
        
        if(r){
            AjaxVisita.Guardar(HTMLGuardar);
        }
    }
}

HTMLGuardar=function(result){
    if( result.rst==1 ){
        msjG.mensaje('success',result.msj,4000);
        AjaxVisita.Cargar(HTMLCargarVisita);
    }
    else{
        msjG.mensaje('warning',result.msj,3000);
    }
}

SlctCargarTrabajador=function(result){
    var html="";
    $.each(result.data,function(index,r){
        html+="<option value="+r.id+">"+r.trabajador+"</option>";
    });
    $("#AsignacionForm #slct_trabajador").html(html); 
    $("#AsignacionForm #slct_trabajador").selectpicker('refresh');

};

HTMLCargarVisita=function(result){
    var html="";
    var costo_convertidoG=0; var chkasigG=0; var chknoasigG=0; var chknocallG=0;
    var chkcallG=0; var chkinteresadoG=0; var chkpendienteG=0; var chknointeresadoG=0;
    var chkotrosG=0; var convertidoG=0;
    $('#TableVisita').DataTable().destroy();

    $.each(result.data,function(index,r){
        codigo=r.ad_name+'|'+r.interes+'|'+r.fecha_carga;
        costo_convertido= r.total*1;
        if( r.convertido*1>0 ){
            costo_convertido= r.total*1 / r.convertido*1;
        }
        costo_convertidoG+= costo_convertido*1;

        convertidoG+= r.convertido*1;

        chkasig=r.si_asignado*1;
        chkasigG+= chkasig*1;
        /*if( r.si_asignado*1>0 ){
        chkasig=
            '<div class="form-check">'+
                '<input type="checkbox" id="chkasig'+index+'" data-cant="'+r.si_asignado*1+'" name="chkasig[]" value="'+codigo+'">'+
                '<label for="chkasig'+index+'">&nbsp;&nbsp;'+r.si_asignado*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }*/

        chknoasig=r.no_asignado*1;
        chknoasigG+= chknoasig*1;
        if( r.no_asignado*1>0 ){
        chknoasig=
            '<div class="form-check">'+
                '<input type="checkbox" id="chknoasig'+index+'" data-cant="'+r.no_asignado*1+'" name="chknoasig[]" value="'+codigo+'">'+
                '<label for="chknoasig'+index+'">&nbsp;&nbsp;'+r.no_asignado*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }

        chknocall=r.no_llamada*1;
        chknocallG+= chknocall*1;
        if( r.no_llamada*1>0 ){
        chknocall=
            '<div class="form-check">'+
                '<input type="checkbox" id="chknocall'+index+'" data-cant="'+r.no_llamada*1+'" name="chknocall[]" value="'+codigo+'">'+
                '<label for="chknocall'+index+'">&nbsp;&nbsp;'+r.no_llamada*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }

        chkcall=r.si_llamada*1;
        chkcallG+= chkcall*1;
        /*if( r.si_llamada*1>0 ){
        chkcall=
            '<div class="form-check">'+
                '<input type="checkbox" id="chkcall'+index+'" data-cant="'+r.si_llamada*1+'" name="chkcall[]" value="'+codigo+'">'+
                '<label for="chkcall'+index+'">&nbsp;&nbsp;'+r.si_llamada*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }*/

        chkinteresado=r.interesado*1;
        chkinteresadoG+= chkinteresado*1;
        if( r.interesado*1>0 ){
        chkinteresado=
            '<div class="form-check">'+
                '<input type="checkbox" id="chkinteresado'+index+'" data-cant="'+r.interesado*1+'" name="chkinteresado[]" value="'+codigo+'">'+
                '<label for="chkinteresado'+index+'">&nbsp;&nbsp;'+r.interesado*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }

        chkpendiente=r.pendiente*1;
        chkpendienteG+= chkpendiente*1;
        if( r.pendiente*1>0 ){
        chkpendiente=
            '<div class="form-check">'+
                '<input type="checkbox" id="chkpendiente'+index+'" data-cant="'+r.pendiente*1+'" name="chkpendiente[]" value="'+codigo+'">'+
                '<label for="chkpendiente'+index+'">&nbsp;&nbsp;'+r.pendiente*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }

        chknointeresado=r.nointeresado*1;
        chknointeresadoG+= chknointeresado*1;
        if( r.nointeresado*1>0 ){
        chknointeresado=
            '<div class="form-check">'+
                '<input type="checkbox" id="chknointeresado'+index+'" data-cant="'+r.nointeresado*1+'" name="chknointeresado[]" value="'+codigo+'">'+
                '<label for="chknointeresado'+index+'">&nbsp;&nbsp;'+r.nointeresado*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }

        chkotros=r.otros*1;
        chkotrosG+= chkotros*1;
        if( r.otros*1>0 ){
        chkotros=
            '<div class="form-check">'+
                '<input type="checkbox" id="chkotros'+index+'" data-cant="'+r.otros*1+'" name="chkotros[]" value="'+codigo+'">'+
                '<label for="chkotros'+index+'">&nbsp;&nbsp;'+r.otros*1+'&nbsp;&nbsp;</label>'+
            '</div>';
        }
        html+="<tr>"+
            "<td>"+$.trim(r.empresa)+"</td>"+
            "<td>"+$.trim(r.fecha_carga)+"</td>"+
            "<td>"+$.trim(r.fmin)+"</td>"+
            "<td>"+$.trim(r.fmax)+"</td>"+
            "<td>"+$.trim(r.ad_name)+"</td>"+
            "<td>"+$.trim(r.interes)+"</td>"+
            "<td>"+$.trim(r.cantidad)+"</td>"+
            "<td>"+$.trim(r.costo_min)+"</td>"+
            "<td>"+$.trim(r.total)+"</td>"+
            "<td>"+chkasig+"</td>"+
            "<td>"+chknoasig+"</td>"+
            "<td>"+chknocall+"</td>"+
            "<td>"+chkcall+"</td>"+
            "<td>"+(r.convertido*1)+"</td>"+
            "<td>"+costo_convertido.toFixed(2)+"</td>"+
            "<td>"+chkinteresado+"</td>"+
            "<td>"+chkpendiente+"</td>"+
            "<td>"+chknointeresado+"</td>"+
            "<td>"+chkotros+"</td>"+
            "";
        html+="</tr>";
    });
    $("#TableVisita tbody").html(html);
    $("#TableVisita #tdasig").text(chkasigG);
    $("#TableVisita #tdnoasig").text(chknoasigG);
    $("#TableVisita #tdnocall").text(chknocallG);
    $("#TableVisita #tdcall").text(chkcallG);
    $("#TableVisita #tdconvertido").text(convertidoG);
    $("#TableVisita #tdto_convertido").text(costo_convertidoG.toFixed(2));
    $("#TableVisita #tdinteresado").text(chkinteresadoG);
    $("#TableVisita #tdpendiente").text(chkpendienteG);
    $("#TableVisita #tdnointeresado").text(chknointeresadoG);
    $("#TableVisita #tdotros").text(chkotrosG);

    $('#TableVisita input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-green'
    }).on('ifChanged', function(e) {
        TI=0; 
        var chkasig=0; var chknoasig=0; var chknocall=0; var chkcall=0; 
        var chkconvertido=0; var chkto_convertido=0; var chkinteresado=0; 
        var chkpendiente=0; var chknointeresado=0; var chkotros=0; 
        $("#TableVisita input[type='checkbox']:checked").each(function(key, value){
            TI+= $(this).attr('data-cant')*1;

            if( value.name=='chknoasig[]' ){
                chknoasig+= value.getAttribute('data-cant')*1;
            }
            else if( value.name=='chknocall[]' ){
                chknocall+= value.getAttribute('data-cant')*1;
            }
            else if( value.name=='chkinteresado[]' ){
                chkinteresado+= value.getAttribute('data-cant')*1;
            }
            else if( value.name=='chkpendiente[]' ){
                chkpendiente+= value.getAttribute('data-cant')*1;
            }
            else if( value.name=='chknointeresado[]' ){
                chknointeresado+= value.getAttribute('data-cant')*1;
            }
            else if( value.name=='chkotros[]' ){
                chkotros+= value.getAttribute('data-cant')*1;
            }

            //$("#TableVisita #chkasig").text(chkasig);
            $("#TableVisita #chknoasig").text(chknoasig);
            $("#TableVisita #chknocall").text(chknocall);
            //$("#TableVisita #chkcall").text(chkcall);
            //$("#TableVisita #chkconvertido").text(chkconvertido);
            //$("#TableVisita #chkto_convertido").text(chkto_convertido);
            $("#TableVisita #chkinteresado").text(chkinteresado);
            $("#TableVisita #chkpendiente").text(chkpendiente);
            $("#TableVisita #chknointeresado").text(chknointeresado);
            $("#TableVisita #chkotros").text(chkotros);
        });
            console.log("/****************************************************/");
        $("#AsignacionForm #txt_contador").text(TI);
    });

    $("#TableVisita").DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "lengthMenu": [20]
    });
};

</script>
