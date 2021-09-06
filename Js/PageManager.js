import {ComponentManager} from "./ComponentManager";

export class PageManager {
    static initPage(initInfo) {
        return Promise.all(initInfo.map(async x => {
                let controllerClass = await ComponentManager.get(x.module, x.component);
                if (controllerClass) {
                    new controllerClass(x);
                }
            }
        ));
    }
}