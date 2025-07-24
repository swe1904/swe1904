/*Dashboard Init*/

"use strict";
/*****Load function start*****/


/*****Sparkline function start*****/
var sparklineLogin = function() {
    if( $('#sparkline_1').length > 0 ){
        $("#sparkline_1").sparkline([2,4,4,6,8,5,6,4,8,6,6], {
            type: 'bar',
            width: '100%',
            height: '180',
            barWidth: '8',
            barSpacing: '15',
            barColor: '#ed8739',
            highlightSpotColor: '#ed8739'
        });
    }
}
/*****Sparkline function end*****/

/*****Resize function start*****/
var sparkResize,echartResize;
$(window).on("resize", function () {
    /*Sparkline Resize*/
    clearTimeout(sparkResize);
    sparkResize = setTimeout(sparklineLogin, 200);

}).resize();
/*****Resize function end*****/

/*****Function Call start*****/
sparklineLogin();
/*****Function Call end*****/