export class ComponentManager {
    static registered = {};

    static register(module, component, fun) {
        if (!this.registered[module]) {
            this.registered[module] = {};
        }
        this.registered[module][component] = fun;
    }

    static async get(module, component) {
        if (this.registered[module] && this.registered[module] [component])
            return await this.registered[module]  [component]();
        else
            return null;
    }
}