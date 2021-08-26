import {DatasourceAjax} from "../../../Js/datasourceAjax";
import {ObjectsList} from "../../../Js/ObjectsList/objectsList";
import {FormManager} from "../../../Js/form";
import {PanelPageManager} from "../../../Js/PanelPageManager";
import {AjaxPanel} from "../../../Js/ajaxPanel";
import {modal} from "../../../Js/modal";

function t(a) {
    return a;//tmp
}
function TCommonBase(a) {
    return a;//tmp
}

export class index {
    constructor(page, data) {
        const container = page.querySelector('.TrackingCodeList');
        let datasource = new DatasourceAjax('TrackingCode', 'getTable', ['TrackingCode', 'TrackingCode']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [{name: "Nazwa", content: row => row.name, sortName: 'name'},{name: "Czy aktywny?", content: row => row.is_active=='1'?'Tak':'nie', sortName: 'is_active'}];
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommonBase("edit"),
                    icon: 'icon-edit',
                    href: "TrackingCode/edit/" + rows[0].id,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommonBase("editInNewTab"), icon: 'icon-edit', showInTable: false, command() {
                        rows.forEach(x => window.open("TrackingCode/edit/" + x.id))
                    }
                });
            }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}

export class add {
    constructor(page, data) {
        this.page = page;
        this.data = data;

        let form = new FormManager(this.page.querySelector('form'));

        form.submit = async data => {
            await AjaxPanel.TrackingCode.insert(data);
            PanelPageManager.goto('TrackingCode');
        }
    }
}

export class edit {
    constructor(page, data) {
        this.page = page;
        this.data = data;

        let form = new FormManager(this.page.querySelector('form'));
        form.load(this.data.trackingCode);

        form.submit = async data => {
            await AjaxPanel.TrackingCode.update(data);
            PanelPageManager.goto('TrackingCode');
        }
    }
}
