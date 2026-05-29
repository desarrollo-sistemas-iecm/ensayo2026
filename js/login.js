

$(document).ready(function() {

   
    

    $("#loginform").submit(function(event) {

    	event.preventDefault();

    	fnlogin(event);


       
    });


});


function fnlogin(event){


		
    	
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        if (event.isDefaultPrevented() == false) {
        	document.getElementById("maincontainer").innerHTML = "";

        //return;
    	}
    	else{
    		


	        var xmlhttp = new XMLHttpRequest();

	        xmlhttp.onreadystatechange = function() {
	        	//alert( xmlhttp.readyState+" <-> "+xmlhttp.status);
	            if (this.readyState == 4 && this.status == 200) {
	                

	                var obj = JSON.parse(this.responseText);

	                //{"success":true,"message":"Success! + admin"}

	                document.getElementById("maincontainer").innerHTML = obj.main;

	                document.getElementById("topmenu").innerHTML = obj.menu;

	                //document.getElementById("maincontainer").innerHTML = jsonResponse;

					


	            }
	        };

	        xmlhttp.open("POST", "login.php", true);
	        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	        datos="usr="+document.getElementById("usr").value+"&pwd="+document.getElementById("pwd").value;
	        //datos="ddd";
	        xmlhttp.send(datos);
	       // xmlhttp.send("usr=datos");

 

        
    	}
}