<?php 
//Disables the Visual tab of the TinyMCE editor entirely
add_filter( 'user_can_richedit' , '__return_false', 50 );
