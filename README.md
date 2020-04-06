# Catena
A plugin to rate a service from 1 to 5 starts thought a shortcode [top_list_render], and through a rest-api call:

Nonce (ufficial):  
http://(yourwebsite)/routeapi/

Basic Authentication:
admin: youradminname  
password: yourpassword  

JWT:
file.js  
'Content-type': 'application/json',  
'Authorization': 'JWT_AUTH_SECRET_KEY'  

wp-config.php  
define('JWT_AUTH_SECRET_KEY', 'yoursaltystring');  
define('JWT_AUTH_CORS_ENABLE', true);  
