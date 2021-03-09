import naja from 'naja';
import netteForms from 'nette-forms';
import $ from 'jquery';
import "bootstrap";

require('./index.scss');
require("./components/menu");

document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
netteForms.initOnLoad();

