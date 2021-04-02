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
        const container = page.querySelector('.UsersList');
        let datasource = new DatasourceAjax('User', 'getTable', ['User', 'User']);
        let objectsList = new ObjectsList(datasource);
        objectsList.columns = [{name: "Imie", content: row => row.name, sortName: 'name'},{name: "Nazwisko", content: row => row.surname, sortName: 'surname'},{name: "Email", content: row => row.mail, sortName: 'mail'}];
        objectsList.generateActions = (rows, mode) => {
            let ret = [];
            if (rows.length == 1) {
                ret.push({
                    name: TCommonBase("edit"),
                    icon: 'icon-edit',
                    href: "User/edit/" + rows[0].id,
                    main: true
                });
            }
            if (mode != 'row') {
                ret.push({
                    name: TCommonBase("editInNewTab"), icon: 'icon-edit', showInTable: false, command() {
                        rows.forEach(x => window.open("User/edit/" + x.id))
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
            await AjaxPanel.User.insert(data);
            PanelPageManager.goto('User');
        }
    }
}

export class edit {
    constructor(page, data) {
        this.page = page;
        this.data = data;

        let form = new FormManager(this.page.querySelector('form'));
        form.load(this.data.user);

        form.submit = async data => {
            await AjaxPanel.User.update(data);
            PanelPageManager.goto('User');
        }
    }
}

export class myAccount {
    constructor(page, data) {
        this.page = page;
        this.data = data;

        let form = new FormManager(this.page.querySelector('form.changePassword'));
        form.submit = async ({password, password2}) => {
            if (password == password2) {
                await AjaxPanel.User.changeCurrentUserPassword(password, password2);
                modal("hasło zmienione");
            } else
                modal("hasła nie są identyczne", 'error');

            form.reset();

        }
    }
}