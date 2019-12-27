<script type="text/javascript">
var AjaxListaprogramacion={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaprogramacionForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LDfiltrosG!='' ){
          filtros=LDfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaprogramacionForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaprogramacionForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaprogramacionForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    },
    Cargar2:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaprogramacionForm2").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LDfiltrosG!='' ){
          filtros=LDfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaprogramacionForm2").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaprogramacionForm2").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaprogramacionForm2 input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.ProgramacionEM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
