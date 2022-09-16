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
        masterG.postAjax(url,data,evento,null,false);
    },
    ActualizaEstadoMat: (evento) =>{
        data={ matricula_id: MatriculaG.Id, estado_mat: MatriculaG.Estado_Mat, observacion: MatriculaG.Observacion };
        url='AjaxDinamic/Proceso.MatriculaPR@ActualizaEstadoMat';
        masterG.postAjax(url,data,evento);
    },
    ActualizaMat: (evento) =>{
        $("#FormBandeja").append("<input type='hidden' value='"+MatriculaG.Id+"' name='matricula_id'>");
        data=$("#FormBandeja").serialize().split("txt_").join("").split("slct_").join("");
        $("#FormBandeja input[type='hidden']").not('.mant').remove();

        url='AjaxDinamic/Proceso.MatriculaPR@ActualizaMat';
        masterG.postAjax(url,data,evento);
    },
    ListaProgramacion: (evento,pag) =>{
        if( typeof(pag)!='undefined' ){
            $("#ListaprogramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( MatriculaG.LDfiltrosG!='' ){
          dfiltros = MatriculaG.LDfiltrosG.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaprogramacionForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        $("#ListaprogramacionForm").append("<input type='hidden' value='1' name='estado_filtro'>");
        data=$("#ListaprogramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaprogramacionForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
};
</script>
