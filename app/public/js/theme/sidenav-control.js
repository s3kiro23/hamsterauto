$(function () {

    $('.navbar-vertical-toggle').on('click', onClick);
    $('#themeControlToggle').on('click', changeMode);
    $('#renew-btn').on('click', function () {
        location.reload()
    } );

    let sidenav = $('#navbarVerticalCollapse');
    sidenav.on('mouseover', mouseOver)
    sidenav.on('mouseout', mouseOut)
});

let html = $('html');

let onClick = function () {
    if (html.hasClass('navbar-vertical-collapsed')) {
        html.removeClass('navbar-vertical-collapsed');
    } else {
        html.addClass('navbar-vertical-collapsed');
    }
}

let mouseOver = function () {
    if (html.hasClass('navbar-vertical-collapsed') && !html.hasClass('navbar-vertical-collapsed-hover')){
        html.addClass('navbar-vertical-collapsed-hover');
    }
}

let mouseOut = function () {
    if (html.hasClass('navbar-vertical-collapsed') && html.hasClass('navbar-vertical-collapsed-hover')){
        html.removeClass('navbar-vertical-collapsed-hover');
    }
}

let changeMode = function () {
    if (html.hasClass('dark')) {
        html.removeClass('dark');
    } else {
        html.addClass('dark');
    }
}