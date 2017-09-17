/**
 * Created by vietnguyenswin on 9/17/17.
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
function add_item_to_inventory()
{
    if(xHRObject)
    {
        var url = "add_item_to_inventory.php";
        var id = document.getElementById("inv_itemID").value;
        var itm_quantity = document.getElementById("inv_quantity").value;
        var itm_purchased_price = document.getElementById("inv_purchased_price").value;
        var itm_selling_price = document.getElementById("inv_selling_price").value;

        var vars = "inv_itemID="+encodeURIComponent(id)+"&inv_quantity="+encodeURIComponent(itm_quantity)+"&inv_purchased_price="+encodeURIComponent(itm_purchased_price)+"&inv_selling_price="+encodeURIComponent(itm_selling_price);
        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_add_item_to_inventory").innerHTML = return_data;
            }
        }
    }
    return false;
}