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
function register_ajax()
{
    if(xHRObject)
    {
        var url = "php/register.php";

        var name = document.getElementById("full_name").value;
        var id = document.getElementById("reg_id").value;
        var pw = document.getElementById("reg_pass").value;
        var pw2 = document.getElementById("confirm_password").value;
        var em = document.getElementById("email").value;
        var r = document.getElementById("role").value;

        var vars = "full_name="+encodeURIComponent(name)+"&reg_id="+encodeURIComponent(id)+"&reg_pass="+encodeURIComponent(pw)
                    +"&confirm_password="+encodeURIComponent(pw2)+"&email="+encodeURIComponent(em)
                    +"&role="+encodeURIComponent(r);

        xHRObject.open("POST", url, true);
        xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xHRObject.send(vars);

        xHRObject.onreadystatechange = function ()
        {
            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))
            {
                var return_data = xHRObject.responseText;
                //alert(return_data);
                return document.getElementById("echo_register").innerHTML = return_data;
            }
        }
    }
    return false;
}