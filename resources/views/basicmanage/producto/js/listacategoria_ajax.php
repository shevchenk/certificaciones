<script type="text/javascript">
var AjaxListacategoria={
    Cargar:function(evento){
        if( LPfiltrosG!='' ){
          filtros=LPfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListacategoriaForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListacategoriaForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#Listacategoria input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.CategoriaBM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
