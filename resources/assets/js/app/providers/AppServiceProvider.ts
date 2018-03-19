import Vue from 'vue';
import PortalVue from 'portal-vue';
import VueClipboard from 'vue-clipboard2';
import ServiceProvider from "varie/lib/support/ServiceProvider";

/*
|--------------------------------------------------------------------------
| App Service Provider
|--------------------------------------------------------------------------
| You can bind various items to the app here, or can create other
| custom providers that bind the container
|
*/
export default class AppProvider extends ServiceProvider {
  public boot() {
      Vue.use(PortalVue);
      Vue.use(VueClipboard);

      String.prototype.toCamelCase = function() {
          return this.replace(/[^A-Za-z0-9]/g, ' ').replace(/^\w|[A-Z]|\b\w|\s+/g, function (match, index) {
              if (+match === 0 || match === '-' || match === '.' ) {
                  return "";
              }
              return index === 0 ? match.toLowerCase() : match.toUpperCase();
          });
      }
  }

  public register() {}
}
