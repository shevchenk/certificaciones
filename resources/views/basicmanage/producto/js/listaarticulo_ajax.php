<script type="text/javascript">
var AjaxListaarticulo={
    Cargar:function(evento){
        if( LPfiltrosG!='' ){
          filtros=LPfiltrosG;
          dfiltros=filtros.split("|");
          for(i=0;i<dfiltros.length;i++){
              $("#ListaarticuloForm").append("<input type='hidden' value='"+dfiltros[i].split(":")[1]+"' name='"+dfiltros[i].split(":")[0]+"'>");
          }
        }
        data=$("#ListaarticuloForm").serialize().split("txt_").join("").split("slct_").join("");
        $("#Listaarticulo input[type='hidden']").not('.mant').remove();
        url='AjaxDinamic/BasicManage.ArticuloBM@Load';
        masterG.postAjax(url,data,evento);
    }
};
</script>
