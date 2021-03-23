<nav>
	<h1><span class="up">F</span> <span class="up">l</span> <span class="up">i</span> <span class="up">p</span> <span class="up">p</span> <span class="up">i</span> <span class="up">t</span></h1>
	<span id="arrow" class="purple large left"><i class="fas fa-angle-right"></i></span>
	<ul id = "nav">
		<li><a id="navHome" href="homePage.php" class="navbutton active">Home</a></li>
		<li><a id="navGame" href="gamePage.php" class="navbutton">Start Game</a></li>

		<?php if (isset($_SESSION['logged'])&& $_SESSION['logged']): ?>
		<li><a id="navProfile" href="profilePage.php" class="navbutton">Profile</a></li>
		<li><a id="navLogout" href="homePage.php" class="navbutton">Logout</a></li>
		<?php endif; ?>
	</ul>
</nav>