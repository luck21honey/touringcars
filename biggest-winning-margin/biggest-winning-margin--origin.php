<?php
/* Template Name: Stats (Margin - biggest) */

get_header();

td_global::$current_template = 'page-homepage-loop';

global $paged, $loop_module_id, $loop_sidebar_position, $post, $more; //$more is a hack to fix the read more loop
$td_page = (get_query_var('page')) ? get_query_var('page') : 1; //rewrite the global var
$td_paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //rewrite the global var


//paged works on single pages, page - works on homepage
if ($td_paged > $td_page) {
    $paged = $td_paged;
} else {
    $paged = $td_page;
}


$list_custom_title_show = true; //show the article list title by default




/*
    read the settings for the loop
---------------------------------------------------------------------------------------- */
if (!empty($post->ID)) {
    td_global::load_single_post($post);
    //read the metadata for the post
    $td_homepage_loop = get_post_meta($post->ID, 'td_homepage_loop', true);


    if (!empty($td_homepage_loop['td_layout'])) {
        $loop_module_id = $td_homepage_loop['td_layout'];
    }

    if (!empty($td_homepage_loop['td_sidebar_position'])) {
        $loop_sidebar_position = $td_homepage_loop['td_sidebar_position'];
    }

    if (!empty($td_homepage_loop['td_sidebar'])) {
        td_global::$load_sidebar_from_template = $td_homepage_loop['td_sidebar'];
    }

    if (!empty($td_homepage_loop['list_custom_title'])) {
        $td_list_custom_title = $td_homepage_loop['list_custom_title'];
    } else {
        $td_list_custom_title =__td('LATEST ARTICLES');
    }


    if (!empty($td_homepage_loop['list_custom_title_show'])) {
        $list_custom_title_show = false;
    }
}

/*
    the first part of the page (built with the page builder)  - empty($paged) or $paged < 2 = first page
---------------------------------------------------------------------------------------- */
if(!empty($post->post_content)) { //show this only when we have content
    if (empty($paged) or $paged < 2) { //show this only on the first page
        if (have_posts()) { ?>
            <?php while ( have_posts() ) : the_post(); ?>
			
			<style type="text/css">
			
			<!-- CSS styles below for database table, to be moved to separate CSS file -->
			
			.h4h {
				margin-left: 10px;
			}
			
			.tb-row:nth-of-type(even) {
				background-color: #f7f7f7;
			}
			.tb-row:nth-of-type(even):hover {
				background-color: #d1d5ff;
			}
			.tb-row:nth-of-type(odd):not(.header):hover {
				background-color: #d1d5ff;
			}
			.tb-row.header {
				font-weight: bold!important;
			}

			.stats-div {
				width: 100%;
				padding: 5px;
				font-size: 13px;
			}

			.stats-table {
				width: 100%;
			}

			.stats-table img {
				display: inline;
			}

			</style>
<style type="text/css">
.show_more_main {
	margin: 15px 25px;
}
.show_more {
	background-color: #f8f8f8;
	background-image: -webkit-linear-gradient(top,#fcfcfc 0,#f8f8f8 100%);
	background-image: linear-gradient(top,#fcfcfc 0,#f8f8f8 100%);
	border: 1px solid;
	border-color: #d3d3d3;
	color: #333;
	font-size: 12px;
	outline: 0;
}
.show_more {
	cursor: pointer;
	display: block;
	padding: 10px 0;
	text-align: center;
	font-weight:bold;
}
.loding {
	background-color: #e9e9e9;
	border: 1px solid;
	border-color: #c6c6c6;
	color: #333;
	font-size: 12px;
	display: block;
	text-align: center;
	padding: 10px 0;
	outline: 0;
	font-weight:bold;
}
.loding_txt {
	background-image: url('<?php echo get_template_directory_uri(); ?>/images/loading.gif');
	background-position: left;
	background-repeat: no-repeat;
	border: 0;
	display: inline-block;
	height: 16px;
	padding-left: 20px;
}
.smore-tr{border-bottom: none !important;}
.smore-tr:hover {
    background-color: #FFFFFF !important;
}
body {
	counter-reset: my-sec-counter;
}
.rownums::before 
{ 
  counter-increment: my-sec-counter;
  content: counter(my-sec-counter) ; 
}
</style>

<link rel="stylesheet/less" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/flex-ages.less" />
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.0.0/less.min.js" ></script>

<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/includes/timezone/tzc.min.js"></script>

<div class="td-container">
    <div class="td-container-border">
        <?php the_content(); ?>
    </div>
</div>
				
    <!-- CODEXWORLD TO PLACE FUNCTIONING AJAX LOAD MORE CODE HERE -->

    <div class="td-container">
        <div class="td-container-border wpb_content_element" style="border-left: 1px solid #E5E5E5;">
            <?php $meta_value = rwmb_meta( 'prefix-text_1' ); ?>
            <div class='block-title' style='margin-left: 0px;'>
                <span>Biggest winning margin</span>
            </div>
            
            <?php  { ?>
            <?php
                $servername = "localhost:3306";
                $username = "tcn_results";
                $password = "BtccWtcr2020!";
                $dbname = "tcn_results";

                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                mysqli_set_charset($conn,"utf8");
                
                $sid = $_GET['series'];
                $sid = mysqli_real_escape_string($conn, $sid);
                
                $id = mysqli_real_escape_string($conn, $meta_value);
                $id2 = $id;
                
                if (empty($_GET)) {
                    $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin LIMIT 25";
                } else {
                    
                    $sql = "SELECT series as cship, year as yr, round as rd, Winner as pilot, track as trvar, car as vehicle, race_id, timed, seed as id FROM marginbigwin WHERE series in ('" . $sid . "') LIMIT 25";
                }

                if (empty($_GET)) {
                    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE Result = '" . $id . "'";
                } else {
                    $sql2 = "select date_format(min(date),'%D %b %Y') as mindate, date_format(max(date),'%D %b %Y') as maxdate from races WHERE `Series` in ('" . $sid . "') and Result = '" . $id . "'";
                }
                
                $result = mysqli_query($conn, $sql);
                $result2 = mysqli_query($conn, $sql2);

            ?>
            <script src="/results/tablesorter/js/jquery.tablesorter.min.js"></script>
            <script src="/results/tablesorter/js/jquery.tablesorter.widgets.min.js"></script>
            <script>
                jQuery(function(){
                    jQuery('table').tablesorter({
                        widgets        : ['columns'],
                        usNumberFormat : false,
                        sortReset      : true,
                        sortRestart    : true
                    });
                });
                
                jQuery(document).ready(function(){
                    jQuery(document).on('click','.show_more',function(){
                        var ID = jQuery(this).attr('id');
                        jQuery('.show_more').hide();
                        jQuery('.loding').show();
                        jQuery.ajax({
                            type:'POST',
                            url:'<?php if (!empty($sid)) { echo get_template_directory_uri() . '/ajax_more-margin-biggest.php?series=' . $sid; } else { echo get_template_directory_uri() . '/ajax_more-margin-biggest.php'; } ?>',
                            data:'id='+ID,
                            success:function(html){
                                jQuery('#show_more_main'+ID).remove();
                                jQuery('.postList').append(html);
                            }
                        });
                    });
                });
            </script>
            
            <p>&nbsp;&nbsp;<em>Note: Data valid for period between <?php if (mysqli_num_rows($result2) > 0) {while($row = mysqli_fetch_assoc($result2)) { echo $row["mindate"] . " and " . $row["maxdate"]; } } else {	echo "0 results"; }	?></em></p>
            
            <aside class='widget widget_meta'>
            
                <ul>
                    <li>
                        <span class="circuit"><a href="/list-of-biggest-winning-margins">ALL</a></span>
                        <span class="circuit"><a href="?series=WTCC">WTCC</a></span>
                        <span class="circuit"><a href="?series=WTCR">WTCR</a></span>
                        <span class="circuit"><a href="?series=BTCC">BTCC</a></span>
                        <span class="circuit"><a href="?series=DTM">DTM</a></span>
                        <span class="circuit"><a href="?series=STCC">STCC</a></span>
                        <span class="circuit"><a href="?series=TCR EU">TCR Europe</a></span>
                        <span class="circuit"><a href="?series=TCR DE">TCR Germany</a></span>
                        <span class="circuit"><a href="?series=TCR IT">TCR Italy</a></span>
                        <span class="circuit"><a href="?series=TCR UK">TCR UK</a></span>
                        <span class="circuit"><a href="?series=TCR Asia">TCR Asia</a></span>
                        <span class="circuit"><a href="?series=TCR Intl">TCR International</a></span>
                        <span class="circuit"><a href="?series=WC TCR">Pirelli World Challenge TCR</a></span>
                        <span class="circuit"><a href="?series=ETCC">ETCC</a></span>
                        <span class="circuit"><a href="?series=ETC Cup">ETC Cup</a></span>
                        <span class="circuit"><a href="?series=STW Cup">STW Cup</a></span>
                    </li>
                </ul>
                
            </aside>
    
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Filter on table contents..." title="Type in a name">
            
            <div class="stats-div" id="stats1">

                <div class="container-fluid" style="margin-top: 10px; padding: 0px;">
                    <div class="tb-row header">
                        <div class="wrapper text-0">
                            <div class="wrapper text-0">
                            <div class="text-series">Rank</div>
                            <div class="text-series">Series</div>
                            <div class="text-year">Year</div>
                            </div>
                        </div>
                        <div class="wrapper text-2">
                            <div class="wrapper text-2">
                            <div class="text-layout">Rd</div>
                            <div class="text-driver">Driver</div>
                            </div>
                        </div>
                        <div class="wrapper text-2">
                            <div class="wrapper text-2">
                            <div class="text-entrant">Circuit</div>
                            <div class="text-car"><em>Car</em></div>
                            </div>
                        </div>
                        <div class="wrapper text-4">
                            <div class="wrapper text-4">
                            <div class="text-time">Margin (secs)</div>
                            </div>
                        </div>
                    </div>
                    <div class="postList">

                        <?php

                        if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            $circuitID = $row['id'];
                            echo "
                                    <div class='tb-row'>
                                        <div class='wrapper text-0'>
                                            <div class='wrapper text-0'>
                                                <div class='text-series rownums'></div>
                                                <div class='text-series'>
                                                    " . (( $row["cship"] == 'STW Cup') ? 'STW' : $row["cship"] ) . "
                                                </div>
                                                <div class='text-year'>
                                                    " . $row["yr"]. "
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-2'>
                                            <div class='wrapper text-2'>
                                                <div class='text-layout' title='" . $row["trvar"] . "'>
                                                    " . $row["rd"]. "
                                                </div>
                                                <div class='text-driver'>
                                                    <a href='/results/statistics/lists/driver-wins.php?series=" . $row["cship"] . "&driver=" . $row["pilot"] . "'>" . $row["pilot"]. "</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-2'>
                                            <div class='wrapper text-2'>
                                                <div class='text-entrant' title='" . $row["trvar"] . "'>
                                                    " . mb_strimwidth($row["trvar"],0,30,"..") . "
                                                </div>
                                                <div class='text-car'title='" . $row["vehicle"] . "'>
                                                    <em>" . mb_strimwidth($row["vehicle"],0,27,"...") . "</em>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='wrapper text-4'>
                                            <div class='wrapper text-4'>
                                                <div class='text-time'>
                                                    " . $row["timed"]. "
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
                        ?>
                        <tr class="smore-tr">
                            <td colspan="7">
                                <div class="show_more_main" id="show_more_main<?php echo $circuitID; ?>">
                                    <span id="<?php echo $circuitID; ?>" class="show_more" title="Load more posts">Show more</span>
                                    <span class="loding" style="display: none;"><span class="loding_txt">Loading...</span></span>
                                </div>
                            </td>
                        </tr>
                        <?php } else {
                            echo "0 results";
                        }

                        mysqli_close($conn);

                        ?>
                    </div>
                </div>
            
            </div>
            
            <script>
                // Reconcile this properyl with https://www.w3schools.com/howto/howto_js_filter_lists.asp (23.04.2018)
                
                function myFunction() {
                    // Declare variables 
                    var input, filter, table, tr, td, i, occurrence;

                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("stats1");
                    tr = table.getElementsByClassName("tb-row");

                    // Loop through all table rows, and hide those who don't match the search query
                    for (i = 1; i < tr.length; i++) {
                        occurrence = false; // Only reset to false once per row.
                        td = tr[i].getElementsByClassName("wrapper");
                        for(var j=1; j< td.length; j++){                
                            currentTd = td[j];
                            if (currentTd ) {
                                if (currentTd.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                    occurrence = true;
                                } 
                            }
                        }
                        if(!occurrence){
                            tr[i].style.display = "none";
                        } else {
                            tr[i].style.display = "";
                        }
                    }
                }
                
            </script>
            
        </div>
    </div>
    
    <div class="td-container">
        <div class="td-container-border">
            <div class="vc_row wpb_row td-pb-row">
                <div class="wpb_column vc_column_container td-pb-span12">
                    <div class="wpb_wrapper">
                        <div class="wpb_text_column wpb_content_element ">
                            <div class="wpb_wrapper">
                                <p><em>Tip: Filter on the database results by entering free text into the search box at the top.</em>
                                <br />
                                TouringCars.Net contains the ultimate statistical record of touring car racing on the internet.
                                <br />
                                <br />
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- Responsive header -->
                                <ins class="adsbygoogle"
                                        style="display:block"
                                        data-ad-client="ca-pub-3615539307566661"
                                        data-ad-slot="8677277793"
                                        data-ad-format="auto"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } endwhile; ?>

    <!-- END OF SECTION FOR PLACEMENT OF CODEXWORLD CODE -->
<?php }
    }
}
?>

<script type="text/javascript">
    var myTZC = new TZC('.tzcontent', {
        labelText  : 'In your timezone:',
        phpfile    : 'http://www.touringcars.net/tcntest/wp-content/themes/TCNv3/includes/timezone/tzc.php', /* Not sure this works */
        cookieName : 'tzco',
        theme      : 'dark'
    });
</script>

<?php

get_footer();