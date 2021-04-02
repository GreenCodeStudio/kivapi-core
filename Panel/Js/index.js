import {PanelPageManager} from "./PanelPageManager";
import "prototype-extensions"

setTimeout(() => PanelPageManager.initPage(window.controllerInitInfo, document.querySelector('.page'), true));

// setEvent('click', 'a', function (e) {
//     e.preventDefault();
//     pageManager.goto(this.href);
// });