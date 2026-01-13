/* Проверяет наличие Local Storage */
function supports_html5_storage() {
  try {
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
}


$(function() {
    $('.app-shot-time').inputmask({ mask: "99:99" });
	
	// Для сохранения данных об открытых вкладках используем Local Storage
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		
		if ( supports_html5_storage() ) {
			
			var cid = $(e.target).attr("href");
			var nid = $(e.relatedTarget).attr("href");
			
			localStorage.setItem(cid, "show");
			localStorage.removeItem(nid);

		}
	});
	
	// При загрузки контента восстанавливаем состояния открытых вкладках
	$('a[data-toggle="tab"]').each(function(e) {
		
		if ( supports_html5_storage() ) {
		
			var cid = $(this).attr("href");
			
			if ( localStorage.getItem(cid) == "show" ) {
				
				$(this).tab('show');
			}
		}
	});
	
	// Списки с сортировкой
	var sortableFunction = function() { 
		$(".sortable").sortable({
			
			start: function (event, ui) {
				
				ui.placeholder.height(ui.helper.height());
				
				var userAgent = navigator.userAgent.toLowerCase();
				
				if(userAgent.match(/firefox/)) {
					ui.item.css('margin-top', $(window).scrollTop());
				}
			},	
			
			beforeStop: function (event, ui) {
				
				var userAgent = navigator.userAgent.toLowerCase();
				if(userAgent.match(/firefox/)) {
					ui.item.css('margin-top', 0 );
				}
			},
			
			stop: function(e, ui) {
				$.map($(this).find('li'), function(el) {
					return el.id + ' = ' + $(el).index();
				}); 
			},
			
			handle: "a[rel=move]",
																
			update: function () {
				
				var data = $(this).sortable("serialize");
				
				$.ajax({
					url: "positions",
					type: "post",
					dataType: "json",
					data: data,
					beforeSend: function() {
						new PNotify({ title: 'Сохранение', text: 'Идет сохраннение', type: 'info' });
					},
					success: function (r) {
						
						if (r == null || typeof r.result == "undefined") {
							new PNotify({ title: 'Ошибка', text: 'Произошла ошибка при сохранении', type: 'error' });
						} else {
							new PNotify({ title: 'Сохранено', text: 'Порядок записей изменен', type: 'success' });
						}
					},
					
					error: function () { 
						new PNotify({ title: 'Ошибка', text: 'Произошла непредвиденная ошибка при сохранении', type: 'error' });
					}
				});
			},
			opacity: 0.8
		});
	};
	
	$("body").bind("ajaxComplete", function(e, xhr, settings) {
		sortableFunction();
	});
	
	// ie bug fix
	$('ul.sortable').bind('mousedown', function(e) {
		  e.stopPropagation();
	});
	
	$('[data-form=datepicker]').datepicker({weekStart: 1,  language: "ru-RU", autoclose: true, todayBtn: 'linked', format: 'dd.mm.yyyy'});

	
	sortableFunction();
});



