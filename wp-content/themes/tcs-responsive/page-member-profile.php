<?php get_header(); ?>
<?php



global $activity;
$activity = get_activity_summary ();

$handle = get_query_var ( 'handle' );

$tab = get_query_var ( 'tab' );
$track= "data/srm";

if ($tab == "algo") {

	$track = "data/srm";

}else if ($tab == "develop") {
	$track = "develop";
}else if ($tab == "design") {
	$track = "design";
}

$userkey = get_option ( 'api_user_key' );
$coder = get_member_profile ( $userkey, $handle );
#print_r($coder);

?>
<script type="text/javascript">
<!--
 $(document).ready(function(){
	coder.initMemberEvents();
})
//-->
</script>



<div class="content">
	<div id="main" class="coderProfile">
		<div id="hero">
			<div class="inner">
				<div class="container">
					<article class="aboutCoder">
						<div class="details">
							<figure class="coderPicWrap">
								<img alt="<?php echo $coder->handle;?>" src="<?php echo 'http://community.topcoder.com/' . $coder->photoLink;?>">
							</figure>
							<div class="info">
								<div class="handle">
									<a href="#"><?php echo $coder->handle;?></a>
								</div>
								<div class="country"><?php echo $coder->country; ?></div>
								<div class="memberSince">
									<label>Member Since:</label>
									<div class="val"><?php 
									$memSince = $coder->memberSince; 
									$memSince = str_replace(".","/",$memSince);
									echo date("M d, Y", strtotime($memSince)) ;
									?></div>
								</div>
								<div class="memberSince">
									<label>Total Earnings :</label>
									<div class="val"><?php echo '$'.$coder->overallEarning;?></div>
								</div>
							</div>
						</div>
						<blockquote class="coderQuote">“<?php echo $coder->quote;?>”</blockquote>
						<ul class="social">
							<li><a class="gp" href="<?php echo get_option('gPlusURL'); ?>"></a></li>
							<li><a class="mail" href="<?php echo get_option('linkedInURL'); ?>"></a></li>
							<li><a class="tw" href="<?php echo get_option('twitterURL'); ?>"></a></li>
							<li><a class="fb" href="<?php echo get_option('facebookURL'); ?>"></a></li>
							
						</ul>
					</article>
					<!-- /.aboutCoder -->
				</div>
			</div>
		</div>
		<!-- /#hero -->
<?php 
//$coder = get_member_statistics ( $handle, $track );
?>

<article>
	<div class="container" align="center">
		</br>
		<p><b>Coming Soon!  Rating history, achievements, forum history, and more. Below is a preview of what is coming.
		</br>Features will be added <font color="red">as they complete</font>. 
		</br></br>Check out the <a href="/challenges">active challenges</a> to get involved in the effort!</b></p>
		</br>
	</div>
</article>


		<article id="mainContent" class="noShadow">
			<article class="coderRatings">
				<div class="container">
					<div class="actions track<?php echo $tab;?>">
						<ul class="trackSwitch switchBtns">
							<li class="first"><a href="./?tab=design" class="<?php if($tab == "design"){ echo "isActive";}?>">Design</a></li>
							<li><a href="./?tab=develop" class="<?php if($tab == "develop"){ echo "isActive";}?>">Develop</a></li>
							<li class="last"><a href="./?tab=algo" class="<?php if($tab == "algo" || $tab == null || $tab == ""){ echo "isActive";}?>">Data Science</a></li>
						</ul>
						<!-- /.trackSwitch -->
						<ul class="viewSwitch switchBtns">
							<li class="graphView first"><a id="graphButton" href="#graphView"></a></li>
							<li class="tabularView last"><a id="tableButton" href="#tabularView" class="isActive"></a></li>
						</ul>
					</div>
					<!-- /.actions -->
					<div class="dataTabs">
						<?php 
						if($tab=="design"){
							get_template_part('content', 'member-design');
						}else if($tab=="develop"){
							get_template_part('content', 'member-develop');
						}else{
							get_template_part('content', 'member-algo');
						}						
						?>						
					</div>
					<!-- /.dataTabs -->
				</div>
				<div class="clear"></div>
				<div class="forumWrap">
						<?php get_template_part('content', 'forum');?>
				</div>
				<!-- /.forumWrap -->
			</article>
			<!-- /.coderRatings -->
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>