/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/build/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/weatherMap.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/weatherMap.js":
/*!*********************************!*\
  !*** ./assets/js/weatherMap.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var APP_ID = 'fd2678a168142c0b422c2a90ee25088e';
var searchButton = document.querySelector('.menu--search');
var locationButton = document.querySelector('.menu--loc');
var input = document.querySelector('.menu__city');
var city = document.querySelector('.name');
var icon = document.querySelector('.icon');
var temp = document.querySelector('.temp');
var h1 = document.querySelector('.h1');

var getWeatherForLocation = function getWeatherForLocation() {
  var searchForCurrentLocation = function searchForCurrentLocation(options) {
    return new Promise(function (resolve, reject) {
      navigator.geolocation.getCurrentPosition(resolve, reject, options);
    });
  };

  searchForCurrentLocation().then(function (position) {
    var url = "https://api.openweathermap.org/data/2.5/weather?lat=".concat(position.coords.latitude, "&lon=").concat(position.coords.longitude, "&units=metric&appid=").concat(APP_ID);
    h1.innerHTML = "M\xE9t\xE9o actuelle pour votre position";
    locationButton.style.display = "none";
    getWeather(url);
  })["catch"](function (err) {
    console.error(err.message);
    alert(err.message);
  });
};

getWeatherForLocation();
locationButton.addEventListener('click', getWeatherForLocation);
searchButton.addEventListener('click', function (e) {
  var cityName = input.value;
  var url = "https://api.openweathermap.org/data/2.5/weather?q=".concat(cityName, "&units=metric&appid=").concat(APP_ID);
  h1.innerHTML = 'Météo actuelle de la ville recherchée';
  locationButton.innerHTML = "Obtenir la m\xE9t\xE9o pour votre position";
  locationButton.style.display = "block";
  getWeather(url);
});
input.addEventListener('keyup', function (e) {
  e.keyCode === 13 && searchButton.click();
});

var getWeather = function getWeather(url) {
  return fetch(url).then(function (response) {
    if (response.status !== 200) {
      console.error("Status Code: ".concat(response.status));
    }

    console.log(url);
    return response.json();
  }).then(function (data) {
    console.log(data);
    var weather = data.weather,
        main = data.main;
    city.innerHTML = "".concat(data.name);
    icon.innerHTML = "<img src= 'https://openweathermap.org/img/w/".concat(weather[0].icon, ".png'>");
    temp.innerHTML = "".concat(Math.round(main.temp), "&deg;C");
  });
};

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3dlYXRoZXJNYXAuanMiXSwibmFtZXMiOlsiQVBQX0lEIiwic2VhcmNoQnV0dG9uIiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwibG9jYXRpb25CdXR0b24iLCJpbnB1dCIsImNpdHkiLCJpY29uIiwidGVtcCIsImgxIiwiZ2V0V2VhdGhlckZvckxvY2F0aW9uIiwic2VhcmNoRm9yQ3VycmVudExvY2F0aW9uIiwib3B0aW9ucyIsIlByb21pc2UiLCJyZXNvbHZlIiwicmVqZWN0IiwibmF2aWdhdG9yIiwiZ2VvbG9jYXRpb24iLCJnZXRDdXJyZW50UG9zaXRpb24iLCJ0aGVuIiwicG9zaXRpb24iLCJ1cmwiLCJjb29yZHMiLCJsYXRpdHVkZSIsImxvbmdpdHVkZSIsImlubmVySFRNTCIsInN0eWxlIiwiZGlzcGxheSIsImdldFdlYXRoZXIiLCJlcnIiLCJjb25zb2xlIiwiZXJyb3IiLCJtZXNzYWdlIiwiYWxlcnQiLCJhZGRFdmVudExpc3RlbmVyIiwiZSIsImNpdHlOYW1lIiwidmFsdWUiLCJrZXlDb2RlIiwiY2xpY2siLCJmZXRjaCIsInJlc3BvbnNlIiwic3RhdHVzIiwibG9nIiwianNvbiIsImRhdGEiLCJ3ZWF0aGVyIiwibWFpbiIsIm5hbWUiLCJNYXRoIiwicm91bmQiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGtEQUEwQyxnQ0FBZ0M7QUFDMUU7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnRUFBd0Qsa0JBQWtCO0FBQzFFO0FBQ0EseURBQWlELGNBQWM7QUFDL0Q7O0FBRUE7QUFDQTtB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LENBQUFQLEdBQUc7QUFBQSxTQUNsQm1CLEtBQUssQ0FBQ25CLEdBQUQsQ0FBTCxDQUFXRixJQUFYLENBQWdCLFVBQUFzQixRQUFRLEVBQUk7QUFDeEIsUUFBSUEsUUFBUSxDQUFDQyxNQUFULEtBQW9CLEdBQXhCLEVBQTZCO0FBQ3pCWixhQUFPLENBQUNDLEtBQVIsd0JBQStCVSxRQUFRLENBQUNDLE1BQXhDO0FBQ0g7O0FBQ0RaLFdBQU8sQ0FBQ2EsR0FBUixDQUFZdEIsR0FBWjtBQUNBLFdBQU9vQixRQUFRLENBQUNHLElBQVQsRUFBUDtBQUNILEdBTkQsRUFNR3pCLElBTkgsQ0FNUSxVQUFBMEIsSUFBSSxFQUFJO0FBQ1pmLFdBQU8sQ0FBQ2EsR0FBUixDQUFZRSxJQUFaO0FBRFksUUFFSkMsT0FGSSxHQUVjRCxJQUZkLENBRUpDLE9BRkk7QUFBQSxRQUVLQyxJQUZMLEdBRWNGLElBRmQsQ0FFS0UsSUFGTDtBQUdaekMsUUFBSSxDQUFDbUIsU0FBTCxhQUFxQm9CLElBQUksQ0FBQ0csSUFBMUI7QUFDQXpDLFFBQUksQ0FBQ2tCLFNBQUwseURBQWlFcUIsT0FBTyxDQUFDLENBQUQsQ0FBUCxDQUFXdkMsSUFBNUU7QUFDQUMsUUFBSSxDQUFDaUIsU0FBTCxhQUFxQndCLElBQUksQ0FBQ0MsS0FBTCxDQUFXSCxJQUFJLENBQUN2QyxJQUFoQixDQUFyQjtBQUNILEdBWkQsQ0FEa0I7QUFBQSxDQUF0QixDIiwiZmlsZSI6ImpzL3dlYXRoZXJNYXAuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9idWlsZC9cIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9hc3NldHMvanMvd2VhdGhlck1hcC5qc1wiKTtcbiIsImNvbnN0IEFQUF9JRCA9ICdmZDI2NzhhMTY4MTQyYzBiNDIyYzJhOTBlZTI1MDg4ZSdcbmNvbnN0IHNlYXJjaEJ1dHRvbiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5tZW51LS1zZWFyY2gnKVxuY29uc3QgbG9jYXRpb25CdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubWVudS0tbG9jJylcbmNvbnN0IGlucHV0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLm1lbnVfX2NpdHknKVxuY29uc3QgY2l0eSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5uYW1lJylcbmNvbnN0IGljb24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuaWNvbicpXG5jb25zdCB0ZW1wID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnRlbXAnKVxuY29uc3QgaDEgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuaDEnKVxuXG5cbmNvbnN0IGdldFdlYXRoZXJGb3JMb2NhdGlvbiA9ICgpID0+IHtcbiAgICBjb25zdCBzZWFyY2hGb3JDdXJyZW50TG9jYXRpb24gPSBvcHRpb25zID0+IHtcbiAgICAgICAgcmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcbiAgICAgICAgICAgIG5hdmlnYXRvci5nZW9sb2NhdGlvbi5nZXRDdXJyZW50UG9zaXRpb24ocmVzb2x2ZSwgcmVqZWN0LCBvcHRpb25zKVxuICAgICAgICB9KX1cblxuICAgIHNlYXJjaEZvckN1cnJlbnRMb2NhdGlvbigpXG4gICAgICAgIC50aGVuKChwb3NpdGlvbikgPT4ge1xuICAgICAgICAgICAgY29uc3QgdXJsID0gYGh0dHBzOi8vYXBpLm9wZW53ZWF0aGVybWFwLm9yZy9kYXRhLzIuNS93ZWF0aGVyP2xhdD0keyBwb3NpdGlvbi5jb29yZHMubGF0aXR1ZGUgfSZsb249JHsgcG9zaXRpb24uY29vcmRzLmxvbmdpdHVkZSB9JnVuaXRzPW1ldHJpYyZhcHBpZD0keyBBUFBfSUQgfWBcbiAgICAgICAgICAgIGgxLmlubmVySFRNTCA9IGBNw6l0w6lvIGFjdHVlbGxlIHBvdXIgdm90cmUgcG9zaXRpb25gXG4gICAgICAgICAgICBsb2NhdGlvbkJ1dHRvbi5zdHlsZS5kaXNwbGF5ID0gYG5vbmVgXG4gICAgICAgICAgICBnZXRXZWF0aGVyKHVybClcbiAgICAgICAgfSlcbiAgICAgICAgLmNhdGNoKChlcnIpID0+IHtcbiAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoZXJyLm1lc3NhZ2UpXG4gICAgICAgICAgICBhbGVydChlcnIubWVzc2FnZSlcbiAgICAgICAgfSlcbn1cblxuZ2V0V2VhdGhlckZvckxvY2F0aW9uKClcbmxvY2F0aW9uQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZ2V0V2VhdGhlckZvckxvY2F0aW9uKVxuXG5zZWFyY2hCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBlID0+IHtcbiAgICBjb25zdCBjaXR5TmFtZSA9IGlucHV0LnZhbHVlXG4gICAgY29uc3QgdXJsID0gYGh0dHBzOi8vYXBpLm9wZW53ZWF0aGVybWFwLm9yZy9kYXRhLzIuNS93ZWF0aGVyP3E9JHsgY2l0eU5hbWUgfSZ1bml0cz1tZXRyaWMmYXBwaWQ9JHsgQVBQX0lEIH1gXG4gICAgaDEuaW5uZXJIVE1MID0gJ03DqXTDqW8gYWN0dWVsbGUgZGUgbGEgdmlsbGUgcmVjaGVyY2jDqWUnXG4gICAgbG9jYXRpb25CdXR0b24uaW5uZXJIVE1MID0gYE9idGVuaXIgbGEgbcOpdMOpbyBwb3VyIHZvdHJlIHBvc2l0aW9uYFxuICAgIGxvY2F0aW9uQnV0dG9uLnN0eWxlLmRpc3BsYXkgPSBgYmxvY2tgXG4gICAgZ2V0V2VhdGhlcih1cmwpXG59KVxuXG5pbnB1dC5hZGRFdmVudExpc3RlbmVyKCdrZXl1cCcsIGUgPT4ge1xuICAgIGUua2V5Q29kZSA9PT0gMTMgJiYgc2VhcmNoQnV0dG9uLmNsaWNrKClcbn0pXG5cbmNvbnN0IGdldFdlYXRoZXIgPSB1cmwgPT4gKFxuICAgIGZldGNoKHVybCkudGhlbihyZXNwb25zZSA9PiB7XG4gICAgICAgIGlmIChyZXNwb25zZS5zdGF0dXMgIT09IDIwMCkge1xuICAgICAgICAgICAgY29uc29sZS5lcnJvcihgU3RhdHVzIENvZGU6ICR7IHJlc3BvbnNlLnN0YXR1cyB9YClcbiAgICAgICAgfVxuICAgICAgICBjb25zb2xlLmxvZyh1cmwpXG4gICAgICAgIHJldHVybiByZXNwb25zZS5qc29uKClcbiAgICB9KS50aGVuKGRhdGEgPT4ge1xuICAgICAgICBjb25zb2xlLmxvZyhkYXRhKVxuICAgICAgICBjb25zdCB7IHdlYXRoZXIsIG1haW4gfSA9IGRhdGFcbiAgICAgICAgY2l0eS5pbm5lckhUTUwgPSBgJHsgZGF0YS5uYW1lfWBcbiAgICAgICAgaWNvbi5pbm5lckhUTUwgPSBgPGltZyBzcmM9ICdodHRwczovL29wZW53ZWF0aGVybWFwLm9yZy9pbWcvdy8keyB3ZWF0aGVyWzBdLmljb24gfS5wbmcnPmBcbiAgICAgICAgdGVtcC5pbm5lckhUTUwgPSBgJHsgTWF0aC5yb3VuZChtYWluLnRlbXApIH0mZGVnO0NgXG4gICAgfSkpXG5cblxuXG4iXSwic291cmNlUm9vdCI6IiJ9