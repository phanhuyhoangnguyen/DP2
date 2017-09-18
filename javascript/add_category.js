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
function add_category()
{
    if(xHRObject)
    {
        var url = "php/add_category.php";
        var cat_id = document.getElementById("categoryID").value;
        var cat_name = document.getElementById("category_name").value;
        var vars = "categoryID="+encodeURIComponent(cat_id)+"&category_name="+encodeURIComponent(cat_name);

        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_add_category").innerHTML = return_data;
            }
        }
    }
    return false;
}