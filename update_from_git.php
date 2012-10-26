<?php $output = shell_exec('git pull');
echo "<pre>$output</pre>"; ?>
# runs git pull command in current catalog