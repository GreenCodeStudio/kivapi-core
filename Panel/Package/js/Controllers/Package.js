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
        const container = page.querySelector('.PackagesList');
        let datasource = new DatasourceAjax('Package', 'getTable', ['Package', 'Package']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [
            {name: t('Name'), content: row => row.fullName, sortName: 'fullName'},
        ]
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommon("details"),
                    icon: 'icon-show',
                    href: "Package/details/" + rows[0].id,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommon("detailsInNewTab"), icon: 'icon-show', showInTable: false, command() {
                        rows.forEach(x => window.open("Package/details/" + x.id))
                    }
                });
            }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}
