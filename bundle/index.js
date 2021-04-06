import naja from 'naja';
import netteForms from 'nette-forms';
import $ from 'jquery';
import "bootstrap";
import "trix";
import "bootstrap-switch";

require("./components/index");
require("./components/menu");
require("./components/editor");

document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
netteForms.initOnLoad();

