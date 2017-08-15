<script type="text/javascript">
var AjaxListadocente={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListadocenteForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LDfiltrosG!='' ){
          filtros=LDfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListadocenteForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListadocenteForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListadocenteForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.DocenteEM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
