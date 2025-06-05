<?php

/**
 * Simple test runner for SwitchInput widget tests
 * This allows running tests without full Yii2 framework setup
 */

// Bootstrap
require_once __DIR__ . '/bootstrap.php';

/**
 * Simple test result tracker
 */
class SimpleTestRunner
{
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    private $errors = [];

    public function run()
    {
        echo "🔧 Running SwitchInput Widget Tests\n";
        echo "=====================================\n\n";

        $this->testBasicConfiguration();
        $this->testConstants();
        $this->testPropertyAssignments();
        $this->testTristateWithCustomIndeterminateValue();
        $this->testValidation();
        $this->testMockHtmlHelpers();

        $this->displayResults();
    }

    private function testBasicConfiguration()
    {
        echo "📋 Testing basic configuration...\n";

        try {
            $widget = new \kartik\switchinput\SwitchInput();

            $this->assert($widget->type === 1, "Default type should be CHECKBOX (1)");
            $this->assert($widget->tristate === false, "Default tristate should be false");
            $this->assert($widget->indeterminateValue === null, "Default indeterminateValue should be null");
            $this->assert($widget->inlineLabel === true, "Default inlineLabel should be true");
            $this->assert($widget->pluginName === 'bootstrapSwitch', "Default pluginName should be 'bootstrapSwitch'");
            $this->assert($widget->separator === " &nbsp;", "Default separator should be ' &nbsp;'");

            echo "✅ Basic configuration tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Basic configuration test failed: " . $e->getMessage());
        }
    }

    private function testConstants()
    {
        echo "🔢 Testing constants...\n";

        try {
            $this->assert(\kartik\switchinput\SwitchInput::CHECKBOX === 1, "CHECKBOX constant should be 1");
            $this->assert(\kartik\switchinput\SwitchInput::RADIO === 2, "RADIO constant should be 2");

            echo "✅ Constants tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Constants test failed: " . $e->getMessage());
        }
    }

    private function testPropertyAssignments()
    {
        echo "⚙️  Testing property assignments...\n";

        try {
            $widget = new \kartik\switchinput\SwitchInput();

            // Test type assignment
            $widget->type = \kartik\switchinput\SwitchInput::RADIO;
            $this->assert($widget->type === 2, "Type should be assignable to RADIO (2)");

            // Test tristate assignment
            $widget->tristate = true;
            $this->assert($widget->tristate === true, "Tristate should be assignable to true");

            // Test items assignment
            $items = [
                ['label' => 'Option 1', 'value' => '1'],
                ['label' => 'Option 2', 'value' => '2']
            ];
            $widget->items = $items;
            $this->assert(count($widget->items) === 2, "Items should be assignable and countable");
            $this->assert($widget->items[0]['label'] === 'Option 1', "Items should retain their structure");

            echo "✅ Property assignment tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Property assignment test failed: " . $e->getMessage());
        }
    }

    private function testValidation()
    {
        echo "🔍 Testing validation...\n";

        try {
            $widget = new \kartik\switchinput\SwitchInput();

            // Configure the widget properly for testing
            $widget->options = ['id' => 'test-widget', 'name' => 'test'];
            $widget->containerOptions = ['class' => 'form-group'];

            // Test invalid type validation
            $widget->type = 3; // Invalid type
            $exceptionThrown = false;
            try {
                $widget->run();
            } catch (\yii\base\InvalidConfigException $e) {
                $exceptionThrown = true;
                $this->assert(
                    strpos($e->getMessage(), "valid 'type'") !== false,
                    "Exception message should mention valid type"
                );
            }
            $this->assert($exceptionThrown, "Invalid type should throw InvalidConfigException");

            // Test radio without items validation
            $widget2 = new \kartik\switchinput\SwitchInput();
            $widget2->type = \kartik\switchinput\SwitchInput::RADIO;
            $widget2->items = [];
            $widget2->options = ['id' => 'test-radio', 'name' => 'test-radio'];
            $widget2->containerOptions = ['class' => 'form-group'];
            $exceptionThrown = false;
            try {
                $widget2->run();
            } catch (\yii\base\InvalidConfigException $e) {
                $exceptionThrown = true;
                $this->assert(
                    strpos($e->getMessage(), "items") !== false,
                    "Exception message should mention items"
                );
            }
            $this->assert($exceptionThrown, "Radio without items should throw InvalidConfigException");

            echo "✅ Validation tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Validation test failed: " . $e->getMessage());
        }
    }

    private function testTristateWithCustomIndeterminateValue()
    {
        echo "🔄 Testing tristate with custom indeterminate value...\n";

        try {
            $widget = new \kartik\switchinput\SwitchInput();

            // Configure tristate with custom indeterminate value (2)
            $widget->type = \kartik\switchinput\SwitchInput::CHECKBOX;
            $widget->tristate = true;
            $widget->indeterminateValue = 2;
            $widget->value = 2;
            $widget->options = ['id' => 'tristate-custom-test', 'name' => 'tristate-custom'];
            $widget->containerOptions = ['class' => 'form-group'];
            $widget->pluginOptions = [];
            $widget->disabled = false;
            $widget->readonly = false;

            // Test configuration
            $this->assert($widget->tristate === true, "Tristate should be enabled");
            $this->assert($widget->indeterminateValue === 2, "Indeterminate value should be 2");
            $this->assert($widget->value === 2, "Widget value should be 2 (indeterminate)");

            // Test registerAssets to verify indeterminate detection
            $widget->registerAssets();
            $this->assert(
                $widget->pluginOptions['indeterminate'] === true,
                "Plugin options should indicate indeterminate state when value equals indeterminateValue"
            );

            // Test with different values
            $widget->value = 0;
            $widget->pluginOptions = []; // Reset
            $widget->registerAssets();
            $this->assert(
                $widget->pluginOptions['indeterminate'] === false,
                "Plugin options should not indicate indeterminate state when value is 0"
            );

            $widget->value = 1;
            $widget->pluginOptions = []; // Reset
            $widget->registerAssets();
            $this->assert(
                $widget->pluginOptions['indeterminate'] === false,
                "Plugin options should not indicate indeterminate state when value is 1"
            );

            echo "✅ Tristate with custom indeterminate value tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Tristate with custom indeterminate value test failed: " . $e->getMessage());
        }
    }

    private function testMockHtmlHelpers()
    {
        echo "🔧 Testing mock HTML helpers...\n";

        try {
            // Test checkbox generation
            $checkbox = \yii\helpers\Html::checkbox('test', true, ['id' => 'test-cb']);
            $this->assert(
                strpos($checkbox, 'type="checkbox"') !== false,
                "Mock Html::checkbox should generate checkbox input"
            );
            $this->assert(
                strpos($checkbox, 'checked') !== false,
                "Mock Html::checkbox should include checked attribute when true"
            );

            // Test hidden input generation
            $hidden = \yii\helpers\Html::hiddenInput('test', 'value', ['id' => 'test-hidden']);
            $this->assert(
                strpos($hidden, 'type="hidden"') !== false,
                "Mock Html::hiddenInput should generate hidden input"
            );
            $this->assert(
                strpos($hidden, 'value="value"') !== false,
                "Mock Html::hiddenInput should include value attribute"
            );

            // Test radio generation
            $radio = \yii\helpers\Html::radio('test', false, ['value' => '1']);
            $this->assert(
                strpos($radio, 'type="radio"') !== false,
                "Mock Html::radio should generate radio input"
            );

            echo "✅ Mock HTML helpers tests passed\n\n";
        } catch (Exception $e) {
            $this->addError("Mock HTML helpers test failed: " . $e->getMessage());
        }
    }

    private function assert($condition, $message)
    {
        if ($condition) {
            $this->passed++;
            echo "  ✓ {$message}\n";
        } else {
            $this->failed++;
            echo "  ✗ {$message}\n";
            $this->errors[] = $message;
        }
    }

    private function addError($error)
    {
        $this->failed++;
        $this->errors[] = $error;
        echo "  ✗ {$error}\n";
    }

    private function displayResults()
    {
        echo "=====================================\n";
        echo "📊 Test Results\n";
        echo "=====================================\n";
        echo "✅ Passed: {$this->passed}\n";
        echo "❌ Failed: {$this->failed}\n";
        echo "📈 Total: " . ($this->passed + $this->failed) . "\n\n";

        if ($this->failed > 0) {
            echo "❌ Errors:\n";
            foreach ($this->errors as $error) {
                echo "  • {$error}\n";
            }
            exit(1);
        } else {
            echo "🎉 All tests passed!\n";
            exit(0);
        }
    }
}

// Run the tests
$runner = new SimpleTestRunner();
$runner->run();
