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
function display_month_report()
{
    if(xHRObject)
    {
        var url = "php/display_month_report.php";

        var month = document.getElementById("select_month").value;
        var year = document.getElementById("select_year").value;
        var view = document.getElementById("display_option").value;

        var vars = "select_month="+encodeURIComponent(month)+"&select_year="+encodeURIComponent(year)+"&display_option="+encodeURIComponent(view);
        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_month_report").innerHTML = return_data;
            }
        }
    }
    return false;
}