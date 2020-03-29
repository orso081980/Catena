# Catena
A plugin to rate a service from 1 to 5 starts thought a shortcode [top_list_render], and through a rest-api call:

Basic Authentication:

admin: marco
password: 18081980

JWT:

'Content-type': 'application/json', 
'Authorization': JWT_AUTH_SECRET_KEY 

define('JWT_AUTH_SECRET_KEY', 'yoursaltystring');
define('JWT_AUTH_CORS_ENABLE', true);
