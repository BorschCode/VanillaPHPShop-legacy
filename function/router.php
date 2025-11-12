<?php
/**
 * Class Router
 * Created by PhpStorm.
 * User: ASUP7
 * Date: 07.12.2017
 * Time: 11:52
 * Core class for handling URL routing and dispatching requests to controllers.
 */

class Router
{
    private $routes;

    /**
     * Constructor: loads the routes configuration file.
     */
    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        # Load the routes array from the configuration file
        $this->routes = include($routesPath) ;
    }

    /**
     * Retrieves the request URI and trims slashes.
     * @return string The request URI
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        {
            # Trim leading and trailing slashes
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }


    /**
     * Runs the router:
     * 1. Gets the URI.
     * 2. Matches the URI against patterns defined in routes.php.
     * 3. Dispatches the request to the appropriate Controller and Action.
     */
    public function run()
    {
        # Get the request URI
        $uri = $this->getURI();

        # Check if the URI matches any pattern in the routes
        foreach ($this->routes as $uriPattern => $path)
        {
            if (preg_match("~$uriPattern~", $uri))
            {
                # Create the internal route path by replacing the pattern in the URI
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                # Determine controller, action, and parameters
                $segments = explode('/', $internalRoute);

                # Controller name (e.g., ProductController)
                $controllerName = array_shift($segments) . 'Controller';

                # Action name (e.g., actionView)
                $actionName = 'action' . ucfirst(array_shift($segments));

                # Remaining segments are parameters
                $parameters = $segments;

                # Path to the controller file
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                # Check and include the controller file
                if (file_exists($controllerFile))
                {
                    include_once ($controllerFile);
                }

                # Create an object of the controller class
                $controllerObject = new $controllerName;

                # Call the action method using the collected parameters
                try
                {
                    $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                }
                catch ( Exception $e)
                {
                    # Display the error message instead of just calling getMessage()
                    error_log("Router Error: " . $e->getMessage());
                    echo "An error occurred while dispatching the request.";
                    return;
                }

                # If the action returned a non-null result, routing is complete
                if ($result != null)
                {
                    break;
                }
                else
                {
                    # Default fallback if the controller action didn't return a result (preserved original logic)
                    require_once(ROOT . '/views/about/index.php');
                }
            }
        }
    }
}