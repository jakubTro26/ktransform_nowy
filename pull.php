<?php

// Use in the “Post-Receive URLs” section of your GitHub repo.



if ( $_POST['payload'] ) {
putenv('PATH=/usr/local/bin');
echo shell_exec("cd /home/bitrends/web/bitrends.pl/public_html && /usr/bin/git pull 2>&1");
echo shell_exec('/usr/bin/whoami 2>&1');
}

?>
