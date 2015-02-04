/**
 * setActiveMenu - Add the class "active" to the current element
 * @param menu
 *
 */
function setActiveMenu(menu) {
    $('li', '.sidebar .nav-sidebar').removeClass('active');
    $('#' + menu).addClass('active');
}