/*
	ClearBox JS by pyro
*/

var CB_version = '2.0';
var CB_Show = 1;

(function($, undefined){
	var _clearbox;

	var CB_ActThumbSrc, CB_IEShowBug = '',
	CB_AllThumbsWidth, CB_ResizeTimer, CB_IsAnimating, CB_ImgWidthOrig, CB_ImgHeightOrig, CB_ieRPBug = 0,
	CB_ie6RPBug = '',
	CB_ClearBox, CB_AnimX, CB_AnimY,

	CB_BodyMarginX,
	CB_BodyMarginY,

	FF_ScrollbarBug, CB_Links, CB_SlideBW = 0,
	CB_SSTimer, CB_SS = 'start',
	CB_ii = 0,
	CB_jj = 0,
	CB_Hide, CB_LoadingImg, CB_JumpX, CB_JumpY, CB_MarginL, CB_MarginT, CB_Content,

	CB_ImgWidth,
	CB_ImgHeight,

	CB_HideContent,

	CB_resize_ing,
	CB_scroll_ing,

	_old_document_event,

	CB_ImgRate, CB_Win, CB_Txt, CB_Img, CB_Prv, CB_Nxt, CB_ImgWidthOld, CB_ImgHeightOld, CB_ActImgId, CB_Gallery, CB_Count, CB_preImages, CB_Loaded, CB_Header, CB_Footer, CB_Left, CB_Right;

	var CB_PrePictures = new Array();

	if (typeof $.log == 'undefined') {
		$.extend({
	 		log : function(a){
				console.log(a);
			},
		});
	}

 	$.extend({
 		clearbox : {
 			defaults : {
 				CB_HideColor : '#000',
 				CB_HideOpacity : 75,
 				CB_OpacityStep : 25,

 				CB_WinBaseW : 120,
 				CB_WinBaseH : 110,
 				CB_WinPadd : 1,

 				CB_RoundPix : 12,

 				CB_Animation : 'double',

 				CB_Jump_X : 60,
				CB_Jump_Y : 60,

				CB_AnimTimeout : 5,

				CB_ImgBorder : 0,
				CB_ImgBorderColor : '#ccc',

				CB_Padd : 2,

				CB_ShowImgURL : true,
				CB_ImgNum : true,
				CB_ImgNumBracket : '[]',

				CB_SlShowTime : 3,

				CB_PadT : 10,

				CB_TextH : 40,
				CB_Font : 'arial',
				CB_FontSize : 12,
				CB_FontColor : '#656565',
				CB_FontWeigth : 'normal',

				CB_CheckDuplicates : false,

				CB_LoadingText : 'Loading...',

				CB_PicDir : 'clearbox/pic',

				CB_BodyMarginLeft : 0,
				CB_BodyMarginRight : 0,
				CB_BodyMarginTop : 0,
				CB_BodyMarginBottom : 0,

				CB_Preload : true,
				CB_TextNav : true,

				CB_NavTextPrv : 'PREV',
				CB_NavTextNxt : 'NEXT',
				CB_NavTextCls : 'CLOSE',

				CB_PictureStart : 'start.png',
				CB_PicturePause : 'pause.png',
				CB_PictureClose : 'close.png',
				CB_PictureLoading : 'loading.gif',

				debug : 0,
 			},
 			options : {

 			},
			init : function(options) {
				_clearbox.setup(options);

				if (!_old_document_event) {
					_old_document_event = {
						onkeyup : document.onkeyup,
						onkeydown : document.onkeydown,
						onkeypress : document.onkeypress,
					};

					document.onkeyup =
					document.onkeydown =
					document.onkeypress = null;
				}

				var _keyeven = function(event) {
					if (CB_ClearBox || CB_IsAnimating) {
						event.preventDefault();
						event.stopPropagation();

						return false;
					} else {
						return true;
					}
				};

				if (!jQuery.browser.msie) document.captureEvents(Event.MOUSEMOVE);
				$(document)
					.unbind('keypress.clearbox')
					.bind('keypress.clearbox', _clearbox.keyeven)
					.unbind('keyup.clearbox')
					.bind('keyup.clearbox', function (event){
						if (_keyeven(event) && _old_document_event.onkeyup) {
							_old_document_event.onkeyup(event);
						}
					})
					.unbind('keydown.clearbox')
					.bind('keydown.clearbox', function (event){
						if (_keyeven(event) && _old_document_event.onkeydown) {
							_old_document_event.onkeydown(event);
						}
					})
				;

				CB_Init();
			},
			setup : function (options) {
				_clearbox.options = $.extend(true, {}
					, _clearbox.defaults
					, options == undefined ? _clearbox.options : options
				);

				_clearbox.options.CB_PicDir += '/';
				/*
				CB_PrePictures[0] = new Image();
				CB_PrePictures[0].src = _clearbox.options.CB_PicDir + 'noprv.gif';
				CB_PrePictures[1] = new Image();
				CB_PrePictures[1].src = _clearbox.options.CB_PicDir + 'loading.gif';
				*/
				CB_PrePictures[0] = _image(_clearbox.options.CB_PicDir + 'noprv.gif');
				CB_PrePictures[1] = _image(_clearbox.options.CB_PicDir + 'loading.gif');

				_clearbox.options.CB_AnimTimeout = parseInt(_clearbox.options.CB_AnimTimeout);
				if (_clearbox.options.CB_AnimTimeout < 5) {
					_clearbox.options.CB_AnimTimeout = 5;
				}
				_clearbox.options.CB_BodyMarginLeft = parseInt(_clearbox.options.CB_BodyMarginLeft);
				if (_clearbox.options.CB_BodyMarginLeft < 0) {
					_clearbox.options.CB_BodyMarginLeft = 0;
				}
				_clearbox.options.CB_BodyMarginRight = parseInt(_clearbox.options.CB_BodyMarginRight);
				if (_clearbox.options.CB_BodyMarginRight < 0) {
					_clearbox.options.CB_BodyMarginRight = 0;
				}
				_clearbox.options.CB_BodyMarginTop = parseInt(_clearbox.options.CB_BodyMarginTop);
				if (_clearbox.options.CB_BodyMarginTop < 0) {
					_clearbox.options.CB_BodyMarginTop = 0;
				}
				_clearbox.options.CB_BodyMarginBottom = parseInt(_clearbox.options.CB_BodyMarginBottom);
				if (_clearbox.options.CB_BodyMarginBottom < 0) {
					_clearbox.options.CB_BodyMarginBottom = 0;
				}
				_clearbox.options.CB_HideOpacity = parseInt(_clearbox.options.CB_HideOpacity);
				if (_clearbox.options.CB_HideOpacity < 0 || _clearbox.options.CB_HideOpacity > 100) {
					_clearbox.options.CB_HideOpacity = 70;
				}
				_clearbox.options.CB_OpacityStep = parseInt(_clearbox.options.CB_OpacityStep);
				if (_clearbox.options.CB_OpacityStep < 1 || _clearbox.options.CB_OpacityStep > _clearbox.options.CB_HideOpacity) {
					_clearbox.options.CB_OpacityStep = 10;
				}
				_clearbox.options.CB_WinBaseW = parseInt(_clearbox.options.CB_WinBaseW);
				if (_clearbox.options.CB_WinBaseW < 25 || _clearbox.options.CB_WinBaseW > 1000) {
					_clearbox.options.CB_WinBaseW = 120;
				}
				_clearbox.options.CB_WinBaseH = parseInt(_clearbox.options.CB_WinBaseH);
				if (_clearbox.options.CB_WinBaseH < 50 || _clearbox.options.CB_WinBaseH > 1000) {
					_clearbox.options.CB_WinBaseH = 110;
				}
				_clearbox.options.CB_WinPadd = parseInt(_clearbox.options.CB_WinPadd);
				if (_clearbox.options.CB_WinPadd < 0) {
					_clearbox.options.CB_WinPadd = 5;
				}
				if (_clearbox.options.CB_Animation != false && _clearbox.options.CB_Animation != 'normal' && _clearbox.options.CB_Animation != 'double' && _clearbox.options.CB_Animation != 'warp') {
					_clearbox.options.CB_Animation = 'double';
				}
				_clearbox.options.CB_Jump_X = parseInt(_clearbox.options.CB_Jump_X);
				if (_clearbox.options.CB_Jump_X < 1 || _clearbox.options.CB_Jump_X > 99) {
					_clearbox.options.CB_Jump_X = 50;
				}
				_clearbox.options.CB_Jump_Y = parseInt(_clearbox.options.CB_Jump_Y);
				if (_clearbox.options.CB_Jump_Y < 1 || _clearbox.options.CB_Jump_Y > 99) {
					_clearbox.options.CB_Jump_Y = 50;
				}
				_clearbox.options.CB_ImgBorder = parseInt(_clearbox.options.CB_ImgBorder);
				if (_clearbox.options.CB_ImgBorder < 0) {
					_clearbox.options.CB_ImgBorder = 1;
				}
				_clearbox.options.CB_Padd = parseInt(_clearbox.options.CB_Padd);
				if (_clearbox.options.CB_Padd < 0) {
					_clearbox.options.CB_Padd = 2;
				}
				if (_clearbox.options.CB_ShowImgURL != true && _clearbox.options.CB_ShowImgURL != false) {
					_clearbox.options.CB_ShowImgURL = false;
				}
				_clearbox.options.CB_PadT = parseInt(_clearbox.options.CB_PadT);
				if (_clearbox.options.CB_PadT < 0) {
					_clearbox.options.CB_PadT = 10;
				}
				_clearbox.options.CB_RoundPix = parseInt(_clearbox.options.CB_RoundPix);
				if (_clearbox.options.CB_RoundPix < 0) {
					_clearbox.options.CB_RoundPix = 12;
				}
				_clearbox.options.CB_TextH = parseInt(_clearbox.options.CB_TextH);
				if (_clearbox.options.CB_TextH < 25) {
					_clearbox.options.CB_TextH = 40;
				}
				_clearbox.options.CB_FontSize = parseInt(_clearbox.options.CB_FontSize);
				if (_clearbox.options.CB_FontSize < 6) {
					_clearbox.options.CB_FontSize = 13;
				}
				if (_clearbox.options.CB_ImgNum != true && _clearbox.options.CB_ImgNum != false) {
					_clearbox.options.CB_ImgNum = true;
				}
				_clearbox.options.CB_SlShowTime = parseInt(_clearbox.options.CB_SlShowTime);
				if (_clearbox.options.CB_SlShowTime < 1) {
					_clearbox.options.CB_SlShowTime = 5;
				}
				_clearbox.options.CB_SlShowTime *= 1000;
				if (_clearbox.options.CB_CheckDuplicates != true && _clearbox.options.CB_CheckDuplicates != false) {
					_clearbox.options.CB_CheckDuplicates = false;
				}
				if (_clearbox.options.CB_Preload != true && _clearbox.options.CB_Preload != false) {
					_clearbox.options.CB_Preload = true;
				}

				CB_BodyMarginX = _clearbox.options.CB_BodyMarginLeft + _clearbox.options.CB_BodyMarginRight;
				CB_BodyMarginY = _clearbox.options.CB_BodyMarginTop + _clearbox.options.CB_BodyMarginBottom;

				CB_ImgWidth = _clearbox.options.CB_WinBaseW;
				CB_ImgHeight = _clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH;

				_clearbox.log(_clearbox.options);
			},
			log : function (s) {
				if (!_clearbox.options.debug) return;
				$.log(s);
			},
			keyeven : function(event){
				var b;

				if (event.keyCode) b = event.keyCode;
				else if (event.which) b = event.which;

				var c = String.fromCharCode(b);

				_clearbox.log([b, c, event]);

				var stop = 0;

				if (CB_ClearBox == true) {
					if (CB_ActImgId > 1 && (c == "%" || b == 37 || b == 52 || b == 38 || b == 33)) {
						if (CB_SSTimer) {
							_clearbox.CB_SlideShowJump();
						}
						_clearbox.CB_LoadImage(CB_ActImgId - 1);

						stop = true;
					}
					if (CB_ActImgId < CB_Gallery.length - 1 && (c == "'" || b == 39 || b == 54 || b == 40 || b == 34)) {
						if (CB_SSTimer) {
							_clearbox.CB_SlideShowJump();
						}
						_clearbox.CB_LoadImage(CB_ActImgId + 1);

						stop = true;
					}
					if ((c == " " || b == 32) && CB_IsAnimating == 0) {
						if (CB_Gallery.length < 3) {
							stop = true;
						}
						if (CB_SS == 'start') {
							_clearbox.CB_SSStart();
							stop = true;
						} else {
							_clearbox.CB_SSPause();
							stop = true;
						}
					}
					if (c == "" || b == 27) {
						_clearbox.CB_Close();
						stop = true;
					}
					if (b == 13) {
						stop = true;
					}
				} else {
					if (CB_IsAnimating == 1 && (c == " " || b == 32 || b == 13)) {
						stop = true;
					}
				}

				if (b == 38 || b == 40 || b == 33 || b == 34) {
					stop = 2;
				}

				if (stop) {
					event.preventDefault();
					if (stop > 1) event.stopPropagation();
				}
			},
			getScrollPosition : function () {
				this.DocScrX = 0;
				this.DocScrY = 0;
				/*
				if (typeof(window.pageYOffset) == 'number') {
					DocScrY = window.pageYOffset;
					DocScrX = window.pageXOffset
				} else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
					DocScrY = document.body.scrollTop;
					DocScrX = document.body.scrollLeft
				} else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
					DocScrY = document.documentElement.scrollTop;
					DocScrX = document.documentElement.scrollLeft
				}
				*/
				DocScrX = $(document).scrollLeft();
				DocScrY = $(document).scrollTop();

				_clearbox.log(['getScrollPosition', DocScrX, DocScrY]);

				return
			},
			getMouseXY : function (e) {
				if (CB_AllThumbsWidth > CB_ImgWidth) {
					tempX = e.pageX;

					if (tempX < 0) {
						tempX = 0
					}

					CB_Thm2.css('marginLeft',
						((BrSizeX - CB_ImgWidth) / 2 - tempX)
						/ (CB_ImgWidth / (CB_AllThumbsWidth - CB_ImgWidth))
					);

					_clearbox.log(['getMouseXY', tempX, e.pageX, CB_Thm2.css('marginLeft')]);
				}
			},
			CB_SlideShow : function() {
				if (CB_SlShowTimer > CB_jj) {
					CB_SSTimer = setTimeout(_clearbox.CB_SlideShow, 25);
					CB_jj += 25;
					CB_SlideBW += (CB_ImgWidth - 44) / (CB_SlShowTimer / 25);
					CB_SlideB.width(CB_SlideBW);
				} else {
					clearTimeout(CB_SSTimer);
					CB_SlideBW = 0;
					CB_SlideB.width(CB_SlideBW);
					if (CB_ActImgId == CB_Gallery.length - 1) {
						_clearbox.CB_LoadImage(1);
					} else {
						_clearbox.CB_LoadImage(CB_ActImgId + 1);
					}
					return;
				}
			},
			CB_SlideShowStop : function () {
				CB_SS = 'start';
				_clearbox.CB_SlideShowJump();
			},
			CB_SlideShowJump : function () {
				if (CB_SSTimer) {
					clearTimeout(CB_SSTimer);
				}
				CB_jj = 0;
				CB_SlideBW = 0;
				CB_SlideB.hide();
			},
			CB_SSStart : function () {
				CB_SlideS.hide();
				CB_SlideP.show();
				CB_SS = 'pause';
				CB_SlideB.show();
				_clearbox.CB_SlideShow();
			},
			CB_SSPause : function () {
				CB_SlideP.hide();
				CB_SlideS.show();
				_clearbox.CB_SlideShowStop();
			},
			getDocumentSize : function () {
				this.DocSizeX = 0;
				this.DocSizeY = 0;
				/*
				if (window.innerWidth && window.scrollMaxX) {
					DocSizeX = window.innerWidth + window.scrollMaxX;
					DocSizeY = window.innerHeight + window.scrollMaxY
				} else if (document.body.scrollWidth > document.body.offsetWidth) {
					DocSizeX = document.body.scrollWidth;
					DocSizeY = document.body.scrollHeight
				} else {
					DocSizeX = document.body.offsetWidth;
					DocSizeY = document.body.offsetHeight
				}
				if (jQuery.browser.msie || jQuery.browser.opera) {
					DocSizeX = document.body.scrollWidth;
					DocSizeY = document.body.scrollHeight
				}
				if (jQuery.browser.firefox || navigator.userAgent.indexOf("Netscape") != -1) {
					DocSizeX = BrSizeX + window.scrollMaxX;
					DocSizeY = BrSizeY + window.scrollMaxY
				}
				*/

				DocSizeX = $(document).width();
				DocSizeY = $(document).height();

				_clearbox.log(['DocSizeX', DocSizeX, $(document).width(), $(document).outerWidth()]);
				_clearbox.log(['DocSizeY', DocSizeY, $(document).height(), $(document).outerHeight()]);

				return;
			},
			getBrowserSize : function () {
				this.BrSizeX = 0;
				this.BrSizeY = 0;
				/*
				if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
					BrSizeX = document.documentElement.clientWidth;
					BrSizeY = document.documentElement.clientHeight
				} else if (typeof(window.innerWidth) == 'number') {
					BrSizeX = window.innerWidth;
					BrSizeY = window.innerHeight
				} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
					BrSizeX = document.body.clientWidth;
					BrSizeY = document.body.clientHeight;
					return
				}
				if (jQuery.browser.opera) {
					BrSizeX = document.documentElement.clientWidth;
					BrSizeY = document.body.clientHeight
				}
				if (document.compatMode != undefined) {
					if (document.compatMode.match('Back') && jQuery.browser.firefox) {
						BrSizeY = document.body.clientHeight
					}
				}
				*/

				BrSizeX = $(window).width();
				BrSizeY = $(window).height();

				_clearbox.log('getBrowserSize');
				_clearbox.log(['BrSizeX', BrSizeX, $(window).width()]);
				_clearbox.log(['BrSizeY', BrSizeY, $(window).height(), window.innerHeight, document.body.clientHeight]);

				return;
			},
			CB_SetAllPositions : function (a) {
				_clearbox.getBrowserSize();
				_clearbox.getDocumentSize();
				_clearbox.getScrollPosition();
				if (BrSizeY > DocSizeY) {
					DocSizeY = BrSizeY;
				}
				if ((navigator.userAgent.indexOf("Netscape") != -1 || jQuery.browser.firefox) && BrSizeX != DocSizeX) {
					FF_ScrollbarBug = window.scrollMaxY + window.innerHeight - DocSizeY;
				} else {
					FF_ScrollbarBug = 0;
				}
				_clearbox.CB_SetMargins(a);
				if (CB_BodyMarginX == 0) {
					CB_HideContent.width(DocSizeX < BrSizeX ? DocSizeX : BrSizeX);
				} else {
					CB_HideContent.width(DocSizeX + CB_BodyMarginX);
				}

				CB_HideContent.css({
					/*
					height : BrSizeY + DocScrY,
					visibility : 'visible'
					*/
				}).height(DocSizeY + CB_BodyMarginY).show();

				return;
			},
			CB_fix_center : function (w) {
				/*
				var _w = CB_ImgCont.offsetWidth || CB_ImgCont.width;
				*/
				var _w = CB_ImgCont.width();

				w = parseInt(w);

				CB_Img.css('marginLeft', (_w > w) ? Math.floor((_w - w) / 2) : 0);

				CB_MarginL = parseInt(DocScrX - (CB_Win.width() + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);

				CB_Win.css('marginLeft', CB_MarginL);

				/*
				_clearbox.log([
					'CB_fix_center',
					CB_ImgCont.offsetWidth,
					CB_ImgCont.width,
					CB_ImgCont.outerWidth(),
					CB_ImgCont.outerWidth(true),
					CB_ImgCont.width(),
				]);
				*/

				/*
				console.log(
					[
						CB_Img.style.marginLeft,
						CB_ImgCont.width,
						CB_ImgCont.offsetWidth,
						_w,
						w,
					]
				);
				*/
			},
			CB_FitToBrowser : function () {
				if (CB_ImgWidth > BrSizeX - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd))) {
					CB_ImgWidth = BrSizeX - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd));
					CB_ImgHeight = Math.round(CB_ImgWidth / CB_ImgRate);
				}
				if (CB_ImgHeight > BrSizeY - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)) - _clearbox.options.CB_TextH) {
					CB_ImgHeight = BrSizeY - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)) - _clearbox.options.CB_TextH;
					CB_ImgWidth = Math.round(CB_ImgRate * CB_ImgHeight);
				}

				_clearbox.log(['CB_FitToBrowser',
					CB_ImgWidth, CB_ImgHeight,
					BrSizeX, BrSizeY,
					BrSizeX - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)),
					BrSizeY - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)) - _clearbox.options.CB_TextH,
				]);

				return;
			},
			CB_WindowResizeX : function () {
				if (CB_ImgWidth == CB_ImgWidthOld) {
					if (CB_TimerX) {
						clearTimeout(CB_TimerX);
					}
					if (_clearbox.options.CB_Animation == 'normal') {
						CB_AnimX = true;
						_clearbox.CB_WindowResizeY();
					} else {
						CB_AnimX = true;
					}

					return;
				} else {
					if (CB_ImgWidth < CB_ImgWidthOld) {
						if (CB_ImgWidthOld < CB_ImgWidth + 100 && _clearbox.options.CB_Jump_X > 20) {
							CB_JumpX = 20;
						}
						if (CB_ImgWidthOld < CB_ImgWidth + 60 && _clearbox.options.CB_Jump_X > 10) {
							CB_JumpX = 10;
						}
						if (CB_ImgWidthOld < CB_ImgWidth + 30 && _clearbox.options.CB_Jump_X > 5) {
							CB_JumpX = 5;
						}
						if (CB_ImgWidthOld < CB_ImgWidth + 15 && _clearbox.options.CB_Jump_X > 2) {
							CB_JumpX = 2;
						}
						if (CB_ImgWidthOld < CB_ImgWidth + 4) {
							CB_JumpX = 1;
						}
						CB_ImgWidthOld -= CB_JumpX;
					} else {
						if (CB_ImgWidthOld > CB_ImgWidth - 100 && _clearbox.options.CB_Jump_X > 20) {
							CB_JumpX = 20;
						}
						if (CB_ImgWidthOld > CB_ImgWidth - 60 && _clearbox.options.CB_Jump_X > 10) {
							CB_JumpX = 10;
						}
						if (CB_ImgWidthOld > CB_ImgWidth - 30 && _clearbox.options.CB_Jump_X > 50) {
							CB_JumpX = 5;
						}
						if (CB_ImgWidthOld > CB_ImgWidth - 15 && _clearbox.options.CB_Jump_X > 2) {
							CB_JumpX = 2;
						}
						if (CB_ImgWidthOld > CB_ImgWidth - 4) {
							CB_JumpX = 1;
						}
						CB_ImgWidthOld += CB_JumpX;
					}
					CB_Img.width(CB_ImgWidthOld);
					CB_MarginL = parseInt(DocScrX - (CB_ImgWidthOld + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);
					CB_Win.css('marginLeft', CB_MarginL);

					_clearbox.CB_fix_center(CB_Img.width());

					CB_TimerX = setTimeout(_clearbox.CB_WindowResizeX, _clearbox.options.CB_AnimTimeout);

					_clearbox.log([
						'CB_WindowResizeX',

						CB_MarginL,
						CB_ImgWidth,
						DocScrX,

						DocSizeX,
						BrSizeX,

						CB_Win.width(),

					]);
				}
			},
			CB_WindowResizeY : function () {
				if (CB_ImgHeight == CB_ImgHeightOld) {
					if (CB_TimerY) {
						clearTimeout(CB_TimerY);
					}
					CB_AnimY = true;
					return;
				} else {
					if (CB_ImgHeight < CB_ImgHeightOld) {
						if (CB_ImgHeightOld < CB_ImgHeight + 100 && _clearbox.options.CB_Jump_Y > 20) {
							CB_JumpY = 20;
						}
						if (CB_ImgHeightOld < CB_ImgHeight + 60 && _clearbox.options.CB_Jump_Y > 10) {
							CB_JumpY = 10;
						}
						if (CB_ImgHeightOld < CB_ImgHeight + 30 && _clearbox.options.CB_Jump_Y > 5) {
							CB_JumpY = 5;
						}
						if (CB_ImgHeightOld < CB_ImgHeight + 15 && _clearbox.options.CB_Jump_Y > 2) {
							CB_JumpY = 2;
						}
						if (CB_ImgHeightOld < CB_ImgHeight + 4) {
							CB_JumpY = 1;
						}
						CB_ImgHeightOld -= CB_JumpY;
					} else {
						if (CB_ImgHeightOld > CB_ImgHeight - 100 && _clearbox.options.CB_Jump_Y > 20) {
							CB_JumpY = 20;
						}
						if (CB_ImgHeightOld > CB_ImgHeight - 60 && _clearbox.options.CB_Jump_Y > 10) {
							CB_JumpY = 10;
						}
						if (CB_ImgHeightOld > CB_ImgHeight - 30 && _clearbox.options.CB_Jump_Y > 5) {
							CB_JumpY = 5;
						}
						if (CB_ImgHeightOld > CB_ImgHeight - 15 && _clearbox.options.CB_Jump_Y > 2) {
							CB_JumpY = 2;
						}
						if (CB_ImgHeightOld > CB_ImgHeight - 4) {
							CB_JumpY = 1;
						}
						CB_ImgHeightOld += CB_JumpY;
					}
					CB_Img.height(CB_ImgHeightOld);
					CB_ImgCont.height(CB_ImgHeightOld + (2 * _clearbox.options.CB_ImgBorder));
					CB_MarginT = parseInt(DocScrY - (CB_ieRPBug + CB_ImgHeightOld + _clearbox.options.CB_TextH + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);
					CB_Win.css('marginTop', (CB_MarginT - (FF_ScrollbarBug / 2)));
					CB_TimerY = setTimeout(_clearbox.CB_WindowResizeY, _clearbox.options.CB_AnimTimeout);
				}
			},
			CB_SetMargins : function (a) {
				/*
				CB_MarginL = parseInt(DocScrX - (CB_ImgWidth + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);
				*/
				CB_MarginT = parseInt(DocScrY - (CB_ieRPBug + CB_ImgHeight + _clearbox.options.CB_TextH + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);

				CB_MarginL = parseInt(DocScrX - (CB_Win.width() + (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd))) / 2);

				if (a) {
					CB_Win.animate({
						marginTop : (CB_MarginT - (FF_ScrollbarBug / 2)),
						marginLeft : CB_MarginL,
					}, {
						duration : 100
					});
				} else {
					CB_Win.css({
						marginLeft : CB_MarginL,
						marginTop : (CB_MarginT - (FF_ScrollbarBug / 2)),
					});
				}

				_clearbox.log(['CB_SetMargins', CB_MarginL, (CB_MarginT - (FF_ScrollbarBug / 2))]);

				return;
			},
			CB_PrevNext : function () {
				if (CB_ActImgId > 1) {
					if (_clearbox.options.CB_Preload == true) {
						/*
						PreloadPrv = new Image();
						PreloadPrv.src = CB_Gallery[CB_ActImgId - 1][0];
						*/
						PreloadPrv = _image(CB_Gallery[CB_ActImgId - 1][0]);
					}
					if (_clearbox.options.CB_TextNav == true) {
						/*
						var a = CB_Txt.innerHTML;
						CB_Txt.innerHTML = '<a class="_clearbox.options.CB_TextNav" href="javascript:void(0)" onclick="if(CB_SSTimer){_clearbox.CB_SlideShowJump();}_clearbox.CB_LoadImage(' + (CB_ActImgId - 1) + ')" alt="&lt;">' + _clearbox.options.CB_NavTextPrv + '</a> ' + a
						*/
						$('<a>')
							.text(_clearbox.options.CB_NavTextPrv)
							.attr({
								'class' : 'CB_TextNav',
								'href' : 'javascript:void(0)',
								'alt' : '&lt;',
							})
							.unbind('click.clearbox')
							.bind('click.clearbox', function(){
								if (CB_SSTimer) {
									_clearbox.CB_SlideShowJump();
								}
								_clearbox.CB_LoadImage(CB_ActImgId - 1);
							})
							.prependTo(CB_Txt)
						;
					}
					CB_Prv
						.show()
						.unbind('click.clearbox')
						.bind('click.clearbox', function() {
							if (CB_SSTimer) {
								_clearbox.CB_SlideShowJump();
							}
							_clearbox.CB_LoadImage(CB_ActImgId - 1);
							return false;
					});
				}
				if (CB_ActImgId < CB_Gallery.length - 1) {
					if (_clearbox.options.CB_Preload == true) {
						/*
						PreloadNxt = new Image();
						PreloadNxt.src = CB_Gallery[CB_ActImgId + 1][0];
						*/
						PreloadNxt = _image(CB_Gallery[CB_ActImgId + 1][0]);
					}
					if (_clearbox.options.CB_TextNav == true) {
						/*
						CB_Txt.innerHTML += ' <a class="_clearbox.options.CB_TextNav" href="javascript:void(0)" onclick="if(CB_SSTimer){_clearbox.CB_SlideShowJump();}_clearbox.CB_LoadImage(' + (CB_ActImgId + 1) + ')" alt="&gt;">' + _clearbox.options.CB_NavTextNxt + '</a>'
						*/
						$('<a/>')
							.text(_clearbox.options.CB_NavTextNxt)
							.attr({
								'class' : 'CB_TextNav',
								'href' : 'javascript:void(0)',
								'alt' : '&gt;',
							})
							.unbind('click.clearbox')
							.bind('click.clearbox', function(){
								if (CB_SSTimer) {
									_clearbox.CB_SlideShowJump();
								}
								_clearbox.CB_LoadImage(CB_ActImgId + 1);
							})
							.appendTo(CB_Txt)
						;
					}
					CB_Nxt
						.show()
						.unbind('click.clearbox')
						.bind('click.clearbox', function() {
							if (CB_SSTimer) {
								_clearbox.CB_SlideShowJump();
							}
							_clearbox.CB_LoadImage(CB_ActImgId + 1);
							return false;
					});
				}
				return;
			},
			CB_Close : function () {

				$(window).unbind('resize.clearbox');
				$(document).unbind('scroll.clearbox');

				CB_ImgHd.css({
					width : 0,
					height : 0,
					visibility : 'hidden',
				});

				CB_ShTh.css('visibility', 'hidden');
				CB_ShEt.css('visibility', 'hidden');
				_clearbox.CB_SlideShowStop();
				CB_Txt.html('');
				CB_Img.attr('src', '').hide();
				CB_ImgWidth = _clearbox.options.CB_WinBaseW;
				CB_ImgHeight = _clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH;
				CB_ImgCont.height(CB_ImgHeight + (2 * _clearbox.options.CB_ImgBorder));

				CB_Win.hide();
				CB_HideContent.unbind('click.clearbox');

				CB_iFr
					.attr('src', '')
					.css({
						top : 0,
						left : 0,
					})
					.width(0)
					.height(0)
				;

				CB_ShowDocument();
				return;
			},
			resize : function() {
				if (CB_resize_ing) {
					CB_resize_ing = 0;

					setTimeout(_clearbox.resize, 5);
				} else {
					_clearbox.CB_LoadImage();
				}
			},
			scroll : function() {
				if (CB_scroll_ing) {
					CB_scroll_ing = 0;

					setTimeout(_clearbox.scroll, 3);
				} else {
					_clearbox.CB_SetAllPositions(1);
				}
			},
			CB_ShowImage : function () {

				// bluelovers
				$(window)
					.unbind('resize.clearbox')
					.bind('resize.clearbox', function() {

						CB_resize_ing = 1;

						setTimeout(_clearbox.resize, 5);

				});
				$(document)
					.unbind('scroll.clearbox')
					.bind('scroll.clearbox', function(){
						CB_scroll_ing = 1;
						setTimeout(_clearbox.scroll, 5);
				});
				// bluelovers

				CB_Cls
					.unbind('click.clearbox')
					.bind('click.clearbox', function() {
						_clearbox.CB_Close();
				});
				CB_SlideS
					.unbind('click.clearbox')
					.bind('click.clearbox', function() {
						_clearbox.CB_SSStart();
						return false;
				});
				CB_SlideP
					.unbind('click.clearbox')
					.bind('click.clearbox', function() {
						_clearbox.CB_SSPause();
						return false;
				});
				CB_PrvNxt.show();
				if (_clearbox.options.CB_Animation != 'warp') {
					CB_Txt.html('');
					CB_LoadingImg.hide();
					CB_Img
						.attr('src', CB_Gallery[CB_ActImgId][0])
						.css('visibility', 'visible')
						.fadeTo('fast' , 1)
					;
				}
				CB_Cls.show();
				CB_HideContent.bind('click.clearbox', function() {
					_clearbox.CB_Close();
					return false;
				});
				CB_Prv.height(CB_ImgHeight);
				CB_Nxt.height(CB_ImgHeight);

				var _txt = '';

				if (CB_Gallery[CB_ActImgId][1] && CB_Gallery[CB_ActImgId][1] != 'null' && CB_Gallery[CB_ActImgId][1] != null) {
					_txt = CB_Gallery[CB_ActImgId][1];
				} else {
					if (_clearbox.options.CB_ShowImgURL == true) {
						_txt = (CB_Gallery[CB_ActImgId][0].split('/'))[(CB_Gallery[CB_ActImgId][0].split('/').length) - 1];
					}
				}
				if (_clearbox.options.CB_ImgNum == true && CB_Gallery.length > 2) {
					_txt += ' ' + _clearbox.options.CB_ImgNumBracket.substring(0, 1) + CB_ActImgId + '/' + (CB_Gallery.length - 1) + _clearbox.options.CB_ImgNumBracket.substring(1, 2);
				}

				CB_Txt.html(_txt);

				_clearbox.CB_PrevNext();
				CB_Txt.css('visibility', 'visible');
				if (CB_Gallery.length > 0) {
					CB_ImgWidthOld = CB_ImgWidth;
					CB_ImgHeightOld = CB_ImgHeight;
				}
				if (CB_Gallery.length > 2) {
					if (CB_SS == 'pause') {
						CB_SlideP.show();
						CB_SlideB.show();
						_clearbox.CB_SlideShow();
					} else {
						CB_SlideS.show();
					}
				} else {
					CB_SS = 'start';
				}
				CB_ClearBox = true;
				CB_IsAnimating = 0;

				CB_ImgHd.css({
					width : CB_ImgWidth + 2,
					height : CB_ImgHeight + 2
				});

				if (CB_ImgWidth < CB_preImages.width || CB_ImgHeight < CB_preImages.height) {
					CB_ShEt.css('visibility', 'visible');
					CB_Et.width(CB_ImgWidth + 2);
				}
				if (CB_Gallery.length > 2) {
					CB_ShTh.css('visibility', 'visible');
					CB_Thm.width(CB_ImgWidth + 2);
					var a = '';
					var b = 5;
					var c = 0;
					CB_AllThumbsWidth = 0;
					for (i = 1; i < CB_Gallery.length; i++) {
						/*
						CB_preThumbs = new Image();
						CB_preThumbs.src = CB_Gallery[i][2];
						*/
						CB_preThumbs = _image(CB_Gallery[i][2]);

						c = Math.round(CB_preThumbs.width / CB_preThumbs.height * 50);
						if (c > 0) {} else {
							c = 50;
						}
						CB_AllThumbsWidth += c;
					}
					CB_AllThumbsWidth += (CB_Gallery.length - 2) * b;
					var d = 0;
					for (i = 1; i < CB_Gallery.length; i++) {
						/*
						CB_preThumbs = new Image();
						CB_preThumbs.src = CB_Gallery[i][2];
						*/
						CB_preThumbs = _image(CB_Gallery[i][2]);
						/*
						a += '<a href="javascript:void(0)" onclick="if(CB_SSTimer){_clearbox.CB_SlideShowJump();}_clearbox.CB_LoadImage(' + i + ')"><img style="border: 0; left: ' + d + 'px;" " src="' + CB_Gallery[i][2] + '" height="50" class="CB_ThumbsImg" /></a>';
						*/
						d += Math.round(CB_preThumbs.width / CB_preThumbs.height * 50) + b;

						$('<img/>')
							.attr({
								'src' : CB_Gallery[i][2],
								'class' : 'CB_ThumbsImg',
							})
							.css({
								border : 0,
								left : d,
							})
							.height(50)
							.appendTo($('<a>'))
							.parent()
							.attr('href', 'javascript:void(0)')
							.prop('data-idx', i)
							.attr('title', i)
							.unbind('click.clearbox')
							.bind('click.clearbox', function(){
								if (CB_SSTimer) {
									_clearbox.CB_SlideShowJump();
								}
								_clearbox.CB_LoadImage($(this).prop('data-idx'));
							})
							.appendTo(CB_Thm2)
						;
					}
					/*
					CB_Thm2.style.width = CB_AllThumbsWidth + 'px';
					CB_Thm2.innerHTML = a;
					CB_Thm2.style.marginLeft = (CB_ImgWidth - CB_AllThumbsWidth) / 2 + 'px'
					*/

					CB_Thm2.width(CB_AllThumbsWidth).css('marginLeft', (CB_ImgWidth - CB_AllThumbsWidth) / 2);
				}

				// bluelovers
				_clearbox.CB_fix_center(CB_Img.width());
				// bluelovers

				return true;
			},
			CB_AnimatePlease : function (a) {
				CB_JumpX = _clearbox.options.CB_Jump_X;
				CB_JumpY = _clearbox.options.CB_Jump_Y;
				CB_AnimX = false;
				CB_AnimY = false;
				CB_IsAnimating = 1;
				if (_clearbox.options.CB_Animation == 'double') {
					_clearbox.CB_WindowResizeX();
					_clearbox.CB_WindowResizeY();
				} else if (_clearbox.options.CB_Animation == 'warp') {
					if (!a) {
						CB_LoadingImg.hide();
						CB_Img.css('visibility', 'visible');
					}
					_clearbox.CB_WindowResizeX();
					_clearbox.CB_WindowResizeY();
				} else if (_clearbox.options.CB_Animation == false) {
					_clearbox.CB_SetMargins();
					CB_ImgCont.height(CB_ImgHeight + (2 * _clearbox.options.CB_ImgBorder));

					CB_Img.width(CB_ImgWidth).height(CB_ImgHeight);

					CB_AnimX = true;
					CB_AnimY = true;
				} else if (_clearbox.options.CB_Animation == 'normal') {
					_clearbox.CB_WindowResizeX();
				}
				if (a) {
					_clearbox.CB_CheckResize2();
				} else {
					_clearbox.CB_CheckResize();
				}
				return;
			},
			CB_CheckResize : function () {
				if (CB_AnimX == true && CB_AnimY == true) {
					if (CB_ResizeTimer) {
						clearTimeout(CB_ResizeTimer);
					}
					_clearbox.CB_ShowImage();
					return;
				} else {
					CB_ResizeTimer = setTimeout(_clearbox.CB_CheckResize, 5);
				}
			},
			CB_CheckResize2 : function () {
				if (CB_AnimX == true && CB_AnimY == true) {
					if (CB_ResizeTimer) {
						clearTimeout(CB_ResizeTimer);
					}
					CB_Gallery = '';
			//		CB_iFr.src = CB_Clicked[1];
			//		CB_iFr.src = CB_Clicked[1]+'&inclearbox=1';

					// bluelovers
					if (CB_Clicked[1].search(/inclearbox=true$/)<0) {
						if (CB_Clicked[1].search(/\?/)>0) CB_Clicked[1] += '&'; else CB_Clicked[1] += '?';
						CB_Clicked[1] += 'inclearbox=true';
					}
					// bluelovers

					/*
					CB_Img.css('visibility', 'visible');
					*/
					CB_Img.css('visibility', 'visible').fadeTo('fast', 1);
					CB_LoadingImg.hide();

					CB_iFr
						.attr('src', CB_Clicked[1])
						.css({
							top : _clearbox.options.CB_ImgBorder,
							left : _clearbox.options.CB_ImgBorder,
						})
						.width(CB_ImgWidth)
						.height(CB_ImgHeight)
					;

					if (CB_Clicked[2] && CB_Clicked[2] != 'null' && CB_Clicked[2] != null) {
						CB_Txt.html(CB_Clicked[2]);
					} else {
						CB_Txt.html(CB_Clicked[1]);
					}
					CB_Txt.append(_clearbox.options.CB_ImgNumBracket.substring(0, 1) + '<a class="_clearbox.options.CB_TextNav" href="javascript:void(0)" onclick="_clearbox.CB_Close();">' + _clearbox.options.CB_NavTextCls + '</a>' + _clearbox.options.CB_ImgNumBracket.substring(1, 2));
					CB_HideContent.bind('click.clearbox', function() {
						_clearbox.CB_Close();
						return false;
					});
					CB_ClearBox = true;
					CB_IsAnimating = 0;
					return;
				} else {
					CB_ResizeTimer = setTimeout(_clearbox.CB_CheckResize2, 5);
				}
			},
			CB_NewWindow : function () {
				CB_Img
					.width(_clearbox.options.CB_WinBaseW)
					.height(_clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH)
					.show()
					.css('visibility', 'hidden')
				;

				CB_Win.show();

				_clearbox.CB_LoadImage();
			},
			CB_ShowEtc : function () {
				CB_ImgHd.css('visibility', 'visible');
				CB_Et.show();
				return;
			},
			CB_HideEtc : function () {
				CB_ImgHd.css('visibility', 'hidden');
				CB_Et.hide();
				return;
			},
			CB_ShowThumbs : function () {
				CB_ImgHd.css('visibility', 'visible');
				CB_Thm.show();
				return;
			},
			CB_HideThumbs : function () {
				CB_ImgHd.css('visibility', 'hidden');
				CB_Thm.hide();
				return;
			},
			CB_GetImageSize : function () {
				// bluelovers
				CB_Img.css('marginLeft', 0);
				// bluelovers

				CB_ImgWidth = CB_preImages.width;
				CB_ImgHeight = CB_preImages.height;
				CB_ImgWidthOrig = CB_ImgWidth;
				CB_ImgHeightOrig = CB_ImgHeight;
				CB_ImgRate = CB_ImgWidth / CB_ImgHeight;
				_clearbox.CB_FitToBrowser();
				CB_Img.attr('src', CB_Gallery[CB_ActImgId][0]);
				_clearbox.CB_AnimatePlease();

				_clearbox.log([
					'CB_GetImageSize',
					CB_ActImgId,

					CB_ImgWidth,
					CB_Img.width(),

					CB_preImages.width,
				]);

				return;
			},
			CB_CheckLoaded : function () {
				if (CB_Count == 1) {
					CB_Loaded = true;
					clearTimeout(CB_ImgLoadTimer);
					_clearbox.CB_GetImageSize();
					return;
				}
				if (CB_Loaded == false && CB_preImages.complete) {
					CB_Count++;
				}
				CB_ImgLoadTimer = setTimeout(_clearbox.CB_CheckLoaded, 5);
				return;
			},
			CB_LoadImage : function (a) {

				// 增加 _clearbox.CB_SetAllPositions() 後可於每次切換圖片時同時更新尺寸
				_clearbox.CB_SetAllPositions();

				CB_ShTh.css('visibility', 'hidden');
				CB_ShEt.css('visibility', 'hidden');
				CB_Thm.hide()
					.width(0);
				CB_Et.hide()
					.width(0);

				CB_ImgHd.css({
					width : 0,
					height : 0,
					visibility : 'hidden'
				});

				CB_ClearBox = false;
				CB_jj = 0;
				CB_HideContent.unbind('click.clearbox');
				if (CB_Gallery.length < 3) {
					CB_SlideS.hide();
					CB_SlideP.hide();
				} else {
					if (CB_SS == 'start') {
						CB_SlideS.show();
						CB_SlideP.hide();
					} else {
						CB_SlideP.show();
						CB_SlideS.hide();
					}
				}
				CB_Prv.hide();
				CB_Nxt.hide();
				if (a) {
					CB_ActImgId = parseInt(a);
				}
				CB_JumpX = _clearbox.options.CB_Jump_X;
				CB_JumpY = _clearbox.options.CB_Jump_Y;
				if (_clearbox.options.CB_Animation != 'warp') {
					/*
					CB_Img.css('visibility', 'hidden');
					*/
					CB_Img.fadeTo('fast', 0.25);
					CB_LoadingImg.show();
				}
				CB_Txt.html(_clearbox.options.CB_LoadingText);
				CB_Count = 0;
				/*
				CB_preImages = new Image();
				CB_preImages.src = CB_Gallery[CB_ActImgId][0];
				*/
				CB_preImages = _image(CB_Gallery[CB_ActImgId][0]);

				CB_Loaded = false;
				CB_preImages.onerror = function() {
					_clearbox.CB_ShowImage();
					alert('ClearBox HIBA:\n\nA kepet nem lehet betolteni: ' + CB_Gallery[CB_ActImgId][0]);
					return;
				};
				_clearbox.CB_CheckLoaded();
			},
			CB_ClickURL : function (a) {
				if (CB_Show == 0) {
					return false;
				}
				CB_ClearBox = false;

				/*
				CB_Clicked = a.split('+\\+');
				*/
				CB_Clicked = arguments;

				_clearbox.log(['CB_ClickURL', CB_Clicked]);

				CB_PrvNxt.hide();
				CB_Cls.hide();
				CB_Rel = CB_Clicked[0].split(',');
				_clearbox.CB_SetAllPositions();
				CB_ImgWidth = parseInt(CB_Rel[0]);
				CB_ImgHeight = parseInt(CB_Rel[1]);
				CB_ImgWidthOld = _clearbox.options.CB_WinBaseW;
				CB_ImgHeightOld = _clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH;
				if (CB_ImgWidth > BrSizeX - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd))) {
					CB_ImgWidth = BrSizeX - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd));
				}
				if (CB_ImgHeight > BrSizeY - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)) - _clearbox.options.CB_TextH) {
					CB_ImgHeight = BrSizeY - (2 * (_clearbox.options.CB_RoundPix + _clearbox.options.CB_ImgBorder + _clearbox.options.CB_Padd + _clearbox.options.CB_WinPadd)) - _clearbox.options.CB_TextH;
				}

				CB_Img
					.width(_clearbox.options.CB_WinBaseW)
					.height(_clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH)
					.show()
					.css('visibility', 'hidden')
				;

				CB_Win.show();
				CB_SlideS.hide();
				CB_SlideP.hide();
				CB_HideDocument('x');
			},
			CB_ClickIMG : function (a) {
				if (CB_Show == 0) {
					return false;
				}
				CB_Cls.unbind('click.clearbox');
				CB_SlideS.unbind('click.clearbox');
				CB_SlideP.unbind('click.clearbox');

				/*
				CB_Clicked = a.split('+\\+');
				*/
				CB_Clicked = arguments;

				_clearbox.log(['CB_ClickIMG', CB_Clicked]);

				CB_Rel = CB_Clicked[0].split(',');
				if (CB_Rel[1] > 0) {
					CB_SlShowTimer = parseInt(CB_Rel[1]) * 1000;
				} else {
					CB_SlShowTimer = _clearbox.options.CB_SlShowTime;
				}
				if (CB_Rel[2] == 'start') {
					CB_SS = 'pause';
				}
				if (CB_Gallery && CB_Rel[0] == CB_Gallery[0][0] && CB_Gallery[0][0] != 'clearbox') {} else {
					CB_Gallery = new Array;
					CB_Gallery.push(new Array(CB_Rel[0], CB_Rel[1], CB_Rel[2]));

					_clearbox.log(['CB_Gallery.push', CB_Rel[0], CB_Rel[1], CB_Rel[2]]);

					if (CB_Clicked[0] == 'clearbox') {
						CB_Gallery.push(new Array(CB_Clicked[1], CB_Clicked[2]));
					} else {
						for (i = 0; i < CB_Links.length; i++) {

							var _link = $(CB_Links[i]);

							if (_link.attr('rel').substring(9, _link.attr('rel').length - 1).split(',')[0] == CB_Gallery[0][0]) {
								CB_ActThumbSrc = _clearbox.options.CB_PicDir + 'noprv.gif';
								if (_link.attr('tnhref') == null || _link.attr('tnhref') == 'null') {
									/*
									for (j = 0; j < CB_Links[i].childNodes.length; j++) {
										if (CB_Links[i].childNodes[j].src != undefined) {
											CB_ActThumbSrc = CB_Links[i].childNodes[j].getAttribute('src');
										}
									}
									*/
									_link.children('*[src]').each(function(idx, elem){
										if ($(elem).attr('src')) {
											CB_ActThumbSrc = $(elem).attr('src');
										}
									});
								} else {
									CB_ActThumbSrc = _link.attr('tnhref');
								}

								// bluelovers
								CB_ActThumbSrc = CB_ActThumbSrc || _link.attr('href');
								// bluelovers

								CB_Gallery.push(new Array(_link.attr('href'), _link.attr('title'), CB_ActThumbSrc))
							}
						}
					}
				}
				CB_ActImgId = 0;
				while (CB_Gallery[CB_ActImgId][0] != CB_Clicked[1]) {
					CB_ActImgId++
				}
				CB_ImgWidthOld = _clearbox.options.CB_WinBaseW;
				CB_ImgHeightOld = _clearbox.options.CB_WinBaseH - _clearbox.options.CB_TextH;
				_clearbox.CB_SetAllPositions();
				CB_HideDocument();
			},
			CB_FullSize : function () {
				CB_Img.width(CB_ImgWidthOrig).height(CB_ImgHeightOrig);

				CB_ImgCont.height(CB_ImgHeightOrig + (2 * _clearbox.options.CB_ImgBorder));
			},
			alert : function (msg) {
				alert(msg);
			},
 		},
 	});

 	function CB_Init() {
		if (!$('#CB_All').size() && CB_Show != 0) {
			$('body').css('position', 'static');

			var a = '<div class="CB_RoundPixBugFix" style="width: ' + _clearbox.options.CB_RoundPix + 'px; height: ' + _clearbox.options.CB_RoundPix + 'px;"></div>';
			if (jQuery.browser.msie) {
				CB_IEShowBug = '<img id="CB_ShowEtc" alt="" src="' + _clearbox.options.CB_PicDir + 'blank.gif" /><img id="CB_ShowTh" alt="" src="' + _clearbox.options.CB_PicDir + 'blank.gif" />';
			} else {
				CB_IEShowBug = '<div id="CB_ShowTh"></div><div id="CB_ShowEtc"></div>';
			}

			var CB_All = $("<div>")
				.attr('id', 'CB_All')
				.html('<table cellspacing="0" cellpadding="0" id="CB_Window"><tr id="CB_Header"><td id="CB_TopLeft">' + a + '</td><td id="CB_Top"></td><td id="CB_TopRight">' + a + '</td></tr><tr id="CB_Body"><td id="CB_Left"></td><td id="CB_Content" valign="top" align="left"><div id="CB_Padding"><div id="CB_ImgContainer"><iframe frameborder="0" id="CB_iFrame" src=""></iframe>' + CB_IEShowBug + '<div id="CB_Etc"><img src="' + _clearbox.options.CB_PicDir + 'max.gif" alt="maximize" /></div><div id="CB_Thumbs"><div id="CB_Thumbs2"></div></div><img id="CB_LoadingImage" alt="loading" src="' + _clearbox.options.CB_PicDir + _clearbox.options.CB_PictureLoading + '" /><img id="CB_Image" alt="" src="' + _clearbox.options.CB_PicDir + 'blank.gif" /><div id="CB_PrevNext"><div id="CB_ImgHide"></div><img id="CB_CloseWindow" alt="x" src="' + _clearbox.options.CB_PicDir + _clearbox.options.CB_PictureClose + '" /><img id="CB_SlideShowBar" src="' + _clearbox.options.CB_PicDir + 'white.gif" /><img id="CB_SlideShowP" alt="Pause SlideShow" src="' + _clearbox.options.CB_PicDir + _clearbox.options.CB_PicturePause + '" /><img id="CB_SlideShowS" alt="Start SlideShow" src="' + _clearbox.options.CB_PicDir + _clearbox.options.CB_PictureStart + '" /><a id="CB_Prev" href="javascript:void(0)"></a><a id="CB_Next" href="javascript:void(0)"></a></div></div><div id="CB_Text"></div></div></td><td id="CB_Right"></td></tr><tr id="CB_Footer"><td id="CB_BtmLeft">' + a + '</td><td id="CB_Btm"></td><td id="CB_BtmRight">' + a + '</td></tr></table>')
			;

			if (navigator.userAgent.indexOf("MSIE 6") != -1 && _clearbox.options.CB_RoundPix == 0) {
				CB_ie6RPBug = 1;
			}
			if (jQuery.browser.msie && _clearbox.options.CB_RoundPix < 2) {
				CB_ieRPBug = 6;
			}
			$('#CB_Padding', CB_All).css('padding', _clearbox.options.CB_Padd);
			CB_ShTh = $('#CB_ShowTh', CB_All);
			CB_ShEt = $('#CB_ShowEtc', CB_All);

			CB_ImgHd = $('#CB_ImgHide', CB_All)
				.css({
					'backgroundColor' : '#fff',
					'opacity' : 0.75
			});

			CB_Win = $('#CB_Window', CB_All).hide();
			CB_Thm = $('#CB_Thumbs', CB_All);
			CB_Thm2 = $('#CB_Thumbs2', CB_All);

			var _img = $('<a/>').css({
				'background': 'url("' + _clearbox.options.CB_PicDir + 'imgzoom_tb.gif") no-repeat scroll 0 0 transparent',
				'height': 17,
				'overflow': 'hidden',
				'width': 17,
				'position' : 'relative',
				'display' : 'block',
				'left' : '50%',
				'top' : '30%',
				'background-position' : '0 -39px',
			}).html('&nbsp;');

			CB_Et = $('#CB_Etc', CB_All)
				.click(function(){
					window.open(CB_Gallery[CB_ActImgId][0], '_blank');
				})
				.css('cursor', 'pointer')
				.html(_img)
				.attr('title', 'Open New Window')
			;

			CB_HideContent = $('<div id="CB_ContentHide"></div>')
				.hide()
				.css({
					'backgroundColor' : _clearbox.options.CB_HideColor,
					'opacity' : 0,
				})
				.appendTo(CB_All)
			;

			CB_Img = $('#CB_Image', CB_All)
				.css('border', _clearbox.options.CB_ImgBorder + 'px solid ' + _clearbox.options.CB_ImgBorderColor);

			CB_LoadingImg = $('#CB_LoadingImage', CB_All).hide();
			CB_ImgCont = $('#CB_ImgContainer', CB_All);

			CB_Cls = $('#CB_CloseWindow', CB_All);
			CB_SlideS = $('#CB_SlideShowS', CB_All);
			CB_SlideP = $('#CB_SlideShowP', CB_All);
			CB_SlideB = $('#CB_SlideShowBar', CB_All)
				.css('opacity', 0.5);

			CB_Prv = $('#CB_Prev', CB_All)
				.css('cursor', 'url("' + _clearbox.options.CB_PicDir + 'bg11.cur"), pointer')
			;
			CB_Nxt = $('#CB_Next', CB_All)
				.css('cursor', 'url("' + _clearbox.options.CB_PicDir + 'bg12.cur"), pointer')
			;
			CB_Txt = $('#CB_Text', CB_All)
				.css({
					marginTop  : _clearbox.options.CB_PadT,
					fontFamily : _clearbox.options.CB_Font,
					fontSize : _clearbox.options.CB_FontSize,
					fontWeight : _clearbox.options.CB_FontWeigth,
					color : _clearbox.options.CB_FontColor,
				})
				.height(_clearbox.options.CB_TextH - _clearbox.options.CB_PadT)
			;

			/*
			CB_Header = document.getElementById('CB_Header').style;
			CB_Header.height = _clearbox.options.CB_RoundPix + 'px';
			CB_Footer = document.getElementById('CB_Footer').style;
			CB_Footer.height = _clearbox.options.CB_RoundPix + 'px';
			CB_Left = document.getElementById('CB_Left').style;
			CB_Left.width = _clearbox.options.CB_RoundPix + CB_ie6RPBug + 'px';
			CB_Right = document.getElementById('CB_Right').style;
			CB_Right.width = _clearbox.options.CB_RoundPix + 'px';
			*/

			$('#CB_Header', CB_All).height(_clearbox.options.CB_RoundPix);
			$('#CB_Footer', CB_All).height(_clearbox.options.CB_RoundPix);

			$('#CB_Left', CB_All).width(_clearbox.options.CB_RoundPix + CB_ie6RPBug);
			$('#CB_Right', CB_All).width(_clearbox.options.CB_RoundPix);

			CB_iFr = $('#CB_iFrame', CB_All);
			CB_PrvNxt = $('#CB_PrevNext', CB_All);

			CB_ShTh.mouseover(function() {
				_clearbox.CB_ShowThumbs();
				return;
			});
			CB_ShEt.mouseover(function() {
				_clearbox.CB_ShowEtc();
				return;
			});
			CB_ImgHd.mouseover(function() {
				_clearbox.CB_HideThumbs();
				_clearbox.CB_HideEtc();
				return;
			});
			CB_Txt.mouseover(function() {
				_clearbox.CB_HideThumbs();
				_clearbox.CB_HideEtc();
				return;
			});
			CB_HideContent.mouseover(function() {
				_clearbox.CB_HideThumbs();
				_clearbox.CB_HideEtc();
				return;
			});
			if (jQuery.browser.opera) {
				CB_BodyMarginX = 0;
				CB_BodyMarginY = 0;
			}
			if (jQuery.browser.firefox) {
				CB_BodyMarginY = 0;
			}

			CB_All.appendTo($("body"));
		}
		$('#CB_Thumbs').mousemove(_clearbox.getMouseXY);
		var d = 0;
		var e = 0;

		CB_Links = [];
		$('a[rel^="clearbox"]').each(function(index, elem){
			CB_Links[index] = elem;
		});

		for (i = 0; i < CB_Links.length; i++) {

			var _link = $(CB_Links[i]);

			CB_Rel = _link.attr('rel');
			CB_URL = _link.attr('href');

			_link
				.unbind('click.clearbox')
				.unbind('mouseover.clearbox')
			;

			if (CB_Rel.match('clearbox') != null && CB_Show != 0) {
				if (CB_Rel == 'clearbox') {

					_link.prop('data-rel-key', CB_Rel)
						.prop('data-rel-func', 1)
						.bind('click.clearbox', _link_rel_func);
				} else {
					_link.prop('data-rel-key', _link.attr('rel').substring(9, _link.attr('rel').length - 1));

					if (CB_Rel.substring(0, 8) == 'clearbox' && CB_Rel.charAt(8) == '[' && CB_Rel.charAt(CB_Rel.length - 1) == ']') {
						if (CB_Rel.substring(9, CB_Rel.length - 1).split(',')[0] != 'clearbox') {
							_link
								.prop('data-rel-func', 1)
								.bind('click.clearbox', _link_rel_func);
						} else {
							_clearbox.alert('ClearBox HIBA:\n\nClearBox galeria neve NEM lehet "clearbox[clearbox]"!\n(Helye: dokumentum, a ' + i + '. <a> tag-en belul.)');
						}
					} else if (CB_Rel.substring(0, 8) == 'clearbox' && CB_Rel.charAt(8) == '(' && CB_Rel.charAt(CB_Rel.length - 1) == ')') {

						_link.prop('data-rel-func', 0);

						if (CB_Rel.substring(9, CB_Rel.length - 1).split(',')[2] == 'click') {
							_link.bind('click.clearbox', _link_rel_func);
						} else {
							_link.bind('mouseover.clearbox', _link_rel_func);
						}
					} else {
						_clearbox.alert('ClearBox HIBA:\n\nHibasan megadott clearbox REL azonosito: "' + CB_Rel + '"!\n(Helye: dokumentum, a ' + i + '. <a> tag-en belul.)');
					}
				}
			}
		}
	}

 	_clearbox = $.clearbox;

 	function intval(n) {
 		return parseInt(n);
 	}

 	function _link_rel_func() {
 		var _link = $(this);

 		if (_link.prop('data-rel-func') > 0) {
 			_clearbox.CB_ClickIMG(_link.prop('data-rel-key'), _link.attr('href'), _link.attr('title'));
		} else {
			_clearbox.CB_ClickURL(_link.prop('data-rel-key'), _link.attr('href'), _link.attr('title'));
		}

		return false;
 	}

 	function _image(src) {
 		var img = new Image();
 		if (src) img.src = src;
 		return img;
 	}

 	var CB_Blur;

	function CB_ShowDocument() {
		CB_HideContent
			.fadeTo('slow', 0, function(){
				clearTimeout(CB_Blur);
				$(this)
					.hide()
				;
				CB_Hide = 0;
				CB_ClearBox = false;
			})
		;
	}

	function CB_HideDocument(a) {
		var b = a;

		CB_HideContent
			.stop()
			.height(DocSizeY + CB_BodyMarginY)
			.show()
			.fadeTo('slow', _clearbox.options.CB_HideOpacity / 100, function(){
				clearTimeout(CB_Blur);
				CB_Hide = _clearbox.options.CB_HideOpacity;

				if (b == 'x') {
					CB_LoadingImg.show();
					_clearbox.CB_AnimatePlease('x');
				} else {
					_clearbox.CB_NewWindow();
				}
			})
		;
	}
})(jQuery);
