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
//This function will trigger functions in bidding.php that will display listing details on listing.htm
function display_inventory()
{
    if(xHRObject)
    {
        var url = "php/display_inventory.php";
        //GET method because the request is not sending data to the server
        xHRObject.open("GET", url, true);
        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                document.getElementById("echo_display_inventory").innerHTML = return_data;
            }
        }
        xHRObject.send(null);
    }
    return false;
}