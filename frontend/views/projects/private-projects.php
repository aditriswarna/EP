<div class="fill">
    <img src="<?= Yii::$app->request->BaseUrl . '/images/private-projects.jpg'; ?> " class="img-responsive" alt="Bank Projects">
</div>

<section class="bg-grey mar-tp5 pad-tb20">
    <div class="container">

        <div class="cbp-panel">
            <div id="filters-container" class="cbp-l-filters-buttonCenter col-md-12">

                <div data-filter=".all" class="cbp-filter-item-active cbp-filter-item pink-bg no-border size18 "> Private Projects
                    <div class="cbp-filter-counter"></div>
                </div>
            </div>
            <div id="grid-container" class="cbp cbp-l-grid-masonry-projects col-lg-12 col-md-12 cbp-caption-active cbp-caption-zoom cbp-ready cbp-cols-4" style="height: 435px;">
                <?php foreach ($privateprojimgs as $projimgs) {
                    ?>
                    <div class="cbp-item all ">
                        <div class="cbp-caption">
                            <div class="cbp-caption-defaultWrap">
                                <?php
                                //echo Yii::$app->basePath.'/web/uploads/project_images/'.$projimgs[0][$j]['project_id'].'/'. $projimgs[0][$j]['project_image'];
                                //.'/uploads/project_images/'.$projimgs[0][$j]['project_id'].'/'. $projimgs[0][$j]['project_image'];
                                //if(file_exists(Yii::$app->basePath.'/web/uploads/project_images/'.$projimgs[0][$j]['project_id'].'/'. $projimgs[0][$j]['project_image']))
                                //  $projectImageUrl = Yii::$app->request->baseUrl .'/uploads/project_images/'.$projimgs[0][$j]['project_id'].'/'. $projimgs[0][$j]['project_image'];

                                if ($projimgs['document_type'] == 'projectImage' && file_exists(Yii::getAlias('@upload') . '/frontend/web/uploads/project_images/' . $projimgs['project_id'] . '/' . $projimgs['document_name']))
                                    $projectImageUrl = Yii::$app->request->baseUrl . '/uploads/project_images/' . $projimgs['project_id'] . '/' . $projimgs['document_name'];
                                else
                                    $projectImageUrl = Yii::$app->request->baseUrl . '/uploads/project_images/no_project_image.jpg';
                                ?>
                                <img src="<?php echo $projectImageUrl; ?>" alt=""> </div>
                            <div class="cbp-caption-activeWrap">
                                <div class="c-masonry-border"></div>
                                <div class="cbp-l-caption-alignCenter">
                                    <div class="cbp-l-caption-body">
                                        <a href="#"  class="cbp-singlePage cbp-l-grid-masonry-projects-title prj-hd-color"><strong><?php echo $projimgs['project_title']; ?></strong></a>
                                        <div class="txt-white pad-lr15 text-left"><?php echo $projimgs['category_name']; ?></div>
                                        <div class="rating text-left pad-lr15">
                                        <!--<i class="flaticon-favorite txt-yellow size16"></i>
                                        <i class="flaticon-favorite txt-yellow size16"></i>
                                        <i class="flaticon-favorite txt-yellow size16"></i>
                                        <i class="flaticon-shape txt-white size16"></i>
                                        <i class="flaticon-shape txt-white size16"></i>-->
                                            <div class="pad-tb10">
                                                <div id="<?= $projimgs['project_id']; ?>">
                                                    <a href="#" data-url="../site/view?id=<?= base64_encode($projimgs['project_id']); ?>" class="view cbp-l-caption-buttonRight btn c-btn-square c-btn-border-1x c-btn-white c-btn-bold c-btn-uppercase" data-title="">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="prj-main-title"><?php echo $projimgs['project_title']; ?></div>
                    </div>

                <?php } ?>


            </div>


        </div>
        <p>&nbsp;</p>
        <div class="pull-right"><a class="btn btn-primary" href="#">See More</a></div>
    </div>


</section>
<script>
    $(function () {

        $('.view').fancybox({type: 'ajax', loop: false});

        $('.view').on('click', function (e)
        {
           e.preventDefault();
           var url=$(this).data(url);
           console.log(url);
           var c = $(this).attr('href',url);
            console.log(c);
            //e.stopPropagation();
            var id = $(this).parent().attr('id');
      
            $.ajax({
                url: getsite_url() + 'site/is-private',
                type: "post",
                data: 'id=' + id,
                success: function (html) {

                    if (html == 'request')
                    {
                        //e.startPropagation();
                        //  $('.view').fancybox({type:'ajax',loop: false});
                        //e.stopImmediatePropagation();
                    }
                    //e.stopImmediatePropagation();
                    // var link=$(this).attr('href');

                    //$('.view').removeClass('fancy_box');
                    //$("a[rel=fancybox]").fancybox({type:'ajax','height':'400','width':'500'});
                }
            });
        });
    });


    function getsite_url()
    {
        var base_url = window.location.host;
        var url = "http://" + base_url + "/equippp/frontend/web/";
        return url;

    }
</script>


<style>
    a.fancybox-nav.fancybox-prev, a.fancybox-nav.fancybox-next{display:none;}
    .fancybox-opened{width:700px !important;}
    .fancybox-inner{width: 100% !important;overflow-x: hidden !important;}
	
	  .fancybox-opened {
       left:0 !important; right:0 !important; margin:0 auto !important;
    }

	
</style>



