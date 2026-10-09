jQuery(document).ready(function ($) {
    var jssor_baznas_options = {
        $AutoPlay: 1,
        $SlideDuration: 800,
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$
        },
        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$
        }
    };

    var jssor_baznas_slider = new $JssorSlider$("jssor_baznas", jssor_baznas_options);

    /*responsive code begin*/
    function ScaleSliderBaznas() {
        var refSize = jssor_baznas_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            refSize = Math.min(refSize, 2880);
            jssor_baznas_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSliderBaznas, 30);
        }
    }
    ScaleSliderBaznas();
    $(window).bind("load", ScaleSliderBaznas);
    $(window).bind("resize", ScaleSliderBaznas);
    $(window).bind("orientationchange", ScaleSliderBaznas);
    /*responsive code end*/
});
