var xHRObject = false;
if (window.XMLHttpRequest)
{
    xHRObject = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}
function predict_ajax()
{
    if(xHRObject)
    {
        var url = "php/predict.php";

        var day = document.getElementById("day").value;

        var vars = "selected_day="+encodeURIComponent(day);

        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                if (return_data != "")
                {
                    //alert(return_data);
                    return document.getElementById("predict_result").innerHTML = return_data;
                }
            }
        }
    }
    return false;
}