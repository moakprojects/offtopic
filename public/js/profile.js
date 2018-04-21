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

/* Activity chart */

$(function () {

	Highcharts.setOptions({
		chart: {
			style: {
				fontFamily: 'Open Sans',
				color: '#ccc'
			}
		}
	});

	$.post('/resources/controllers/userController.php', {requestPostDistributionChartData: true}, function(returnData) {

		var obj = jQuery.parseJSON(returnData);

		if(obj.data_type === 1) {
			
			var xAxisValues = [];
			var dataValues = [];

			for(var i = 0; i < obj.data_value.length; i++) {
				xAxisValues.push(obj.data_value[i].categoryName);
				dataValues.push(obj.data_value[i].numberOfPostsPercent);
			}
  
			$('#activityChart').highcharts({
					chart: {
						type: 'column',
						backgroundColor: '#152b39'
					},
					colors: ['#204d52'],
					title: {
						text: 'Distribution of posts by category',
						style: {  
						color: '#ccc'
						}
					},
					xAxis: {
						tickWidth: 0,
						labels: {
						style: {
							color: '#ccc',
							}
						},
						categories: xAxisValues
					},
					yAxis: {
						gridLineWidth: .5,
						max: 100,
						gridLineDashStyle: 'dash',
						gridLineColor: '#ccc',
						title: {
							text: '',
							style: {
							color: '#fff'
							}
						},
						labels: {
						formatter: function() {
									return Highcharts.numberFormat(this.value, 0) + ' %';
								},
						style: {
							color: '#ccc',
							}
						}
					},
					legend: {
						enabled: false,
					},
					credits: {
						enabled: false
					},
					tooltip: {
						valueSuffix: ' %'
					},
					plotOptions: {
						column: {
							borderRadius: 2,
						pointPadding: 0,
							groupPadding: 0.1
						} 
						},
					series: [{
						name: 'Activity',
						data: dataValues
					}]
				});
		}
	});

	

	$.post('/resources/controllers/userController.php', {requestPostHistoryChartData: true}, function(returnData) {
		console.log(returnData);
		
		var obj = jQuery.parseJSON(returnData);

		console.log(obj);

		if(obj.data_type === 1) {
			
			var xAxisValues = [];
			var dataValues = [];

			for(var i = 0; i < obj.data_value.length; i++) {
				xAxisValues.push(obj.data_value[i].monthName);

				if(obj.data_value[i].numberOfPosts) {
					dataValues.push(parseInt(obj.data_value[i].numberOfPosts));
				} else {
					dataValues.push(0);
				}
			}

			Highcharts.chart('historyChart', {
				chart: {
					backgroundColor: '#152b39'
				},
				title: {
					text: 'Number of posts',
					style: {  
						color: '#ccc'
					}
				},
			
				xAxis: {
					labels: {
						style: {  
							color: '#ccc'
							}
					},
					categories: xAxisValues
				},
				yAxis: {
					minTickInterval: 1,
					title: {
						text: '',
					},
					labels: {
						style: {
							color: '#ccc',
							}
					}
				},
				legend: {
					enabled: false,
				},
				credits: {
					enabled: false
				},
				series: [{
					name: 'Written posts',
					type: 'area',
					keys: ['y', 'selected'],
					data: dataValues
				}]
			});
		}
	});
  
	var pieColors = (function () {
		var colors = [],
			base = Highcharts.getOptions().colors[0],
			i;
	
		for (i = 0; i < 10; i += 1) {
			// Start out with a darkened base color (negative brighten), and end
			// up with a much brighter color
			colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
		}
		return colors;
	}());
  // Build the chart
  Highcharts.chart('pieChart', {
	colors: Highcharts.map(pieColors, function (color) {
		return {
		  radialGradient: {
			cx: 0.5,
			cy: 0.3,
			r: 0.7
		  },
		  stops: [
			[0, color],
			[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		  ]
		};
	  }),
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
	},
	title: {
	  text: 'Shared posts'
	},
	tooltip: {
	  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions: {
	  pie: {
		allowPointSelect: true,
		cursor: 'pointer',
		dataLabels: {
		  enabled: true,
		  format: '<b>{point.name}</b>: {point.percentage:.0f} %',
		  style: {
			color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		  },
		  connectorColor: 'silver'
		}
	  }
	},
	legend: {
		enabled: false,
	},
	credits: {
		enabled: false
	},
	series: [{
	  name: 'Share',
	  data: [
		{ name: 'Facebook', y: 60 },
		{ name: 'Twitter', y: 29 },
		{ name: 'Google+', y: 11 }
		]
	}]
  });

  $.post('/resources/controllers/userController.php', {requestPostLikesChartData: true}, function(returnData) {
	var obj = jQuery.parseJSON(returnData);

	if(obj.data_type === 1) {

		if(obj.data_value.numberOfLikes === 0 && obj.data_value.numberOfDislikes === 0) {
			$('.noAcceptanceValue').removeClass('hide');
		} else {
			Highcharts.chart('donutChart', {
				colors: Highcharts.map(pieColors, function (color) {
					return {
						radialGradient: {
						cx: 0.5,
						cy: 0.3,
						r: 0.7
						},
						stops: [
						[0, color],
						[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
						]
					};
					}),
				chart: {
					type: 'pie',
					options3d: {
					enabled: true,
					alpha: 45
					}
				},
				title: {
					text: 'Distribution of likes on posts'
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
				plotOptions: {
					pie: {
					innerSize: 100,
					depth: 45,
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.0f} %',
						connectorColor: 'silver'
					},
					style: {
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						},
					}
				},
				legend: {
					enabled: false,
				},
				credits: {
					enabled: false
				},
				series: [{
					name: 'Amount',
					data: [
					['Like', parseInt(obj.data_value.numberOfLikes)],
					['Dislike', parseInt(obj.data_value.numberOfDislikes)]
					]
				}]
				});
		}
		
		var averagePostLikes = Math.round(obj.data_value.numberOfLikes / obj.data_value.numberOfPosts);
		
	}
  });

});

/* Togle menu for own things */

var select = function(s) {
    return document.querySelector(s);
  },
  selectAll = function(s) {
    return document.querySelectorAll(s);
  }, 
  animationWindow = select('#animationWindow'),    
    animData = {
		wrapper: animationWindow,
		animType: 'svg',
		loop: false,
		prerender: false,
		autoplay: false,
		path: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/35984/egg_flip.json'
	}, anim;

	anim = bodymovin.loadAnimation(animData);
 anim.addEventListener('DOMLoaded', onDOMLoaded);
 anim.setSpeed(15);

function onDOMLoaded(e){
  
 animationWindow.onclick = function(e){
  if(anim.currentFrame > 0){
   anim.playSegments([anim.currentFrame, 0], true);
   TweenMax.to('.eggGroup', 1, {
    x:0,
    ease:Elastic.easeOut.config(0.9,0.38)
   });

   $('.ownTitle').html("Created Topics");
   $('.createdTopics').removeClass("hide");
   $('.createdPosts').addClass("hide");
  } else {
   TweenMax.to('.eggGroup', 1.4, {
    x:73,
    ease:Elastic.easeOut.config(0.9,0.38)
   })
   anim.playSegments([anim.currentFrame, 300], true);

   $('.ownTitle').html("Written Posts");
   $('.createdTopics').addClass("hide");
   $('.createdPosts').removeClass("hide");
  }
 }

 var num = 0;
	$("svg g g g path").each(function() {
		var cl = $(this).attr('class');
		$(this).attr('class', 'eggPath' + num);
		num++;
	});
}