/*

/---------------------------------------------------------/

    Task: Restock Reminder

    Date Created: 26 - Sep - 2017

    Author: Don Dave (Duy The Nguyen)

    Last Modified: 17:46 30 - Sep -2017

 /---------------------------------------------------------/

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

//This function will trigger functions in restock_reminder.php that will display listing details on restock_reminder.html

function restock_reminder()

{

    if(xHRObject)

    {

        var url = "php/restock_reminder.php";

        //GET method because the request is not sending data to the server

        xHRObject.open("GETs", url, true);

        xHRObject.onreadystatechange = function ()

        {

            if ((xHRObject.readyState == 4) && (xHRObject.status == 200))

            {
                document.getElementById("echo_restocks_reminder").innerHTML = return_data;
            }

        }

        xHRObject.send(null);

    }

    return false;

}