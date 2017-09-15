/**
 * Created by vietnguyenswin on 9/15/17.
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
function fetching_record()
{
    if(xHRObject)
    {
        var url = "fetching_record.php";
        var id = document.getElementById("rt_order").value;
        var vars = "saleid="+encodeURIComponent(id);
        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                alert(return_data);
                //return document.getElementById("status").innerHTML = return_data;
            }
        }
    }
    return false;
}