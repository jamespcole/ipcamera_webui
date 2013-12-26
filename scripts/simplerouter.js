function SimpleRouter()  {
	this.routes = [];
	this.handler_404 = null;
	this.init = function() {

	},

	this.route = function() {
		var url_info = this.getUrlInfo();
		var bits = url_info.url_data;
		var query_string = url_info.query_string;
		for(var i = 0; i < this.routes.length; i++) {
			var route = this.routes[i];
			var match = this.matchRoute(route, bits);
			if(match !== false) {
				route.navigate(match);
				return;
			}
		}
		if(this.handler_404) {
			this.handler_404();
		}
		else {
			console.log('No 404 handler set in router');
		}
	},

	this.matchRoute = function(route, url_data) {
		var params = {};
		for(var i = 0; i < route.url_parts.length; i++) {
			if(url_data.length < i) {
				return false;
			}
			//go backwards though parts so that the beginning of the URL does not matter
			var part = route.url_parts[(route.url_parts.length - 1) - i];
			var url_part = url_data[(url_data.length - 1) - i];
			if(part.indexOf(':') === 0) {
				params[part.replace(':', '')] = url_part;
				continue;
			}
			if(url_part != part) {
				return false;
			}
		}
		return params;
	},

	this.addRoute = function(route) {
		route['url_parts'] = route.url.split('/');
		if(route['url_parts'].length == 0) {
			route['url_parts'].push(route.url);
		}
		if(route.include_querystring === undefined) {
			route['include_querystring'] = true;
		}
		this.routes.push(route);
	},

	this.add404Handler = function(handler) {
		this.handler_404 = handler;
	}

	//Handle use of hash in URL for older browsers
	this.getUrlInfo = function() {
		var result={
			url_data: [],
			query_string: ''
		};
		if(window.location.href.indexOf('#') != -1) {
			if(window.location.hash.indexOf('?') !== -1) {
				var hash = window.location.hash;
				var hash_bits = window.location.hash.split('?');
				if(hash_bits.length > 1) {
					result.query_string = hash_bits[1];
					hash = hash_bits[0];
				}
			}
			else if(window.location.search !== '') {
				result.query_string = window.location.search.replace('?', '');
			}
			hash = hash.replace('#!', '');
			result.url_data = hash.replace('#', '').split('/');
			if(result.url_data.length == 0) {
				result.url_data.push(hash);
			}
		}
		else {
			result.query_string = window.location.search.replace('?', '');
			result.url_data = window.location.pathname.split('/');
			if(result.url_data.length == 0) {
				result.url_data.push(window.location.pathname);
			}
		}
		return result;
	},

	this.parseQueryString = function(query_string) {
		var result = {};
	}
};