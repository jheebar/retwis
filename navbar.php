<div id="navbar">
<a href="index.php">home</a>
| <a href="timeline.php">timeline</a>
<?php
f(isLoggedIn()) {?>
| <a href="logout.php">logout</a>
<?php
?>
</div>
