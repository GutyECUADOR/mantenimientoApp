$(function() {

function getTheme() {
    $html = $('html');
    this_theme = localStorage.getItem("altair_theme");
    console.log(this_theme);
   
    $html
        .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g app_theme_h app_theme_i app_theme_dark')
        .addClass(this_theme);

    if(this_theme == '') {
        localStorage.removeItem('altair_theme');
        $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
    } else {
        localStorage.setItem("altair_theme", this_theme);
        if(this_theme == 'app_theme_dark') {
            $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.materialblack.min.css')
        } else {
            $('#kendoCSS').attr('href','bower_components/kendo-ui/styles/kendo.material.min.css');
        }
    }

};

getTheme();

});

