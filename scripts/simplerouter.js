function SimpleRouter()  {
	this.routes = [];
	this.init = function() {

	},

	this.route = function() {
		var url_data = window.location.pathname;
		var bits = url_data.split('/');
		if(bits.length == 0) {
			bits.push(url_data);
		}
		for(var i = 0; i < this.routes.length; i++) {
			var route = this.routes[i];
			var match = this.matchRoute(route, bits);
			if(match !== false) {
				route.navigate(match);
				return;
			}
		}
	},

	this.matchRoute = function(route, url_data) {
		var params = {};
		for(var i = 0; i < route.url_parts.length; i++) {
			if(url_data.length < i) {
				return false;
			}
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
	}
};