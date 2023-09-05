server { 
    listen 80;    
	server_name pma.ru;

	error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;	

	root   /var/www/pma.ru;
	index  index.php;		
	
	location ~ /\. {
		deny all;
		break; 		
	}		
	location / {
		charset UTF-8; 
		try_files $uri $uri/ /index.php$is_args$args;		
	}	
	location ~ \.php$ {
		# fastcgi_split_path_info ^(.+\.php)(.*)$;
		fastcgi_pass php_sdbn:9000;
		fastcgi_index  index.php;

		fastcgi_param  DOCUMENT_ROOT    /var/www/pma.ru;
		fastcgi_param  SCRIPT_FILENAME  /var/www/pma.ru$fastcgi_script_name;
		fastcgi_param  PATH_TRANSLATED  /var/www/pma.ru$fastcgi_script_name;

		include fastcgi_params;
		fastcgi_param  QUERY_STRING     $query_string;
		fastcgi_param  REQUEST_METHOD   $request_method;
		fastcgi_param  CONTENT_TYPE     $content_type;
		fastcgi_param  CONTENT_LENGTH   $content_length;
		fastcgi_intercept_errors        on;
		fastcgi_ignore_client_abort     off;
		fastcgi_connect_timeout 60;
		fastcgi_send_timeout 180;
		fastcgi_read_timeout 180;
		fastcgi_buffer_size 128k;
		fastcgi_buffers 4 256k;
		fastcgi_busy_buffers_size 256k;
		fastcgi_temp_file_write_size 256k;
	}	
}

