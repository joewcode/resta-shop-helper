//

function footerStickBottom (footer_block) {
    $(footer_block).css({'margin-top':0});
    var allWindowsHeight = $(window).height();
    var footerBottomOffset = $(footer_block).offset().top + $(footer_block).outerHeight();
    if (allWindowsHeight > footerBottomOffset) {
        var footerMarginTop = allWindowsHeight - footerBottomOffset;
        $(footer_block).css({'margin-top':footerMarginTop});
    }
}

function resizeAll() {

    footerStickBottom ('.footer');
    
}

function orderUpload(date, type) {
	location.href = '?mod=orders&act=download&today='+date+(type ? '&win=1' : '');
}

