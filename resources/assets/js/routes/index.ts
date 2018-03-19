let $router = $app.make<RouterInterface>("$router");
import RouterInterface from "varie/lib/routing/RouterInterface";

/*
|--------------------------------------------------------------------------
| Your default routes for your application
|--------------------------------------------------------------------------
|
*/

$router.route("/", "dashboard/index").setName('dashboard');
$router.route('/my-profile', 'user/profile');

$router.route("/repositories/add", "repository/add");
$router.route("/repositories/:repository", "repository/patches");
$router.route("/repositories/:repository/settings", "repository/settings");
$router.route("/repositories/:repository/patches/:patch", "repository/patch");

$router.route("*", "errors/404");
