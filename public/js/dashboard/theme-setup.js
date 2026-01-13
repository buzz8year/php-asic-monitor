$(function(){
	'use strict';

    // NORMALIZE HASH FROM URL
    // HASH IS OUTOMATICALLY ADDING ON URL IF .data-ajax IS CALLED
    // SO WE NEED TO REMOVE/RESET IT ON PAGE LOAD
    if (window.location.hash.indexOf('!/') == 1) { // not 0 because # is first character of window.location.hash
        location.hash = '';
    }
    
    // SCROLL TO TOP
    $(document).on('click', 'a[rel=to-top]',function(e) {
        e.preventDefault();
        $('body,html').animate({
            scrollTop: 0
        }, 'slow');
    });
    // END SCROLL TO TOP

    // ANIMATE SCROLL, define class .scroll to tag <a> will be activate this
    $(document).on('click', 'a.scroll',function(e){
        e.preventDefault();
        $("html,body").animate({scrollTop: $(this.hash).offset().top+5}, 600);
    });
    // END ANIMATE SCROLL
    
    // BOOTSTRAP MODAL
    $(document).on('shown.bs.modal', '.modal', function (e) {
        var $this = $(this),
            data_sound = ($this.attr('data-sound') === undefined) ? 'hello' : $this.attr('data-sound') ;

        if (data_sound != 'off') {

            if (data_sound == 'hello') {
                $.playSound('/stilearn/sounds/' + data_sound);
            }
            else if(data_sound == 'complete'){
                $.playSound('/stilearn/sounds/' + data_sound);
            }
            else if(data_sound == 'note'){
                $.playSound('/stilearn/sounds/' + data_sound);
            }
            else if(data_sound == 'bamboo'){
                $.playSound('/stilearn/sounds/' + data_sound);
            }
            else if(data_sound == 'pulse'){
                $.playSound('/stilearn/sounds/' + data_sound);
            }
            else{
                $.playSound(data_sound);
            }
        };
    })
    .on('hidden.bs.modal', '.modal', function (e) {
        $('.playSound').each(function(){
            var $this = $(this),
                audio = $this.next();

            $this.remove();
            audio.remove();
        });

        // remove posible bugs
        $(document).find('[data-toggle="popover"], [rel*="popover"]').popover('hide');
    })
    .on('loaded.bs.modal', '.modal', function(e){
        var target_id = e.target.id,
            handler = $('[data-target="#' + target_id + '"]'),
            data_scripts = handler.data('scripts'),
            scripts = data_scripts.replace(/\s+/g, '');

        scripts = scripts.split(",");

        $.each(scripts, function(i, val){
            var script = createScript(val);

            // remove the same existing script
            $('script[src="' + val + '"]').remove();
            // reload re-execute scripts (this may register script to re-axecute scripts)
            $('body').append(script);
            // clean console
            console.clear();
        });
    });
    // END BOOTSTRAP MODAL
    
    // BOOTSTRAP INPUT GROUP HACK
    $(document).on('focus', '.input-group-in .form-control', function(){
        var group = $(this).parent();

        if (group.hasClass('twitter-typeahead') || group.hasClass('minicolors')) {
            group.parent().addClass('focus');
        }
        else if(group.hasClass('input-group-in')){
            group.addClass('focus');
        }
    })
    .on('blur', '.input-group-in .form-control', function(){
        var group = $(this).parent();
        
        if (group.hasClass('twitter-typeahead') || group.hasClass('minicolors')) {
            group.parent().removeClass('focus');
        }
        else if(group.hasClass('input-group-in')){
            group.removeClass('focus');
        }
    });
    // END BOOTSTRAP INPUT GROUP HACK

    // COLLAPSE
    $(document).on('click.bs.collapse.data-api', '[data-toggle=collapse]', function(e) {
        var $this = $(this),
            panel_heading = ($this.parent().hasClass('panel-heading')) ? $this.parent() : $this.parent().parent(),
            group = $($this.data('parent'));

        // add all btn-collapsed
        group.find('[data-toggle="collapse"]').addClass('btn-collapsed');

        // remove .btn-collapsed if not .collapsed
        if (!$this.hasClass('collapsed')) {
            panel_heading.find('[data-toggle="collapse"]').removeClass('btn-collapsed');
        };
    });
    // END COLLAPSE
    

	$("tbody.table-dnd").tableDnD({
		onDrop: function(table, row) {
			//alert();
			$.ajax({
				url: $(table).attr("ajax-url"),
				data: $.tableDnD.serialize(),
				method: "post",
				dataType: "json",
				beforeSend: function() {
					new PNotify({
						title: 'Cохранение',
						text: 'Сохранение...',
						type: 'info',
						delay: 2000
					});
				},
				success: function() {
					new PNotify({
						title: 'Успешно',
						text: 'Сохранено',
						type: 'success'
					});
				},				
				error: function() {
					new PNotify({
						title: 'Ошибка',
						text: 'Произошла непредвиденная ошибка при сохранение',
						type: 'error'
					});
				}
			});
		}
	});

    // easyPieChart
    $('.easyPieChart').each(function(){
        var $this = $(this),
            barColor = $this.attr('data-barColor'),
            trackColor = $this.attr('data-trackColor'),
            scaleColor = $this.attr('data-scaleColor'),
            lineWidth = $this.attr('data-lineWidth'),
            size = $this.attr('data-size'),
            rotate = $this.attr('data-rotate');

        // default for undefined
        barColor = (barColor === undefined) ? '#13A89E' : barColor ;        // teal
        trackColor = (trackColor === undefined) ? '#ecf0f1' : trackColor ;  // cloud
        scaleColor = (scaleColor === undefined) ? '#bdc3c7' : scaleColor ;  // silver
        lineWidth = (lineWidth === undefined) ? 3 : parseInt(lineWidth) ;
        size = (size === undefined) ? 110 : parseInt(size) ;
        rotate = (rotate === undefined) ? 0 : parseInt(rotate) ;

        trackColor = (trackColor == 'false' || trackColor == '') ? false : trackColor ;
        scaleColor = (scaleColor == 'false' || scaleColor == '') ? false : scaleColor ;

        // initilize easy pie chart
        $this.easyPieChart({
            barColor: barColor,
            trackColor: trackColor,
            scaleColor: scaleColor,
            lineWidth: lineWidth,
            size: size,
            rotate: rotate,
            onStep: function(from, to, currentValue) {
                $(this.el).find('span').text(currentValue.toFixed(0) +'%');
            }
        });
    });



});