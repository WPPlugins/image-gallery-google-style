// fix for ie in case console.log left uncommented
if (!window.console) console = {log: function() {}};

// Paul Irish window debounce
(function(jQuery,sr){
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;
      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };
          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);
          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
})(jQuery,'smartresize');


// VARIABLES
// set up a single object namespace for the bigflannel thumbgrid app
var iggs = {};

// data
// window height
iggs.windowHeight;
// screen height
iggs.screenHeight;
// thumb container width
iggs.thumbGridContainerWidth;
// might amend this later based on screen height and rows wanted
iggs.thumbGridRowTargetHeight = 250;
iggs.thumbGridSpacing = 5;
// device pixel ratio for determining which images to load
iggs.devicePixelRatio = window.devicePixelRatio;
// the amount a title or caption overflows a thumbgrid panel
iggs.overflowHeight;
// the scrollTop position of the page after placing a panel
iggs.pageScrollTop;
// for debugging
iggs.finalRowNumber = new Array;

// status
// whether thumb grid panel open
iggs.thumbGridPanelStatus = false;
// click events added
iggs.clickEventStatus = false;

// objects
// array to contain thumb grids
iggs.thumbGrids;
// array to contain the thumbs
iggs.thumbGridThumbs;
// array to contain a row of thumbs
iggs.thumbRow;
// last clicked thumb in thumb grid
iggs.lastClickedThumb;
// parents of last clicked thumb
iggs.lastClickedThumbParents;

// counters
var h;
var i;
var j;


// INITIALIZE

jQuery(document).ready(function() {
	//console.log('document ready');
	iggs.thumbGridInit();
});


// FUNCTIONS

iggs.thumbGridInit = function() {
	//console.log('iggs.thumbGridInit');
	// set variables used throughout
	// could add in something here to do with retina
	iggs.screenHeight = screen.height;
	iggs.windowHeight = jQuery(window).height();
	// align thumbs in the grid
	iggs.thumbGridAlign();
	// add a debounce
	jQuery(window).smartresize(function() {
		iggs.windowResized();
	});
}

iggs.thumbGridAlign = function() {
	//console.log('iggs.thumbGridAlign');
	//console.log('jQuery(window).width() = ' + jQuery(window).width());
	iggs.thumbGrids = jQuery('.iggs-grid');
	//console.log('iggs.thumbGrids.length = ' + iggs.thumbGrids.length);
	for (h = 0; h < iggs.thumbGrids.length; h++) {
		//console.log('h = ' + h);
		// The calculation for width results in difference of about +-4 on all calculations
		// Subtracting 3 from the container width ensures no thumbs are pushed down a row
		iggs.thumbGridContainerWidth = jQuery(iggs.thumbGrids[h]).width() - 4;
		//console.log('iggs.thumbGridContainerWidth = ' + iggs.thumbGridContainerWidth);
		iggs.thumbGridThumbs = jQuery(iggs.thumbGrids[h]).find('.iggs-grid-thumb');
		//console.log('iggs.thumbGridThumbs.length = ' + iggs.thumbGridThumbs.length);
		// variables used to align thumbs
		var thumbsInRow = 0;
		var thumbWidth = 0;
		var thumbHeight = 0;
		var rowNumber = 0;
		var rowClass;
		var startThumb = 0;
		for (i = 0; i < iggs.thumbGridThumbs.length; i++) {
			// calculate total thumb width at target height
			thumbWidth = thumbWidth + ((iggs.thumbGridRowTargetHeight/jQuery(iggs.thumbGridThumbs[i]).data('iggsheight')) * jQuery(iggs.thumbGridThumbs[i]).data('iggswidth'));
			thumbsInRow = i - startThumb + 1;
			if (thumbWidth >  (iggs.thumbGridContainerWidth - (thumbsInRow * iggs.thumbGridSpacing))) {
				//console.log ('thumbsInRow = ' + thumbsInRow);
				//console.log ('iggs.thumbGridContainerWidth = ' + iggs.thumbGridContainerWidth);
				//console.log ('thumbWidth = ' + thumbWidth);			
				thumbHeight = iggs.thumbGridRowTargetHeight  * ((iggs.thumbGridContainerWidth - (thumbsInRow * iggs.thumbGridSpacing))/thumbWidth);
				//console.log ('actual height = ' + (iggs.thumbGridRowTargetHeight  * ((iggs.thumbGridContainerWidth - (thumbsInRow * iggs.thumbGridSpacing))/thumbWidth)));
				//console.log ('thumbHeight = ' + thumbHeight);	
				rowClass = 'iggs-grid-row-' + rowNumber;
				for (j=startThumb; j < i+1; j++) {
					jQuery(iggs.thumbGridThumbs[j]).addClass(rowClass);
				}
				iggs.thumbRow = jQuery(iggs.thumbGrids[h]).find('.'+rowClass);
				jQuery(iggs.thumbRow).css('height', thumbHeight).css('width', 'auto');
				rowNumber = rowNumber + 1;
				thumbWidth = 0;
				startThumb = i+1;
				iggs.finalRowNumber[h] = rowNumber;
			} else if (i == iggs.thumbGridThumbs.length - 1) {
				rowClass = 'iggs-grid-row-' + rowNumber;
				for (j=startThumb; j < i+1; j++) {
					jQuery(iggs.thumbGridThumbs[j]).addClass(rowClass);
				}
				if (thumbHeight == 0) {
					thumbHeight = iggs.thumbGridRowTargetHeight;
				}
				iggs.thumbRow = jQuery(iggs.thumbGrids[h]).find('.'+rowClass);
				jQuery(iggs.thumbRow).css('height', thumbHeight).css('width', 'auto');
				iggs.finalRowNumber[h] = rowNumber;
			}
		}
		if (!iggs.clickEventStatus) {
			iggs.thumbGridEvents(iggs.thumbGrids[h]);
		}
	}
	iggs.clickEventStatus = true;
	//console.log('jQuery(window).width() = ' + jQuery(window).width());
	// run some debug output
	//setTimeout(function() {
	//	iggs.thumbGridData();	
	//}, 1000);
}

iggs.thumbGridData = function() {
	console.log('iggs.thumbGridData');
	console.log('jQuery(window).width() = ' + jQuery(window).width());
	for (h = 0; h < iggs.thumbGrids.length; h++) {
		console.log('thumb grid = ' + h);
		iggs.thumbGridContainerWidth = jQuery(iggs.thumbGrids[h]).width();
		for (i = 0; i < iggs.finalRowNumber[h]; i++) {
			var rowClass = '';
			var thumbWidth = 0;
			rowClass = 'iggs-grid-row-' + i;
			iggs.thumbRow = jQuery(iggs.thumbGrids[h]).find('.'+rowClass);
			for (j=0; j < iggs.thumbRow.length; j++) {
				thumbWidth = thumbWidth + jQuery(iggs.thumbRow[j]).width();
				console.log('jQuery(iggs.thumbRow[' + j + ']).width() = ' + jQuery(iggs.thumbRow[j]).width());
			}
			thumbWidth = thumbWidth + (iggs.thumbRow.length * iggs.thumbGridSpacing);
			console.log ('------- row ' + i);
			console.log ('thumbsInRow = ' + iggs.thumbRow.length);
			console.log ('iggs.thumbGridContainerWidth = ' + iggs.thumbGridContainerWidth);
			console.log ('thumbWidth = ' + thumbWidth);
			if (thumbWidth > iggs.thumbGridContainerWidth) {
				console.log ('ROW TOO LONG');
			}
			console.log ('------- row [end] ' + i);
			thumbWidth = 0;
		}
	}
}

iggs.thumbGridEvents = function(thumbGrid) {
	//console.log('iggs.thumbGridEvents');
	var thumbLinks = jQuery(thumbGrid).find('a');
	// if not a phone, add an image, title, caption panel
	jQuery(thumbLinks).click(function(event) {
		//console.log('thumb clicked');
		iggs.lastClickedThumb = event.target;
		//console.log(iggs.lastClickedThumb);
		iggs.lastClickedThumbParents = jQuery(iggs.lastClickedThumb).parents();
		//console.log(iggs.lastClickedThumbParents);
		//console.log(iggs.lastClickedThumbParents[3]);
		if (!iggs.isSmall()) {
			event.preventDefault();
			iggs.thumbGridClick(event);
		}
	});
}

iggs.isSmall = function() {
	//console.log('iggs.isSmall');
	if (jQuery(window).width() < 480) {
		return true;
	} else {
		return false;
	}
}

iggs.thumbGridClick = function(event) {
	//console.log('iggs.thumbGridClick');
	event.preventDefault();
	iggs.lastClickedThumb = event.target;
	//console.log('iggs.lastClickedThumb');
	//console.log(iggs.lastClickedThumb);
	if (iggs.thumbGridPanelStatus) {
		// remove the existing panel if there is one
		jQuery('#iggs-thumb-grid-panel').remove();
		// add the new panel
		iggs.thumbGridAddPanel();
	} else {
		// add the new panel
		iggs.thumbGridAddPanel();
	}
}

iggs.thumbGridAddPanel = function() {
	//console.log('iggs.thumbGridAddPanel');
	iggs.thumbGridPanelStatus = true;
	var imageSRC = iggs.thumbGridPanelThumbSRC(iggs.lastClickedThumb);
	var imageAspect = iggs.thumbGridPanelThumbAspect(iggs.lastClickedThumb);
	// add panel, close, and container
	var addHTML = '<div id="iggs-thumb-grid-panel" class="iggs-group"><div id="iggs-controls"><i class="iggs-icon icon-cancel-circled"></i></div><div class="iggs-panel-container">';
	// add container content
	addHTML = addHTML + iggs.addThumGridPanelContainerContent(iggs.lastClickedThumb, imageAspect, imageSRC);
	// close elements
	addHTML = addHTML + '</div><!-- .iggs-panel-container --></div><!-- #iggs-thumb-grid-panel -->';
	iggs.getElementToAddThumbGridPanelAfter().after(addHTML);
	// register the close button click
	jQuery('#iggs-thumb-grid-panel .iggs-icon.icon-cancel-circled').click (function() {
		// remove the panel
		jQuery('#iggs-thumb-grid-panel').remove();
		jQuery("html, body").animate({scrollTop: iggs.pageScrollTop }, "fast");
		iggs.thumbGridPanelStatus = false;
	});
	iggs.setThumbGridPanelHeight();
	iggs.positionThumbGridPanel();
}

iggs.thumbGridPanelThumbSRC = function(element) {
	//console.log('iggs.thumbGridPanelThumbSRC');
	var screenPixelHeight = iggs.screenHeight*iggs.devicePixelRatio;
	var imageSRC;
	//console.log('iggs.screenHeight ' + iggs.screenHeight);
	//console.log('screenPixelHeight ' + screenPixelHeight);
	//console.log('image heights available ' + jQuery(element).data('iggssmallheight') + ' ' + jQuery(element).data('iggsmediumheight') + ' ' + jQuery(element).data('iggslargeheight')); 
	if (screenPixelHeight < jQuery(element).data('iggssmallheight')) {
		//console.log('small');
		imageSRC = jQuery(element).data('iggssmallsrc');
	} else if (screenPixelHeight < jQuery(element).data('iggsmediumheight')) {
		//console.log('medium');
		imageSRC = jQuery(element).data('iggsmediumsrc');
	} else {
		//console.log('large');
		imageSRC = jQuery(element).data('iggslargesrc');
	}
	//console.log('imageSRC ' + imageSRC);
	return imageSRC;
}

iggs.thumbGridPanelThumbAspect = function(element) {
	//console.log('iggs.thumbGridPanelThumbAspect');
	var imageAspect
	if (jQuery(element).data('iggswidth')/jQuery(element).data('iggsheight') > .99) {
		imageAspect = "horizontal";
	} else {
		imageAspect = "vertical";
	}
	//console.log('imageAspect ' + imageAspect);
	return imageAspect;
}

iggs.addThumGridPanelContainerContent = function(element, imageAspect, imageSRC) {
	//console.log('iggs.addThumGridPanelContainerContent');	
	var addHTML = '';
	if (jQuery(element).data('iggstitle').length > 0 || jQuery(element).data('iggscaption').length > 0) {
		// add left panel, image
		addHTML = '<div class="iggs-panel-left ' + imageAspect + '"><a href="' + jQuery(element).data('iggslink') +'"><img src="' + imageSRC + '" /></a></div>'
		// add right panel and title and caption
		addHTML = addHTML + '<div class="iggs-panel-right ' + imageAspect + '">';
		if (jQuery(element).data('iggstitle').length > 0) {
			addHTML = addHTML + '<h2><a href="' + jQuery(element).data('iggslink') +'">' + jQuery(element).data('iggstitle') + '</a></h2>';
		}
		if (jQuery(element).data('iggscaption').length > 0) {
			addHTML = addHTML + '<p>' + jQuery(element).data('iggscaption') + '</p>';
		}	
	} else {
		// add left panel, image full width
		addHTML = '<div class="iggs-panel-left full-width"><a href="' + jQuery(element).data('iggslink') +'"><img class="' + imageAspect + '" src="' + imageSRC + '" /></a></div>';
	}
	addHTML = addHTML + '</div><!-- .iggs-panel-right -->';
	return addHTML;
}

iggs.setThumbGridPanelHeight = function() {
	//console.log('iggs.setThumbGridPanelHeight');
	// set panel height
	var panelHeight = iggs.windowHeight;
	// make panel window height
	panelHeight = panelHeight * .66;;
	var imageHeight = panelHeight - jQuery('#iggs-controls').height() - 20;
	// jQuery('#iggs-thumb-grid-panel').css('height',panelHeight);
	jQuery('#iggs-thumb-grid-panel img').css('max-height',imageHeight).css('visibility','visible');
	// jQuery('#iggs-thumb-grid-panel img').css('visibility','visible');	
}

iggs.getElementToAddThumbGridPanelAfter = function() {
	//console.log('iggs.getElementToAddPanelAfter');
	var rowClass = jQuery(iggs.lastClickedThumb).attr('class').split(' ').pop();
	var rowData = rowClass.split('-');
	var rowNumber = Number(rowData[rowData.length-1]);
	var thumbs = jQuery(iggs.lastClickedThumbParents[3]).find('.iggs-grid-row-' + rowNumber);
	var thumbA = jQuery(thumbs[thumbs.length-1]).parent();
	var thumbLI = jQuery(thumbA).parent();
	return thumbLI;
}

iggs.positionThumbGridPanel = function() {
	//console.log('iggs.positionThumbGridPanel');
	var thumbGridPanelOffset;
	var thumbGridPanelPosition;
	thumbGridPanelOffset = jQuery('#iggs-thumb-grid-panel').offset();
	// set the panel to be about a thumbnail below window
	thumbGridPanelPosition = thumbGridPanelOffset.top - 100;
	iggs.pageScrollTop = jQuery(window).scrollTop();
	jQuery("html, body").animate({scrollTop: thumbGridPanelPosition}, "fast");
}

iggs.windowResized = function() {
	//console.log('iggs.windowResized');
	iggs.windowHeight = jQuery(window).height();
	iggs.thumbGridContainerWidth = jQuery('#iggs-grid').width();
	// remove classes from thumbs
	iggs.removeThumbGridPanelThumbClasses();
	// resize the thumb grid
	iggs.thumbGridAlign();
	// resize the thumb grid panel
	if (iggs.thumbGridPanelStatus) {	
		// adjust #iggs-thumb-grid-panel's position in the dom
		var detachedPanel = jQuery('#iggs-thumb-grid-panel');
		detachedPanel.detach();
		jQuery(iggs.getElementToAddThumbGridPanelAfter()).after(detachedPanel);
		// adjust panel height based on window height
		iggs.setThumbGridPanelHeight();
		// scroll to the right place
		iggs.positionThumbGridPanel();
	}
}

iggs.removeThumbGridPanelThumbClasses = function() {
	//console.log('iggs.removeThumbGridPanelThumbClasses');
	for (h = 0; h < iggs.thumbGrids.length; h++) {
		//console.log('h = ' + h);
		iggs.thumbGridThumbs = jQuery(iggs.thumbGrids[h]).find('.iggs-grid-thumb');
		for (i = 0; i < iggs.thumbGridThumbs.length; i++) {
			var classToRemove = jQuery(iggs.thumbGridThumbs[i]).attr('class').split(' ').pop();
			jQuery(iggs.thumbGridThumbs[i]).removeClass(classToRemove);
		}
	}
}



