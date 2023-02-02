<script type="text/javascript">
var AjaxBandeja={
    BandejaValida: (evento,pag) => {
        if( typeof(pag)!='undefined' ){
            $("#BandejaValidaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaValidaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaValidaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.MatriculaPR@BandejaValida';
        masterG.postAjax(url,data,evento);
    },
    BandejaHistorica: (evento,pag) => {
        if( typeof(pag)!='undefined' ){
            $("#BandejaHistoricaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        data=$("#BandejaHistoricaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#BandejaHistoricaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/Proceso.MatriculaPR@BandejaHistorica';
        masterG.postAjax(url,data,evento);
    },
    LoadPagos: (evento) => {
        data={ matricula_id: MatriculaG.Id };
        url='AjaxDinamic/Reporte.SeminarioEM@LoadPagos';
        masterG.postAjax(url,data,evento);
    },
    LoadCuotas: (evento) => {
        data={ matricula_id: MatriculaG.Id };
        url='AjaxDinamic/Proceso.MatriculaPR@LoadCuotas';
        masterG.postAjax(url,data,evento);
    },
    ActualizaEstadoMat: (evento) =>{
        data={ matricula_id: MatriculaG.Id, estado_mat: MatriculaG.Estado_Mat, observacion: MatriculaG.Observacion };
        url='AjaxDinamic/Proceso.MatriculaPR@ActualizaEstadoMat';
        masterG.postAjax(url,data,evento);
    },
};
</script>
