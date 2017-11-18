window._ = require('lodash');

import Moment from "moment";

window.moment = Moment;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');

} catch (e) {}

window.accounting = require('accounting-js');

import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';

import 'fullcalendar/dist/fullcalendar.css';

