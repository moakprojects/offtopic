$(document).ready(function(){

	$('.modal-trigger').click(function() {
		$('.carousel').carousel();
		$('.carousel').carousel('next');
	});

	$(document).on('click', '.carousel-item', function() {
		$('#errorMsg').addClass('hide');
		$('.preloader-wrapper').removeClass('hide');
		console.log('dfd', $(this).attr("id"));
		$.post('/resources/controllers/userController.php', {avatarName: $(this).attr("id")}, function(returnData) {
			console.log('returnDataDefaultKépen: ', returnData);
			var obj = jQuery.parseJSON(returnData);
			$('.preloader-wrapper').addClass('hide');
			if(obj.data_type == 0) {
				$('#errorMsg').removeClass('hide');
				$('#errorMsg').html(obj.data_value);
			} else {
				var aux = "/public/images/upload/" + obj.data_value;
				$('.newAvatarImg').attr('src', aux);
			}
		});

	});

	$(document).on('change', '#avatarImg', function() {
		$('#errorMsg').addClass('hide');
		var property = $("#avatarImg")[0].files[0];
		var imageName = property.name;
		var imageExtension = imageName.split('.').pop().toLowerCase();
		var imageSize = property.size;

		if(jQuery.inArray(imageExtension, ['png', 'jpg', 'jpeg']) == -1) {
			$('#errorMsg').removeClass('hide');
			$('#errorMsg').html("Invalid file type");
		} else if(imageSize > 4000000) {
			$('#errorMsg').removeClass('hide');
			$('#errorMsg').html("The size is too big");
		} else {
			var form_data = new FormData();
			console.log("property", property);
			form_data.append("file", property);
			console.log('form_Data', form_data);
			$.ajax({
				url: '/resources/controllers/userController.php',
				method: 'POST',
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function() {
					$('.preloader-wrapper').removeClass('hide');
				},
				success: function(data) {
					console.log('returnDataFeltöltöttKépen: ', data);
					$('.preloader-wrapper').addClass('hide');
					var obj = jQuery.parseJSON(data);
					
					if(obj.data_type == 0) {
						$('#errorMsg').removeClass('hide');
						$('#errorMsg').html(obj.data_value);
					} else {
						var aux = "/public/images/upload/" + obj.data_value;
						$('.newAvatarImg').attr('src', aux);
					}
					
				}
			});
		}
	});

	(function ($) {
		$.fn.countTo = function (options) {
			options = options || {};
			
			return $(this).each(function () {
				// set options for current element
				var settings = $.extend({}, $.fn.countTo.defaults, {
					from:            $(this).data('from'),
					to:              $(this).data('to'),
					speed:           $(this).data('speed'),
					refreshInterval: $(this).data('refresh-interval'),
					decimals:        $(this).data('decimals')
				}, options);
				
				// how many times to update the value, and how much to increment the value on each update
				var loops = Math.ceil(settings.speed / settings.refreshInterval),
					increment = (settings.to - settings.from) / loops;
				
				// references & variables that will change with each update
				var self = this,
					$self = $(this),
					loopCount = 0,
					value = settings.from,
					data = $self.data('countTo') || {};
				
				$self.data('countTo', data);
				
				// if an existing interval can be found, clear it first
				if (data.interval) {
					clearInterval(data.interval);
				}
				data.interval = setInterval(updateTimer, settings.refreshInterval);
				
				// initialize the element with the starting value
				render(value);
				
				function updateTimer() {
					value += increment;
					loopCount++;
					
					render(value);
					
					if (typeof(settings.onUpdate) == 'function') {
						settings.onUpdate.call(self, value);
					}
					
					if (loopCount >= loops) {
						// remove the interval
						$self.removeData('countTo');
						clearInterval(data.interval);
						value = settings.to;
						
						if (typeof(settings.onComplete) == 'function') {
							settings.onComplete.call(self, value);
						}
					}
				}
				
				function render(value) {
					var formattedValue = settings.formatter.call(self, value, settings);
					$self.html(formattedValue);
				}
			});
		};
		
		$.fn.countTo.defaults = {
			from: 0,               // the number the element should start at
			to: 0,                 // the number the element should end at
			speed: 1000,           // how long it should take to count between the target numbers
			refreshInterval: 100,  // how often the element should be updated
			decimals: 0,           // the number of decimal places to show
			formatter: formatter,  // handler for formatting the value before rendering
			onUpdate: null,        // callback method for every time the element is updated
			onComplete: null       // callback method for when the element finishes updating
		};
		
		function formatter(value, settings) {
			return value.toFixed(settings.decimals);
		}
	}(jQuery));

	jQuery(function ($) {
	// custom formatting example
	$('.count-number').data('countToOptions', {
		formatter: function (value, options) {
		return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
		}
	});
	
	// start all the timers
	$('.timer').each(count);  
	
	function count(options) {
		var $this = $(this);
		options = $.extend({}, options || {}, $this.data('countToOptions') || {});
		$this.countTo(options);
	}
	});
});