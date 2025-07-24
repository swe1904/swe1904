 
$( document ).ready(function() {
	$( "#scoop" ).scoopmenu({
		themelayout: 'vertical',
		verticalMenuplacement: 'left',		// value should be left/right
		verticalMenulayout: 'wide',   		// value should be wide/box/widebox
		MenuTrigger: 'hover',
		SubMenuTrigger: 'click',
		activeMenuClass: 'active',
		ThemeBackgroundPattern: 'pattern6',
		HeaderBackground: 'theme2' ,
		LHeaderBackground :'theme4',
		NavbarBackground: 'theme4',
		ActiveItemBackground: 'theme0',
		SubItemBackground: 'theme2',
		ActiveItemStyle: 'style0',
		ItemBorder: true,
		ItemBorderStyle: 'solid',
		SubItemBorder: true,
		DropDownIconStyle: 'style1', // Value should be style1,style2,style3
		FixedNavbarPosition: false,
		FixedHeaderPosition: false,
		collapseVerticalLeftHeader: true,
		VerticalSubMenuItemIconStyle: 'style6',  // value should be style1,style2,style3,style4,style5,style6
		VerticalNavigationView: 'view1',
		verticalMenueffect:{
			desktop : "shrink",
			tablet : "push",
			phone : "overlay",
		},
		defaultVerticalMenu: {
			desktop : "compact",	// value should be offcanvas/collapsed/expanded/compact/compact-acc/fullpage/ex-popover/sub-expanded
			tablet : "collapsed",		// value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
			phone : "offcanvas",		// value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
		},
		onToggleVerticalMenu : {
			desktop : "collapsed",		// value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
			tablet : "expanded",		// value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
			phone : "expanded",			// value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
		},

	});
// modified to disable content without style
    $(".scoop").show();
    <!-- Need to register form.js at end-->

        /*--to focus on already filled item--*/
        $("form :input").each(function(){
            $(this).focus();
        });

    /*    $("form :input").each(function(){
            $(this).focus(
                function () {
                    var parent = $(this).parent('div');
                    var inputError = parent.hasClass('has-error');
                    var arr = [];
                    if (inputError) {
                        var spans=parent.find('.fa-check');
                        $(this).before( '<span class="fa fa-2x fa-times"></span>' );
                        spans.replaceWith('<span class="fa fa-2x fa-times"></span>');
                        console.log('focuson err');
                    }
                    else {
                        var spans=parent.find('.fa-times');
                        $(this).before( '<span class="fa fa-2x fa-check"></span>' );
                        spans.replaceWith('<span class="fa fa-2x fa-check"></span>');
                        console.log('focuson');
                    }


                }

            );
        });*/
    $('form').on("afterValidateAttribute", function (form, attribute, data, hasError) {
        var id=attribute.id;
        var parent = $('#'+id).parent('div');
        if(data.length){
            console.log(parent);
            var spans=parent.find('.fa-check');
            //  $('#'+id).before( '<span class="fa fa-2x fa-times"></span>' );
            $('label[for='+id+']').after( '<span class="fa fa-2x fa-times"></span>' );
            spans.replaceWith('<span class="fa fa-2x fa-times"></span>');
        }else{
            var spans=parent.find('.fa-times');
            //$('#'+id).before( '<span class="fa fa-2x fa-check"></span>' );
            $('label[for='+id+']').after( '<span class="fa fa-2x fa-check"></span>' );
            spans.replaceWith('<span class="fa fa-2x fa-check"></span>');
        }
    });

    //---to add panel class background white to col-md-6
    var containerClass=$(".col-md-6.col-md-offset-3");
    containerClass.closest('.panel-body').removeClass('panel');
    containerClass.addClass('panel');

    $(".myselect").select2();

    $('textarea').removeClass('form-control');
    $('textarea').addClass('formInput');

    //  $('input').removeClass('fld-required');
    //   $('input').addClass('formInput');
  
});