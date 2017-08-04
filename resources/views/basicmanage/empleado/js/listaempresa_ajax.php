<script type="text/javascript">
var AjaxListaempresa={
    Cargar:function(evento){
        if( LEfiltrosG!='' ){
          filtros=LEfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaempresaForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaempresaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaempresaForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.EmpresaBM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
