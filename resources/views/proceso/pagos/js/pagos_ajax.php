<script type="text/javascript">
var Reporte={
    Cargar:function(evento){
        url='AjaxDinamic/Reporte.SeminarioEM@LoadPagos';
        data = $("#PaeForm").serialize().split("txt_").join("").split("slct_").join("");
        masterG.postAjax(url,data,evento);
    },
    CargarEspecialidad:function(evento){
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidad';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarCurso:function(evento){
        url='AjaxDinamic/Mantenimiento.CursoEM@ListCurso';
        data={};
        masterG.postAjax(url,data,evento);
    },
    VerSaldos:function(evento, mmd,mm,c){
        data={cuota:c, matricula_id:mm};
        if(mmd!=''){
            data={matricula_detalle_id:mmd};
        }
        url='AjaxDinamic/Proceso.AlumnoPR@ListarSaldos';
        masterG.postAjax(url,data,evento);
    },
    GuardarPago:function(evento){
        data=$("#ModalPago #PagoForm").serialize().split("txt_").join("").split("slct_").join("");
        url='AjaxDinamic/Proceso.AlumnoPR@GuardarPago';
        masterG.postAjax(url,data,evento);
    },
    CargarBanco:function(evento){
        url='AjaxDinamic/Mantenimiento.BancoMA@ListBanco';
        data={};
        masterG.postAjax(url,data,evento);
    },
    CargarMedioCaptacion:function(evento){
        url='AjaxDinamic/Mantenimiento.MedioCaptacionMA@ListMedioCaptacion';
        data={};
        masterG.postAjax(url,data,evento);
    },
};
</script>
