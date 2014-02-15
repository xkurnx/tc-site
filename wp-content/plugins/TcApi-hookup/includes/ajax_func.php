<?php
function post_register_controller(){
	global $_POST;
	$url = "https://api.topcoder.com/v2/users";
	$response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => array( 
	'firstName' => $_POST['firstName'], 
	'lastName' => $_POST['lastName'],
	'handle' => $_POST['handle'],
	'country' => $_POST['country'],
	'email' => $_POST['email'],
	'password' => $_POST['password']
	),
	'cookies' => array()
	)
);

	$msg = json_decode($response['body']);
	$code = $response['response']['code'];
	#print_r($msg);
	$mm = "";
	if ( $msg->error )
	foreach ( $msg->error->details as $m ):
		$mm.= $m;
	endforeach;

	echo json_encode(array("code" => $code, "description" => $mm ));	
	exit;
	return "Error in processing request";


}
add_action ( 'wp_ajax_post_register', 'post_register_controller' );
add_action ( 'wp_ajax_nopriv_post_register', 'post_register_controller' );




function post_login_controller(){
global $_POST;
$url = "https://api.topcoder.com/v2/users/";
$arg = array(
'method' => 'POST',
'headers' => array("Content-Type: application/json"),
'body' => "{\n \"firstname\" : \"".$_POST['name']."\",\n \"lastname\" : \"Doe\",\n \"handle\" : \"".$_POST['name']."\",\n \"country\" : \"UK\",\n \"email\" : \"".$_POST['password']."\",\n \"password\" : \"HashedPassword\",\n \"socialProvider\" : \"google\",\n \"socialUserName\" : \"JohnsGoogleName\",\n \"socialEmail\" : \"john@gmail.com\",\n \"socialEmailVerified\" : \"true\"\n}"
);
$response = wp_remote_post ( $url, $args );

// harcoded message
$description = 'We have sent you an email to<strong> '.$_POST['email'].'</strong> with a activation instructions.<br />If you do not receive that email within 1 hour, please email <a href="mailto:support@topcoder.com">support@topcoder.com</a>';
echo json_encode(array('description' => $description ));
exit;

return "Error in processing request";




}
add_action ( 'wp_ajax_post_login', 'post_login_controller' );
add_action ( 'wp_ajax_nopriv_post_login', 'post_login_controller' );




function get_active_contest_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$contest_type = $_GET ['contest_type'];
$page = get_query_var ( 'pages' );
$post_per_page = $_GET['pageSize'];
$page = $_GET ['pageIndex'];
$sortColumn = $_GET ['sortColumn'];
$sortOrder = $_GET ['sortOrder'];

$contest_list = get_active_contests_ajax ( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
if ($contest_list->data != null) {
echo json_encode ( $contest_list->data );
}
die ();
}
add_action ( 'wp_ajax_get_active_contest', 'get_active_contest_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_active_contest', 'get_active_contest_ajax_controller' );
function get_past_contest_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$contest_type = $_GET ['contest_type'];
$page = get_query_var ( 'pages' );
$post_per_page = $_GET ['pageSize'];
$sortColumn = $_GET ['sortColumn'];
$sortOrder = $_GET ['sortOrder'];

$contest_list = get_past_contests_ajax ( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
if ($contest_list->data != null) {
echo json_encode ( $contest_list->data );
}
die ();
}

add_action ( 'wp_ajax_get_past_contest', 'get_past_contest_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_past_contest', 'get_past_contest_ajax_controller' );

function get_member_profile_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$handle = $_GET ["handle"];

$memberProfile = get_member_profile ( $handle );
if ($memberProfile != null) {
echo json_encode ( $memberProfile );
}
die ();
}
add_action ( 'wp_ajax_get_member_profile', 'get_member_profile_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_member_profile', 'get_member_profile_ajax_controller' );
function get_user_achievements_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$handle = $_GET ["handle"];

$userAchievements = get_user_achievements ( $userkey, $handle );
if ($userAchievements != null) {
echo json_encode ( $userAchievements );
}
die ();
}
add_action ( 'wp_ajax_get_user_achievement', 'get_user_achievements_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_user_achievement', 'get_user_achievements_ajax_controller' );
function get_copilot_stats_controller() {
$userkey = get_option ( 'api_user_key' );
$handle = $_GET ["handle"];

$userAchievements = get_copilot_stats ( $userkey, $handle );
if ($userAchievements != null) {
echo json_encode ( $userAchievements );
}
die ();
}
add_action ( 'wp_ajax_get_copilot_stats', 'get_copilot_stats_controller' );
add_action ( 'wp_ajax_nopriv_get_copilot_stats', 'get_copilot_stats_controller' );

/**
* End of ajax controller
*/

/**
* Start of ajax functioning
*/

// returns active contest list
function get_active_contests_ajax($userKey = '', $contestType = 'design', $page = 1, $post_per_page = 30, $sortColumn = 'submissionEndDate', $sortOrder = '') {
$contestType = str_replace ( " ", "+", $contestType );
$contestType = str_replace ( "-", "/", $contestType );
$listType = ( $contestType == 'data/marathon' or $contestType == 'data/srm' ) ? "active":"Open";
$url = "https://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;

if ($contestType == "") {
$url = "https://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;
}
#echo $url;
if ($sortOrder) {
$url .= "&sortOrder=$sortOrder";
}
if ($sortColumn) {
$url .= "&sortColumn=$sortColumn";
}
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );
if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
$active_contest_list = json_decode (str_replace('"items":','"data":',$response ['body'] ));
return $active_contest_list;
}

return "Error in processing request";
}

// returns past contest list
function get_past_contests_ajax($userKey = '', $contestType = '', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '') {
$contestType = str_replace ( " ", "+", $contestType );
$url = "https://api.topcoder.com/v2/develop/challenges?user_key=" . $userKey . "&listType=PAST&type=" . $contestType . "&pageSize=1000";
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
if ($contestType == "") {
$url = "https://api.topcoder.com/v2/develop/challenges?user_key=" . $userKey . "&listType=PAST&pageSize=1000";
}
if ($sortOrder) {
$url .= "&sortOrder=$sortOrder";
}
if ($sortColumn) {
$url .= "&sortColumn=$sortColumn";
}
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {
$active_contest_list = json_decode ( $response ['body'] );
return $active_contest_list;
}
return "Error in processing request";
}

// returns member profile
function get_member_profile($handle = '') {
#echo $userKey;
$url = "http://api.topcoder.com/v2/users/" . $handle;
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );
#print_r($response);
if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request or Member dosen't exist";
}
if ($response ['response'] ['code'] == 200) {
$coder_profile = json_decode ( $response ['body'] );
return $coder_profile;
}
#print_r($response);
return "Error in processing request";
}

// returns achievements data
function get_user_achievements($userKey = '', $handle = '') {
$url = "https://api.topcoder.com/rest/statistics/$handle/achievements?user_key=" . $userKey;
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request or Member dosen't exist";
}
if ($response ['response'] ['code'] == 200) {
$coder_achievements = json_decode ( $response ['body'] );
return $coder_achievements;
}
return "Error in processing request";
}

// returns copilot stats
function get_copilot_stats($userKey = '', $handle = '') {
$url = "https://api.topcoder.com/rest/statistics/copilots/$handle/contests?user_key=" . $userKey;
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request or Member dosen't exist";
}
if ($response ['response'] ['code'] == 200) {
$copilot_stats = json_decode ( $response ['body'] );
return $copilot_stats;
}
return "Error in processing request";
}

// returns top rank
function get_top_rank($userKey = '', $contestType = 'Algorithm') {
$contestType = str_replace ( " ", "+", $contestType );

switch($contestType){
case "develop":
$url = "https://api.topcoder.com/v2/develop/statistics/tops/development?rankType=rank";
break;
case "data":
$url = "https://api.topcoder.com/v2/data/srm/statistics/tops";
break;	

}

$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {
$arrTopRank = json_decode ( $response ['body'] );
return $arrTopRank;
}
return "Error in processing request";
}

/**
* End of ajax functioning
*/

/**
* Start of load data functioning
*/
function get_contest_info($contestID = '') {
$url = "https://api.topcoder.com/v2/software/contests/$contestID";
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );
if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {
$contestObj = $response ['body'];
return $contestObj;
}
return "Error in processing request";
}
 
/**
* End of load data functioning
*/
 
 
/**
* Challenges changes from "TopCoder Website - Challenges Pages - Wordpress Theme Build" Contest
*/
add_action ( 'wp_ajax_get_challenges', 'get_challenges_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_challenges', 'get_challenges_ajax_controller' );
function get_challenges_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$contest_type = $_GET ['contest_type'];
$page = $_GET['pageIndex'];
$listType = $_GET['listType'];
$post_per_page = $_GET ['pageSize'];
$sortColumn = ($_GET ['sortColumn']);
$sortOrder = $_GET ['sortOrder'];
$challengeType = urlencode($_GET ['challengeType']);
$startDate = $_GET ['startDate'] ;
$endDate = $_GET ['endDate'] ;

$contest_list = get_challenges_ajax ( $listType, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder,$challengeType,$startDate,$endDate );

if ($contest_list->data != null) {
echo json_encode ( $contest_list );
}
die ();
}
 
function get_challenges_ajax($listType = 'Active', $contestType = 'design', $page = 1, $post_per_page = 30, $sortColumn = "submissionEndDate", $sortOrder = 'desc', $challengeType = '',
$startDate='', $endDate=''
) {

$url = "http://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;

if ($contestType == "") {
$url = "http://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;
}

// set default value since failed using params;
$sortColumn = ( $sortColumn == '' ) ? "submissionEndDate" : $sortColumn;
$sortOrder = ( $sortOrder == '' ) ? "desc" : $sortOrder;
 

if ($sortOrder) {
$url .= "&sortOrder=$sortOrder";
}
if ($sortColumn) {
$url .= "&sortColumn=$sortColumn";
}
if ($challengeType) {
$url .= "&challengeType=$challengeType";
}
if ($startDate) {
$url .= "&submissionEndDate.startDate=$startDate";
}
if ($endDate) {
$url .= "&submissionEndDate.endDate=$endDate";
}

$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
$active_contest_list = json_decode($response['body']);
return $active_contest_list;
}

return "Error in processing request";
}


 
/**
* Review opportunities changes from "TopCoder Website - Challenges Pages - Wordpress Theme Build" Contest
*/
 
add_action ( 'wp_ajax_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
function get_review_opportunities_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$contest_type = $_GET ['contest_type'];
$page = $_GET['pageIndex'];
$listType = $_GET['listType'];
$post_per_page = $_GET ['pageSize'];
$sortColumn = $_GET ['sortColumn'];
$sortOrder = $_GET ['sortOrder'];
$challengeType = urlencode($_GET ['challengeType']);

$contest_list = get_review_opportunities_ajax ( $listType, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder, $challengeType );
if ($contest_list->data != null) {
echo json_encode ( $contest_list );
}
die ();
}
 
function get_review_opportunities_ajax($listType = 'Active', $contestType = 'design', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '', $challengeType = '') {

$url = "http://api.topcoder.com/v2/".$contestType."/reviewOpportunities?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;

if ($contestType == "") {
$url = "http://api.topcoder.com/v2/".$contestType."/reviewOpportunities?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;
}
//echo $url;
if ($sortOrder) {
$url .= "&sortOrder=$sortOrder";
}
if ($sortColumn) {
$url .= "&sortColumn=$sortColumn";
}
if ($challengeType) {
$url .= "&challengeType=$challengeType";
}
$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
$active_contest_list = json_decode($response['body']);
return $active_contest_list;
}

return "Error in processing request";
}

/**
* Get Active Challenges Data List
*/
 
add_action ( 'wp_ajax_get_active_data_challenges', 'get_active_data_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_active_data_challenges', 'get_active_data_ajax_controller' );
function get_active_data_ajax_controller() {
$userkey = get_option ( 'api_user_key' );
$page = $_GET['pageIndex'];
$post_per_page = $_GET ['pageSize'];
$sortColumn = $_GET ['sortColumn'];
$sortOrder = $_GET ['sortOrder'];

$contest_list = get_data_challenges_ajax ( $page, $post_per_page, $sortColumn, $sortOrder );
if ($contest_list->data != null) {
echo json_encode ( $contest_list );
}
die ();
}
 
function get_data_challenges_ajax($page = 1, $post_per_page = 1, $sortColumn = '', $sortOrder = '') {

$url = "http://api.topcoder.com/v2/data/marathon/challenges?pageIndex=".$page."&pageSize=".$post_per_page;
#echo $url;

$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$responseSrm = wp_remote_get ( $url, $args );

if (is_wp_error ( $responseSrm ) || ! isset ( $responseSrm ['body'] )) {
return "Error in processing request";
}

$urlMarathon = "http://api.topcoder.com/v2/data/marathon/?pageIndex=".$page."&pageSize=".$post_per_page;

$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$responseMarathon = wp_remote_get ( $urlMarathon, $args );

/* merge the srm and marathon */
if ($responseMarathon ['response'] ['code'] == 200) {

$srmData = json_decode($responseSrm['body']);
if($srmData->data!=null) {
$marathonData = json_decode($responseMarathon['body']);

if($marathonData->data!=null)
foreach($marathonData->data as $row) {
$srmData->data[count($srmData)+1] = array(
"name"=>$row->fullName,
"startDate"=>$row->startDate
);
}
}

$urlMarathon = "http://api.topcoder.com/v2/data/marathon/?pageIndex=".$page."&pageSize=".$post_per_page;

$args = array (
'httpversion' => get_option ( 'httpversion' ),
'timeout' => get_option ( 'request_timeout' )
);
$responseMarathon = wp_remote_get ( $urlMarathon, $args );


}

if ($responseSrm ['response'] ['code'] == 200) {

return $srmData;
}

return "Error in processing request";
}