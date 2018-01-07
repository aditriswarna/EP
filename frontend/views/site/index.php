
      
      <div id="myCarousel" class="carousel slide carousel-fade">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <div class="item active">
                <!-- Set the third background image using inline CSS below. -->
                <div class="fill" style="background-image:url('<?=Yii::getAlias('@web')?>/images/coming-soon/banner4.jpg');"></div>
                <div class="carousel-caption">
                    <h2>locate.</h2>
                </div>
            </div>
            <div class="item">
                <!-- Set the third background image using inline CSS below. -->
                <div class="fill" style="background-image:url('<?=Yii::getAlias('@web')?>/images/coming-soon/banner3.jpg');"></div>
                <div class="carousel-caption">
                    <h2>initiate.</h2>
                </div>
            </div>
            <div class="item">
                <!-- Set the second background image using inline CSS below. -->
                <div class="fill" style="background-image:url('<?=Yii::getAlias('@web')?>/images/coming-soon/banner2.jpg');"></div>
                <div class="carousel-caption">
                    <h2>MOTIVATE.</h2>
                </div>
            </div>
            <div class="item ">
                <!-- Set the first background image using inline CSS below. -->
                <div class="fill" style="background-image:url('<?=Yii::getAlias('@web')?>/images/coming-soon/banner1.jpg');"></div>
                <div class="carousel-caption">
                    <h2>participate.</h2>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>

    </div>
      
      <div class="home-content-section">
        <div class="empowering-sec container">
          <h1 class="wow fadeInDown" data-wow-duration="1s">TOGETHER TO BETTER SOCIETY</h1>
            <p class="wow fadeInUp" data-wow-duration="1s"><strong>EquiPPP</strong> is a collaborative platform that vitalizes crowd participation in <strong>Public-Private Projects</strong> and connects Corporates, Citizens, Domain Experts, NGOs with the Government to <strong class="itl">Initiate</strong> and <strong class="itl">Participate</strong> together in socially relevant projects.</p>
            <div class="text-center empowr-btns wow fadeInUp"  data-wow-duration="1.3s">
            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../search-projects" class="btn btn-primary">View Projects</a>
            <?php if(Yii::$app->user->id){ ?>
            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project" class="btn btn-default">Create Project</a>
            <?php } else {?>
            <a href="javascript:void(0)" class="start_project btn btn-default" data-toggle="modal" data-target="#w0" >Create Project</a>
            <?php } ?>
            </div>
          </div>
          
        <div class="how-it-work">
            <div class="container">
          
            <div class="howitwork-inner">
                <h1 class="wow fadeInDown" id="HowItWorks" data-wow-duration="1s">How it works</h1>
            <div class="h-img wow pulse wow fadeInRightBig"  data-wow-iteration="1" data-wow-duration="2s"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/how-it-work-flow.png" /></div>
            <div class="h-content">
                <ul>
                <li class="hit1 wow fadeInLeftBig" data-wow-delay="0.2s">
                    <h2>Step 1</h2>
                    <div class="h-list-c">
                        <strong>LOCATE</strong> - EquiPPP has a database of projects selected across sectors and geographies started by <span>Individuals</span> and <span>Organizations</span>
                    </div>
                    </li>
                    <li class="hit2 wow fadeInLeftBig" data-wow-delay="0.4s">
                    <h2>Step 2</h2>
                        <div class="h-list-c">
                            <strong>INITIATE</strong> - EquiPPP enables <span>Individuals</span> and <span>Organizations</span> to <i>Initiate</i> projects based on innovative solutions for socio-economic problems
                        </div>
                    </li>
                    <li class="hit3 wow fadeInLeftBig" data-wow-delay="0.6s">
                    <h2>Step 3</h2>
                        <div class="h-list-c">
                            <strong>MOTIVATE</strong> - EquiPPP intends to <i>Motivate</i> <span>Individuals</span> and <span>Organizations</span> through its network of projects to collaborate, <i>Initiate</i> and/or <i>Participate</i>
                        </div>
                    </li>
                    <li class="hit4 wow fadeInLeftBig" data-wow-delay="0.8s">
                    <h2>Step 4</h2>
                        <div class="h-list-c">
                            <strong>PARTICIPATE</strong> - The EquiPPP ecosystem through the collaborative work of <span>Individuals</span> and <span>Organizations</span> encourages participation in projects through expressions of interest on the platform
                        </div>
                    </li>
                </ul>
                </div>
            </div>
          </div> 
      </div>
        
          <div class="participants-sec clearfix">
            <div class="container participants-detailed">
                <div class="row">
                <div class="col-md-3 col-sm-6">
					<div class="projects-details">
						<h3>Projects</h3>
						<div class="count">252</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="projects-details">
						<h3>Participants</h3>
						<div class="count">46320</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="projects-details">
						<h3>States Covered</h3>
						<div class="count">21</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="projects-details">
						<h3>People Impacted</h3>
						<div class="count">155453</div>
					</div>
				</div>
				</div>
            </div>
        </div>
          
          
          <div class="ecosystem-sec">
            <h1 class="wow fadeInDown" id="Projects" data-wow-duration="1s" style="text-transform: uppercase">Projects in the equippp ecosystem</h1>
              <div class="ecosystem-inner">
                <div class="container eco-ind">
                    <div class="eco-list-main eco-left">
                    <ul class="eco-list wow fadeInLeftBig" data-wow-delay="1s">
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/agriculture-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">Agriculture</a>
                            </div>
                        </li>
                        
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/infrastructure-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">infrastructure</a>
                            </div>
                        </li>
                        
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/technology-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">technology</a>
                            </div>
                        </li>
                        </ul>
                    </div>
                    <div class="view-project-sec">
                    <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../search-projects" class="btn btn-primary">View Projects</a><br />
                    <?php if(Yii::$app->user->id){ ?>
                    <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project" class="btn btn-default">Create Project</a>
                    <?php } else {?>
                    <a href="javascript:void(0)" class="start_project btn btn-default" data-toggle="modal" data-target="#w0" >Create Project</a>
                    <?php } ?>
                    </div>
                    <div class="eco-list-main eco-right">
                    <ul class="eco-list wow fadeInRightBig" data-wow-delay="1s">
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/education-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">Education</a>
                            </div>
                        </li>
                        
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/community-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">community</a>
                            </div>
                        </li>
                        
                        <li>
                        <img src="<?=Yii::getAlias('@web')?>/images/coming-soon/healthcare-img.png" />
                            <div class="eco-fade">
                            <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">healthcare</a>
                            </div>
                        </li>
                        </ul>
                    </div>
                    
                  </div>
              </div>
          </div>
          
          <div class="newsletter-section">
		<div class="container">			
                    <div class="text-center wow fadeInUp" data-wow-duration="1s">subscribe to our newsletter 
                        <span class="input-section">
                            <form id="subscription" method="post" action="site/subscribe">
                                <input class=" " name="email" placeholder="Your email address" type="email" id="subscribe_email" required style="text-transform: none;">
                                <button type="subscribe" onclick="return checkErrors()" class="">subscribe</button>                            
                            </form>
                        </span>
                        <label for="subscribeform-email" id="subscribe_error" class="error" style="text-transform: none; display: none">Please fill out this field</label>                            
                    </div>			
		</div>
              
</div>
          
          
          <div class="footer-section">
          <div class="container">
              <ul class="footer-links wow fadeInUp animated"  data-wow-duration="1.9s">
<!--                <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">EquiPPP Blog</a></li>
                <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">Terms of Use</a></li>
                <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">EquiPPP Projects</a></li>
                <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">Privacy Policy</a></li>-->
                <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/contact-us">Contact Us</a></li>
              </ul>
              <?php /*?><div class="partners-section">
              <ul>
                  <li class="euqip-logo"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/equipp-partners-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li class="fst"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/itc-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/kalamandir-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/ind-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/greenk-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/sunpharma-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                  <li><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/telangana-logo.png" class="wow fadeInDown animated" data-wow-duration="2s" /></li>
                      
                  </ul>
              </div><?php */?>
              <div class="soclial-links"><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/fb-logo.png" /></a> <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/instagram-logo.png" /></a> <a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon"><img src="<?=Yii::getAlias('@web')?>/images/coming-soon/twit-logo.png" /></a></div>
              </div>
          </div>
          <!--<div class="copyright">&copy; Copyrights EquiPPP 2017</div>-->
          
          
          
      </div>      
      <script>
          $(document).ready(function(){
            $('.carousel').carousel({
                interval: 5000 //changes the speed
            })
                      
            $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 4000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
              
          });
          
          $(window).scroll(function(){
            if ($(window).scrollTop() >= 300) {
               $('header').addClass('fixed-header');
            }
            else {
               $('header').removeClass('fixed-header');
            }
        });
                  
                  $(window).load(function() {
            var wow = new WOW({
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 0,
                mobile: true,
                live: true
            });
            wow.init();
        });
          
      </script>
      
      
<!--Scripts-->

<script type="text/javascript">
    $(document).ready(function () {                      
        $('#subscription').submit(function(e){
            e.preventDefault();
            var $form = $( this ),
            url = $form.attr( 'action' ),
            email = $('#subscribe_email').val();
            $('#subscribe_error').css("display","none");
            
            $.ajax({
                url: url,
                type: 'post',
                data: {email: email},
                success: function (data) { 
                    jsonParsedObject = JSON.parse(data);
                    if(jsonParsedObject.msg == 'success'){ 
                        $('#subscribe_email').val('');
                        $('#subscribeModal').modal('hide');
                        //$.notify("You have successfully subscribed to our news letter", "success");
                        $('#subscribe_error').text("You have successfully subscribed to our news letter");
                        $('#subscribe_error').css("display","block");
                    }else if(jsonParsedObject.msg == 'failed'){
                        $('#subscribe_email').val('');
                        $('#subscribeModal').modal('hide');
                        //$.notify("An unknown error occurred while processing your request", "failed");
                        $('#subscribe_error').text("An unknown error occurred while processing your request");
                        $('#subscribe_error').css("display","block");
                    }else if(jsonParsedObject.msg == 'subscribed'){
                        $('#subscribe_email').val('');
                        $('#subscribe_error').text('You have already subscribed');
                        $('#subscribe_error').css("display","block");
                    }
                },
                error: function (xhr, status, error) {
                    // alert('There was an error with your request.' + xhr.responseText);
                }
            });
            
        });       
        
		$('.navbar-nav a').click(function() {
	var keyword = $(this).attr('href');
	var scrollTo = $(keyword);
	$('html, body').animate({
		scrollTop: scrollTo.offset().top - 100
	}, 'slow');
    });
});
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#subscribe_error').css("display","none");
        $('#subscribe_email').removeClass('error');  
       
        $('#subscribe').on('click', function(){
              $('#subscribe_error').css("display","none");
              $('#subscribe_email').val('');
        });
    });
    
    function checkErrors(){
        $('#subscribe_error').text("Invalid email");
        var email = $.trim($('#subscribe_email').val());
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = emailReg.test(email);
        
        if(email == ''){
           $('#subscribe_error').css("display","block");
           $('#subscribe_email').addClass('error');
           return false;
        }else if(!valid) {
            $('#subscribe_error').text("Invalid email");
            $('#subscribe_error').css("display","block");
            $('#subscribe_email').addClass('error');
            return false;
        } else {             
            return true;
        }
    }
</script>
<style>
.fixed-header .navbar.navbar-inverse{   -moz-transition: 0.5s;-ms-transition: 0.5s;-o-transition: 0.5s; -webkit-transition: 0.5s;}
.navbar.navbar-inverse{ -moz-transition: 0.5s;-ms-transition: 0.5s;-o-transition: 0.5s; -webkit-transition: 0.5s;}
.ecosystem-sec .ecosystem-inner .eco-list-main {width: 275px;}
</style>