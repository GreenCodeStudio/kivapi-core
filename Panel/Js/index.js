import {PanelPageManager} from "./PanelPageManager";
import "prototype-extensions"

setTimeout(() => PanelPageManager.initPage(window.controllerInitInfo, document.querySelector('.page'), true));

addEventListener('click', function (e) {
    if (e.target?.matches('a')) {
        e.preventDefault();
        PanelPageManager.goto(e.target.href)
    }
    ;
});
