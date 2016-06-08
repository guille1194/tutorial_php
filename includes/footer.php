  </div><br><br>
    
    <footer class="text-center" id="footer">&copy; Payne's store</footer>
    
    
    <script>
        jQuery(window).scroll(function(){
            var vscroll = jQuery(this).scrollTop();
            jQuery('#logotexto').css({
                "transform":translate(0px, "+vscroll/2+"px)"
        });
            
    function detailsmodal(id){
        var data = {"id" : id};
        jQuery.ajax({
        url : '/tutorial/includes/detailsmodal.php',
        method : "post",
        data : data,
        success: function(data){
        jQuery('body').append(data);
        jQuery('#details-modal').modal('toggle');
        },
        error: function(){
        alert("algo esta mal!");
        }
        });
    }
     
    function actualizar_carrito(modo,editar_id,editar_tamaño){
        var datos = ("modo" : modo, "editar_id" : editar_id, "editar_tamaño" : editar_tamaño);
        jQuery.ajax({
            url : '/tutorial/admin/parsers/update_cart.php',
            method : "post",
            data : data,
            success : function(){location.reload();},
            error : function(){alert("Algo esta mal.");},
        });
    }
            
    function agregar_al_carrito(){
        jQuery('#modal_errors').html();
        var tamaño = jQuery('#tamaño');
        var cantidad = jQuery('#cantidad');
        var disponible = jQuery('#disponible');
        var error = '';
        var datos = jQuery('#add_product_form').serialize();
        if(tamaño == '' || cantidad == '' || cantidad == 0){
            error += '<p class="text-danger text-center">Debes elegir uns cantidad.</p>';
            return;
        }
        else if(cantidad > disponible){
            error += '<p class="text-danger text-center">Solo hay '+disponible+' disponible.</p>';
            jQuery('#modal_errors').html(error); 
            return;
        }
        else{
            jQuery.ajax({
                url: '/tutorial/admin/parsers/add_cart.php',
                method: 'post',
                data : data,
                success : function(){
                location.reload();
                },
                error : function(){alert("Algo esta mal");}
            });
        }
    }
        }
                              
    </script>
    </body>
</html>