<script type="text/javascript">
var my_skinsG = [
    "skin-blue",
    "skin-black",
    "skin-red",
    "skin-yellow",
    "skin-purple",
    "skin-green",
    "skin-blue-light",
    "skin-black-light",
    "skin-red-light",
    "skin-yellow-light",
    "skin-purple-light",
    "skin-green-light"
];

var skins_listG = $("<ul />", {"class": 'list-unstyled clearfix'});
  //Dark sidebar skins
var skin_blueG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-blue' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></span><span class='bg-light-blue' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Blue</p>");
skins_listG.append(skin_blueG);
var skin_blackG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-black' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div style='box-shadow: 0 0 2px rgba(0,0,0,0.1)' class='clearfix'><span style='display:block; width: 20%; float: left; height: 7px; background: #fefefe;'></span><span style='display:block; width: 80%; float: left; height: 7px; background: #fefefe;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Black</p>");
skins_listG.append(skin_blackG);
var skin_purpleG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-purple' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-purple-active'></span><span class='bg-purple' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Purple</p>");
skins_listG.append(skin_purpleG);
var skin_greenG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-green' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-green-active'></span><span class='bg-green' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Green</p>");
skins_listG.append(skin_greenG);
var skin_redG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-red' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-red-active'></span><span class='bg-red' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Red</p>");
skins_listG.append(skin_redG);
var skin_yellowG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-yellow' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-yellow-active'></span><span class='bg-yellow' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin'>Yellow</p>");
skins_listG.append(skin_yellowG);
//Light sidebar skins
var skin_blue_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-blue-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></span><span class='bg-light-blue' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px'>Blue Light</p>");
skins_listG.append(skin_blue_lightG);
var skin_black_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-black-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div style='box-shadow: 0 0 2px rgba(0,0,0,0.1)' class='clearfix'><span style='display:block; width: 20%; float: left; height: 7px; background: #fefefe;'></span><span style='display:block; width: 80%; float: left; height: 7px; background: #fefefe;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px'>Black Light</p>");
skins_listG.append(skin_black_lightG);
var skin_purple_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-purple-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-purple-active'></span><span class='bg-purple' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px'>Purple Light</p>");
skins_listG.append(skin_purple_lightG);
var skin_green_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-green-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-green-active'></span><span class='bg-green' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px'>Green Light</p>");
skins_listG.append(skin_green_lightG);
var skin_red_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-red-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-red-active'></span><span class='bg-red' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px'>Red Light</p>");
skins_listG.append(skin_red_lightG);
var skin_yellow_lightG =
  $("<li />", {style: "float:left; width: 33.33333%; padding: 5px;"})
      .append("<a href='javascript:void(0);' data-skin='skin-yellow-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
      + "<div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-yellow-active'></span><span class='bg-yellow' style='display:block; width: 80%; float: left; height: 7px;'></span></div>"
      + "<div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>"
      + "</a>"
      + "<p class='text-center no-margin' style='font-size: 12px;'>Yellow Light</p>");
skins_listG.append(skin_yellow_lightG);

$(document).ready(function() {
    redimensionG.validar();
    var opcionesm="<?php echo session('opciones'); ?>";
    var validarutaurlm="<?php echo $valida_ruta_url; ?>";
    var iconom='fa fa-dashboard';
    if( opcionesm.split(validarutaurlm).length>1 ){
      iconom=opcionesm.split(validarutaurlm)[1].split("|")[1];
    }
    else if( validarutaurlm=='secureaccess.myself' ){
      iconom="fa fa-lock";
    }
    $("ol.breadcrumb>li>i").removeClass().addClass("fa "+iconom);

    var tmp = masterG.get('skin');
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
      $('.selectpicker').selectpicker('mobile');
    }
    
    if (tmp && $.inArray(tmp, my_skinsG))
        masterG.change_skin(tmp);

    $.AdminLTE.pushMenu.expandOnHover();
    $("#tema-body").after(skins_listG);
    
    $("[data-skin]").on('click', function (e) {
      if($(this).hasClass('knob'))
        return;
      e.preventDefault();
      masterG.change_skin($(this).data('skin'));
    });

    $('ul.sidebar-menu>li').each(function(indice, elemento) {
        htm=$(elemento).html();
        if(htm.split('<a href="'+validarutaurlm+'"').length>1){
            $(elemento).addClass('active').addClass('menu-open');
            $(elemento).find('li').each(function(ind,ele) {
              htm=$(ele).html();
              if(htm.split('<a href="'+validarutaurlm+'"').length>1){
                $(ele).addClass('active');
              }
            });
        }

        if( "<?php echo $valida_ruta_url; ?>"=="secureaccess.inicio" ){
          msjG.mensaje('success','Bienvenido',3000);
        }
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

ActualizarPrivilegioG=function(v){
    url='ReportDinamic/SecureAccess.PersonaSA@ActualizarPrivilegio?privilegio_id='+v;
    window.location=url;
}

ActualizarEmpresaG=function(v){
    url='ReportDinamic/SecureAccess.PersonaSA@ActualizarEmpresa?empresa_id='+v;
    window.location=url;
}

HTMLActualizarPrivilegioG=function(result){
  window.onload();
}

var masterG ={
    change_skin:function(cls) {
        $.each(my_skinsG, function (i) {
          $("body").removeClass(my_skinsG[i]);
        });

        $("body").addClass(cls);
        masterG.store('skin', cls);
        return false;
    },
    store:function(name, val) {
        if (typeof (Storage) !== "undefined") {
            localStorage.setItem(name, val);
        } else {
            window.alert('Please use a modern browser to properly view this template!');
        }
    },
    get:function(name) {
        if (typeof (Storage) !== "undefined") {
            return localStorage.getItem(name);
        } else {
            window.alert('Please use a modern browser to properly view this template!');
        }
    },
    postAjax:function(url,data,eventsucces,eventbefore,syncr){
            if( typeof syncr== 'undefined' ){
                  syncr=true;
            }
      $.ajax({
            url         : url,
            type        : 'POST',
            cache       : false,
            dataType    : 'json',
            data        : data,
            async       : syncr,
            beforeSend : function() {
                $(".content .box-body").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $(".content .row .btn").attr('disabled','true')
                if( typeof eventbefore!= 'undefined' && eventbefore!=null){
                  eventbefore();
                }
            },
            success : function(r) {
                $(".content .box-body .overlay").remove();
                $(".content .row .btn").removeAttr('disabled')
                if( typeof eventsucces!= 'undefined' && eventsucces!=null){
                  eventsucces(r);
                }
            },
            error: function(result){
                $(".content .box-body .overlay").remove();
                $(".content .row .btn").removeAttr('disabled')
                if( typeof(result.status)!='undefined' && result.status==401 && result.statusText=='Unauthorized' ){
                    msjG.mensaje('warning','Su sesión a caducado',4000);
                }
                else{
                    msjG.mensaje('danger','',3000);
                }

            }
        });
    },
    CargarPaginacion:function(HTML,ajax,result,id){
        if( typeof(ajax.split(".")[1]) == 'undefined' ){
            ajax = ajax+".Cargar";
        }
        var html='<ul class="pagination">';
        if( result.current_page==1 ){
            html+=  '<li class="paginate_button previous disabled">'+
                        '<a>Atras</a>'+
                    '</li>';
        }
        else{
            html+=  '<li class="paginate_button previous" onClick="'+ajax+'('+HTML+','+(result.current_page-1)+');">'+
                        '<a>Atras</a>'+
                    '</li>';
        }
        var ini=1; var fin=result.last_page;
        if( result.last_page>5 ){
            if( result.last_page-3<=result.current_page ){
                ini=result.last_page-4;
            }
            else if( result.current_page<5 ){
                fin=5;
            }
            else{
                ini=result.current_page-1;
                fin=result.current_page+1;
            }
        }

        if( (ini>1 && result.current_page>4) || (result.last_page-3<=result.current_page && result.current_page<=4 && ini>1) ){
            html+=  '<li class="paginate_button" onClick="'+ajax+'('+HTML+',1);">'+
                        '<a>1</a>'+
                    '</li>';
            html+=  '<li class="paginate_button disabled"><a>…</a></li>';
        }
        for(i=ini; i<=fin; i++){
            if( i==result.current_page ){
                html+=  '<li class="paginate_button active">'+
                            '<a>'+i+'</a>'+
                        '</li>';
            }
            else{
                html+=  '<li class="paginate_button" onClick="'+ajax+'('+HTML+','+i+');">'+
                            '<a>'+i+'</a>'+
                        '</li>';
            }
        }
        if( fin>=5 && result.last_page>5 && result.last_page-3>result.current_page){
            html+=  '<li class="paginate_button disabled"><a>…</a></li>';
            html+=  '<li class="paginate_button" onClick="'+ajax+'('+HTML+','+result.last_page+');">'+
                        '<a>'+result.last_page+'</a>'+
                    '</li>';
        }

        if( result.current_page==result.last_page ){
            html+=  '<li class="paginate_button next disabled">'+
                        '<a>Siguiente</a>'+
                    '</li>';
        }
        else{
            html+=  '<li class="paginate_button next" onClick="'+ajax+'('+HTML+','+(result.current_page*1+1)+');">'+
                        '<a>Siguiente</a>'+
                    '</li>';
        }
        html+='</ul>';

        $(id).append(html);
    },
    enterGlobal:function(e,etiqueta,selecciona){
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==13){
            e.preventDefault();
            $(etiqueta).click(); 
            if( typeof(selecciona)!='undefined' ){
                $(etiqueta).focus(); 
            }
        }
    },
    validaNumerosMax:function(e,t,max){ 
        tecla = (document.all) ? e.keyCode : e.which;//captura evento teclado
        if (tecla==8 || tecla==0) return true;//8 barra, 0 flechas desplaz
        if(t.value.length>=max)return false;
        patron = /\d/; // Solo acepta números
        te = String.fromCharCode(tecla); 
        return patron.test(te);
    },
    validaLetras:function(e) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8 || tecla==0) return true;//8 barra, 0 flechas desplaz
        patron =/[A-Za-zñÑáéíóúÁÉÍÓÚ\s]/; // 4 ,\s espacio en blanco, patron = /\d/; // Solo acepta números, patron = /\w/; // Acepta números y letras, patron = /\D/; // No acepta números, patron =/[A-Za-z\s]/; //sin ñÑ
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    },
    validaAlfanumerico:function(e) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8 || tecla==0 || tecla==46) return true;//8 barra, 0 flechas desplaz
        patron =/[A-Za-zñÑáéíóúÁÉÍÓÚ@.,_\-\s\d]/; // 4 ,\s espacio en blanco, patron = /\d/; // Solo acepta números, patron = /\w/; // Acepta números y letras, patron = /\D/; // No acepta números, patron =/[A-Za-z\s]/; //sin ñÑ
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    },
    validaNumeros:function(e) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8 || tecla==0) return true;//8 barra, 0 flechas desplaz
        patron = /\d/; // Solo acepta números
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    },
    validaDecimal:function(e,t) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        pos=t.value.indexOf('.');
        if ( pos!= -1 && tecla == 46 ) return false;// Valida si se registro nuevament el
        if ((tecla == 8 || tecla == 0 || tecla == 46)) return true;
        if (tecla <= 47 || tecla >= 58) return false;
        patron = /\d/; // Solo acepta números
        te = String.fromCharCode(tecla); // 5
        return patron.test(te);
    },
    DecimalMax:function(t,n){
        pos=t.value.indexOf('.');
        if( pos!= -1 && t.value!='' && t.value.substring(pos+1).length>=2 ){
          t.value = parseFloat(t.value).toFixed(n);
        }
    },
    SelectImagen: function(archivo,src,carga){
      if( $.trim(archivo)!='' && archivo.split('.')[1]=='pdf' ){
        $(src).attr('src','archivo/pdf.jpg');
      }
      else if( $.trim(archivo)!='' && (archivo.split('.')[1]=='docx' || archivo.split('.')[1]=='doc') ){
        $(src).attr('src','archivo/word.png');
      }
      else if( $.trim(archivo)!='' && (archivo.split('.')[1]=='xlsx' || archivo.split('.')[1]=='xls') ){
        $(src).attr('src','archivo/excel.jpg');
      }
      else if( $.trim(archivo)!='' && (archivo.split('.')[1]=='pptx' || archivo.split('.')[1]=='ppt') ){
        $(src).attr('src','archivo/ppt.png');
      }
      else if( $.trim(archivo)!='' && archivo.split('.')[1]=='txt' ){
        $(src).attr('src','archivo/txt.jpg');
      }
      else{
        $(src).attr('src',archivo);
      }
        $(carga).removeAttr('href').removeAttr('target');
        if( $.trim(archivo)!='' ){
          $(carga).attr('href',archivo).attr('target','__blank');
        }
    },
    onImagen: function (ev,nombre,archivo,src) {
        var files = ev.target.files || ev.dataTransfer.files;
        if (!files.length)
            return;
        var image = new Image();
        var reader = new FileReader();
        const d = new Date();
        let time = d.getTime();
        reader.onload = (e) => {
            $(archivo).val(e.target.result);
            if(files[0].name.split('.')[1]=='pdf'){
              $(src).attr('src','archivo/pdf.jpg?time='+time);
            }
            else if(files[0].name.split('.')[1]=='docx' || files[0].name.split('.')[1]=='doc'){
              $(src).attr('src','archivo/word.png?time='+time);
            }
            else if(files[0].name.split('.')[1]=='xlsx' || files[0].name.split('.')[1]=='xls'){
              $(src).attr('src','archivo/excel.jpg?time='+time);
            }
            else if(files[0].name.split('.')[1]=='pptx' || files[0].name.split('.')[1]=='ppt'){
              $(src).attr('src','archivo/ppt.png?time='+time);
            }
            else if(files[0].name.split('.')[1]=='txt'){
              $(src).attr('src','archivo/txt.jpg?time='+time);
            }
            else{
              $(src).attr('src',e.target.result);
            }
            $(src).fadeOut(1000,function(){
                $(src).fadeIn(1800);
            });
        };
        reader.onprogress=() => {
            //msjG.mensaje('warning','Cargando yo ando',2000);
        }
        reader.readAsDataURL(files[0]);
        $(nombre).val(files[0].name);
        console.log(files[0].name);
    },
    Limpiar: function(ids){ 
      $(ids).val('');
    }
}

var msjG = {
    mensaje: function (tipo, texto, tiempo) {
      var img=tipo;
        if(tipo=="success"){
          img="check";
        }
        else if(tipo=="danger"){
          img="ban";
        }
        if (tipo == 'danger' && texto.length == 0) {
            texto = 'Ocurrio una interrupción en el proceso, favor de intentar nuevamente.';
        }
        etiqueta='msjG';
        $('.'+etiqueta).html('<div class="alert alert-dismissable alert-' + tipo + '">' +
                '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>' +
                '<h4><i class="icon fa fa-'+img+'"> '+texto+'</h4>'+
                '</div>');
        $('.'+etiqueta).slideToggle(500);
        $('.'+etiqueta).fadeOut(tiempo);
    },
}

var sweetalertG = {
    confirm: function (titulo, descripcion, consulta) {
      swal({
          title: titulo,
          text: descripcion,
          showCancelButton: true,
          confirmButtonText: "Procesar",
          closeOnConfirm: true
      },
      consulta
      );
    },
    alert: function (titulo, descripcion, consulta) {
      swal({
          type: 'success',
          title: titulo,
          text: descripcion,
          closeOnConfirm: true,
      },
      consulta
      );
    },
    pregunta: function(titulo, descripcion, consulta){
      swal({
          title: titulo,
          text: descripcion,
          showCancelButton: true,
          confirmButtonText: 'Si',
          cancelButtonText: 'No',
          closeOnConfirm: true
      },
      consulta
      );
    }
}

var redimensionG = {
    validar: function(){
      //var src = $("#imageCurso").attr('src').split('/');
      if ($('header').width() <= 400 ){
          $("section.sidebar").css({"margin-top":"110px"});
      }
      else if ($('header').width() <= 600 ){
          $("section.sidebar").css({"margin-top":"110px"});
      }
      else if ($('header').width() <= 800 ){
          $("section.sidebar").css({"margin-top":"30px"});
      }
      else if ($('header').width() <= 1000 ){
          
      }
      else if ($('header').width() > 1000 ){
          
      }
    }
}
$(window).resize(function(){
    redimensionG.validar();
});

const msjG2 ={
    //TODO: icons: success, error, warning, info, question
    alert: (icon, title, time)=> {
        btn=icon;
        if( icon=='error' ){ btn='danger'; }
        
        Swal.fire({
          icon: icon,
          title: title,
          showConfirmButton: true,
          timer: time,
          showClass: {
            popup: 'animated fadeInDown slow'
          },
          hideClass: {
            popup: 'animated fadeOutUp slow'
          },
          allowEscapeKey: false,
          allowOutsideClick: false,
          buttonsStyling: false,
          customClass: {
            confirmButton: 'btn btn-lg btn-'+btn,
          },
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        });
    },
    question: (title, question, fn)=> {
        Swal.fire({
          icon: 'question',
          title: title,
          html: question,
          showClass: {
            popup: 'animated fadeInDown slow'
          },
          hideClass: {
            popup: 'animated fadeOutUp slow'
          },
          showCancelButton: true,
          confirmButtonText: 'SI',
          cancelButtonText: 'NO',
          reverseButtons: true,
          customClass: {
            confirmButton: 'btn btn-lg btn-success mg-15',
            cancelButton: 'btn btn-lg btn-danger'
          },
          allowEscapeKey: false,
          allowOutsideClick: false,
          buttonsStyling: false,
        }).then((result) => {
            if (result.value) {
              fn();
            }
        });
    },
    alertfn: (icon, title, time, fn)=> {
      Swal.fire({
        icon: icon,
        title: title,
        html: 'Cerrando en <b>2000</b> milisegundo(s).',
        timer: time,
        showConfirmButton: false,
        timerProgressBar: true,
        showClass: {
          popup: 'animated fadeInDown slow'
        },
        hideClass: {
          popup: 'animated fadeOutUp slow'
        },
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading()
          msjIntervalG = setInterval(() => {
            const content = Swal.getHtmlContainer()
            console.log('hola');
            if (content) {
              const b = content.querySelector('b')
              if (b) {
                b.textContent = Swal.getTimerLeft()
              }
            }
          }, 100)
        },
        didDestroy: () => {
          clearInterval(msjIntervalG)
        }
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          fn();
        }
      })
    }
}

</script>
