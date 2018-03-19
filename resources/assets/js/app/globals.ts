import ConfigInterface from "varie/lib/config/ConfigInterface";
import ApplicationInterface from "varie/lib/foundation/ApplicationInterface";

/*
|--------------------------------------------------------------------------
| App Globals
|--------------------------------------------------------------------------
| We have a few globals that the framework sets by default
|
*/

declare global {
  interface Window { Laravel : any; }
  interface String {
      toCamelCase() : string;
  }
  const $config: ConfigInterface;
  const $app: ApplicationInterface;
}
