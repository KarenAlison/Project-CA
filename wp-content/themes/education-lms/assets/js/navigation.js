/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	var container, button, page, close;

	container = document.getElementById( 'mobile-nav' );
	if ( ! container ) {
		return;
	}

	button = document.getElementById( 'mobile-open' );
    if ( ! button ) {
        return;
    }

	page = document.getElementById( 'page' );
    if ( ! page ) {
        return;
    }

    close = document.getElementById('mobile-close');

    button.onclick = function() {
        container.style.width = '300px';
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    };

    close.onclick = function() {
        container.style.width = '0';
        document.body.style.backgroundColor = "white";
    };


} )();
