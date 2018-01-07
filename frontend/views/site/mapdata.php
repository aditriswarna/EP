<?php $img = isset($rows[0]['document_name']) ? $rows[0]['document_name'] :'no_image';?>
<style type="text/css">
    .wrapper1 {
        position:relative;
        margin:0 auto;
        overflow:hidden;
        padding:5px;
        //height:50px;
    }
    .list1 {
        position:absolute;
        left:0px;
        top:0px;
        min-width:600px;
        margin-left:28px;
        margin-top:0px;
    }
    .list1 li{
        display:table-cell;
        position:relative;
        text-align:center;
        cursor:grab;
        cursor:-webkit-grab;
        color:#efefef;
        vertical-align:middle;
    }
    .grid-view{ overflow:auto;}
	
	@media(max-width: 1024px) {
     .mp-cmtbox1{clear:both;display:inline-block;overflow:visible;width:100%;height:auto;position:relative !important;z-index:999999;float:none !important;}
    }
    .fancybox-overlay{
            z-index: 999 !important;
    }
</style>
<div class="project-detail-pane ">
    <div class="project-detail-black-pane col-sm-5 col-xs-12">
    <div class="project-details-in">
    <div class="pull-right project-detail-mobile-close">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-pane2">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

        <div class="project-detail-title pull-left"><?php echo $projectData['project_title']; ?></div>
        <div class="pull-right projectlike"><span class="prjcount"></span></div>


        <div class="project-detail-image ">
            <div class="project-detail-category "><p class="category-p"><?php echo isset($projectData['category_name']) ? $projectData['category_name'] : ''; ?></p></div>
            <?php ?><div class="pull-right media-icons">
                <i class="fa fa-sm fa-picture-o currenti slider-toggle" title="Images"></i>
                <i class="fa fa-sm fa-video-camera video-toggle" title="Videos"></i>
            </div><?php ?>

            <?php
            if (count($rows) > 0) {
                $project_image = array();
                $project_document = array();
                $project_videos = array();
                foreach ($rows as $key => $projets) {
                    if ($projets['document_type'] == 'projectImage') {
                        $project_image[$key]['project_ref_id'] = $projets['project_ref_id'];
                        $project_image[$key]['document_name'] = $projets['document_name'];
                    }
                    if ($projets['document_type'] == 'projectDocument') {
                        $project_document[$key]['project_ref_id'] = $projets['project_ref_id'];
                        $project_document[$key]['document_name'] = $projets['document_name'];
                    }
                    if ($projets['document_type'] == 'projectVideos') {
                        $project_videos[$key]['project_ref_id'] = $projets['project_ref_id'];
                        $project_videos[$key]['document_name'] = $projets['document_name'];
                    }
                }
                ?>

                <?php
                if (count($project_image) > 0) {
                    $activeimage = 0;
                    ?>
                    <div id="carousel-example-generic1" class="carousel slide overflow-hidden" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php
                            foreach ($project_image as $key => $projets) {

                                $activeimage++;
                                if (count($project_image) > 1) {
                                    ?>
                                    <li data-target="#carousel-example-generic1" data-slide-to="<?= $key; ?>" class="<?php echo ($activeimage == 1 ? 'active' : ''); ?>"></li>
                                  <!-- <li data-target="#carousel-example-generic" data-slide-to="<?= $key; ?>" class=""></li>
                                   <li data-target="#carousel-example-generic" data-slide-to="<?= $key; ?>" class="active"></li>-->
                                    <?php
                                }
                            }
                            ?>
                        </ol>    
                        <div class="carousel-inner max-height-330" role="listbox">
                            <?php
                            $activeimage = 0;
                            foreach ($project_image as $projets) {

                                $activeimage++;

                                if (!empty($projets['document_name']))
                                    $projectImageUrl = 'https://s3.ap-south-1.amazonaws.com/'. Yii::getAlias('@bucket') . '/uploads/project_images/' . $projets['project_ref_id'] . '/' . $projets['document_name'];
                                else
                                    $projectImageUrl = Yii::$app->request->baseUrl . '/uploads/project_images/no_project_image.jpg';
                                ?>
                                <div class="item no_imgg <?php echo ($activeimage == 1 ? 'active' : ''); ?>">
                                    <a class="fancybox_for_image" rel="fancybox_for_image" href="<?php echo $projectImageUrl; ?>">
                                        <img src="<?php echo $projectImageUrl; ?>" alt="slide" class="img-responsive"></a>
                                </div>
                                <?php
                            }
                            if (count($project_image) > 1) {
                                ?>
                                <a class="left carousel-control" href="#carousel-example-generic1" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#carousel-example-generic1" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                                <?php }
                            ?>
                        </div></div>
                <?php
                } else {
                    ?>
                    <div class="item active no_imgg for_image">
                        <img src="<?php echo SITE_URL . Yii::getAlias('@web') . '/uploads/project_images/no_project_image.jpg'; ?>" alt="No_image" class="img-responsive">
                    </div>
                    <?php
                }
                ?>


                <?php
                if (count($project_videos) > 0) {
                    $activeimage = 0;
                    ?>
                    <div class="video-toggle-slide" style="display:none">
                        <div id="carousel-example-generic2" class="carousel slide overflow-hidden" data-ride="carousel" style="clear:both">


                            <ol class="carousel-indicators">
                                <?php
                                foreach ($project_videos as $key => $projets) {
                                    $activeimage++;
                                    if (count($project_videos) > 1) {
                                        ?>
                                        <li data-target="#carousel-example-generic2" data-slide-to="<?= $key; ?>" class="<?php echo ($activeimage == 1 ? 'active' : ''); ?>"></li>
                                      <!-- <li data-target="#carousel-example-generic" data-slide-to="<?= $key; ?>" class=""></li>
                                       <li data-target="#carousel-example-generic" data-slide-to="<?= $key; ?>" class="active"></li>-->
                                        <?php
                                    }
                                }
                                ?>
                            </ol> 
                            <div class="carousel-inner max-height-330" role="listbox">
                                <?php
                                $activeimage = 0;
                                foreach ($project_videos as $projets) {
                                    $activeimage++;
                                    ?>
                                    <div class="item no_img <?php echo ($activeimage == 1 ? 'active' : ''); ?>">
                                        <iframe width="100%" height="100%" src="<?php echo $projets['document_name']; ?>" frameborder="0" allowfullscreen=""></iframe>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                            if (count($project_image) > 1) {
                                ?>
                                <a class="left carousel-control" href="#carousel-example-generic2" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#carousel-example-generic2" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="item active no_imgg video_no_image" style="display:none">
                        <img src="<?php echo SITE_URL . Yii::getAlias('@web') . '/uploads/project_images/no_project_video.jpg'; ?>" alt="No_image" class="img-responsive">
                    </div>
                    <?php
                }
            } else {
                ?>
               <div class="item active no_imgg for_image">
                        <img src="<?php echo SITE_URL . Yii::getAlias('@web') . '/uploads/project_images/no_project_image.jpg'; ?>" alt="No_image" class="img-responsive">
                    </div>
             <div class="item active no_imgg video_no_image" style="display:none">
                        <img src="<?php echo SITE_URL . Yii::getAlias('@web') . '/uploads/project_images/no_project_video.jpg'; ?>" alt="No_image" class="img-responsive">
                    </div>
            
        <?php } ?>  

        </div>

        <div class="project-detail-address"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $projectData['location']; ?></div>
        <?php $uname = $projectData['Organization_name']?$projectData['Organization_name']:$projectData['fname'] . ' ' . $projectData['lname'];?>
        <div class="project-user"><i class="fa fa-user"></i> <span><?php echo $uname; ?></span> <span class="status">User Type: <b><?php echo $projectData['user_type'] == 'CSR'?'Corporate':$projectData['user_type']; echo !empty($projectData['media_agency_name']) ? " - ".$projectData['media_agency_name']:''; ?></b></span></div>
        <?php ?><div class="project-amount">
            <div class="budget-amount pull-left">Proposed Estimated Budget : &nbsp;<span><i class="fa fa-inr" aria-hidden="true"></i> &nbsp;<span class="total_b_amt"><?php echo $projectData['estimated_project_cost']; ?></span></span></div>
            <div class="pledged-amount pull-left">Expressions Received : &nbsp;<span><i class="fa fa-inr" aria-hidden="true"></i> &nbsp;<span class="total_p_amt"><?php echo abs($projectData['total_participation_amount']); ?></span></span></div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo round((($projectData['total_participation_amount']) / ($projectData['estimated_project_cost'])) * 100); ?>%"><?php echo round((($projectData['total_participation_amount']) / ($projectData['estimated_project_cost'])) * 100); ?>%</div>
            </div>            
        </div><?php ?>
        <div>
            <input type="hidden" class="already_partipated" value="">
        </div>
        <div class="remaining-days" style="color:#fff">Project End Date: <?php echo date('d-M-Y',strtotime($projectData['project_end_date'])); ?> </div>
    <?php  ?><?php
       // $diff = date_diff(date_create(date("Y-m-d")), date_create($projectData['project_end_date']));
        if (date_create(date("Y-m-d")) > date_create($projectData['project_end_date'])) {
    ?>
<!--            <div class="remaining-days" style="color:#fff"> Project has been exceeded by <?php // echo $diff->days; ?> days</div>-->
        <?php } else { ?>
        <?php $total_participation_amount = $projectData['total_participation_amount'] == ''?0:$projectData['total_participation_amount'];?>
<!--            <div class="remaining-days" style="color:#fff">  <?php // echo $diff->days; ?>  Days To Go </div>-->
             <div class="participate-area" onClick="participateProject(<?php echo $total_participation_amount; ?>, <?php echo $projectData['estimated_project_cost']; ?>, <?php echo $projectData['project_id']; ?>, '<?php echo Yii::$app->getUrlManager()->getBaseUrl() . '/../../search-projects?id=' . $projectData['project_id'] . '&cat=' . $projectData['project_category_ref_id']; ?>')"><a href="#">Express Your Interest</a></div>
        <?php } ?><?php ?>
             <div class="soclial-links text-center" style="margin-top:10px;"><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon" target="_blank"><img src="/equippp/frontend/web/images/coming-soon/fb-logo.png"></a> <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon" target="_blank"><img src="/equippp/frontend/web/images/coming-soon/instagram-logo.png"></a> <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon" target="_blank"><img src="/equippp/frontend/web/images/coming-soon/twit-logo.png"></a></div>
</div>
    </div>

    <div class="pop-tabs col-sm-7 col-xs-12" style="padding: 15px;">
        <div class="clearfix">

            <div class="objective-heading pull-left">Objective</div>

            <div class="pull-right project-detail-close">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-pane1">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
        </div>
        <div class="objective-text"><?php echo $projectData['objective']; ?></div>
        <div class="scroller scroller-left"><i class="glyphicon glyphicon-chevron-left"></i></div>
        <div class="scroller scroller-right"><i class="glyphicon glyphicon-chevron-right"></i></div>
        <div class="tabbable-line wrapper1">
            <ul class="nav nav-tabs gmap-tabs list1" id="myTab">
                <li role="presentation" class="active"><a role="tab" data-toggle="tab" href="#Description">Description</a></li>
                <li role="presentation"><a role="tab" data-toggle="tab" href="#Conditions" id="getConditions">
                    <!--<i class="fa fa-check-square-o cod-mapdata" aria-hidden="true"></i>-->
                        Conditions</a>
                </li>
                <?php if (Yii::$app->user->id) { ?>
                    <li role="presentation"><a role="tab" data-toggle="tab" href="#Documents" id="getDocuments">Documents</a></li>
                    <li role="presentation"><a role="tab" data-toggle="tab" href="#Comments" id="getComments">Comments</a></li>
                    <?php if ($projectData['user_ref_id'] == Yii::$app->user->id) { ?>
                        <li role="presentation"><a role="tab" data-toggle="tab" href="#Investors" class="getinvester">Investors List</a></li>
                    <?php } ?>
                <?php } ?>


            </ul>

            <div class="tab-content mar-btm10" style="padding: 0px !important;">
                <div id="Description" class="tab-pane fade in active">
                    <p><?php echo $projectData['project_desc'] ?></p>
                </div>

                <div id="Conditions" class="tab-pane fade">
                    <div class="ConditionsLink"><?php echo $projectData['conditions'] ?></div>
                </div>

                <div id="Comments" class="tab-pane fade">
                    <div class="row comment-rowtab comment-rowtab1">
                        <div class="divCommentsBlock">
                            <div id="divComments" style="min-height: 250px; overflow: auto;"></div>
                        </div>
                    </div>
                 
                    <div class="col-md-12 submit-comments mp-cmtbox mp-cmtbox1">
                        <?php if(date_create(date("Y-m-d")) <= date_create($projectData['project_end_date']))
                    {?>
                        <div class="cmt-box">
                            <textarea style="width: 73%" rows="2" placeholder="Comments" id="txtComments" name="txtComments"></textarea>
                            <input type="button" name="btnAddComments" id="btnAddComments" value="Send" disabled="disabled" class="btn btn-success cmt-btnsent map-commet-btn" />
                             </div>
                         <?php }?>
                            
                            <input type="hidden" name="projectId" id="projectId" value="<?php echo $projectData['project_id']; ?>" />
                            <input type="hidden" name="userId" id="userId" value="<?php echo Yii::$app->user->id; ?>" />
                            <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/displayallcomments" id="commentsUrl">
                       
                    </div>
                    
                        
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/displayallcomments" id="commentsUrl">
                    <input type="hidden" value="<?php echo (Yii::$app->getRequest()->getQueryParam('actionId')) ? Yii::$app->getRequest()->getQueryParam('actionId') : ""; ?>" id="actionId" />
                </div>

                <div id="Investors" class="tab-pane fade">
                    <div id="invster-data">   </div>
                </div>

                <div id="Documents" class="tab-pane fade">
                    <div id="divDocuments"></div>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/displayalldocuments" id="documentsUrl">
                </div>

            </div>
            <!--    <ul class="nav nav-tabs gmap-tabs">
                        <li><a data-toggle="tab" href="#Conditions" id="getConditions">Conditions</a></li>
                    </ul>-->

        </div>
        <!--    <div class="icon_twitter">
                    <span id="tweetBtn">Tweet</span> 
                </div>-->

        <?php /*?><div class="social-share-icons">
            <div class="icon_fb">
                <span onclick="FBShareOp();" data-layout="button_count" class="fa-facebook-icon" style="cursor: pointer">
                    <img src="<?php echo SITE_URL . Yii::getAlias('@web'); ?>/images/f-share.jpg" alt="Share">
                    <!--<i class="fa fa-facebook" aria-hidden="true"></i>-->
                </span>
            </div>

            <div id='twitter_div'>
                <a class="twitter popup" href="https://twitter.com/intent/tweet?url=[URL]">
                   <!-- <i class="fa fa-twitter" aria-hidden="true"></i>-->
                    <img src="<?php echo SITE_URL . Yii::getAlias('@web'); ?>/images/t-share.jpg" alt="Share">
                </a>   
            </div>
            <div class="mail-shar" onClick="emailSharing(<?php echo $projectData['project_id']; ?>, <?php echo $projectData['project_category_ref_id']; ?>, '<?php echo Yii::$app->getUrlManager()->getBaseUrl() . '/../../search-projects?id=' . $projectData['project_id'] . '&cat=' . $projectData['project_category_ref_id']; ?>')">
                <a href="javascript:void(0);" class="email-shar-icon">
                <!-- <i class="fa fa-envelope" aria-hidden="true"></i>-->
                    <img src="<?php echo SITE_URL . Yii::getAlias('@web'); ?>/images/m-share.jpg" alt="Share">
                </a>
            </div>


        </div><?php */?>



    </div>
</div>  


<span class="icon_google"></span>               

</div> 
</div>    
</div>
<input type="hidden" name="tweetText" id="tweetText" value="<?php echo urlencode(SITE_URL . Yii::getAlias('@web') . '/../../search-projects?id=' . $projectData["project_id"] . '&cat=' . $projectData["project_category_ref_id"]); ?>"/>

<script>
    /* variables used in project likes */
    var pid = '<?php echo $projectData["project_id"]; ?>';

    var uid = '<?php echo $projectData["user_ref_id"]; ?>';
    var userid = '<?php echo Yii::$app->user->id; ?>';
    var purl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/save-project-likes'); ?>';
    var gurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/is-project-liked'); ?>';
    var lurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/get-project-likes'); ?>';

    /* variables used in fb sharing */
    var img_name = '<?php echo $img; ?>';
    var siteUrl = '<?php echo SITE_URL . Yii::getAlias('@web'); ?>';
    var id = '<?php echo $projectData["project_id"]; ?>';
    var proj_cat = '<?php echo $projectData['project_category_ref_id']; ?>';
    var desc = "<?php echo strip_tags(trim(preg_replace('/\s+/', ' ', $projectData["project_desc"]))); ?>";
    var title = '<?php echo $projectData["project_title"]; ?>';
    var objective = "<?php echo trim(preg_replace('/\s+/', ' ', $projectData['objective'])); ?>";
    var img = 'https://s3.ap-south-1.amazonaws.com/<?php echo Yii::getAlias('@bucket') . '/uploads/project_images/' . $projectData["project_id"] . '/thumb/'; ?>' + img_name;

    $(document).ready(function () {

        /* remove twitter iframe */
        /* $('#tweetBtn iframe').remove();
         
         // Generate new markup
         var tweetBtn = $('<a></a>')
         .addClass('twitter-share-button')
         .attr('href', 'http://twitter.com/share')
         .attr('data-url', $('#tweetText').val());
         // .attr('data-text', $('#tweetText').val());
         $('#tweetBtn').replaceWith(tweetBtn);
         twttr.widgets.load();
         */

        $('.popup').click(function (event) {
            var url_val = $('#tweetText').val();
            $(this).attr('href', $(this).attr('href').replace('[URL]', url_val));
            var width = 575,
                    height = 400,
                    left = ($(window).width() - width) / 2,
                    top = ($(window).height() - height) / 2,
                    url = this.href,
                    opts = 'status=1' +
                    ',width=' + width +
                    ',height=' + height +
                    ',top=' + top +
                    ',left=' + left;

            window.open(url, 'twitter', opts);

            return false;
        });

        /* Project Likes*/


        //checkProjectLiked(pid);        
        $(".prjcount").append(<?php echo $projectData["projectlikes"]; ?>);

        if (userid) {
		$('.projectlike').hide();
            $.ajax({
                url: gurl,
                type: 'get',
                data: {pid: pid},
                success: function (data) {
				$('.projectlike').show();
                    if (data != "" && data >= 1) {
					$('.likebtn').remove();
                        $(".projectlike").addClass('project-liked');
                        //  $('div.projectlike').children().remove();
                        $(".projectlike").append("<span class='likebtn'><i class='pr-like prjlike project-liked' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>");
                    } else {
					$('.likebtn').remove();
                        $(".projectlike").removeClass('project-liked');
                        $(".projectlike").append("<span class='likebtn'><i class='pr-like prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=1></i></span>");
                    }
                }


            });
        } else {
            $(".projectlike").append("<span class='likebtn'><i class='pr-like prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>");
        }

        function checkProjectLiked(pid) {
            $.ajax({
                url: lurl,
                type: 'GET',
                data: {pid: pid},
                success: function (data) {
                    $( ".prjcount" ).empty();
                    $(".prjcount").append(data);
                    $(".projectlike-" + pid).empty();
                    $(".projectlike-" + pid).append(data);
                }
            });
        }


        $('.projectlike').on('click', '.prjlike', function (e) {
		
		if(!$( this ).hasClass( "project-liked" ))
		{
		
            if (!userid) {
                //FOR  MOBILE
                 if($(window).width()<=768)
              {
            if(!$('.container >.navbar-collapse').hasClass('in'))
               {
            $('.navbar-header > .navbar-toggle').trigger('click');
               }
              }
                $('#loginModal').trigger('click');
            } else {

                var pid;
                var like;

                pid = $(this).attr('pid');
                like = $(this).attr('like');
                if (like == 1)
                {
					$(this).attr('like', '0');
                    $.ajax({
                        url: purl,
                        type: 'get',
                        data: {
                            pid: pid, like: like
                        },
                        success: function (data) {
                            checkProjectLiked(pid);
                            if (data != "") {
                                if (data == 1) {
                                    $('.prjcount').empty();
                                    $('.likebtn').remove();
                                    $(".projectlike").addClass('project-liked');

                                    $(".projectlike").append("<span class='likebtn'><i class='pr-like prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>");
                                } else {
                                    $('.prjcount').empty();
                                    $('.likebtn').remove();
                                    $(".projectlike").removeClass('project-liked');
                                    $(".projectlike").append("<span class='likebtn'><i class='pr-like prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=1></i></span>");
                                }
                            }
                        }
                    });
                }else{
					return false;
				}
            }

            return false;
			}else{
				return false;
			}

        });


        $('.getinvester').on('click', function ()
        {
            var invester_url = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/invester-list'); ?>';
            $('#invster-data').html(' Please wait loading...');

            $.ajax({
                url: invester_url,
                type: 'post',
                data: {pid: pid},
                success: function (data) {
                    //alert(data);
                    $('#invster-data').html('');
                    $('#invster-data').append(data);
                }
            });

        });
        $('#close-pane1').on('click', function ()
        {
            $(".project-details").animate({marginRight: -$(".project-details").outerWidth()}).removeClass('prod');
            $(".project-details").css('display','none');
        });
          //for mobile close button
          $('#close-pane2').on('click',function()
         {
        $(".project-details").fadeOut('slow').css('display','none');
        });
    });
    
      
     


    function fancyConfirm(msg, callbackYes, callbackNo) {
        var ret;
        jQuery.fancybox({
            'modal': true,
            'content': "<div class=\"fancybx\" style=\"margin:1px;width:350px;\">" + msg + "<div style=\"text-align:right;margin-top:10px;\"><input id=\"fancyconfirm_cancel\" style=\"margin:3px;padding:0px;\" type=\"button\" class=\"btn btn-pink-tp pad-tb5 size16\"  value=\"Cancel\"><input id=\"fancyConfirm_ok\" class=\"btn btn-blue-tp pad-tb5 size16\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>",
            'beforeShow': function () {
                jQuery("#fancyconfirm_cancel").click(function () {
                    callbackNo();
                    $.fancybox.close();
                });

                jQuery("#fancyConfirm_ok").click(function () {
                    callbackYes();
                    // $.fancybox.close(); 
                });
            }
        });
    }
    function participateProject(total_participation_amount, estimated_project_cost, id, reference_url)
    {
        //projectId=(id);
        var projectId = id;
        var reference_url = reference_url;
        var total_participation_amount = total_participation_amount;
        var estimated_project_cost = estimated_project_cost;
        <?php if (isset(Yii::$app->user->id) && Yii::$app->user->id != 0) { ?>
        if(total_participation_amount > estimated_project_cost || total_participation_amount == estimated_project_cost){
            fancyConfirm('<span class="fancy_msg">The total participation amount has exceeded the project cost. Do you want to participate?</span>', function (e) {
                do_participate('yes');
            }, function () {
                do_participate('no');
            });
            function do_participate(res){
                    if (res == 'yes')
                    {                        
                        $.ajax({
                        url: getsite_url() + '/site/is-participated',
                        type: "post",
                        data: 'id=' + projectId,
                        success: function (participated_id) {
                            var data = participated_id;
                            participated_id = $.parseJSON(participated_id)

                            if (typeof participated_id == 'object')
                            {                                                               
                                if (participated_id['participation_type'] == 'Invest')
                                {
                                    var msg_fancy = "You have already invested Rs.<span class='invest'>" + participated_id['amount'] + " </span> in to this project.Do you want Renew?";
                                    $('.already_partipated').val(participated_id['amount'])
                                }                        
                                else 
                                {
                                    var msg_fancy = "You have already supported this project.Do you want to Renew ?";
                                    $('.already_partipated').val(participated_id['amount'])
                                }
                                // $('.fancybox-opened').css('z-index','999999');
                                fancyConfirm('<span class="fancy_msg">' + msg_fancy + '</span>', function (e) {                                    
                                    do_action('yes');
                                }, function () {                                     
                                    do_action('no');
                                });   
                                // $('body .fancybox-opened').css('z-index','999999'); 
                                function  do_action(a) {
                                    if (a == 'yes')
                                    {
                                        $.fancybox.close();
                                        $.fancybox({
                                            type: 'ajax',
                                            href: getsite_url() + '/project-participation/ajaxcreate/?id=' + projectId,
                                            helpers: {
                                                overlay: {closeClick: false,
                                                    css: {'z-index': 9}
                                                }// prevents closing when clicking OUTSIDE fancybox 

                                            }

                                        });
                                    }
                                    else if(a == 'redirect_to_profile_page'){
                                        window.location.replace(getsite_url()+"/profile");
                                    }
                                }

                            }
                            else if(data == 2){
                                fancyConfirm('<span class="fancy_msg">Please set your profile before requesting access for this project </span>', function() {
                                        do_redirect('redirect_to_profile_page');
                                        }, function() {
                                         do_redirect('no');
                                    }); 
                            }
                            else
                            {
                                $.fancybox({
                                    type: 'ajax',
                                    href: getsite_url() + 'project-participation/ajaxcreate/?id=' + projectId,
                                    helpers: {
                                        overlay: {closeClick: false,
                                        }
                                        // prevents closing when clicking OUTSIDE fancybox 
                                    }
                                });
                            }
                        }
                    });
                }
            } 
        }else{
            $.ajax({
                url: getsite_url() + '/site/is-participated',
                type: "post",
                data: 'id=' + projectId,
                success: function (participated_id) {
                    var data = participated_id;
                    participated_id = $.parseJSON(participated_id)

                    if (typeof participated_id == 'object')
                    {                       
                        if (participated_id['participation_type'] == 'Invest')
                        {
                            var msg_fancy = "You have already invested Rs.<span class='invest'>" + participated_id['amount'] + " </span> in to this project.Do you want Renew?";
                            $('.already_partipated').val(participated_id['amount'])
                        }                        
                        else 
                        {
                            var msg_fancy = "You have already supported this project.Do you want to Renew ?";
                            $('.already_partipated').val(participated_id['amount'])
                        }
                        fancyConfirm('<span class="fancy_msg">' + msg_fancy + '</span>', function (e) {
                            do_action('yes');
                        }, function () {
                            do_action('no');
                        });
                        function  do_action(a) {
                            if (a == 'yes')
                            {
                                $.fancybox.close();
                                $.fancybox({
                                    type: 'ajax',
                                    href: getsite_url() + '/project-participation/ajaxcreate/?id=' + projectId,
                                    helpers: {
                                        overlay: {closeClick: false,
                                            css: {'z-index': 9}
                                        }// prevents closing when clicking OUTSIDE fancybox 

                                    }

                                });
                            }
                            else if(a == 'redirect_to_profile_page'){
                                window.location.replace(getsite_url()+"/profile");
                            }
                        }

                    }
                    else if(data == 2){
                        fancyConfirm('<span class="fancy_msg">Please set your profile before requesting access for this project </span>', function() {
                                do_redirect('redirect_to_profile_page');
                                }, function() {
                                 do_redirect('no');
                            }); 
                    }
                    else
                    {
                        $.fancybox({
                            type: 'ajax',
                            href: getsite_url() + 'project-participation/ajaxcreate/?id=' + projectId,
                            helpers: {
                                overlay: {closeClick: false,
                                }
                                // prevents closing when clicking OUTSIDE fancybox 
                            }
                        });
                    }
                }
            });
        }
    <?php
    } else {
    ?>
            //if($(".project-details").hasClass('prod'))
            //{
            //	$(".project-details").animate({marginRight : -$(".project-details").outerWidth()}).removeClass('prod');
            //} 
            //FOR MOBILE DEVICES
           if($(window).width()<=768)
              {
            if(!$('.container >.navbar-collapse').hasClass('in'))
               {
            $('.navbar-header > .navbar-toggle').trigger('click');
               }
              }
            $('#loginModal').trigger('click');
            $("div.rurl").remove();
            $("form#login-form").append('<div class=\"rurl\"><input type=\"hidden\" id=\"reference_url\" name=\"reference_url\" value=\"' + reference_url + '" /></div>');
            // e.stopImmediatePropagation();
    <?php } ?>
        }
        
    function saveComment(commentsUrl, projectCommentId, projectId) {
        var comments = $('#comment_' + projectCommentId).val();

        $.ajax({
            url: commentsUrl,
            type: 'get',
            data: {'comments': comments, 'projectCommentId': projectCommentId, 'projectId': projectId},
            success: function (data) {
                //alert('Success');
                $('#comment_' + projectCommentId).prop('disabled', true);
                $('#comment_' + projectCommentId).css('border', '0px none black');
                $('#btnEdit_' + projectCommentId).show();
                $('#btnSave_' + projectCommentId).hide();
                $('#commentId').val('');
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
        return false;
    }
        
    function modifyComment(projectCommentId) {
        $('#comment_' + projectCommentId).prop('disabled', false);
        //$('.commentEdit_'+projectCommentId).removeAttr('onClick');
        $('#comment_' + projectCommentId).css('border', '1px solid black');
        $('#btnEdit_' + projectCommentId).hide();
        $('#btnSave_' + projectCommentId).show();
        $('#commentId').val(projectCommentId);
    }

    function deleteComment(projectCommentId) {
        var commentsUrl = $('#commentsUrl').val();
        var projectId = $('#projectId').val();

        $.ajax({
            url: commentsUrl,
            type: 'get',
            data: {'projectCommentId': projectCommentId, 'projectId': projectId},
            success: function () {
                //alert('Success--'+projectCommentId);
                //if (data) {
                $('#divComment_' + projectCommentId).remove();
                return false;
                //}
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
    }


    $(function () {

        $('#getComments').on('click', function () {
            var commentsUrl = $('#commentsUrl').val();
            //var comments = $('#txtComments').val();
            var projectId = '<?php echo $projectData['project_id']; ?>';
            $('#projectId').val(projectId);
            if ($('#searchUrl').val())
                var actionId = '';
            else
                var actionId = 'googleMap';

            $.ajax({
                url: commentsUrl,
                type: 'get',
                data: {'projectId': projectId, 'actionId': actionId},
                success: function (data) {
                    //alert(data);
                    $('#Description').removeClass('in');
                    $('#Description').removeClass('active');
                    $('#Comments').addClass('in');
                    $('#Comments').addClass('active');
                    $('#Investors').removeClass('in');
                    $('#Investors').removeClass('active');
                    $('#Conditions').removeClass('in');
                    $('#Conditions').removeClass('active');
                    $('#Documents').removeClass('in');
                    $('#Documents').removeClass('active');
                    $('.gmap-tabs li').removeClass('active');
                    $('#getComments').parent().addClass('active');
                    $('#divComments').html(data);
                    return false;
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
        });
        //$('#create_form').validate();


        $('#getDocuments').on('click', function () {
            var documentsUrl = $('#documentsUrl').val();
            //var comments = $('#txtComments').val();
            var projectId = '<?php echo $projectData['project_id']; ?>';
            $('#projectId').val(projectId);
            if ($('#searchUrl').val())
                var actionId = '';
            else
                var actionId = 'googleMap';

            $.ajax({
                url: documentsUrl,
                type: 'get',
                data: {'projectId': projectId, 'actionId': actionId},
                success: function (data) {
                    $('#Description').removeClass('in');
                    $('#Description').removeClass('active');
                    $('#Comments').removeClass('in');
                    $('#Comments').removeClass('active');
                    $('#Investors').removeClass('in');
                    $('#Investors').removeClass('active');
                    $('#Conditions').removeClass('in');
                    $('#Conditions').removeClass('active');
                    $('#Documents').addClass('in');
                    $('#Documents').addClass('active');
                    $('.gmap-tabs li').removeClass('active');
                    $('#getDocuments').parent().addClass('active');
                    $('#divDocuments').html(data);
                    return false;
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
        });

        $('#getConditions').on('click', function () {
            $('#Description').removeClass('in');
            $('#Description').removeClass('active');
            $('#Comments').removeClass('in');
            $('#Comments').removeClass('active');
            $('#Investors').removeClass('in');
            $('#Investors').removeClass('active');
            $('#Documents').removeClass('in');
            $('#Documents').removeClass('active');
            $('#Conditions').addClass('in');
            $('#Conditions').addClass('active');
            $('.gmap-tabs li').removeClass('active');
            $('#getConditions').parent().addClass('active');

            return false;
        });
        
        
        $('#btnAddComments').on('click', function (e) {
            var commentsUrl = $('#commentsUrl').val();
            var comments = $('#txtComments').val();
            var projectId = $('#projectId').val();
            var userId = $('#userId').val();

            $.ajax({
                url: commentsUrl,
                type: 'get',
                data: {'comments': comments, 'projectId': projectId, 'userId': userId},
                success: function (data) {
                    //alert(data);
                    if (data) {
                        //alert('Success In IF');
                        $('#txtComments').val();
                        $.ajax({
                            url: commentsUrl,
                            type: 'get',
                            data: {'projectId': projectId},
                            success: function (data) {
                                $('#divComments').html(data);
                                $('#txtComments').val('');
                                $('#btnAddComments').attr('disabled',true);
                            },
                            error: function (xhr, status, error) {
                                alert('There was an error with your request.' + xhr.responseText);
                            }
                        });
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
                        return false;
                    }
                    return false;
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
        });

        /*
         $('#btnAddComments').on('click', function (e) {
         alert('Hello');
         var commentsUrl = $('#commentsUrl').val();
         var comments = $('#txtComments').val();
         var projectId = $('#projectId').val();
         var userId = $('#userId').val();
         
         $.ajax({
         url: commentsUrl,
         type: 'get',
         data: {'comments': comments, 'projectId': projectId, 'userId': userId},
         success: function (data) {
         alert(data);
         if (data) {
         //alert('Success In IF');
         $('#txtComments').val();
         $.ajax({
         url: commentsUrl,
         type: 'get',
         data: {'projectId': projectId},
         success: function (data) {
         $('#divComments').html(data);
         },
         error: function (xhr, status, error) {
         alert('There was an error with your request.' + xhr.responseText);
         }
         });
         //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
         //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
         return false;
         }
         },
         error: function (xhr, status, error) {
         alert('There was an error with your request.' + xhr.responseText);
         }
         });
         return false;
         });
         

        $('#getComments').on('click', function () {
            var commentsUrl = $('#commentsUrl').val();
            //var comments = $('#txtComments').val();
            var projectId = '<?php echo $projectData['project_id']; ?>';
            $('#projectId').val(projectId);
//            if ($('#searchUrl').val())
//                var actionId = '';
//            else
//                var actionId = 'googleMap';

            $.ajax({
                url: commentsUrl,
                type: 'get',
//                data: {'projectId': projectId, 'actionId': actionId},
                data: {'projectId': projectId},
                success: function (data) {
                    $('#Description').removeClass('in');
                    $('#Description').removeClass('active');
                    $('#Comments').addClass('in');
                    $('#Comments').addClass('active');
                    $('#Investors').removeClass('in');
                    $('#Investors').removeClass('active');
                    $('#Documents').removeClass('in');
                    $('#Documents').removeClass('active');
                    $('#Conditions').removeClass('in');
                    $('#Conditions').removeClass('active');
                    $('.gmap-tabs li').removeClass('active');
                    $('#getComments').parent().addClass('active');
                    $('#divComments').html(data);
                    return false;
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
        });
        //$('#create_form').validate();

        $('#getDocuments').on('click', function () {
            var documentsUrl = $('#documentsUrl').val();
            //var comments = $('#txtComments').val();
            var projectId = '<?php echo $projectData['project_id']; ?>';
            $('#projectId').val(projectId);
//            if ($('#searchUrl').val())
//                var actionId = '';
//            else
//                var actionId = 'googleMap';

            $.ajax({
                url: documentsUrl,
                type: 'get',
//                data: {'projectId': projectId, 'actionId': actionId},
                data: {'projectId': projectId},
                success: function (data) {
                    $('#Description').removeClass('in');
                    $('#Description').removeClass('active');
                    $('#Comments').removeClass('in');
                    $('#Comments').removeClass('active');
                    $('#Investors').removeClass('in');
                    $('#Investors').removeClass('active');
                    $('#Conditions').removeClass('in');
                    $('#Conditions').removeClass('active');
                    $('#Documents').addClass('in');
                    $('#Documents').addClass('active');
                    $('.gmap-tabs li').removeClass('active');
                    $('#getDocuments').parent().addClass('active');
                    $('#divDocuments').html(data);
                    return false;
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
        });
        
        $('#getConditions').on('click', function () {
            $('#Description').removeClass('in');
            $('#Description').removeClass('active');
            $('#Comments').removeClass('in');
            $('#Comments').removeClass('active');
            $('#Investors').removeClass('in');
            $('#Investors').removeClass('active');
            $('#Documents').removeClass('in');
            $('#Documents').removeClass('active');
            $('#Conditions').addClass('in');
            $('#Conditions').addClass('active');
            $('.gmap-tabs li').removeClass('active');
            $('#getConditions').parent().addClass('active');
            
            return false;
        });
        */
    
    });

    /* Load Facebook SDK for JavaScript */
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1198675376821245',
            xfbml: true,
            version: 'v2.6'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = siteUrl + "/themes/custom/js/fsdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    /* add dynamic data to fb dialogue feed*/
    function FBShareOp() {
        var product_name = title;
        var description = desc;
        var share_image = img;
        var share_url = siteUrl + '/../../search-projects?id=' + id +'&cat=' + proj_cat;
        var share_capt = objective;
        // alert(share_url);
        FB.ui({
            method: 'feed',
            name: product_name,
            link: share_url,
            picture: share_image,
            caption: share_capt,
            description: description

        }, function (response) {
            if (response && response.post_id) {
                console.log(response);
            }
            else {
                console.log(response.post_id);
            }
        });

    }

    $(function ()
    {
        var total_height = $(window).height();
        var header_top = $('.navbar-fixed-top').height();
        var total_header = header_top + 60;
        var project_map1 = total_height - total_header;
        //$('.project-details >.project-detail-pane >.project-detail-black-pane').css('height', project_map1 - 9 + 'px');
        if (window.matchMedia("(max-width: 1024px)").matches) {
            //$('.project-detail-black-pane').css({'min-height' : '645px', 'white-space' : 'inherit','overflow':'hidden'});
        } 

        //for image slider in map page
        $('.fancybox_for_image').fancybox({
            prevEffect: 'none',
            autoSize: false,
            'nextEffect': 'none',
            'closeBtn': true,
            helpers: {
                title: {type: 'inside'},
                overlay: {
                    css: {'z-index': 9}


                }

            }
        });

        //for embed videos
        $('.video-toggle').on('click', function ()
        {
            $('.video-toggle-slide').css('display', 'block')
            $('#carousel-example-generic1').css('display', 'none');
            $('.video_no_image').css('display', 'block');
            $('.for_image').css('display', 'none');
            $('.project-detail-category').css('display', 'none');

        });

        $('.slider-toggle').on('click', function ()
        {
            $('.video-toggle-slide').css('display', 'none');
            $('#carousel-example-generic1').css('display', 'block');
            $('.for_image').css('display', 'block');
            $('.video_no_image').css('display', 'none');
            $('.project-detail-category').css('display', 'block');

        });
        
        $('#txtComments').on('keyup',function(){
            if($('#txtComments').val() == ''){
                $('#btnAddComments').attr('disabled',true);
                return false;
            }else{
                $('#btnAddComments').attr('disabled',false);
                return false;
            }
        });
    });

    /* For email sharing from google map page */
    function emailSharing(prj_id, cid, reference_url) {
        var reference_url = reference_url;
        var cid = cid;
        var project_id = prj_id;

    <?php if (isset(Yii::$app->user->identity->id) && Yii::$app->user->identity->id != 0) { ?>
            $.fancybox({
                type: 'ajax',
                href: siteUrl + '/send-email/index?id=' + project_id + '&cat=' + cid
            });
    <?php } else { ?>
      // FOR MOBILE
           if($(window).width()<=768)
              {
            if(!$('.container >.navbar-collapse').hasClass('in'))
               {
            $('.navbar-header > .navbar-toggle').trigger('click');
               }
              }
            $('#loginModal').trigger('click');
            $("div.rurl").remove();
            $("form#login-form").append('<div class=\"rurl\"><input type=\"hidden\" id=\"reference_url\" name=\"reference_url\" value=\"' + reference_url + '" /></div>');

    <?php } ?>
        }
</script>

<script>
    var hidWidth;
    var scrollBarWidths = 40;

    var widthOfList = function () {
        var itemsWidth = 0;
        $('.list1 li').each(function () {
            var itemWidth = $(this).outerWidth();
            itemsWidth += itemWidth;
        });
        return itemsWidth;
    };

    var widthOfHidden = function () {
        return (($('.wrapper1').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
    };

    var getLeftPosi = function () {
        return $('.list1').position().left;
    };

    var reAdjust = function () {
        //alert($('.wrapper1').innerWidth()+"____"+$('.wrapper1').outerWidth()+"____"+widthOfList());
        if (($('.wrapper1').outerWidth()) < widthOfList()) {
            $('.scroller-right').show();
            $('.scroller-left').fadeIn('slow');
        }
        else {
            $('.scroller-right').hide();
            //$('.list1').css('margin-left', '5px');
        }

        if (getLeftPosi() < 0) {
            $('.scroller-left').show();
            }
        else {
            $('.item').animate({left: "-=" + getLeftPosi() + "px"}, 'slow');
            //$('.scroller-left').hide();
             }             
       
    }
    
   /*  function windowReadjust(){
        if (window.matchMedia("(min-width: 768px)").matches || window.matchMedia("(max-width: 990px)").matches) {
           //alert("sdhfdhgf");
            $('.project-details').css({'margin-right' : '200px'});
           
        }else if(window.matchMedia("(min-width: 1024px)").matches){
            $('.project-details').css({'margin-right' : '260px'});           
        }else{
           alert('here');
        }
     } */

    reAdjust();

    $(window).on('resize', function (e) {
        reAdjust();
       // windowReadjust();
    });
    //$('.scroller-left').fadeIn('slow');
    $('.scroller-right').click(function () {

        $('.scroller-left').fadeIn('slow');
        //$('.scroller-right').fadeOut('slow');
        $('.list1').animate({left: "+=" + widthOfHidden() + "px"}, 'slow', function () {

        });
    });

    $('.scroller-left').click(function () {
        if (getLeftPosi() != 0)
            $('.scroller-right').fadeIn('slow');
        //$('.scroller-left').fadeOut('slow');
        $('.list1').animate({left: "-=" + getLeftPosi() + "px"}, 'slow', function () {

        });
    });
    
    function  do_redirect(a) {
        if(a == 'redirect_to_profile_page'){
            window.location.replace(getsite_url()+"/profile");
        }
    }
</script>

<style>
    .fancybox-wrap {
        position: absolute;
        /*top: 100px !important;*/
    }	
	@media (min-width:1100px){
	.fancybox-inner .project-participation-form.participation-border {width: 100%;overflow-y: auto;overflow-x:hidden;height: 280px;}
	}
</style>