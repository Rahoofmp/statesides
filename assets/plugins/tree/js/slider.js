fb.ready(function () {
	
var sliderWidth = Math.min( fb.getViewport( 'width' ) * 0.4, 330 ),
	hangover = 5,
	startOpen = true,  // height starts at 100%, width at 1px
//	startOpen = false,  // height starts at 1px, width at 5px
	closeIcon = '&nbsp;&#61702;',
	openIcon = '&nbsp;&#61703;',
	slider = fb.getInstance( 'slider' ),
	btn, icon;

if ( !slider ) {
    fb.start( 'starter.php', {
        instanceName: 'slider',
        boxLeft: 0,
		boxTop: 0, 
		width: startOpen ? 1 : hangover,
		height: startOpen ? '100%' : 1,
        autoFitSpace: 0,
        boxScroll: false,
        contentScroll: false,
        className: 'slider',
        doAnimations: false,
        modal: false,
        shadowType: 'hybrid',
		shadowSize: 6,
		shadowOpacity: 0.3,
        showOuterClose: false,
        enableKeyboardNav: false,
		loadPageOnClose: 'self',
        afterItemStart: init
    } );
}

function init ( instance ) {
	slider = instance;
	btn = document.createElement( 'a' );
	icon = document.createElement( 'i' );

	fb.addClass( icon, 'fbIcon' );
	icon.innerHTML = startOpen ? closeIcon : openIcon;
	btn.appendChild( icon );
	slider.fbMain.appendChild( btn );

    fb.setStyle( btn, {
        position: 'absolute',
        display: 'inline-block',
        color: '#333',
        backgroundColor: 'white',
        boxShadow: '4px 4px 4px rgba(0,0,0,0.3)',
		padding: '15px 0',
        cursor: 'pointer',
		right: -btn.offsetWidth+1,
		top: 50
    } );

    fb.addEvent( btn, 'mousedown', toggleSlider );

	startOpen ? openSlider() : closeSlider();
}

function toggleSlider ( e ) {
	fb.getLayout( slider.fbMain ).left < -50 ? openSlider() : closeSlider();
	fb.stopEvent( e );
}

function openSlider () {
	slider.resize( { left: 0, width: sliderWidth, height: '100%' }, function () {
		icon.innerHTML = closeIcon;
	}, 0.7 );
}

function closeSlider () {
	slider.resize( { left: hangover - sliderWidth, width: sliderWidth, height: '100%' }, function () {
		icon.innerHTML = openIcon;
	}, 0.7 );
}

});
