<?php

/**
 * MvcFramework
 *
 * @link        https://github.com/joncarlmatthews/MvcFramework for the canonical source repository
 * @copyright   
 * @link        Coded to the Zend Framework Coding Standard for PHP 
 *              http://framework.zend.com/manual/1.12/en/coding-standard.html
 * 
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 */

namespace MvcFramework\View
{
    use \MvcFramework\View\Exception as ViewException;
    use \MvcFramework\View\Helper\Exception as HelperException;

    use \ArrayObject as ArrayObject;
    use \MvcFramework\Bootstrap\Core;

    /**
     * Core View class.
     *
     * The View class provides methods for rendering a specific view file. In
     * addition to this the View class provides methods setting and getting
     * view file properties via class overloading.
     *
     * @category    MvcFramework
     * @package     Mvc
     * @subpackage  View
     * @see         ArrayObject
     * @link        http://mwop.net/blog/131-Overloading-arrays-in-PHP-5.2.0.html
     */
    class View extends ArrayObject
    {
        /**
         * The Controller object. (All Views must have a Controller.)
         *
         * @access private
         * @var \MvcFramework\Controller\ControllerAbstract
         */
        private $_controllerObject = null;

        /**
         * The root directory path that all view files must stem from. This 
         * stops view files from being included from outside of the application's
         * directory tree.
         *
         * This object sets this value, so you don't have to.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::_setRootDirectory()
         */
        private $_rootDirPath = null;

        /**
         * The module directory name.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::setModuleName()
         */
        private $_moduleDirName = null;

        /**
         * The module's sub directory name.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::_setModuleSubDirectoryName()
         */
        private $_moduleSubFolderDirName = null;

        /**
         * The standard View directory name.
         *
         * @access private
         * @var string
         */
        private $_viewDirName = 'View';

        /**
         * The controller directory name.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::setControllerName()
         */
        private $_controllerDirName = null;

        /**
         * The view file prefix.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::setViewPrefix()
         */
        private $_viewFilePrefix = null;

        /**
         * The view file name.
         *
         * @access private
         * @var string
         * @see \MvcFramework\View\View::setViewName()
         */
        private $_viewFileName = null;

        /**
         * The standard View file extension.
         *
         * @access private
         * @var string
         */
        private $_viewFileExtension = '.phtml';

        /**
         * Constructor. Creates a new View object.
         *
         * @access public
         * @author  Jon Matthews
         * @param   \MvcFramework\Controller\ControllerAbstract     $controllerObject
         * @param   array   $props  An array of view properties to set when 
         *                          instantiating the object.
         * @return \MvcFramework\View\View
         */
        public function __construct(\MvcFramework\Controller\ControllerAbstract $controllerObject,
                                    array $props = array())
        {
            // Allow accessing properties as either array keys or object 
            // properties.
            parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);

            // Set the controller object.
            $this->_controllerObject = $controllerObject;

            // Set the root directory path.
            $this->_setRootDirectory(APP_PATH 
                                    . DIRECTORY_SEPARATOR 
                                    . APP_NAMESPACE);

            // Set view properties that have been passd in.
            foreach($props as $key => $value){
                $this->$key = $value;
            }
        }

        /**
         * Setter for @link $_moduleDirName
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $name
         * @return \MvcFramework\View\View
         */
        public function setModuleName($name)
        {
            // Format to UC first.
            $name = ucfirst($name);

            $this->_moduleDirName = $name;

            /*
            if ('Core' == $name){
                $this->_setModuleSubDirectoryName('Mvc');
            }
            */

            return $this;
        }

        /**
         * Setter for @link $_controllerDirName
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $name
         * @return \MvcFramework\View\View
         */
        public function setControllerName($name)
        {
            // Format to UC first.
            $name = ucfirst($name);

            $this->_controllerDirName = $name;

            return $this;
        }

        /**
         * Setter for @link $_viewFilePrefix
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $name
         * @return \MvcFramework\View\View
         */
        public function setViewPrefix($prefix)
        {
            $this->_viewFilePrefix = $prefix;

            return $this;
        }

        /**
         * Setter for @link $_viewFileName
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $name
         * @return \MvcFramework\View\View
         */
        public function setViewName($name)
        {
            // Lowercase.
            $name = strtolower($name);

            // Replace backslashes with forward slashes.
            $name = str_replace('\\', '/', $name);

            // Remove any upwards directory changing values.
            $name = str_replace(array('../', '..'), null, $name);

            // Set the View file name.
            $this->_viewFileName = $name;

            return $this;
        }

        /**
         * Returns the calculated view file to render. 
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getAbsoluteFilePath()
        {
            return $this->_calculateAbsoluteFilePath();
        }

        /**
         * Returns TRUE if the calculated view file can be rendered. FALSE
         * otherwise. 
         *
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        public function canRender()
        {
            $pathToRender = $this->getAbsoluteFilePath();

            if ( (!is_file($pathToRender)) || (!is_readable($pathToRender)) ) {
                return false;
            }

            return true;
        }

        /**
         * Renders the view file calculated by @link _calculateAbsoluteFilePath()
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $actionCustomViewFileName   For rendering a specific
         *                                               view file from within
         *                                               an action. E.g:
         *                                               myAction()
         *                                               {
         *                                                  $this->_view->render('my-custom-view-file');
         *                                               } 
         * @throws ViewException
         * @return void
         */
        public function render($actionCustomViewFileName = null,
                                $outputBuffer = false)
        {
            if (!is_null($actionCustomViewFileName)){
                $this->setModuleName($this->_controllerObject->getRequest()->getModuleName());
                $this->setControllerName($this->_controllerObject->getRequest()->getControllerName());
                $this->setViewName($actionCustomViewFileName);
            }

            $customViewRendered = false;

            if (null != Core::getBootstrap()->getChildThemeLocation()){                

                $pathToRender = $this->_calculateChildThemeAbsoluteFilePath();

                if ( (is_file($pathToRender)) || (is_readable($pathToRender)) ) {

                    Core::getInstance()->setViewRendered(true);
                    $customViewRendered = true;

                    // Render the View.
                    if ($outputBuffer){
                        ob_start();
                        include $pathToRender;
                        $viewScript = ob_get_contents();
                        ob_end_clean();
                        return $viewScript;
                    }else{
                        include $pathToRender;
                    }
                    
                }
            }

            if (!$customViewRendered){

                $pathToRender = $this->getAbsoluteFilePath();

                if ( (!is_file($pathToRender)) || (!is_readable($pathToRender)) ) {
                    throw new ViewException(sprintf(
                        "View file '%s' doesn't exist or not readable",
                        $pathToRender
                    ));
                }else{
                   Core::getInstance()->setViewRendered(true);
                }

                // Render the View.
                if ($outputBuffer){
                    ob_start();
                    include $pathToRender;
                    $viewScript = ob_get_contents();
                    ob_end_clean();
                    return $viewScript;
                }else{
                    include $pathToRender;
                }
            }
        }

        /**
         * Renders the a custom view file.
         *
         * @access public
         * @author Jon Matthews
         * @param  string  $scriptPath relative path to the script.
         * @throws ViewException
         * @return string
         */
        public function renderScript($scriptPath)
        {
            $pathToRender = APP_PATH 
                            . DIRECTORY_SEPARATOR 
                            . APP_NAMESPACE 
                            . DIRECTORY_SEPARATOR
                            . $scriptPath;

            if ( (!is_file($pathToRender)) || (!is_readable($pathToRender)) ) {
                throw new ViewException(sprintf(
                    "View file '%s' doesn't exist or not readable",
                    $pathToRender
                ));
            }else{
               Core::getInstance()->setViewRendered(true);
            }

            include $pathToRender;
        }

        /**
         * The magic offsetSet method is used for setting View class properties for
         * access within the view files.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string  $key    The key of the property
         * @param   mixed   $value  The value of the property
         * @return  void
         */
        public function offsetSet($key, $value)
        {
            if ('_' != substr($key, 0, 1)) {
                return parent::offsetSet($key, $value);
                return;
            }

            throw new ViewException('Setting private or protected 
                                                class members on the View object 
                                                is not allowed');

        }

        /**
         * The magic offsetGet method is used for supressing E_NOTICE errors when
         * accessing undefined View properties from within a view file.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string  $key    The name of the property to return
         * @return  mixed|NULL
         */
        public function offsetGet($key)
        {
            if (!parent::offsetExists($key)){
                return null;
            }
            return parent::offsetGet($key);
        }

        /**
         * The magic offsetExists method is used for checking if a key exists 
         * within the Array
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string  $key  The key name
         * @return  boolean
         */
        public function offsetExists($key)
        {
            if (parent::offsetExists($key)){
                return true;
            }
            return false;
        } 

        /**
         * The magic __call method provides methods for loading and running
         * View Helpers.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string  $methodName    The name of the view helper to run.
         * @param   mixed   $args          The arguments sent to the view helper.
         * @throws  ViewException
         * @return  mixed|NULL
         */
        public function __call($methodName, $args)
        {
            // Construct the View Helper class name.
            $className = ucfirst($methodName);

            // Array of class names to search for.
            $searchFor = array();            

            // (1) The Core module.
            $searchFor[] = '\\' 
                            . APP_NAMESPACE 
                            . '\\Core\\View\\Helper\\' 
                            . $className;

            // (2) The registered modules.
            $modules = Core::getBootstrap()->getModules();
            foreach($modules as $module){
                $searchFor[] = '\\' 
                            . APP_NAMESPACE 
                            . '\\' 
                            . $module->getName()
                            . '\\View\\Helper\\' 
                            . $className;
            }

            // (3) The Framework.
            $searchFor[] = '\\MvcFramework\\View\\Helper\\' 
                            . $className;

            // Debug:
            /*
            echo '<pre>';
            print_r($searchFor);
            echo '</pre>';
            exit();
            */

            $helperToInstantiate = false;

            foreach ($searchFor as $helperClassName){
                if (class_exists($helperClassName)) {
                    $helperToInstantiate = $helperClassName;
                    break;
                }
            }

            // Was the helper found?
            if (!$helperToInstantiate){

                $base  = null;
                $base .= 'View Helper not found. Looked for: ';

                $base .= implode(', ', $searchFor);

                throw new ViewException($base);
            }
           

            // Instantiate the View Helper.
            $viewHelperObject = new $helperToInstantiate($this);

            // Call the View Helper method...
            $methodVariable = array($viewHelperObject, $methodName);

            if( (is_callable($methodVariable, true, $callableName)) 
                    && (method_exists($viewHelperObject, $methodName)) ){

                return call_user_func_array(array($viewHelperObject, $methodName), 
                                                                            $args);

            }else{

                // View Helper not found.
                throw new ViewException('View Helper Method "' 
                                                . $methodName 
                                                . '" not found within "' 
                                                . $helperToInstantiate 
                                                . '"');

            }
        }

        /**
         * The getPartial method renders a partial view file. Partial file names
         * are prefixed with an underscore (_).
         *
         * Partials can exists within any of the calculated directories. See
         * method body for locations.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string  $viewFileName    The name of the view partial.
         * @throws  ViewException
         * @return  mixed|NULL
         */
        public function getPartial($viewFileName, array $partialProperties = array())
        {
            // Get the current View's public properties.
            //$props = get_object_vars($this);

            // ..no dont, only use $partialProperties.
            $props = $partialProperties;

            // Debug:
            /*
            echo 'loading partial ' . $viewFileName;
            echo ' from ' . spl_object_hash($this);
            echo '<br>';

            echo '<pre>';
            print_r($props);
            echo '</pre>';
            */

            $partials = array();

            // Construct paths to search in (in order)

            // Firstly look in the current module/controller view dir.
            // /<Namespace>/<CurrentModule>/View/<CurrentController>/_<viewFile>.phtml
            $partial = new self($this->_controllerObject, $props);

            $partial->setViewPrefix('_');
            
            $partial->setModuleName($partial->_controllerObject->getRequest()->getModuleName())
                            ->setControllerName($partial->_controllerObject->getRequest()->getControllerName())
                            ->setViewName($viewFileName);

            $partials[] = $partial;

            // Secondly look in the current module view dir.
            // /<Namespace>/<CurrentModule>/View/_<viewFile>.phtml
            $partial = new self($this->_controllerObject, $props);

            $partial->setViewPrefix('_');
            
            $partial->setModuleName($partial->_controllerObject->getRequest()->getModuleName())
                            ->setViewName($viewFileName);

            $partials[] = $partial;

            // Thirdly look in the app's Core dir.
            $partial = new self($this->_controllerObject, $props);

            $partial->setViewPrefix('_');
            
            $partial->setModuleName('Core')
                            ->setViewName($viewFileName);

            $partials[] = $partial;

            // Fourthly look in the app's registered module dirs.
            $modules = Core::getBootstrap()->getModules();
            foreach($modules as $module){

                // Don't bother adding the current module as we've just done
                // this above.
                if ($module->getName() == $this->_controllerObject->getRequest()->getModuleName()){
                    continue;
                }

                $partial = new self($this->_controllerObject, $props);

                $partial->setViewPrefix('_');
                
                $partial->setModuleName($module->getName())
                                ->setViewName($viewFileName);

                $partials[] = $partial;

            }

            // Lastly look in the framework's dir.
            // TO DO? View API dioesnt currently support rendering View files
            // outside of the app's root dir.

            $detected = false;
            foreach($partials as $partial){

                // Debug:
                /*
                echo $partial->getAbsoluteFilePath();
                echo '<br>';
                */

                if ($partial->canRender()){
                    $viewScript = $partial->render(null, true);
                    $detected = true;
                    break;
                }
            }

            // Partial not found?
            if (!$detected){
                $base  = null;
                $base .= 'View partial "' . $viewFileName . '" not found. Searched: ';

                foreach($partials as $partial){
                    $base .= '"' . $partial->getAbsoluteFilePath() . '", ';
                }

                throw new HelperException($base);
            }else{
                return $viewScript;
            }
        }

        /**
         * Setter for @link $_rootDirPath
         *
         * @access private
         * @author  Jon Matthews
         * @param   string  $dir
         * @return \MvcFramework\View\View
         */
        private function _setRootDirectory($dir)
        {
            $this->_rootDirPath = $dir;

            return $this;
        }

        /**
         * Setter for @link $_moduleSubFolderDirName
         *
         * @access private
         * @author  Jon Matthews
         * @param   string  $name
         * @return \MvcFramework\View\View
         */
        private function _setModuleSubDirectoryName($name)
        {
            $this->_moduleSubFolderDirName = $name;

            return $this;
        }

        /**
         * Calculates the file path to render.
         *
         * @access private
         * @author  Jon Matthews
         * @return string
         */
        private function _calculateAbsoluteFilePath()
        {
            if (is_null($this->_moduleDirName)){
                throw new ViewException('Cannot calculate View file.
                                                No Module name set. 
                                                Set with 
                                                \MvcFramework\Mvc::setModuleName().');
            }

            if (is_null($this->_viewFileName)){
                throw new ViewException('Cannot calculate View file.
                                                No view file set. 
                                                Set with 
                                                \MvcFramework\Mvc::setViewName().');
            }

            $absoluteFilePath  = null;
            $absoluteFilePath .= $this->_rootDirPath
                                    . DIRECTORY_SEPARATOR
                                    . $this->_moduleDirName
                                    . DIRECTORY_SEPARATOR;

            if (strlen($this->_moduleSubFolderDirName) >= 1){
                $absoluteFilePath .= $this->_moduleSubFolderDirName 
                                        . DIRECTORY_SEPARATOR;
            }

            $absoluteFilePath .= $this->_viewDirName 
                                        . DIRECTORY_SEPARATOR;

            if (strlen($this->_controllerDirName) >= 1){
                $absoluteFilePath .= $this->_controllerDirName
                                        . DIRECTORY_SEPARATOR;
            }

            // ONly add the prefix if there isnt a forward slash in the 
            // view file script.
            if ( (strlen($this->_viewFilePrefix) >= 1) 
                    && (!preg_match('/\//', $this->_viewFileName))){
                $viewFileName = $this->_viewFilePrefix . $this->_viewFileName;
            }else{
                $viewFileName = $this->_viewFileName;
            }

            $absoluteFilePath .= $viewFileName;

            // Filter the path.
            $filter = new \MvcFramework\Filter\UniversalizePath;

            $absoluteFilePath = $filter->filter($absoluteFilePath);

            $absoluteFilePath .= $this->_viewFileExtension;

            return $absoluteFilePath;
        }

        /**
         * Calculates the file path of a child theme view file to render.
         *
         * @access private
         * @author  Jon Matthews
         * @return string
         */
        private function _calculateChildThemeAbsoluteFilePath()
        {
            if (is_null($this->_moduleDirName)){
                throw new ViewException('Cannot calculate View file.
                                                No Module name set. 
                                                Set with 
                                                \MvcFramework\Mvc::setModuleName().');
            }

            if (is_null($this->_viewFileName)){
                throw new ViewException('Cannot calculate View file.
                                                No view file set. 
                                                Set with 
                                                \MvcFramework\Mvc::setViewName().');
            }

            $absoluteFilePath  = null;
            $absoluteFilePath .= Core::getBootstrap()->getChildThemeLocation()
                                    . DIRECTORY_SEPARATOR
                                    . 'templates'
                                    . DIRECTORY_SEPARATOR
                                    . strtolower($this->_moduleDirName)
                                    . DIRECTORY_SEPARATOR;

            if (strlen($this->_controllerDirName) >= 1){
                $absoluteFilePath .= strtolower($this->_controllerDirName)
                                        . DIRECTORY_SEPARATOR;
            }

            if (strlen($this->_viewFilePrefix) >= 1){
                $absoluteFilePath .= strtolower($this->_viewFilePrefix);
            }

            $absoluteFilePath .= $this->_viewFileName;

            // Filter the path.
            $filter = new \MvcFramework\Filter\UniversalizePath;

            $absoluteFilePath = $filter->filter($absoluteFilePath);

            $absoluteFilePath .= $this->_viewFileExtension;

            return $absoluteFilePath;
        }
    }
}