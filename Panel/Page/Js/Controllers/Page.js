// import {FormManager} from "../../../Core/js/form";
// import {AjaxTask} from "../../../Core/js/ajaxTask";
// import {pageManager} from "../../../Core/js/pageManager";

import {DatasourceAjax} from "../../../Js/datasourceAjax";
import {ObjectsList} from "../../../Js/ObjectsList/objectsList";
import {FormManager} from "../../../Js/form";
import {PanelPageManager} from "../../../Js/PanelPageManager";
import {AjaxPanel} from "../../../Js/ajaxPanel";
import {t} from "../../i18n.xml";
import {t as TCommon} from "../../../Common/i18n.xml";
import StructValueEdit from "../ValueEdit/StructValueEdit";
import {editParams} from "../ValueEdit/ValueEditFactory";
import PageSimulator from "../PageSimulator";


export class index {
    constructor(page, data) {
        const container = page.querySelector('.list');
        let datasource = new DatasourceAjax(AjaxPanel.Page.getTable);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [];
        objectsList.columns.push({
            name: t('Fields.title'),
            content: row => row.title ?? '',
            sortName: 'title',
            width: 180,
            widthGrow: 1
        });
        objectsList.columns.push({
            name: t('Fields.path'),
            content: row => row.path,
            sortName: 'path',
            width: 180,
            widthGrow: 1
        });
        objectsList.columns.push({
            name: t('Fields.component'),
            content: row => (row.module ?? '') + '\\' + row.component,
            sortName: 'component',
            width: 180,
            widthGrow: 1
        });
        objectsList.columns.push({
            name: t('Fields.type'),
            content: row => row.type,
            sortName: 'type',
            width: 180,
            widthGrow: 1
        });

        //objectsList.sort = {"col": "stamp", "desc": true};
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            // if (rows.length == 1) {
            //     ret.push({
            //         name: TCommonBase("details"),
            //         icon: 'icon-show',
            //         href: "/Balance/show/" + rows[0].id,
            //         main: true
            //     });
            //if (Permissions.can('Balance', 'edit')) {
            ret.push({
                name: TCommon("Edit"),
                icon: 'icon-edit',
                href: "/panel/Page/edit/" + rows[0].id,
            });
            //}
            // }
            // if (mode != 'row' && Permissions.can('Balance', 'edit')) {
            //     ret.push({
            //         name: TCommonBase("openInNewTab"), icon: 'icon-show', showInTable: false, command() {
            //             rows.forEach(x => window.open("/Balance/show/" + x.id))
            //         }
            //     });
            // }
            return ret;
        }
        container.append(objectsList);
        objectsList.refresh();
    }
}

export class edit {
    constructor(page, data) {
        this.page = page;
        this.availableComponents = data.availableComponents;
        this.form = new FormManager(page.querySelector('form'));
        this.form.loadSelects(data.selects);
        this.form.load(data.Page);
        this.component = data.Page.component;
        this.module = data.Page.module;
        const config = JSON.parse(data.Page.parameters);
        let parameters = page.querySelector('.parameters');
        editParams.availableComponents = data.availableComponents;
        this.valueEdit = new StructValueEdit({value: config}, {items: data.Page.definedParameters});
        this.valueEdit.draw();
        parameters.append(this.valueEdit);

        if(Object.values(data.Page.definedParameters).length==0){
            parameters.append(document.create('p', {text: t('NoAdditionalParams')}));
        }
        this.form.submit = async newData => {
            newData.parameters = JSON.stringify(this.valueEdit.collectParameters().value);
            await AjaxPanel.Page.update(newData);
            PanelPageManager.goto('Page');
        }
        this.pageSimulator = new PageSimulator(page.querySelector('.pageSimulator'));
        this.refreshPreview();
        page.addEventListener('input', () => {
            this.refreshPreview();
        })
        const resizeObserver = new ResizeObserver(entries => {
            this.refreshPreview();
        });
        resizeObserver.observe(page.querySelector('.pageSimulator'));
    }

    refreshPreview() {
        const data = this.form.getData();
        data.parameters = this.valueEdit.collectParameters().value;
        data.component = this.component;
        data.module = this.module;
        this.pageSimulator.setData(data);
        this.pageSimulator.setSize();
    }
}

export class add {
    constructor(page, data) {
        let form = new FormManager(page.querySelector('form'));
        if (data && data.selects)
            form.loadSelects(data.selects);

        form.submit = async newData => {
            let id = await AjaxPanel.Page.insert(newData);
            PanelPageManager.goto('Page/edit/' + id);
        }
    }
}
