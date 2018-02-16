'use strict';
angular.module('KramyaApp', ['ui.router' ,
 'ngAnimate', 'ui.bootstrap' ,
 "ngTouch", 'ngMap', 'ngAutocomplete' , 'ngCookies', 
 'daterangepicker' , 'angular-momentjs' , 'directive.g+signin',
 'angucomplete-alt' , 'angular-jwt', 'angular-storage' , 'textAngular' , 'toaster'
 ])
	.config(function( $stateProvider, $urlRouterProvider, $locationProvider ,  $httpProvider) {
		// $locationProvider.html5Mode(true);
		$urlRouterProvider.otherwise('/');
		// jwtInterceptorProvider.tokenGetter=function(store){
		// 	return store.get('jwt');
		// };
		// $httpProvider.interceptors.push('jwtInterceptor');
		$stateProvider
			.state('home', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/home/home.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/'
			})
			.state('user', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/profile/user-profile.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/user-profile',
				data : {requiresLogin:true}
			})
			.state('hospital-profile', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/profile/hospital-profile.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/hospital-profile',
				data : {requiresLogin:true}
			})
			.state('career', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/career/career.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/career'
			})
			.state('about', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/about/about.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/about'
			})
			.state('blog', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/blog/blog.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/blog'
			})
			.state('blog-post', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/blog/blog_post.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/blog-post'
			})
			.state('mobile', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/mobile/mobile_app.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/mobile'
			})
			.state('contact', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/contact/contact.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/contact'
			})
			.state('faqs', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/faq/faqs.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/faqs'
			})
			.state('terms', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/terms/terms.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/terms'
			})
			.state('suggestions', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/suggestions/suggestion.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/suggestions'
			})
			.state('team', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/team/team.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/team'
			})
			.state('login', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/login/login.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/login',
				data : {doNotShowAfterLogin:true}
			})
			.state('signup', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/signup/signup.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/signup',
				data : {doNotShowAfterLogin:true}
			})
			.state('inter', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/register/inter.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/hospital-list'
				// data : {requiresLogin:true}
			})
			.state('register', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/register/register.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/register',
				data : {requiresLogin:true}
			})
			.state('hospital', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/hospital/hospital.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/hospital'
			})
			.state('search', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/search-result/search-results.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/search-result'
			})
			.state('recent', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/recent/recent.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/recent'
			})
			.state('inbox', {
                views: {
              'header': {
                templateUrl: 'views/header.html',
              },
              'body-view': {
                templateUrl: 'views/inbox/inbox.html',
              },
              'footer': {
                templateUrl: 'views/footer.html',
              }
            },
                url: '/inbox',
                data : {requiresLogin:true}
            })
			.state('privacy', {
				views: {
		      'header': {
		        templateUrl: 'views/header.html',
		      },
		      'body-view': {
		        templateUrl: 'views/privacy/privacy.html',
		      },
		      'footer': {
		        templateUrl: 'views/footer.html',
		      }
		    },
				url: '/privacy'
			});		
	})
	.config(function($sceDelegateProvider) {
	  $sceDelegateProvider.resourceUrlWhitelist([
	   'self',
	   "https://www.google.com/maps/embed/**",
	   'http://kramya.com/**',
	   'https://www.facebook.com/**',
	   'http://http://192.168.69.15:8080/kramya-development/**'
	  ]);
	})
	.run(function ($state , store ,$location, $window , $rootScope) {      
    $rootScope.$on('$routeChangeSuccess', function () {
        // fix recaptcha bug
        $('.pls-container').remove();              
    });
    $rootScope.$on('$stateChangeStart' , function(e,to){
    	// console.log('e:' , e);
    	// console.log('to:' , to);
    	if(to.data){
    		if(to.data.doNotShowAfterLogin){
	    			if(store.get('jwt')){
			    		e.defaultPrevented=true;
			    		$state.go("user");
		    		}
		    	}
    		if(to.data.requiresLogin){
	    		if(!store.get('jwt')){
	    			e.defaultPrevented=true;
	    			$state.go("home");
	    		}
	    	}
    	}
    	
    });
  })
	.factory('myService', function() {
		var savedData = {};
   	function set(key,data) {
    	savedData[key]=data;
   	}
   	function get(key) {
    	return savedData[key];
   	}
    return {
     	set: set,
     	get: get,
   	}
	})
	.service('anchorSmoothScroll', function(){
    
    this.scrollTo = function(eID) {

        // This scrolling function 
        // is from http://www.itnewb.com/tutorial/Creating-the-Smooth-Scroll-Effect-with-JavaScript
        
        var startY = currentYPosition();
        var stopY = elmYPosition(eID);
        var distance = stopY > startY ? stopY - startY : startY - stopY;
        if (distance < 100) {
            scrollTo(0, stopY); return;
        }
        var speed = Math.round(distance / 100);
        if (speed >= 20) speed = 20;
        var step = Math.round(distance / 25);
        var leapY = stopY > startY ? startY + step : startY - step;
        var timer = 0;
        if (stopY > startY) {
            for ( var i=startY; i<stopY; i+=step ) {
                setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
                leapY += step; if (leapY > stopY) leapY = stopY; timer++;
            } return;
        }
        for ( var i=startY; i>stopY; i-=step ) {
            setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
            leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
        }
        
        function currentYPosition() {
            // Firefox, Chrome, Opera, Safari
            if (self.pageYOffset) return self.pageYOffset;
            // Internet Explorer 6 - standards mode
            if (document.documentElement && document.documentElement.scrollTop)
                return document.documentElement.scrollTop;
            // Internet Explorer 6, 7 and 8
            if (document.body.scrollTop) return document.body.scrollTop;
            return 0;
        }
        
        function elmYPosition(eID) {
            var elm = document.getElementById(eID);
            var y = elm.offsetTop;
            var node = elm;
            while (node.offsetParent && node.offsetParent != document.body) {
                node = node.offsetParent;
                y += node.offsetTop;
            } return y;
        }

    };
    
});



