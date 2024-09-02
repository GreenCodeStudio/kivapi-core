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
            {name: t('Vendor'), content: row => row.vendor, sortName: 'vendor'},
            {name: t('Name'), content: row => row.name, sortName: 'name'},
            {name: t('Version'), content: row => row.version, sortName: 'version'},
        ]
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommon("details"),
                    icon: 'icon-show',
                    href: "Package/details/" + rows[0].fullName,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommon("detailsInNewTab"), icon: 'icon-show', showInTable: false, command() {
                        rows.forEach(x => window.open("Package/details/" + rows[0].fullName))
                    }
                });
            }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}
export class available {
    constructor(page, data) {
        const container = page.querySelector('.PackagesList');
        let datasource = new DatasourceAjax('Package', 'getAvailableTable', ['Package', 'Package']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [
            {name: t('Vendor'), content: row => row.vendor, sortName: 'vendor'},
            {name: t('Name'), content: row => row.name, sortName: 'name'},
            {name: t('Version'), content: row => row.version, sortName: 'version'},
        ]
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommon("details"),
                    icon: 'icon-show',
                    href: "Package/availableDetails/" + rows[0].vendor+"/"+rows[0].name,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommon("detailsInNewTab"), icon: 'icon-show', showInTable: false, command() {
                        rows.forEach(x => window.open("Package/details/" + rows[0].fullName))
                    }
                });
            }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}

export class availableDetails {
    constructor(page,data) {
        page.querySelector('.installBtn').onclick=e=>{
            installByURL(data.git);
        }
    }
}
async function installByURL(url){
    let result = await AjaxPanel.Package.prepareInstallation(url);
    console.log(result)
    if (result?.details?.name) {
        if (await modal(`Are you sure want to install package ${result?.details?.vendor}/${result?.details?.name}? Do you trust author of this package, that it doesn't contains malicious code?`, 'warning', [{
            value: false,
            text: 'no'
        }, {value: true, text: 'yes, install'}])) {
            await AjaxPanel.Package.install(result.tmpId, result.url);
        }
    }
}
export class install {
    constructor(page, data) {

        this.form = new FormManager(page.querySelector('form'));
        this.form.submit = async newData => {
           await installByURL(newData.url);
        }
    }
}
