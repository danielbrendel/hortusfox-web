/*
    install.js
*/

window.gotoSection = function(which) {
    let el = document.querySelector('.install-content');
    el.innerHTML = '<i class="fas fa-spinner fa-spin fa-lg"></i>';

    location.href = '/install/index.php?section=' + which;
};