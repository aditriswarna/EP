<?php
// use common\models\Storage;
$img = isset($rows[0]['document_name'])?$rows[0]['document_name']:'no_image'; 

?>
<link href="<?php echo Yii::$app->urlManagerFrontend->baseUrl ?>/../themes/custom/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<style>
    .fancybox-opened {        width: 850px !important;    }
	@media (max-width: 991px) {       .fancybox-opened {  width:90% !important; }     } 
</style>
<div class="project-detail-pane ">
    <div class="project-detail-black-pane col-sm-5 col-xs-12">
        <!--<div class="pull-right projectlike"><span class="prjcount"></span></div>-->
        <div class="project-detail-title"><?php echo $projectData['project_title']; ?></div>
        <div class="project-detail-image">
        <div class="project-detail-category"><p class="category-p"><?php echo $projectData['category_name']; ?></p></div>
            <div class="pull-right media-icons">
                 <i class="fa fa-sm fa-picture-o currenti slider-toggle" title="Images"></i>
                <i class="fa fa-sm fa-video-camera video-toggle" title="Videos"></i>
            </div>
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
                            foreach ($project_image as $projects) {

                                $activeimage++;

                                if (!empty($projects['document_name']))
                                    $projectImageUrl = 'https://s3.ap-south-1.amazonaws.com/'. Yii::getAlias('@bucket') . '/uploads/project_images/' . $projects['project_ref_id'] . '/' . $projects['document_name'];
                                  /*  $s3 = new Storage(); 
                                    $res = $s3->checkKeyExists(Yii::getAlias('@bucket'), $filename);
                                    print_r($res); */
                                else
                                    $projectImageUrl = Yii::$app->urlManagerFrontend->baseUrl . '/project_images/no_project_image.jpg';
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
                        <img src="<?php  echo Yii::$app->urlManagerFrontend->baseUrl.'/project_images/no_project_image.jpg'; ?>" alt="No_image" class="img-responsive">
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
                        <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl.'/project_images/no_project_video.jpg'; ?>" alt="No_image" class="img-responsive">
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="item active no_imgg for_image">
                    <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl.'/project_images/no_project_image.jpg'; ?>" alt="No_image" class="img-responsive">
                </div>
                <div class="item active no_imgg video_no_image" style="display:none">
                    <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl.'/project_images/no_project_video.jpg'; ?>" alt="No_image" class="img-responsive">
                </div>

            <?php } ?>  
        </div>
            <div class="project-detail-address"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $projectData['location']; ?></div>
            <div class="project-user"><i class="fa fa-user"></i> <span><?php echo $projectData['fname'].' '.$projectData['lname']; ?></span> <span class="status">User Type: <b><?php echo $projectData['user_type']; echo !empty($projectData['media_agency_name']) ? " - ".$projectData['media_agency_name']:''; ?></b></span></div>
            <div class="project-amount">
                <div class="pledged-amount pull-left">Expressions Received : &nbsp;<span><i class="fa fa-inr" aria-hidden="true"></i> &nbsp;<?php echo $projectData['total_participation_amount']; ?></span></div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo round((($projectData['total_participation_amount']) / ($projectData['estimated_project_cost'])) * 100); ?>%"><?php echo round((($projectData['total_participation_amount']) / ($projectData['estimated_project_cost'])) * 100); ?>%</div>
                </div>
                <div class="budget-amount pull-left">Proposed Estimated Budget : &nbsp;<span><i class="fa fa-inr" aria-hidden="true"></i> &nbsp;<?php echo number_format($projectData['estimated_project_cost']); ?></span></div>
            </div>
            
            <?php
    $diff=date_diff(date_create(date("Y-m-d")),date_create($projectData['project_end_date']));
    if(date_create(date("Y-m-d")) > date_create($projectData['project_end_date']))
    {  
    ?>
        <div class="remaining-days"> Project has been exceeded by <?php echo $diff->days ;?> days</div>
    <?php  } else { ?>
         <div class="remaining-days">  <?php echo $diff->days;?>  Days To Go </div>
    <?php }  ?>
        <!--<div class="participate-area" onClick="participateProject(<?php //echo $projectData['project_id'];?>)"><a href="#">Participate</a></div>-->
       
        
        </div>
        
        <div class="pop-tabs col-sm-7 col-xs-12 project-popup-view">
            <div class="clearfix">
                <div class="">
                    <div class="objective-heading pull-left">Objective</div>
                </div>
               
            <!--<div class=" col-sm-4">
                    <div class="pull-right project-detail-close">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-pane1">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                </div> -->
            </div>
            <div class="objective-text"><?php echo $projectData['objective']; ?></div>
            <div class="tabbable-line">
                <ul class="nav nav-tabs gmap-tabs">
                    <li class="active"><a data-toggle="tab" href="#Description">Description</a></li>
                    <li ><a data-toggle="tab" href="#Conditions">Conditions</a></li>
                    <?php if(Yii::$app->user->id) { ?>
                    <li><a data-toggle="tab" href="#Documents"  id="getDocuments">Documents</a></li>
                    <li><a data-toggle="tab" href="#Comments" id="getComments">Comments</a></li>
                    <?php //if ($projectData['user_ref_id'] == Yii::$app->user->id) { ?>
                    <li><a data-toggle="tab" href="#Investors"  class="getinvester">
                            Investors List
                    </a></li>
                    <?php //} ?>                    
                    <?php } ?>
                </ul>

                <div class="tab-content" style="padding: 0px !important;">
                    <div id="Description" class="tab-pane fade in active tab-view-mar-btm10 tabHeight">
                        <p><?php echo $projectData['project_desc'] ?></p>
                    </div>

                    <div id="Comments" class="tab-pane fade ">
                        <div class="row comment-rowtab" style="background-color: #FFFFFF;">
                            <div class="divCommentsBlock">
                                <div id="divComments" class="tab-view-mar-btm10"></div>
                                <?php
                                //if(empty(Yii::$app->getRequest()->getQueryParam('actionId'))) {
                                ?>
                                    <div class="col-md-12 submit-comments mp-cmtbox">
                                    	<div class="cmt-box">
                                        <textarea cols="50" rows="2" placeholder="Comments" id="txtComments" name="txtComments"></textarea>
                                        <input type="button" name="btnAddComments" id="btnAddComments" disabled="disabled" value="Send" class="btn btn-success cmt-btnsen" style="float: right;     padding: 6px 20px 4px 20px; margin:7px;" />
                                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $projectData['project_id']; ?>" />
                                        <input type="hidden" name="userId" id="userId" value="<?php echo Yii::$app->user->id; ?>" />
                                        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/displayallcomments" id="commentsUrl">
                                    </div></div>
                               <?php //} ?>
                            </div>
                        </div>
                        <!--<input type="hidden" value="<?php //echo Yii::$app->request->BaseUrl; ?>/projects/displayallcomments" id="commentsUrl_1">-->
                        <input type="hidden" value="<?php echo (Yii::$app->getRequest()->getQueryParam('actionId')) ? Yii::$app->getRequest()->getQueryParam('actionId') : ""; ?>" id="actionId" />
                    </div>

                    <div id="Investors" class="tab-pane fade tab-view-mar-btm10 tabHeight">
                        <div id="invster-data">   </div>
                    </div>

                    <div id="Documents" class="tab-pane fade tab-view-mar-btm10 tabHeight">
                        <div id="divDocuments"></div>
                        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/displayalldocuments" id="documentsUrl">
                    </div>
                    
                    <div id="Conditions" class="tab-pane fade tabHeight">
                        <div class="condition-detailes">
                            <div class=""><?php echo nl2br($projectData['conditions']); ?></div>
                        </div>
                    </div>

                </div>

                </div>
                
<!--            <div class="social-media-icons">
                <div class="icon_fb">
                    <span onclick="FBShareOp();" data-layout="button_count" style="cursor: pointer"><img src="<?php echo SITE_URL. Yii::getAlias('@web'); ?>/images/icon_facebook_share.png" alt="Share"></span>
                </div>
                     
                <div class="icon_twitter">
                    <span id="tweetBtn">Tweet</span> 
                </div>
            </div>-->
<div class="social-share-icons">
                        <div class="icon_fb">
                            <span onclick="FBShareOp();" data-layout="button_count" class="fa-facebook-icon" style="cursor: pointer">
                                <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl; ?>/../images/f-share.jpg" alt="Share">
                                <!--<i class="fa fa-facebook" aria-hidden="true"></i>-->
                            </span>
                        </div>

                        <div id='twitter_div'>
                            <a class="twitter popup" href="https://twitter.com/intent/tweet?url=[URL]">
                               <!-- <i class="fa fa-twitter" aria-hidden="true"></i>-->
                             <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl; ?>/../images/t-share.jpg" alt="Share">
                            </a>   
                        </div>
                        <div class="mail-shar" onClick="emailSharing(<?php echo $projectData['project_id']; ?>, <?php echo $projectData['project_category_ref_id']; ?>, '<?php echo Yii::$app->getUrlManager()->getBaseUrl() . '/../../search-projects?id=' . $projectData['project_id'] . '&cat=' . $projectData['project_category_ref_id']; ?>')">
                            <a href="javascript:void(0);" class="email-shar-icon">
                            <!-- <i class="fa fa-envelope" aria-hidden="true"></i>-->
                            <img src="<?php echo Yii::$app->urlManagerFrontend->baseUrl; ?>/../images/m-share.jpg" alt="Share">
                            </a>
                        </div>


                    </div>              
    </div>  
      
                
                <span class="icon_google"></span>               
               
                </div> 
            </div>    
</div>
<input type="hidden" name="tweetText" id="tweetText" value="<?php echo Yii::$app->urlManagerFrontend->baseUrl.'/../'; ?>/site/dynamic-new?id=<?php echo $projectData["project_id"]; ?>"/>

    <script>
        /*
        $(document).on('click', '#btnAddComments', function(){
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
        
        */
       var backendUrl = '<?php echo yii::$app->request->baseUrl;?>';
        
        /* variables used in project likes */
        var pid='<?php echo $projectData["project_id"]; ?>';

        var uid='<?php echo $projectData["user_ref_id"]; ?>';
        var userid = '<?php echo Yii::$app->user->id; ?>';
        var purl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/save-project-likes'); ?>';
        var gurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/is-project-liked'); ?>';
        var lurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/get-project-likes'); ?>';
       
        
       
        /* variables used in fb sharing */
        var img_name = '<?php echo $img;?>';
        var siteUrl = '<?php echo Yii::$app->urlManagerFrontend->baseUrl.'/../../'; ?>';
        var id = '<?php echo $projectData["project_id"]; ?>';   
        var desc = '<?php echo strip_tags(trim(preg_replace('/\s+/', ' ', $projectData["project_desc"]))); ?>';    
        var title = '<?php echo $projectData["project_title"]; ?>';
        var objective = '<?php echo trim(preg_replace('/\s+/', ' ', $projectData["objective"])); ?>';
        var img = 'https://s3.ap-south-1.amazonaws.com/<?php echo Yii::getAlias('@bucket') .'/uploads/project_images/'.$projectData["project_id"].'/thumb/'; ?>'+img_name;
    
        $(document).ready(function() {
        
        /* twitter sharing */
        $('.popup').click(function(event) {
            var url_val = $('#tweetText').val();
            $(this).attr('href',$(this).attr('href').replace('[URL]', url_val));
            var width  = 575,
            height = 400,
            left   = ($(window).width()  - width)  / 2,
            top    = ($(window).height() - height) / 2,
            url    = this.href,                
            opts   = 'status=1' +
                ',width='  + width  +
                ',height=' + height +
                ',top='    + top    +
                ',left='   + left;

            window.open(url, 'twitter', opts);

            return false;
        });
    
    /* Project Likes*/
    
       
        /*checkProjectLiked(pid);        
    
       
       if(userid){
       $.ajax({
                url: gurl,
                type: 'get',
                data: {pid: pid},
                success: function (data) {
                    if (data != "" && data >= 1) {
                        $( ".projectlike" ).addClass('project-liked');
                        //  $('div.projectlike').children().remove();
                        $(".projectlike").append("<span class='likebtn'><i class='fa fa-sm fa-heart prjlike project-liked' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>");
                    } else {
                        $( ".projectlike" ).removeClass('project-liked');
                        $(".projectlike").append("<span class='likebtn'><i class='fa fa-sm fa-heart prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=1></i></span>");
                    }
                }


            });
            }else{
            $( ".projectlike" ).append( "<span class='likebtn'><i class='fa fa-sm fa-heart prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>" );
            }
         
        function checkProjectLiked(pid){         
        $.ajax({
                url: lurl,
                type: 'GET',
                data: {pid: pid},
                success: function (data) {
                    //   $( ".prjcount" ).empty();
                    $(".prjcount").append(data);
                }
            });
        }

        
        $('.projectlike').on('click', '.prjlike', function (e) {             
            if (!userid){
               $('#loginModal').trigger('click');
            }else{

                 var pid; var like;

                pid     = $(this).attr('pid');
                like     = $(this).attr('like');
            if(like == 1)
            {
                $.ajax({
                    url: purl,
                    type: 'get',
                    data: {
                        pid:pid,like:like
                             },
                    success: function(data) {
                        checkProjectLiked(pid);
                        if(data !=""){
                            if(data==1){
                                $('.prjcount').empty();
                                $('.likebtn').remove();
                                $( ".projectlike" ).addClass('project-liked');
                                
                                $( ".projectlike" ).append( "<span class='likebtn'><i class='fa fa-sm fa-heart prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=0></i></span>" );
                            }else{
                                $('.prjcount').empty();
                                $('.likebtn').remove();
                                $( ".projectlike" ).removeClass('project-liked');
                                $( ".projectlike" ).append( "<span class='likebtn'><i class='fa fa-sm fa-heart prjlike' pid='<?php echo $projectData["project_id"]; ?>' like=1></i></span>" );
                            }
                        }
                    }
                });
                }
            }

            return false;

        });*/


        $('.getinvester').on('click', function ()
        {
            var invester_url = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/invester-list'); ?>';
            $('#invster-data').html(' Please wait loading...');
            var pid = '<?php echo $projectData['project_id']; ?>';

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
$('#close-pane1').on('click',function()
	{
		  $(".project-details").animate({marginRight : -$(".project-details").outerWidth()}).removeClass('prod');
});
});

 
    function participateProject(id)
	{
            //projectId=(id);
		var projectdata=id;
	    
		$.ajax({
			url: getsite_url()+'site/is-login',
			type: "post",
			data:'id='+projectId,
			success: function(data){ 
				if(data == 'LoggedIn') {
                                     
                              $.fancybox({
                              type: 'ajax',
                               href: getsite_url()+'project-participation/ajaxcreate/?id='+projectId    
                              });
                       
                   }
				else if(data == "NotLoggedIn")
				{ 
					//if($(".project-details").hasClass('prod'))
					//{
					//	$(".project-details").animate({marginRight : -$(".project-details").outerWidth()}).removeClass('prod');
					//} 
			   
					$('#loginModal').trigger('click');
					// e.stopImmediatePropagation();
				}
				 
		}
	});
        }
        
   
    
    $(function () {
        
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
        */
        
        $('#getComments').on('click', function () {
            var commentsUrl = $('#commentsUrl').val();
            //var comments = $('#txtComments').val();
            var projectId = '<?php echo $projectData['project_id']; ?>';
            $('#projectId').val(projectId);
            if($('#searchUrl').val())
                var actionId = '';
            else
                var actionId = 'googleMap';

            $.ajax({
                url: commentsUrl,
                type: 'get',
                data: {'projectId': projectId, 'actionId': actionId},
                success: function (data) {
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
    
    </script>
    
    <?php
    /*
    $this->registerJs("function addComment123(commentsUrl, userId) {
        alert('111111111111111');
        var comments = $('#txtComments').val();
        var projectId = $('#project_id').val();

        $.ajax({
            url: commentsUrl,
            type: 'get',
            data: {'comments': comments, 'projectId': projectId, 'userId': userId},
            success: function (data) {
                alert('Success');
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
    }");
    */
    ?>
    <script>
    function saveComment(commentsUrl, projectCommentId, projectId) {
        var comments = $('#comment_'+projectCommentId).val();
        
        $.ajax({
                url: commentsUrl,
                type: 'get',
                data: {'comments': comments, 'projectCommentId': projectCommentId, 'projectId': projectId},
                success: function (data) {
                    //alert('Success');
                    $('#comment_'+projectCommentId).prop('disabled', true);
                    $('#comment_'+projectCommentId).css('border', '0px none black');
                    $('#btnEdit_'+projectCommentId).show();
                    $('#btnSave_'+projectCommentId).hide();
                    $('#commentId').val('');
                },
                error: function (xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            });
            return false;
    }
    
    function changeCommentStatus(projectCommentId, projectId, userId, status) {
        var commentsUrl = $('#commentsUrl').val();
        
        $.ajax({
            url: commentsUrl,
            type: 'get',
            data: {'projectCommentId': projectCommentId, 'projectId': projectId, 'userId': userId, 'status': status},
            success: function (data) {
                //alert('Success--'+status);
                //if (data) {
                    if(status == '7')
                        var statusName = "Accepted";
                    else if(status == '8')
                        var statusName = "Rejected";
                    $('#commentStatus_'+projectCommentId).html('<div class="btn-primary" style="width: 100%;text-align: left;float: left;padding: 0px 5px"><b>'+statusName+'</b></div>');
                    $('.status_'+projectCommentId).hide();
                    return false;
                //}
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
    }
    
    function modifyComment(projectCommentId) {
        $('#comment_'+projectCommentId).prop('disabled', false);
        //$('.commentEdit_'+projectCommentId).removeAttr('onClick');
        $('#comment_'+projectCommentId).css('border', '1px solid black');
        $('#btnEdit_'+projectCommentId).hide();
        $('#btnSave_'+projectCommentId).show();
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
                    $('#divComment_'+projectCommentId).remove();
                    return false;
                //}
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
    }
    
    /* Load Facebook SDK for JavaScript */
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '1198675376821245',
        xfbml      : true,
        version    : 'v2.6'
      });
    };

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       //if (d.getElementById(id)) {return;}
       if (typeof(FB) != 'undefined'
        && FB != null ) { return; }
       js = d.createElement(s); js.id = id;
       js.src = siteUrl+"web/themes/custom/js/fsdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk')); 
    
    /* add dynamic data to fb dialogue feed*/
    function FBShareOp(){
            var product_name   = title;
            var description    =  desc;
            var share_image   =   img;
            var share_url    =    siteUrl+'/site/dynamic-new?id='+id; 
            var share_capt   =   objective;
            // alert(share_url);
        FB.ui({
            method: 'feed',
            name: product_name,
            link: share_url,
            picture: share_image,
            caption: share_capt,
            description: description

        }, function(response) {
            if(response && response.post_id){
                console.log(response);
            }
            else{
                console.log(response.post_id);
            }
        });

    }
    
	$(function()
	{
   var total_height=$(window).height();
  var header_top=$('.navbar-fixed-top').height();
  var total_header=header_top+80;
  var project_map1= total_height-total_header;
	$('.project-details >.project-detail-pane >.project-detail-black-pane').css('height',project_map1-9+'px');
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
	});
        
        
    /* Email sharing */
    function emailSharing(prj_id, cid, reference_url) {
        var reference_url = reference_url;
        var cid = cid;
        var project_id = prj_id;
        $.fancybox.close();
        $.fancybox({
            type: 'ajax',
            href: backendUrl + '/send-email/index?id=' + project_id + '&cat=' + cid,
            helpers : { 
                overlay: {
                    opacity: 0.8, // or the opacity you want 
                    css: {'z-index': 0} // or your preferred hex color value
                } // overlay 
            }
        });        
    }    
    
</script>