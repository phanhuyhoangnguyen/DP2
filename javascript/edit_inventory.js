/**
 * Created by vietnguyenswin on 9/18/17.
 */
var xHRObject = false;
if (window.XMLHttpRequest)
{
    xHRObject = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}
function edit_inv_ajax()
{
    if(xHRObject)
    {
        var url = "php/edit_inventory.php";

        var id = document.getElementById("inv_itemID").value;
        var qty = document.getElementById("inv_quantity").value;
        var pp = document.getElementById("inv_purchased_price").value;
        var sl = document.getElementById("inv_selling_price").value;
        var rs = document.getElementById("inv_update_reason").value;

        var vars = "inv_itemID="+encodeURIComponent(id)+"&inv_quantity="+encodeURIComponent(qty)
            +"&inv_purchased_price="+encodeURIComponent(pp)
            +"&inv_selling_price="+encodeURIComponent(sl)
            +"&inv_update_reason="+encodeURIComponent(rs);

        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_edit_inventory").innerHTML = return_data;
            }
        }
    }
    return false;
}