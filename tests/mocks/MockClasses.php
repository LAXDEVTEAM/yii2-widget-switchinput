<?php

/**
 * Mock classes for testing without full Yii2 framework
 */

namespace yii\base {
    /**
     * Mock InvalidConfigException
     */
    class InvalidConfigException extends \Exception {}
}

namespace yii\helpers {
    /**
     * Mock Html helper
     */
    class Html
    {
        public static function checkbox($name, $checked = false, $options = [])
        {
            $id = isset($options['id']) ? $options['id'] : $name;
            $checkedAttr = $checked ? ' checked' : '';
            return "<input type=\"checkbox\" id=\"{$id}\" name=\"{$name}\"{$checkedAttr}>";
        }

        public static function hiddenInput($name, $value = null, $options = [])
        {
            $id = isset($options['id']) ? $options['id'] : $name;
            return "<input type=\"hidden\" id=\"{$id}\" name=\"{$name}\" value=\"{$value}\">";
        }

        public static function radio($name, $checked = false, $options = [])
        {
            $value = isset($options['value']) ? $options['value'] : '';
            $checkedAttr = $checked ? ' checked' : '';
            return "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\"{$checkedAttr}>";
        }

        public static function label($content, $for = null, $options = [])
        {
            $forAttr = $for ? " for=\"{$for}\"" : '';
            return "<label{$forAttr}>{$content}</label>";
        }

        public static function tag($name, $content = '', $options = [])
        {
            $attrs = self::renderTagAttributes($options);
            return "<{$name}{$attrs}>{$content}</{$name}>";
        }

        public static function addCssClass(&$options, $class)
        {
            if (isset($options['class'])) {
                $options['class'] .= ' ' . $class;
            } else {
                $options['class'] = $class;
            }
        }

        public static function getInputName($model, $attribute)
        {
            return get_class($model) . '[' . $attribute . ']';
        }

        private static function renderTagAttributes($attributes)
        {
            $html = '';
            foreach ($attributes as $name => $value) {
                if ($value !== null && $value !== false) {
                    $html .= " {$name}=\"{$value}\"";
                }
            }
            return $html;
        }
    }

    /**
     * Mock ArrayHelper
     */
    class ArrayHelper
    {
        public static function getValue($array, $key, $default = null)
        {
            return isset($array[$key]) ? $array[$key] : $default;
        }

        public static function merge($a, $b)
        {
            return array_merge($a, $b);
        }

        public static function remove(&$array, $key, $default = null)
        {
            $value = isset($array[$key]) ? $array[$key] : $default;
            unset($array[$key]);
            return $value;
        }
    }
}

namespace yii\web {
    /**
     * Mock View class
     */
    class View
    {
        public function registerJs($js)
        {
            // Mock implementation
        }
    }
}

namespace kartik\base {
    /**
     * Mock InputWidget base class
     */
    class InputWidget
    {
        public $options = [];
        public $name;
        public $value;
        public $model;
        public $attribute;
        public $pluginOptions = [];
        public $pluginName;
        public $disabled = false;
        public $readonly = false;

        public function run()
        {
            // Mock implementation
        }

        public function getView()
        {
            return new \yii\web\View();
        }

        public function registerPlugin($name, $selector)
        {
            // Mock implementation
        }

        public function getInput($type)
        {
            return "<input type=\"{$type}\" name=\"{$this->name}\" value=\"{$this->value}\">";
        }

        public function hasModel()
        {
            return $this->model !== null;
        }
    }

    /**
     * Mock AssetBundle class
     */
    class AssetBundle
    {
        public $css = [];
        public $js = [];
        public $depends = [];

        public function init()
        {
            // Mock implementation
        }

        public static function register($view)
        {
            // Mock implementation
        }

        protected function setSourcePath($path)
        {
            // Mock implementation
        }

        protected function setupAssets($type, $files)
        {
            // Mock implementation
        }
    }
}
