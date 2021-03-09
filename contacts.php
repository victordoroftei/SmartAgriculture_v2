<!DOCTYPE html>
<html lang="en">
    <head>
        <title>About us</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Slicebox - 3D Image Slider with Fallback" />
        <meta name="keywords" content="jquery, css3, 3d, webkit, fallback, slider, css3, 3d transforms, slices, rotate, box, automatic" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />
    <link rel="shortcut icon" href="img/logoico.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/loading.css">
	<link rel="stylesheet" href="css/flower.css">
	<script type="text/javascript" src="js/modernizr.custom.46884.js"></script>
    <script src="js/pixi.min.js"></script>
    <script src="https://code.jquery.com/pep/0.4.3/pep.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
	</head>
	<body>
	<div style="z-index: -32180973213; width: 100vw; height: 100%;position: fixed; top:0;left:0;opacity: 0.2;">
    <div class="bg-effect" style="z-index: -999999!important;"></div>
</div>
	<div class="containerLoading">
        <div class="wrapperLoading">
            <div class="petal-wrapper">
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
            </div>
            <div class="core"></div>
            </div>
        </div>
            </div>
		<div class="m-4 w-100">
    <a href="index.php"><img src="img/logoico.png" alt="" class="buttonnav" style="width: 3rem;height:3rem;margin-top:3rem;"></a>
</div>
			<div class="wrapper appear" style="margin-top:10rem;">
				<ul id="sb-slider" class="sb-slider">
					<li>
						<div style="display: flex;">
						<img src="images/poster2.jpg" alt="image1" style="height:30rem; width: 23rem;"/>
						<p style="padding: 2rem; color: white; background: rgba(0,0,0,0.2);margin: 2rem;font-size: 1.5rem"> Responsible for the pitching and planning phases and making predictions using Artificial Intelligence techniques.
						</p>
						</div>
						<div class="sb-description">
							<h3 style="z-index: -1;">Cuzenco  Andrei - Robert</h3>
						</div>
					</li>
					<li>
					<div style="display: flex;">
						<img src="images/poster3.jpg" alt="image1" style="height:30rem; width: 22.5rem"/>
						<p style="padding: 2rem; color: white; background: rgba(0,0,0,0.2);margin: 2rem;font-size: 1.5rem">I am a friendly guy who is eager to tackle any set of tasks. Back-end developer, in charge of PHP development, webhosting and database management. 
						</p>
						</div>
						<div class="sb-description">
							<h3 style="z-index: -1;">Doroftei Victor</h3>
						</div>
					</li>
					<li>
					<div style="display: flex;">
						<img src="images/poster4.jpg" alt="image1" style="height:30rem; width: 23rem;"/>
						<p style="padding: 2rem; color: white; background: rgba(0,0,0,0.2);margin: 2rem;font-size: 1.5rem">
						UI/UX designer, business planner <br><br>Bootstrap ftw.
						</p>
						</div>
						<div class="sb-description">
							<h3 style="z-index: -1;">Volostiuc Eusebiu</h3>
						</div>
					</li>
					<li>
					<div style="display: flex;">
						<img src="images/poster1.jpg" alt="image1" style="height:30rem; width: 23rem;"/>
						<p style="padding: 2rem; color: white; background: rgba(0,0,0,0.2); margin: 2rem;font-size: 1.5rem">
						I am a talkative guy, eager to always learn new things, passionate about computer science and very friendly.
						<br>I love to put my whole creativity in the websites that I am creating.
						
						
						
						</p>
						</div>
						<div class="sb-description">
							<h3 style="z-index: -1;">Hostiuc Robert - Gabriel</h3>
						</div>
					</li>
				</ul>
				<div id="shadow" class="shadow"></div>
				<div id="nav-arrows" class="nav-arrows">
					<a href="#">Next</a>
					<a href="#">Previous</a>
				</div>
			</div>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.slicebox.js"></script>
		<script type="text/javascript">
			$(function() {

				var Page = (function() {

					var $navArrows = $( '#nav-arrows' ).hide(),
						$navDots = $( '#nav-dots' ).hide(),
						$nav = $navDots.children( 'span' ),
						$shadow = $( '#shadow' ).hide(),
						slicebox = $( '#sb-slider' ).slicebox( {
							onReady : function() {

								$navArrows.show();
								$navDots.show();
								$shadow.show();

							},
							onBeforeChange : function( pos ) {

								$nav.removeClass( 'nav-dot-current' );
								$nav.eq( pos ).addClass( 'nav-dot-current' );

							}
						} ),
						
						init = function() {

							initEvents();
							
						},
						initEvents = function() {

							// add navigation events
							$navArrows.children( ':first' ).on( 'click', function() {

								slicebox.next();
								return false;

							} );

							$navArrows.children( ':last' ).on( 'click', function() {
								
								slicebox.previous();
								return false;

							} );

							$nav.each( function( i ) {
							
								$( this ).on( 'click', function( event ) {
									
									var $dot = $( this );
									
									if( !slicebox.isActive() ) {

										$nav.removeClass( 'nav-dot-current' );
										$dot.addClass( 'nav-dot-current' );
									
									}
									
									slicebox.jump( i + 1 );
									return false;
								
								} );
								
							} );

						};

						return { init : init };

				})();

				Page.init();

			});
		</script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
	<script src="assets/js/theme.js"></script>
</body>
</html>	
