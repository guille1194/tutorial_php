  </div><br><br>
    
    <footer class="text-center" id="footer">&copy; Payne's store</footer>
    <script>
        function actualizarTamaños(){
            alert("esta trabajando");
            var tamañoString = '';
            for(var i=1;i<=1;i++){
                if(jQuery('#size'+i).val() != ''){
                    tamañoString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+',';
                }
            }
            jQuery('#sizes').val(tamañoString);
        }
        
        function obtener_opc_hijo(seleccionado){
            if(typeof seleccionado == 'undefined'){
                var seleccionado = '';
            }
            
            var padreID = jQuery('#parent').val();
            jQuery.ajax({
                url: '/tutorial/admin/parsers/child_categories.php',
                type: 'POST',
                data: {padreID : padreID, seleccionado: seleccionado},
                success: function(data){
                    jQuery('#child').html(data);
        },
        error: function(){alert("Algo esta mal con las opcoiones hijo")},
            }):
        }
        jQuery('select[name="padre"]').change(obtener_opc_hijo);
</script>
    </body>
</html>