<script type="text/javascript">
var AjaxListatrabajador={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListatrabajadorForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LDfiltrosG!='' ){
          filtros=LDfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListatrabajadorForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListatrabajadorForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListatrabajadorForm input[type='hidden']").remove();
        url='AjaxDinamic/Mantenimiento.TrabajadorEM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
