<script type="text/javascript">
var AjaxListaempresa={
    Cargar:function(evento,pag){
        if( typeof(pag)!='undefined' ){
            $("#ListaempresaForm").append("<input type='hidden' value='"+pag+"' name='page'>");
        }
        if( LEfiltrosG!='' ){
          filtros=LEfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaempresaForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaempresaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaempresaForm input[type='hidden']").remove();
        url='AjaxDinamic/ExpertManage.EmpresaEM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
