import {DatasourceAjax} from "../../../Js/datasourceAjax";
import {ObjectsList} from "../../../Js/ObjectsList/objectsList";
import {FormManager} from "../../../Js/form";
import {PanelPageManager} from "../../../Js/PanelPageManager";
import {AjaxPanel} from "../../../Js/ajaxPanel";
import {modal} from "../../../Js/modal";
import {t} from "../../i18n.xml";
import {t as TCommon} from "../../../Common/i18n.xml";

export class index {
    constructor(page, data) {
        const container = page.querySelector('.ComponentsList');
        let datasource = new DatasourceAjax('Component', 'getTable', ['Component', 'Component']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [
            {name: t('Package'), content: row => row.package, sortName: 'package'},
            {name: t('Name'), content: row => row.name, sortName: 'name'},
        ]
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommon("details"),
                    icon: 'icon-show',
                    href: "Component/details/" + (rows[0].package??'')+"/"+rows[0].name,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommon("detailsInNewTab"), icon: 'icon-show', showInTable: false, command() {
                        rows.forEach(x => window.open("Component/details/" + x.package+"/"+x.name));
                    }
                });
            }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}
