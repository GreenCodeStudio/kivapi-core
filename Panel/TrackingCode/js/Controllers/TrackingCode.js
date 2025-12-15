import {DatasourceAjax} from "../../../Js/datasourceAjax";
import {ObjectsList} from "../../../Js/ObjectsList/objectsList";
import {FormManager} from "../../../Js/form";
import {PanelPageManager} from "../../../Js/PanelPageManager";
import {AjaxPanel} from "../../../Js/ajaxPanel";
import {modal} from "../../../Js/modal";
import {t as TCommon} from "../../../Common/i18n.xml";
import {t} from "../../i18n.xml";


export class index {
    constructor(page, data) {
        const container = page.querySelector('.TrackingCodeList');
        let datasource = new DatasourceAjax(AjaxPanel.TrackingCode.getTable, ['TrackingCode', 'TrackingCode']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [
            {name: t('Fields.name'), content: row => row.name, sortName: 'name'},
            {name: t('Fields.is_active'), content: row => row.is_active == '1' ? 'Tak' : 'nie', sortName: 'is_active'}
        ];
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommon("edit"),
                    icon: 'icon-edit',
                    href: "TrackingCode/edit/" + rows[0].id,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommon("editInNewTab"), icon: 'icon-edit', showInTable: false, command() {
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
