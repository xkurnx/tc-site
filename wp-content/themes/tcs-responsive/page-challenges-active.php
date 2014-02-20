<?php
/**
 * Template Name: Challenges Active Contest List Page
 * Author : evilkyro1965 
 */
get_header('challenge-landing'); 


$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;
?>

<?php
	$tcoTooltipTitle = get_option("tcoTooltipTitle");
	$tcoTooltipMessage = get_option("tcoTooltipMessage");
	
	// get contest details
	$contest_type = get_query_var("contest_type") == "" ? "design" : get_query_var("contest_type");
	$listType = get_post_meta($postId,"List Type",true) =="" ? "Active" : get_post_meta($postId,"List Type",true);
	$postPerPage = get_post_meta($postId,"Contest Per Page",true) == "" ? 10 : get_post_meta($postId,"Contest Per Page",true);
?>

<script type="text/javascript" >
	var siteurl = "<?php bloginfo('siteurl');?>";
	
	var reviewType = "contest";
	var isBugRace = false;
	var ajaxAction = "get_challenges";
	var stylesheet_dir = "<?php bloginfo('stylesheet_directory');?>";
	var currentPage = 1;
	var postPerPage = <?php echo $postPerPage;?>;
	var contest_type = "<?php echo $contest_type;?>";
	var listType = "<?php echo $listType;?>";
	<?php 
		if($tcoTooltipTitle) echo "var tcoTooltipTitle= '$tcoTooltipTitle';"; 
		if($tcoTooltipMessage) echo "var tcoTooltipMessage= '$tcoTooltipMessage';"; 
	?>
</script>
<div class="content">
	<div id="main">
	
	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>

		<div id="hero">
			<?php 
				$activeDesignChallengesLink = get_bloginfo('siteurl')."/active-challenges/design/";
				$activeDevlopChallengesLink = get_bloginfo('siteurl')."/active-challenges/develop/";
				$activeDataChallengesLink = get_bloginfo('siteurl')."/active-challenges/data/";
			?>
			<div class="container grid grid-float">
				<div class="grid-3-1 track trackUX<?php if($contest_type=="design") echo " isActive"; ?>" >
					<a href="<?php echo $activeDesignChallengesLink;?>"><i></i>Graphic Design Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackSD<?php if($contest_type=="develop") echo " isActive"; ?>" >
					<a href="<?php echo $activeDevlopChallengesLink;?>"><i></i>Software Development Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackAn<?php if($contest_type=="data") echo " isActive"; ?>" >
					<a href="<?php echo $activeDataChallengesLink;?>">
						<i></i>Data Science Challenges
					</a><span class="arrow"></span>
				</div>
			</div>
		</div>
		<!-- /#hero -->
				
		<article id="mainContent" class="layChallenges">
			<div class="container">
				<header>
					<h1><?php echo ($contest_type=="design" ? "Graphic Design Challenges" : "Software Development Challenges" ); ?></h1>
					<aside class="rt">
						<span class="views"> <a href="#gridView" class="gridView"></a> <a href="#tableView" class="listView isActive"></a>
						</span>
					</aside>
				</header>
				<div class="actions">
					<div class="lt challengeType">
						<?php
							$pastChallenges = get_bloginfo('siteurl')."/past-challenges/".$contest_type."/";
							$reviewChallenges = get_bloginfo('siteurl')."/review-opportunities/".$contest_type."/";
							$bugraceChallenges = get_bloginfo('siteurl')."/bug-races/".$contest_type."/";
						?>
						<ul>
							<li><a href="javascript:;" class="active link">Open Challenges</a></li>
							<li><a href="<?php echo $pastChallenges;?>" class="link">Past Challenges</a></li>
							<li><a href="<?php echo $reviewChallenges;?>" class="link">Review Opportunities </a></li>
							<li><a href="<?php echo $bugraceChallenges;?>" class="link">Races</a></li>
						</ul>
					</div>
					<div class="rt">
						<a href="javascript:;" class="searchLink advSearch">
							<i></i>Advanced Search
						</a>
					</div>
				</div>
				<!-- /.actions -->
				<div class="searchFilter hide">
					<div class="filterOpts">
						<section class="types">
							<h5>Contest types:</h5>
							<div class="data">
								<?php if($contest_type=="design") : ?>
								<ul class="list">
									<li><input type="radio" id="fAll" name="radioFilterChallenge" value="all"> <label for="fAll"><strong>All</strong></label></li>
									<li><input type="radio" id="fP" name="radioFilterChallenge" value="Print/Presentation"> <label for="fAll">Print/Presentation</label></li>
									<li><input type="radio" id="fW" name="radioFilterChallenge" value="Web Design"> <label for="fAll">Web Design</label></li>
									<li><input type="radio" id="fIco" name="radioFilterChallenge" value="Banners/Icons"> <label for="fAll">Banner/Icon</label></li>
									<li><input type="radio" id="fWF" name="radioFilterChallenge" value="Wireframes"> <label for="fAll">Wireframe</label></li>
									<li><input type="radio" id="fLogo" name="radioFilterChallenge" value="Logo Design"> <label for="fAll">Logo Design</label></li>
									<li><input type="radio" id="fAFE" name="radioFilterChallenge" value="Application Front-End Design"> <label for="fAll">Application Front End</label></li>
									<li><input type="radio" id="fIG" name="radioFilterChallenge" value="Idea Generation"> <label for="fAll">Idea Generation</label></li>
									<li><input type="radio" id="fWI" name="radioFilterChallenge" value="Widget or Mobile Screen Design"> <label for="fAll">Widget/Mobile Screen</label></li>
								</ul>
								<?php else : ?>
								<ul class="list">
									<li><input type="radio" id="fAll" name="radioFilterChallenge" class="all" value="all" /> <label for="fAll"><strong>All</strong></label></li>
									<li><input type="radio" id="fDe" name="radioFilterChallenge" value="Design" /> <label for="fDe">Design</label></li>
									<li><input type="radio" id="fDev" name="radioFilterChallenge" value="Development" /> <label for="fDev">Development</label></li>
									<li><input type="radio" id="fSec" name="radioFilterChallenge" value="Security" /> <label for="fSec">Security</label></li>
									<li><input type="radio" id="fPr" name="radioFilterChallenge" value="Process" /> <label for="fPr">Process</label></li>
									<li><input type="radio" id="fTC" name="radioFilterChallenge" value="Testing Competition" /> <label for="fTC">Testing Competition</label></li>
									<li><input type="radio" id="fSpec" name="radioFilterChallenge" value="Specification" /> <label for="fSpec">Specification</label></li>
									<li><input type="radio" id="fArc" name="radioFilterChallenge" value="Architecture" /> <label for="fArc">Architecture</label></li>
									<li><input type="radio" id="fCP" name="radioFilterChallenge" value="Component Production" /> <label for="fCP">Component Production</label></li>
									<li><input type="radio" id="fBH" name="radioFilterChallenge" value="Bug Hunt" /> <label for="fBH">Bug Hunt</label></li>
									<li><input type="radio" id="fDep" name="radioFilterChallenge" value="Deployment" /> <label for="fDep">Deployment</label></li>
									<li><input type="radio" id="fTeS" name="radioFilterChallenge" value="Test Suites" /> <label for="fTeS">Test Suites</label></li>
									<li><input type="radio" id="fAC" name="radioFilterChallenge" value="Assembly Competition" /> <label for="fAC">Assembly Competition</label></li>
									<li><input type="radio" id="fLe" name="radioFilterChallenge" value="Legacy" /> <label for="fLe">Legacy</label></li>
									<li><input type="radio" id="fUI" name="radioFilterChallenge" value="UI Prototype Competition" /> <label for="fUI">UI Prototype Competition</label></li>
									<li><input type="radio" id="fCon" name="radioFilterChallenge" value="Conceptualization" /> <label for="fCon">Conceptualization</label></li>
									<li><input type="radio" id="fRia" name="radioFilterChallenge" value="RIA Build Competition" /> <label for="fRia">RIA Build Competition</label></li><li><input type="radio" id="fRiaComp" /> <label for="fRiaComp">RIA Component Competition</label></li>
									<li><input type="radio" id="fTS" name="radioFilterChallenge" value="Test Scenarios" /> <label for="fTS">Test Scenarios</label></li>
									<li><input type="radio" id="fSR" name="radioFilterChallenge" value="Spec Review" /> <label for="fSR">Spec Review</label></li>
									<li><input type="radio" id="fCoP" name="radioFilterChallenge" value="Copilot Posting" /> <label for="fCoP">Copilot Posting</label></li>
									<li><input type="radio" id="fCC" name="radioFilterChallenge" value="Content Creation" /> <label for="fCC">Content Creation</label></li>
									<li><input type="radio" id="fRep" name="radioFilterChallenge" value="Reporting" /> <label for="fRep">Reporting</label></li>
									<li><input type="radio" id="fMM" name="radioFilterChallenge" value="Marathon Match" /> <label for="fMM">Marathon Match</label></li>
									<li><input type="radio" id="f2f" name="radioFilterChallenge" value="First2Finish" /> <label for="f2f">First2Finish</label></li>
									<li><input type="radio" id="fCode" name="radioFilterChallenge" value="Code" /> <label for="fCode">Code</label></li>
								</ul>	
								<?php endif; ?>
							</div>
						</section>
						<section class="otherOpts">
							<ul>
								<li class="date row"><div class="lbl">
										<input type="checkbox" id="fSDate" />
										<label for="fSDate"><strong>Start date:</strong></label>
									</div>
									<div class="val">
										<span class="datePickerWrap"><input id="startDate" type="text" class="datepicker from" /></span>
										<!--
										<select disabled="disabled" class="time">
											<option selected="selected">01:00</option>
											<option>02:00</option>
											<option>03:00</option>
											<option>04:00</option>
											<option>05:00</option>
											<option>06:00</option>
											<option>07:00</option>
											<option>08:00</option>
											<option>09:00</option>
											<option>10:00</option>
											<option>11:00</option>
											<option>12:00</option>
											<option>13:00</option>
											<option>14:00</option>
											<option>15:00</option>
											<option>16:00</option>
											<option>17:00</option>
											<option>18:00</option>
											<option>19:00</option>
											<option>20:00</option>
											<option>21:00</option>
											<option>22:00</option>
											<option>23:00</option>
											<option>24:00</option>
										</select>
										<span class="tt"> ET (GMT-04)</span>
										-->
									</div></li>
								<li class="date row">
									<div class="lbl">
										<input type="checkbox" id="fEDate" />
										<label for="fEDate"><strong>End date:</strong></label>
									</div>

									<div class="val">
										<span class="datePickerWrap"><input id="endDate"  type="text" class="datepicker to" /></span>
										<!--
										<select disabled="disabled" class="time">
											<option selected="selected">01:00</option>
											<option>02:00</option>
											<option>03:00</option>
											<option>04:00</option>
											<option>05:00</option>
											<option>06:00</option>
											<option>07:00</option>
											<option>08:00</option>
											<option>09:00</option>
											<option>10:00</option>
											<option>11:00</option>
											<option>12:00</option>
											<option>13:00</option>
											<option>14:00</option>
											<option>15:00</option>
											<option>16:00</option>
											<option>17:00</option>
											<option>18:00</option>
											<option>19:00</option>
											<option>20:00</option>
											<option>21:00</option>
											<option>22:00</option>
											<option>23:00</option>
											<option>24:00</option>
										</select>
										<span class="tt"> ET (GMT-04)</span>
										-->
									</div>
								</li>
								<!--
								<li class="handle">
									<div class="subRow1 row">
										<div class="lbl">
										<span class="handleWrap">
											<input type="checkbox" id="fHandle" />
											<label for="fHandle"><strong>Winner's handle:</strong></label>
											</span>
										</div>
										<div class="val">
											<input type="text" class="winnerHandle" disabled="disabled"  />
										</div>
									</div>
									<div class="subRow2 row">
										<div class="lbl">
											<label for="fUpto"><strong>up to:</strong></label>
										</div>
										<div class="val">
											<select id="fUpto" disabled="disabled" >
												<option selected="selected" value="Firstplace">first place</option>
												<option value="Secondplace">top two places</option>
												<option value="Thirdplace">top three places</option>
												<option value="any">any prize placement</option>
											</select>
										</div>
									</div>
								</li>
								-->
							</ul>
						</section>
						<div class="clear"></div>
					</div>
					<!-- /.filterOpts -->
					<div class="actions">
						<a href="javascript:;" class="btn btnSecondary btnClose">Close</a>
						<a href="javascript:;" class="btn btnApply">Apply</a>
					</div>
				</div>
				<!-- /.searchFilter -->
				<div id="tableView" class=" viewTab">
					<div class="tableWrap tcoTableWrap">
						<table class="dataTable tcoTable">
							<thead>
								<tr>
									<th class="colCh" data-placeholder="challengeName">Challenges<i></i></th>
									<th class="colType" data-placeholder="challengeType">Type<i></i></th>
									<th class="colTime desc" data-placeholder="postingDate">Timeline<i></i></th>
									<th class="colTLeft noSort" data-placeholder="currentPhaseRemainingTime">Time Left<i></i></th>
									<th class="colPur noSort" data-placeholder="prize">Prizes<i></i></th>
									<th class="colPhase noSort" data-placeholder="currentPhase">Current Phase<i></i></th>
									<th class="colReg noSort" data-placeholder="numRegistrants">Registrants<i></i></th>
									<th class="colSub noSort" data-placeholder="numSubmissions">Submissions<i></i></th>
								</tr>
							</thead>
							<tbody>
										
							</tbody>
						</table>
					</div>
				</div>
				<!-- /#tableView -->
				<div id="gridView" class="viewTab hide">
					<div class="contestGrid alt">

					</div>
					<!-- /.contestGrid -->
				</div>
				<!-- /#gridView -->
				<div class="dataChanges">
					<div class="lt">
						<a href="javascript:;" class="viewAll">View All</a>
					</div>
					<div id="challengeNav" class="rt">
						<a href="javascript:;" class="prevLink">
							<i></i> Prev
						</a>
						<a href="javascript:;" class="nextLink">
							Next <i></i>
						</a>
					</div>
					<div class="mid onMobi">
						<a href="#" class="viewPastCh">
							View Past Challenges<i></i>
						</a>
					</div>
				</div>
				<!-- /.dataChanges -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>