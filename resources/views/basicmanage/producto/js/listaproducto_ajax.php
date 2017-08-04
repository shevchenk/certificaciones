<script type="text/javascript">
var AjaxListaproducto={
    Cargar:function(evento){
        if( LPfiltrosG!='' ){
          filtros=LPfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaproductoForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaproductoForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#ListaproductoForm input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ProductoBM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
