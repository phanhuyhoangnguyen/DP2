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
function add_item()
{
    if(xHRObject)
    {
        var url = "php/add_item.php";
        var itm_id = document.getElementById("itemID").value;
        var itm_name = document.getElementById("item_name").value;
        var itm_cat = document.getElementById("item_category").value;
        var vars = "itemID="+encodeURIComponent(itm_id)+"&item_name="+encodeURIComponent(itm_name)+"&item_category="+encodeURIComponent(itm_cat);

        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_add_item").innerHTML = return_data;
            }
        }
    }
    return false;
}