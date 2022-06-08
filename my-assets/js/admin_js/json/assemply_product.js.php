"use strict";
<?php
$cache_file = "product.json";
header('Content-Type: text/javascript; charset=utf8');
?>
var productList = <?php echo file_get_contents($cache_file); ?> ;
var csrf_test_name=  $("#CSRF_TOKEN").val();


function assembly_productList(cName) {
$( ".assemblyproductSelection" ).autocomplete(
{
source: productList,
delay:300,
focus: function(event, ui) {
$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
$(this).val(ui.item.label);
return false;
},
select: function(event, ui) {
$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
$(this).val(ui.item.label);

var id=ui.item.value;
var dataString = 'csrf_test_name='+csrf_test_name+'&assembly_product_id='+ id;
var base_url = $('.baseUrl').val();


$(this).unbind("change");
return false;
}
});

}