
</div>

<div id="column_right">

		<div class="box">
			<div class="top">Oferta da semana</div>
			<div class="middle" style="text-align:center;">
				<?php
						$deal_retailer = GetDealofWeek();
						$dow_query = "SELECT * FROM cashbackengine_retailers WHERE (retailer_id='".(int)$deal_retailer."' OR deal_of_week='1') AND status='active' ORDER BY RAND() LIMIT 1";
						$dow_result = smart_mysql_query($dow_query);

						if (mysql_num_rows($dow_result) > 0)
						{
							$dow_row = mysql_fetch_array($dow_result);
				?>
					<a href="/view_retailer.php?rid=<?php echo $dow_row['retailer_id']; ?>"><img src="<?php if (!stristr($dow_row['image'], 'http')) echo "/img/"; echo $dow_row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" border="0" alt="<?php echo $dow_row['title']; ?>" title="<?php echo $dow_row['title']; ?>" class="thebest" /></a><br/>
				<?php	} ?>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>

		<div class="box">
			<div class="top">Populares</div>
			<div class="middle">
				<ul id="popular_list">
				<?php
						$tops_query = "SELECT * FROM cashbackengine_retailers WHERE retailer_id<>'".(int)$deal_retailer."' AND status='active' ORDER BY visits DESC LIMIT 5";
						$tops_result = smart_mysql_query($tops_query);
						$tops_total = mysql_num_rows($tops_result);

						if ($tops_total > 0)
						{
							while ($tops_row = mysql_fetch_array($tops_result))
							{
				?>
					<li><a href="/view_retailer.php?rid=<?php echo $tops_row['retailer_id']; ?>"><?php echo $tops_row['title']; ?></a></li>
				<?php
							} 
						}
				?>
				</ul>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>

       <div class="box">
			<div class="top">Navegar por título</div>
			<div class="middle">

				<table class="alphabet" border="0" cellpadding="2" cellspacing="0">
				<?php

					$a = 0;

					foreach ($alphabet as $letter)
					{
						if ($a == 0 || $a%7 == 0) echo "<tr>";
						if (isset($ltr) && $ltr == $letter)
							echo "<td class=\"td_alphabet_active\"><a href=\"/retailers.php?letter=".$letter."\">".$letter."</a></td>";
						else
							echo "<td class=\"td_alphabet\"><a href=\"/retailers.php?letter=".$letter."\">".$letter."</a></td>";
						$a++;
						if ($a%7 == 0 || $a == $numLetters) echo "</tr>";
					}

				?>
				</table>

			</div>
			<div class="bottom">&nbsp;</div>
		</div>

		<div class="box">
			<div class="top">Novas lojas</div>
			<div class="middle">
				<ul id="newest_list">
					<?php

						$n_query = "SELECT * FROM cashbackengine_retailers WHERE status='active' ORDER BY added DESC LIMIT 5";
						$n_result = smart_mysql_query($n_query);
						$n_total = mysql_num_rows($n_result);

						if ($n_total > 0)
						{
							while ($n_row = mysql_fetch_array($n_result))
							{
					?>
								<li><a href="/view_retailer.php?rid=<?php echo $n_row['retailer_id']; ?>"><?php echo $n_row['title']; ?></a> <br><span class="newest_cashback"><?php echo DisplayCashback($n_row['cashback']); ?> CashBack</span></li>
					<?php
							}
						}
					?>
				</ul>
				<div align="right"><a class="more" href="/retailers.php">ver mais...</a></div>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>


		<div class="box">
			<div class="top">Siga nos em</div>
			<div class="middle">
				<div id="social">
					<?php if (FACEBOOK_PAGE != "") { ?><a href="<?php echo FACEBOOK_PAGE; ?>" class="facebook_icon" target="_blank" rel="nofollow">Facebook</a><?php } ?>
					<?php if (TWITTER_PAGE != "") { ?><a href="<?php echo TWITTER_PAGE; ?>" class="twitter_icon" target="_blank" rel="nofollow">Twitter</a><?php } ?>
					<a href="/rss.php" class="rss_icon">RSS Feed</a>
				</div>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>


</div>

<div id="footer">

	<a href="/aboutus.php">Sobre nós</a> &middot; <a href="/news.php">Notícias</a> &middot; <a href="/terms.php">Termos &amp; Condições</a> &middot; <a href="/privacy.php">Política de Privacidade</a> &middot; <a href="/contact.php">Contate-nos</a> &middot; <a href="/rss.php" class="rss">RSS Feed</a>
	<p>&copy; 2015 <?php echo SITE_TITLE; ?>. Todos os direitos reservados.</p>

	<!-- Do not remove this copyright notice! -->
		<div class="powered-by-spacetek"> Powered by <a href="http://www.casheiro.com/spacetek" title="SPACE TEK " target="_blank"><font color="#84C315"></font><font color="#5BADFF"> SPACE TEK</font></a><div>
	<!-- Do not remove this copyright notice! -->

</div>

</div>

</body>
</html>