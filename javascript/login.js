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
function login_ajax()
{
    if(xHRObject)
    {
        var url = "php/login.php";

        var id = document.getElementById("login_id").value;
        var pw = document.getElementById("login_pass").value;

        var vars = "login_id="+encodeURIComponent(id)+"&login_pass="+encodeURIComponent(pw);

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
                    return document.getElementById("echo_login").innerHTML = return_data;
                } else
                {
                    window.location.href="home.php";
                }
            }
        }
    }
    return false;
}