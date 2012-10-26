<?php 
$output = shell_exec('git pull');
echo "shell_exec: <pre>$output</pre>"; 

$output = exec('git pull');
echo "exec: <pre>$output</pre>"; 

$output = system('git pull', $a);
echo "exec: <pre>$output + $a</pre>"; 
?>