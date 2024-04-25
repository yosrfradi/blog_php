<?php
define('USER',"root");
define('PASSWD',"");
define('SERVER',"localhost");
define('BASE',"bd_ds2web");
?>


<!--
CREATE TABLE posts (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
content TEXT NOT NULL
);

CREATE TABLE comments (
id INT AUTO_INCREMENT PRIMARY KEY,
post_id INT NOT NULL,
text TEXT NOT NULL,
author VARCHAR(100) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
-->

