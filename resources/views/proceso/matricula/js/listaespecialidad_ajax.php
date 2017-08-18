<script type="text/javascript">
var AjaxListaespecialidad={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaespecialidadForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LDfiltrosG!='' ){
          filtros=LDfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaespecialidadForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaespecialidadForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaespecialidadForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.EspecialidadEM@ListEspecialidadDisponible';
        masterG.postAjax(url,data,evento);
    }
};
</script>
