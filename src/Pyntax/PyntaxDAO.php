<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 11/08/15
 * Time: 3:51 PM
 */

namespace Pyntax;

use Pyntax\Config\Config;
use Pyntax\DAO\Adapter\MySqlAdapter;
use Pyntax\DAO\Bean\BeanFactory;
use Pyntax\Html\Element\Element;

class PyntaxDAO
{
    static $factory = null;

    /**
     * @param $beanName
     * @return bool
     */
    public static function getBean($beanName) {
        if(is_null(self::$factory)) {
            self::loadFactory();
        }

        if(self::$factory instanceof BeanFactory) {
            return self::$factory->getBean($beanName);
        }

        return false;
    }

    /**
     * @param $elementName
     * @param $value
     * @param array $attributes
     *
     * @return Element
     */
    public static function generateHtmlElement($elementName, $value, array $attributes = array()) {
        $el = new Element($elementName);
        $el->setValue($value);

        return $el;
    }

    /**
     * @return bool
     */
    private static function loadFactory()
    {
        $config = new Config();
        $db_config = $config->readConfig('database');

        $pdo = null;

        if(!empty($db_config['server']) && !empty($db_config['database'])) {
            $user = (isset($db_config['user'])) ? $db_config['user'] : 'root';
            $password = (isset($db_config['password'])) ? $db_config['password'] : "";

            $pdo = new \PDO('mysql:host='.$db_config['server'].';dbname='.$db_config['database'].';charset=utf8', $user, $password);
        }

        if(!is_null($pdo)) {
            self::$factory = new BeanFactory(new MySqlAdapter($pdo));
            return true;
        }

        return false;
    }
}